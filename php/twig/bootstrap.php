<?php
// Load các thư viện (packages) do Composer quản lý vào chương trình
require_once __DIR__.'/vendor/autoload.php';

// Start session
session_start();

// Chỉ định thư mục `templates` (nơi Twig sẽ biên dịch cú pháp Twig thành các đoạn code PHP)
$loader = new Twig_Loader_Filesystem(__DIR__.'/templates');

// Khởi tạo Twig
$twig = new Twig_Environment($loader);

// Tạo biến global để có thể sử dụng trong tất cả các view được render bởi TWIG
$twig->addGlobal('session', $_SESSION);

// Bổ sung hàm để tiện debug code
if (!function_exists('dd')) {
    function dd()
    {
        array_map(function($x) { 
            dump($x); 
        }, func_get_args());
        die;
    }
 }