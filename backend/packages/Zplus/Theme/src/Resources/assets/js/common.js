/**
 * ZPlus Shop - Common JavaScript Functionality
 * Handles shared features across all pages
 */

// Global variables
let cartCount = 3;
let wishlistCount = 0;

// DOM Content Loaded Event
document.addEventListener('DOMContentLoaded', function() {
    initializeCommonFeatures();
    updateCounters();
});

/**
 * Initialize common features across all pages
 */
function initializeCommonFeatures() {
    // Mobile menu toggle
    const mobileMenuBtn = document.querySelector('.mobile-menu-btn');
    if (mobileMenuBtn) {
        mobileMenuBtn.addEventListener('click', toggleMobileMenu);
    }

    // Search functionality
    const searchBtns = document.querySelectorAll('.search-btn');
    searchBtns.forEach(btn => {
        btn.addEventListener('click', handleSearch);
    });

    // Newsletter subscription
    const newsletterForms = document.querySelectorAll('.newsletter-form');
    newsletterForms.forEach(form => {
        form.addEventListener('submit', handleNewsletterSubscription);
    });

    // Add to cart buttons
    const addToCartBtns = document.querySelectorAll('.add-to-cart-btn, .quick-add-btn');
    addToCartBtns.forEach(btn => {
        btn.addEventListener('click', handleAddToCart);
    });

    // Add to wishlist buttons
    const wishlistBtns = document.querySelectorAll('.wishlist-btn, .add-to-wishlist-btn');
    wishlistBtns.forEach(btn => {
        btn.addEventListener('click', handleAddToWishlist);
    });

    // Initialize smooth scrolling for anchor links
    initializeSmoothScrolling();
}

/**
 * Toggle mobile menu
 */
function toggleMobileMenu() {
    // This would open a mobile menu drawer
    showToast('Menu mobile chưa được triển khai', 'info');
}

/**
 * Handle search functionality
 */
function handleSearch(e) {
    e.preventDefault();
    const searchInput = e.target.closest('.search-bar, .mobile-search-bar')?.querySelector('input');
    const query = searchInput?.value?.trim();
    
    if (query) {
        showToast(`Tìm kiếm "${query}"...`, 'info');
        // In a real implementation, this would redirect to search results
        setTimeout(() => {
            window.location.href = `category.html?search=${encodeURIComponent(query)}`;
        }, 1000);
    } else {
        showToast('Vui lòng nhập từ khóa tìm kiếm', 'warning');
    }
}

/**
 * Handle newsletter subscription
 */
function handleNewsletterSubscription(e) {
    e.preventDefault();
    const emailInput = e.target.querySelector('input[type="email"]');
    const email = emailInput?.value?.trim();
    
    if (email && isValidEmail(email)) {
        showToast('Đăng ký thành công! Cảm ơn bạn đã quan tâm.', 'success');
        emailInput.value = '';
    } else {
        showToast('Vui lòng nhập địa chỉ email hợp lệ', 'error');
    }
}

/**
 * Handle add to cart functionality
 */
function handleAddToCart(e) {
    e.preventDefault();
    const productName = getProductName(e.target);
    
    cartCount++;
    updateCounters();
    showToast(`Đã thêm ${productName} vào giỏ hàng`, 'success');
    
    // Add visual feedback
    animateCartIcon();
}

/**
 * Handle add to wishlist functionality
 */
function handleAddToWishlist(e) {
    e.preventDefault();
    const productName = getProductName(e.target);
    const btn = e.target.closest('.wishlist-btn, .add-to-wishlist-btn');
    
    if (btn.classList.contains('active')) {
        wishlistCount--;
        btn.classList.remove('active');
        showToast(`Đã xóa ${productName} khỏi danh sách yêu thích`, 'info');
    } else {
        wishlistCount++;
        btn.classList.add('active');
        showToast(`Đã thêm ${productName} vào danh sách yêu thích`, 'success');
    }
    
    updateCounters();
}

/**
 * Get product name from button context
 */
function getProductName(button) {
    const productCard = button.closest('.product-card, .product-item');
    const productTitle = productCard?.querySelector('h3, h4, .product-title, .item-details h4');
    return productTitle?.textContent?.trim() || 'sản phẩm';
}

/**
 * Update all counter displays
 */
function updateCounters() {
    // Cart counters
    const cartElements = [
        'cart-count',
        'mobile-cart-count', 
        'mobile-nav-cart-count'
    ];
    
    cartElements.forEach(id => {
        const element = document.getElementById(id);
        if (element) {
            element.textContent = cartCount;
            element.style.display = cartCount > 0 ? 'inline' : 'none';
        }
    });
    
    // Wishlist counters
    const wishlistElements = [
        'wishlist-count',
        'mobile-wishlist-count',
        'mobile-nav-wishlist-count'
    ];
    
    wishlistElements.forEach(id => {
        const element = document.getElementById(id);
        if (element) {
            element.textContent = wishlistCount;
            element.style.display = wishlistCount > 0 ? 'inline' : 'none';
        }
    });
}

/**
 * Animate cart icon when item is added
 */
function animateCartIcon() {
    const cartIcons = document.querySelectorAll('.header-action i.fa-shopping-cart, .mobile-action i.fa-shopping-cart');
    cartIcons.forEach(icon => {
        icon.style.transform = 'scale(1.2)';
        icon.style.color = 'var(--success-color)';
        
        setTimeout(() => {
            icon.style.transform = 'scale(1)';
            icon.style.color = '';
        }, 300);
    });
}

/**
 * Show toast notification
 */
function showToast(message, type = 'info') {
    const toast = document.getElementById('toast') || createToastElement();
    
    toast.textContent = message;
    toast.className = `toast ${type} show`;
    
    // Auto hide after 3 seconds
    setTimeout(() => {
        toast.classList.remove('show');
    }, 3000);
}

/**
 * Create toast element if it doesn't exist
 */
function createToastElement() {
    const toast = document.createElement('div');
    toast.id = 'toast';
    toast.className = 'toast';
    document.body.appendChild(toast);
    return toast;
}

/**
 * Initialize smooth scrolling for anchor links
 */
function initializeSmoothScrolling() {
    const links = document.querySelectorAll('a[href^="#"]');
    links.forEach(link => {
        link.addEventListener('click', function(e) {
            const href = this.getAttribute('href');
            if (href === '#') return;
            
            const target = document.querySelector(href);
            if (target) {
                e.preventDefault();
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
}

/**
 * Validate email address
 */
function isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

/**
 * Format currency (Vietnamese Dong)
 */
function formatCurrency(amount) {
    return new Intl.NumberFormat('vi-VN', {
        style: 'currency',
        currency: 'VND'
    }).format(amount);
}

/**
 * Format number with Vietnamese locale
 */
function formatNumber(number) {
    return new Intl.NumberFormat('vi-VN').format(number);
}

/**
 * Debounce function for search and other input events
 */
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

/**
 * Throttle function for scroll and resize events
 */
function throttle(func, limit) {
    let inThrottle;
    return function() {
        const args = arguments;
        const context = this;
        if (!inThrottle) {
            func.apply(context, args);
            inThrottle = true;
            setTimeout(() => inThrottle = false, limit);
        }
    };
}

// Export functions for use in other scripts
window.ZPlusShop = {
    showToast,
    updateCounters,
    formatCurrency,
    formatNumber,
    debounce,
    throttle
};
