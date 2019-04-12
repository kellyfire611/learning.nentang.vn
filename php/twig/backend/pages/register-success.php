<?php
// Include file cấu hình ban đầu của `Twig`
require_once __DIR__.'/../../bootstrap.php';

// Truy vấn database
// 1. Include file cấu hình kết nối đến database, khởi tạo kết nối $conn
include_once(__DIR__.'/../../dbconnect.php');

// 2. Chuẩn bị câu truy vấn $sqlSelect, lấy dữ liệu ban đầu của record
// Lấy giá trị khóa chính được truyền theo dạng QueryString Parameter key1=value1&key2=value2...
$kh_tendangnhap = $_GET['kh_tendangnhap'];
$sqlSelect = "SELECT * FROM `khachhang` WHERE kh_tendangnhap='$kh_tendangnhap';";

// 3. Thực thi câu truy vấn SQL để lấy về dữ liệu ban đầu của record
$resultSelect = mysqli_query($conn, $sqlSelect);
$khachhangRow = mysqli_fetch_array($resultSelect, MYSQLI_ASSOC); // 1 record

// Yêu cầu `Twig` vẽ giao diện được viết trong file `backend/pages/register-success.html.twig`
// với dữ liệu truyền vào file giao diện được đặt tên là `khachhang`
echo $twig->render('backend/pages/register-success.html.twig', ['khachhang' => $khachhangRow] );