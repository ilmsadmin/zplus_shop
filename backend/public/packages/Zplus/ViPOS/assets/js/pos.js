// POS JavaScript functionality - Simple and working version
class PosSystem {
    constructor() {
        this.cart = [];
        this.currentCustomer = null;
        this.currentSession = null;
        this.currentCategoryId = null;
        this.searchTimeout = null;
        this.subtotal = 0;
        this.discount = 0;
        this.tax = 0;
        this.total = 0;
        
        this.init();
    }

    init() {
        this.setupEventListeners();
        this.checkCurrentSession();
        this.loadCartFromStorage();
        this.updateCurrentTime();
        this.calculateTotal();
        this.loadProducts();
        this.initCustomerSection();
    }

    setupEventListeners() {
        // Search functionality
        const productSearch = document.getElementById('product-search');
        if (productSearch) {
            productSearch.addEventListener('input', (e) => {
                this.searchProducts(e.target.value);
            });
        }

        // Category buttons
        const categoryBtns = document.querySelectorAll('.category-btn');
        categoryBtns.forEach(btn => {
            btn.addEventListener('click', (e) => {
                this.filterByCategory(e.target.dataset.category);
                categoryBtns.forEach(b => b.classList.remove('active'));
                e.target.classList.add('active');
            });
        });

        // Customer search
        const customerSearch = document.getElementById('customer-search');
        if (customerSearch) {
            customerSearch.addEventListener('input', (e) => {
                this.searchCustomers(e.target.value);
            });
        }

        // Close modal on overlay click
        document.addEventListener('click', (e) => {
            if (e.target.classList.contains('modal-overlay')) {
                this.hideAllModals();
            }
        });
    }

    // Session Management
    async checkCurrentSession() {
        console.log('Checking current session...');
        
        // First check localStorage
        const savedSession = localStorage.getItem('pos_session');
        if (savedSession) {
            try {
                this.currentSession = JSON.parse(savedSession);
                console.log('Found local session:', this.currentSession);
                
                // Verify with backend
                const sessionValid = await this.verifyActiveSession();
                if (sessionValid) {
                    console.log('Session verified with backend');
                    this.updateSessionUI(true);
                    return;
                } else {
                    console.log('Local session invalid, clearing...');
                    localStorage.removeItem('pos_session');
                    this.currentSession = null;
                }
            } catch (error) {
                console.error('Error parsing saved session:', error);
                localStorage.removeItem('pos_session');
                this.currentSession = null;
            }
        }
        
        // No valid local session, check backend
        console.log('No valid local session, checking backend...');
        const sessionValid = await this.verifyActiveSession();
        if (sessionValid) {
            console.log('Active session found on backend');
            this.updateSessionUI(true);
        } else {
            console.log('No active session found');
            this.updateSessionUI(false);
        }
    }

    updateSessionUI(hasActiveSession) {
        const shiftStatus = document.getElementById('shift-status');
        const shiftBtn = document.getElementById('shift-btn');
        const shiftBtnText = document.getElementById('shift-btn-text');
        const noShiftScreen = document.getElementById('no-shift-screen');
        const posMain = document.getElementById('pos-main');

        if (hasActiveSession) {
            if (shiftStatus) shiftStatus.innerHTML = '<span class="shift-indicator">🟢</span><span class="shift-text">Ca đang mở</span>';
            if (shiftBtnText) shiftBtnText.textContent = 'Đóng ca';
            if (noShiftScreen) noShiftScreen.style.display = 'none';
            if (posMain) posMain.style.display = 'flex';
        } else {
            if (shiftStatus) shiftStatus.innerHTML = '<span class="shift-indicator">🔴</span><span class="shift-text">Ca đóng</span>';
            if (shiftBtnText) shiftBtnText.textContent = 'Mở ca';
            if (noShiftScreen) noShiftScreen.style.display = 'flex';
            if (posMain) posMain.style.display = 'none';
        }

        // Update checkout button state when session status changes
        this.updateCartUI();
    }

    // Load products from API
    async loadProducts(categoryId = null, searchTerm = null) {
        try {
            const loadingElement = document.getElementById('products-loading');
            const productsGrid = document.getElementById('products-grid');
            
            if (loadingElement) {
                loadingElement.style.display = 'flex';
            }

            let url = '/admin/vipos/transactions/products';
            const params = new URLSearchParams();
            
            if (categoryId && categoryId !== 'all') {
                params.append('category_id', categoryId);
            }
            
            if (searchTerm) {
                params.append('search', searchTerm);
            }
            
            if (params.toString()) {
                url += '?' + params.toString();
            }

            const response = await fetch(url, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });

            if (!response.ok) {
                throw new Error('Failed to fetch products');
            }

            const data = await response.json();
            
            if (data.success) {
                this.renderProducts(data.products);
            } else {
                throw new Error(data.message || 'Failed to load products');
            }

        } catch (error) {
            console.error('Error loading products:', error);
            const productsGrid = document.getElementById('products-grid');
            if (productsGrid) {
                productsGrid.innerHTML = `
                    <div class="loading-message">
                        <div class="loading-icon">❌</div>
                        <div>Lỗi khi tải sản phẩm</div>
                        <button onclick="pos.loadProducts()" style="margin-top: 10px; padding: 5px 10px; background: #667eea; color: white; border: none; border-radius: 4px;">Thử lại</button>
                    </div>
                `;
            }
        }
    }

    renderProducts(products) {
        const productsGrid = document.getElementById('products-grid');
        if (!productsGrid) return;

        if (products.length === 0) {
            productsGrid.innerHTML = `
                <div class="loading-message">
                    <div class="loading-icon">📦</div>
                    <div>Không tìm thấy sản phẩm</div>
                </div>
            `;
            return;
        }

        const productsHTML = products.map(product => {
            // Check if image is from same origin to avoid CORS issues
            let imageHTML = '📦';
            if (product.image) {
                try {
                    const imageUrl = new URL(product.image, window.location.origin);
                    // Only show image if it's from same origin or relative path
                    if (imageUrl.origin === window.location.origin || product.image.startsWith('/')) {
                        imageHTML = `<img src="${product.image}" alt="${product.name}" style="width: 100%; height: 100%; object-fit: cover;" onerror="this.style.display='none'; this.parentElement.innerHTML='📦';">`;
                    }
                } catch (e) {
                    // If URL parsing fails, treat as relative path
                    if (product.image.startsWith('/')) {
                        imageHTML = `<img src="${product.image}" alt="${product.name}" style="width: 100%; height: 100%; object-fit: cover;" onerror="this.style.display='none'; this.parentElement.innerHTML='📦';">`;
                    }
                }
            }
            
            return `
                <div class="product-card" data-id="${product.id}" data-category="${product.category_id || 'all'}">
                    <div class="product-image">
                        ${imageHTML}
                    </div>
                    <div class="product-info">
                        <div class="product-name">${product.name}</div>
                        <div class="product-price">${this.formatCurrency(product.price)}</div>
                        ${product.quantity ? `<div class="product-stock">Còn: ${product.quantity}</div>` : ''}
                    </div>
                    <div class="product-overlay">
                        <button class="add-to-cart-btn" onclick="addToCart(${product.id}, '${product.name.replace(/'/g, "\\'")}', ${product.price})">+</button>
                    </div>
                </div>
            `;
        }).join('');

        productsGrid.innerHTML = productsHTML;
    }

    // Product Management
    searchProducts(searchTerm) {
        // Debounce search to avoid too many API calls
        clearTimeout(this.searchTimeout);
        this.searchTimeout = setTimeout(() => {
            this.loadProducts(this.currentCategoryId || null, searchTerm);
        }, 300);
    }

    filterByCategory(categoryId) {
        this.currentCategoryId = categoryId;
        const searchTerm = document.getElementById('product-search')?.value || null;
        this.loadProducts(categoryId, searchTerm);
    }

    // Cart Management
    addToCart(productId, productName, productPrice) {
        const existingItem = this.cart.find(item => item.product_id === productId);
        
        if (existingItem) {
            existingItem.quantity += 1;
            existingItem.subtotal = existingItem.quantity * existingItem.price;
        } else {
            this.cart.push({
                product_id: productId,
                name: productName,
                price: productPrice,
                quantity: 1,
                subtotal: productPrice
            });
        }

        this.updateCartUI();
        this.calculateTotal();
        this.saveCartToStorage();
        this.showCartNotification(productName);
    }

    updateQuantity(productId, newQuantity) {
        if (newQuantity <= 0) {
            this.removeFromCart(productId);
            return;
        }

        const item = this.cart.find(item => item.product_id === productId);
        if (item) {
            item.quantity = newQuantity;
            item.subtotal = item.quantity * item.price;
            this.updateCartUI();
            this.calculateTotal();
            this.saveCartToStorage();
        }
    }

    removeFromCart(productId) {
        this.cart = this.cart.filter(item => item.product_id !== productId);
        this.updateCartUI();
        this.calculateTotal();
        this.saveCartToStorage();
    }

    clearCart() {
        this.cart = [];
        this.updateCartUI();
        this.calculateTotal();
        this.saveCartToStorage();
    }

    updateCartUI() {
        const cartItems = document.getElementById('cart-items');
        const cartCount = document.getElementById('cart-count');
        
        if (!cartItems || !cartCount) return;
        
        if (this.cart.length === 0) {
            cartItems.innerHTML = `
                <div class="empty-cart">
                    <div class="empty-cart-icon">🛒</div>
                    <div class="empty-cart-text">Giỏ hàng trống</div>
                    <div class="empty-cart-subtitle">Chọn sản phẩm để bắt đầu</div>
                </div>
            `;
            cartCount.textContent = '0 sản phẩm';
        } else {
            let cartHTML = '';
            this.cart.forEach(item => {
                cartHTML += `
                    <div class="cart-item">
                        <div class="cart-item-info">
                            <div class="cart-item-name">${item.name}</div>
                            <div class="cart-item-price">${this.formatCurrency(item.price)}</div>
                        </div>
                        <div class="cart-item-controls">
                            <div class="quantity-controls">
                                <button class="quantity-btn" onclick="pos.updateQuantity(${item.product_id}, ${item.quantity - 1})">-</button>
                                <span class="quantity">${item.quantity}</span>
                                <button class="quantity-btn" onclick="pos.updateQuantity(${item.product_id}, ${item.quantity + 1})">+</button>
                            </div>
                        </div>
                        <div class="cart-item-total">${this.formatCurrency(item.subtotal)}</div>
                        <button class="remove-btn" onclick="pos.removeFromCart(${item.product_id})">×</button>
                    </div>
                `;
            });
            cartItems.innerHTML = cartHTML;
            cartCount.textContent = `${this.cart.length} sản phẩm`;
        }

        const checkoutBtn = document.getElementById('checkout-btn');
        if (checkoutBtn) {
            // Disable checkout if cart is empty OR no active session
            checkoutBtn.disabled = this.cart.length === 0 || !this.currentSession;
            
            // Update button text based on session status
            if (!this.currentSession) {
                checkoutBtn.textContent = '🔒 Mở ca để thanh toán';
            } else if (this.cart.length === 0) {
                checkoutBtn.textContent = '💳 Thanh toán';
            } else {
                checkoutBtn.textContent = '💳 Thanh toán';
            }
        }
    }

    // Customer Management
    async searchCustomers(searchTerm) {
        const suggestions = document.getElementById('customer-suggestions');
        if (!suggestions) return;
        
        if (searchTerm.length > 2) {
            try {
                // Clear previous timeout
                if (this.searchTimeout) {
                    clearTimeout(this.searchTimeout);
                }
                
                // Set loading state
                suggestions.innerHTML = '<div class="customer-suggestion loading">Đang tìm kiếm...</div>';
                suggestions.style.display = 'block';
                
                // Debounce search
                this.searchTimeout = setTimeout(async () => {
                    const response = await fetch(`/admin/vipos/transactions/customers/search?search=${encodeURIComponent(searchTerm)}`, {
                        method: 'GET',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                        }
                    });
                    
                    if (!response.ok) {
                        throw new Error('Failed to search customers');
                    }
                    
                    const data = await response.json();
                    
                    if (data.success && data.customers.length > 0) {
                        suggestions.innerHTML = data.customers.map(customer => `
                            <div class="customer-suggestion" onclick="pos.selectCustomer({id: ${customer.id}, name: '${customer.name}', phone: '${customer.phone}', email: '${customer.email}'})">
                                <div class="customer-name">${customer.name}</div>
                                <div class="customer-contact">${customer.phone} - ${customer.email}</div>
                            </div>
                        `).join('');
                    } else {
                        suggestions.innerHTML = '<div class="customer-suggestion no-results">Không tìm thấy khách hàng nào</div>';
                    }
                    suggestions.style.display = 'block';
                }, 300);
                
            } catch (error) {
                console.error('Error searching customers:', error);
                suggestions.innerHTML = '<div class="customer-suggestion error">Lỗi tìm kiếm khách hàng</div>';
                suggestions.style.display = 'block';
            }
        } else {
            suggestions.style.display = 'none';
        }
    }

    selectCustomer(customer) {
        this.currentCustomer = customer;
        const customerSearch = document.getElementById('customer-search');
        const suggestions = document.getElementById('customer-suggestions');
        const selectedCustomer = document.getElementById('selected-customer');
        const customerNameDisplay = document.getElementById('customer-name-display');
        const customerPhoneDisplay = document.getElementById('customer-phone-display');
        const customerEmailDisplay = document.getElementById('customer-email-display');
        
        if (customerSearch) customerSearch.value = '';
        if (suggestions) suggestions.style.display = 'none';
        if (customerNameDisplay) customerNameDisplay.textContent = customer.name;
        if (customerPhoneDisplay) customerPhoneDisplay.textContent = customer.phone || 'N/A';
        if (customerEmailDisplay) customerEmailDisplay.textContent = customer.email || '';
        if (selectedCustomer) selectedCustomer.style.display = 'block';
        
        this.saveCustomerToStorage();
    }

    clearCustomer() {
        this.currentCustomer = null;
        const selectedCustomer = document.getElementById('selected-customer');
        if (selectedCustomer) selectedCustomer.style.display = 'none';
        this.saveCustomerToStorage();
    }

    initCustomerSection() {
        // Ensure customer section is hidden on init
        const selectedCustomer = document.getElementById('selected-customer');
        if (selectedCustomer) {
            selectedCustomer.style.display = 'none';
        }
        
        // Clear customer search suggestions
        const suggestions = document.getElementById('customer-suggestions');
        if (suggestions) {
            suggestions.style.display = 'none';
        }
        
        // Load customer from storage if exists
        const savedCustomer = localStorage.getItem('pos_customer');
        if (savedCustomer && savedCustomer !== 'null') {
            try {
                const customer = JSON.parse(savedCustomer);
                if (customer && customer.id) {
                    this.selectCustomer(customer);
                }
            } catch (e) {
                console.warn('Invalid customer data in storage');
                localStorage.removeItem('pos_customer');
            }
        }
    }

    // Discount Management
    toggleDiscount() {
        const controls = document.getElementById('discount-controls');
        if (!controls) return;
        
        if (controls.style.display === 'none' || !controls.style.display) {
            controls.style.display = 'flex';
        } else {
            controls.style.display = 'none';
            this.discount = 0;
            this.calculateTotal();
        }
    }

    applyDiscount() {
        const discountType = document.getElementById('discount-type');
        const discountValue = document.getElementById('discount-value');
        if (!discountType || !discountValue) return;
        
        const type = discountType.value;
        const value = parseFloat(discountValue.value) || 0;
        
        if (type === 'percent') {
            this.discount = this.subtotal * (value / 100);
        } else {
            this.discount = value;
        }
        
        this.calculateTotal();
    }

    // Calculation
    calculateTotal() {
        this.subtotal = this.cart.reduce((sum, item) => sum + item.subtotal, 0);
        this.tax = this.subtotal * 0.1; // 10% tax
        this.total = this.subtotal - this.discount + this.tax;

        const subtotalEl = document.getElementById('subtotal');
        const taxEl = document.getElementById('tax');
        const totalEl = document.getElementById('total');
        const discountRow = document.getElementById('discount-row');
        const discountAmount = document.getElementById('discount-amount');

        if (subtotalEl) subtotalEl.textContent = this.formatCurrency(this.subtotal);
        if (taxEl) taxEl.textContent = this.formatCurrency(this.tax);
        if (totalEl) totalEl.textContent = this.formatCurrency(this.total);

        if (discountRow && discountAmount) {
            if (this.discount > 0) {
                discountAmount.textContent = '-' + this.formatCurrency(this.discount);
                discountRow.style.display = 'flex';
            } else {
                discountRow.style.display = 'none';
            }
        }
    }

    // Checkout
    async checkout() {
        console.log('=== Starting Checkout Process ===');
        await this.debugSessionStatus();
        
        if (this.cart.length === 0) {
            this.showNotification('Giỏ hàng trống!', 'error');
            return;
        }

        // Check if session is active before proceeding
        if (!this.currentSession) {
            console.log('No local session found, checking backend...');
            // Try to get session from backend before showing error
            const sessionValid = await this.verifyActiveSession();
            if (!sessionValid) {
                this.showNotification('Vui lòng mở ca làm việc trước khi thanh toán!', 'error');
                return;
            }
        } else {
            // We have a local session, but let's verify it's still valid
            console.log('Local session found, verifying with backend...');
            const sessionValid = await this.verifyActiveSession();
            if (!sessionValid) {
                this.showNotification('Phiên làm việc đã hết hạn. Vui lòng mở ca mới!', 'error');
                // Clear invalid session from localStorage
                localStorage.removeItem('pos_session');
                this.currentSession = null;
                this.updateSessionUI(false);
                return;
            }
        }

        console.log('Session verified, proceeding to payment modal...');
        // Show payment modal
        this.showPaymentModal();
    }

    // Verify if current session is still active on backend
    async verifyActiveSession() {
        try {
            const response = await fetch('/admin/vipos/sessions/current', {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                }
            });

            if (!response.ok) {
                console.error('Session verification failed:', response.status);
                return false;
            }

            const data = await response.json();
            
            // Check if API call was successful
            if (!data.success) {
                console.error('Session verification error:', data.message);
                return false;
            }

            // Check if session exists and is valid
            if (data.session && data.session.status === 'open') {
                // Update local session data if it exists
                this.currentSession = data.session;
                localStorage.setItem('pos_session', JSON.stringify(data.session));
                return true;
            } else {
                // No active session found
                console.log('No active session found on backend');
                return false;
            }
        } catch (error) {
            console.error('Error verifying session:', error);
            return false;
        }
    }

    // Show payment modal for checkout
    showPaymentModal() {
        // Create payment modal if it doesn't exist
        let paymentModal = document.getElementById('payment-modal');
        if (!paymentModal) {
            paymentModal = document.createElement('div');
            paymentModal.id = 'payment-modal';
            paymentModal.className = 'modal-overlay';
            paymentModal.innerHTML = `
                <div class="modal-content">
                    <div class="modal-header">
                        <h3>Thanh toán</h3>
                        <button class="modal-close" onclick="hidePaymentModal()">×</button>
                    </div>
                    <div class="modal-body">
                        <div class="payment-summary">
                            <div class="summary-row">
                                <span>Tổng cộng:</span>
                                <span id="payment-total">${this.formatCurrency(this.total)}</span>
                            </div>
                        </div>
                        <form id="payment-form">
                            <div class="form-group">
                                <label>Phương thức thanh toán *</label>
                                <select name="payment_method" required class="form-input">
                                    <option value="cash" selected>Tiền mặt</option>
                                    <option value="card">Thẻ</option>
                                    <option value="bank_transfer">Chuyển khoản</option>
                                    <option value="other">Khác</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Số tiền khách đưa *</label>
                                <input type="number" name="paid_amount" required class="form-input" 
                                       value="${this.total}" min="${this.total}" step="1000" 
                                       oninput="calculateChange()">
                            </div>
                            <div class="form-group">
                                <label>Tiền thừa</label>
                                <input type="number" id="change_amount" class="form-input" readonly value="0">
                            </div>
                            <div class="form-group">
                                <label>Ghi chú</label>
                                <textarea name="notes" class="form-textarea" placeholder="Ghi chú (không bắt buộc)"></textarea>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button class="btn-secondary" onclick="hidePaymentModal()">Hủy</button>
                        <button class="btn-primary" onclick="processPayment()">Xác nhận thanh toán</button>
                    </div>
                </div>
            `;
            document.body.appendChild(paymentModal);
        } else {
            // Update total in existing modal
            const paymentTotal = paymentModal.querySelector('#payment-total');
            if (paymentTotal) paymentTotal.textContent = this.formatCurrency(this.total);
            const paidAmountInput = paymentModal.querySelector('input[name="paid_amount"]');
            if (paidAmountInput) {
                paidAmountInput.value = this.total;
                paidAmountInput.min = this.total;
            }
        }
        
        this.showModal('payment-modal');
    }

    // Process the actual payment
    async processPayment() {
        const form = document.getElementById('payment-form');
        if (!form) return;

        const formData = new FormData(form);
        const paidAmount = parseFloat(formData.get('paid_amount') || 0);
        const changeAmount = Math.max(0, paidAmount - this.total);

        // Validate minimum payment
        if (paidAmount < this.total) {
            this.showNotification('Số tiền khách đưa không đủ!', 'error');
            return;
        }

        try {
            // Show loading state
            const submitBtn = document.querySelector('#payment-modal .btn-primary');
            const originalText = submitBtn.textContent;
            submitBtn.textContent = 'Đang xử lý...';
            submitBtn.disabled = true;

            // Prepare cart items in the correct format
            const items = this.cart.map(item => ({
                product_id: item.product_id,
                quantity: item.quantity,
                price: item.price,
                name: item.name
            }));

            const response = await fetch('/admin/vipos/transactions/checkout', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    items: items,
                    customer_id: this.currentCustomer?.id || null,
                    payment_method: formData.get('payment_method'),
                    subtotal: this.subtotal,
                    discount_amount: this.discount,
                    tax_amount: this.tax,
                    total: this.total,
                    paid_amount: paidAmount,
                    change_amount: changeAmount,
                    notes: formData.get('notes') || null
                })
            });

            const data = await response.json();
            
            if (data.success) {
                this.showNotification('Thanh toán thành công!', 'success');
                this.hideModal('payment-modal');
                this.clearCart();
                this.clearCustomer();
                this.discount = 0;
                const discountControls = document.getElementById('discount-controls');
                if (discountControls) discountControls.style.display = 'none';
                this.calculateTotal();
            } else {
                let errorMessage = data.message || 'Có lỗi xảy ra';
                
                // Handle session-related errors specifically
                if (response.status === 400 && (errorMessage.includes('phiên giao dịch') || errorMessage.includes('session') || errorMessage.includes('Không tìm thấy phiên'))) {
                    console.log('Session expired during payment, clearing local session');
                    // Clear invalid session only if backend specifically says session is invalid
                    localStorage.removeItem('pos_session');
                    this.currentSession = null;
                    this.updateSessionUI(false);
                    this.hideModal('payment-modal');
                    errorMessage = 'Phiên làm việc đã hết hạn. Vui lòng mở ca mới để tiếp tục!';
                } else {
                    console.log('Payment failed but session might still be valid:', errorMessage);
                }
                
                if (data.errors) {
                    // Display first validation error
                    const firstError = Object.values(data.errors)[0];
                    if (firstError && firstError[0]) {
                        errorMessage = firstError[0];
                    }
                }
                this.showNotification(errorMessage, 'error');
            }
        } catch (error) {
            console.error('Payment error:', error);
            this.showNotification('Lỗi kết nối. Vui lòng thử lại.', 'error');
        } finally {
            // Restore button state
            const submitBtn = document.querySelector('#payment-modal .btn-primary');
            if (submitBtn) {
                submitBtn.textContent = 'Xác nhận thanh toán';
                submitBtn.disabled = false;
            }
        }
    }

    // Debug function to check session status
    async debugSessionStatus() {
        console.log('=== Session Debug Info ===');
        console.log('Local session:', this.currentSession);
        console.log('LocalStorage session:', localStorage.getItem('pos_session'));
        
        try {
            const response = await fetch('/admin/vipos/sessions/current', {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                }
            });
            const data = await response.json();
            console.log('Backend session response:', data);
        } catch (error) {
            console.error('Error getting backend session:', error);
        }
        console.log('=========================');
    }

    // Modal Management
    showModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) modal.style.display = 'flex';
    }

    hideModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) modal.style.display = 'none';
    }

    hideAllModals() {
        const modals = document.querySelectorAll('.modal-overlay');
        modals.forEach(modal => modal.style.display = 'none');
    }

    // Storage Management
    saveCartToStorage() {
        localStorage.setItem('pos_cart', JSON.stringify(this.cart));
    }

    loadCartFromStorage() {
        const savedCart = localStorage.getItem('pos_cart');
        if (savedCart) {
            this.cart = JSON.parse(savedCart);
            this.updateCartUI();
        }
    }

    saveCustomerToStorage() {
        localStorage.setItem('pos_customer', JSON.stringify(this.currentCustomer));
    }

    // Utilities
    formatCurrency(amount) {
        return new Intl.NumberFormat('vi-VN', {
            style: 'currency',
            currency: 'VND'
        }).format(amount);
    }

    updateCurrentTime() {
        const timeElement = document.getElementById('current-time');
        if (timeElement) {
            const now = new Date();
            timeElement.textContent = now.toLocaleTimeString('vi-VN');
            setTimeout(() => this.updateCurrentTime(), 1000);
        }
    }

    showCartNotification(productName) {
        const notification = document.getElementById('cart-notification');
        const productInfo = document.getElementById('notification-product-info');
        
        if (notification && productInfo) {
            productInfo.textContent = productName;
            notification.classList.add('show');
            
            setTimeout(() => {
                notification.classList.remove('show');
            }, 2000);
        }
    }

    async createNewCustomer() {
        const form = document.getElementById('new-customer-form');
        if (!form) return;
        
        const formData = new FormData(form);
        const customerData = {
            first_name: formData.get('first_name'),
            last_name: formData.get('last_name'),
            email: formData.get('email'),
            phone: formData.get('phone'),
            gender: formData.get('gender'),
            date_of_birth: formData.get('date_of_birth')
        };
        
        // Validate required fields
        if (!customerData.first_name || !customerData.last_name || !customerData.email || !customerData.gender) {
            this.showNotification('Vui lòng điền đầy đủ thông tin bắt buộc (Tên, Họ, Email, Giới tính)', 'error');
            return;
        }
        
        try {
            // Show loading state
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalText = submitBtn.textContent;
            submitBtn.textContent = 'Đang tạo...';
            submitBtn.disabled = true;
            
            const response = await fetch('/admin/vipos/transactions/customers/quick-create', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                },
                body: JSON.stringify(customerData)
            });
            
            const data = await response.json();
            
            if (data.success) {
                // Select the newly created customer
                this.selectCustomer(data.customer);
                this.hideModal('customer-modal');
                form.reset();
                this.showNotification('Tạo khách hàng thành công!', 'success');
            } else {
                let errorMessage = data.message || 'Có lỗi xảy ra';
                if (data.errors) {
                    // Display first validation error
                    const firstError = Object.values(data.errors)[0];
                    if (firstError && firstError[0]) {
                        errorMessage = firstError[0];
                    }
                }
                this.showNotification(errorMessage, 'error');
            }
        } catch (error) {
            console.error('Error creating customer:', error);
            this.showNotification('Lỗi kết nối. Vui lòng thử lại.', 'error');
        } finally {
            // Restore button state
            const submitBtn = form.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.textContent = 'Tạo khách hàng';
                submitBtn.disabled = false;
            }
        }
    }

    // Show notification instead of alert
    showNotification(message, type = 'info') {
        // Remove existing notifications
        const existingNotifications = document.querySelectorAll('.pos-notification');
        existingNotifications.forEach(n => n.remove());

        // Create notification element
        const notification = document.createElement('div');
        notification.className = `pos-notification pos-notification-${type}`;
        notification.innerHTML = `
            <div class="notification-content">
                <span class="notification-icon">${type === 'success' ? '✓' : type === 'error' ? '✗' : 'ℹ'}</span>
                <span class="notification-message">${message}</span>
                <button class="notification-close" onclick="this.parentElement.parentElement.remove()">×</button>
            </div>
        `;

        // Add to page
        document.body.appendChild(notification);

        // Auto remove after 5 seconds
        setTimeout(() => {
            if (notification.parentElement) {
                notification.remove();
            }
        }, 5000);
    }
}

// Global functions for onclick handlers
function addToCart(productId, productName, productPrice) {
    if (window.pos) {
        window.pos.addToCart(productId, productName, productPrice);
    }
}

function toggleShift() {
    if (!window.pos) return;
    
    const shiftModal = document.getElementById('shift-modal');
    const shiftModalTitle = document.getElementById('shift-modal-title');
    const shiftActionBtn = document.getElementById('shift-action-btn');
    const shiftOpenForm = document.getElementById('shift-open-form');
    const shiftCloseForm = document.getElementById('shift-close-form');
    
    if (window.pos.currentSession) {
        if (shiftModalTitle) shiftModalTitle.textContent = 'Đóng ca làm việc';
        if (shiftActionBtn) shiftActionBtn.textContent = 'Đóng ca';
        if (shiftOpenForm) shiftOpenForm.style.display = 'none';
        if (shiftCloseForm) shiftCloseForm.style.display = 'block';
        
        const shiftStartTime = document.getElementById('shift-start-time');
        const shiftOpeningCash = document.getElementById('shift-opening-cash');
        if (shiftStartTime) shiftStartTime.textContent = window.pos.currentSession.start_time || '--';
        if (shiftOpeningCash) shiftOpeningCash.textContent = window.pos.formatCurrency(window.pos.currentSession.opening_cash || 0);
    } else {
        if (shiftModalTitle) shiftModalTitle.textContent = 'Mở ca làm việc';
        if (shiftActionBtn) shiftActionBtn.textContent = 'Mở ca';
        if (shiftOpenForm) shiftOpenForm.style.display = 'block';
        if (shiftCloseForm) shiftCloseForm.style.display = 'none';
    }
    
    window.pos.showModal('shift-modal');
}

function processShiftAction() {
    if (!window.pos) return;
    
    if (window.pos.currentSession) {
        closeShift();
    } else {
        openShift();
    }
}

async function openShift() {
    const openingCashEl = document.getElementById('opening-cash');
    const openingNoteEl = document.getElementById('opening-note');
    
    if (!openingCashEl) return;
    
    const openingCash = parseFloat(openingCashEl.value);
    const openingNote = openingNoteEl ? openingNoteEl.value : '';
    
    if (!openingCash || openingCash < 0) {
        alert('Vui lòng nhập số tiền mặt đầu ca hợp lệ');
        return;
    }
    
    try {
        // Show loading state
        const shiftActionBtn = document.getElementById('shift-action-btn');
        if (shiftActionBtn) {
            shiftActionBtn.textContent = 'Đang mở ca...';
            shiftActionBtn.disabled = true;
        }
        
        const response = await fetch('/admin/vipos/sessions/open', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
            },
            body: JSON.stringify({
                opening_balance: openingCash,
                notes: openingNote
            })
        });
        
        const data = await response.json();
        
        if (data.success) {
            // Update local session data
            window.pos.currentSession = data.session;
            localStorage.setItem('pos_session', JSON.stringify(data.session));
            window.pos.updateSessionUI(true);
            window.pos.hideModal('shift-modal');
            
            // Clear form
            openingCashEl.value = '';
            if (openingNoteEl) openingNoteEl.value = '';
            
            window.pos.showNotification('Mở ca thành công!', 'success');
        } else {
            window.pos.showNotification(data.message || 'Lỗi khi mở ca', 'error');
        }
    } catch (error) {
        console.error('Error opening shift:', error);
        window.pos.showNotification('Lỗi kết nối. Vui lòng thử lại.', 'error');
    } finally {
        // Restore button state
        const shiftActionBtn = document.getElementById('shift-action-btn');
        if (shiftActionBtn) {
            shiftActionBtn.textContent = 'Mở ca';
            shiftActionBtn.disabled = false;
        }
    }
}

async function closeShift() {
    const closingCashEl = document.getElementById('closing-cash');
    const closingNoteEl = document.getElementById('closing-note');
    
    if (!closingCashEl) return;
    
    const closingCash = parseFloat(closingCashEl.value);
    const closingNote = closingNoteEl ? closingNoteEl.value : '';
    
    if (!closingCash || closingCash < 0) {
        alert('Vui lòng nhập số tiền mặt cuối ca hợp lệ');
        return;
    }
    
    if (!window.pos.currentSession || !window.pos.currentSession.id) {
        alert('Không tìm thấy ca làm việc để đóng');
        return;
    }
    
    try {
        // Show loading state
        const shiftActionBtn = document.getElementById('shift-action-btn');
        if (shiftActionBtn) {
            shiftActionBtn.textContent = 'Đang đóng ca...';
            shiftActionBtn.disabled = true;
        }
        
        const response = await fetch(`/admin/vipos/sessions/${window.pos.currentSession.id}/close`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
            },
            body: JSON.stringify({
                closing_balance: closingCash,
                notes: closingNote
            })
        });
        
        const data = await response.json();
        
        if (data.success) {
            // Clear local session data
            localStorage.removeItem('pos_session');
            window.pos.currentSession = null;
            window.pos.updateSessionUI(false);
            window.pos.hideModal('shift-modal');
            
            // Clear form
            closingCashEl.value = '';
            if (closingNoteEl) closingNoteEl.value = '';
            
            window.pos.showNotification('Đóng ca thành công!', 'success');
        } else {
            window.pos.showNotification(data.message || 'Lỗi khi đóng ca', 'error');
        }
    } catch (error) {
        console.error('Error closing shift:', error);
        window.pos.showNotification('Lỗi kết nối. Vui lòng thử lại.', 'error');
    } finally {
        // Restore button state
        const shiftActionBtn = document.getElementById('shift-action-btn');
        if (shiftActionBtn) {
            shiftActionBtn.textContent = 'Đóng ca';
            shiftActionBtn.disabled = false;
        }
    }
}

function hideShiftModal() {
    if (window.pos) window.pos.hideModal('shift-modal');
}

function showNewCustomerForm() {
    if (window.pos) window.pos.showModal('customer-modal');
}

function hideNewCustomerForm() {
    if (window.pos) window.pos.hideModal('customer-modal');
}

function createNewCustomer() {
    if (window.pos) window.pos.createNewCustomer();
}

function searchCustomers(searchTerm) {
    if (window.pos) window.pos.searchCustomers(searchTerm);
}

function clearCustomer() {
    if (window.pos) window.pos.clearCustomer();
}

function toggleDiscount() {
    if (window.pos) window.pos.toggleDiscount();
}

function applyDiscount() {
    if (window.pos) window.pos.applyDiscount();
}

function clearCart() {
    if (window.pos && confirm('Bạn có chắc muốn xóa tất cả sản phẩm trong giỏ hàng?')) {
        window.pos.clearCart();
    }
}

function checkout() {
    if (window.pos) window.pos.checkout();
}

// Global functions for payment modal
function hidePaymentModal() {
    if (window.pos) window.pos.hideModal('payment-modal');
}

function processPayment() {
    if (window.pos) window.pos.processPayment();
}

function calculateChange() {
    const paidAmountInput = document.querySelector('#payment-modal input[name="paid_amount"]');
    const changeAmountInput = document.getElementById('change_amount');
    
    if (paidAmountInput && changeAmountInput && window.pos) {
        const paidAmount = parseFloat(paidAmountInput.value || 0);
        const total = window.pos.total;
        const change = Math.max(0, paidAmount - total);
        changeAmountInput.value = change;
    }
}

// Global function to debug session status
function debugSession() {
    if (window.pos) window.pos.debugSessionStatus();
}

// Initialize POS system when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    window.pos = new PosSystem();
});
