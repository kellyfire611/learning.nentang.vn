<?php
// Include file cấu hình ban đầu của `Twig`
require_once __DIR__.'/../../bootstrap.php';

// Chức năng Đăng xuất đơn giản là xóa giá trị của người dùng đã đăng nhập trong SESSION
// Và điều hướng người dùng về trang chúng ta mong muốn

// Nếu trong SESSION có giá trị của key 'username' <-> người dùng đã đăng nhập thành công
// Điều hướng người dùng về trang DASHBOARD
if(isset($_SESSION['username'])) {
    unset($_SESSION['username']);
    header('location:login.php');
}
else {
    echo 'Người dùng chưa đăng nhập. Không thể đăng xuất dược!'; die;
}