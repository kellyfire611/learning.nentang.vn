<!DOCTYPE html>
<html lang="vi">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="keywords" content="Nền tảng,HTML,CSS,XML,JavaScript, Lập trình C#, Lập trình, Web, Kiến thức, Đồ án">
  <meta name="author" content="Dương Nguyễn Phú Cường">
  <meta name="description" content="Cung cấp các kiến thức Nền tảng, cơ bản về Lập trình, Lập trình web, Lập trình di động, Cơ sở dữ liệu, ...">
  <meta property="og:locale" content="vi_VN">
  <meta property="og:type" content="website">
  <meta property="og:title" content="Nền tảng Kiến thức">
  <meta property="og:description" content="Cung cấp các kiến thức Nền tảng, cơ bản về Lập trình, Lập trình web, Lập trình di động, Cơ sở dữ liệu, ...">
  <meta property="og:url" content="https://nentang.vn/">
  <meta property="og:site_name" content="Nền tảng Kiến thức">
  <title>Bài tập ghi file LOG | NenTang.vn</title>
</head>

<body>
  <h1>Ghi thông tin vào file LOG Hệ thống</h1>
  <form name="frmThaoTac" method="post" action="">
    <label for="">Thao tác</label>
    <select name="slThaoTac">
      <option value="read">Xem</option>
      <option value="create">Thêm</option>
      <option value="update">Sửa</option>
      <option value="delete">Xóa</option>
    </select>
    <br />

    <label for="">Ghi chú</label>
    <input type="text" name="txtGhiChu" />
    <br />

    <button name="btnLuu">Lưu log</button>
  </form>

  <?php
  if (isset($_POST['btnLuu'])) {
    // Thu thập thông tin từ người dùng gởi đến
    $thaotac = $_POST['slThaoTac'];
    $ghichu = $_POST['txtGhiChu'];

    $filePath = __DIR__ . '/log-2023-10-26.txt';
    if($logfile) {
      // Mở file để ghi đè (W - WRITE)
      $logfile = fopen($filePath, 'w') or die('Không thể mở file!');
      // Mở file để ghi vào cuối (A - APPEND)
      //$logfile = fopen($filePath, 'a') or die('Không thể mở file!');

      $txt = "Thao tác: $thaotac - Ghi chú: $ghichu";
      fwrite($logfile, $txt);
    }

    // Đóng file
    fclose($logfile);
  }
  ?>
</body>

</html>