@php
    $channel = core()->getCurrentChannel();
    $customizations = app('Webkul\Theme\Repositories\ThemeCustomizationRepository')->orderBy('sort_order')->findWhere([
        'status'     => 1,
        'channel_id' => core()->getCurrentChannel()->id,
    ]);
@endphp

<!-- SEO Meta Content -->
@push ('meta')
    <meta
        name="title"
        content="{{ $channel->home_seo['meta_title'] ?? '' }}"
    />

    <meta
        name="description"
        content="{{ $channel->home_seo['meta_description'] ?? '' }}"
    />

    <meta
        name="keywords"
        content="{{ $channel->home_seo['meta_keywords'] ?? '' }}"
    />
@endPush

<x-zplus-theme::layouts>
    <!-- Page Title -->
    <x-slot:title>
        {{  $channel->home_seo['meta_title'] ?? '' }}
    </x-slot>
    
    <!-- Main Content -->
    <main style="margin-top: 80px;" class="mobile-only">
        <!-- Hero Section -->
        <section style="background: linear-gradient(135deg, var(--primary-color), var(--primary-light)); color: var(--white); padding: 2rem 0;">
            <div class="container text-center">
                <h1 style="font-size: 2rem; font-weight: 700; margin-bottom: 1rem;">
                    🔥 Siêu Sale 12.12
                </h1>
                <p style="font-size: 1.125rem; margin-bottom: 2rem; opacity: 0.9;">
                    Giảm giá lên đến 50% cho tất cả sản phẩm
                </p>
                <a href="#products" class="btn btn-secondary btn-lg">
                    Mua ngay
                </a>
            </div>
        </section>

        <!-- Categories -->
        <section class="p-lg">
            <div class="container">
                <h2 class="text-xl font-semibold mb-lg">Danh mục nổi bật</h2>
                
                <div class="grid grid-cols-4 gap-md">
                    @foreach (app('Webkul\Category\Repositories\CategoryRepository')->getVisibleCategoryTree(core()->getCurrentChannel()->root_category_id) as $category)
                        <a href="{{ route('shop.categories.index', $category->slug) }}" class="mobile-category-card">
                            <div class="mobile-category-icon">
                                <i data-lucide="tag"></i>
                            </div>
                            <div class="mobile-category-title">{{ $category->name }}</div>
                            <div class="mobile-category-count">{{ $category->products()->count() }}+ sản phẩm</div>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>

        <!-- Flash Sale -->
        <section class="p-lg bg-gray-50">
            <div class="container">
                <div class="flex items-center justify-between mb-lg">
                    <h2 class="text-xl font-semibold">⚡ Flash Sale</h2>
                    <div class="flex items-center gap-sm text-error">
                        <i data-lucide="clock"></i>
                        <span class="text-sm font-medium" id="countdown">02:45:30</span>
                    </div>
                </div>
                
                <div class="grid grid-cols-2 gap-md">
                    @foreach (app('Webkul\Product\Repositories\ProductRepository')->getFeaturedProducts()->take(4) as $product)
                        <div class="mobile-product-card">
                            @if ($product->base_image)
                                <img src="{{ $product->base_image_url }}" alt="{{ $product->name }}" class="mobile-product-image">
                            @else
                                <img src="{{ theme_asset('images/products/placeholder.jpg') }}" alt="{{ $product->name }}" class="mobile-product-image">
                            @endif
                            <div class="mobile-product-content">
                                <h3 class="mobile-product-title">{{ $product->name }}</h3>
                                <div class="mobile-product-rating">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <i data-lucide="star" class="{{ $i <= 4 ? 'filled' : '' }}"></i>
                                    @endfor
                                    <span>(4.5)</span>
                                </div>
                                <div class="mobile-product-price">
                                    <span class="price">{{ core()->formatPrice($product->getTypeInstance()->getMinimalPrice()) }}</span>
                                    @if ($product->getTypeInstance()->getMinimalPrice() < $product->price)
                                        <span class="original-price">{{ core()->formatPrice($product->price) }}</span>
                                        <span class="discount">-{{ round((($product->price - $product->getTypeInstance()->getMinimalPrice()) / $product->price) * 100) }}%</span>
                                    @endif
                                </div>
                                <button class="mobile-product-add-to-cart" data-product-id="{{ $product->id }}">
                                    <i data-lucide="shopping-cart"></i>
                                    Thêm vào giỏ
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <!-- Products -->
        <section class="p-lg" id="products">
            <div class="container">
                <h2 class="text-xl font-semibold mb-lg">Sản phẩm nổi bật</h2>
                
                <div class="grid grid-cols-2 gap-md">
                    @foreach (app('Webkul\Product\Repositories\ProductRepository')->getAll()->take(6) as $product)
                        <div class="mobile-product-card">
                            @if ($product->base_image)
                                <img src="{{ $product->base_image_url }}" alt="{{ $product->name }}" class="mobile-product-image">
                            @else
                                <img src="{{ theme_asset('images/products/placeholder.jpg') }}" alt="{{ $product->name }}" class="mobile-product-image">
                            @endif
                            <div class="mobile-product-content">
                                <h3 class="mobile-product-title">
                                    <a href="{{ route('shop.product_or_category.index', $product->url_key) }}">{{ $product->name }}</a>
                                </h3>
                                <div class="mobile-product-rating">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <i data-lucide="star" class="{{ $i <= 4 ? 'filled' : '' }}"></i>
                                    @endfor
                                    <span>(4.5)</span>
                                </div>
                                <div class="mobile-product-price">
                                    <span class="price">{{ core()->formatPrice($product->getTypeInstance()->getMinimalPrice()) }}</span>
                                    @if ($product->getTypeInstance()->getMinimalPrice() < $product->price)
                                        <span class="original-price">{{ core()->formatPrice($product->price) }}</span>
                                        <span class="discount">-{{ round((($product->price - $product->getTypeInstance()->getMinimalPrice()) / $product->price) * 100) }}%</span>
                                    @endif
                                </div>
                                <button class="mobile-product-add-to-cart" data-product-id="{{ $product->id }}">
                                    <i data-lucide="shopping-cart"></i>
                                    Thêm vào giỏ
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <div class="text-center mt-lg">
                    <a href="{{ route('shop.search.index') }}" class="btn btn-primary btn-lg">Xem tất cả sản phẩm</a>
                </div>
            </div>
        </section>

        <!-- Features -->
        <section class="p-lg bg-gray-50">
            <div class="container">
                <h2 class="text-xl font-semibold mb-lg text-center">Tại sao chọn ZPlus Shop?</h2>
                
                <div class="grid grid-cols-2 gap-lg">
                    <div class="text-center">
                        <div class="w-16 h-16 bg-primary rounded-full flex items-center justify-center mx-auto mb-md">
                            <i data-lucide="shield-check" style="color: white; width: 24px; height: 24px;"></i>
                        </div>
                        <h3 class="font-semibold mb-xs">Chính hãng 100%</h3>
                        <p class="text-sm text-gray-600">Cam kết sản phẩm chính hãng</p>
                    </div>
                    
                    <div class="text-center">
                        <div class="w-16 h-16 bg-primary rounded-full flex items-center justify-center mx-auto mb-md">
                            <i data-lucide="truck" style="color: white; width: 24px; height: 24px;"></i>
                        </div>
                        <h3 class="font-semibold mb-xs">Giao hàng nhanh</h3>
                        <p class="text-sm text-gray-600">Giao hàng trong 24h</p>
                    </div>
                    
                    <div class="text-center">
                        <div class="w-16 h-16 bg-primary rounded-full flex items-center justify-center mx-auto mb-md">
                            <i data-lucide="rotate-ccw" style="color: white; width: 24px; height: 24px;"></i>
                        </div>
                        <h3 class="font-semibold mb-xs">Đổi trả 30 ngày</h3>
                        <p class="text-sm text-gray-600">Đổi trả miễn phí trong 30 ngày</p>
                    </div>
                    
                    <div class="text-center">
                        <div class="w-16 h-16 bg-primary rounded-full flex items-center justify-center mx-auto mb-md">
                            <i data-lucide="headphones" style="color: white; width: 24px; height: 24px;"></i>
                        </div>
                        <h3 class="font-semibold mb-xs">Hỗ trợ 24/7</h3>
                        <p class="text-sm text-gray-600">Tư vấn nhiệt tình 24/7</p>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- Desktop Content -->
    <main class="desktop-only" style="padding-top: 20px;">
        <!-- Hero Carousel -->
        <section style="margin-bottom: 3rem;">
            <div class="container">
                <div class="hero-carousel">
                    <div class="hero-slide">
                        <img src="{{ theme_asset('images/hero-banner.jpg') }}" alt="Hero Banner" style="width: 100%; height: 400px; object-fit: cover; border-radius: 8px;">
                    </div>
                </div>
            </div>
        </section>

        <!-- Loop over the theme customizations -->
        @foreach ($customizations as $customization)
            @include ('shop::home.' . $customization->type, ['customization' => $customization])
        @endforeach
    </main>

    @push('scripts')
        <script>
            // Flash Sale Countdown
            function updateCountdown() {
                const countdownElement = document.getElementById('countdown');
                if (!countdownElement) return;
                
                const now = new Date().getTime();
                const endTime = now + (2 * 60 * 60 * 1000) + (45 * 60 * 1000) + (30 * 1000); // 2h 45m 30s
                
                const distance = endTime - now;
                
                const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);
                
                countdownElement.textContent = 
                    (hours < 10 ? '0' : '') + hours + ':' +
                    (minutes < 10 ? '0' : '') + minutes + ':' +
                    (seconds < 10 ? '0' : '') + seconds;
            }
            
            // Update countdown every second
            setInterval(updateCountdown, 1000);
            updateCountdown();
        </script>
    @endpush

</x-zplus-theme::layouts>