<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SQL Injection và cách phòng chống - Nền tảng .vn</title>
</head>

<body>

  <form name="frmDangNhap" id="frmDangNhap" method="post" action="">
    Tên tài khoản: <input type="text" name="kh_tendangnhap" id="kh_tendangnhap" /><br />
    Mật khẩu: <input type="text" name="kh_matkhau" id="kh_matkhau" /><br />

    <input type="submit" name="btnLogin" id="btnLogin" value="Đăng nhập" />
  </form>

  <?php
  // Hiển thị tất cả lỗi trong PHP
  // Chỉ nên hiển thị lỗi khi đang trong môi trường Phát triển (Development)
  // Không nên hiển thị lỗi trên môi trường Triển khai (Production)
  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
  error_reporting(E_ALL);

  // Truy vấn database
  // 1. Include file cấu hình kết nối đến database, khởi tạo kết nối $conn
  include_once(__DIR__ . '/../../../dbconnect.php');

  // Chưa đăng nhập -> Xử lý logic/nghiệp vụ kiểm tra Tài khoản và Mật khẩu trong database
  if (isset($_POST['btnLogin'])) {
    // Phân tách thông tin từ người dùng gởi đến qua Request POST
    // Bổ sung hàm addslashes để chống SQL Injection
    $kh_tendangnhap = addslashes( $_POST['kh_tendangnhap'] );
    $kh_matkhau = addslashes( $_POST['kh_matkhau'] );
    // var_dump($kh_tendangnhap);
    // var_dump($kh_matkhau);die;

    // Câu lệnh SELECT Kiểm tra đăng nhập...
    $sqlSelect = <<<EOT
    SELECT *
    FROM khachhang kh
    WHERE kh.kh_tendangnhap = '$kh_tendangnhap' AND kh.kh_matkhau = '$kh_matkhau';
EOT;

    // var_dump($sqlSelect);die;

    // Thực thi SELECT
    $result = mysqli_query($conn, $sqlSelect);

    // In ra màn hình câu lệnh vừa thực thi
    echo 'Câu lệnh vừa thực thi: <br />';
    echo $sqlSelect;
    echo '<br />';

    // Sử dụng hàm `mysqli_num_rows()` để đếm số dòng SELECT được
    // Nếu có bất kỳ dòng dữ liệu nào SELECT được <-> Người dùng có tồn tại và đã đúng thông tin USERNAME, PASSWORD
    if (mysqli_num_rows($result) > 0) {

      echo '<h2>Đăng nhập thành công!</h2>';

    } else {
      echo '<h2 style="color: red;">Đăng nhập thất bại!</h2>';
    }
  }
  ?>
</body>

</html>