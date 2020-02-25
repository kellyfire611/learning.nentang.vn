<?php
// Include file cấu hình ban đầu của `Twig`
require_once __DIR__ . '/../../bootstrap.php';

// Truy vấn database
// 1. Include file cấu hình kết nối đến database, khởi tạo kết nối $conn
include_once(__DIR__ . '/../../dbconnect.php');

// Kiểm tra có đăng nhập hay chưa? (Xác thực Authentication)
// Nếu trong SESSION có giá trị của key 'email' <-> người dùng đã đăng nhập thành công
// Nếu chưa đăng nhập thì chuyển hướng về trang đăng nhập của Backend
if (!isset($_SESSION['backend']['email'])) {
    header('location:../auth/login.php');
    return;
}

// Kiểm tra có Quyền vào chức năng này không? (Xác thực Authorization)
$email = $_SESSION['backend']['email'];

// 2. Chuẩn bị câu truy vấn $sql
$sqlPermissions = <<<EOT
    -- Kiểm tra quyền
    -- 1. Tạo biến và gán giá trị
    SET @email := '$email' COLLATE utf8mb4_unicode_ci;

    -- Người dùng vời điều kiện @email có ID bao nhiêu?
    SET @user_id := 
        (SELECT id
        FROM `acl_users`
        WHERE email = @email
        LIMIT 1);
    -- SELECT @user_id;

    -- 1. Người dùng thuộc những vai trò nào?
    SET @list_role_ids = (
        SELECT GROUP_CONCAT(ar.id)
        FROM `acl_model_has_roles` amhr
        JOIN `acl_roles` ar ON amhr.role_id = ar.id
        WHERE amhr.model_type = 'App\\Models\\Auth\\User' -- Tài khoản Backend
            AND model_id = @user_id);
    SELECT @list_role_ids;

    -- 2. Vai trò đó có quyền gì?
    DROP TABLE IF EXISTS `tmp_list_permissions_via_roles`;
    CREATE TEMPORARY TABLE IF NOT EXISTS `tmp_list_permissions_via_roles` AS (
        SELECT ap.*
        FROM `acl_role_has_permissions` arhp
        JOIN `acl_permissions` ap ON arhp.permission_id = ap.id
        WHERE FIND_IN_SET(arhp.role_id, @list_role_ids) > 0
    );

    -- 3. Người dùng có những quyền gì được cấp riêng biệt không?
    SET @list_permission_ids = (
        SELECT GROUP_CONCAT(ap.id)
        FROM `acl_model_has_permissions` amhp
        JOIN `acl_permissions` ap ON amhp.permission_id = ap.id
        WHERE amhp.model_type = 'App\\Models\\Auth\\User' -- Tài khoản Backend
            AND model_id = @user_id);
    SELECT @list_permission_ids;

    DROP TABLE IF EXISTS `tmp_list_individual_permissions`;
    CREATE TEMPORARY TABLE IF NOT EXISTS `tmp_list_individual_permissions` AS (
        SELECT ap.*
        FROM `acl_permissions` ap
        WHERE FIND_IN_SET(ap.id, @list_permission_ids) > 0
    );

    -- 4. Tổng hợp danh sách các Quyền của người dùng
    SELECT * FROM `tmp_list_permissions_via_roles`
    UNION ALL
    SELECT * FROM `tmp_list_individual_permissions`;
EOT;

// 3. Thực thi câu truy vấn SQL để lấy về dữ liệu
$resultPermissions = mysqli_query($conn, $sqlPermissions);

// 4. Khi thực thi các truy vấn dạng SELECT, dữ liệu lấy về cần phải phân tách để sử dụng
// Thông thường, chúng ta sẽ sử dụng vòng lặp while để duyệt danh sách các dòng dữ liệu được SELECT
// Ta sẽ tạo 1 mảng array để chứa các dữ liệu được trả về
$dataPermissions = [];
while ($row = mysqli_fetch_array($resultPermissions, MYSQLI_ASSOC)) {
    $dataPermissions[] = array(
        'id' => $row['id'],
        'name' => $row['name'],
        'display_name' => $row['display_name'],
        'guard_name' => $row['guard_name'],
    );
}

$allow_permission_backend_view = false;
foreach($dataPermissions as $permission) {
    if($permission['name'] == )
}


// 2. Người dùng mới truy cập trang lần đầu tiên (người dùng chưa gởi dữ liệu `btnSave` - chưa nhấn nút Save) về Server
// có nghĩa là biến $_POST['btnSave'] chưa được khởi tạo hoặc chưa có giá trị
// => hiển thị Form nhập liệu
if (!isset($_POST['btnSave'])) {
    // Yêu cầu `Twig` vẽ giao diện được viết trong file `backend/shop_suppliers/create.html.twig`
    echo $twig->render('backend/shop_suppliers/create.html.twig');
    return;
}

// 3. Nếu người dùng có bấm nút `Lưu dữ liệu` thì thực thi câu lệnh INSERT
// Lấy dữ liệu người dùng hiệu chỉnh gởi từ REQUEST POST
$supplier_code = $_POST['supplier_code'];
$supplier_name = $_POST['supplier_name'];
$description = $_POST['description'];
$image = $_POST['image'];
$created_at = date('Y-m-d H:i:s'); // Lấy ngày giờ hiện tại theo định dạng `Năm-Tháng-Ngày Giờ-Phút-Giây`. Vd: 2020-02-18 09:12:12
$updated_at = NULL;

// 4. Kiểm tra ràng buộc dữ liệu (Validation)
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

// 5. Thông báo lỗi cụ thể người dùng mắc phải (nếu vi phạm bất kỳ quy luật kiểm tra ràng buộc)
// dd($errors);
if (!empty($errors)) {
    // Yêu cầu `Twig` vẽ giao diện được viết trong file `backend/shop_suppliers/create.html.twig`
    // kèm theo dữ liệu thông báo lỗi
    echo $twig->render('backend/shop_suppliers/create.html.twig', [
        'errors' => $errors,
        'supplier_code_oldvalue' => $supplier_code,
        'supplier_name_oldvalue' => $supplier_name,
        'description_oldvalue' => $description,
        'image_oldvalue' => $image,
    ]);
    return;
}

// 6. Nếu không có lỗi dữ liệu sẽ thực thi câu lệnh SQL
// Câu lệnh INSERT
$sqlInsert = <<<EOT
    INSERT INTO shop_suppliers (supplier_code, supplier_name, description, image, created_at, updated_at) 
    VALUES ('$supplier_code', '$supplier_name', '$description', '$image', '$created_at', '$updated_at')";
EOT;

// Code dùng cho DEBUG
//var_dump($sql); die;

// Thực thi INSERT
mysqli_query($conn, $sqlInsert);

// Đóng kết nối
mysqli_close($conn);

// Sau khi cập nhật dữ liệu, tự động điều hướng về trang Danh sách
header('location:index.php');
