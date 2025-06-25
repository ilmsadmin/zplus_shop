<?php

namespace Zplus\ViPOS\Database\Seeders;

use Illuminate\Database\Seeder;
use Zplus\ViPOS\Models\PosSession;
use Zplus\ViPOS\Models\PosTransaction;
use Zplus\ViPOS\Models\PosTransactionItem;
use Zplus\ViPOS\Models\PosCashMovement;
use Webkul\User\Models\Admin;
use Webkul\Customer\Models\Customer;
use Webkul\Product\Models\Product;
use Carbon\Carbon;

class PosTestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Seeding POS test data...');
        
        // Skip seeding if no admin users exist
        $admin = Admin::first();
        if (!$admin) {
            $this->command->warn('No admin users found. Skipping POS seeder.');
            return;
        }

        // Get some customers (skip if none exist)
        $customers = Customer::take(3)->get();
        
        // Get some products (skip if none exist)
        $products = Product::where('status', 1)->take(10)->get();
        if ($products->isEmpty()) {
            $this->command->warn('No products found. Skipping POS seeder.');
            return;
        }

        // Create some POS sessions
        $this->createSessions($admin, $customers, $products);
        
        $this->command->info('POS test data seeded successfully!');
    }

    private function createSessions($admin, $customers, $products)
    {
        // Create a closed session from yesterday
        $closedSession = PosSession::create([
            'user_id' => $admin->id,
            'store_id' => 1,
            'opening_balance' => 1000000, // 1M VND
            'closing_balance' => 1500000, // 1.5M VND
            'total_sales' => 500000,
            'total_cash' => 300000,
            'total_card' => 200000,
            'total_other' => 0,
            'transaction_count' => 3,
            'opened_at' => Carbon::yesterday()->hour(8),
            'closed_at' => Carbon::yesterday()->hour(18),
            'status' => 'closed',
            'notes' => 'Session test từ ngày hôm qua'
        ]);

        // Create transactions for closed session
        $this->createTransactionsForSession($closedSession, $admin, $customers, $products, 3);

        // Create an open session for today
        $openSession = PosSession::create([
            'user_id' => $admin->id,
            'store_id' => 1,
            'opening_balance' => 1500000, // 1.5M VND
            'closing_balance' => null,
            'total_sales' => 200000,
            'total_cash' => 150000,
            'total_card' => 50000,
            'total_other' => 0,
            'transaction_count' => 1,
            'opened_at' => Carbon::today()->hour(8),
            'closed_at' => null,
            'status' => 'open',
            'notes' => 'Session hiện tại đang mở'
        ]);

        // Create one transaction for open session
        $this->createTransactionsForSession($openSession, $admin, $customers, $products, 1);
    }

    private function createTransactionsForSession($session, $admin, $customers, $products, $count)
    {
        for ($i = 0; $i < $count; $i++) {
            $customer = $customers->isNotEmpty() ? $customers->random() : null;
            $selectedProducts = $products->random(rand(1, 3));
            
            $subtotal = 0;
            $items = [];
            
            foreach ($selectedProducts as $product) {
                $quantity = rand(1, 3);
                $price = $product->price ?? rand(50000, 500000);
                $total = $quantity * $price;
                $subtotal += $total;
                
                $items[] = [
                    'product_id' => $product->id,
                    'sku' => $product->sku,
                    'name' => $product->name ?? 'Test Product',
                    'quantity' => $quantity,
                    'price' => $price
                ];
            }

            $discount = rand(0, $subtotal * 0.1); // Max 10% discount
            $tax = 0; // No tax for simplicity
            $total = $subtotal - $discount + $tax;

            // Create transaction
            $transaction = PosTransaction::create([
                'pos_session_id' => $session->id,
                'user_id' => $admin->id,
                'customer_id' => $customer?->id,
                'payment_method' => ['cash', 'card', 'bank_transfer'][rand(0, 2)],
                'subtotal_amount' => $subtotal,
                'discount_amount' => $discount,
                'discount_percentage' => $subtotal > 0 ? ($discount / $subtotal) * 100 : 0,
                'tax_amount' => $tax,
                'total_amount' => $total,
                'paid_amount' => $total,
                'change_amount' => 0,
                'status' => 'completed',
                'completed_at' => $session->status === 'open' ? Carbon::now() : 
                    Carbon::create($session->opened_at)->addHours(rand(1, 8)),
                'notes' => 'Giao dịch test #' . ($i + 1),
                'items' => $items
            ]);

            // Create transaction items
            foreach ($items as $item) {
                PosTransactionItem::create([
                    'pos_transaction_id' => $transaction->id,
                    'product_id' => $item['product_id'],
                    'product_sku' => $item['sku'],
                    'product_name' => $item['name'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'total' => $item['quantity'] * $item['price']
                ]);
            }

            // Create cash movement
            PosCashMovement::create([
                'pos_session_id' => $session->id,
                'user_id' => $admin->id,
                'pos_transaction_id' => $transaction->id,
                'amount' => $total,
                'type' => 'sale',
                'reference' => $transaction->transaction_number,
                'description' => 'Bán hàng POS - Giao dịch #' . ($i + 1),
                'movement_at' => $transaction->completed_at
            ]);
        }
    }
}
