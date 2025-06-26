# ZPlus Theme for Bagisto

A custom theme for Bagisto e-commerce platform based on the ZPlus Shop design mockup.

## Features

- **Mobile-first responsive design** - Optimized for mobile devices with desktop fallbacks
- **Vietnamese localization** - Fully localized interface in Vietnamese
- **Modern UI components** - Clean, modern design with Lucide icons
- **Complete page templates** - Home, product detail, category listing, and shopping cart pages
- **Interactive elements** - AJAX cart operations, product filtering, and user feedback

## Structure

```
packages/Zplus/Theme/
├── src/
│   ├── Providers/
│   │   └── ThemeServiceProvider.php    # Service provider for theme registration
│   └── Resources/
│       ├── assets/
│       │   ├── css/                    # Theme stylesheets (main.css, mobile.css)
│       │   ├── js/                     # Theme JavaScript files
│       │   └── images/                 # Theme images and assets
│       └── views/
│           ├── components/layouts/     # Layout components (header, footer, main)
│           ├── home/                   # Home page template
│           ├── products/               # Product detail page template
│           ├── categories/             # Category listing page template
│           └── checkout/cart/          # Shopping cart page template
├── composer.json                       # Package definition
├── package.json                       # Node.js dependencies for building assets
└── vite.config.js                     # Vite configuration for asset building
```

## Installation

The theme is automatically loaded when the ZPlus theme is set as the default shop theme in `config/themes.php`.

## Configuration

The theme is configured in `config/themes.php`:

```php
'shop-default' => 'zplus',

'shop' => [
    'zplus' => [
        'name'        => 'ZPlus',
        'assets_path' => 'public/themes/shop/zplus',
        'views_path'  => 'packages/Zplus/Theme/src/Resources/views',
        'vite'        => [
            'hot_file'                 => 'shop-zplus-vite.hot',
            'build_directory'          => 'themes/shop/zplus/build',
            'package_assets_directory' => 'src/Resources/assets',
        ],
    ],
],
```

## Building Assets

To build theme assets:

```bash
cd packages/Zplus/Theme
npm install
npm run build
```

For development:

```bash
npm run dev
```

## Theme Components

### Layout Components
- **Header** - Mobile and desktop navigation with search, cart, and user account links
- **Footer** - Company information, links, newsletter signup, and mobile bottom navigation
- **Main Layout** - Base layout with proper meta tags and asset loading

### Page Templates
- **Home** - Hero section, featured categories, flash sale, product listings
- **Product Detail** - Product gallery, information, options, reviews, related products
- **Category Listing** - Product grid/list with filtering, sorting, and pagination
- **Shopping Cart** - Cart items management, order summary, checkout actions

### Features
- Responsive design (mobile-first)
- AJAX cart operations
- Product image galleries
- Rating and review systems
- Search functionality
- Newsletter subscription
- Social media integration
- Payment method icons

## Customization

The theme can be customized by:

1. **Modifying CSS** - Edit files in `src/Resources/assets/css/`
2. **Updating templates** - Modify Blade templates in `src/Resources/views/`
3. **Adding JavaScript** - Update files in `src/Resources/assets/js/`
4. **Changing images** - Replace files in `src/Resources/assets/images/`

## License

This theme is proprietary to ZPlus and built for the ZPlus Shop e-commerce platform.