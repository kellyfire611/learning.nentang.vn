<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop bán hàng NetaShop</title>

    <!-- Liên kết CSS Bootstrap bằng CDN -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
</head>

<body>

    <!-- Main content -->
    <div class="container">
        <h1>Form Thêm mới Nhà cung cấp</h1>

        <form name="frmCreate" method="post" action="" class="form">
            <table class="table">
                <tr>
                    <td>Mã nhà cung cấp</td>
                    <td><input type="text" name="supplier_code" id="supplier_code" class="form-control" /></td>
                </tr>
                <tr>
                    <td>Tên nhà cung cấp</td>
                    <td><input type="text" name="supplier_name" id="supplier_name" class="form-control" /></td>
                </tr>
                <tr>
                    <td>Ghi chú</td>
                    <td><textarea name="description" id="description" class="form-control"></textarea></td>
                </tr>
                <tr>
                    <td>Ảnh đại diện</td>
                    <td><input type="text" name="image" id="image" class="form-control" /></td>
                </tr>
                <tr>
                    <td colspan="2">
                        <button name="btnSave" class="btn btn-primary"><i class="fas fa-save"></i> Lưu dữ liệu</button>
                    </td>
                </tr>
            </table>
        </form>
    </div>

    <?php
    // Truy vấn database
    // 1. Include file cấu hình kết nối đến database, khởi tạo kết nối $conn
    include_once(__DIR__ . '/../dbconnect.php');

    // 2. Người dùng mới truy cập trang lần đầu tiên (người dùng chưa gởi dữ liệu `btnSave` - chưa nhấn nút Save) về Server
    // có nghĩa là biến $_POST['btnSave'] chưa được khởi tạo hoặc chưa có giá trị
    // => hiển thị Form nhập liệu

    // Nếu biến $_POST['btnSave'] đã được khởi tạo
    // => Người dùng đã bấm nút "Lưu dữ liệu"
    if ( isset($_POST['btnSave']) ) {
        
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
            // In ra thông báo lỗi
            // kèm theo dữ liệu thông báo lỗi
            foreach($errors as $errorField) {
                foreach($errorField as $error) {
                    echo $error['msg'] . '<br />';
                }
            }
            return;
        }

        // 6. Nếu không có lỗi dữ liệu sẽ thực thi câu lệnh SQL
        // Câu lệnh INSERT
        $sqlInsert = <<<EOT
        INSERT INTO shop_suppliers (supplier_code, supplier_name, description, image, created_at, updated_at) 
        VALUES ('$supplier_code', '$supplier_name', '$description', '$image', '$created_at', '$updated_at')
EOT;

        // Code dùng cho DEBUG
        // var_dump($sqlInsert); die;

        // Thực thi INSERT
        mysqli_query($conn, $sqlInsert);

        // Đóng kết nối
        mysqli_close($conn);

        // Sau khi cập nhật dữ liệu, tự động điều hướng về trang Danh sách
        header('location:index.php');   
    }
    ?>

    <!-- Liên kết JS Jquery bằng CDN -->
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>

    <!-- Liên kết JS Popper bằng CDN -->
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>

    <!-- Liên kết JS Bootstrap bằng CDN -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>

    <!-- Liên kết JS FontAwesome bằng CDN -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/js/all.min.js"></script>
</body>

</html>