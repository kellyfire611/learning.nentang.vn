<?php
// Include file cấu hình ban đầu của `Twig`
require_once __DIR__ . '/../../bootstrap.php';

// Truy vấn database
// 1. Include file cấu hình kết nối đến database, khởi tạo kết nối $conn
include_once(__DIR__ . '/../../dbconnect.php');

// Lấy giá trị khóa chính được truyền theo dạng QueryString Parameter key1=value1&key2=value2...
$dh_ma = $_GET['dh_ma'];
$dh_ngaygiao = 'NULL';
$dh_noigiao = 'NULL';
$dh_trangthaithanhtoan = 0; //0: chưa xử lý; 1: đã xử lý

// Câu lệnh
$sql = "UPDATE `dondathang` SET dh_ngaygiao=$dh_ngaygiao, dh_noigiao=$dh_noigiao, dh_trangthaithanhtoan=$dh_trangthaithanhtoan WHERE dh_ma=$dh_ma;";

// Thực thi
mysqli_query($conn, $sql);

// Đóng kết nối
mysqli_close($conn);

// Sau khi cập nhật dữ liệu, tự động điều hướng về trang Danh sách
header('location:index.php');
