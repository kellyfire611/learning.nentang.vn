<?php
// Include file cấu hình ban đầu của `Twig`
require_once __DIR__ . '/../../bootstrap.php';

// Truy vấn database
// 1. Include file cấu hình kết nối đến database, khởi tạo kết nối $conn
include_once(__DIR__ . '/../../dbconnect.php');

// 2. Người dùng mới truy cập trang lần đầu tiên (người dùng chưa gởi dữ liệu `btnLogin` - chưa nhấn `nút Đăng nhập`) về Server
// có nghĩa là biến $_POST['btnLogin'] chưa được khởi tạo hoặc chưa có giá trị
// => hiển thị Form nhập liệu
if (!isset($_POST['btnLogin'])) {
    // Nếu trong SESSION có giá trị của key 'email' <-> người dùng đã đăng nhập rồi
    // Điều hướng người dùng về trang chủ
    if (isset($_SESSION['backend']['email'])) {
        // echo "<h1>Xin chào mừng ". $_SESSION['backend']['email'] ."</h1>";
        // echo session_save_path();
        header('location:home.php');
    } else {
        // Biến dùng lưu thông báo lỗi
        $errors = [];

        // Nếu chưa đăng nhập trước đó
        // Yêu cầu `Twig` vẽ giao diện được viết trong file `backend/auth/login.html.twig`
        // với dữ liệu truyền vào file giao diện được đặt tên là `errors`
        echo $twig->render('backend/auth/login.html.twig', ['errors' => $errors]);
    }
    return;
}

// 3. Nếu người dùng có bấm nút Đăng nhập thì thực thi câu lệnh UPDATE
// Lấy dữ liệu người dùng hiệu chỉnh gởi từ REQUEST POST
$email = $_POST['email'];
$password = sha1($_POST['password']); // mã hóa password với giải thuật SHA1

// Câu lệnh SELECT
$sql = <<<EOT
    SELECT username, last_name, first_name, email, avatar, status
    FROM `shop_customers`
    WHERE email = '$email' AND password = '$password'
    LIMIT 1;
EOT;

// Thực thi SELECT
$result = mysqli_query($conn, $sql);

// Sử dụng hàm `mysqli_num_rows()` để đếm số dòng SELECT được
// Nếu có bất kỳ dòng dữ liệu nào SELECT được <-> Người dùng có tồn tại và đã đúng thông tin USERNAME, PASSWORD
if (mysqli_num_rows($result) > 0) {
    $data = [];
    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        $data[] = array(
            'username' => $row['username'],
            'last_name' => $row['last_name'],
            'first_name' => $row['first_name'],
            'email' => $row['email'],
            'avatar' => $row['avatar'],
            'status' => $row['status'],
        );
    }

    // Đóng kết nối
    mysqli_close($conn);

    // Kiểm tra Trạng thái của Tài khoản (Đã kích hoạt hay chưa kích hoạt?)
    if ($data[0]['status'] != 1) { //Chưa kích hoạt tài khoản
        // Biến dùng lưu thông báo lỗi
        $errors = [];
        $errors['email'][] = [
            'rule' => 'must_activate_account',
            'rule_value' => true,
            'value' => '',
            'msg' => 'Tài khoản của bạn chưa được kích hoạt. Vui lòng kiểm tra hộp mail để xác nhận Tài khoản!!!'
        ];

        // Nếu chưa kích hoạt tài khoản thì hiển thị giao diện yêu cầu kích hoạt tài khoản
        // Yêu cầu `Twig` vẽ giao diện được viết trong file `backend/auth/user-not-activated.html.twig`
        // với dữ liệu truyền vào file giao diện được đặt tên là `errors`
        echo $twig->render('backend/auth/user-not-activated.html.twig', ['errors' => $errors]);
    } else { 
        // Đã kích hoạt -> Đăng nhập thành công
        // Lưu thông tin Đăng nhập vào Session
        $_SESSION['backend']['email'] = $email;
        $_SESSION['backend']['is_logged'] = true; // #True: Đăng nhập thành công; #False: Thất bại

        // Điều hướng về Trang chủ
        header("location:./../pages/home.php");
    }
} else {
    // Biến dùng lưu thông báo lỗi
    $errors = [];

    // Không tìm thấy bất kỳ dòng dữ liệu nào => Người dùng cung cấp thông tin email/password sai.
    // Hiển thị lại trang Đăng nhập với thông báo lỗi "Đăng nhập thất bại"
    $errors['email'][] = [
        'rule' => 'login',
        'rule_value' => 0,
        'value' => '',
        'msg' => 'Đăng nhập thất bại! Vui lòng kiểm tra lại <b>Địa chỉ Email</b> hoặc <b>Mật khẩu</b> của bạn và thử lại...'
    ];

    // Yêu cầu `Twig` vẽ giao diện được viết trong file `backend/shop_suppliers/create.html.twig`
    // kèm theo dữ liệu thông báo lỗi
    echo $twig->render('backend/auth/login.html.twig', ['errors' => $errors]);
}
