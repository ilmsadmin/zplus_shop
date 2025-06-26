# ZPlus Shop - E-commerce Mockup

This directory contains a complete set of modern, responsive HTML mockups for an e-commerce website inspired by Bagisto's architecture but with a contemporary mobile-first design approach.

## 📁 Project Structure

```
mock/
├── css/
│   ├── main.css          # Core CSS framework with design system
│   └── mobile.css        # Mobile-specific styles and bottom navigation
├── images/
│   ├── *.svg            # Placeholder images and icons
│   └── placeholder assets for products and branding
├── js/                  # JavaScript directory (reserved for future use)
├── fonts/              # Custom fonts directory (reserved for future use)
├── index.html          # Home page
├── category.html       # Product category/listing page
├── product-detail.html # Individual product detail page
├── cart.html          # Shopping cart page
├── checkout.html       # Multi-step checkout process
└── README.md          # This file
```

## 🎨 Design Features

### Mobile-First Approach
- **iOS-style bottom navigation** with 5 main tabs
- **Touch-friendly interfaces** optimized for mobile devices
- **Safe area support** for modern iOS devices
- **Responsive design** that scales beautifully from mobile to desktop

### Modern UI Components
- **Design system** based on CSS custom properties
- **Contemporary color palette** with primary, secondary, and accent colors
- **Flexible grid system** for consistent layouts
- **Component library** including buttons, cards, forms, badges
- **Animation and loading states** for enhanced user experience

### E-commerce Functionality
- **Product browsing** with category filters and search
- **Advanced product filtering** with price range, brands, ratings
- **Shopping cart management** with quantity controls
- **Multi-step checkout process** with form validation
- **Payment method integration** (COD, bank transfer, e-wallets, credit cards)
- **Order management** and tracking features

## 📱 Pages Overview

### 1. Home Page (`index.html`)
- **Responsive header** with search and account actions
- **Hero section** with call-to-action
- **Category grid** with visual icons
- **Flash sale section** with countdown timer
- **Featured products** showcase
- **Feature highlights** and value propositions
- **Comprehensive footer** with company info and links

### 2. Category Page (`category.html`)
- **Advanced filtering sidebar** with multiple filter types
- **Product grid/list view** toggle
- **Sorting options** and pagination
- **Mobile filter drawer** for touch devices
- **Product cards** with hover actions and quick add

### 3. Product Detail Page (`product-detail.html`)
- **Image gallery** with thumbnail navigation
- **Product options** (variants, quantity selection)
- **Tabbed content** (description, specifications, reviews, shipping)
- **Related products** suggestions
- **Add to cart/wishlist** functionality

### 4. Shopping Cart (`cart.html`)
- **Mobile cart interface** with touch controls
- **Desktop table layout** with detailed information
- **Quantity management** and item removal
- **Cart summary** with totals and discounts
- **Coupon/discount** application
- **Product recommendations** for upselling

### 5. Checkout Page (`checkout.html`)
- **Multi-step process** (Information → Shipping → Payment → Review)
- **Form validation** and error handling
- **Multiple payment methods** with appropriate forms
- **Order summary** sidebar with real-time updates
- **Address management** with location selection
- **Terms and conditions** acceptance

## 🎯 Technical Highlights

### CSS Architecture
- **Custom properties** for consistent theming
- **Modern flexbox and grid** layouts
- **Component-based** styling approach
- **Mobile-first** responsive design
- **Animation** and transition effects

### JavaScript Features
- **Step navigation** for checkout process
- **Form validation** and error handling
- **Toast notifications** for user feedback
- **Dynamic content** updates (cart counts, totals)
- **Mobile navigation** state management

### Responsive Design
- **Breakpoint system** for different screen sizes
- **Flexible layouts** that adapt to content
- **Touch-optimized** interactions for mobile
- **Desktop enhancements** for larger screens

## 🚀 Getting Started

1. **Open any HTML file** in a modern web browser
2. **Navigate between pages** using the navigation links
3. **Test responsive design** by resizing the browser window
4. **Try mobile view** by using browser developer tools

## 🌐 Vietnamese Localization

All content is localized in Vietnamese (vi-VN) including:
- **Interface text** and labels
- **Product information** and descriptions
- **Form placeholders** and validation messages
- **Navigation items** and categories
- **Currency formatting** (Vietnamese Dong - ₫)

## 🎨 Color Palette

```css
Primary: #007AFF (iOS Blue)
Success: #34C759 (iOS Green)
Warning: #FF9500 (iOS Orange)
Error: #FF3B30 (iOS Red)
Gray Scale: #000000 to #FFFFFF (Multiple shades)
```

## 📐 Typography

- **Primary Font**: Inter (Google Fonts)
- **Font Weights**: 300, 400, 500, 600, 700
- **Responsive Typography**: Scales based on screen size

## 🔧 Customization

### Adding New Components
1. Add styles to `css/main.css` following the existing pattern
2. Use CSS custom properties for consistency
3. Follow mobile-first responsive design principles

### Modifying Colors
1. Update CSS custom properties in `:root` selector
2. Colors will automatically update throughout the design

### Adding New Pages
1. Copy an existing HTML file as a template
2. Update the content while keeping the header/footer structure
3. Add any new styles to the CSS files

## 🌟 Features to Implement

### Phase 1 (Completed)
- ✅ Responsive home page
- ✅ Category/product listing page
- ✅ Product detail page
- ✅ Shopping cart page
- ✅ Multi-step checkout process
- ✅ Mobile bottom navigation
- ✅ CSS framework and design system

### Phase 2 (Future)
- 🔲 User authentication pages (login/register)
- 🔲 User account/profile pages
- 🔲 Order history and tracking
- 🔲 Wishlist page
- 🔲 Search results page
- 🔲 Contact and support pages

### Phase 3 (Future)
- 🔲 Real product images
- 🔲 Interactive JavaScript functionality
- 🔲 Form submission handling
- 🔲 API integration preparation
- 🔲 Performance optimization

## 🤝 Integration with Bagisto

These mockups are designed to be easily integrated with Bagisto:

1. **Template Structure** follows Bagisto's Blade template patterns
2. **CSS Classes** can be mapped to Bagisto's existing classes
3. **Form Elements** match Bagisto's form structure
4. **Component Organization** aligns with Bagisto's component system

## 📞 Support

For questions or modifications, refer to the Bagisto documentation and the CSS/HTML structure in this mockup as a reference for modern e-commerce design patterns.

---

**Built with ❤️ for modern e-commerce experiences**
