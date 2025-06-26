<!-- Mobile Header -->
<header class="mobile-header mobile-only">
    <div class="mobile-header-content">
        <a href="{{ route('shop.home.index') }}">
            <img src="{{ theme_asset('images/logo.svg') }}" alt="ZPlus Shop" class="mobile-header-logo">
        </a>
        
        <div class="mobile-header-search">
            <div class="mobile-search">
                <i data-lucide="search" class="mobile-search-icon"></i>
                <input type="text" placeholder="Tìm kiếm sản phẩm..." class="mobile-search-input">
            </div>
        </div>
        
        <div class="mobile-header-actions">
            <a href="{{ route('shop.customer.session.index') }}" class="mobile-header-action">
                <i data-lucide="user"></i>
            </a>
        </div>
    </div>
</header>

<!-- Desktop Header -->
<header class="desktop-only" style="background-color: var(--white); border-bottom: 1px solid var(--gray-200); padding: 1rem 0;">
    <div class="container">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-xl">
                <a href="{{ route('shop.home.index') }}">
                    <img src="{{ theme_asset('images/logo.svg') }}" alt="ZPlus Shop" style="height: 40px;">
                </a>
                
                <!-- Desktop Search -->
                <div style="position: relative; width: 400px;">
                    <form action="{{ route('shop.search.index') }}" method="GET">
                        <i data-lucide="search" style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); width: 16px; height: 16px; color: var(--gray-400);"></i>
                        <input 
                            type="text" 
                            name="query"
                            placeholder="Tìm kiếm sản phẩm..." 
                            class="form-input" 
                            style="padding-left: 40px;"
                            value="{{ request('query') }}"
                        >
                    </form>
                </div>
            </div>
            
            <nav class="flex items-center gap-lg">
                <a href="{{ route('shop.categories.index') }}" class="text-gray-700 hover:text-primary">Danh mục</a>
                <a href="#" class="text-gray-700 hover:text-primary">Khuyến mãi</a>
                <a href="#" class="text-gray-700 hover:text-primary">Hỗ trợ</a>
                
                <div class="flex items-center gap-md">
                    <a href="{{ route('shop.customers.account.wishlist.index') }}" class="mobile-header-action">
                        <i data-lucide="heart"></i>
                    </a>
                    <a href="{{ route('shop.checkout.cart.index') }}" class="mobile-header-action" style="position: relative;">
                        <i data-lucide="shopping-cart"></i>
                        <span class="mobile-bottom-nav-badge">{{ cart()->getItemsCount() }}</span>
                    </a>
                    @auth('customer')
                        <a href="{{ route('shop.customers.account.profile.index') }}" class="mobile-header-action">
                            <i data-lucide="user"></i>
                        </a>
                    @else
                        <a href="{{ route('shop.customer.session.index') }}" class="mobile-header-action">
                            <i data-lucide="user"></i>
                        </a>
                    @endauth
                </div>
            </nav>
        </div>
    </div>
</header>