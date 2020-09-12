<?php
// hàm `session_id()` sẽ trả về giá trị SESSION_ID (tên file session do Web Server tự động tạo)
// - Nếu trả về Rỗng hoặc NULL => chưa có file Session tồn tại
if (session_id() === '') {
    // Yêu cầu Web Server tạo file Session để lưu trữ giá trị tương ứng với CLIENT (Web Browser đang gởi Request)
    session_start();
}

// Chức năng Đăng xuất đơn giản là xóa giá trị của người dùng đã đăng nhập trong SESSION
// Và điều hướng người dùng về trang chúng ta mong muốn
// Nếu trong SESSION có giá trị của key 'kh_tendangnhap_logged' <-> người dùng đã đăng nhập thành công
// => xóa bỏ giá trị trong SESSION và Điều hướng người dùng về trang DASHBOARD
if(isset($_SESSION['kh_tendangnhap_logged'])) {
    unset($_SESSION['kh_tendangnhap_logged']);
    header('location:login.php');
}
else {
    echo 'Người dùng chưa đăng nhập. Không thể đăng xuất dược!'; die;
}