<?php
// Include file cấu hình ban đầu của `Twig`
require_once __DIR__ . '/../../bootstrap.php';

// Truy vấn database để lấy danh sách
// 1. Include file cấu hình kết nối đến database, khởi tạo kết nối $conn
include_once(__DIR__ . '/../../dbconnect.php');

// Kiểm tra dữ liệu trong session
$data = [];
if (isset($_SESSION['cartdata'])) {
    $data = $_SESSION['cartdata'];
} else { 
    $data = [];
}

// Yêu cầu `Twig` vẽ giao diện được viết trong file `frontend/checkout/cart.html.twig`
// với dữ liệu truyền vào file giao diện được đặt tên là `cartdata`
// dd($data);
echo $twig->render('frontend/checkout/cart.html.twig', ['cartdata' => $data]);
