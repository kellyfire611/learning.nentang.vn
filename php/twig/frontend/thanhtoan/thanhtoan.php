<?php
// Include file cấu hình ban đầu của `Twig`
require_once __DIR__ . '/../../bootstrap.php';

// Truy vấn database để lấy danh sách
// 1. Include file cấu hình kết nối đến database, khởi tạo kết nối $conn
include_once(__DIR__ . '/../../dbconnect.php');

// Lưu đơn hàng
if (isset($_POST['btnDatHang'])) {
    // dd($_POST);
    // Lấy dữ liệu từ POST
    $kh_tendangnhap = $_POST['kh_tendangnhap'];
    $httt_ma = $_POST['httt_ma'];
    $dh_ngaylap = date('Y-m-d H:m:s'); // lấy ngày hiện tại
    $dh_trangthaithanhtoan = 0; // 0: Đơn hàng chưa xử lý

    // Insert Đơn hàng
    // Câu lệnh INSERT
    $sqlDonHang = "INSERT INTO `dondathang` (dh_ngaylap, dh_ngaygiao, dh_noigiao, dh_trangthaithanhtoan, httt_ma, kh_tendangnhap) VALUES ('$dh_ngaylap', null, null, $dh_trangthaithanhtoan, $httt_ma, '$kh_tendangnhap');";
    // dd($sqlDonHang);

    // Thực thi INSERT
    mysqli_query($conn, $sqlDonHang);

    // Lấy ID đơn hàng vừa được lưu
    $last_donhang_id = mysqli_insert_id($conn);

    // Duyệt vòng lặp sản phẩm trong giỏ hàng để thực thi câu lệnh INSERT vào table `sanpham_donhang`
    foreach ($_POST['sanphamgiohang'] as $sanpham) {
        $sp_ma = $sanpham['sp_ma'];
        $gia = $sanpham['gia'];
        $soluong = $sanpham['soluong'];

        // Insert Sản phẩm Đơn hàng
        // Câu lệnh INSERT
        $sqlSanPhamDonHang = "INSERT INTO `sanpham_dondathang` (sp_ma, dh_ma, sp_dh_soluong, sp_dh_dongia) VALUES ($sp_ma, $last_donhang_id, $soluong, $gia);";

        // Thực thi INSERT
        mysqli_query($conn, $sqlSanPhamDonHang);
    }

    // Thanh toán thành công, xóa Giỏ hàng trong SESSION
    // lưu dữ liệu giỏ hàng vào session
    $_SESSION['giohangdata'] = [];

    echo $twig->render('frontend/thanhtoan/thanhtoan-finish.html.twig');
} else {
    // Nếu trong SESSION có giá trị của key 'username' <-> người dùng đã đăng nhập thành công
    // Nếu chưa đăng nhập thì chuyển hướng về trang đăng nhập
    if (!isset($_SESSION['username'])) {
        header('location:../pages/login.php');
    } else {
        // Chuẩn bị câu truy vấn $sql
        $sqlHinhThucThanhToan = "select * from `hinhthucthanhtoan`";

        // Thực thi câu truy vấn SQL để lấy về dữ liệu
        $resultHinhThucThanhToan = mysqli_query($conn, $sqlHinhThucThanhToan);

        // Khi thực thi các truy vấn dạng SELECT, dữ liệu lấy về cần phải phân tích để sử dụng
        // Thông thường, chúng ta sẽ sử dụng vòng lặp while để duyệt danh sách các dòng dữ liệu được SELECT
        // Ta sẽ tạo 1 mảng array để chứa các dữ liệu được trả về
        $dataHinhThucThanhToan = [];
        while ($row = mysqli_fetch_array($resultHinhThucThanhToan, MYSQLI_ASSOC)) {
            $dataHinhThucThanhToan[] = array(
                'httt_ma' => $row['httt_ma'],
                'httt_ten' => $row['httt_ten'],
            );
        }

        // Lấy thông tin khách hàng
        // Lấy dữ liệu người dùng đã đăng nhập từ SESSION
        $username = $_SESSION['username'];

        // Câu lệnh SELECT
        $sql = "SELECT * FROM `khachhang` WHERE kh_tendangnhap = '$username';";

        // Thực thi SELECT
        $result = mysqli_query($conn, $sql);
        $dataKhachHang;
        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $dataKhachHang = array(
                'kh_tendangnhap' => $row['kh_tendangnhap'],
                'kh_ten' => $row['kh_ten'],
                'kh_gioitinh' => $row['kh_gioitinh'],
                'kh_diachi' => $row['kh_diachi'],
                'kh_dienthoai' => $row['kh_dienthoai'],
                'kh_email' => $row['kh_email'],
                'kh_ngaysinh' => $row['kh_ngaysinh'],
                'kh_thangsinh' => $row['kh_thangsinh'],
                'kh_namsinh' => $row['kh_namsinh'],
                'kh_cmnd' => $row['kh_cmnd'],
            );
        }

        // Kiểm tra dữ liệu trong session
        $data = [];
        if (isset($_SESSION['giohangdata'])) {
            $data = $_SESSION['giohangdata'];
        } else {
            $data = [];
        }

        // Yêu cầu `Twig` vẽ giao diện được viết trong file `frontend/thanhtoan/thanhtoan.html.twig`
        // với dữ liệu truyền vào file giao diện được đặt tên là `giohangdata`
        // dd($data);
        echo $twig->render('frontend/thanhtoan/thanhtoan.html.twig', [
            'giohangdata' => $data,
            'danhsachhinhthucthanhtoan' => $dataHinhThucThanhToan,
            'khachhang' => $dataKhachHang
        ]);
    }
}
