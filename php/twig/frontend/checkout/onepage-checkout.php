<?php
// Include file cấu hình ban đầu của `Twig`
require_once __DIR__ . '/../../bootstrap.php';

// Truy vấn database để lấy danh sách
// 1. Include file cấu hình kết nối đến database, khởi tạo kết nối $conn
include_once(__DIR__ . '/../../dbconnect.php');


if (!isset($_POST['btnDatHang'])) {
    // Nếu trong SESSION có giá trị của key 'email' <-> người dùng đã đăng nhập thành công
    // Nếu chưa đăng nhập thì chuyển hướng về trang đăng nhập
    if (!isset($_SESSION['frontend']['email'])) {
        header('location:../auth/login.php');
        return;
    }

    // Hiển thị trang thanh toán
    // Lấy thông tin Hình thức thanh toán
    // Câu lệnh SELECT
    $sqlSelectHinhThucThanhToan = <<<EOT
        SELECT * 
        FROM shop_payment_types
    EOT;

    // Thực thi SELECT
    $resultSelectHinhThucThanhToan = mysqli_query($conn, $sqlSelectHinhThucThanhToan);
    $dataPaymentTypes = [];
    while ($row = mysqli_fetch_array($resultSelectHinhThucThanhToan, MYSQLI_ASSOC)) {
        $dataPaymentTypes[] = array(
            'id' => $row['id'],
            'payment_code' => $row['payment_code'],
            'payment_name' => $row['payment_name'],
            'description' => $row['description'],
            'image' => $row['image'],
            'created_at' => $row['created_at'],
            'updated_at' => $row['updated_at'],
        );
    }

    // Lấy thông tin khách hàng
    // Lấy dữ liệu người dùng đã đăng nhập từ SESSION
    $email = $_SESSION['frontend']['email'];

    // Câu lệnh SELECT
    $sqlSelect = <<<EOT
        SELECT *
        FROM shop_customers
        WHERE email = '$email'
        LIMIT 1;
    EOT;

    // Thực thi SELECT
    $result = mysqli_query($conn, $sqlSelect);
    $dataCustomer;
    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        $dataCustomer = array(
            'id' => $row['id'],
            'username' => $row['username'],
            'last_name' => $row['last_name'],
            'first_name' => $row['first_name'],
            'email' => $row['email'],
            'avatar' => $row['avatar'],
            'billing_address' => $row['billing_address'],
            'shipping_address' => $row['shipping_address'],
            'phone' => $row['phone'],
            'avatar' => $row['avatar'],
            // Sử dụng hàm date($format, $timestamp) để chuyển đổi ngày thành định dạng Việt Nam (ngày/tháng/năm)
            // Do hàm date() nhận vào là đối tượng thời gian, chúng ta cần sử dụng hàm strtotime() để chuyển đổi từ chuỗi có định dạng 'yyyy-mm-dd' trong MYSQL thành đối tượng ngày tháng
            'birthday' => date('d/m/Y', strtotime($row['birthday'])),
            'code' => $row['code'],
            'created_at_formatted' => date('d/m/Y H:i:s', strtotime($row['created_at'])),
        );
    }

    // Kiểm tra dữ liệu trong session
    $data = [];
    if (isset($_SESSION['cartdata'])) {
        $data = $_SESSION['cartdata'];
    } else {
        $data = [];
    }

    // Yêu cầu `Twig` vẽ giao diện được viết trong file `frontend/checkout/onepage-checkout.html.twig`
    // với dữ liệu truyền vào file giao diện được đặt tên là `cartdata`
    echo $twig->render('frontend/checkout/onepage-checkout.html.twig', [
        'cartdata' => $data,
        'payment_types' => $dataPaymentTypes,
        'customer' => $dataCustomer
    ]);
    return;
}

// Lưu đơn hàng
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
    $quantity = $sanpham['quantity'];

    // Insert Sản phẩm Đơn hàng
    // Câu lệnh INSERT
    $sqlSanPhamDonHang = "INSERT INTO `sanpham_dondathang` (sp_ma, dh_ma, sp_dh_quantity, sp_dh_dongia) VALUES ($sp_ma, $last_donhang_id, $quantity, $gia);";

    // Thực thi INSERT
    mysqli_query($conn, $sqlSanPhamDonHang);
}

// Thanh toán thành công, xóa Giỏ hàng trong SESSION
// lưu dữ liệu giỏ hàng vào session
$_SESSION['cartdata'] = [];

echo $twig->render('frontend/thanhtoan/thanhtoan-finish.html.twig');
