<?php
  // Phân tách dữ liệu từ khối dữ liệu được truyền đến từ CLIENT (Request GET)
  // Biến lưu trữ thông tin Username
  $ten_tai_khoan = $_GET['username'];
  
  // Biến lưu trữ thông tin Password
  $mat_khau = $_GET['password'];

  // Xử lý các logic/Nghiệp vụ ...
  if($ten_tai_khoan == 'admin' && $mat_khau == '123456') {
    echo "<h1>Chào mừng bạn {$ten_tai_khoan} đã đăng nhập thành công!</h1>";
  } else {
    echo "<h1>Đăng nhập Thất bại!</h1>";
  }
?>