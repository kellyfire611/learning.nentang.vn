# 4.Tạo bố cục (layout) cho ứng dụng web sử dụng `Twig`
- Mỗi ứng dụng web thường sẽ có một `bố cục (layout)` đồng nhất về giao diện.
- Ý tưởng sử dụng `bố cục (layout)` là để có thể kế thừa lại giao diện; sử dụng cùng lúc nhiều nơi trong các trang của ứng dụng; dễ dàng tùy chỉnh bố cục khi cần thiết;...
- Cách thực hiện thường thấy:
    - Ứng dụng web sẽ tạo 1 `layout chung` cho toàn ứng dụng web. 
    - Các `trang (hay chức năng)` của ứng dụng sẽ kế thừa `layout chung` và nhúng các thành phần giao diện tương ứng với `trang (hay chức năng)` vào thành phần đã `đục lỗ (hay chừa chỗ sẵn)` trong `layout chung`.

## Step 1: chuẩn bị thư viện frontend để tạo bố cục trang web
- Chúng ta sẽ sử dụng một số thư viện giao diện nổi tiếng như sau:
    - Bootstrap 4.3 (mới nhất hiện nay). [Bootstrap 4 examples](https://getbootstrap.com/docs/4.3/examples/)
    - Jquery 3.3.1 (mới nhất hiện nay). [Jquery 3.3.1](https://jquery.com/download/)
    - PopperJS. [PopperJS](https://popper.js.org/)
    - Feather icon. [Feather icon](https://feathericons.com/)

### Step 1.1. Cài đặt Bootstrap 4.3.1
- Truy cập trang chủ để xem hướng dẫn - [Starter template](https://getbootstrap.com/docs/4.3/getting-started/introduction/)
- [Download Bootstrap 4.3.1](https://github.com/twbs/bootstrap/archive/v4.3.1.zip)
- Download về, giải nén, copy thư mục `dist` vào trong thư mục `/assets/vendor/` và đổi tên thư mục `dist` thành `bootstrap`.
- Để dễ dàng quản lý các thư viện Frontend, chúng ta sẽ tạo cấu trúc thư mục như sau:
```
+---assets
|   \---vendor
|       \---bootstrap
|           +---css
|           \---js
|       ...
|       \***Các gói thư viện frontend khác
```

### Step 1.2. Cài đặt Jquery
- [Download Jquery](https://code.jquery.com/jquery-3.3.1.min.js)
- Download về, chép vào thư mục như cấu trúc sau:
```
+---assets
|   \---vendor
|       \---jquery
|           +---jquery.min.js
|       ...
|       \***Các gói thư viện frontend khác
```

### Step 1.3. Cài đặt PopperJS
- [PopperJS v1](https://unpkg.com/popper.js/dist/umd/popper.min.js)
- Download về, chép vào thư mục như cấu trúc sau:
```
+---assets
|   \---vendor
|       \---popperjs
|           +---jquery.min.js
|       ...
|       \***Các gói thư viện frontend khác
```

### Step 1.4. Cài đặt FeatherIcon
- [Feather Icon Javascript](https://unpkg.com/feather-icons/dist/feather.min.js)
- Download về, chép vào thư mục như cấu trúc sau:
```
+---assets
|   \---vendor
|       \---feather
|           +---feather.min.js
|       ...
|       \***Các gói thư viện frontend khác
```

## Step 2: tạo file template quản lý bố cục ứng dụng web
- Để dễ dàng quản lý các file template về bố cục. Chúng ta tạo mới thư mục `/templates/backend/layouts`
- Tạo file `/templates/backend/layouts/layout.html.twig`
- Nội dung file:
```html
<!DOCTYPE html>
<html lang="vi-VN">
    <head>
        <meta charset="UTF-8">
        <title>Nền tảng - Bố cục chung</title>
    </head>
    <body>
        <!-- Block content - Đục lỗ trên giao diện bố cục chung, đặt tên là `content` -->
        {% block content %}
        {% endblock %}
        <!-- End block content -->
    </body>
</html>
```

## Step 3: tạo file template `Giới thiệu`
- Để dễ dàng quản lý các trang tĩnh (static). Ta sẽ tạo thư mục `/pages/` để quản lý các trang này.
- Trang `Giới thiệu` đơn giản là một nội dung tĩnh; hoặc nếu làm tốt hơn, các bạn có thể lưu nội dung trang trong database và truy xuất.
- Tạo file `/pages/gioithieu.php`
- Nội dung file:
```php

```

## Step 4: tạo file template `Giới thiệu` kế thừa giao diện `layout chung`
- Để dễ dàng quản lý các chức năng tương ứng. Chúng ta sẽ tạo thư mục tương ứng với chức năng
- Nội dung file:
```html
{% extends "layout.html" %}

{% block content %}
    <table border="1" style="width: 80%;">
        <thead>
            <tr>
                <td>Product</td>
                <td>Description</td>
                <td>Value</td>
                <td>Date</td>
            </tr>
        </thead>
        <tbody>
            {% for product in products %}
                <tr>
                    <td>{{ product.name }}</td>
                    <td>{{ product.description }}</td>
                    <td>{{ product.value }}</td>
                    <td>{{ product.date_register|date("m/d/Y") }}</td>
                </tr>
            {% endfor %}
        </tbody>
    </table>
{% endblock %}
```