@inject ('reviewRepository', 'Webkul\Product\Repositories\ProductReviewRepository')
@inject ('productImageRepository', 'Webkul\Product\Repositories\ProductImageRepository')

@php
    $avgRating = round($reviewRepository->getAverageRating($product), 1);
    $totalReviews = $reviewRepository->getTotalReviews($product);
@endphp

<!-- SEO Meta Content -->
@push ('meta')
    <meta name="title" content="{{ $product->meta_title ?: $product->name }}" />
    <meta name="description" content="{{ $product->meta_description ?: strip_tags($product->short_description) }}" />
    <meta name="keywords" content="{{ $product->meta_keywords }}" />
@endPush

<x-zplus-theme::layouts>
    <!-- Page Title -->
    <x-slot:title>
        {{ $product->meta_title ?: $product->name }}
    </x-slot>

    <!-- Desktop Breadcrumbs -->
    <div class="breadcrumbs desktop-only">
        <div class="container">
            <a href="{{ route('shop.home.index') }}">Trang chủ</a>
            <span>/</span>
            @if ($product->categories->isNotEmpty())
                @foreach ($product->categories as $category)
                    <a href="{{ route('shop.categories.index', $category->slug) }}">{{ $category->name }}</a>
                    <span>/</span>
                @endforeach
            @endif
            <span>{{ $product->name }}</span>
        </div>
    </div>

    <!-- Product Detail Page -->
    <main class="product-detail">
        <div class="container">
            <div class="product-layout">
                <!-- Product Gallery -->
                <div class="product-gallery">
                    <div class="main-image">
                        <div class="image-container">
                            @if ($product->base_image)
                                <img src="{{ $product->base_image_url }}" alt="{{ $product->name }}" class="main-product-image">
                            @else
                                <div class="placeholder-image main">
                                    <i data-lucide="package"></i>
                                </div>
                            @endif
                            @if ($product->getTypeInstance()->isOnSale())
                                <div class="product-badges">
                                    <span class="badge sale">Giảm giá</span>
                                </div>
                            @endif
                            <button class="zoom-btn">
                                <i data-lucide="zoom-in"></i>
                            </button>
                        </div>
                    </div>
                    @if ($product->images->count() > 1)
                        <div class="thumbnail-gallery">
                            @foreach ($product->images as $index => $image)
                                <div class="thumbnail {{ $index === 0 ? 'active' : '' }}">
                                    <img src="{{ $image->url }}" alt="{{ $product->name }}">
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- Product Info -->
                <div class="product-info">
                    <div class="product-header">
                        <div class="product-category">
                            @if ($product->categories->isNotEmpty())
                                {{ $product->categories->first()->name }}
                            @endif
                        </div>
                        <h1 class="product-title">{{ $product->name }}</h1>
                        <div class="product-rating">
                            <div class="stars">
                                @for ($i = 1; $i <= 5; $i++)
                                    <i data-lucide="star" class="{{ $i <= $avgRating ? 'filled' : '' }}"></i>
                                @endfor
                            </div>
                            <span class="rating-text">({{ $avgRating }}/5 - {{ $totalReviews }} đánh giá)</span>
                        </div>
                    </div>

                    <div class="product-price">
                        <div class="price-section">
                            <span class="current-price">{{ core()->formatPrice($product->getTypeInstance()->getMinimalPrice()) }}</span>
                            @if ($product->getTypeInstance()->getMinimalPrice() < $product->price)
                                <span class="original-price">{{ core()->formatPrice($product->price) }}</span>
                                <span class="discount-percent">-{{ round((($product->price - $product->getTypeInstance()->getMinimalPrice()) / $product->price) * 100) }}%</span>
                            @endif
                        </div>
                    </div>

                    @if ($product->short_description)
                        <div class="product-description">
                            <h3>Mô tả sản phẩm</h3>
                            <p>{!! $product->short_description !!}</p>
                        </div>
                    @endif

                    <!-- Product Options -->
                    @include ('shop::products.view.types.' . $product->type)

                    <!-- Add to Cart Section -->
                    <div class="add-to-cart-section">
                        <div class="quantity-selector">
                            <label>Số lượng:</label>
                            <div class="quantity-controls">
                                <button class="qty-btn minus" onclick="decreaseQty()">
                                    <i data-lucide="minus"></i>
                                </button>
                                <input type="number" id="quantity" value="1" min="1" class="quantity-input">
                                <button class="qty-btn plus" onclick="increaseQty()">
                                    <i data-lucide="plus"></i>
                                </button>
                            </div>
                        </div>

                        <div class="action-buttons">
                            <button class="btn-wishlist" onclick="addToWishlist({{ $product->id }})">
                                <i data-lucide="heart"></i>
                                Thêm vào yêu thích
                            </button>
                            <button class="btn-add-to-cart" onclick="addToCart({{ $product->id }})">
                                <i data-lucide="shopping-cart"></i>
                                Thêm vào giỏ hàng
                            </button>
                            <button class="btn-buy-now" onclick="buyNow({{ $product->id }})">
                                <i data-lucide="zap"></i>
                                Mua ngay
                            </button>
                        </div>
                    </div>

                    <!-- Product Features -->
                    <div class="product-features">
                        <div class="feature-item">
                            <i data-lucide="shield-check"></i>
                            <span>Bảo hành chính hãng</span>
                        </div>
                        <div class="feature-item">
                            <i data-lucide="truck"></i>
                            <span>Giao hàng miễn phí</span>
                        </div>
                        <div class="feature-item">
                            <i data-lucide="rotate-ccw"></i>
                            <span>Đổi trả 30 ngày</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Product Details Tabs -->
            <div class="product-tabs">
                <div class="tab-headers">
                    <button class="tab-header active" data-tab="description">Mô tả chi tiết</button>
                    <button class="tab-header" data-tab="specifications">Thông số kỹ thuật</button>
                    <button class="tab-header" data-tab="reviews">Đánh giá ({{ $totalReviews }})</button>
                </div>

                <div class="tab-contents">
                    <div class="tab-content active" id="description">
                        @if ($product->description)
                            {!! $product->description !!}
                        @else
                            <p>Thông tin mô tả chi tiết đang được cập nhật.</p>
                        @endif
                    </div>

                    <div class="tab-content" id="specifications">
                        @if ($product->additional)
                            <table class="specifications-table">
                                @foreach ($product->additional as $key => $value)
                                    @if (is_string($value) && $value)
                                        <tr>
                                            <td>{{ ucwords(str_replace('_', ' ', $key)) }}</td>
                                            <td>{{ $value }}</td>
                                        </tr>
                                    @endif
                                @endforeach
                            </table>
                        @else
                            <p>Thông số kỹ thuật đang được cập nhật.</p>
                        @endif
                    </div>

                    <div class="tab-content" id="reviews">
                        @if ($totalReviews > 0)
                            <div class="reviews-summary">
                                <div class="rating-overview">
                                    <div class="avg-rating">{{ $avgRating }}</div>
                                    <div class="stars-large">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <i data-lucide="star" class="{{ $i <= $avgRating ? 'filled' : '' }}"></i>
                                        @endfor
                                    </div>
                                    <div class="total-reviews">{{ $totalReviews }} đánh giá</div>
                                </div>
                            </div>

                            <!-- Individual Reviews -->
                            @foreach ($reviewRepository->getCustomerReviews($product) as $review)
                                <div class="review-item">
                                    <div class="review-header">
                                        <span class="reviewer-name">{{ $review->name }}</span>
                                        <div class="review-rating">
                                            @for ($i = 1; $i <= 5; $i++)
                                                <i data-lucide="star" class="{{ $i <= $review->rating ? 'filled' : '' }}"></i>
                                            @endfor
                                        </div>
                                        <span class="review-date">{{ $review->created_at->format('d/m/Y') }}</span>
                                    </div>
                                    <div class="review-content">
                                        <h4>{{ $review->title }}</h4>
                                        <p>{{ $review->comment }}</p>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <p>Chưa có đánh giá nào cho sản phẩm này.</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Related Products -->
            @if ($product->related_products->count())
                <div class="related-products">
                    <h3>Sản phẩm liên quan</h3>
                    <div class="products-grid">
                        @foreach ($product->related_products->take(4) as $relatedProduct)
                            <div class="product-card">
                                <a href="{{ route('shop.product_or_category.index', $relatedProduct->url_key) }}">
                                    @if ($relatedProduct->base_image)
                                        <img src="{{ $relatedProduct->base_image_url }}" alt="{{ $relatedProduct->name }}" class="product-image">
                                    @else
                                        <div class="placeholder-image">
                                            <i data-lucide="package"></i>
                                        </div>
                                    @endif
                                    <h4 class="product-name">{{ $relatedProduct->name }}</h4>
                                    <div class="product-price">
                                        <span class="current-price">{{ core()->formatPrice($relatedProduct->getTypeInstance()->getMinimalPrice()) }}</span>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </main>

    @push('scripts')
        <script>
            function increaseQty() {
                const qty = document.getElementById('quantity');
                qty.value = parseInt(qty.value) + 1;
            }

            function decreaseQty() {
                const qty = document.getElementById('quantity');
                if (parseInt(qty.value) > 1) {
                    qty.value = parseInt(qty.value) - 1;
                }
            }

            function addToCart(productId) {
                const quantity = document.getElementById('quantity').value;
                
                fetch('{{ route("shop.checkout.cart.add", $product->id) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        product_id: productId,
                        quantity: quantity
                    })
                }).then(response => response.json()).then(data => {
                    if (data.success) {
                        showToast('Đã thêm sản phẩm vào giỏ hàng', 'success');
                        updateCartBadge();
                    } else {
                        showToast('Có lỗi xảy ra', 'error');
                    }
                });
            }

            function addToWishlist(productId) {
                // Add to wishlist functionality
                showToast('Đã thêm vào danh sách yêu thích', 'success');
            }

            function buyNow(productId) {
                addToCart(productId);
                setTimeout(() => {
                    window.location.href = '{{ route("shop.checkout.cart.index") }}';
                }, 1000);
            }

            function updateCartBadge() {
                // Update cart badge count
                const badges = document.querySelectorAll('.mobile-bottom-nav-badge');
                badges.forEach(badge => {
                    const currentCount = parseInt(badge.textContent) || 0;
                    badge.textContent = currentCount + 1;
                });
            }

            function showToast(message, type = 'success') {
                const toast = document.createElement('div');
                toast.className = `mobile-toast ${type}`;
                toast.textContent = message;
                document.body.appendChild(toast);
                
                setTimeout(() => toast.classList.add('show'), 100);
                setTimeout(() => {
                    toast.classList.remove('show');
                    setTimeout(() => document.body.removeChild(toast), 300);
                }, 3000);
            }

            // Tab functionality
            document.querySelectorAll('.tab-header').forEach(tab => {
                tab.addEventListener('click', function() {
                    const targetTab = this.dataset.tab;
                    
                    // Remove active class from all tabs and contents
                    document.querySelectorAll('.tab-header').forEach(t => t.classList.remove('active'));
                    document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
                    
                    // Add active class to clicked tab and corresponding content
                    this.classList.add('active');
                    document.getElementById(targetTab).classList.add('active');
                });
            });
        </script>
    @endpush

</x-zplus-theme::layouts>