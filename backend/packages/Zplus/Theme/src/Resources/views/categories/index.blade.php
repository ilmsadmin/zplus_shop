<!-- SEO Meta Content -->
@push ('meta')
    <meta name="title" content="{{ $category->meta_title ?: $category->name }}" />
    <meta name="description" content="{{ $category->meta_description ?: $category->description }}" />
    <meta name="keywords" content="{{ $category->meta_keywords }}" />
@endPush

<x-zplus-theme::layouts>
    <!-- Page Title -->
    <x-slot:title>
        {{ $category->meta_title ?: $category->name }}
    </x-slot>

    <!-- Desktop Breadcrumbs -->
    <div class="breadcrumbs desktop-only">
        <div class="container">
            <a href="{{ route('shop.home.index') }}">Trang chủ</a>
            <span>/</span>
            @if ($category->parent)
                <a href="{{ route('shop.categories.index', $category->parent->slug) }}">{{ $category->parent->name }}</a>
                <span>/</span>
            @endif
            <span>{{ $category->name }}</span>
        </div>
    </div>

    <!-- Main Content -->
    <main style="margin-top: 80px;" class="mobile-only">
        <div class="container">
            <!-- Page Header -->
            <div class="mobile-page-header">
                <button class="back-btn" onclick="history.back()">
                    <i data-lucide="arrow-left"></i>
                </button>
                <h1 class="page-title">{{ $category->name }}</h1>
            </div>

            <!-- Products Toolbar -->
            <div class="products-toolbar">
                <div class="toolbar-left">
                    <span class="results-count">{{ $products->total() }} sản phẩm</span>
                </div>
                <div class="toolbar-right">
                    <select class="sort-select" onchange="updateSort(this.value)">
                        <option value="name-asc" {{ request('sort') == 'name-asc' ? 'selected' : '' }}>Tên A-Z</option>
                        <option value="name-desc" {{ request('sort') == 'name-desc' ? 'selected' : '' }}>Tên Z-A</option>
                        <option value="price-asc" {{ request('sort') == 'price-asc' ? 'selected' : '' }}>Giá thấp đến cao</option>
                        <option value="price-desc" {{ request('sort') == 'price-desc' ? 'selected' : '' }}>Giá cao đến thấp</option>
                        <option value="created_at-desc" {{ request('sort') == 'created_at-desc' ? 'selected' : '' }}>Mới nhất</option>
                    </select>
                    
                    <div class="view-toggles">
                        <button class="view-btn active" data-view="grid">
                            <i data-lucide="grid-3x3"></i>
                        </button>
                        <button class="view-btn" data-view="list">
                            <i data-lucide="list"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Products Grid -->
            <div class="products-grid">
                @forelse ($products as $product)
                    <div class="mobile-product-card">
                        <a href="{{ route('shop.product_or_category.index', $product->url_key) }}">
                            @if ($product->base_image)
                                <img src="{{ $product->base_image_url }}" alt="{{ $product->name }}" class="mobile-product-image">
                            @else
                                <div class="placeholder-image">
                                    <i data-lucide="package"></i>
                                </div>
                            @endif
                        </a>
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
                            <div class="mobile-product-actions">
                                <button class="mobile-product-wishlist" onclick="toggleWishlist({{ $product->id }})">
                                    <i data-lucide="heart"></i>
                                </button>
                                <button class="mobile-product-add-to-cart" onclick="addToCart({{ $product->id }})">
                                    <i data-lucide="shopping-cart"></i>
                                    Thêm vào giỏ
                                </button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="no-products text-center" style="grid-column: 1 / -1; padding: 2rem;">
                        <i data-lucide="package" style="font-size: 4rem; color: var(--gray-400); margin-bottom: 1rem;"></i>
                        <h3>Không có sản phẩm</h3>
                        <p style="color: var(--gray-600); margin-bottom: 2rem;">Hiện tại danh mục này chưa có sản phẩm nào.</p>
                        <a href="{{ route('shop.home.index') }}" class="btn btn-primary">Về trang chủ</a>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if ($products->hasPages())
                <div class="pagination-wrapper">
                    {{ $products->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </main>

    <!-- Desktop Content -->
    <main class="desktop-only" style="padding-top: 20px;">
        <div class="container">
            <div class="category-layout">
                <!-- Sidebar Filters -->
                <div class="sidebar-filters">
                    <div class="filter-section">
                        <h3>Danh mục</h3>
                        <ul class="category-list">
                            @foreach ($category->children as $childCategory)
                                <li>
                                    <a href="{{ route('shop.categories.index', $childCategory->slug) }}">
                                        {{ $childCategory->name }}
                                        <span>({{ $childCategory->products()->count() }})</span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="filter-section">
                        <h3>Khoảng giá</h3>
                        <div class="price-filter">
                            <input type="range" id="price-range" min="0" max="50000000" step="100000">
                            <div class="price-inputs">
                                <input type="number" placeholder="Từ" id="price-from">
                                <input type="number" placeholder="Đến" id="price-to">
                            </div>
                            <button class="apply-filter-btn">Áp dụng</button>
                        </div>
                    </div>

                    <div class="filter-section">
                        <h3>Thương hiệu</h3>
                        <div class="brand-filters">
                            <label class="checkbox-label">
                                <input type="checkbox" name="brands[]" value="apple">
                                <span class="checkmark"></span>
                                Apple
                            </label>
                            <label class="checkbox-label">
                                <input type="checkbox" name="brands[]" value="samsung">
                                <span class="checkmark"></span>
                                Samsung
                            </label>
                            <label class="checkbox-label">
                                <input type="checkbox" name="brands[]" value="xiaomi">
                                <span class="checkmark"></span>
                                Xiaomi
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Main Content Area -->
                <div class="main-content">
                    <!-- Category Header -->
                    <div class="category-header">
                        <h1>{{ $category->name }}</h1>
                        @if ($category->description)
                            <p class="category-description">{{ $category->description }}</p>
                        @endif
                    </div>

                    <!-- Products Toolbar -->
                    <div class="products-toolbar desktop">
                        <div class="toolbar-left">
                            <span class="results-count">Hiển thị {{ $products->count() }} trong số {{ $products->total() }} sản phẩm</span>
                        </div>
                        <div class="toolbar-right">
                            <select class="sort-select" onchange="updateSort(this.value)">
                                <option value="name-asc" {{ request('sort') == 'name-asc' ? 'selected' : '' }}>Tên A-Z</option>
                                <option value="name-desc" {{ request('sort') == 'name-desc' ? 'selected' : '' }}>Tên Z-A</option>
                                <option value="price-asc" {{ request('sort') == 'price-asc' ? 'selected' : '' }}>Giá thấp đến cao</option>
                                <option value="price-desc" {{ request('sort') == 'price-desc' ? 'selected' : '' }}>Giá cao đến thấp</option>
                                <option value="created_at-desc" {{ request('sort') == 'created_at-desc' ? 'selected' : '' }}>Mới nhất</option>
                            </select>
                            
                            <div class="view-toggles">
                                <button class="view-btn active" data-view="grid">
                                    <i data-lucide="grid-3x3"></i>
                                </button>
                                <button class="view-btn" data-view="list">
                                    <i data-lucide="list"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Products Grid -->
                    <div class="products-grid desktop">
                        @forelse ($products as $product)
                            <div class="product-card">
                                <div class="product-image-container">
                                    <a href="{{ route('shop.product_or_category.index', $product->url_key) }}">
                                        @if ($product->base_image)
                                            <img src="{{ $product->base_image_url }}" alt="{{ $product->name }}" class="product-image">
                                        @else
                                            <div class="placeholder-image">
                                                <i data-lucide="package"></i>
                                            </div>
                                        @endif
                                    </a>
                                    <div class="product-actions">
                                        <button class="action-btn wishlist" onclick="toggleWishlist({{ $product->id }})">
                                            <i data-lucide="heart"></i>
                                        </button>
                                        <button class="action-btn quick-view" onclick="quickView({{ $product->id }})">
                                            <i data-lucide="eye"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="product-info">
                                    <h3 class="product-title">
                                        <a href="{{ route('shop.product_or_category.index', $product->url_key) }}">{{ $product->name }}</a>
                                    </h3>
                                    <div class="product-rating">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <i data-lucide="star" class="{{ $i <= 4 ? 'filled' : '' }}"></i>
                                        @endfor
                                        <span>(4.5)</span>
                                    </div>
                                    <div class="product-price">
                                        <span class="current-price">{{ core()->formatPrice($product->getTypeInstance()->getMinimalPrice()) }}</span>
                                        @if ($product->getTypeInstance()->getMinimalPrice() < $product->price)
                                            <span class="original-price">{{ core()->formatPrice($product->price) }}</span>
                                        @endif
                                    </div>
                                    <button class="add-to-cart" onclick="addToCart({{ $product->id }})">
                                        <i data-lucide="shopping-cart"></i>
                                        Thêm vào giỏ
                                    </button>
                                </div>
                            </div>
                        @empty
                            <div class="no-products text-center" style="grid-column: 1 / -1; padding: 4rem;">
                                <i data-lucide="package" style="font-size: 6rem; color: var(--gray-400); margin-bottom: 2rem;"></i>
                                <h2>Không có sản phẩm</h2>
                                <p style="color: var(--gray-600); margin-bottom: 3rem; font-size: 1.125rem;">Hiện tại danh mục này chưa có sản phẩm nào.</p>
                                <a href="{{ route('shop.home.index') }}" class="btn btn-primary btn-lg">Về trang chủ</a>
                            </div>
                        @endforelse
                    </div>

                    <!-- Pagination -->
                    @if ($products->hasPages())
                        <div class="pagination-wrapper">
                            {{ $products->appends(request()->query())->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </main>

    @push('scripts')
        <script>
            function updateSort(sortValue) {
                const url = new URL(window.location);
                url.searchParams.set('sort', sortValue);
                window.location.href = url.toString();
            }

            function addToCart(productId) {
                fetch('{{ route("shop.checkout.cart.add", ":productId") }}'.replace(':productId', productId), {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        product_id: productId,
                        quantity: 1
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

            function toggleWishlist(productId) {
                // Toggle wishlist functionality
                const wishlistBtn = event.target.closest('.wishlist, .mobile-product-wishlist');
                wishlistBtn.classList.toggle('active');
                const isActive = wishlistBtn.classList.contains('active');
                showToast(isActive ? 'Đã thêm vào yêu thích' : 'Đã xóa khỏi yêu thích', isActive ? 'success' : 'warning');
            }

            function quickView(productId) {
                // Quick view functionality
                console.log('Quick view for product:', productId);
            }

            function updateCartBadge() {
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

            // View toggle functionality
            document.querySelectorAll('.view-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const view = this.dataset.view;
                    const viewButtons = document.querySelectorAll('.view-btn');
                    const productsGrid = document.querySelector('.products-grid');
                    
                    viewButtons.forEach(b => b.classList.remove('active'));
                    this.classList.add('active');
                    
                    productsGrid.className = view === 'list' ? 'products-list' : 'products-grid';
                });
            });
        </script>
    @endpush

</x-zplus-theme::layouts>