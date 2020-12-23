<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>XSS Stored và cách phòng chống - Nền tảng .vn</title>
</head>

<body>

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

  // 2. Câu lệnh SELECT
  $sqlSelect = <<<EOT
    SELECT *
    FROM hinhthucthanhtoan
EOT;

  // 3. Thực thi SELECT
  $result = mysqli_query($conn, $sqlSelect);

  // 4. Khi thực thi các truy vấn dạng SELECT, dữ liệu lấy về cần phải phân tích để sử dụng
  // Thông thường, chúng ta sẽ sử dụng vòng lặp while để duyệt danh sách các dòng dữ liệu được SELECT
  // Ta sẽ tạo 1 mảng array để chứa các dữ liệu được trả về
  $ds_hinhthucthanhtoan = [];
  while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
    $ds_hinhthucthanhtoan[] = array(
      'httt_ma' => $row['httt_ma'],
      'httt_ten' => $row['httt_ten'],
    );
  }
  ?>

  <a href="create.php">Thêm mới HTTT</a>
  <table border="1" width="800px">
    <tr>
      <th>Mã HTTT</th>
      <th>Tên HTTT</th>
    </tr>
    <?php foreach($ds_hinhthucthanhtoan as $httt): ?>
    <tr>
      <td><?= $httt['httt_ma'] ?></td>
      <td><?= $httt['httt_ten'] ?></td>
    </tr>
    <?php endforeach; ?>
  </table>
</body>

</html>