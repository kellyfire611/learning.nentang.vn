# Tạo chức năng Đăng xuất (Logout) Backend

- Chức năng Đăng xuất đơn giản là xóa giá trị của người dùng đã đăng nhập trong SESSION và điều hướng người dùng về trang chúng ta mong muốn.

## Step 1: tạo chức năng `logout` dùng để Đăng xuất hệ thống
- Tạo file `/php/twig/backend/pages/logout.php` để xử lý logic/nghiệp vụ
```
+---php
|   \---twig                    <- Đây là thư mục gốc của dự án, các bạn có thể đặt tên các bạn...
|       \---backend
|           \---pages     
|               \---logout.php  <- Tạo file
```
- Nội dung file:
```
<?php
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
```

## Step 2: gắn liên kết `logout` vào phần header của backend
- Hiệu chỉnh file file `/php/twig/templates/backend/layouts/includes\header.html.twig` để gắn liên kết xử lý Đăng xuất
```html

```

## Kiểm tra ứng dụng
- Truy cập địa chỉ: [http://learning.nentang.vn/php/twig/backend/pages/login.php](http://learning.nentang.vn/php/twig/backend/pages/login.php)

# Bài học trước
[Bài học 6](./readme-lession6.md)

# Bài học tiếp theo
[Bài học 8](./readme-lession8.md)