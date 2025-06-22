<?php

return [
    'checkout' => [
        'cart' => [
            'integrity' => [
                'qty-missing'   => 'Ít nhất một sản phẩm phải có số lượng lớn hơn 1.',
            ],

            'invalid-file-extension'   => 'Tìm thấy định dạng tệp không hợp lệ.',
            'inventory-warning'        => 'Số lượng yêu cầu không có sẵn, vui lòng thử lại sau.',
            'missing-links'            => 'Thiếu liên kết tải xuống cho sản phẩm này.',
            'missing-options'          => 'Thiếu tùy chọn cho sản phẩm này.',
            'selected-products-simple' => 'Các sản phẩm đã chọn phải là loại sản phẩm đơn giản.',
        ],
    ],

    'datagrid' => [
        'copy-of-slug'                  => 'ban-sao-cua-:value',
        'copy-of'                       => 'Bản sao của :value',
        'variant-already-exist-message' => 'Biến thể với các tùy chọn thuộc tính tương tự đã tồn tại.',
    ],

    'response' => [
        'product-can-not-be-copied' => 'Sản phẩm loại :type không thể được sao chép',
    ],

    'sort-by'  => [
        'options' => [
            'cheapest-first'  => 'Rẻ nhất trước',
            'expensive-first' => 'Đắt nhất trước',
            'from-a-z'        => 'Từ A-Z',
            'from-z-a'        => 'Từ Z-A',
            'latest-first'    => 'Mới nhất trước',
            'oldest-first'    => 'Cũ nhất trước',
        ],
    ],

    'type'     => [
        'abstract'     => [
            'offers' => 'Mua :qty với giá :price mỗi cái và tiết kiệm :discount',
        ],

        'bundle'       => 'Gói',
        'booking'      => 'Đặt chỗ',
        'configurable' => 'Có thể cấu hình',
        'downloadable' => 'Có thể tải xuống',
        'grouped'      => 'Nhóm',
        'simple'       => 'Đơn giản',
        'virtual'      => 'Ảo',
    ],
];