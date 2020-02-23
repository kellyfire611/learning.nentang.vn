<?php
// Include file cấu hình ban đầu của `Twig`
require_once __DIR__ . '/../../bootstrap.php';

// Truy vấn database để lấy danh sách
// 1. Include file cấu hình kết nối đến database, khởi tạo kết nối $conn
include_once(__DIR__ . '/../../dbconnect.php');

// Lấy thông tin người dùng gởi đến
$id = $_POST['id'];

// Lưu trữ giỏ hàng trong session
// Nếu khách hàng đặt hàng cùng sản phẩm đã có trong giỏ hàng => cập nhật lại Số lượng, Thành tiền
if (isset($_SESSION['cartdata'])) {
    $data = $_SESSION['cartdata'];
    
    if(isset($data[$id])) {
        unset($data[$id]);
    }

    // lưu dữ liệu giỏ hàng vào session
    $_SESSION['cartdata'] = $data;
}

echo json_encode($_SESSION['cartdata']);
