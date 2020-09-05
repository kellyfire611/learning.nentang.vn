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
        include_once(__DIR__ . '/../../dbconnect.php');

        // 2. Nếu người dùng có bấm nút Đăng ký thì thực thi câu lệnh UPDATE
        if (isset($_POST['btnSave'])) {
          // Lấy dữ liệu người dùng hiệu chỉnh gởi từ REQUEST POST
          $tenLoai = $_POST['category_name'];
          $mota = $_POST['description'];

          // Kiểm tra ràng buộc dữ liệu (Validation)
          // Tạo biến lỗi để chứa thông báo lỗi
          $errors = [];
          // required
          if (empty($tenLoai)) {
            $errors['category_name'][] = [
              'rule' => 'required',
              'rule_value' => true,
              'value' => $tenLoai,
              'msg' => 'Vui lòng nhập tên Loại sản phẩm'
            ];
          }
          // minlength 3
          if (!empty($tenLoai) && strlen($tenLoai) < 3) {
            $errors['category_name'][] = [
              'rule' => 'minlength',
              'rule_value' => 3,
              'value' => $tenLoai,
              'msg' => 'Tên Loại sản phẩm phải có ít nhất 3 ký tự'
            ];
          }
          // maxlength 50
          if (!empty($tenLoai) && strlen($tenLoai) > 50) {
            $errors['category_name'][] = [
              'rule' => 'maxlength',
              'rule_value' => 50,
              'value' => $tenLoai,
              'msg' => 'Tên Loại sản phẩm không được vượt quá 50 ký tự'
            ];
          }

          // required
          if (empty($mota)) {
            $errors['description'][] = [
              'rule' => 'required',
              'rule_value' => true,
              'value' => $mota,
              'msg' => 'Vui lòng nhập mô tả Loại sản phẩm'
            ];
          }
          // minlength 3
          if (!empty($mota) && strlen($mota) < 3) {
            $errors['description'][] = [
              'rule' => 'minlength',
              'rule_value' => 3,
              'value' => $mota,
              'msg' => 'Mô tả loại sản phẩm phải có ít nhất 3 ký tự'
            ];
          }
          // maxlength 255
          if (!empty($mota) && strlen($mota) > 255) {
            $errors['description'][] = [
              'rule' => 'maxlength',
              'rule_value' => 255,
              'value' => $mota,
              'msg' => 'Mô tả loại sản phẩm không được vượt quá 255 ký tự'
            ];
          }
        }
        ?>

        <?php 
        // if (!empty($errors)) {
        //   // Yêu cầu `Twig` vẽ giao diện được viết trong file `backend/loaisanpham/create.html.twig`
        //   // kèm theo dữ liệu thông báo lỗi
        //   echo $twig->render('backend/loaisanpham/create.html.twig', [
        //     'errors' => $errors,
        //     'category_name_oldvalue' => $tenLoai,
        //     'description_oldvalue' => $mota
        //   ]);
        // } else { // Nếu không có lỗi dữ liệu sẽ thực thi câu lệnh SQL
        //   // Câu lệnh INSERT
        //   $sql = "INSERT INTO `loaisanpham` (category_name, description) VALUES ('" . $tenLoai . "', '" . $mota . "');";

        //   // Thực thi INSERT
        //   mysqli_query($conn, $sql);

        //   // Đóng kết nối
        //   mysqli_close($conn);

        //   // Sau khi cập nhật dữ liệu, tự động điều hướng về trang Danh sách
        //   header('location:index.php');
        // }
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