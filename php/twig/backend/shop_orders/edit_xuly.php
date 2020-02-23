<?php
// Include file cấu hình ban đầu của `Twig`
require_once __DIR__.'/../../bootstrap.php';

// Truy vấn database
// 1. Include file cấu hình kết nối đến database, khởi tạo kết nối $conn
include_once(__DIR__.'/../../dbconnect.php');

/* --- 
   --- 2. Truy vấn dữ liệu Sản phẩm theo khóa chính
   --- 
*/
// Chuẩn bị câu truy vấn $sqlSelect, lấy dữ liệu ban đầu của record cần update
// Lấy giá trị khóa chính được truyền theo dạng QueryString Parameter key1=value1&key2=value2...
$dh_ma = $_GET['dh_ma'];
$sqlSelect = <<<EOT
SELECT 
    ddh.dh_ma, ddh.dh_ngaylap, ddh.dh_ngaygiao, ddh.dh_noigiao, ddh.dh_trangthaithanhtoan, httt.httt_ten, kh.kh_ten, kh.kh_dienthoai
    , SUM(spddh.sp_dh_quantity * spddh.sp_dh_dongia) AS Tongamount
FROM `dondathang` ddh
JOIN `sanpham_dondathang` spddh ON ddh.dh_ma = spddh.dh_ma
JOIN `khachhang` kh ON ddh.kh_tendangnhap = kh.kh_tendangnhap
JOIN `hinhthucthanhtoan` httt ON ddh.httt_ma = httt.httt_ma
WHERE ddh.dh_ma=$dh_ma
GROUP BY ddh.dh_ma, ddh.dh_ngaylap, ddh.dh_ngaygiao, ddh.dh_noigiao, ddh.dh_trangthaithanhtoan, httt.httt_ten, kh.kh_ten, kh.kh_dienthoai
EOT;

// Thực thi câu truy vấn SQL để lấy về dữ liệu ban đầu của record cần update
$resultSelect = mysqli_query($conn, $sqlSelect);
$dondathangRow;
while($row = mysqli_fetch_array($resultSelect, MYSQLI_ASSOC))
{
    $dondathangRow = array(
        'dh_ma' => $row['dh_ma'],
        'dh_ngaylap' => date('d/m/Y H:i:s', strtotime($row['dh_ngaylap'])),
        'dh_ngaygiao' => empty($row['dh_ngaygiao']) ? '' : date('d/m/Y H:i:s', strtotime($row['dh_ngaygiao'])),
        'dh_noigiao' => $row['dh_noigiao'],
        'dh_trangthaithanhtoan' => $row['dh_trangthaithanhtoan'],
        'httt_ten' => $row['httt_ten'],
        'kh_ten' => $row['kh_ten'],
        'kh_dienthoai' => $row['kh_dienthoai'],
        'Tongamount' => number_format($row['Tongamount'], 2, ".", ",") . ' vnđ',
    );
}
/* --- End Truy vấn dữ liệu Sản phẩm theo khóa chính --- */

// 2. Nếu người dùng có bấm nút Đăng ký thì thực thi câu lệnh UPDATE
if(isset($_POST['btnSave'])) 
{
    // Lấy dữ liệu người dùng hiệu chỉnh gởi từ REQUEST POST
    $dh_ngaygiao = $_POST['dh_ngaygiao'];
    $dh_noigiao = $_POST['dh_noigiao'];
    $dh_trangthaithanhtoan = 1; //1: đã xử lý

    // Câu lệnh
    $sql = "UPDATE `dondathang` SET dh_ngaygiao='$dh_ngaygiao', dh_noigiao='$dh_noigiao', dh_trangthaithanhtoan=$dh_trangthaithanhtoan WHERE dh_ma=$dh_ma;";
    
    // Thực thi
    mysqli_query($conn, $sql);

    // Đóng kết nối
    mysqli_close($conn);

    // Sau khi cập nhật dữ liệu, tự động điều hướng về trang Danh sách
    header('location:index.php');
}

// Yêu cầu `Twig` vẽ giao diện được viết trong file `backend/dondathang/edit.html.twig`
echo $twig->render('backend/dondathang/edit.html.twig', [
    'dondathang' => $dondathangRow,
]);