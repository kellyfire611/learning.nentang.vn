<?php
// Truy vấn database
// 1. Include file cấu hình kết nối đến database, khởi tạo kết nối $conn
include_once(__DIR__.'/../../dbconnect.php');

// 2. Chuẩn bị câu truy vấn $sql
// Lấy giá trị khóa chính được truyền theo dạng QueryString Parameter key1=value1&key2=value2...
$km_ma = $_GET['km_ma'];
$sql = "DELETE FROM `khuyenmai` WHERE km_ma=" . $km_ma;

// 3. Thực thi câu lệnh DELETE
$result = mysqli_query($conn, $sql);

// 4. Đóng kết nối
mysqli_close($conn);
    
// Sau khi cập nhật dữ liệu, tự động điều hướng về trang Danh sách
header('location:index.php');