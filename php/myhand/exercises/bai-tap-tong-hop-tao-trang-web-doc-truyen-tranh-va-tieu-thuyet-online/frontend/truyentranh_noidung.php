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

      /* --- 
        --- 2.Truy vấn dữ liệu Truyện
        --- Lấy giá trị khóa chính được truyền theo dạng QueryString Parameter key1=value1&key2=value2...
        --- 
        */
      // 2.1. Chuẩn bị câu truy vấn $sql
      $truyen_id = $_GET['truyen_id'];
      $chuong_id = $_GET['chuong_id'];

      $sqlDanhSachTruyen = <<<EOT
      SELECT truyen_id, truyen_ma, truyen_ten, truyen_hinhdaidien, truyen_loai, truyen_theloai, truyen_tacgia, truyen_mota_ngan, truyen_ngaydang
      FROM truyen
      WHERE truyen_id = $truyen_id
EOT;

      // 2.2. Thực thi câu truy vấn SQL để lấy về dữ liệu
      $resultTruyen = mysqli_query($conn, $sqlDanhSachTruyen);

      // 2.3. Khi thực thi các truy vấn dạng SELECT, dữ liệu lấy về cần phải phân tích để sử dụng
      // Thông thường, chúng ta sẽ sử dụng vòng lặp while để duyệt danh sách các dòng dữ liệu được SELECT
      // Ta sẽ tạo 1 mảng array để chứa các dữ liệu được trả về
      $dataTruyenRow = [];
      while ($row = mysqli_fetch_array($resultTruyen, MYSQLI_ASSOC)) {
        $dataTruyenRow = array(
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
      /* --- End Truy vấn dữ liệu Truyện --- */

      /* --- 
        --- 3.Truy vấn dữ liệu Chương/Tập cụ thể của Truyện
        --- Lấy giá trị khóa chính được truyền theo dạng QueryString Parameter key1=value1&key2=value2...
        --- 
        */
      // 3.1. Chuẩn bị câu truy vấn $sql
      $sqlChuong = <<<EOT
      SELECT chuong_id, chuong_so, chuong_ten, chuong_noidung, truyen_id
      FROM chuong
      WHERE truyen_id = $truyen_id
        AND chuong_id = $chuong_id
EOT;

      // 3.2. Thực thi câu truy vấn SQL để lấy về dữ liệu
      $resultChuong = mysqli_query($conn, $sqlChuong);

      // 4. Khi thực thi các truy vấn dạng SELECT, dữ liệu lấy về cần phải phân tích để sử dụng
      // Thông thường, chúng ta sẽ sử dụng vòng lặp while để duyệt danh sách các dòng dữ liệu được SELECT
      // Ta sẽ tạo 1 mảng array để chứa các dữ liệu được trả về
      $dataChuongRow = [];
      while ($row = mysqli_fetch_array($resultChuong, MYSQLI_ASSOC)) {
        $dataChuongRow = array(
          'chuong_id' => $row['chuong_id'],
          'chuong_so' => $row['chuong_so'],
          'chuong_ten' => $row['chuong_ten'],
          'chuong_noidung' => $row['chuong_noidung'],
          'truyen_id' => $row['truyen_id'],
        );
      }
      /* --- End Truy vấn dữ liệu Chương/Tập cụ thể của Truyện --- */

      /* --- 
        --- 4.Truy vấn dữ liệu các hình ảnh của Chương/Tập cụ thể
        --- Lấy giá trị khóa chính được truyền theo dạng QueryString Parameter key1=value1&key2=value2...
        --- 
        */
      // 4.1. Chuẩn bị câu truy vấn $sql
      $sqlChuongHinhAnh = <<<EOT
      SELECT chuong_hinhanh_id, chuong_id, chuong_hinhanh_tenhinh
	    FROM chuong_hinhanh
      WHERE chuong_id = $chuong_id
EOT;

      // 4.2. Thực thi câu truy vấn SQL để lấy về dữ liệu
      $resultChuongHinhAnh = mysqli_query($conn, $sqlChuongHinhAnh);

      // 4. Khi thực thi các truy vấn dạng SELECT, dữ liệu lấy về cần phải phân tích để sử dụng
      // Thông thường, chúng ta sẽ sử dụng vòng lặp while để duyệt danh sách các dòng dữ liệu được SELECT
      // Ta sẽ tạo 1 mảng array để chứa các dữ liệu được trả về
      $dataChuongHinhAnhRow = [];
      while ($row = mysqli_fetch_array($resultChuongHinhAnh, MYSQLI_ASSOC)) {
        $dataChuongHinhAnhRow[] = array(
          'chuong_hinhanh_id' => $row['chuong_hinhanh_id'],
          'chuong_id' => $row['chuong_id'],
          'chuong_hinhanh_tenhinh' => $row['chuong_hinhanh_tenhinh'],
        );
      }
      // print_r($dataChuongHinhAnhRow);die;
      
      /* --- End Truy vấn dữ liệu các hình ảnh của Chương/Tập cụ thể --- */
      ?>
    </div>

    <div class="container">
      <!-- THÔNG TIN CHƯƠNG/TẬP START -->
      <div class="row">
        <div class="col text-center">
          <h3><?= $dataTruyenRow['truyen_ten'] ?></h3>
          <h4>Chương <?= $dataChuongRow['chuong_so'] ?> - <?= $dataChuongRow['chuong_ten'] ?></h4>
          <?php
          // Tính toán Chương trước ID = chương hiện tại +/- 1
          $chuongTruocId = $dataChuongRow['chuong_so'] - 1;
          $chuongSauId = $dataChuongRow['chuong_so'] + 1;
          ?>
          <a href="tieuthuyet_noidung.php?truyen_id=<?= $truyen_id ?>&chuong_id=<?= $chuongTruocId ?>" class="btn btn-primary">Chương trước</a>
          <a href="tieuthuyet.php?truyen_id=<?= $truyen_id ?>" class="btn btn-outline-success">Quay về Danh sách</a>
          <a href="tieuthuyet_noidung.php?truyen_id=<?= $truyen_id ?>&chuong_id=<?= $chuongSauId ?>" class="btn btn-primary">Chương sau</a>
        </div>
      </div>
      <!-- THÔNG TIN CHƯƠNG/TẬP END -->

      <!-- THÔNG TIN NỘI DUNG CHƯƠNG/TẬP START -->
      <div class="row justify-content-center">
        <div class="col-md-12">
          <?= $dataChuongRow['chuong_noidung'] ?>

          <?php foreach($dataChuongHinhAnhRow as $hinhanh): ?>
            <img src="/php/myhand/exercises/bai-tap-tong-hop-tao-trang-web-doc-truyen-tranh-va-tieu-thuyet-online/assets/uploads/<?= $hinhanh['chuong_hinhanh_tenhinh'] ?>" class="card-img-top img-fluid" alt="">
          <?php endforeach; ?>
        </div>
      </div>
      <!-- THÔNG TIN NỘI DUNG CHƯƠNG/TẬP END -->
      





      
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