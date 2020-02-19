<?php
// Include file cấu hình ban đầu của `Twig`
require_once __DIR__ . '/../../bootstrap.php';

// Truy vấn database
// 1. Include file cấu hình kết nối đến database, khởi tạo kết nối $conn
include_once(__DIR__ . '/../../dbconnect.php');

// Biến dùng lưu thông báo message
$message = '';

// 2. Người dùng mới truy cập trang lần đầu tiên (người dùng chưa gởi dữ liệu `btnDangNhap` - chưa nhấn `nút Đăng nhập`) về Server
// có nghĩa là biến $_POST['btnDangNhap'] chưa được khởi tạo hoặc chưa có giá trị
// => hiển thị Form nhập liệu
if (!isset($_POST['btnLogin'])) {
    // Nếu trong SESSION có giá trị của key 'username' <-> người dùng đã đăng nhập rồi
    // Điều hướng người dùng về trang chủ
    if (isset($_SESSION['username'])) {
        // echo "<h1>Xin chào mừng ". $_SESSION['username'] ."</h1>";
        // echo session_save_path();
        header('location:home.php');
    } else {
        // Nếu chưa đăng nhập trước đó
        // Yêu cầu `Twig` vẽ giao diện được viết trong file `frontend/auth/login.html.twig`
        // với dữ liệu truyền vào file giao diện được đặt tên là `login`
        echo $twig->render('frontend/auth/login.html.twig', ['message' => $message]);
    }
    return;
}

// 3. Nếu người dùng có bấm nút Đăng nhập thì thực thi câu lệnh UPDATE
// Lấy dữ liệu người dùng hiệu chỉnh gởi từ REQUEST POST
$email = $_POST['email'];
$password = sha1($_POST['password']); // mã hóa password với giải thuật SHA1

// Câu lệnh SELECT
$sql = <<<EOT
    SELECT COUNT(*)
    FROM `shop_customers`
    WHERE email = '$email' AND password = '$password';
EOT;

// Thực thi SELECT
$result = mysqli_query($conn, $sql);

// Sử dụng hàm `mysqli_num_rows()` để đếm số dòng SELECT được
// Nếu có bất kỳ dòng dữ liệu nào SELECT được <-> Người dùng có tồn tại và đã đúng thông tin USERNAME, PASSWORD
if (mysqli_num_rows($result) > 0) {
    $data = [];
    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        $data[] = array(
            'kh_tendangnhap' => $row['kh_tendangnhap'],
            'kh_ten' => $row['kh_ten'],
            'kh_trangthai' => $row['kh_trangthai'],
        );
    }

    if ($data[0]['kh_trangthai'] != 1) { //Chưa kích hoạt tài khoản
        echo $twig->render('frontend/pages/user-not-activated.html.twig');
    } else { //Đã kích hoạt
        $message = 'Đăng nhập thành công!';
        $_SESSION['username'] = $username;
        $_SESSION['trangthai'] = 1; // 1: Đăng nhập thành công; 0: Thất bại
    }
} else {
    $message = 'Đăng nhập thất bại!';
    die;
}

// Đóng kết nối
mysqli_close($conn);
