<?php

namespace Zplus\ViPOS\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Webkul\User\Models\Admin;
use Webkul\Customer\Models\Customer;
use Webkul\Product\Models\Product;
use Webkul\Sales\Models\Order;
use Zplus\ViPOS\Models\PosSession;
use Zplus\ViPOS\Models\PosTransaction;
use Zplus\ViPOS\Services\BagistoOrderService;

class BagistoOrderIntegrationTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Setup default admin and customer
        $this->admin = Admin::factory()->create();
        $this->customer = Customer::factory()->create();
        
        // Setup products
        $this->product = Product::factory()->create([
            'sku' => 'TEST-PRODUCT-001',
            'name' => 'Test Product',
            'type' => 'simple',
            'status' => 1,
        ]);
    }

    /** @test */
    public function it_creates_bagisto_order_from_pos_transaction()
    {
        // Create POS session
        $session = PosSession::factory()->create([
            'user_id' => $this->admin->id,
            'status' => 'open',
        ]);

        // Create POS transaction
        $transaction = PosTransaction::create([
            'transaction_number' => 'TX-' . time(),
            'pos_session_id' => $session->id,
            'customer_id' => $this->customer->id,
            'user_id' => $this->admin->id,
            'subtotal_amount' => 100.00,
            'discount_amount' => 0,
            'tax_amount' => 0,
            'total_amount' => 100.00,
            'paid_amount' => 100.00,
            'change_amount' => 0,
            'payment_method' => 'cash',
            'status' => 'completed',
            'completed_at' => now(),
            'items' => [
                [
                    'product_id' => $this->product->id,
                    'name' => $this->product->name,
                    'sku' => $this->product->sku,
                    'quantity' => 1,
                    'price' => 100.00,
                ]
            ]
        ]);

        // Create transaction items
        $transaction->items()->create([
            'product_id' => $this->product->id,
            'product_name' => $this->product->name,
            'product_sku' => $this->product->sku,
            'quantity' => 1,
            'price' => 100.00,
            'total' => 100.00,
        ]);

        // Test the service
        $bagistoOrderService = app(BagistoOrderService::class);
        $order = $bagistoOrderService->createOrderFromPosTransaction($transaction);

        // Assertions
        $this->assertNotNull($order);
        $this->assertInstanceOf(Order::class, $order);
        $this->assertEquals($transaction->total_amount, $order->grand_total);
        $this->assertEquals($this->customer->id, $order->customer_id);
        
        // Check if transaction is updated with order ID
        $transaction->refresh();
        $this->assertEquals($order->id, $transaction->bagisto_order_id);
    }

    /** @test */
    public function it_creates_guest_order_when_no_customer()
    {
        // Create POS session
        $session = PosSession::factory()->create([
            'user_id' => $this->admin->id,
            'status' => 'open',
        ]);

        // Create POS transaction without customer
        $transaction = PosTransaction::create([
            'transaction_number' => 'TX-' . time(),
            'pos_session_id' => $session->id,
            'customer_id' => null,
            'user_id' => $this->admin->id,
            'subtotal_amount' => 50.00,
            'total_amount' => 50.00,
            'paid_amount' => 50.00,
            'payment_method' => 'cash',
            'status' => 'completed',
            'completed_at' => now(),
        ]);

        // Create transaction items
        $transaction->items()->create([
            'product_id' => $this->product->id,
            'product_name' => $this->product->name,
            'product_sku' => $this->product->sku,
            'quantity' => 1,
            'price' => 50.00,
            'total' => 50.00,
        ]);

        // Test the service
        $bagistoOrderService = app(BagistoOrderService::class);
        $order = $bagistoOrderService->createOrderFromPosTransaction($transaction);

        // Assertions
        $this->assertNotNull($order);
        $this->assertEquals('guest@pos.local', $order->customer_email);
        $this->assertEquals('POS', $order->customer_first_name);
        $this->assertEquals('Guest', $order->customer_last_name);
    }

    /** @test */
    public function it_handles_service_errors_gracefully()
    {
        // Create invalid transaction (no items)
        $session = PosSession::factory()->create([
            'user_id' => $this->admin->id,
            'status' => 'open',
        ]);

        $transaction = PosTransaction::create([
            'transaction_number' => 'TX-' . time(),
            'pos_session_id' => $session->id,
            'user_id' => $this->admin->id,
            'subtotal_amount' => 0,
            'total_amount' => 0,
            'paid_amount' => 0,
            'payment_method' => 'cash',
            'status' => 'completed',
        ]);

        // Test the service with invalid data
        $bagistoOrderService = app(BagistoOrderService::class);
        $order = $bagistoOrderService->createOrderFromPosTransaction($transaction);

        // Should return null on error
        $this->assertNull($order);
        
        // Transaction should not have bagisto_order_id
        $transaction->refresh();
        $this->assertNull($transaction->bagisto_order_id);
    }
}
