<?php
// Include file cấu hình ban đầu của `Twig`
require_once __DIR__ . '/../../bootstrap.php';

// Truy vấn database
// 1. Include file cấu hình kết nối đến database, khởi tạo kết nối $conn
include_once(__DIR__ . '/../../dbconnect.php');

// 2. Chuẩn bị câu truy vấn $sqlSelect, lấy dữ liệu ban đầu của record cần update
// Lấy giá trị khóa chính được truyền theo dạng QueryString Parameter key1=value1&key2=value2...
$id = $_GET['id'];
$sqlSelect = "SELECT * FROM `shop_suppliers` WHERE id=$id;";

// 3. Người dùng mới truy cập trang lần đầu tiên (người dùng chưa gởi dữ liệu `btnSave` - chưa nhấn nút Save) về Server
// có nghĩa là biến $_POST['btnSave'] chưa được khởi tạo hoặc chưa có giá trị
// => hiển thị Form nhập liệu
if (!isset($_POST['btnSave'])) {
    // Thực thi câu truy vấn SQL để lấy về dữ liệu ban đầu của record cần update
    $resultSelect = mysqli_query($conn, $sqlSelect);
    $updateRow = mysqli_fetch_array($resultSelect, MYSQLI_ASSOC); // 1 record

    // Nếu không tìm thấy dòng dữ liệu thì chuyển sang trang báo lỗi không tìm thấy (404 status code)
    if(empty($updateRow)) {
        // Yêu cầu `Twig` vẽ giao diện được viết trong file `backend/errors/404.html.twig`
        // với dữ liệu truyền vào file giao diện được đặt tên là `errors`
        $errors = [
            'msg' => 'Không tìm thấy dòng dữ liệu bạn muốn hiệu chỉnh. Vui lòng kiểm tra lại giá trị ID !!!',
            'previous_url' => 'index.php',
        ];
        echo $twig->render('backend/errors/404.html.twig', ['errors' => $errors]);
    }

    // Yêu cầu `Twig` vẽ giao diện được viết trong file `backend/loaisanpham/edit.html.twig`
    // với dữ liệu truyền vào file giao diện được đặt tên là `loaisanpham`
    echo $twig->render('backend/loaisanpham/edit.html.twig', ['loaisanpham' => $loaisanphamRow]);
    return;
}

// 4. Nếu người dùng có bấm nút Đăng ký thì thực thi câu lệnh UPDATE
// Lấy dữ liệu người dùng hiệu chỉnh gởi từ REQUEST POST
$tenLoai = $_POST['lsp_ten'];
$mota = $_POST['lsp_mota'];

// Câu lệnh UPDATE
$sql = "UPDATE `loaisanpham` SET lsp_ten='$tenLoai', lsp_mota='$mota' WHERE lsp_ma=$lsp_ma;";

// Thực thi UPDATE
mysqli_query($conn, $sql);

// Đóng kết nối
mysqli_close($conn);

// Sau khi cập nhật dữ liệu, tự động điều hướng về trang Danh sách
header('location:index.php');
