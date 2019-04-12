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
```php
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
```

## Step 2: bổ sung biến SESSION vào phạm vi global của TWIG để tiện sử dụng cho tất cả các view được render bởi TWIG
- Hiệu chỉnh file `/php/twig/bootstrap.php`, bổ sung thêm đoạn code sau
```php
// Tạo biến global để có thể sử dụng trong tất cả các view được render bởi TWIG
$twig->addGlobal('session', $_SESSION);
```

## Step 3: gắn liên kết `logout` vào phần header của backend
- Hiệu chỉnh file file `/php/twig/templates/backend/layouts/includes/header.html.twig` để gắn liên kết xử lý Đăng xuất
```html
<nav class="navbar navbar-dark fixed-top bg-dark flex-md-nowrap p-0 shadow">
    <a class="navbar-brand col-sm-3 col-md-2 mr-0" href="#">Nền tảng</a>
    <input class="form-control form-control-dark w-100" type="text" placeholder="Tìm kiếm" aria-label="Search">
    <ul class="navbar-nav px-3">
        <li class="nav-item text-nowrap">
            {% if session['username'] %}
            <!-- Nếu đã đăng nhập thì hiển thị nút Đăng xuất -->
            <a class="nav-link" href="/php/twig/backend/pages/logout.php">{{ session['username'] }} - Đăng xuất</a>
            {% else %}
            <!-- Nếu chưa đăng nhập thì hiển thị nút Đăng nhập -->
            <a class="nav-link" href="/php/twig/backend/pages/login.php">Đăng nhập</a>
            {% endif %}
        </li>
    </ul>
</nav>
```

## Kiểm tra ứng dụng
- Truy cập địa chỉ: [http://learning.nentang.vn/php/twig/backend/pages/logout.php](http://learning.nentang.vn/php/twig/backend/pages/logout.php)

# Bài học trước
[Bài học 7](./readme-lession7.md)

# Bài học tiếp theo
[Bài học 9](./readme-lession9.md)