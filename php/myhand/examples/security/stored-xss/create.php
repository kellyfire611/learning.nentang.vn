<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>XSS Stored và cách phòng chống - Nền tảng .vn</title>
</head>

<body>

  <a href="index.php">Danh sách HTTT</a>
  <form name="frmHinhThucThanhToan" id="frmHinhThucThanhToan" method="post" action="">
    Tên Hình thức thanh toán: <input type="text" name="httt_ten" id="httt_ten" /><br />

    <input type="submit" name="btnSave" id="btnSave" value="Lưu dữ liệu" />
  </form>

  <?php
  // Hiển thị tất cả lỗi trong PHP
  // Chỉ nên hiển thị lỗi khi đang trong môi trường Phát triển (Development)
  // Không nên hiển thị lỗi trên môi trường Triển khai (Production)
  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
  error_reporting(E_ALL);

  if (isset($_POST['btnSave'])) {
    // 1. Liên kết Database
    include_once(__DIR__ . '/../../../dbconnect.php');

    // Phân tách thông tin từ người dùng gởi đến qua Request POST
    // Sử dụng hàm htmlentites để mã hóa các ký tự có khả năng thực thi JavaScript trước khi lưu trữ
    $tenhinhthucthanhtoan =  $_POST['httt_ten'] ;
    // $tenhinhthucthanhtoan = htmlentities( $_POST['httt_ten'] );

    // 2. Chuẩn bị QUERY
    $sql = "INSERT INTO `hinhthucthanhtoan`(httt_ten) VALUES('$tenhinhthucthanhtoan');";

    // print_r($sql);die;

    // 3. Thực thi
    mysqli_query($conn, $sql);

    echo 'Lưu thành công';
  };
  ?>
</body>

</html>