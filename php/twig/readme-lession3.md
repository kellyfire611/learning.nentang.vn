# 5. Phân tách các thành phần của bố cục (layout) backend
- Các thành phần thường có của bố cục (layout) backend bao gồm:
  - `header`: hiển thị brand logo, thông tin người dùng đăng nhập (họ tên/email/avatar), menu Đăng xuất, ...
  - `sidebar`: thanh điều hướng chứa các menu chức năng của hệ thống.
  - `content`: vùng nội dung chính của layout, các chức năng con sẽ nhúng giao diện vào layout chính.
  - `footer`: chân trang, chứa thông tin phiên bản hệ thống, chứng nhận bản quyền,...
- Chúng ta sẽ tiến hành tách các thành phần từ bố cục ra để dễ dàng quản lý

## Step 1: phân tích các thành phần bố cục layout backend

### Step 1.1: phân tích các thành phần bố cục layout backend


### Step 1.2: tạo thư mục để quản lý các thành phần
- Tạo thư mục `/templates/backend/layouts/includes` để chứa các thành phần của layout backend
```
+---php
|   \---twig                    <- Đây là thư mục gốc của dự án, các bạn có thể đặt tên các bạn...
|       \---templates
|           \---backend
|               \---layouts
|                   \---includes    <- tạo thư mục quản lý các thành phần
```

## Step 2: tạo template thành phần `header`
- Tạo file `/templates/backend/layouts/includes/header.html.twig`
```
+---php
|   \---twig                    <- Đây là thư mục gốc của dự án, các bạn có thể đặt tên các bạn...
|       \---templates
|           \---backend
|               \---layouts
|                   \---includes
|                       \---header.html.twig  <- tạo file
```
- Nội dung file:
```html
<nav class="navbar navbar-dark fixed-top bg-dark flex-md-nowrap p-0 shadow">
    <a class="navbar-brand col-sm-3 col-md-2 mr-0" href="#">Nền tảng</a>
    <input class="form-control form-control-dark w-100" type="text" placeholder="Tìm kiếm" aria-label="Search">
    <ul class="navbar-nav px-3">
        <li class="nav-item text-nowrap">
            <a class="nav-link" href="#">Đăng xuất</a>
        </li>
    </ul>
</nav>
```
- Hiệu chỉnh file `/templates/backend/layouts/layout.html.twig` để tách thành phần `header` từ layout chính ra
```html
#Tìm đoạn
<nav class="navbar navbar-dark fixed-top bg-dark flex-md-nowrap p-0 shadow">
    <a class="navbar-brand col-sm-3 col-md-2 mr-0" href="#">Nền tảng</a>
    <input class="form-control form-control-dark w-100" type="text" placeholder="Tìm kiếm" aria-label="Search">
    <ul class="navbar-nav px-3">
        <li class="nav-item text-nowrap">
            <a class="nav-link" href="#">Đăng xuất</a>
        </li>
    </ul>
</nav>

#Thay thế bằng
<!-- header -->
{{ include('backend/layouts/includes/header.html.twig') }}
<!-- end header -->
```

## Step 3: tạo template thành phần `sidebar`
- Tạo file `/templates/backend/layouts/includes/sidebar.html.twig`
```
+---php
|   \---twig                    <- Đây là thư mục gốc của dự án, các bạn có thể đặt tên các bạn...
|       \---templates
|           \---backend
|               \---layouts
|                   \---includes
|                       \---sidebar.html.twig  <- tạo file
```
- Nội dung file:
```html
<nav class="col-md-2 d-none d-md-block bg-light sidebar">
    <div class="sidebar-sticky">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link active" href="#">
                    <span data-feather="home"></span>
                    Bảng tin <span class="sr-only">(current)</span>
                </a>
            </li>
        </ul>
    </div>
</nav>
```
- Hiệu chỉnh file `/templates/backend/layouts/layout.html.twig` để tách thành phần `sidebar` từ layout chính ra
```html
#Tìm đoạn
<nav class="col-md-2 d-none d-md-block bg-light sidebar">
    <div class="sidebar-sticky">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link active" href="#">
                    <span data-feather="home"></span>
                    Bảng tin <span class="sr-only">(current)</span>
                </a>
            </li>
        </ul>
    </div>
</nav>

#Thay thế bằng
<!-- sidebar -->
{{ include('backend/layouts/includes/sidebar.html.twig') }}
<!-- end sidebar -->
```

## Step 4: tạo template thành phần `footer`
- Tạo file `/templates/backend/layouts/includes/footer.html.twig`
```
+---php
|   \---twig                    <- Đây là thư mục gốc của dự án, các bạn có thể đặt tên các bạn...
|       \---templates
|           \---backend
|               \---layouts
|                   \---includes
|                       \---footer.html.twig  <- tạo file
```
- Nội dung file:
```html
<footer class="footer mt-auto py-3">
    <div class="container">
        <span>Bản quyền &copy; bởi <a href="https://nentang.vn">Nền Tảng</a> - 2019.</span>
        <span class="text-muted">Hành trang tới Tương lai</span>
    </div>
</footer>
```
- Hiệu chỉnh file `/templates/backend/layouts/layout.html.twig` để tách thành phần `sidebar` từ layout chính ra
```html
#Tìm đoạn
<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="/php/twig/assets/vendor/jquery/jquery.min.js"></script>
<script src="/php/twig/assets/vendor/popperjs/popper.min.js"></script>
<script src="/php/twig/assets/vendor/bootstrap/js/bootstrap.min.js"></script>
<script src="/php/twig/assets/vendor/feather/feather.min.js"></script>

<!-- Custom script - Các file js do mình tự viết -->
<script src="/php/twig/assets/backend/js/app.js"></script>

#Bổ sung thêm bên trên
<!-- footer -->
{{ include('backend/layouts/includes/footer.html.twig') }}
<!-- end footer -->
```
- Để footer được tự động nằm dưới cùng của chân trang. Chúng ta sẽ hiệu chỉnh thêm một số class tùy biến chiều cao cho layout backend, sử dụng Bootstrap 4 Utilities.
- Hiệu chỉnh file `/templates/backend/layouts/layout.html.twig`
- Bổ sung class `d-flex flex-column h-100` cho tag `<body>`
```html
<body class="d-flex flex-column h-100">
  ...
</body>
```

- Bổ sung class `h-100` cho tag `<html>`
```html
<html lang="vi" class="h-100">
  ...
</html>
```

- Hiệu chỉnh file `/php/twig/assets/backend/css/style.css`, bổ sung thêm màu nền cho footer
```css
/*
Footer
*/
.footer {
  background-color: #272b30;
  color: #fff;
}
```

## Step 5: Kiểm tra ứng dụng
- Truy cập địa chỉ: [http://learning.nentang.vn/php/twig/backend/pages/dashboard.php](http://learning.nentang.vn/php/twig/backend/pages/dashboard.php)

# Bài học trước
[Bài học 2](./readme-lession2.md)

# Bài học tiếp theo
[Bài học 4](./readme-lession4.md)