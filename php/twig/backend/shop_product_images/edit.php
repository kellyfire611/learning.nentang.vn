<?php
// Include file cấu hình ban đầu của `Twig`
require_once __DIR__.'/../../bootstrap.php';

// Truy vấn database
// 1. Include file cấu hình kết nối đến database, khởi tạo kết nối $conn
include_once(__DIR__.'/../../dbconnect.php');

/* --- 
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
while($rowSanPham = mysqli_fetch_array($resultSanPham, MYSQLI_ASSOC))
{
    // Sử dụng hàm sprintf() để chuẩn bị mẫu câu với các giá trị truyền vào tương ứng từng vị trí placeholder
    $sp_tomtat = sprintf("Sản phẩm %s, giá: %d", 
        $rowSanPham['product_name'],
        number_format($rowSanPham['sp_gia'], 2, ".", ",") . ' vnđ');

    $dataSanPham[] = array(
        'sp_ma' => $rowSanPham['sp_ma'],
        'sp_tomtat' => $sp_tomtat
    );
}
/* --- End Truy vấn dữ liệu sản phẩm --- */

/* --- 
   --- 3. Truy vấn dữ liệu Sản phẩm theo khóa chính
   --- 
*/
// Chuẩn bị câu truy vấn $sqlSelect, lấy dữ liệu ban đầu của record cần update
// Lấy giá trị khóa chính được truyền theo dạng QueryString Parameter key1=value1&key2=value2...
$hsp_ma = $_GET['hsp_ma'];
$sqlSelect = "SELECT * FROM `hinhsanpham` WHERE hsp_ma=$hsp_ma;";

// Thực thi câu truy vấn SQL để lấy về dữ liệu ban đầu của record cần update
$resultSelect = mysqli_query($conn, $sqlSelect);
$hinhsanphamRow = mysqli_fetch_array($resultSelect, MYSQLI_ASSOC); // 1 record
/* --- End Truy vấn dữ liệu Sản phẩm theo khóa chính --- */

// 4. Nếu người dùng có bấm nút Đăng ký thì thực thi câu lệnh UPDATE
if(isset($_POST['btnSave'])) 
{
    // Lấy dữ liệu người dùng hiệu chỉnh gởi từ REQUEST POST
    $sp_ma = $_POST['sp_ma'];

    // // Nếu người dùng có chọn file để upload
    // if (isset($_FILES['hproduct_nametaptin']))
    // {
    //     // Đường dẫn để chứa thư mục upload trên ứng dụng web của chúng ta. Các bạn có thể tùy chỉnh theo ý các bạn.
    //     // Ví dụ: các file upload sẽ được lưu vào thư mục ../../assets/uploads
    //     $upload_dir = "./../../assets/uploads/";

    //     // Đối với mỗi file, sẽ có các thuộc tính như sau:
    //     // $_FILES['hproduct_nametaptin']['name']     : Tên của file chúng ta upload
    //     // $_FILES['hproduct_nametaptin']['type']     : Kiểu file mà chúng ta upload (hình ảnh, word, excel, pdf, txt, ...)
    //     // $_FILES['hproduct_nametaptin']['tmp_name'] : Đường dẫn đến file tạm trên web server
    //     // $_FILES['hproduct_nametaptin']['error']    : Trạng thái của file chúng ta upload, 0 => không có lỗi
    //     // $_FILES['hproduct_nametaptin']['size']     : Kích thước của file chúng ta upload

    //     // Nếu file upload bị lỗi, tức là thuộc tính error > 0
    //     if ($_FILES['hproduct_nametaptin']['error'] > 0)
    //     {
    //         echo 'File Upload Bị Lỗi';die;
    //     }
    //     else{
    //         // Tiến hành di chuyển file từ thư mục tạm trên server vào thư mục chúng ta muốn chứa các file uploads
    //         // Ví dụ: move file từ C:\xampp\tmp\php6091.tmp -> C:/xampp/htdocs/learning.nentang.vn/assets/uploads/hoahong.jpg
    //         $hproduct_nametaptin = $_FILES['hproduct_nametaptin']['name'];
    //         move_uploaded_file($_FILES['hproduct_nametaptin']['tmp_name'], $upload_dir.$hproduct_nametaptin);
            
    //         // Xóa file cũ để tránh rác trong thư mục UPLOADS
    //         $old_file = $upload_dir.$hinhsanphamRow['hproduct_nametaptin'];
    //         if(file_exists($old_file)) {
    //             unlink($old_file);
    //         }
    //     }

    //     // Câu lệnh UPDATE
    //     $sql = "UPDATE `hinhsanpham` SET hproduct_nametaptin='$hproduct_nametaptin', sp_ma=$sp_ma WHERE hsp_ma=$hsp_ma;";
        
    //     // Thực thi UPDATE
    //     mysqli_query($conn, $sql);
            
    //     // Đóng kết nối
    //     mysqli_close($conn);

    //     // Sau khi cập nhật dữ liệu, tự động điều hướng về trang Danh sách
    //     header('location:index.php');
    // }
}

// Yêu cầu `Twig` vẽ giao diện được viết trong file `backend/hinhsanpham/edit.html.twig`
echo $twig->render('backend/hinhsanpham/edit.html.twig', [
    'ds_sanpham' => $dataSanPham,
    'hinhsanpham' => $hinhsanphamRow,
]);