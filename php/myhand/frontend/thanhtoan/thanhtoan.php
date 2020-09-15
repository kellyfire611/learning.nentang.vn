<?php
// hàm `session_id()` sẽ trả về giá trị SESSION_ID (tên file session do Web Server tự động tạo)
// - Nếu trả về Rỗng hoặc NULL => chưa có file Session tồn tại
if (session_id() === '') {
    // Yêu cầu Web Server tạo file Session để lưu trữ giá trị tương ứng với CLIENT (Web Browser đang gởi Request)
    session_start();
}

// Đã người dùng chưa đăng nhập rồi -> hiển thị thông báo yêu cầu người dùng đăng nhập
if (!isset($_SESSION['kh_tendangnhap_logged']) || empty($_SESSION['kh_tendangnhap_logged'])) {
    echo 'Vui lòng Đăng nhập trước khi Thanh toán! <a href="/php/myhand/backend/auth/login.php">Click vào đây để đến trang Đăng nhập</a>';
    die;
} else {
    // Nếu đã đăng nhập, tạo Đơn hàng
    // 1. Phân tách lấy dữ liệu người dùng gởi từ REQUEST POST
    // Thông tin đơn hàng
    $kh_tendangnhap = $_SESSION['kh_tendangnhap_logged'];
    $dh_ngaylap = date('Y-m-d'); // Lấy ngày hiện tại theo định dạng yyyy-mm-dd
    $dh_ngaygiao = '';
    $dh_noigiao = '';
    $dh_trangthaithanhtoan = 0; // Mặc định là 0 chưa thanh toán
    $httt_ma = 1; // Mặc định là 1

    // Thông tin các dòng chi tiết đơn hàng
    $arr_sp_ma = $_POST['sp_ma'];                   // mảng array do đặt tên name="sp_ma[]"
    $arr_sp_dh_soluong = $_POST['sp_dh_soluong'];   // mảng array do đặt tên name="sp_dh_soluong[]"
    $arr_sp_dh_dongia = $_POST['sp_dh_dongia'];     // mảng array do đặt tên name="sp_dh_dongia[]"
    // var_dump($sp_ma);die;

    // 2. Thực hiện câu lệnh Tạo mới (INSERT) Đơn hàng
    // Câu lệnh INSERT
    $sqlInsertDonHang = "INSERT INTO `dondathang` (`dh_ngaylap`, `dh_ngaygiao`, `dh_noigiao`, `dh_trangthaithanhtoan`, `httt_ma`, `kh_tendangnhap`) VALUES ('$dh_ngaylap', '$dh_ngaygiao', N'$dh_noigiao', '$dh_trangthaithanhtoan', '$httt_ma', '$kh_tendangnhap')";
    // print_r($sql); die;

    // Thực thi INSERT Đơn hàng
    mysqli_query($conn, $sqlInsertDonHang);

    // 3. Lấy ID Đơn hàng mới nhất vừa được thêm vào database
    // Do ID là tự động tăng (PRIMARY KEY và AUTO INCREMENT), nên chúng ta không biết được ID đă tăng đến số bao nhiêu?
    // Cần phải sử dụng biến `$conn->insert_id` để lấy về ID mới nhất
    // Nếu thực thi câu lệnh INSERT thành công thì cần lấy ID mới nhất của Đơn hàng để làm khóa ngoại trong Chi tiết đơn hàng
    $dh_ma = $conn->insert_id;

    // 4. Duyệt vòng lặp qua mảng các dòng Sản phẩm của chi tiết đơn hàng được gởi đến qua request POST
    for ($i = 0; $i < count($arr_sp_ma); $i++) {
        // 4.1. Chuẩn bị dữ liệu cho câu lệnh INSERT vào table `sanpham_dondathang`
        $sp_ma = $arr_sp_ma[$i];
        $sp_dh_soluong = $arr_sp_dh_soluong[$i];
        $sp_dh_dongia = $arr_sp_dh_dongia[$i];

        // 4.2. Câu lệnh INSERT
        $sqlInsertSanPhamDonDatHang = "INSERT INTO `sanpham_dondathang` (`sp_ma`, `dh_ma`, `sp_dh_soluong`, `sp_dh_dongia`) VALUES ($sp_ma, $dh_ma, $sp_dh_soluong, $sp_dh_dongia)";

        // 4.3. Thực thi INSERT
        mysqli_query($conn, $sqlInsertSanPhamDonDatHang);
    }

    // 5. Thực thi hoàn tất, điều hướng về trang Danh sách
    echo '<script>location.href = "index.php";</script>';
}
