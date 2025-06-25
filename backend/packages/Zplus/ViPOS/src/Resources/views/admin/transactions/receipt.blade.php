<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hóa đơn POS - {{ $transaction->transaction_number }}</title>
    <link rel="stylesheet" href="{{ asset('packages/Zplus/ViPOS/assets/css/receipt.css') }}">
</head>
<body>
    <div class="receipt-container">
        <!-- Header -->
        <div class="header">
            <div class="store-name">
                {{ core()->getConfigData('sales.shipping.origin.store_name') ?: 'ViPOS Store' }}
            </div>
            <div class="store-info">
                {{ core()->getConfigData('sales.shipping.origin.address') }}
            </div>
            @if(core()->getConfigData('sales.shipping.origin.city') || core()->getConfigData('sales.shipping.origin.zipcode'))
                <div class="store-info">
                    {{ core()->getConfigData('sales.shipping.origin.zipcode') }} {{ core()->getConfigData('sales.shipping.origin.city') }}
                </div>
            @endif
            @if(core()->getConfigData('sales.shipping.origin.state') || core()->getConfigData('sales.shipping.origin.country'))
                <div class="store-info">
                    {{ core()->getConfigData('sales.shipping.origin.state') }}, {{ core()->getConfigData('sales.shipping.origin.country') }}
                </div>
            @endif
            <div class="receipt-title">HÓA ĐƠN BÁN HÀNG</div>
        </div>

        <!-- Transaction Info -->
        <div class="transaction-info">
            <div>
                <span>Mã giao dịch:</span>
                <span>{{ $transaction->transaction_number }}</span>
            </div>
            <div>
                <span>Ngày:</span>
                <span>{{ $transaction->created_at->format('d/m/Y H:i:s') }}</span>
            </div>
            <div>
                <span>Thu ngân:</span>
                <span>{{ $transaction->user->name ?? 'N/A' }}</span>
            </div>
            @if($transaction->customer)
                <div>
                    <span>Khách hàng:</span>
                    <span>{{ $transaction->customer->first_name }} {{ $transaction->customer->last_name }}</span>
                </div>
                @if($transaction->customer->phone)
                    <div>
                        <span>SĐT:</span>
                        <span>{{ $transaction->customer->phone }}</span>
                    </div>
                @endif
            @endif
        </div>

        <!-- Items -->
        <div class="items-section">
            <div class="items-header">
                <span>Sản phẩm</span>
                <span>SL x Giá</span>
                <span>Thành tiền</span>
            </div>
            @if($transaction->items && (is_array($transaction->items) ? count($transaction->items) > 0 : $transaction->items->count() > 0))
                @foreach($transaction->items as $item)
                    <div class="item">
                        @if(is_array($item))
                            <div class="item-name">{{ $item['product_name'] ?? $item['name'] ?? 'N/A' }}</div>
                            <div class="item-qty-price">{{ $item['quantity'] ?? 0 }} x {{ number_format($item['unit_price'] ?? $item['price'] ?? 0, 0, ',', '.') }}đ</div>
                            <div class="item-total">{{ number_format($item['total'] ?? 0, 0, ',', '.') }}đ</div>
                        @else
                            <div class="item-name">{{ $item->product_name ?? 'N/A' }}</div>
                            <div class="item-qty-price">{{ $item->quantity ?? 0 }} x {{ number_format($item->unit_price ?? 0, 0, ',', '.') }}đ</div>
                            <div class="item-total">{{ number_format($item->total ?? 0, 0, ',', '.') }}đ</div>
                        @endif
                    </div>
                @endforeach
            @else
                <div class="item">
                    <div class="item-name">Không có sản phẩm</div>
                    <div class="item-qty-price">0 x 0đ</div>
                    <div class="item-total">0đ</div>
                </div>
            @endif
        </div>

        <!-- Totals -->
        <div class="totals-section">
            <div class="total-line">
                <span>Tạm tính:</span>
                <span>{{ number_format($transaction->subtotal_amount, 0, ',', '.') }}đ</span>
            </div>
            
            @if($transaction->discount_amount > 0)
                <div class="total-line">
                    <span>Giảm giá:</span>
                    <span>-{{ number_format($transaction->discount_amount, 0, ',', '.') }}đ</span>
                </div>
            @endif
            
            @if($transaction->tax_amount > 0)
                <div class="total-line">
                    <span>Thuế:</span>
                    <span>{{ number_format($transaction->tax_amount, 0, ',', '.') }}đ</span>
                </div>
            @endif
            
            <div class="total-line grand-total">
                <span>TỔNG CỘNG:</span>
                <span>{{ number_format($transaction->total_amount, 0, ',', '.') }}đ</span>
            </div>
        </div>

        <!-- Payment Info -->
        <div class="payment-info">
            <div class="total-line">
                <span>Phương thức thanh toán:</span>
                <span>
                    @switch($transaction->payment_method)
                        @case('cash')
                            Tiền mặt
                            @break
                        @case('card')
                            Thẻ
                            @break
                        @case('bank_transfer')
                            Chuyển khoản
                            @break
                        @default
                            Khác
                    @endswitch
                </span>
            </div>
            <div class="total-line">
                <span>Tiền khách đưa:</span>
                <span>{{ number_format($transaction->paid_amount, 0, ',', '.') }}đ</span>
            </div>
            @if($transaction->change_amount > 0)
                <div class="total-line">
                    <span>Tiền thối:</span>
                    <span>{{ number_format($transaction->change_amount, 0, ',', '.') }}đ</span>
                </div>
            @endif
        </div>

        @if($transaction->notes)
            <div class="payment-info">
                <div class="total-line">
                    <span>Ghi chú:</span>
                </div>
                <div style="margin-top: 5px; font-style: italic;">
                    {{ $transaction->notes }}
                </div>
            </div>
        @endif

        <!-- Footer -->
        <div class="footer">
            <div class="thank-you">CẢM ƠN QUÝ KHÁCH!</div>
            <div>Hẹn gặp lại quý khách</div>
            @if(core()->getConfigData('sales.shipping.origin.contact'))
                <div style="margin-top: 5px;">
                    Liên hệ: {{ core()->getConfigData('sales.shipping.origin.contact') }}
                </div>
            @endif
        </div>
    </div>
</body>
</html>
