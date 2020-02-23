<?php
// Include file cấu hình ban đầu của `Twig`
require_once __DIR__ . '/../../bootstrap.php';

// Truy vấn database để lấy danh sách
// 1. Include file cấu hình kết nối đến database, khởi tạo kết nối $conn
include_once(__DIR__ . '/../../dbconnect.php');

// Lấy thông tin người dùng gởi đến
$id = $_POST['id'];
$product_name = $_POST['product_name'];
$quantity = $_POST['quantity'];
$list_price = $_POST['list_price'];
$list_price_after_discount = empty($_POST['list_price_after_discount']) ? 0 : $_POST['list_price_after_discount'];
$image = $_POST['image'];
$is_fixed = $_POST['is_fixed'];
$discount_amount = $_POST['discount_amount'];

// Lưu trữ giỏ hàng trong session
// Nếu khách hàng đặt hàng cùng sản phẩm đã có trong giỏ hàng => cập nhật lại Số lượng, Thành tiền
if (isset($_SESSION['cartdata'])) {
    $data = $_SESSION['cartdata'];
    $data[$id] = array(
        'id' => $id,
        'product_name' => $product_name,
        'quantity' => $quantity,
        'list_price' => $list_price,
        'list_price_after_discount' => $list_price_after_discount,
        'amount' => ($quantity * $list_price_after_discount),
        'image' => $image,
        'is_fixed' => $is_fixed,
        'discount_amount' => $discount_amount,
    );

    // lưu dữ liệu giỏ hàng vào session
    $_SESSION['cartdata'] = $data;
} else { // Nếu khách hàng đặt hàng sản phẩm chưa có trong giỏ hàng => thêm vào
    $data[$id] = array(
        'id' => $id,
        'product_name' => $product_name,
        'quantity' => $quantity,
        'list_price' => $list_price,
        'list_price_after_discount' => $list_price_after_discount,
        'amount' => ($quantity * $list_price_after_discount),
        'image' => $image,
        'is_fixed' => $is_fixed,
        'discount_amount' => $discount_amount,
    );

    // lưu dữ liệu giỏ hàng vào session
    $_SESSION['cartdata'] = $data;
}

echo json_encode($_SESSION['cartdata']);
