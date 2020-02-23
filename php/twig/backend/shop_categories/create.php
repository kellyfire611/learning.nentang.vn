<?php
// Include file cấu hình ban đầu của `Twig`
require_once __DIR__ . '/../../bootstrap.php';

// Truy vấn database
// 1. Include file cấu hình kết nối đến database, khởi tạo kết nối $conn
include_once(__DIR__ . '/../../dbconnect.php');

// 2. Nếu người dùng có bấm nút Đăng ký thì thực thi câu lệnh UPDATE
if (isset($_POST['btnSave'])) {
    // Lấy dữ liệu người dùng hiệu chỉnh gởi từ REQUEST POST
    $tenLoai = $_POST['lproduct_name'];
    $mota = $_POST['lsp_mota'];

    // Kiểm tra ràng buộc dữ liệu (Validation)
    // Tạo biến lỗi để chứa thông báo lỗi
    $errors = [];
    // required
    if (empty($tenLoai)) {
        $errors['lproduct_name'][] = [
            'rule' => 'required',
            'rule_value' => true,
            'value' => $tenLoai,
            'msg' => 'Vui lòng nhập tên Loại sản phẩm'
        ];
    }
    // minlength 3
    if (!empty($tenLoai) && strlen($tenLoai) < 3) {
        $errors['lproduct_name'][] = [
            'rule' => 'minlength',
            'rule_value' => 3,
            'value' => $tenLoai,
            'msg' => 'Tên Loại sản phẩm phải có ít nhất 3 ký tự'
        ];
    }
    // maxlength 50
    if (!empty($tenLoai) && strlen($tenLoai) > 50) {
        $errors['lproduct_name'][] = [
            'rule' => 'maxlength',
            'rule_value' => 50,
            'value' => $tenLoai,
            'msg' => 'Tên Loại sản phẩm không được vượt quá 50 ký tự'
        ];
    }

    // required
    if (empty($mota)) {
        $errors['lsp_mota'][] = [
            'rule' => 'required',
            'rule_value' => true,
            'value' => $mota,
            'msg' => 'Vui lòng nhập mô tả Loại sản phẩm'
        ];
    }
    // minlength 3
    if (!empty($mota) && strlen($mota) < 3) {
        $errors['lsp_mota'][] = [
            'rule' => 'minlength',
            'rule_value' => 3,
            'value' => $mota,
            'msg' => 'Mô tả loại sản phẩm phải có ít nhất 3 ký tự'
        ];
    }
    // maxlength 255
    if (!empty($mota) && strlen($mota) > 255) {
        $errors['lsp_mota'][] = [
            'rule' => 'maxlength',
            'rule_value' => 255,
            'value' => $mota,
            'msg' => 'Mô tả loại sản phẩm không được vượt quá 255 ký tự'
        ];
    }

    // dd($errors);
    if (!empty($errors)) {
        // Yêu cầu `Twig` vẽ giao diện được viết trong file `backend/loaisanpham/create.html.twig`
        // kèm theo dữ liệu thông báo lỗi
        echo $twig->render('backend/loaisanpham/create.html.twig', [
            'errors' => $errors,
            'lproduct_name_oldvalue' => $tenLoai,
            'lsp_mota_oldvalue' => $mota
        ]);
    } else { // Nếu không có lỗi dữ liệu sẽ thực thi câu lệnh SQL
        // Câu lệnh INSERT
        $sql = "INSERT INTO `loaisanpham` (lproduct_name, lsp_mota) VALUES ('" . $tenLoai . "', '" . $mota . "');";

        // Thực thi INSERT
        mysqli_query($conn, $sql);

        // Đóng kết nối
        mysqli_close($conn);

        // Sau khi cập nhật dữ liệu, tự động điều hướng về trang Danh sách
        header('location:index.php');
    }
} else {
    // Yêu cầu `Twig` vẽ giao diện được viết trong file `backend/loaisanpham/create.html.twig`
    echo $twig->render('backend/loaisanpham/create.html.twig');
}
