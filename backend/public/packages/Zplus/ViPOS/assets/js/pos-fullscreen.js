/**
 * ViPOS Fullscreen JavaScript
 * Handles fullscreen POS interface functionality
 */

class ViPOSFullscreen {
    constructor() {
        this.cart = new Map();
        this.currentSession = null;
        this.currentCustomer = null;
        this.products = [];
        this.categories = [];
        this.searchTimeout = null;
        this.isProcessing = false;
        
        this.init();
    }

    /**
     * Initialize POS system
     */
    init() {
        this.bindEvents();
        this.loadCategories();
        this.loadProducts();
        this.setupNotifications();
        this.setupKeyboardShortcuts();
        this.startAutoSave();
    }

    /**
     * Bind event listeners
     */
    bindEvents() {
        // Product search
        document.getElementById('productSearch')?.addEventListener('input', (e) => {
            this.handleProductSearch(e.target.value);
        });

        // Customer search
        document.getElementById('customerSearch')?.addEventListener('input', (e) => {
            this.handleCustomerSearch(e.target.value);
        });

        // Checkout button
        document.getElementById('checkoutBtn')?.addEventListener('click', () => {
            this.handleCheckout();
        });

        // Clear cart
        document.getElementById('clearCartBtn')?.addEventListener('click', () => {
            this.clearCart();
        });

        // Payment method selection
        document.querySelectorAll('.payment-method').forEach(btn => {
            btn.addEventListener('click', (e) => {
                this.selectPaymentMethod(e.target.dataset.method);
            });
        });

        // Category filters
        document.addEventListener('click', (e) => {
            if (e.target.classList.contains('category-btn')) {
                this.filterByCategory(e.target.dataset.categoryId);
            }
        });

        // Add to cart buttons
        document.addEventListener('click', (e) => {
            if (e.target.classList.contains('add-to-cart-btn')) {
                e.preventDefault();
                const productId = e.target.dataset.productId;
                this.addToCart(productId);
            }
        });

        // Cart quantity controls
        document.addEventListener('click', (e) => {
            if (e.target.classList.contains('qty-decrease')) {
                const productId = e.target.dataset.productId;
                this.decreaseQuantity(productId);
            } else if (e.target.classList.contains('qty-increase')) {
                const productId = e.target.dataset.productId;
                this.increaseQuantity(productId);
            } else if (e.target.classList.contains('remove-item')) {
                const productId = e.target.dataset.productId;
                this.removeFromCart(productId);
            }
        });

        // Fullscreen toggle
        document.getElementById('fullscreenBtn')?.addEventListener('click', () => {
            this.toggleFullscreen();
        });

        // ESC key to exit fullscreen
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && document.fullscreenElement) {
                this.exitFullscreen();
            }
        });
    }

    /**
     * Setup keyboard shortcuts
     */
    setupKeyboardShortcuts() {
        document.addEventListener('keydown', (e) => {
            // Ctrl/Cmd + F for search
            if ((e.ctrlKey || e.metaKey) && e.key === 'f') {
                e.preventDefault();
                document.getElementById('productSearch')?.focus();
            }
            
            // F11 for fullscreen
            if (e.key === 'F11') {
                e.preventDefault();
                this.toggleFullscreen();
            }
            
            // Enter for checkout
            if (e.key === 'Enter' && e.target.id !== 'productSearch' && e.target.id !== 'customerSearch') {
                if (this.cart.size > 0) {
                    this.handleCheckout();
                }
            }
        });
    }

    /**
     * Load product categories
     */
    async loadCategories() {
        try {
            const response = await fetch('/pos/categories');
            const data = await response.json();
            
            if (data.success) {
                this.categories = data.categories;
                this.renderCategories();
            }
        } catch (error) {
            console.error('Error loading categories:', error);
            this.showNotification('Error loading categories', 'error');
        }
    }

    /**
     * Load products
     */
    async loadProducts(categoryId = null) {
        try {
            let url = '/pos/products';
            if (categoryId) {
                url += `?category_id=${categoryId}`;
            }
            
            const response = await fetch(url);
            const data = await response.json();
            
            if (data.success) {
                this.products = data.products;
                this.renderProducts();
            }
        } catch (error) {
            console.error('Error loading products:', error);
            this.showNotification('Error loading products', 'error');
        }
    }

    /**
     * Render categories
     */
    renderCategories() {
        const container = document.getElementById('categoriesContainer');
        if (!container) return;

        let html = '<button class="category-btn active" data-category-id="">All Products</button>';
        
        this.categories.forEach(category => {
            html += `
                <button class="category-btn" data-category-id="${category.id}">
                    ${category.name}
                </button>
            `;
        });

        container.innerHTML = html;
    }

    /**
     * Render products
     */
    renderProducts() {
        const container = document.getElementById('productsContainer');
        if (!container) return;

        if (this.products.length === 0) {
            container.innerHTML = '<div class="no-products">No products found</div>';
            return;
        }

        let html = '';
        this.products.forEach(product => {
            const imageUrl = product.images?.[0]?.url || '/images/placeholder.png';
            html += `
                <div class="product-item" data-product-id="${product.id}">
                    <div class="product-image">
                        <img src="${imageUrl}" alt="${product.name}" loading="lazy">
                        <button class="add-to-cart-btn" data-product-id="${product.id}">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                    <div class="product-info">
                        <h4 class="product-name">${product.name}</h4>
                        <p class="product-sku">SKU: ${product.sku}</p>
                        <div class="product-price">
                            <span class="price">${this.formatCurrency(product.price)}</span>
                            ${product.quantity > 0 ? 
                                `<span class="stock">Stock: ${product.quantity}</span>` : 
                                '<span class="out-of-stock">Out of Stock</span>'
                            }
                        </div>
                    </div>
                </div>
            `;
        });

        container.innerHTML = html;
    }

    /**
     * Filter products by category
     */
    filterByCategory(categoryId) {
        // Update active category button
        document.querySelectorAll('.category-btn').forEach(btn => {
            btn.classList.toggle('active', btn.dataset.categoryId === categoryId);
        });

        this.loadProducts(categoryId || null);
    }

    /**
     * Handle product search
     */
    handleProductSearch(query) {
        clearTimeout(this.searchTimeout);
        
        this.searchTimeout = setTimeout(() => {
            if (query.length >= 2) {
                this.searchProducts(query);
            } else {
                this.loadProducts();
            }
        }, 300);
    }

    /**
     * Search products
     */
    async searchProducts(query) {
        try {
            const response = await fetch(`/pos/products?search=${encodeURIComponent(query)}`);
            const data = await response.json();
            
            if (data.success) {
                this.products = data.products;
                this.renderProducts();
            }
        } catch (error) {
            console.error('Error searching products:', error);
        }
    }

    /**
     * Handle customer search
     */
    async handleCustomerSearch(query) {
        if (query.length < 2) {
            this.hideCustomerDropdown();
            return;
        }

        try {
            const response = await fetch(`/pos/customers/search?q=${encodeURIComponent(query)}`);
            const data = await response.json();
            
            if (data.success) {
                this.showCustomerDropdown(data.customers);
            }
        } catch (error) {
            console.error('Error searching customers:', error);
        }
    }

    /**
     * Show customer dropdown
     */
    showCustomerDropdown(customers) {
        let dropdown = document.getElementById('customerDropdown');
        if (!dropdown) {
            dropdown = document.createElement('div');
            dropdown.id = 'customerDropdown';
            dropdown.className = 'customer-dropdown';
            document.getElementById('customerSearch').parentNode.appendChild(dropdown);
        }

        let html = '';
        customers.forEach(customer => {
            html += `
                <div class="customer-option" data-customer-id="${customer.id}">
                    <div class="customer-name">${customer.first_name} ${customer.last_name}</div>
                    <div class="customer-email">${customer.email}</div>
                </div>
            `;
        });

        dropdown.innerHTML = html;
        dropdown.style.display = 'block';

        // Add click handlers
        dropdown.querySelectorAll('.customer-option').forEach(option => {
            option.addEventListener('click', () => {
                this.selectCustomer({
                    id: option.dataset.customerId,
                    name: option.querySelector('.customer-name').textContent,
                    email: option.querySelector('.customer-email').textContent
                });
            });
        });
    }

    /**
     * Hide customer dropdown
     */
    hideCustomerDropdown() {
        const dropdown = document.getElementById('customerDropdown');
        if (dropdown) {
            dropdown.style.display = 'none';
        }
    }

    /**
     * Select customer
     */
    selectCustomer(customer) {
        this.currentCustomer = customer;
        document.getElementById('customerSearch').value = customer.name;
        this.hideCustomerDropdown();
        this.updateCustomerDisplay();
    }

    /**
     * Update customer display
     */
    updateCustomerDisplay() {
        const display = document.getElementById('selectedCustomer');
        if (display) {
            if (this.currentCustomer) {
                display.innerHTML = `
                    <div class="customer-info">
                        <strong>${this.currentCustomer.name}</strong>
                        <small>${this.currentCustomer.email}</small>
                        <button class="remove-customer" onclick="vipos.removeCustomer()">×</button>
                    </div>
                `;
            } else {
                display.innerHTML = '';
            }
        }
    }

    /**
     * Remove selected customer
     */
    removeCustomer() {
        this.currentCustomer = null;
        document.getElementById('customerSearch').value = '';
        this.updateCustomerDisplay();
    }

    /**
     * Add product to cart
     */
    async addToCart(productId) {
        const product = this.products.find(p => p.id == productId);
        if (!product) return;

        if (product.quantity <= 0) {
            this.showNotification('Product out of stock', 'warning');
            return;
        }

        const existingItem = this.cart.get(productId);
        const currentQty = existingItem ? existingItem.quantity : 0;

        if (currentQty >= product.quantity) {
            this.showNotification('Cannot add more items than available stock', 'warning');
            return;
        }

        if (existingItem) {
            existingItem.quantity += 1;
            existingItem.total = existingItem.quantity * existingItem.price;
        } else {
            this.cart.set(productId, {
                id: product.id,
                name: product.name,
                sku: product.sku,
                price: product.price,
                quantity: 1,
                total: product.price,
                image: product.images?.[0]?.url || '/images/placeholder.png'
            });
        }

        this.updateCartDisplay();
        this.showCartNotification(product, 'added');
        this.saveCartToStorage();

        // Add visual feedback
        const button = document.querySelector(`[data-product-id="${productId}"]`);
        if (button) {
            button.classList.add('quick-add-effect', 'added');
            setTimeout(() => {
                button.classList.remove('added');
            }, 1000);
        }
    }

    /**
     * Increase quantity
     */
    increaseQuantity(productId) {
        const cartItem = this.cart.get(productId);
        const product = this.products.find(p => p.id == productId);
        
        if (!cartItem || !product) return;

        if (cartItem.quantity >= product.quantity) {
            this.showNotification('Cannot add more items than available stock', 'warning');
            return;
        }

        cartItem.quantity += 1;
        cartItem.total = cartItem.quantity * cartItem.price;
        
        this.updateCartDisplay();
        this.saveCartToStorage();
    }

    /**
     * Decrease quantity
     */
    decreaseQuantity(productId) {
        const cartItem = this.cart.get(productId);
        if (!cartItem) return;

        if (cartItem.quantity > 1) {
            cartItem.quantity -= 1;
            cartItem.total = cartItem.quantity * cartItem.price;
        } else {
            this.cart.delete(productId);
        }

        this.updateCartDisplay();
        this.saveCartToStorage();
    }

    /**
     * Remove item from cart
     */
    removeFromCart(productId) {
        this.cart.delete(productId);
        this.updateCartDisplay();
        this.saveCartToStorage();
    }

    /**
     * Clear entire cart
     */
    clearCart() {
        if (this.cart.size === 0) return;

        if (confirm('Are you sure you want to clear the cart?')) {
            this.cart.clear();
            this.updateCartDisplay();
            this.saveCartToStorage();
            this.showNotification('Cart cleared', 'info');
        }
    }

    /**
     * Update cart display
     */
    updateCartDisplay() {
        const container = document.getElementById('cartItems');
        const totalEl = document.getElementById('cartTotal');
        const countEl = document.getElementById('cartCount');
        const checkoutBtn = document.getElementById('checkoutBtn');

        if (!container) return;

        // Render cart items
        if (this.cart.size === 0) {
            container.innerHTML = '<div class="empty-cart">Cart is empty</div>';
        } else {
            let html = '';
            this.cart.forEach((item, productId) => {
                html += `
                    <div class="cart-item" data-product-id="${productId}">
                        <div class="item-image">
                            <img src="${item.image}" alt="${item.name}">
                        </div>
                        <div class="item-details">
                            <h5>${item.name}</h5>
                            <p>SKU: ${item.sku}</p>
                            <div class="quantity-controls">
                                <button class="qty-decrease" data-product-id="${productId}">-</button>
                                <span class="quantity">${item.quantity}</span>
                                <button class="qty-increase" data-product-id="${productId}">+</button>
                            </div>
                        </div>
                        <div class="item-price">
                            <div class="unit-price">${this.formatCurrency(item.price)}</div>
                            <div class="total-price">${this.formatCurrency(item.total)}</div>
                        </div>
                        <button class="remove-item" data-product-id="${productId}">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                `;
            });
            container.innerHTML = html;
        }

        // Update totals
        const total = this.getCartTotal();
        if (totalEl) totalEl.textContent = this.formatCurrency(total);
        if (countEl) countEl.textContent = this.cart.size;
        
        // Enable/disable checkout button
        if (checkoutBtn) {
            checkoutBtn.disabled = this.cart.size === 0 || this.isProcessing;
        }
    }

    /**
     * Get cart total
     */
    getCartTotal() {
        let total = 0;
        this.cart.forEach(item => {
            total += item.total;
        });
        return total;
    }

    /**
     * Handle checkout
     */
    async handleCheckout() {
        if (this.cart.size === 0) {
            this.showNotification('Cart is empty', 'warning');
            return;
        }

        if (this.isProcessing) return;

        const paymentMethod = document.querySelector('.payment-method.active')?.dataset.method || 'cash';
        
        this.isProcessing = true;
        this.updateCheckoutButton(true);

        try {
            const orderData = {
                customer_id: this.currentCustomer?.id || null,
                payment_method: paymentMethod,
                items: Array.from(this.cart.values()),
                total: this.getCartTotal(),
                notes: document.getElementById('orderNotes')?.value || ''
            };

            const response = await fetch('/pos/checkout', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                },
                body: JSON.stringify(orderData)
            });

            const data = await response.json();

            if (data.success) {
                this.showNotification('Order completed successfully!', 'success');
                this.cart.clear();
                this.currentCustomer = null;
                this.updateCartDisplay();
                this.updateCustomerDisplay();
                this.clearCustomerSearch();
                this.clearCartFromStorage();
                
                // Show receipt or redirect
                if (data.receipt) {
                    this.showReceipt(data.receipt);
                }
            } else {
                throw new Error(data.message || 'Checkout failed');
            }
        } catch (error) {
            console.error('Checkout error:', error);
            this.showNotification(error.message || 'Checkout failed', 'error');
        } finally {
            this.isProcessing = false;
            this.updateCheckoutButton(false);
        }
    }

    /**
     * Update checkout button state
     */
    updateCheckoutButton(loading) {
        const btn = document.getElementById('checkoutBtn');
        if (!btn) return;

        if (loading) {
            btn.classList.add('add-to-cart-loading');
            btn.disabled = true;
        } else {
            btn.classList.remove('add-to-cart-loading');
            btn.disabled = this.cart.size === 0;
        }
    }

    /**
     * Select payment method
     */
    selectPaymentMethod(method) {
        document.querySelectorAll('.payment-method').forEach(btn => {
            btn.classList.toggle('active', btn.dataset.method === method);
        });
    }

    /**
     * Show receipt
     */
    showReceipt(receipt) {
        // Implementation for showing receipt modal or printing
        console.log('Receipt:', receipt);
    }

    /**
     * Clear customer search
     */
    clearCustomerSearch() {
        document.getElementById('customerSearch').value = '';
        this.hideCustomerDropdown();
    }

    /**
     * Setup notifications
     */
    setupNotifications() {
        // Create toast container if not exists
        if (!document.getElementById('toastContainer')) {
            const container = document.createElement('div');
            container.id = 'toastContainer';
            container.className = 'toast-container';
            document.body.appendChild(container);
        }
    }

    /**
     * Show notification
     */
    showNotification(message, type = 'info', duration = 3000) {
        const container = document.getElementById('toastContainer');
        if (!container) return;

        const toast = document.createElement('div');
        toast.className = `cart-notification ${type} show`;
        
        const iconMap = {
            success: '✓',
            error: '✗',
            warning: '⚠',
            info: 'ℹ'
        };

        toast.innerHTML = `
            <div class="notification-header">
                <div class="notification-icon">${iconMap[type] || 'ℹ'}</div>
                <div class="notification-title">${this.capitalizeFirst(type)}</div>
                <button class="notification-close">×</button>
            </div>
            <div class="notification-body">
                <div class="notification-message">${message}</div>
            </div>
            <div class="notification-progress"></div>
        `;

        container.appendChild(toast);

        // Close button handler
        toast.querySelector('.notification-close').addEventListener('click', () => {
            this.hideNotification(toast);
        });

        // Auto hide
        if (duration > 0) {
            const progress = toast.querySelector('.notification-progress');
            progress.style.width = '100%';
            progress.style.transition = `width ${duration}ms linear`;
            
            setTimeout(() => {
                progress.style.width = '0%';
            }, 100);

            setTimeout(() => {
                this.hideNotification(toast);
            }, duration);
        }
    }

    /**
     * Show cart notification
     */
    showCartNotification(product, action) {
        const container = document.getElementById('toastContainer');
        if (!container) return;

        const toast = document.createElement('div');
        toast.className = 'cart-notification success show';
        
        toast.innerHTML = `
            <div class="notification-header">
                <div class="notification-icon">✓</div>
                <div class="notification-title">Added to Cart</div>
                <button class="notification-close">×</button>
            </div>
            <div class="notification-body">
                <img src="${product.images?.[0]?.url || '/images/placeholder.png'}" 
                     class="product-thumbnail" alt="${product.name}">
                <div class="product-details">
                    <div class="product-name">${product.name}</div>
                    <div class="product-info">
                        <span class="quantity-badge">Qty: 1</span>
                        <span class="price-badge">${this.formatCurrency(product.price)}</span>
                    </div>
                </div>
            </div>
            <div class="notification-footer">
                <div class="cart-summary">
                    <span class="cart-total">Total: ${this.formatCurrency(this.getCartTotal())}</span>
                </div>
                <button class="view-cart-btn" onclick="document.getElementById('cartSection').scrollIntoView()">
                    View Cart
                </button>
            </div>
            <div class="notification-progress"></div>
        `;

        container.appendChild(toast);

        // Handlers
        toast.querySelector('.notification-close').addEventListener('click', () => {
            this.hideNotification(toast);
        });

        // Auto hide after 3 seconds
        const progress = toast.querySelector('.notification-progress');
        progress.style.width = '100%';
        progress.style.transition = 'width 3000ms linear';
        
        setTimeout(() => {
            progress.style.width = '0%';
        }, 100);

        setTimeout(() => {
            this.hideNotification(toast);
        }, 3000);
    }

    /**
     * Hide notification
     */
    hideNotification(toast) {
        toast.classList.remove('show');
        toast.classList.add('hide');
        
        setTimeout(() => {
            if (toast.parentNode) {
                toast.parentNode.removeChild(toast);
            }
        }, 400);
    }

    /**
     * Toggle fullscreen
     */
    toggleFullscreen() {
        if (!document.fullscreenElement) {
            document.documentElement.requestFullscreen().catch(err => {
                console.error('Error attempting to enable fullscreen:', err);
            });
        } else {
            document.exitFullscreen();
        }
    }

    /**
     * Exit fullscreen
     */
    exitFullscreen() {
        if (document.fullscreenElement) {
            document.exitFullscreen();
        }
    }

    /**
     * Save cart to localStorage
     */
    saveCartToStorage() {
        const cartData = {
            cart: Array.from(this.cart.entries()),
            customer: this.currentCustomer,
            timestamp: Date.now()
        };
        localStorage.setItem('vipos_cart', JSON.stringify(cartData));
    }

    /**
     * Load cart from localStorage
     */
    loadCartFromStorage() {
        try {
            const data = localStorage.getItem('vipos_cart');
            if (data) {
                const cartData = JSON.parse(data);
                
                // Check if cart is not too old (24 hours)
                if (Date.now() - cartData.timestamp < 24 * 60 * 60 * 1000) {
                    this.cart = new Map(cartData.cart);
                    this.currentCustomer = cartData.customer;
                    this.updateCartDisplay();
                    this.updateCustomerDisplay();
                }
            }
        } catch (error) {
            console.error('Error loading cart from storage:', error);
        }
    }

    /**
     * Clear cart from localStorage
     */
    clearCartFromStorage() {
        localStorage.removeItem('vipos_cart');
    }

    /**
     * Start auto-save
     */
    startAutoSave() {
        setInterval(() => {
            if (this.cart.size > 0) {
                this.saveCartToStorage();
            }
        }, 30000); // Save every 30 seconds
    }

    /**
     * Format currency
     */
    formatCurrency(amount) {
        return new Intl.NumberFormat('en-US', {
            style: 'currency',
            currency: 'USD'
        }).format(amount);
    }

    /**
     * Capitalize first letter
     */
    capitalizeFirst(str) {
        return str.charAt(0).toUpperCase() + str.slice(1);
    }
}

// Initialize POS system when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    window.vipos = new ViPOSFullscreen();
    window.vipos.loadCartFromStorage();
});

// Export for module systems
if (typeof module !== 'undefined' && module.exports) {
    module.exports = ViPOSFullscreen;
}
