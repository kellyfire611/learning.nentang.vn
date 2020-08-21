<?php
  // Phân tách dữ liệu từ khối dữ liệu được truyền đến từ CLIENT (Request POST)
  // Biến lưu trữ thông tin Username
  $ten_tai_khoan = $_POST['username'];
  
  // Biến lưu trữ thông tin Password
  $mat_khau = $_POST['password'];

  // Biến lưu trữ thông tin Full name
  $ho_ten = $_POST['full_name'];

  // Xử lý các logic/Nghiệp vụ ...
  // Lưu vào database ...
  echo "Chào mừng bạn {$ho_ten} đã đăng ký thành công. Tên tài khoản để đăng nhập Hệ thống là: ${ten_tai_khoan}.";
?>