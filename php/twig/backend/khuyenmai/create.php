<?php
// Include file cấu hình ban đầu của `Twig`
require_once __DIR__.'/../../bootstrap.php';

// Truy vấn database
// 1. Include file cấu hình kết nối đến database, khởi tạo kết nối $conn
include_once(__DIR__.'/../../dbconnect.php');

// 2. Nếu người dùng có bấm nút Đăng ký thì thực thi câu lệnh UPDATE
if(isset($_POST['btnCapNhat'])) 
{
    // Lấy dữ liệu người dùng hiệu chỉnh gởi từ REQUEST POST
    $ten = $_POST['km_ten'];
    $noidung = $_POST['km_noidung'];
    $tungay = $_POST['km_tungay'];
    $denngay = $_POST['km_denngay'];

    // Câu lệnh INSERT
    $sql = "INSERT INTO `khuyenmai` (km_ten, km_noidung, km_tungay, km_denngay) VALUES ('$ten', '$noidung', '$tungay', '$denngay');";
    
    // Thực thi INSERT
    mysqli_query($conn, $sql);

    // Đóng kết nối
    mysqli_close($conn);

    // Sau khi cập nhật dữ liệu, tự động điều hướng về trang Danh sách
    header('location:index.php');
}

// Yêu cầu `Twig` vẽ giao diện được viết trong file `backend/khuyenmai/create.html.twig`
echo $twig->render('backend/khuyenmai/create.html.twig');