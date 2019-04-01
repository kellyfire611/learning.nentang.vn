<?php
// Include file cấu hình ban đầu của `Twig`
require_once __DIR__.'/../../bootstrap.php';

// Truy vấn database
// 1. Include file cấu hình kết nối đến database, khởi tạo kết nối $conn
include_once(__DIR__.'/../../dbconnect.php');

// 2. Chuẩn bị câu truy vấn $sqlSelect, lấy dữ liệu ban đầu của record cần update
// Lấy giá trị khóa chính được truyền theo dạng QueryString Parameter key1=value1&key2=value2...
$km_ma = $_GET['km_ma'];
$sqlSelect = "SELECT * FROM `khuyenmai` WHERE km_ma=$km_ma;";

// 3. Thực thi câu truy vấn SQL để lấy về dữ liệu ban đầu của record cần update
$resultSelect = mysqli_query($conn, $sqlSelect);
$khuyenmaiRow = mysqli_fetch_array($resultSelect, MYSQLI_ASSOC); // 1 record

// 4. Nếu người dùng có bấm nút Đăng ký thì thực thi câu lệnh UPDATE
if(isset($_POST['btnCapNhat'])) 
{
    // Lấy dữ liệu người dùng hiệu chỉnh gởi từ REQUEST POST
    $ten = $_POST['km_ten'];
    $noidung = $_POST['km_noidung'];
    $tungay = $_POST['km_tungay'];
    $denngay = $_POST['km_denngay'];

    // Câu lệnh UPDATE
    $sql = "UPDATE `khuyenmai` SET km_ten='$ten', km_mota='$noidung', km_tungay='$tungay', km_dengay='$denngay' WHERE km_ma=$km_ma;";

    // Thực thi UPDATE
    mysqli_query($conn, $sql);

    // Đóng kết nối
    mysqli_close($conn);

    // Sau khi cập nhật dữ liệu, tự động điều hướng về trang Danh sách
    header('location:index.php');
}

// Yêu cầu `Twig` vẽ giao diện được viết trong file `backend/khuyenmai/edit.html.twig`
// với dữ liệu truyền vào file giao diện được đặt tên là `khuyenmai`
echo $twig->render('backend/khuyenmai/edit.html.twig', ['khuyenmai' => $khuyenmaiRow] );