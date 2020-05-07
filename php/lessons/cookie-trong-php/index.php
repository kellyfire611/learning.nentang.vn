<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cookie trong PHP | Nền tảng VN</title>
</head>

<body>
    <h1>Cookie trong PHP</h1>

    <?php
    // Kiểm tra xem Người dùng có sử dụng Ghi nhớ Đăng nhập không?
    if(isset($_COOKIE['is_logged'])) {
        // Lấy thông tin từ COOKIE từ Web Browser của client gởi đến
        $username = isset($_COOKIE['username']) ? $_COOKIE['username'] : '';

        echo "Xin chào $username! Bạn đã đăng nhập rồi.";
        die;
    }
    ?>

    <!-- Form Login -->
    <form name="frmLogin" method="post" action="">
        Tài khoản: <input type="text" name="username" id="username" /><br />
        Mật khẩu: <input type="text" name="password" id="password" /><br />
        Ghi nhớ đăng nhập: <input type="checkbox" name="remember_me" id="remember_me" value="1" /><br />
        <input type="submit" name="btnLogin" value="Đăng nhập" />
    </form>

    <?php
    // Xử lý nếu người dùng có bấm nút "btnLogin"
    if(isset($_POST['btnLogin'])) {
        // Lấy thông tin người dùng gởi đến Server
        $username = $_POST['username'];
        $password = $_POST['password'];
        $remember_me = isset($_POST['remember_me']) ? 1 : 0;

        // Nếu người dùng có chọn "Ghi nhớ Đăng nhập"
        // => tiến hành lưu thông tin vào COOKIE và gởi lại người dùng
        if($remember_me == 1) {
            // Thiết lập Cookie "Ghi nhớ đăng nhập"
            setcookie('is_logged', true, time()+ 3600, '/');

            // Thiết lập Cookie "Tên username đã đăng nhập"
            setcookie("username_logged", $username, time()+3600, "/","", 0);
        }

        // Hiển thị thông tin chào mừng
        echo "<h2>Xin chào $username!</h2>";
    }
    ?>

    
</body>

</html>