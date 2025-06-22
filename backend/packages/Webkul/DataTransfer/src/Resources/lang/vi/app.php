<?php

return [
    'importers' => [
        'customers' => [
            'title' => 'Khách hàng',

            'validation' => [
                'errors' => [
                    'duplicate-email'        => 'Email: \'%s\' được tìm thấy nhiều hơn một lần trong tệp nhập.',
                    'duplicate-phone'        => 'Điện thoại: \'%s\' được tìm thấy nhiều hơn một lần trong tệp nhập.',
                    'email-not-found'        => 'Email: \'%s\' không tìm thấy trong hệ thống.',
                    'invalid-customer-group' => 'Nhóm khách hàng không hợp lệ hoặc không được hỗ trợ',
                ],
            ],
        ],

        'products' => [
            'title' => 'Sản phẩm',

            'validation' => [
                'errors' => [
                    'duplicate-url-key'         => 'Khóa URL: \'%s\' đã được tạo cho một mục với SKU: \'%s\'.',
                    'invalid-attribute-family'  => 'Giá trị không hợp lệ cho cột họ thuộc tính (họ thuộc tính không tồn tại?)',
                    'invalid-type'              => 'Loại sản phẩm không hợp lệ hoặc không được hỗ trợ',
                    'sku-not-found'             => 'Không tìm thấy sản phẩm với SKU được chỉ định',
                    'super-attribute-not-found' => 'Thuộc tính cha với mã: \'%s\' không tìm thấy hoặc không thuộc về họ thuộc tính: \'%s\'',
                ],
            ],
        ],

        'tax-rates' => [
            'title' => 'Tỷ lệ thuế',

            'validation' => [
                'errors' => [
                    'duplicate-identifier' => 'Định danh: \'%s\' được tìm thấy nhiều hơn một lần trong tệp nhập.',
                    'identifier-not-found' => 'Định danh: \'%s\' không tìm thấy trong hệ thống.',
                ],
            ],
        ],
    ],

    'validation' => [
        'errors' => [
            'column-empty-headers' => 'Các cột số "%s" có tiêu đề trống.',
            'column-name-invalid'  => 'Tên cột không hợp lệ: "%s".',
            'column-not-found'     => 'Không tìm thấy các cột bắt buộc: %s.',
            'column-numbers'       => 'Số cột không tương ứng với số hàng trong tiêu đề.',
            'invalid-attribute'    => 'Tiêu đề chứa (các) thuộc tính không hợp lệ: "%s".',
            'system'               => 'Đã xảy ra lỗi hệ thống không mong muốn.',
            'wrong-quotes'         => 'Dấu ngoặc kép cong được sử dụng thay vì dấu ngoặc kép thẳng.',
        ],
    ],
];