<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Session trong PHP | Nền tảng VN</title>
</head>

<body>
    <h1>Session trong PHP</h1>

    <?php
    // hàm `session_id()` sẽ trả về giá trị SESSION_ID (tên file session do Web Server tự động tạo)
    // - Nếu trả về Rỗng hoặc NULL => chưa có file Session tồn tại
    if (session_id() === '') {
        // Yêu cầu Web Server tạo file Session để lưu trữ giá trị tương ứng với CLIENT (Web Browser đang gởi Request)
        session_start();
    }

    // Nội dung file trong file session sẽ được biến $_SESSION của PHP quản lý theo dạng key=value
    // Truy xuất thông tin bằng cách: $_SESSION['keyname']
    // Gán giá trị bằng               $_SESSION['keyname'] = value;
    if (isset($_SESSION['counter'])) {
        $_SESSION['counter'] += 1;
    } else {
        $_SESSION['counter'] = 1;
    }

    $msg = '<p>Bạn đã truy cập vào trang này:' . $_SESSION['counter'] . '</p>';
    echo $msg;

    ?>
</body>

</html>