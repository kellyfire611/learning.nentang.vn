<?php
// Include file cấu hình ban đầu của `Twig`
require_once __DIR__ . '/../../bootstrap.php';

// Truy vấn database
// 1. Include file cấu hình kết nối đến database, khởi tạo kết nối $conn
include_once(__DIR__ . '/../../dbconnect.php');

// 2. Nếu người dùng có bấm nút Đăng ký thì thực thi câu lệnh UPDATE
if (isset($_POST['btnCapNhat'])) {
    // Lấy dữ liệu người dùng hiệu chỉnh gởi từ REQUEST POST
    $tenLoai = $_POST['lsp_ten'];
    $mota = $_POST['lsp_mota'];

    // Kiểm tra ràng buộc dữ liệu (Validation)
    // Tạo biến lỗi để chứa thông báo lỗi
    $errors = [];
    // required
    if (empty($tenLoai)) {
        $errors['lsp_ten'][] = [
            'rule' => 'required',
            'rule_value' => true,
            'value' => $tenLoai,
            'msg' => 'Vui lòng nhập tên Loại sản phẩm'
        ];
    }
    // minlength 3
    if (!empty($tenLoai) && strlen($tenLoai) < 3) {
        $errors['lsp_ten'][] = [
            'rule' => 'minlength',
            'rule_value' => 3,
            'value' => $tenLoai,
            'msg' => 'Tên Loại sản phẩm phải có ít nhất 3 ký tự'
        ];
    }
    // maxlength 50
    if (!empty($tenLoai) && strlen($tenLoai) > 50) {
        $errors['lsp_ten'][] = [
            'rule' => 'maxlength',
            'rule_value' => 50,
            'value' => $tenLoai,
            'msg' => 'Tên Loại sản phẩm không được vượt quá 50 ký tự'
        ];
    }

    // dd($errors);
    if (!empty($errors)) {
        // Yêu cầu `Twig` vẽ giao diện được viết trong file `backend/loaisanpham/create.html.twig`
        // kèm theo dữ liệu thông báo lỗi
        echo $twig->render('backend/loaisanpham/create.html.twig', [
            'errors' => $errors,
            'lsp_ten_oldvalue' => $tenLoai,
            'lsp_mota_oldvalue' => $mota
        ]);
    } else { // Nếu không có lỗi dữ liệu sẽ thực thi câu lệnh SQL
        // Câu lệnh INSERT
        $sql = "INSERT INTO `loaisanpham` (lsp_ten, lsp_mota) VALUES ('" . $tenLoai . "', '" . $mota . "');";

        // Thực thi INSERT
        //mysqli_query($conn, $sql);

        // Đóng kết nối
        mysqli_close($conn);

        // Sau khi cập nhật dữ liệu, tự động điều hướng về trang Danh sách
        header('location:index.php');
    }
} else {
    // Yêu cầu `Twig` vẽ giao diện được viết trong file `backend/loaisanpham/create.html.twig`
    echo $twig->render('backend/loaisanpham/create.html.twig');
}
