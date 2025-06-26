<!-- SEO Meta Content -->
@push ('meta')
    <meta name="title" content="Giỏ hàng - ZPlus Shop" />
    <meta name="description" content="Giỏ hàng của bạn tại ZPlus Shop - Cửa hàng điện tử hàng đầu Việt Nam" />
@endPush

<x-zplus-theme::layouts>
    <!-- Page Title -->
    <x-slot:title>
        Giỏ hàng - ZPlus Shop
    </x-slot>
    
    <!-- Main Content -->
    <main style="margin-top: 80px;" class="mobile-only">
        <div class="container">
            <!-- Page Header -->
            <div class="mobile-page-header">
                <button class="back-btn" onclick="history.back()">
                    <i data-lucide="arrow-left"></i>
                </button>
                <h1 class="page-title">Giỏ hàng</h1>
                <div class="cart-summary">
                    <span class="cart-count">{{ cart()->getItemsCount() }} sản phẩm</span>
                    <span class="cart-total">{{ core()->formatPrice(cart()->getSubTotal()) }}</span>
                </div>
            </div>

            @if (cart()->getItemsCount())
                <div class="mobile-cart-items">
                    @foreach (cart()->getItems() as $item)
                        <div class="mobile-cart-item">
                            <div class="cart-item-checkbox">
                                <input type="checkbox" checked>
                            </div>
                            <div class="cart-item-image">
                                @if ($item->product->base_image)
                                    <img src="{{ $item->product->base_image_url }}" alt="{{ $item->product->name }}">
                                @else
                                    <img src="{{ theme_asset('images/placeholder.svg') }}" alt="{{ $item->product->name }}">
                                @endif
                            </div>
                            <div class="cart-item-details">
                                <h3 class="cart-item-title">{{ $item->product->name }}</h3>
                                @if ($item->additional['attributes'])
                                    <p class="cart-item-variant">
                                        @foreach ($item->additional['attributes'] as $attribute)
                                            {{ $attribute['attribute_name'] }}: {{ $attribute['option_label'] }}@if (!$loop->last), @endif
                                        @endforeach
                                    </p>
                                @endif
                                <div class="cart-item-price">
                                    <span class="current-price">{{ core()->formatPrice($item->price) }}</span>
                                    @if ($item->base_price > $item->price)
                                        <span class="original-price">{{ core()->formatPrice($item->base_price) }}</span>
                                    @endif
                                </div>
                                <div class="cart-item-actions">
                                    <div class="quantity-controls">
                                        <button class="qty-btn minus" onclick="updateQuantity({{ $item->id }}, {{ $item->quantity - 1 }})">
                                            <i data-lucide="minus"></i>
                                        </button>
                                        <span class="quantity">{{ $item->quantity }}</span>
                                        <button class="qty-btn plus" onclick="updateQuantity({{ $item->id }}, {{ $item->quantity + 1 }})">
                                            <i data-lucide="plus"></i>
                                        </button>
                                    </div>
                                    <button class="remove-item" onclick="removeFromCart({{ $item->id }})">
                                        <i data-lucide="trash-2"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Cart Summary -->
                <div class="cart-summary-section">
                    <div class="cart-summary-card">
                        <h3>Tóm tắt đơn hàng</h3>
                        
                        <div class="summary-row">
                            <span>Tạm tính:</span>
                            <span>{{ core()->formatPrice(cart()->getSubTotal()) }}</span>
                        </div>
                        
                        <div class="summary-row">
                            <span>Phí vận chuyển:</span>
                            <span>Miễn phí</span>
                        </div>
                        
                        <div class="summary-total">
                            <span>Tổng cộng:</span>
                            <span id="final-total">{{ core()->formatPrice(cart()->getSubTotal()) }}</span>
                        </div>

                        <!-- Coupon Code -->
                        <div class="coupon-section">
                            <div class="coupon-input">
                                <input type="text" placeholder="Mã giảm giá" id="coupon-code">
                                <button class="apply-coupon-btn" onclick="applyCoupon()">Áp dụng</button>
                            </div>
                        </div>

                        <div class="checkout-actions">
                            <button class="btn btn-secondary continue-shopping" onclick="window.location='{{ route('shop.home.index') }}'">
                                <i data-lucide="arrow-left"></i>
                                Tiếp tục mua hàng
                            </button>
                            <button class="btn btn-primary checkout-btn" onclick="window.location='{{ route('shop.checkout.onepage.index') }}'">
                                Thanh toán
                                <i data-lucide="arrow-right"></i>
                            </button>
                        </div>
                    </div>
                </div>
            @else
                <!-- Empty Cart -->
                <div class="empty-cart text-center" style="padding: 2rem;">
                    <i data-lucide="shopping-cart" style="font-size: 4rem; color: var(--gray-400); margin-bottom: 1rem;"></i>
                    <h3>Giỏ hàng trống</h3>
                    <p style="color: var(--gray-600); margin-bottom: 2rem;">Hãy thêm sản phẩm vào giỏ hàng để tiếp tục mua sắm</p>
                    <a href="{{ route('shop.home.index') }}" class="btn btn-primary">Mua sắm ngay</a>
                </div>
            @endif
        </div>
    </main>

    <!-- Desktop Content -->
    <main class="desktop-only" style="padding-top: 20px;">
        <div class="container">
            <nav class="breadcrumb">
                <a href="{{ route('shop.home.index') }}">Trang chủ</a>
                <span>/</span>
                <span>Giỏ hàng</span>
            </nav>

            <h1>Giỏ hàng ({{ cart()->getItemsCount() }} sản phẩm)</h1>

            @if (cart()->getItemsCount())
                <div class="desktop-cart-layout">
                    <div class="cart-items-section">
                        <!-- Desktop cart items display -->
                        @foreach (cart()->getItems() as $item)
                            <div class="desktop-cart-item">
                                <div class="item-cell select-cell">
                                    <input type="checkbox" checked>
                                </div>
                                <div class="item-cell product-cell">
                                    <div class="product-info">
                                        @if ($item->product->base_image)
                                            <img src="{{ $item->product->base_image_url }}" alt="{{ $item->product->name }}">
                                        @else
                                            <img src="{{ theme_asset('images/placeholder.svg') }}" alt="{{ $item->product->name }}">
                                        @endif
                                        <div class="product-details">
                                            <h3>{{ $item->product->name }}</h3>
                                            @if ($item->additional['attributes'])
                                                <p>
                                                    @foreach ($item->additional['attributes'] as $attribute)
                                                        {{ $attribute['attribute_name'] }}: {{ $attribute['option_label'] }}@if (!$loop->last), @endif
                                                    @endforeach
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="item-cell price-cell">
                                    <div class="price-info">
                                        <span class="current-price">{{ core()->formatPrice($item->price) }}</span>
                                        @if ($item->base_price > $item->price)
                                            <span class="original-price">{{ core()->formatPrice($item->base_price) }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="item-cell quantity-cell">
                                    <div class="quantity-controls">
                                        <button class="qty-btn" onclick="updateQuantity({{ $item->id }}, {{ $item->quantity - 1 }})">-</button>
                                        <span class="quantity">{{ $item->quantity }}</span>
                                        <button class="qty-btn" onclick="updateQuantity({{ $item->id }}, {{ $item->quantity + 1 }})">+</button>
                                    </div>
                                </div>
                                <div class="item-cell total-cell">
                                    <span>{{ core()->formatPrice($item->price * $item->quantity) }}</span>
                                </div>
                                <div class="item-cell action-cell">
                                    <button class="remove-btn" onclick="removeFromCart({{ $item->id }})">
                                        <i data-lucide="trash-2"></i>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="cart-sidebar">
                        <div class="order-summary">
                            <h3>Tóm tắt đơn hàng</h3>
                            
                            <div class="summary-row">
                                <span>Tạm tính:</span>
                                <span>{{ core()->formatPrice(cart()->getSubTotal()) }}</span>
                            </div>
                            
                            <div class="summary-row">
                                <span>Phí vận chuyển:</span>
                                <span>Miễn phí</span>
                            </div>
                            
                            <div class="summary-total">
                                <span>Tổng cộng:</span>
                                <span>{{ core()->formatPrice(cart()->getSubTotal()) }}</span>
                            </div>

                            <div class="checkout-actions">
                                <button class="btn btn-secondary continue-shopping" onclick="window.location='{{ route('shop.home.index') }}'">
                                    <i data-lucide="arrow-left"></i>
                                    Tiếp tục mua hàng
                                </button>
                                <button class="btn btn-primary checkout-btn" onclick="window.location='{{ route('shop.checkout.onepage.index') }}'">
                                    Thanh toán
                                    <i data-lucide="arrow-right"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="empty-cart text-center" style="padding: 4rem;">
                    <i data-lucide="shopping-cart" style="font-size: 6rem; color: var(--gray-400); margin-bottom: 2rem;"></i>
                    <h2>Giỏ hàng trống</h2>
                    <p style="color: var(--gray-600); margin-bottom: 3rem; font-size: 1.125rem;">Hãy thêm sản phẩm vào giỏ hàng để tiếp tục mua sắm</p>
                    <a href="{{ route('shop.home.index') }}" class="btn btn-primary btn-lg">Mua sắm ngay</a>
                </div>
            @endif
        </div>
    </main>

    @push('scripts')
        <script>
            function updateQuantity(itemId, quantity) {
                if (quantity < 1) {
                    removeFromCart(itemId);
                    return;
                }
                
                // Add AJAX call to update cart quantity
                fetch('{{ route("shop.checkout.cart.update") }}', {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        qty: {[itemId]: quantity}
                    })
                }).then(() => {
                    location.reload();
                });
            }

            function removeFromCart(itemId) {
                fetch('{{ route("shop.checkout.cart.remove") }}/' + itemId, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                }).then(() => {
                    location.reload();
                });
            }

            function applyCoupon() {
                const code = document.getElementById('coupon-code').value;
                // Add coupon application logic
                console.log('Applying coupon:', code);
            }
        </script>
    @endpush

</x-zplus-theme::layouts>