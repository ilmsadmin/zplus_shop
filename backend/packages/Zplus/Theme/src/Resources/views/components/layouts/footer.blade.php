<!-- Mobile Bottom Navigation -->
<nav class="mobile-bottom-nav mobile-only">
    <ul class="mobile-bottom-nav-list">
        <li class="mobile-bottom-nav-item">
            <a href="{{ route('shop.home.index') }}" class="mobile-bottom-nav-link {{ request()->routeIs('shop.home.index') ? 'active' : '' }}">
                <i data-lucide="home" class="mobile-bottom-nav-icon"></i>
                <span class="mobile-bottom-nav-text">Trang chủ</span>
            </a>
        </li>
        <li class="mobile-bottom-nav-item">
            <a href="{{ route('shop.categories.index') }}" class="mobile-bottom-nav-link {{ request()->routeIs('shop.categories.*') ? 'active' : '' }}">
                <i data-lucide="grid-3x3" class="mobile-bottom-nav-icon"></i>
                <span class="mobile-bottom-nav-text">Danh mục</span>
            </a>
        </li>
        <li class="mobile-bottom-nav-item">
            <a href="{{ route('shop.checkout.cart.index') }}" class="mobile-bottom-nav-link {{ request()->routeIs('shop.checkout.cart.*') ? 'active' : '' }}" style="position: relative;">
                <i data-lucide="shopping-cart" class="mobile-bottom-nav-icon"></i>
                <span class="mobile-bottom-nav-text">Giỏ hàng</span>
                <span class="mobile-bottom-nav-badge">{{ cart()->getItemsCount() }}</span>
            </a>
        </li>
        <li class="mobile-bottom-nav-item">
            <a href="{{ route('shop.customers.account.wishlist.index') }}" class="mobile-bottom-nav-link {{ request()->routeIs('shop.customers.account.wishlist.*') ? 'active' : '' }}">
                <i data-lucide="heart" class="mobile-bottom-nav-icon"></i>
                <span class="mobile-bottom-nav-text">Yêu thích</span>
            </a>
        </li>
        <li class="mobile-bottom-nav-item">
            @auth('customer')
                <a href="{{ route('shop.customers.account.profile.index') }}" class="mobile-bottom-nav-link {{ request()->routeIs('shop.customers.account.*') ? 'active' : '' }}">
                    <i data-lucide="user" class="mobile-bottom-nav-icon"></i>
                    <span class="mobile-bottom-nav-text">Tài khoản</span>
                </a>
            @else
                <a href="{{ route('shop.customer.session.index') }}" class="mobile-bottom-nav-link {{ request()->routeIs('shop.customer.session.*') ? 'active' : '' }}">
                    <i data-lucide="user" class="mobile-bottom-nav-icon"></i>
                    <span class="mobile-bottom-nav-text">Đăng nhập</span>
                </a>
            @endauth
        </li>
    </ul>
</nav>

<!-- Desktop Footer -->
<footer class="desktop-footer desktop-only">
    <div class="container">
        <!-- Main Footer Content -->
        <div class="footer-main">
            <div class="footer-grid">
                <!-- Company Info -->
                <div class="footer-section footer-brand">
                    <img src="{{ theme_asset('images/logo.svg') }}" alt="ZPlus Shop" style="height: 40px; margin-bottom: 1rem;">
                    <p>ZPlus Shop là cửa hàng điện tử hàng đầu Việt Nam, cung cấp các sản phẩm công nghệ chính hãng với chất lượng cao và dịch vụ tốt nhất.</p>
                    <div class="footer-contact">
                        <div class="contact-item">
                            <i data-lucide="map-pin"></i>
                            <span>123 Đường ABC, Quận 1, TP.HCM</span>
                        </div>
                        <div class="contact-item">
                            <i data-lucide="phone"></i>
                            <span>1900 1234</span>
                        </div>
                        <div class="contact-item">
                            <i data-lucide="mail"></i>
                            <span>contact@zplusshop.vn</span>
                        </div>
                    </div>
                    <div class="footer-social">
                        <a href="#" class="social-link">
                            <i data-lucide="facebook"></i>
                        </a>
                        <a href="#" class="social-link">
                            <i data-lucide="twitter"></i>
                        </a>
                        <a href="#" class="social-link">
                            <i data-lucide="instagram"></i>
                        </a>
                        <a href="#" class="social-link">
                            <i data-lucide="youtube"></i>
                        </a>
                    </div>
                </div>

                <!-- Product Categories -->
                <div class="footer-section">
                    <h4>Danh mục sản phẩm</h4>
                    <ul class="footer-links">
                        @foreach (app('Webkul\Category\Repositories\CategoryRepository')->getVisibleCategoryTree(core()->getCurrentChannel()->root_category_id) as $category)
                            <li><a href="{{ route('shop.categories.index', $category->slug) }}">{{ $category->name }}</a></li>
                        @endforeach
                    </ul>
                </div>

                <!-- Customer Service -->
                <div class="footer-section">
                    <h4>Hỗ trợ khách hàng</h4>
                    <ul class="footer-links">
                        <li><a href="#">Hướng dẫn mua hàng</a></li>
                        <li><a href="#">Chính sách giao hàng</a></li>
                        <li><a href="#">Chính sách đổi trả</a></li>
                        <li><a href="#">Chính sách bảo hành</a></li>
                        <li><a href="#">Phương thức thanh toán</a></li>
                        <li><a href="#">Câu hỏi thường gặp</a></li>
                        <li><a href="#">Liên hệ hỗ trợ</a></li>
                    </ul>
                </div>

                <!-- Company -->
                <div class="footer-section">
                    <h4>Về ZPlus Shop</h4>
                    <ul class="footer-links">
                        <li><a href="#">Giới thiệu công ty</a></li>
                        <li><a href="#">Tuyển dụng</a></li>
                        <li><a href="#">Tin tức</a></li>
                        <li><a href="#">Sự kiện</a></li>
                        <li><a href="#">Điều khoản sử dụng</a></li>
                        <li><a href="#">Chính sách bảo mật</a></li>
                        <li><a href="#">Sitemap</a></li>
                    </ul>
                </div>

                <!-- Newsletter & App Download -->
                <div class="footer-section">
                    <h4>Kết nối với chúng tôi</h4>
                    <p style="margin-bottom: 1rem; color: var(--gray-600); font-size: 0.875rem;">
                        Đăng ký nhận thông tin khuyến mãi và sản phẩm mới nhất
                    </p>
                    <div class="newsletter-form">
                        <input type="email" placeholder="Nhập email của bạn" class="newsletter-input">
                        <button class="newsletter-btn">
                            <i data-lucide="send"></i>
                        </button>
                    </div>
                    
                    <div class="app-download">
                        <h5 style="margin: 1.5rem 0 0.75rem 0; font-size: 0.875rem; font-weight: 600;">Tải ứng dụng ZPlus</h5>
                        <div class="app-links">
                            <a href="#" class="app-link">
                                <img src="{{ theme_asset('images/app-store.png') }}" alt="App Store" style="height: 40px;">
                            </a>
                            <a href="#" class="app-link">
                                <img src="{{ theme_asset('images/google-play.png') }}" alt="Google Play" style="height: 40px;">
                            </a>
                        </div>
                    </div>

                    <div class="payment-methods">
                        <h5 style="margin: 1.5rem 0 0.75rem 0; font-size: 0.875rem; font-weight: 600;">Phương thức thanh toán</h5>
                        <div class="payment-icons">
                            <i data-lucide="credit-card"></i>
                            <i data-lucide="smartphone"></i>
                            <i data-lucide="banknote"></i>
                            <i data-lucide="wallet"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer Bottom -->
        <div class="footer-bottom">
            <div class="footer-bottom-content">
                <div class="footer-copyright">
                    <p>&copy; {{ date('Y') }} ZPlus Shop. Tất cả quyền được bảo lưu.</p>
                </div>
                <div class="footer-certifications">
                    <div class="cert-item">
                        <i data-lucide="shield-check"></i>
                        <span>Chứng nhận</span>
                    </div>
                    <div class="cert-item">
                        <i data-lucide="award"></i>
                        <span>Uy tín</span>
                    </div>
                    <div class="cert-item">
                        <i data-lucide="truck"></i>
                        <span>Giao hàng toàn quốc</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>