<?php
// Include file cấu hình ban đầu của `Twig`
require_once __DIR__.'/../../bootstrap.php';

// Truy vấn database
// 1. Include file cấu hình kết nối đến database, khởi tạo kết nối $conn
include_once(__DIR__.'/../../dbconnect.php');

// Lấy dữ liệu người dùng hiệu chỉnh gởi từ REQUEST GET
$kh_tendangnhap = $_GET['kh_tendangnhap'];
$kh_makichhoat = $_GET['kh_makichhoat'];

// Câu lệnh SELECT
$sql = "SELECT * FROM `khachhang` WHERE kh_tendangnhap = '$kh_tendangnhap' AND kh_makichhoat = '$kh_makichhoat';";

// Thực thi SELECT
$result = mysqli_query($conn, $sql);

// Sử dụng hàm `mysqli_num_rows()` để đếm số dòng SELECT được
// Nếu có bất kỳ dòng dữ liệu nào SELECT được <-> Người dùng có tồn tại và đã đúng thông tin USERNAME, MAKICHHOAT
if(mysqli_num_rows($result)>0) {
    // Cập nhật trạng thái của User này thành 1
    $sqlUpdate = "UPDATE khachhang SET kh_trangthai=1 WHERE kh_tendangnhap='$kh_tendangnhap'";

    // Thực thi SELECT
    mysqli_query($conn, $sqlUpdate);

    // Yêu cầu `Twig` vẽ giao diện được viết trong file `backend/pages/user-activated.html.twig`
    echo $twig->render('backend/pages/user-activated.html.twig');
}
else {
    echo 'Kích hoạt thất bại!';
}