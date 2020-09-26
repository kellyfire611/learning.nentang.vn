<?php
// hàm `session_id()` sẽ trả về giá trị SESSION_ID (tên file session do Web Server tự động tạo)
// - Nếu trả về Rỗng hoặc NULL => chưa có file Session tồn tại
if (session_id() === '') {
  // Yêu cầu Web Server tạo file Session để lưu trữ giá trị tương ứng với CLIENT (Web Browser đang gởi Request)
  session_start();
}
?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>NenTang.vn</title>

  <!-- Nhúng file Quản lý các Liên kết CSS dùng chung cho toàn bộ trang web -->
  <?php include_once(__DIR__ . '/layouts/styles.php'); ?>

  <link href="/php/myhand/exercises/bai-tap-tong-hop-tao-trang-web-doc-truyen-tranh-va-tieu-thuyet-online/assets/frontend/css/style.css" type="text/css" rel="stylesheet" />
</head>

<body class="d-flex flex-column h-100">
  <!-- header -->
  <?php include_once(__DIR__ . '/layouts/partials/header.php'); ?>
  <!-- end header -->

  <main role="main" class="mb-2">
    <!-- Block content -->
    <div class="container mt-2">
      <h1 class="text-center">Nền tảng - Hành trang tới Tương lai</h1>
      <h1>Web tổng hợp Truyện tranh và Tiểu thuyết Online 24/7</h1>

      <?php
      // Hiển thị tất cả lỗi trong PHP
      // Chỉ nên hiển thị lỗi khi đang trong môi trường Phát triển (Development)
      // Không nên hiển thị lỗi trên môi trường Triển khai (Production)
      ini_set('display_errors', 1);
      ini_set('display_startup_errors', 1);
      error_reporting(E_ALL);

      // Truy vấn database để lấy danh sách
      // 1. Include file cấu hình kết nối đến database, khởi tạo kết nối $conn
      include_once(__DIR__ . '/../dbconnect.php');

      // 2. Chuẩn bị câu truy vấn $sql
      $sqlDanhSachTruyen = <<<EOT
      SELECT truyen_id, truyen_ma, truyen_ten, truyen_hinhdaidien, truyen_loai, truyen_theloai, truyen_tacgia, truyen_mota_ngan, truyen_ngaydang
      FROM truyen
EOT;

      // 3. Thực thi câu truy vấn SQL để lấy về dữ liệu
      $result = mysqli_query($conn, $sqlDanhSachTruyen);

      // 4. Khi thực thi các truy vấn dạng SELECT, dữ liệu lấy về cần phải phân tích để sử dụng
      // Thông thường, chúng ta sẽ sử dụng vòng lặp while để duyệt danh sách các dòng dữ liệu được SELECT
      // Ta sẽ tạo 1 mảng array để chứa các dữ liệu được trả về
      $dataDanhSachTruyen = [];
      while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        $dataDanhSachTruyen[] = array(
          'truyen_id' => $row['truyen_id'],
          'truyen_ma' => $row['truyen_ma'],
          'truyen_ten' => $row['truyen_ten'],
          'truyen_hinhdaidien' => $row['truyen_hinhdaidien'],
          'truyen_loai' => $row['truyen_loai'],
          'truyen_theloai' => $row['truyen_theloai'],
          'truyen_tacgia' => $row['truyen_tacgia'],
          'truyen_mota_ngan' => $row['truyen_mota_ngan'],
          'truyen_ngaydang' => $row['truyen_ngaydang'],
        );
      }
      // print_r($dataDanhSachTruyen);die;

      // 5. Phân thành 2 array tùy theo loại Truyện
      // Sử dụng hàm array_filter() để lọc các phần tử theo điều kiện mong muốn
      $dataDanhSachTieuThuyet = array_filter($dataDanhSachTruyen, function($value, $key) {
        return $value['truyen_loai'] == 1; // #1: Tiểu thuyết; #2: Truyện tranh
      }, ARRAY_FILTER_USE_BOTH);

      $dataDanhSachTruyenTranh = array_filter($dataDanhSachTruyen, function($value, $key) {
        return $value['truyen_loai'] == 2; // #1: Tiểu thuyết; #2: Truyện tranh
      }, ARRAY_FILTER_USE_BOTH);
      ?>
    </div>

    <div class="container">
      <!-- Danh sách TRUYỆN TRANH START -->
      <div class="row">
        <div class="col">
          <h3>Danh sách Truyện Tranh</h3>
        </div>
      </div>
      <div class="row row-cols-3">
        <?php foreach ($dataDanhSachTruyenTranh as $truyentranh) : ?>
          <div class="col">
            <div class="card">
              <img src="/php/myhand/exercises/bai-tap-tong-hop-tao-trang-web-doc-truyen-tranh-va-tieu-thuyet-online/assets/uploads/<?= $truyentranh['truyen_hinhdaidien'] ?>" class="card-img-top img-fluid" alt="">
              <div class="card-body">
                <h5 class="card-title"><?= $truyentranh['truyen_ten'] ?></h5>
                <p class="card-text">
                  <?= $truyentranh['truyen_mota_ngan'] ?>
                </p>
                <a href="truyentranh.php?truyen_id=<?= $truyentranh['truyen_id'] ?>" class="btn btn-primary">Đọc Truyện</a>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
      <!-- Danh sách TRUYỆN TRANH END -->

      <!-- Danh sách TIỂU THUYẾT START -->
      <div class="row">
        <div class="col">
          <h3>Danh sách Tiểu thuyết</h3>
        </div>
      </div>
      <div class="row row-cols-3">
        <?php foreach ($dataDanhSachTieuThuyet as $tieuthuyet) : ?>
          <div class="col">
            <div class="card">
              <img src="/php/myhand/exercises/bai-tap-tong-hop-tao-trang-web-doc-truyen-tranh-va-tieu-thuyet-online/assets/uploads/<?= $tieuthuyet['truyen_hinhdaidien'] ?>" class="card-img-top img-fluid" alt="">
              <div class="card-body">
                <h5 class="card-title"><?= $tieuthuyet['truyen_ten'] ?></h5>
                <p class="card-text">
                  <?= $tieuthuyet['truyen_mota_ngan'] ?>
                </p>
                <a href="tieuthuyet.php?truyen_id=<?= $tieuthuyet['truyen_id'] ?>" class="btn btn-primary">Đọc Truyện</a>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
      <!-- Danh sách TIỂU THUYẾT END -->
    </div>
    <!-- End block content -->
  </main>

  <!-- footer -->
  <?php include_once(__DIR__ . '/layouts/partials/footer.php'); ?>
  <!-- end footer -->

  <!-- Nhúng file quản lý phần SCRIPT JAVASCRIPT -->
  <?php include_once(__DIR__ . '/layouts/scripts.php'); ?>

  <!-- Các file Javascript sử dụng riêng cho trang này, liên kết tại đây -->

</body>

</html>