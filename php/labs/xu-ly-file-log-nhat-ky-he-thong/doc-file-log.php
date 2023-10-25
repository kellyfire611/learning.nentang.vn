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
  <title>Bài tập đọc file LOG | NenTang.vn</title>
</head>

<body>
  <h1>Đọc thông tin file LOG Hệ thống</h1>
  <?php
  ini_set('display_errors', 0);
  ini_set('display_startup_errors', 0);
  error_reporting(E_ALL & ~E_DEPRECATED & ~E_STRICT);

  try {
    // Mở file để đọc (R - READ)
    $filePath = __DIR__ . '/log-2023-10-25.txt';
    $logfile = fopen($filePath, 'r');

    if($logfile) {
      // Đọc toàn bộ nội dung file (dùng cho file nhỏ)
      // echo fread($logfile, filesize($filePath));

      // Đọc từng dòng (dành cho file lớn)
      while( ($line = fgets($logfile)) !== false ) {
        echo $line . '<br />';
      }
    } else {
      echo 'Không tìm thấy file!';
    }

    // Đóng file
    fclose($logfile);
  }
  catch(DivisionByZeroError $ex) {
    echo $ex->getMessage();
  }
  catch(Exception $ex) {
    echo $ex->getMessage();
  }
  ?>
</body>

</html>