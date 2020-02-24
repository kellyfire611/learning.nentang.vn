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

    // Yêu cầu `Twig` vẽ giao diện được viết trong file `backend/shop_suppliers/edit.html.twig`
    // với dữ liệu truyền vào file giao diện được đặt tên là `shop_suppliers`
    echo $twig->render('backend/shop_suppliers/edit.html.twig', ['shop_suppliers' => $shop_suppliersRow]);
    return;
}

// 4. Nếu người dùng có bấm nút Đăng ký thì thực thi câu lệnh UPDATE
// Lấy dữ liệu người dùng hiệu chỉnh gởi từ REQUEST POST
$supplier_code = $_POST['supplier_code'];
$supplier_name = $_POST['supplier_name'];
$description = $_POST['description'];
$image = $_POST['image'];
$created_at = date('Y-m-d H:i:s'); // Lấy ngày giờ hiện tại theo định dạng `Năm-Tháng-Ngày Giờ-Phút-Giây`. Vd: 2020-02-18 09:12:12
$updated_at = NULL;

// 5. Kiểm tra ràng buộc dữ liệu (Validation)
// Tạo biến lỗi để chứa thông báo lỗi
$errors = [];

// --- Kiểm tra Mã nhà cung cấp (validate)
// required (bắt buộc nhập <=> không được rỗng)
if (empty($supplier_code)) {
    $errors['supplier_code'][] = [
        'rule' => 'required',
        'rule_value' => true,
        'value' => $supplier_code,
        'msg' => 'Vui lòng nhập mã Nhà cung cấp'
    ];
}
// minlength 3 (tối thiểu 3 ký tự)
if (!empty($supplier_code) && strlen($supplier_code) < 3) {
    $errors['supplier_code'][] = [
        'rule' => 'minlength',
        'rule_value' => 3,
        'value' => $supplier_code,
        'msg' => 'Mã Nhà cung cấp phải có ít nhất 3 ký tự'
    ];
}
// maxlength 50 (tối đa 50 ký tự)
if (!empty($supplier_code) && strlen($supplier_code) > 50) {
    $errors['supplier_code'][] = [
        'rule' => 'maxlength',
        'rule_value' => 50,
        'value' => $supplier_code,
        'msg' => 'Mã Nhà cung cấp không được vượt quá 50 ký tự'
    ];
}

// --- Kiểm tra Tên nhà cung cấp (validate)
// required (bắt buộc nhập <=> không được rỗng)
if (empty($description)) {
    $errors['description'][] = [
        'rule' => 'required',
        'rule_value' => true,
        'value' => $description,
        'msg' => 'Vui lòng nhập mô tả Loại sản phẩm'
    ];
}
// minlength 3 (tối thiểu 3 ký tự)
if (!empty($description) && strlen($description) < 3) {
    $errors['description'][] = [
        'rule' => 'minlength',
        'rule_value' => 3,
        'value' => $description,
        'msg' => 'Mô tả loại sản phẩm phải có ít nhất 3 ký tự'
    ];
}
// maxlength 255 (tối đa 255 ký tự)
if (!empty($description) && strlen($description) > 255) {
    $errors['description'][] = [
        'rule' => 'maxlength',
        'rule_value' => 255,
        'value' => $description,
        'msg' => 'Mô tả loại sản phẩm không được vượt quá 255 ký tự'
    ];
}

// Câu lệnh UPDATE
$sqlUpdate = <<<EOT
    UPDATE shop_suppliers
	SET
		supplier_code='$supplier_code',
		supplier_name='$supplier_name',
		description='$description',
		image='$image',
		updated_at='$updated_at'
	WHERE id=$id
EOT;

// Thực thi UPDATE
mysqli_query($conn, $sqlUpdate);

// Đóng kết nối
mysqli_close($conn);

// Sau khi cập nhật dữ liệu, tự động điều hướng về trang Danh sách
header('location:index.php');
