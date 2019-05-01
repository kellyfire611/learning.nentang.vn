<?php
// Include file cấu hình ban đầu của `Twig`
require_once __DIR__ . '/../../bootstrap.php';

// Truy vấn database để lấy danh sách
// 1. Include file cấu hình kết nối đến database, khởi tạo kết nối $conn
include_once(__DIR__ . '/../../dbconnect.php');

/* --- 
   --- 2.Truy vấn dữ liệu Loại Sản phẩm 
   --- 
*/
$sqlSelectLoaiSanPham = <<<EOT
    SELECT lsp.lsp_ma, lsp.lsp_ten, COUNT(*) soluongsanpham
    FROM `loaisanpham` lsp
    LEFT JOIN `sanpham` sp ON lsp.lsp_ma = sp.lsp_ma
    GROUP BY lsp.lsp_ma, lsp.lsp_ten
EOT;

// Thực thi câu truy vấn SQL để lấy về dữ liệu ban đầu của record 
$resultSelectLoaiSanPham = mysqli_query($conn, $sqlSelectLoaiSanPham);

// Khi thực thi các truy vấn dạng SELECT, dữ liệu lấy về cần phải phân tích để sử dụng
// Thông thường, chúng ta sẽ sử dụng vòng lặp while để duyệt danh sách các dòng dữ liệu được SELECT
// Ta sẽ tạo 1 mảng array để chứa các dữ liệu được trả về
$loaisanphamData;
while ($row = mysqli_fetch_array($resultSelectLoaiSanPham, MYSQLI_ASSOC)) {
    $loaisanphamData[] = array(
        'lsp_ma' => $row['lsp_ma'],
        'lsp_ten' => $row['lsp_ten'],
        'soluongsanpham' => $row['soluongsanpham'],
    );
}
/* --- End Truy vấn dữ liệu Loại Sản phẩm --- */

/* --- 
   --- 3.Truy vấn dữ liệu Nhà sản xuất
   --- 
*/
$sqlSelectNhaSanXuat = <<<EOT
    SELECT nsx.nsx_ma, nsx.nsx_ten, COUNT(*) soluongsanpham
    FROM `nhasanxuat`nsx
    LEFT JOIN `sanpham` sp ON nsx.nsx_ma = sp.nsx_ma
    GROUP BY nsx.nsx_ma, nsx.nsx_ten
EOT;

// Thực thi câu truy vấn SQL để lấy về dữ liệu ban đầu của record 
$resultSelectNhaSanXuat = mysqli_query($conn, $sqlSelectNhaSanXuat);

// Khi thực thi các truy vấn dạng SELECT, dữ liệu lấy về cần phải phân tích để sử dụng
// Thông thường, chúng ta sẽ sử dụng vòng lặp while để duyệt danh sách các dòng dữ liệu được SELECT
// Ta sẽ tạo 1 mảng array để chứa các dữ liệu được trả về
$nhasanxuatData;
while ($row = mysqli_fetch_array($resultSelectNhaSanXuat, MYSQLI_ASSOC)) {
    $nhasanxuatData[] = array(
        'nsx_ma' => $row['nsx_ma'],
        'nsx_ten' => $row['nsx_ten'],
        'soluongsanpham' => $row['soluongsanpham'],
    );
}
/* --- End Truy vấn dữ liệu Nhà sản xuất --- */

/* --- 
   --- 4.Truy vấn dữ liệu Khuyến mãi
   --- 
*/
$sqlSelectKhuyenMai = <<<EOT
    SELECT km.km_ma, km.km_ten, km_noidung, km_tungay, km_denngay, COUNT(*) soluongsanpham
    FROM `khuyenmai` km
    LEFT JOIN `sanpham` sp ON km.km_ma = sp.km_ma
    GROUP BY km.km_ma, km.km_ten, km_noidung, km_tungay, km_denngay
EOT;

// Thực thi câu truy vấn SQL để lấy về dữ liệu ban đầu của record 
$resultSelectKhuyenMai = mysqli_query($conn, $sqlSelectKhuyenMai);

// Khi thực thi các truy vấn dạng SELECT, dữ liệu lấy về cần phải phân tích để sử dụng
// Thông thường, chúng ta sẽ sử dụng vòng lặp while để duyệt danh sách các dòng dữ liệu được SELECT
// Ta sẽ tạo 1 mảng array để chứa các dữ liệu được trả về
$khuyenmaiData;
while ($row = mysqli_fetch_array($resultSelectKhuyenMai, MYSQLI_ASSOC)) {
    $khuyenmaiData[] = array(
        'km_ma' => $row['km_ma'],
        'km_ten' => $row['km_ten'],
        'km_noidung' => $row['km_noidung'],
        'km_tungay' => $row['km_tungay'],
        'km_denngay' => $row['km_denngay'],
        'soluongsanpham' => $row['soluongsanpham'],
    );
}
/* --- End Truy vấn dữ liệu Nhà sản xuất --- */

/* --- 
   --- 5.Truy vấn dữ liệu Sản phẩm theo keyword tìm kiếm
   --- 
*/
// Giữ lại keyword mà người dùng tìm kiếm
$keyword_tensanpham = isset($_GET['keyword_tensanpham']) ? $_GET['keyword_tensanpham'] : '';
$keyword_loaisanpham = isset($_GET['keyword_loaisanpham']) ? $_GET['keyword_loaisanpham'] : [];
$keyword_nhasanxuat = isset($_GET['keyword_nhasanxuat']) ? $_GET['keyword_nhasanxuat'] : [];
$keyword_khuyenmai = isset($_GET['keyword_khuyenmai']) ? $_GET['keyword_khuyenmai'] : [];
$keyword_sotientu = isset($_GET['keyword_sotientu']) ? $_GET['keyword_sotientu'] : 0;
$keyword_sotienden = isset($_GET['keyword_sotienden']) ? $_GET['keyword_sotienden'] : 50000000;

// Câu lệnh query động tùy theo yêu cầu tìm kiếm của người dùng
$sqlDanhSachSanPham = <<<EOT
    SELECT sp.sp_ma, sp.sp_ten, sp.sp_gia, sp.sp_giacu, sp.sp_mota_ngan, sp.sp_soluong, lsp.lsp_ten, MAX(hsp.hsp_tentaptin) AS hsp_tentaptin
    FROM `sanpham` sp
    JOIN `loaisanpham` lsp ON sp.lsp_ma = lsp.lsp_ma
    LEFT JOIN `hinhsanpham` hsp ON sp.sp_ma = hsp.sp_ma
    LEFT JOIN `nhasanxuat` nsx ON sp.nsx_ma = nsx.nsx_ma
    LEFT JOIN `khuyenmai` km ON sp.km_ma = km.km_ma

EOT;

// Tìm theo tên sản phẩm
$sqlWhereArr = [];
if (!empty($keyword_tensanpham)) {
    $sqlWhereArr[] = "sp.sp_ten LIKE '%$keyword_tensanpham%'";
}
// Tìm theo loại sản phẩm
if (!empty($keyword_loaisanpham)) {
    $value = implode(',', $keyword_loaisanpham);
    $sqlWhereArr[] = "lsp.lsp_ma IN ($value)";
}
// Tìm theo nhà sản xuất
if (!empty($keyword_nhasanxuat)) {
    $value = implode(',', $keyword_nhasanxuat);
    $sqlWhereArr[] = "nsx.nsx_ma IN ($value)";
}
// Tìm theo khuyến mãi
if (!empty($keyword_khuyenmai)) {
    $value = implode(',', $keyword_khuyenmai);
    $sqlWhereArr[] = "km.km_ma IN ($value)";
}
// Tìm theo khoảng giá tiền
if (!empty($keyword_sotientu) && !empty($keyword_sotienden)) {
    $sqlWhereArr[] = "sp.sp_gia BETWEEN $keyword_sotientu AND $keyword_sotienden";
}

// Câu lệnh cuối cùng
if (count($sqlWhereArr) > 0) {
    $sqlWhere = "WHERE " . implode(' AND ', $sqlWhereArr);
    $sqlDanhSachSanPham .= $sqlWhere;
}
$sqlDanhSachSanPham .= <<<EOT
    GROUP BY sp.sp_ma, sp.sp_ten, sp.sp_gia, sp.sp_giacu, sp.sp_mota_ngan, sp.sp_soluong, lsp.lsp_ten
EOT;

// Thực thi câu truy vấn SQL để lấy về dữ liệu
$result = mysqli_query($conn, $sqlDanhSachSanPham);

// Khi thực thi các truy vấn dạng SELECT, dữ liệu lấy về cần phải phân tích để sử dụng
// Thông thường, chúng ta sẽ sử dụng vòng lặp while để duyệt danh sách các dòng dữ liệu được SELECT
// Ta sẽ tạo 1 mảng array để chứa các dữ liệu được trả về
$dataDanhSachSanPham = [];
while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        $dataDanhSachSanPham[] = array(
            'sp_ma' => $row['sp_ma'],
            'sp_ten' => $row['sp_ten'],
            'sp_gia' => number_format($row['sp_gia'], 2, ".", ",") . ' vnđ',
            'sp_giacu' => number_format($row['sp_giacu'], 2, ".", ","),
            'sp_mota_ngan' => $row['sp_mota_ngan'],
            'sp_soluong' => $row['sp_soluong'],
            'lsp_ten' => $row['lsp_ten'],
            'hsp_tentaptin' => $row['hsp_tentaptin'],
        );
    }
// dd($sqlWhereArr, $sqlWhere, $sqlDanhSachSanPham, $dataDanhSachSanPham);

// Yêu cầu `Twig` vẽ giao diện được viết trong file `frontend/pages/timkiem.html.twig`
echo $twig->render(
    'frontend/pages/timkiem.html.twig',
    [
        // Danh mục tiêu chí tìm kiếm
        'danhsachloaisanpham' => $loaisanphamData,
        'danhsachnhasanxuat' => $nhasanxuatData,
        'danhsachkhuyenmai' => $khuyenmaiData,
        'danhsachsanpham' => $dataDanhSachSanPham,

        // Keyword người dùng đã tìm kiếm
        'keyword_tensanpham' => $keyword_tensanpham,
        'keyword_loaisanpham' => $keyword_loaisanpham,
        'keyword_nhasanxuat' => $keyword_nhasanxuat,
        'keyword_khuyenmai' => $keyword_khuyenmai,
        'keyword_sotientu' => $keyword_sotientu,
        'keyword_sotienden' => $keyword_sotienden,
    ]
);
