<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>NenTang.vn</title>

  <!-- Nhúng file Quản lý các Liên kết CSS dùng chung cho toàn bộ trang web -->
  <?php include_once(__DIR__ . '/../../layouts/styles.php'); ?>
</head>

<body class="d-flex flex-column h-100">
  <!-- header -->
  <?php include_once(__DIR__ . '/../../layouts/partials/header.php'); ?>
  <!-- end header -->

  <div class="container-fluid">
    <div class="row">
      <!-- sidebar -->
      <?php include_once(__DIR__ . '/../../layouts/partials/sidebar.php'); ?>
      <!-- end sidebar -->

      <main role="main" class="col-md-10 ml-sm-auto px-4 mb-2">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
          <h1 class="h2">Thêm mới</h1>
        </div>

        <!-- Block content -->
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

        /*  --- 
        --- 2.Truy vấn dữ liệu sản phẩm 
        --- 
        */
        // Chuẩn bị câu truy vấn sản phẩm
        $sqlSanPham = "select * from `sanpham`";

        // Thực thi câu truy vấn SQL để lấy về dữ liệu
        $resultSanPham = mysqli_query($conn, $sqlSanPham);

        // Khi thực thi các truy vấn dạng SELECT, dữ liệu lấy về cần phải phân tích để sử dụng
        // Thông thường, chúng ta sẽ sử dụng vòng lặp while để duyệt danh sách các dòng dữ liệu được SELECT
        // Ta sẽ tạo 1 mảng array để chứa các dữ liệu được trả về
        $dataSanPham = [];
        while ($rowSanPham = mysqli_fetch_array($resultSanPham, MYSQLI_ASSOC)) {
          // Sử dụng hàm sprintf() để chuẩn bị mẫu câu với các giá trị truyền vào tương ứng từng vị trí placeholder
          $sp_tomtat = sprintf(
            "Sản phẩm %s, giá: %s",
            $rowSanPham['sp_ten'],
            number_format($rowSanPham['sp_gia'], 2, ".", ",") . ' vnđ'
          );

          $dataSanPham[] = array(
            'sp_ma' => $rowSanPham['sp_ma'],
            'sp_tomtat' => $sp_tomtat,
          );
        }
        // var_dump($dataSanPham);die;
        /* --- End Truy vấn dữ liệu sản phẩm --- */
        ?>

        <!-- Form cho phép người dùng upload file lên Server bắt buộc phải có thuộc tính enctype="multipart/form-data" -->
        <form name="frmhinhsanpham" id="frmhinhanpham" method="post" action="" enctype="multipart/form-data">
          <div class="form-group">
            <label for="sp_ma">Sản phẩm</label>
            <select class="form-control" id="sp_ma" name="sp_ma">
              <?php foreach ($dataSanPham as $sanpham) : ?>
                <option value="<?= $sanpham['sp_ma'] ?>"><?= $sanpham['sp_tomtat'] ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="form-group">
            <label for="hsp_tentaptin">Tập tin ảnh</label>

            <!-- Tạo khung div hiển thị ảnh cho người dùng Xem trước khi upload file lên Server -->
            <div class="preview-img-container">
              <img src="/php/myhand/assets/shared/img/default-image_600.png" id="preview-img" width="200px" />
            </div>

            <!-- Input cho phép người dùng chọn FILE -->
            <input type="file" class="form-control" id="hsp_tentaptin" name="hsp_tentaptin">
          </div>
          <button class="btn btn-primary" name="btnSave">Lưu</button>
          <a href="index.php" class="btn btn-outline-secondary" name="btnBack" id="btnBack">Quay về</a>
        </form>

        <?php
        // 3. Nếu người dùng có bấm nút Đăng ký thì thực thi câu lệnh UPDATE
        if (isset($_POST['btnSave'])) {
          // Lấy dữ liệu người dùng hiệu chỉnh gởi từ REQUEST POST
          $sp_ma = $_POST['sp_ma'];

          // Nếu người dùng có chọn file để upload
          if (isset($_FILES['hsp_tentaptin'])) {
            // Đường dẫn để chứa thư mục upload trên ứng dụng web của chúng ta. Các bạn có thể tùy chỉnh theo ý các bạn.
            // Ví dụ: các file upload sẽ được lưu vào thư mục ../../../assets/uploads
            $upload_dir = __DIR__ . "/../../../assets/uploads/";
            // Các hình ảnh sẽ được lưu trong thư mục con `products` để tiện quản lý
            $subdir = 'products/';

            // Đối với mỗi file, sẽ có các thuộc tính như sau:
            // $_FILES['hsp_tentaptin']['name']     : Tên của file chúng ta upload
            // $_FILES['hsp_tentaptin']['type']     : Kiểu file mà chúng ta upload (hình ảnh, word, excel, pdf, txt, ...)
            // $_FILES['hsp_tentaptin']['tmp_name'] : Đường dẫn đến file tạm trên web server
            // $_FILES['hsp_tentaptin']['error']    : Trạng thái của file chúng ta upload, 0 => không có lỗi
            // $_FILES['hsp_tentaptin']['size']     : Kích thước của file chúng ta upload

            if($_FILES['hsp_tentaptin']['size'] > 555555) {
              // ...
            }

            if($_FILES['hsp_tentaptin']['type'] == '*.jpg' 
              || $_FILES['hsp_tentaptin']['type'] == '*.png'
              || $_FILES['hsp_tentaptin']['type'] == '*.gif') {
              //...
            }

            // // 3.1. Chuyển file từ thư mục tạm vào thư mục Uploads
            // // Nếu file upload bị lỗi, tức là thuộc tính error > 0
            // if ($_FILES['hsp_tentaptin']['error'] > 0) {
            //   echo 'File Upload Bị Lỗi';
            //   die;
            // } else {
            //   // Để tránh trường hợp có 2 người dùng cùng lúc upload tập tin trùng tên nhau
            //   // Ví dụ:
            //   // - Người 1: upload tập tin hình ảnh tên `hoahong.jpg`
            //   // - Người 2: cũng upload tập tin hình ảnh tên `hoahong.jpg`
            //   // => dẫn đến tên tin trong thư mục chỉ còn lại tập tin người dùng upload cuối cùng
            //   // Cách giải quyết đơn giản là chúng ta sẽ ghép thêm ngày giờ vào tên file
            //   $hsp_tentaptin = $_FILES['hsp_tentaptin']['name'];
            //   $tentaptin = date('YmdHis') . '_' . $hsp_tentaptin; //20200530154922_hoahong.jpg

            //   // Tiến hành di chuyển file từ thư mục tạm trên server vào thư mục chúng ta muốn chứa các file uploads
            //   // Ví dụ: move file từ C:\xampp\tmp\php6091.tmp -> C:/xampp/htdocs/learning.nentang.vn/php/twig/assets/uploads/hoahong.jpg
            //   // var_dump($_FILES['hsp_tentaptin']['tmp_name']);
            //   // var_dump($upload_dir . $subdir . $tentaptin);

            //   move_uploaded_file($_FILES['hsp_tentaptin']['tmp_name'], $upload_dir . $subdir . $tentaptin);
            // }

            // // 3.2. Lưu thông tin file upload vào database
            // // Câu lệnh INSERT
            // $sql = "INSERT INTO `hinhsanpham` (hsp_tentaptin, sp_ma) VALUES ('$tentaptin', $sp_ma);";
            // // print_r($sql); die;

            // // Thực thi INSERT
            // mysqli_query($conn, $sql);

            // // Đóng kết nối
            // mysqli_close($conn);

            // // Sau khi cập nhật dữ liệu, tự động điều hướng về trang Danh sách
            // echo '<script>location.href = "index.php";</script>';
          }
        }
        ?>
        <!-- End block content -->
      </main>
    </div>
  </div>

  <!-- footer -->
  <?php include_once(__DIR__ . '/../../layouts/partials/footer.php'); ?>
  <!-- end footer -->

  <!-- Nhúng file quản lý phần SCRIPT JAVASCRIPT -->
  <?php include_once(__DIR__ . '/../../layouts/scripts.php'); ?>

  <!-- Các file Javascript sử dụng riêng cho trang này, liên kết tại đây -->
  <script>
    // Hiển thị ảnh preview (xem trước) khi người dùng chọn Ảnh
    const reader = new FileReader();
    const fileInput = document.getElementById("hsp_tentaptin");
    const img = document.getElementById("preview-img");
    reader.onload = e => {
      img.src = e.target.result;
    }
    fileInput.addEventListener('change', e => {
      const f = e.target.files[0];
      reader.readAsDataURL(f);
    })
  </script>
</body>

</html>