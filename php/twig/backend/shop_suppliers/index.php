<?php
// Include file cấu hình ban đầu của `Twig`
require_once __DIR__ . '/../../bootstrap.php';

// Truy vấn database để lấy danh sách
// 1. Include file cấu hình kết nối đến database, khởi tạo kết nối $conn
include_once(__DIR__ . '/../../dbconnect.php');

// 2. Chuẩn bị câu truy vấn $sql
$stt = 1;
$sql = "select * from `shop_suppliers`";

// 3. Thực thi câu truy vấn SQL để lấy về dữ liệu
$result = mysqli_query($conn, $sql);

// 4. Khi thực thi các truy vấn dạng SELECT, dữ liệu lấy về cần phải phân tích để sử dụng
// Thông thường, chúng ta sẽ sử dụng vòng lặp while để duyệt danh sách các dòng dữ liệu được SELECT
// Ta sẽ tạo 1 mảng array để chứa các dữ liệu được trả về
$data = [];
while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
    $data[] = array(
        'supplier_code' => $row['supplier_code'],
        'supplier_name' => $row['supplier_name'],
        'description' => $row['description'],
        'image' => $row['image'],
        'created_at' => $row['created_at'],
        'updated_at' => $row['updated_at'],
    );
}

// Yêu cầu `Twig` vẽ giao diện được viết trong file `backend/shop_suppliers/index.html.twig`
// với dữ liệu truyền vào file giao diện được đặt tên là `suppliers`
echo $twig->render('backend/shop_suppliers/index.html.twig', [
    'suppliers' => $data
]);
