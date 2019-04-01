<?php
// Include file cấu hình ban đầu của `Twig`
require_once __DIR__.'/../../bootstrap.php';

// Truy vấn database
// 1. Include file cấu hình kết nối đến database, khởi tạo kết nối $conn
include_once(__DIR__.'/../../dbconnect.php');

/* --- 
   --- 2.Truy vấn dữ liệu Loại sản phẩm 
   --- 
*/
// Chuẩn bị câu truy vấn Loại sản phẩm
$sqlLoaiSanPham = "select * from `loaisanpham`";

// Thực thi câu truy vấn SQL để lấy về dữ liệu
$resultLoaiSanPham = mysqli_query($conn, $sqlLoaiSanPham);

// Khi thực thi các truy vấn dạng SELECT, dữ liệu lấy về cần phải phân tích để sử dụng
// Thông thường, chúng ta sẽ sử dụng vòng lặp while để duyệt danh sách các dòng dữ liệu được SELECT
// Ta sẽ tạo 1 mảng array để chứa các dữ liệu được trả về
$dataLoaiSanPham = [];
while($rowLoaiSanPham = mysqli_fetch_array($resultLoaiSanPham, MYSQLI_ASSOC))
{
    $dataLoaiSanPham[] = array(
        'lsp_ma' => $rowLoaiSanPham['lsp_ma'],
        'lsp_ten' => $rowLoaiSanPham['lsp_ten'],
        'lsp_mota' => $rowLoaiSanPham['lsp_mota'],
    );
}
/* --- End Truy vấn dữ liệu Loại sản phẩm --- */

/* --- 
   --- 3. Truy vấn dữ liệu Nhà sản xuất 
   --- 
*/
// Chuẩn bị câu truy vấn Nhà sản xuất
$sqlNhaSanXuat = "select * from `nhasanxuat`";

// Thực thi câu truy vấn SQL để lấy về dữ liệu
$resultNhaSanXuat = mysqli_query($conn, $sqlNhaSanXuat);

// Khi thực thi các truy vấn dạng SELECT, dữ liệu lấy về cần phải phân tích để sử dụng
// Thông thường, chúng ta sẽ sử dụng vòng lặp while để duyệt danh sách các dòng dữ liệu được SELECT
// Ta sẽ tạo 1 mảng array để chứa các dữ liệu được trả về
$dataNhaSanXuat = [];
while($rowNhaSanXuat = mysqli_fetch_array($resultNhaSanXuat, MYSQLI_ASSOC))
{
    $dataNhaSanXuat[] = array(
        'nsx_ma' => $rowNhaSanXuat['nsx_ma'],
        'nsx_ten' => $rowNhaSanXuat['nsx_ten'],
    );
}
/* --- End Truy vấn dữ liệu Nhà sản xuất --- */

/* --- 
   --- 4. Truy vấn dữ liệu Khuyến mãi
   --- 
*/
// Chuẩn bị câu truy vấn Khuyến mãi
$sqlKhuyenMai = "select * from `khuyenmai`";

// Thực thi câu truy vấn SQL để lấy về dữ liệu
$resultKhuyenMai = mysqli_query($conn, $sqlKhuyenMai);

// Khi thực thi các truy vấn dạng SELECT, dữ liệu lấy về cần phải phân tích để sử dụng
// Thông thường, chúng ta sẽ sử dụng vòng lặp while để duyệt danh sách các dòng dữ liệu được SELECT
// Ta sẽ tạo 1 mảng array để chứa các dữ liệu được trả về
$dataKhuyenMai = [];
while($rowKhuyenMai = mysqli_fetch_array($resultKhuyenMai, MYSQLI_ASSOC))
{
    $km_tomtat = '';
    if(!empty($rowKhuyenMai['km_ten'])) {
        // Sử dụng hàm sprintf() để chuẩn bị mẫu câu với các giá trị truyền vào tương ứng từng vị trí placeholder
        $km_tomtat = sprintf("Khuyến mãi %s, nội dung: %s, thời gian: %s-%s", 
            $rowKhuyenMai['km_ten'],
            $rowKhuyenMai['km_noidung'],
            // Sử dụng hàm date($format, $timestamp) để chuyển đổi ngày thành định dạng Việt Nam (ngày/tháng/năm)
            // Do hàm date() nhận vào là đối tượng thời gian, chúng ta cần sử dụng hàm strtotime() để chuyển đổi từ chuỗi có định dạng 'yyyy-mm-dd' trong MYSQL thành đối tượng ngày tháng
            date('d/m/Y', strtotime($rowKhuyenMai['km_tungay'])),    //vd: '2019-04-25'
            date('d/m/Y', strtotime($rowKhuyenMai['km_denngay'])));  //vd: '2019-05-10'
    }
    $dataKhuyenMai[] = array(
        'km_ma' => $rowKhuyenMai['km_ma'],
        'km_tomtat' => $km_tomtat,
    );
}
/* --- End Truy vấn dữ liệu Khuyến mãi --- */

// 2. Nếu người dùng có bấm nút Đăng ký thì thực thi câu lệnh UPDATE
if(isset($_POST['btnCapNhat'])) 
{
    // Lấy dữ liệu người dùng hiệu chỉnh gởi từ REQUEST POST
    $ten = $_POST['sp_ten'];
    $gia = $_POST['sp_gia'];
    $giacu = $_POST['sp_giacu'];
    $motangan = $_POST['sp_mota_ngan'];
    $motachitiet = $_POST['sp_mota_chitiet'];
    $ngaycapnhat = $_POST['sp_ngaycapnhat'];
    $soluong = $_POST['sp_soluong'];
    $lsp_ma = $_POST['lsp_ma'];
    $nsx_ma = $_POST['nsx_ma'];
    $km_ma = $_POST['km_ma'];

    // Câu lệnh INSERT
    $sql = "INSERT INTO `sanpham` (sp_ten, sp_gia, sp_giacu, sp_mota_ngan, sp_mota_chitiet, sp_ngaycapnhat, sp_soluong, lsp_ma, nsx_ma, km_ma) VALUES ('$ten', $gia, $giacu, '$motangan', '$motachitiet', '$ngaycapnhat', $soluong, $lsp_ma, $nsx_ma, $km_ma);";
    
    // Thực thi INSERT
    mysqli_query($conn, $sql);

    // Đóng kết nối
    mysqli_close($conn);

    // Sau khi cập nhật dữ liệu, tự động điều hướng về trang Danh sách
    header('location:index.php');
}

// Yêu cầu `Twig` vẽ giao diện được viết trong file `backend/sanpham/create.html.twig`
echo $twig->render('backend/sanpham/create.html.twig', [
    'ds_loaisanpham' => $dataLoaiSanPham,
    'ds_nhasanxuat' => $dataNhaSanXuat,
    'ds_khuyenmai' => $dataKhuyenMai,
]);