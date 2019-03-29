# Twig
- `Twig` là một Template Engine nổi tiếng của Symfony.
- Cú pháp của `Twig` khá dễ học. Về cơ bản chỉ có các thành phần sau:
    - Sử dụng `{{ variable }}` để render giá trị thay thế cho dòng lệnh thường thấy của php thuần `<?php echo $variable; ?>`
    - Sử dụng `{% logic %}` cho các xử lý dạng logic `if; loop; while`
    - Sử dụng `{# comment #}` cho các ghi chú (comments)
    - ...
- Tài liệu tham khảo:
    - Cú pháp sử dụng Twig version 2x: [https://twig.symfony.com/doc/2.x/templates.html](https://twig.symfony.com/doc/2.x/templates.html)

# 1.Cài đặt TWIG
## Step 1: Cài đặt `composer` (công cụ quản lý các gói package PHP):
- Download: [https://getcomposer.org/download/](https://getcomposer.org/download/)
- Chọn Window Installer để cài đặt.

## Step 2: Cài đặt thư viện `twig/twig` thông qua `composer`:
- Chạy câu lệnh sau:
```
composer require twig/twig
```
- Cấu trúc thư mục sau khi cài đặt:
```
+---php
|   \---twig
|       \---vendor
|           +---composer
|           +---symfony
|           +---twig
|           +---twig
|           \---autoload.php
|   +---composer.json
|   \---composer.lock
```

# Bài học tiếp theo
[Bài học 1](./readme-lession1.md)