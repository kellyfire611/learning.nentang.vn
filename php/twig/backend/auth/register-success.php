<?php
// Include file cấu hình ban đầu của `Twig`
require_once __DIR__.'/../../bootstrap.php';

// Truy vấn database
// 1. Include file cấu hình kết nối đến database, khởi tạo kết nối $conn
include_once(__DIR__.'/../../dbconnect.php');

// 2. Chuẩn bị câu truy vấn $sqlSelect, lấy dữ liệu ban đầu của record
// Lấy giá trị khóa chính được truyền theo dạng QueryString Parameter key1=value1&key2=value2...
$email = $_GET['email'];
$sqlSelect = <<<EOT
    SELECT *
    FROM shop_customers
    WHERE email = '$email'
    LIMIT 1;
EOT;

// 3. Thực thi câu truy vấn SQL để lấy về dữ liệu ban đầu của record
$resultSelect = mysqli_query($conn, $sqlSelect);
$customerRow = mysqli_fetch_array($resultSelect, MYSQLI_ASSOC); // 1 record

// Yêu cầu `Twig` vẽ giao diện được viết trong file `backend/auth/register-success.html.twig`
// với dữ liệu truyền vào file giao diện được đặt tên là `customer`
echo $twig->render('backend/auth/register-success.html.twig', ['customer' => $customerRow] );