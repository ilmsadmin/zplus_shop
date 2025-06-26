<?php

namespace Zplus\ViPOS\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;
use Webkul\Sales\Repositories\OrderRepository;
use Webkul\Customer\Repositories\CustomerRepository;
use Webkul\Core\Repositories\ChannelRepository;
use Webkul\Core\Repositories\CurrencyRepository;
use Webkul\Product\Repositories\ProductRepository;
use Webkul\Sales\Transformers\OrderResource;
use Zplus\ViPOS\Models\PosTransaction;

class BagistoOrderService
{
    public function __construct(
        protected OrderRepository $orderRepository,
        protected CustomerRepository $customerRepository,
        protected ChannelRepository $channelRepository,
        protected CurrencyRepository $currencyRepository,
        protected ProductRepository $productRepository
    ) {}

    /**
     * Create a Bagisto order from POS transaction
     *
     * @param PosTransaction $posTransaction
     * @return \Webkul\Sales\Contracts\Order|null
     */
    public function createOrderFromPosTransaction(PosTransaction $posTransaction)
    {
        try {
            DB::beginTransaction();

            // Get customer (guest if no customer)
            $customer = $posTransaction->customer;
            $isGuest = !$customer;

            // Get default channel and currency
            $channel = $this->channelRepository->find(core()->getCurrentChannel()->id);
            $baseCurrency = $this->currencyRepository->findOneByField('code', config('app.currency'));
            $channelCurrency = $channel->base_currency;

            // Prepare customer data
            $customerData = $this->prepareCustomerData($customer, $isGuest);

            // Prepare items data
            $itemsData = $this->prepareItemsData($posTransaction);

            // Prepare addresses (use default addresses or create guest address)
            $addressData = $this->prepareAddressData($customer, $isGuest, $posTransaction);

            // Prepare payment data
            $paymentData = $this->preparePaymentData($posTransaction);

            // Build order data structure matching Bagisto's OrderResource format
            $orderData = [
                'cart_id' => null, // POS orders don't have cart
                'is_guest' => $isGuest,
                'customer_id' => $customer?->id,
                'customer_type' => $customer ? get_class($customer) : null,
                'customer_email' => $customerData['customer_email'],
                'customer_first_name' => $customerData['customer_first_name'],
                'customer_last_name' => $customerData['customer_last_name'],
                'customer_gender' => $customerData['customer_gender'],
                'customer_date_of_birth' => $customerData['customer_date_of_birth'],
                'channel_id' => $channel->id,
                'channel_type' => get_class($channel),
                'channel_name' => $channel->name,
                'channel_currency_code' => $channelCurrency->code,
                'order_currency_code' => $channelCurrency->code,
                'base_currency_code' => $baseCurrency->code,
                'currency_exchange_rate' => 1.0,
                'total_item_count' => count($itemsData),
                'total_qty_ordered' => $this->calculateTotalQuantity($itemsData),
                'grand_total' => $posTransaction->total_amount,
                'base_grand_total' => $posTransaction->total_amount,
                'sub_total' => $posTransaction->subtotal_amount,
                'base_sub_total' => $posTransaction->subtotal_amount,
                'sub_total_incl_tax' => $posTransaction->subtotal_amount + $posTransaction->tax_amount,
                'base_sub_total_incl_tax' => $posTransaction->subtotal_amount + $posTransaction->tax_amount,
                'tax_amount' => $posTransaction->tax_amount,
                'base_tax_amount' => $posTransaction->tax_amount,
                'discount_amount' => $posTransaction->discount_amount,
                'base_discount_amount' => $posTransaction->discount_amount,
                'discount_percent' => $posTransaction->discount_percentage,
                'shipping_amount' => 0,
                'base_shipping_amount' => 0,
                'shipping_amount_incl_tax' => 0,
                'base_shipping_amount_incl_tax' => 0,
                'shipping_discount_amount' => 0,
                'base_shipping_discount_amount' => 0,
                'shipping_tax_amount' => 0,
                'base_shipping_tax_amount' => 0,
                'coupon_code' => null,
                'applied_cart_rule_ids' => null,
                'billing_address' => $addressData['billing'],
                'shipping_address' => $addressData['shipping'],
                'payment' => $paymentData,
                'items' => $itemsData,
                // POS specific fields for tracking
                'additional' => [
                    'pos_transaction_id' => $posTransaction->id,
                    'pos_transaction_number' => $posTransaction->transaction_number,
                    'pos_session_id' => $posTransaction->pos_session_id,
                    'created_from' => 'pos',
                ],
            ];

            // Create the order using Bagisto's OrderRepository
            $order = $this->orderRepository->create($orderData);

            // Update POS transaction with order reference
            $posTransaction->update([
                'bagisto_order_id' => $order->id,
            ]);

            DB::commit();

            return $order;

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to create Bagisto order from POS transaction: ' . $e->getMessage(), [
                'pos_transaction_id' => $posTransaction->id,
                'error' => $e->getTraceAsString()
            ]);
            return null;
        }
    }

    /**
     * Prepare customer data for order
     */
    protected function prepareCustomerData($customer, bool $isGuest): array
    {
        if ($isGuest) {
            return [
                'customer_email' => 'guest@pos.local',
                'customer_first_name' => 'POS',
                'customer_last_name' => 'Guest',
                'customer_gender' => 'Other',
                'customer_date_of_birth' => null,
            ];
        }

        return [
            'customer_email' => $customer->email,
            'customer_first_name' => $customer->first_name,
            'customer_last_name' => $customer->last_name,
            'customer_gender' => $customer->gender ?? 'Other',
            'customer_date_of_birth' => $customer->date_of_birth,
        ];
    }

    /**
     * Prepare items data for order
     */
    protected function prepareItemsData(PosTransaction $posTransaction): array
    {
        $itemsData = [];

        // Get items from relationship, fallback to stored JSON array
        $posItems = $posTransaction->items()->get();
        
        if ($posItems->isEmpty() && is_array($posTransaction->items)) {
            // Use stored items array if relationship is empty
            foreach ($posTransaction->items as $itemArray) {
                $product = $this->productRepository->find($itemArray['product_id']);
                
                if (!$product) {
                    continue;
                }

                $quantity = $itemArray['quantity'];
                $price = $itemArray['price'];
                $itemTotal = $quantity * $price;
                
                $itemsData[] = [
                    'product_id' => $product->id,
                    'product_type' => get_class($product),
                    'sku' => $product->sku,
                    'type' => $product->type,
                    'name' => $itemArray['name'],
                    'weight' => $product->weight ?? 0,
                    'total_weight' => ($product->weight ?? 0) * $quantity,
                    'qty_ordered' => $quantity,
                    'price' => $price,
                    'price_incl_tax' => $price,
                    'base_price' => $price,
                    'base_price_incl_tax' => $price,
                    'total' => $itemTotal,
                    'total_incl_tax' => $itemTotal,
                    'base_total' => $itemTotal,
                    'base_total_incl_tax' => $itemTotal,
                    'tax_percent' => 0,
                    'tax_amount' => 0,
                    'base_tax_amount' => 0,
                    'tax_category_id' => null,
                    'discount_percent' => 0,
                    'discount_amount' => 0,
                    'base_discount_amount' => 0,
                    'additional' => [
                        'locale' => core()->getCurrentLocale()->code,
                        'pos_item_from_array' => true,
                    ],
                    'children' => [],
                ];
            }
        } else {
            // Use relationship items
            foreach ($posItems as $posItem) {
                $product = $this->productRepository->find($posItem->product_id);
                
                if (!$product) {
                    continue;
                }

                $itemTotal = $posItem->quantity * $posItem->total; // Use total from relationship
                
                $itemsData[] = [
                    'product_id' => $product->id,
                    'product_type' => get_class($product),
                    'sku' => $posItem->product_sku,
                    'type' => $product->type,
                    'name' => $posItem->product_name,
                    'weight' => $product->weight ?? 0,
                    'total_weight' => ($product->weight ?? 0) * $posItem->quantity,
                    'qty_ordered' => $posItem->quantity,
                    'price' => $posItem->total, // Use total from POS item
                    'price_incl_tax' => $posItem->total,
                    'base_price' => $posItem->total,
                    'base_price_incl_tax' => $posItem->total,
                    'total' => $itemTotal,
                    'total_incl_tax' => $itemTotal,
                    'base_total' => $itemTotal,
                    'base_total_incl_tax' => $itemTotal,
                    'tax_percent' => 0,
                    'tax_amount' => 0,
                    'base_tax_amount' => 0,
                    'tax_category_id' => null,
                    'discount_percent' => 0,
                    'discount_amount' => 0,
                    'base_discount_amount' => 0,
                    'additional' => [
                        'locale' => core()->getCurrentLocale()->code,
                        'pos_item_id' => $posItem->id,
                    ],
                    'children' => [],
                ];
            }
        }

        return $itemsData;
    }

    /**
     * Prepare address data for order
     */
    protected function prepareAddressData($customer, bool $isGuest, PosTransaction $posTransaction): array
    {
        if (!$isGuest && $customer && $customer->addresses->count() > 0) {
            // Use customer's default address
            $defaultAddress = $customer->addresses->where('default_address', 1)->first() 
                           ?? $customer->addresses->first();
            
            $addressData = [
                'address_type' => 'customer_address',
                'first_name' => $defaultAddress->first_name,
                'last_name' => $defaultAddress->last_name,
                'gender' => $defaultAddress->gender,
                'company_name' => $defaultAddress->company_name,
                'address' => $defaultAddress->address,
                'city' => $defaultAddress->city,
                'state' => $defaultAddress->state,
                'country' => $defaultAddress->country,
                'postcode' => $defaultAddress->postcode,
                'email' => $defaultAddress->email ?? $customer->email,
                'phone' => $defaultAddress->phone,
                'vat_id' => $defaultAddress->vat_id,
            ];
        } else {
            // Create guest address or default address
            $addressData = [
                'address_type' => 'guest',
                'first_name' => 'POS',
                'last_name' => 'Guest',
                'gender' => 'Other',
                'company_name' => 'POS Store',
                'address' => 'In-store purchase',
                'city' => 'Store City',
                'state' => 'Store State',
                'country' => config('app.country', 'VN'),
                'postcode' => '10000',
                'email' => $customer?->email ?? 'guest@pos.local',
                'phone' => $customer?->phone ?? '0000000000',
                'vat_id' => null,
            ];
        }

        return [
            'billing' => array_merge($addressData, ['address_type' => 'billing']),
            'shipping' => array_merge($addressData, ['address_type' => 'shipping']),
        ];
    }

    /**
     * Prepare payment data for order
     */
    protected function preparePaymentData(PosTransaction $posTransaction): array
    {
        $methodMap = [
            'cash' => 'cashondelivery',
            'card' => 'moneytransfer',
            'bank_transfer' => 'moneytransfer',
            'other' => 'moneytransfer',
        ];

        $method = $methodMap[$posTransaction->payment_method] ?? 'cashondelivery';
        
        return [
            'method' => $method,
            'method_title' => ucfirst($posTransaction->payment_method),
            'additional' => [
                'pos_payment_method' => $posTransaction->payment_method,
                'pos_transaction_number' => $posTransaction->transaction_number,
                'paid_amount' => $posTransaction->paid_amount,
                'change_amount' => $posTransaction->change_amount,
            ],
        ];
    }

    /**
     * Calculate total quantity of items
     */
    protected function calculateTotalQuantity(array $itemsData): int
    {
        return array_sum(array_column($itemsData, 'qty_ordered'));
    }
}
