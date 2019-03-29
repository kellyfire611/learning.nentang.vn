<?php
// Include file cấu hình ban đầu của `Twig`
require_once __DIR__.'/../../bootstrap.php';

// Tạo danh sách sản phẩm mẫu
// Các bạn có thể viết các câu lệnh truy xuất vào database để lấy dữ liệu, ...
$products = [
    [
        'name'          => 'Notebook',
        'description'   => 'Core i7',
        'value'         =>  800.00,
        'date_register' => '2017-06-22',
    ],
    [
        'name'          => 'Mouse',
        'description'   => 'Razer',
        'value'         =>  125.00,
        'date_register' => '2017-10-25',
    ],
    [
        'name'          => 'Keyboard',
        'description'   => 'Mechanical Keyboard',
        'value'         =>  250.00,
        'date_register' => '2017-06-23',
    ],
];

// Yêu cầu `Twig` vẽ giao diện được viết trong file `backend/pages/dashboard.html.twig`
// với dữ liệu truyền vào file giao diện được đặt tên là `products`
echo $twig->render('backend/pages/dashboard.html.twig', ['products' => $products] );