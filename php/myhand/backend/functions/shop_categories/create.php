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
          <h1 class="h2">Thêm mới Loại sản phẩm</h1>
        </div>

        <!-- Block content -->
        <form name="frmLoaiSanPham" id="frmLoaiSanPham" method="post" action="">
          <div class="form-group">
            <label for="id">ID loại sản phẩm</label>
            <input type="text" class="form-control" id="id" name="id" placeholder="ID loại sản phẩm" readonly>
            <small id="idHelp" class="form-text text-muted">ID loại sản phẩm không được hiệu chỉnh.</small>
          </div>
          <div class="form-group">
            <label for="category_code">Mã loại sản phẩm</label>
            <input type="text" class="form-control" id="category_code" name="category_code" placeholder="Mã loại sản phẩm" value="">
          </div>
          <div class="form-group">
            <label for="category_name">Tên loại sản phẩm</label>
            <input type="text" class="form-control" id="category_name" name="category_name" placeholder="Tên loại sản phẩm" value="">
          </div>
          <div class="form-group">
            <label for="description">Mô tả loại sản phẩm</label>
            <textarea class="form-control" id="description" name="description" placeholder="Mô tả loại sản phẩm"></textarea>
          </div>
          <button class="btn btn-primary" name="btnSave">Lưu dữ liệu</button>
        </form>

        <?php
        // Truy vấn database
        // 1. Include file cấu hình kết nối đến database, khởi tạo kết nối $conn
        include_once(__DIR__ . '/../../../dbconnect.php');

        // 2. Nếu người dùng có bấm nút "Lưu dữ liệu" thì kiểm tra VALIDATE dữ liệu
        if (isset($_POST['btnSave'])) {
          // Lấy dữ liệu người dùng hiệu chỉnh gởi từ REQUEST POST
          $category_code = $_POST['category_code'];
          $category_name = $_POST['category_name'];
          $description = $_POST['description'];

          // Kiểm tra ràng buộc dữ liệu (Validation)
          // Tạo biến lỗi để chứa thông báo lỗi
          $errors = [];

          // Validate Mã loại Sản phẩm
          // required
          if (empty($category_code)) {
            $errors['category_code'][] = [
              'rule' => 'required',
              'rule_value' => true,
              'value' => $category_code,
              'msg' => 'Vui lòng nhập Mã Loại sản phẩm'
            ];
          }
          // minlength 3
          if (!empty($category_code) && strlen($category_code) < 3) {
            $errors['category_code'][] = [
              'rule' => 'minlength',
              'rule_value' => 3,
              'value' => $category_code,
              'msg' => 'Mã sản phẩm phải có ít nhất 3 ký tự'
            ];
          }
          // maxlength 50
          if (!empty($category_code) && strlen($category_code) > 50) {
            $errors['category_code'][] = [
              'rule' => 'maxlength',
              'rule_value' => 50,
              'value' => $category_code,
              'msg' => 'Mã Loại sản phẩm không được vượt quá 50 ký tự'
            ];
          }

          // Validate Tên loại Sản phẩm
          // required
          if (empty($category_name)) {
            $errors['category_name'][] = [
              'rule' => 'required',
              'rule_value' => true,
              'value' => $category_name,
              'msg' => 'Vui lòng nhập tên Loại sản phẩm'
            ];
          }
          // minlength 3
          if (!empty($category_name) && strlen($category_name) < 3) {
            $errors['category_name'][] = [
              'rule' => 'minlength',
              'rule_value' => 3,
              'value' => $category_name,
              'msg' => 'Tên Loại sản phẩm phải có ít nhất 3 ký tự'
            ];
          }
          // maxlength 50
          if (!empty($category_name) && strlen($category_name) > 50) {
            $errors['category_name'][] = [
              'rule' => 'maxlength',
              'rule_value' => 50,
              'value' => $category_name,
              'msg' => 'Tên Loại sản phẩm không được vượt quá 50 ký tự'
            ];
          }

          // Validate Diễn giải
          // required
          if (empty($description)) {
            $errors['description'][] = [
              'rule' => 'required',
              'rule_value' => true,
              'value' => $description,
              'msg' => 'Vui lòng nhập mô tả Loại sản phẩm'
            ];
          }
          // minlength 3
          if (!empty($description) && strlen($description) < 3) {
            $errors['description'][] = [
              'rule' => 'minlength',
              'rule_value' => 3,
              'value' => $description,
              'msg' => 'Mô tả loại sản phẩm phải có ít nhất 3 ký tự'
            ];
          }
          // maxlength 255
          if (!empty($description) && strlen($description) > 255) {
            $errors['description'][] = [
              'rule' => 'maxlength',
              'rule_value' => 255,
              'value' => $description,
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
          // Câu lệnh INSERT
          $sql = "INSERT INTO `shop_categories` (category_code, category_name, description) VALUES ('$category_code', '$category_name', '$description');";

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
    // VALIDATE dữ liệu trong form
    $(document).ready(function() {
      $("#frmLoaiSanPham").validate({
        rules: {
          category_code: {
            required: true,
            minlength: 3,
            maxlength: 50
          },
          category_name: {
            required: true,
            minlength: 3,
            maxlength: 50
          },
          description: {
            required: true,
            minlength: 3,
            maxlength: 255
          }
        },
        messages: {
          category_code: {
            required: "Vui lòng nhập Mã Loại sản phẩm",
            minlength: "Mã Loại sản phẩm phải có ít nhất 3 ký tự",
            maxlength: "Mã Loại sản phẩm không được vượt quá 50 ký tự"
          },
          category_name: {
            required: "Vui lòng nhập tên Loại sản phẩm",
            minlength: "Tên Loại sản phẩm phải có ít nhất 3 ký tự",
            maxlength: "Tên Loại sản phẩm không được vượt quá 50 ký tự"
          },
          description: {
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