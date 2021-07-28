<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="keywords" content="Nền tảng,HTML,CSS,XML,JavaScript, Lập trình C#, Lập trình, Web, Kiến thức, Đồ án">
  <meta name="author" content="Dương Nguyễn Phú Cường">
  <meta name="description" content="Cung cấp các kiến thức Nền tảng, cơ bản về Lập trình, Lập trình web, Lập trình di động, Cơ sở dữ liệu, ...">
  <title>Render dữ liệu đơn giản bằng PHP | NenTang.vn</title>
</head>
<body>
  <h1>Render dữ liệu đơn giản bằng PHP</h1>

  <?php
  // Khai báo các biến (variables) mô tả về thông tin 1 Sinh viên
  $hoten = 'Dương Nguyễn Phú Cường';    // Họ tên
  $gioitinh = 'Nam';                    // Giới tính
  $diemlt = 8;                          // Điểm lý thuyết
  $diemth = 10;                         // Điểm thực hành
  ?>

  <h2>Thông tin của Sinh viên</h2>
  <ul>
    <li>Họ tên: <b><?php echo $hoten; ?></b></li>
    <li>Giới tính: <b><?php echo $gioitinh; ?></b></li>
    <li>Điểm lý thuyết: <b><?= $diemlt; ?></b></li>
    <li>Điểm thực hành: <b><?= $diemth; ?></b></li>
  </ul>
</body>
</html>