<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cookie trong PHP | Nền tảng VN</title>
</head>

<body>
    <h1>Cookie trong PHP</h1>

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

        // Đối với checkbox cần kiểm tra xem giá trị có tồn tại hay không?
        // Nếu có thì lấy giá trị do người dùng checked; nếu không thì phải gán giá trị mặc định
        $remember_me = isset($_POST['remember_me']) ? 1 : 0; 

        // Xử lý các logic/Nghiệp vụ ...
        // Nếu username=admin và password=123456 thì đăng nhập thành công
        if($username == 'admin' && $password == '123456') {

            // Hiển thị thông tin chào mừng
            echo "<h2>Xin chào $username!</h2>";
        } else {
            echo "Đăng nhập thất bại!";
        }
    }
    ?>

    
</body>

</html>