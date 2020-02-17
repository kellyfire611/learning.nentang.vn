<?php
// Include file cấu hình ban đầu của `Twig`
require_once __DIR__ . '/../../bootstrap.php';

// Truy vấn database
// 1. Include file cấu hình kết nối đến database, khởi tạo kết nối $conn
include_once(__DIR__ . '/../../dbconnect.php');

/* --- 
   --- 2.Truy vấn dữ liệu Sản phẩm 
   --- Lấy giá trị khóa chính được truyền theo dạng QueryString Parameter key1=value1&key2=value2...
   --- 
*/
$sp_ma = $_GET['sp_ma'];
$sqlSelectSanPham = <<<EOT
    SELECT sp.sp_ma, sp.sp_ten, sp.sp_gia, sp.sp_giacu, sp.sp_mota_ngan, sp.sp_mota_chitiet, sp.sp_soluong, lsp.lsp_ten
    FROM `sanpham` sp
    JOIN `loaisanpham` lsp ON sp.lsp_ma = lsp.lsp_ma
    WHERE sp.sp_ma = $sp_ma
EOT;

// Thực thi câu truy vấn SQL để lấy về dữ liệu ban đầu của record 
$resultSelectSanPham = mysqli_query($conn, $sqlSelectSanPham);

// Khi thực thi các truy vấn dạng SELECT, dữ liệu lấy về cần phải phân tích để sử dụng
// Thông thường, chúng ta sẽ sử dụng vòng lặp while để duyệt danh sách các dòng dữ liệu được SELECT
// Ta sẽ tạo 1 mảng array để chứa các dữ liệu được trả về
$sanphamRow;
while ($row = mysqli_fetch_array($resultSelectSanPham, MYSQLI_ASSOC)) {
    $sanphamRow = array(
        'sp_ma' => $row['sp_ma'],
        'sp_ten' => $row['sp_ten'],
        'sp_gia' => $row['sp_gia'],
        'sp_gia_formated' => number_format($row['sp_gia'], 2, ".", ",") . ' vnđ',
        'sp_giacu_formated' => number_format($row['sp_giacu'], 2, ".", ",") . ' vnđ',
        'sp_mota_ngan' => $row['sp_mota_ngan'],
        'sp_mota_chitiet' => $row['sp_mota_chitiet'],
        'sp_soluong' => $row['sp_soluong'],
        'lsp_ten' => $row['lsp_ten']
    );
}
/* --- End Truy vấn dữ liệu Sản phẩm --- */

/* --- 
   --- 3.Truy vấn dữ liệu Hình ảnh Sản phẩm 
   --- 
*/
$sqlSelect = <<<EOT
    SELECT hsp.hsp_tentaptin
    FROM `hinhsanpham` hsp
    WHERE hsp.sp_ma = $sp_ma
EOT;

// Thực thi câu truy vấn SQL để lấy về dữ liệu ban đầu của record 
$result = mysqli_query($conn, $sqlSelect);

// Khi thực thi các truy vấn dạng SELECT, dữ liệu lấy về cần phải phân tích để sử dụng
// Thông thường, chúng ta sẽ sử dụng vòng lặp while để duyệt danh sách các dòng dữ liệu được SELECT
// Ta sẽ tạo 1 mảng array để chứa các dữ liệu được trả về
$danhsachhinhanh = [];
while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
    $danhsachhinhanh[] = array(
        'hsp_tentaptin' => $row['hsp_tentaptin']
    );
}
/* --- End Truy vấn dữ liệu Hình ảnh sản phẩm --- */

// Hiệu chỉnh dữ liệu theo cấu trúc để tiện xử lý
$sanphamRow['danhsachhinhanh'] = $danhsachhinhanh;

// Yêu cầu `Twig` vẽ giao diện được viết trong file `backend/sanpham/chitiet.html.twig`
// với dữ liệu truyền vào file giao diện được đặt tên là `sanpham`
// dd($sanphamRow);
echo $twig->render('frontend/sanpham/chitiet.html.twig', ['sanpham' => $sanphamRow]);
