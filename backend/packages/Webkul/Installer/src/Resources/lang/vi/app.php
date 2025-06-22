<?php

return [
    'seeders' => [
        'attribute' => [
            'attribute-families' => [
                'default' => 'Mặc định',
            ],

            'attribute-groups' => [
                'description'       => 'Mô tả',
                'general'           => 'Chung',
                'inventories'       => 'Tồn kho',
                'meta-description'  => 'Mô tả Meta',
                'price'             => 'Giá',
                'settings'          => 'Cài đặt',
                'shipping'          => 'Giao hàng',
            ],

            'attributes' => [
                'brand'                => 'Thương hiệu',
                'color'                => 'Màu sắc',
                'cost'                 => 'Chi phí',
                'description'          => 'Mô tả',
                'featured'             => 'Nổi bật',
                'guest-checkout'       => 'Thanh toán khách',
                'height'               => 'Chiều cao',
                'length'               => 'Chiều dài',
                'manage-stock'         => 'Quản lý tồn kho',
                'meta-description'     => 'Mô tả Meta',
                'meta-keywords'        => 'Từ khóa Meta',
                'meta-title'           => 'Tiêu đề Meta',
                'name'                 => 'Tên',
                'new'                  => 'Mới',
                'price'                => 'Giá',
                'product-number'       => 'Số sản phẩm',
                'short-description'    => 'Mô tả ngắn',
                'size'                 => 'Kích thước',
                'sku'                  => 'SKU',
                'special-price'        => 'Giá đặc biệt',
                'special-price-from'   => 'Giá đặc biệt từ',
                'special-price-to'     => 'Giá đặc biệt đến',
                'status'               => 'Trạng thái',
                'tax-category'         => 'Danh mục thuế',
                'url-key'              => 'Khóa URL',
                'visible-individually' => 'Hiển thị riêng lẻ',
                'weight'               => 'Trọng lượng',
                'width'                => 'Chiều rộng',
            ],

            'attribute-options' => [
                'black'  => 'Đen',
                'green'  => 'Xanh lá',
                'l'      => 'L',
                'm'      => 'M',
                'red'    => 'Đỏ',
                's'      => 'S',
                'white'  => 'Trắng',
                'xl'     => 'XL',
                'yellow' => 'Vàng',
            ],
        ],

        'category' => [
            'categories' => [
                'description' => 'Mô tả danh mục gốc',
                'name'        => 'Gốc',
            ],
        ],

        'cms' => [
            'pages' => [
                'about-us' => [
                    'content' => 'Nội dung trang về chúng tôi',
                    'title'   => 'Về chúng tôi',
                ],

                'contact-us' => [
                    'content' => 'Nội dung trang liên hệ',
                    'title'   => 'Liên hệ với chúng tôi',
                ],

                'customer-service' => [
                    'content' => 'Nội dung trang dịch vụ khách hàng',
                    'title'   => 'Dịch vụ khách hàng',
                ],

                'payment-policy' => [
                    'content' => 'Nội dung trang chính sách thanh toán',
                    'title'   => 'Chính sách thanh toán',
                ],

                'privacy-policy' => [
                    'content' => 'Nội dung trang chính sách bảo mật',
                    'title'   => 'Chính sách bảo mật',
                ],

                'refund-policy' => [
                    'content' => 'Nội dung trang chính sách hoàn tiền',
                    'title'   => 'Chính sách hoàn tiền',
                ],

                'return-policy' => [
                    'content' => 'Nội dung trang chính sách đổi trả',
                    'title'   => 'Chính sách đổi trả',
                ],

                'shipping-policy' => [
                    'content' => 'Nội dung trang chính sách giao hàng',
                    'title'   => 'Chính sách giao hàng',
                ],

                'terms-conditions' => [
                    'content' => 'Nội dung trang điều khoản & điều kiện',
                    'title'   => 'Điều khoản & Điều kiện',
                ],

                'terms-of-use' => [
                    'content' => 'Nội dung trang điều khoản sử dụng',
                    'title'   => 'Điều khoản sử dụng',
                ],

                'whats-new' => [
                    'content' => 'Nội dung trang có gì mới',
                    'title'   => 'Có gì mới',
                ],
            ],
        ],

        'core' => [
            'channels' => [
                'name'             => 'Mặc định',
                'meta-title'       => 'Cửa hàng demo',
                'meta-keywords'    => 'Từ khóa meta cửa hàng demo',
                'meta-description' => 'Mô tả meta cửa hàng demo',
            ],

            'currencies' => [
                'AED' => 'Dirham Các Tiểu vương quốc Ả Rập Thống nhất',
                'ARS' => 'Peso Argentina',
                'AUD' => 'Đô la Úc',
                'BDT' => 'Taka Bangladesh',
                'BHD' => 'Dinar Bahrain',
                'BRL' => 'Real Brazil',
                'CAD' => 'Đô la Canada',
                'CHF' => 'Franc Thụy Sĩ',
                'CLP' => 'Peso Chile',
                'CNY' => 'Nhân dân tệ Trung Quốc',
                'COP' => 'Peso Colombia',
                'CZK' => 'Koruna Séc',
                'DKK' => 'Krone Đan Mạch',
                'DZD' => 'Dinar Algeria',
                'EGP' => 'Bảng Ai Cập',
                'EUR' => 'Euro',
                'FJD' => 'Đô la Fiji',
                'GBP' => 'Bảng Anh',
                'HKD' => 'Đô la Hồng Kông',
                'HUF' => 'Forint Hungary',
                'IDR' => 'Rupiah Indonesia',
                'ILS' => 'Shekel Israel',
                'INR' => 'Rupee Ấn Độ',
                'JOD' => 'Dinar Jordan',
                'JPY' => 'Yên Nhật',
                'KRW' => 'Won Hàn Quốc',
                'KWD' => 'Dinar Kuwait',
                'KZT' => 'Tenge Kazakhstan',
                'LBP' => 'Bảng Lebanon',
                'LKR' => 'Rupee Sri Lanka',
                'LYD' => 'Dinar Libya',
                'MAD' => 'Dirham Morocco',
                'MUR' => 'Rupee Mauritius',
                'MXN' => 'Peso Mexico',
                'MYR' => 'Ringgit Malaysia',
                'NGN' => 'Naira Nigeria',
                'NOK' => 'Krone Na Uy',
                'NPR' => 'Rupee Nepal',
                'NZD' => 'Đô la New Zealand',
                'OMR' => 'Rial Oman',
                'PAB' => 'Balboa Panama',
                'PEN' => 'Sol Peru',
                'PHP' => 'Peso Philippines',
                'PKR' => 'Rupee Pakistan',
                'PLN' => 'Zloty Ba Lan',
                'PYG' => 'Guarani Paraguay',
                'QAR' => 'Rial Qatar',
                'RON' => 'Leu Romania',
                'RUB' => 'Ruble Nga',
                'SAR' => 'Riyal Ả Rập Xê Út',
                'SEK' => 'Krona Thụy Điển',
                'SGD' => 'Đô la Singapore',
                'THB' => 'Baht Thái Lan',
                'TND' => 'Dinar Tunisia',
                'TRY' => 'Lira Thổ Nhĩ Kỳ',
                'TWD' => 'Đô la Đài Loan',
                'UAH' => 'Hryvnia Ukraine',
                'USD' => 'Đô la Mỹ',
                'UZS' => 'Som Uzbekistan',
                'VEF' => 'Bolívar Venezuela',
                'VND' => 'Đồng Việt Nam',
                'XAF' => 'Franc CFA BEAC',
                'XOF' => 'Franc CFA BCEAO',
                'ZAR' => 'Rand Nam Phi',
                'ZMW' => 'Kwacha Zambia',
            ],

            'locales'    => [
                'ar'    => 'Tiếng Ả Rập',
                'bn'    => 'Tiếng Bengal',
                'ca'    => 'Tiếng Catalan',
                'de'    => 'Tiếng Đức',
                'en'    => 'Tiếng Anh',
                'es'    => 'Tiếng Tây Ban Nha',
                'fa'    => 'Tiếng Ba Tư',
                'fr'    => 'Tiếng Pháp',
                'he'    => 'Tiếng Do Thái',
                'hi_IN' => 'Tiếng Hindi',
                'id'    => 'Tiếng Indonesia',
                'it'    => 'Tiếng Ý',
                'ja'    => 'Tiếng Nhật',
                'nl'    => 'Tiếng Hà Lan',
                'pl'    => 'Tiếng Ba Lan',
                'pt_BR' => 'Tiếng Bồ Đào Nha Brazil',
                'ru'    => 'Tiếng Nga',
                'sin'   => 'Tiếng Sinhala',
                'tr'    => 'Tiếng Thổ Nhĩ Kỳ',
                'uk'    => 'Tiếng Ukraine',
                'zh_CN' => 'Tiếng Trung Quốc',
            ],
        ],

        'customer' => [
            'customer-groups' => [
                'general'   => 'Chung',
                'guest'     => 'Khách',
                'wholesale' => 'Bán sỉ',
            ],
        ],

        'inventory' => [
            'inventory-sources' => [
                'name' => 'Mặc định',
            ],
        ],

        'shop' => [
            'theme-customizations' => [
                'all-products' => [
                    'name' => 'Tất cả sản phẩm',

                    'options' => [
                        'title' => 'Tất cả sản phẩm',
                    ],
                ],

                'bold-collections' => [
                    'content' => [
                        'btn-title'   => 'Xem bộ sưu tập',
                        'description' => 'Giới thiệu bộ sưu tập Bold mới của chúng tôi! Nâng tầm phong cách của bạn với những thiết kế táo bạo và tuyên bố rực rỡ. Khám phá những họa tiết nổi bật và màu sắc táo bạo tái định nghĩa tủ quần áo của bạn. Hãy sẵn sàng đón nhận sự phi thường!',
                        'title'       => 'Hãy sẵn sàng cho bộ sưu tập Bold mới của chúng tôi!',
                    ],

                    'name' => 'Bộ sưu tập Bold',
                ],

                'categories-collections' => [
                    'name' => 'Bộ sưu tập danh mục',
                ],

                'featured-collections' => [
                    'name' => 'Bộ sưu tập nổi bật',

                    'options' => [
                        'title' => 'Sản phẩm nổi bật',
                    ],
                ],

                'footer-links' => [
                    'name' => 'Liên kết chân trang',

                    'options' => [
                        'about-us'         => 'Về chúng tôi',
                        'contact-us'       => 'Liên hệ với chúng tôi',
                        'customer-service' => 'Dịch vụ khách hàng',
                        'payment-policy'   => 'Chính sách thanh toán',
                        'privacy-policy'   => 'Chính sách bảo mật',
                        'refund-policy'    => 'Chính sách hoàn tiền',
                        'return-policy'    => 'Chính sách đổi trả',
                        'shipping-policy'  => 'Chính sách giao hàng',
                        'terms-conditions' => 'Điều khoản & Điều kiện',
                        'terms-of-use'     => 'Điều khoản sử dụng',
                        'whats-new'        => 'Có gì mới',
                    ],
                ],

                'game-container' => [
                    'content' => [
                        'sub-title-1' => 'Bộ sưu tập của chúng tôi',
                        'sub-title-2' => 'Bộ sưu tập của chúng tôi',
                        'title'       => 'Trò chơi với những bổ sung mới của chúng tôi!',
                    ],

                    'name' => 'Thùng chứa trò chơi',
                ],
            ],
        ],

        'user' => [
            'users' => [
                'name' => 'Ví dụ',
            ],
        ],
    ],

    'installer' => [
        'index' => [
            'server-requirements' => [
                'calendar'    => 'Lịch',
                'ctype'       => 'cType',
                'curl'        => 'cURL',
                'dom'         => 'dom',
                'fileinfo'    => 'Thông tin tệp',
                'filter'      => 'Bộ lọc',
                'gd'          => 'GD',
                'hash'        => 'Hash',
                'intl'        => 'intl',
                'json'        => 'JSON',
                'mbstring'    => 'mbstring',
                'openssl'     => 'openssl',
                'pcre'        => 'pcre',
                'pdo'         => 'pdo',
                'php'         => 'PHP',
                'php-version' => '8.1 hoặc cao hơn',
                'session'     => 'session',
                'title'       => 'Yêu cầu hệ thống',
                'tokenizer'   => 'tokenizer',
                'xml'         => 'XML',
            ],

            'environment-configuration' => [
                'allowed-currencies'  => 'Tiền tệ được phép',
                'allowed-locales'     => 'Ngôn ngữ được phép',
                'application-name'    => 'Tên ứng dụng',
                'bagisto'             => 'Bagisto',
                'chinese-yuan'        => 'Nhân dân tệ Trung Quốc (CNY)',
                'database-connection' => 'Kết nối cơ sở dữ liệu',
                'database-hostname'   => 'Tên máy chủ cơ sở dữ liệu',
                'database-name'       => 'Tên cơ sở dữ liệu',
                'database-password'   => 'Mật khẩu cơ sở dữ liệu',
                'database-port'       => 'Cổng cơ sở dữ liệu',
                'database-prefix'     => 'Tiền tố cơ sở dữ liệu',
                'database-username'   => 'Tên người dùng cơ sở dữ liệu',
                'default-currency'    => 'Tiền tệ mặc định',
                'default-locale'      => 'Ngôn ngữ mặc định',
                'default-timezone'    => 'Múi giờ mặc định',
                'default-url'         => 'URL mặc định',
                'default-url-link'    => 'https://localhost',
                'euro'                => 'Euro (EUR)',
                'iranian-rial'        => 'Rial Iran (IRR)',
                'israeli-shekel'      => 'Shekel Israel (ILS)',
                'japanese-yen'        => 'Yên Nhật (JPY)',
                'mysql'               => 'Mysql',
                'pgsql'               => 'pgSQL',
                'pound-sterling'      => 'Bảng Anh (GBP)',
                'russian-ruble'       => 'Ruble Nga (RUB)',
                'saudi-riyal'         => 'Riyal Ả Rập Xê Út (SAR)',
                'singapore-dollar'    => 'Đô la Singapore (SGD)',
                'south-african-rand'  => 'Rand Nam Phi (ZAR)',
                'south-korean-won'    => 'Won Hàn Quốc (KRW)',
                'sqlsrv'              => 'SQLSRV',
                'sri-lankan-rupee'    => 'Rupee Sri Lanka (LKR)',
                'swedish-krona'       => 'Krona Thụy Điển (SEK)',
                'swiss-franc'         => 'Franc Thụy Sĩ (CHF)',
                'thai-baht'           => 'Baht Thái Lan (THB)',
                'title'               => 'Cấu hình cửa hàng',
                'tunisian-dinar'      => 'Dinar Tunisia (TND)',
                'turkish-lira'        => 'Lira Thổ Nhĩ Kỳ (TRY)',
                'ukrainian-hryvnia'   => 'Hryvnia Ukraine (UAH)',
                'united-arab-emirates-dirham' => 'Dirham Các Tiểu vương quốc Ả Rập Thống nhất (AED)',
                'united-states-dollar' => 'Đô la Mỹ (USD)',
                'uzbekistani-som'     => 'Som Uzbekistan (UZS)',
                'venezuelan-bolívar'  => 'Bolívar Venezuela (VEF)',
                'vietnamese-dong'     => 'Đồng Việt Nam (VND)',
                'warning-message'     => 'Lưu ý! Cài đặt cho ngôn ngữ hệ thống mặc định và tiền tệ mặc định của bạn là vĩnh viễn và không thể thay đổi sau khi đã thiết lập.',
                'zambian-kwacha'      => 'Kwacha Zambia (ZMW)',
            ],

            'sample-products' => [
                'download-sample' => 'tải-mẫu',
                'no'              => 'Không',
                'sample-products' => 'Sản phẩm mẫu',
                'title'           => 'Sản phẩm mẫu',
                'yes'             => 'Có',
            ],

            'installation-processing' => [
                'bagisto'      => 'Cài đặt Bagisto',
                'bagisto-info' => 'Tạo bảng cơ sở dữ liệu, có thể mất vài phút',
                'title'        => 'Cài đặt',
            ],

            'installation-completed' => [
                'admin-panel'                => 'Bảng quản trị',
                'bagisto-forums'             => 'Diễn đàn Bagisto',
                'customer-panel'             => 'Bảng khách hàng',
                'explore-bagisto-extensions' => 'Khám phá tiện ích mở rộng Bagisto',
                'title'                      => 'Cài đặt hoàn tất',
                'title-info'                 => 'Bagisto đã được cài đặt thành công trên hệ thống của bạn.',
            ],

            'ready-for-installation' => [
                'create-databsae-table'   => 'Tạo bảng cơ sở dữ liệu',
                'install'                 => 'Cài đặt',
                'install-info'            => 'Bagisto để cài đặt',
                'install-info-button'     => 'Nhấp vào nút bên dưới để',
                'populate-database-table' => 'Điền dữ liệu vào bảng cơ sở dữ liệu',
                'start-installation'      => 'Bắt đầu cài đặt',
                'title'                   => 'Sẵn sàng cài đặt',
            ],

            'start' => [
                'locale'        => 'Ngôn ngữ',
                'main'          => 'Bắt đầu',
                'select-locale' => 'Chọn ngôn ngữ',
                'title'         => 'Cài đặt Bagisto của bạn',
                'welcome-title' => 'Chào mừng đến với Bagisto',
            ],

            'server-requirements' => [
                'calendar'    => 'Lịch',
                'ctype'       => 'cType',
                'curl'        => 'cURL',
                'dom'         => 'dom',
                'fileinfo'    => 'Thông tin tệp',
                'filter'      => 'Bộ lọc',
                'gd'          => 'GD',
                'hash'        => 'Hash',
                'intl'        => 'intl',
                'json'        => 'JSON',
                'mbstring'    => 'mbstring',
                'openssl'     => 'openssl',
                'pcre'        => 'pcre',
                'pdo'         => 'pdo',
                'php'         => 'PHP',
                'php-version' => '8.1 hoặc cao hơn',
                'session'     => 'session',
                'title'       => 'Yêu cầu hệ thống',
                'tokenizer'   => 'tokenizer',
                'xml'         => 'XML',
            ],

            'arabic'                   => 'Tiếng Ả Rập',
            'back'                     => 'Quay lại',
            'bagisto'                  => 'Bagisto',
            'bagisto-info'             => 'một dự án cộng đồng của',
            'bagisto-logo'             => 'Logo Bagisto',
            'bengali'                  => 'Tiếng Bengal',
            'chinese'                  => 'Tiếng Trung Quốc',
            'continue'                 => 'Tiếp tục',
            'dutch'                    => 'Tiếng Hà Lan',
            'english'                  => 'Tiếng Anh',
            'french'                   => 'Tiếng Pháp',
            'german'                   => 'Tiếng Đức',
            'hebrew'                   => 'Tiếng Do Thái',
            'hindi'                    => 'Tiếng Hindi',
            'installation-description' => 'Cài đặt Bagisto thường bao gồm nhiều bước. Đây là tổng quan về quy trình cài đặt cho Bagisto',
            'installation-info'        => 'Chúng tôi rất vui khi thấy bạn ở đây!',
            'installation-title'       => 'Chào mừng đến với cài đặt',
            'italian'                  => 'Tiếng Ý',
            'japanese'                 => 'Tiếng Nhật',
            'persian'                  => 'Tiếng Ba Tư',
            'polish'                   => 'Tiếng Ba Lan',
            'portuguese'               => 'Tiếng Bồ Đào Nha Brazil',
            'russian'                  => 'Tiếng Nga',
            'sinhala'                  => 'Tiếng Sinhala',
            'spanish'                  => 'Tiếng Tây Ban Nha',
            'title'                    => 'Trình cài đặt Bagisto',
            'turkish'                  => 'Tiếng Thổ Nhĩ Kỳ',
            'ukrainian'                => 'Tiếng Ukraine',
            'webkul'                   => 'Webkul',
        ],
    ],
];