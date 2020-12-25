<!-- Nhúng file cấu hình để xác định được Tên và Tiêu đề của trang hiện tại người dùng đang truy cập -->
<?php include_once(__DIR__ . '/../../layouts/config.php'); ?>

<!DOCTYPE html>
<html>

<head>
  <!-- Nhúng file quản lý phần HEAD -->
  <?php include_once(__DIR__ . '/../../layouts/head.php'); ?>
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
          <h1 class="h2">Hiệu chỉnh Loại sản phẩm</h1>
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

        /* --- 
          --- 2. Truy vấn dữ liệu Loại Sản phẩm theo khóa chính
          --- 
        */
        // Chuẩn bị câu truy vấn $sqlSelect, lấy dữ liệu ban đầu của record cần update
        // Lấy giá trị khóa chính được truyền theo dạng QueryString Parameter key1=value1&key2=value2...
        $lsp_ma = $_GET['lsp_ma'];
        $sqlSelect = "SELECT * FROM `loaisanpham` WHERE lsp_ma=$lsp_ma;";

        // Thực thi câu truy vấn SQL để lấy về dữ liệu ban đầu của record cần update
        $resultSelect = mysqli_query($conn, $sqlSelect);
        $loaisanphamRow = mysqli_fetch_array($resultSelect, MYSQLI_ASSOC); // 1 record
        /* --- End Truy vấn dữ liệu Sản phẩm theo khóa chính --- */
        ?>

        <!-- Block content -->
        <form name="frmLoaiSanPham" id="frmLoaiSanPham" method="post" action="">
          <div class="form-group">
            <label for="lsp_ma">Mã loại sản phẩm</label>
            <input type="text" class="form-control" id="lsp_ma" name="lsp_ma" placeholder="Mã loại sản phẩm" value="<?= $loaisanphamRow['lsp_ma'] ?>" readonly>
            <small id="idHelp" class="form-text text-muted">Mã loại sản phẩm không được hiệu chỉnh.</small>
          </div>
          <div class="form-group">
            <label for="lsp_ten">Tên loại sản phẩm</label>
            <input type="text" class="form-control" id="lsp_ten" name="lsp_ten" placeholder="Tên loại sản phẩm" value="<?= $loaisanphamRow['lsp_ten'] ?>">
          </div>
          <div class="form-group">
            <label for="lsp_mota">Mô tả loại sản phẩm</label>
            <textarea class="form-control" id="lsp_mota" name="lsp_mota" placeholder="Mô tả loại sản phẩm"><?= $loaisanphamRow['lsp_mota'] ?></textarea>
          </div>
          <button class="btn btn-primary" name="btnSave">Lưu dữ liệu</button>
        </form>

        <?php
        // 2. Nếu người dùng có bấm nút "Lưu dữ liệu"
        if (isset($_POST['btnSave'])) {
          // Lấy dữ liệu người dùng hiệu chỉnh gởi từ REQUEST POST
          $lsp_ten = $_POST['lsp_ten'];
          $lsp_mota = $_POST['lsp_mota'];

          // Kiểm tra ràng buộc dữ liệu (Validation)
          // Tạo biến lỗi để chứa thông báo lỗi
          $errors = [];

          // Validate Tên loại Sản phẩm
          // required
          if (empty($lsp_ten)) {
            $errors['lsp_ten'][] = [
              'rule' => 'required',
              'rule_value' => true,
              'value' => $lsp_ten,
              'msg' => 'Vui lòng nhập tên Loại sản phẩm'
            ];
          }
          // minlength 3
          if (!empty($lsp_ten) && strlen($lsp_ten) < 3) {
            $errors['lsp_ten'][] = [
              'rule' => 'minlength',
              'rule_value' => 3,
              'value' => $lsp_ten,
              'msg' => 'Tên Loại sản phẩm phải có ít nhất 3 ký tự'
            ];
          }
          // maxlength 50
          if (!empty($lsp_ten) && strlen($lsp_ten) > 50) {
            $errors['lsp_ten'][] = [
              'rule' => 'maxlength',
              'rule_value' => 50,
              'value' => $lsp_ten,
              'msg' => 'Tên Loại sản phẩm không được vượt quá 50 ký tự'
            ];
          }

          // Validate Diễn giải
          // required
          if (empty($lsp_mota)) {
            $errors['lsp_mota'][] = [
              'rule' => 'required',
              'rule_value' => true,
              'value' => $lsp_mota,
              'msg' => 'Vui lòng nhập mô tả Loại sản phẩm'
            ];
          }
          // minlength 3
          if (!empty($lsp_mota) && strlen($lsp_mota) < 3) {
            $errors['lsp_mota'][] = [
              'rule' => 'minlength',
              'rule_value' => 3,
              'value' => $lsp_mota,
              'msg' => 'Mô tả loại sản phẩm phải có ít nhất 3 ký tự'
            ];
          }
          // maxlength 255
          if (!empty($lsp_mota) && strlen($lsp_mota) > 255) {
            $errors['lsp_mota'][] = [
              'rule' => 'maxlength',
              'rule_value' => 255,
              'value' => $lsp_mota,
              'msg' => 'Mô tả loại sản phẩm không được vượt quá 255 ký tự'
            ];
          }
        }
        ?>

        <!-- Nếu có lỗi VALIDATE dữ liệu thì hiển thị ra màn hình 
        - Sử dụng thành phần (component) Alert của Bootstrap
        - Mỗi một lỗi hiển thị sẽ in theo cấu trúc Danh sách không thứ tự UL > LI
        -->
        <?php if (
          isset($_POST['btnSave'])  // Nếu người dùng có bấm nút "Lưu dữ liệu"
          && isset($errors)         // Nếu biến $errors có tồn tại
          && (!empty($errors))      // Nếu giá trị của biến $errors không rỗng
        ) : ?>
          <div id="errors-container" class="alert alert-danger face my-2" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
            <ul>
              <?php foreach ($errors as $fields) : ?>
                <?php foreach ($fields as $field) : ?>
                  <li><?php echo $field['msg']; ?></li>
                <?php endforeach; ?>
              <?php endforeach; ?>
            </ul>
          </div>
        <?php endif; ?>

        <?php
        // Nếu không có lỗi VALIDATE dữ liệu (tức là dữ liệu đã hợp lệ)
        // Tiến hành thực thi câu lệnh SQL Query Database
        // => giá trị của biến $errors là rỗng
        if (
          isset($_POST['btnSave'])  // Nếu người dùng có bấm nút "Lưu dữ liệu"
          && (!isset($errors) || (empty($errors))) // Nếu biến $errors không tồn tại Hoặc giá trị của biến $errors rỗng
        ) {
          // VALIDATE dữ liệu đã hợp lệ
          // Thực thi câu lệnh SQL QUERY
          // Câu lệnh UPDATE
          $sql = "UPDATE `loaisanpham` SET lsp_ten = '$lsp_ten', lsp_mota = '$lsp_mota' WHERE lsp_ma = $lsp_ma;";

          // Thực thi INSERT
          mysqli_query($conn, $sql) or die("<b>Có lỗi khi thực thi câu lệnh SQL: </b>" . mysqli_error($conn) . "<br /><b>Câu lệnh vừa thực thi:</b></br>$sql");

          // Đóng kết nối
          mysqli_close($conn);

          // Sau khi cập nhật dữ liệu, tự động điều hướng về trang Danh sách
          // Điều hướng bằng Javascript
          echo '<script>location.href = "index.php";</script>';
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
  <!-- <script src="..."></script> -->
  <script>
    $(document).ready(function() {
      $("#frmLoaiSanPham").validate({
        rules: {
          lsp_ten: {
            required: true,
            minlength: 3,
            maxlength: 50
          },
          lsp_mota: {
            required: true,
            minlength: 3,
            maxlength: 255
          }
        },
        messages: {
          lsp_ten: {
            required: "Vui lòng nhập tên Loại sản phẩm",
            minlength: "Tên Loại sản phẩm phải có ít nhất 3 ký tự",
            maxlength: "Tên Loại sản phẩm không được vượt quá 50 ký tự"
          },
          lsp_mota: {
            required: "Vui lòng nhập mô tả cho Loại sản phẩm",
            minlength: "Mô tả cho Loại sản phẩm phải có ít nhất 3 ký tự",
            maxlength: "Mô tả cho Loại sản phẩm không được vượt quá 255 ký tự"
          },
        },
        errorElement: "em",
        errorPlacement: function(error, element) {
          // Thêm class `invalid-feedback` cho field đang có lỗi
          error.addClass("invalid-feedback");
          if (element.prop("type") === "checkbox") {
            error.insertAfter(element.parent("label"));
          } else {
            error.insertAfter(element);
          }
        },
        success: function(label, element) {},
        highlight: function(element, errorClass, validClass) {
          $(element).addClass("is-invalid").removeClass("is-valid");
        },
        unhighlight: function(element, errorClass, validClass) {
          $(element).addClass("is-valid").removeClass("is-invalid");
        }
      });
    });
  </script>
</body>

</html>