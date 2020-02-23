# Tạo chức năng CRUD (Thêm, Sửa, Xóa, Xem) danh mục có liên kết khóa ngoại
- Chúng ta sẽ cùng tạo chức năng CRUD (Create, Read, Update, Delete) một danh mục có liên kết khóa ngoại. Cụ thể là danh mục Sản phẩm

## Step 1: tạo cấu trúc thư mục cho chức năng CRUD `Sản phẩm`
- Để tiện cho việc quản lý, ta sẽ tạo cấu trúc thư mục tương ứng với tên của từng chức năng

[![../../assets/php/twig/CachToChucSourceCode_Logic_TuongUng_Template.png](../../assets/php/twig/CachToChucSourceCode_Logic_TuongUng_Template.png)](../../assets/php/twig/CachToChucSourceCode_Logic_TuongUng_Template.png)

- Tạo thư mục `/php/twig/backend/sanpham` quản lý code logic/nghiệp vụ PHP
```
+---php
|   \---twig                    <- Đây là thư mục gốc của dự án, các bạn có thể đặt tên các bạn...
|       \---backend
|           \---sanpham         <- Tạo thư mục
```

- Tạo thư mục `/php/twig/templates/backend/sanpham` quản lý template giao diện tương ứng
```
+---php
|   \---twig                    <- Đây là thư mục gốc của dự án, các bạn có thể đặt tên các bạn...
|       \---templates
|           \---backend
|               \---sanpham     <- Tạo thư mục
```

## Step 2: tạo chức năng `index` dùng để hiển thị màn hình danh sách các Loại sản phẩm có trong database
- Mô hình hoạt động của chức năng `index`:
    - Thực thi dữ liệu/tính toán/chuẩn bị dữ liệu (định dạng ngày, số, câu chữ, ...)
    - Truyền dữ liệu sang template để hiển thị

[![../../assets/php/twig/SanPhamIndex_Logic_To_Template_DataFlow.png](../../assets/php/twig/SanPhamIndex_Logic_To_Template_DataFlow.png)](../../assets/php/twig/SanPhamIndex_Logic_To_Template_DataFlow.png)

- Tạo file `/php/twig/backend/sanpham/index.php` để xử lý logic/nghiệp vụ
```
+---php
|   \---twig                    <- Đây là thư mục gốc của dự án, các bạn có thể đặt tên các bạn...
|       \---backend
|           \---sanpham     
|               \---index.php   <- Tạo file
```
- Nội dung file:
```php
<?php
// Include file cấu hình ban đầu của `Twig`
require_once __DIR__.'/../../bootstrap.php';
// Truy vấn database để lấy danh sách
// 1. Include file cấu hình kết nối đến database, khởi tạo kết nối $conn
include_once(__DIR__.'/../../dbconnect.php');
// 2. Chuẩn bị câu truy vấn $sql
// Sử dụng HEREDOC của PHP để tạo câu truy vấn SQL với dạng dễ đọc, thân thiện với việc bảo trì code
$sql = <<<EOT
    SELECT sp.*
        , lsp.lproduct_name
        , nsx.nsx_ten
        , km.km_ten, km.km_noidung, km.km_tungay, km.km_denngay
    FROM `sanpham` sp
    JOIN `loaisanpham` lsp ON sp.lsp_ma = lsp.lsp_ma
    JOIN `nhasanxuat` nsx ON sp.nsx_ma = nsx.nsx_ma
    LEFT JOIN `khuyenmai` km ON sp.km_ma = km.km_ma
    ORDER BY sp.sp_ma DESC
EOT;

// 3. Thực thi câu truy vấn SQL để lấy về dữ liệu
$result = mysqli_query($conn, $sql);
// 4. Khi thực thi các truy vấn dạng SELECT, dữ liệu lấy về cần phải phân tích để sử dụng
// Thông thường, chúng ta sẽ sử dụng vòng lặp while để duyệt danh sách các dòng dữ liệu được SELECT
// Ta sẽ tạo 1 mảng array để chứa các dữ liệu được trả về
$data = [];
while($row = mysqli_fetch_array($result, MYSQLI_ASSOC))
{
    $km_tomtat = '';
    if(!empty($row['km_ten'])) {
        // Sử dụng hàm sprintf() để chuẩn bị mẫu câu với các giá trị truyền vào tương ứng từng vị trí placeholder
        $km_tomtat = sprintf("Khuyến mãi %s, nội dung: %s, thời gian: %s-%s", 
            $row['km_ten'],
            $row['km_noidung'],
            // Sử dụng hàm date($format, $timestamp) để chuyển đổi ngày thành định dạng Việt Nam (ngày/tháng/năm)
            // Do hàm date() nhận vào là đối tượng thời gian, chúng ta cần sử dụng hàm strtotime() để chuyển đổi từ chuỗi có định dạng 'yyyy-mm-dd' trong MYSQL thành đối tượng ngày tháng
            date('d/m/Y', strtotime($row['km_tungay'])),    //vd: '2019-04-25'
            date('d/m/Y', strtotime($row['km_denngay'])));  //vd: '2019-05-10'
    }
    $data[] = array(
        'sp_ma' => $row['sp_ma'],
        'product_name' => $row['product_name'],
        // Sử dụng hàm number_format(số tiền, số lẻ thập phân, dấu phân cách số lẻ, dấu phân cách hàng nghìn) để định dạng số khi hiển thị trên giao diện. Vd: 15800000 -> format thành 15,800,000.66 vnđ
        'sp_gia' => number_format($row['sp_gia'], 2, ".", ",") . ' vnđ',
        'sp_giacu' => number_format($row['sp_giacu'], 2, ".", ",") . ' vnđ',
        'sp_mota_ngan' => $row['sp_mota_ngan'],
        'sp_mota_chitiet' => $row['sp_mota_chitiet'],
        'sp_ngaycapnhat' => date('d/m/Y H:i:s', strtotime($row['sp_ngaycapnhat'])),
        'sp_quantity' => number_format($row['sp_quantity'], 0, ".", ","),
        'lsp_ma' => $row['lsp_ma'],
        'nsx_ma' => $row['nsx_ma'],
        'km_ma' => $row['km_ma'],
        // Các cột dữ liệu lấy từ liên kết khóa ngoại
        'lproduct_name' => $row['lproduct_name'],
        'nsx_ten' => $row['nsx_ten'],
        'km_tomtat' => $km_tomtat,
    );
}
// Yêu cầu `Twig` vẽ giao diện được viết trong file `backend/sanpham/index.html.twig`
// với dữ liệu truyền vào file giao diện được đặt tên là `ds_sanpham`
echo $twig->render('backend/sanpham/index.html.twig', ['ds_sanpham' => $data] );
```

- Tạo file template cho giao diện Danh sách Loại sản phẩm `/php/twig/templates/backend/sanpham/index.html.twig`
```
+---php
|   \---twig                    <- Đây là thư mục gốc của dự án, các bạn có thể đặt tên các bạn...
|       \---templates
|           \---backend
|               \---sanpham     
|                   \---index.html.twig   <- Tạo file
```
- Nội dung file:
```html
{# Kế thừa layout backend #}
{% extends "backend/layouts/layout.html.twig" %}

{# Nội dung trong block title #}
{% block title %}
Danh sách Sản phẩm
{% endblock %}
{# End Nội dung trong block title #}

{# Nội dung trong block headline #}
{% block headline %}
Danh sách Sản phẩm
{% endblock %}
{# End Nội dung trong block headline #}

{# Nội dung trong block content #}
{% block content %}
<!-- Nút thêm mới, bấm vào sẽ hiển thị form nhập thông tin Thêm mới -->
<a href="create.php" class="btn btn-primary">
    <span data-feather="plus"></span> Thêm mới
</a>
<table class="table table-bordered table-hover mt-2">
    <thead class="thead-dark">
        <tr>
            <th>Mã Sản phẩm</th>
            <th>Tên Sản phẩm</th>
            <th>Giá</th>
            <th>Giá cũ</th>
            <th>Mô tả</th>
            <th>Ngày cập nhật</th>
            <th>Số lượng</th>
            <th>Loại sản phẩm</th>
            <th>Nhà sản xuất</th>
            <th>Khuyến mãi</th>
            <th>Hành động</th>
        </tr>
    </thead>
    <tbody>
        {% for sanpham in ds_sanpham %}
        <tr>
            <td>{{ sanpham.sp_ma }}</td>
            <td>{{ sanpham.product_name }}</td>
            <td>{{ sanpham.sp_gia }}</td>
            <td>{{ sanpham.sp_giacu }}</td>
            <td>
                {{ sanpham.sp_mota_ngan }}
                <p>
                    {{ sanpham.sp_mota_chitiet }}
                </p>
            </td>
            <td>{{ sanpham.sp_ngaycapnhat }}</td>
            <td>{{ sanpham.sp_quantity }}</td>
            <td>{{ sanpham.lproduct_name }}</td>
            <td>{{ sanpham.nsx_ten }}</td>
            <td>{{ sanpham.km_tomtat }}</td>
            <td>
                <!-- Nút sửa, bấm vào sẽ hiển thị form hiệu chỉnh thông tin dựa vào khóa chính `sp_ma` -->
                <a href="edit.php?sp_ma={{ sanpham.sp_ma }}" class="btn btn-warning">
                    <span data-feather="edit"></span> Sửa
                </a>

                <!-- Nút xóa, bấm vào sẽ xóa thông tin dựa vào khóa chính `sp_ma` -->
                <a href="delete.php?sp_ma={{ sanpham.sp_ma }}" class="btn btn-danger">
                    <span data-feather="delete"></span> Xóa
                </a>
            </td>
        </tr>
        {% endfor %}
    </tbody>
</table>
{% endblock %}
{# End Nội dung trong block content #}
```

## Step 3: tạo chức năng `create` dùng để hiển thị màn hình form thêm mới Sản phẩm
- Tạo file `/php/twig/backend/sanpham/create.php` để xử lý logic/nghiệp vụ
```
+---php
|   \---twig                    <- Đây là thư mục gốc của dự án, các bạn có thể đặt tên các bạn...
|       \---backend
|           \---sanpham     
|               \---create.php   <- Tạo file
```
- Nội dung file:
```php
<?php
// Include file cấu hình ban đầu của `Twig`
require_once __DIR__.'/../../bootstrap.php';

// Truy vấn database
// 1. Include file cấu hình kết nối đến database, khởi tạo kết nối $conn
include_once(__DIR__.'/../../dbconnect.php');

/* --- 
   --- 2.Truy vấn dữ liệu Loại sản phẩm 
   --- 
*/
// Chuẩn bị câu truy vấn Loại sản phẩm
$sqlLoaiSanPham = "select * from `loaisanpham`";

// Thực thi câu truy vấn SQL để lấy về dữ liệu
$resultLoaiSanPham = mysqli_query($conn, $sqlLoaiSanPham);

// Khi thực thi các truy vấn dạng SELECT, dữ liệu lấy về cần phải phân tích để sử dụng
// Thông thường, chúng ta sẽ sử dụng vòng lặp while để duyệt danh sách các dòng dữ liệu được SELECT
// Ta sẽ tạo 1 mảng array để chứa các dữ liệu được trả về
$dataLoaiSanPham = [];
while($rowLoaiSanPham = mysqli_fetch_array($resultLoaiSanPham, MYSQLI_ASSOC))
{
    $dataLoaiSanPham[] = array(
        'lsp_ma' => $rowLoaiSanPham['lsp_ma'],
        'lproduct_name' => $rowLoaiSanPham['lproduct_name'],
        'lsp_mota' => $rowLoaiSanPham['lsp_mota'],
    );
}
/* --- End Truy vấn dữ liệu Loại sản phẩm --- */

/* --- 
   --- 3. Truy vấn dữ liệu Nhà sản xuất 
   --- 
*/
// Chuẩn bị câu truy vấn Nhà sản xuất
$sqlNhaSanXuat = "select * from `nhasanxuat`";

// Thực thi câu truy vấn SQL để lấy về dữ liệu
$resultNhaSanXuat = mysqli_query($conn, $sqlNhaSanXuat);

// Khi thực thi các truy vấn dạng SELECT, dữ liệu lấy về cần phải phân tích để sử dụng
// Thông thường, chúng ta sẽ sử dụng vòng lặp while để duyệt danh sách các dòng dữ liệu được SELECT
// Ta sẽ tạo 1 mảng array để chứa các dữ liệu được trả về
$dataNhaSanXuat = [];
while($rowNhaSanXuat = mysqli_fetch_array($resultNhaSanXuat, MYSQLI_ASSOC))
{
    $dataNhaSanXuat[] = array(
        'nsx_ma' => $rowNhaSanXuat['nsx_ma'],
        'nsx_ten' => $rowNhaSanXuat['nsx_ten'],
    );
}
/* --- End Truy vấn dữ liệu Nhà sản xuất --- */

/* --- 
   --- 4. Truy vấn dữ liệu Khuyến mãi
   --- 
*/
// Chuẩn bị câu truy vấn Khuyến mãi
$sqlKhuyenMai = "select * from `khuyenmai`";

// Thực thi câu truy vấn SQL để lấy về dữ liệu
$resultKhuyenMai = mysqli_query($conn, $sqlKhuyenMai);

// Khi thực thi các truy vấn dạng SELECT, dữ liệu lấy về cần phải phân tích để sử dụng
// Thông thường, chúng ta sẽ sử dụng vòng lặp while để duyệt danh sách các dòng dữ liệu được SELECT
// Ta sẽ tạo 1 mảng array để chứa các dữ liệu được trả về
$dataKhuyenMai = [];
while($rowKhuyenMai = mysqli_fetch_array($resultKhuyenMai, MYSQLI_ASSOC))
{
    $km_tomtat = '';
    if(!empty($rowKhuyenMai['km_ten'])) {
        // Sử dụng hàm sprintf() để chuẩn bị mẫu câu với các giá trị truyền vào tương ứng từng vị trí placeholder
        $km_tomtat = sprintf("Khuyến mãi %s, nội dung: %s, thời gian: %s-%s", 
            $rowKhuyenMai['km_ten'],
            $rowKhuyenMai['km_noidung'],
            // Sử dụng hàm date($format, $timestamp) để chuyển đổi ngày thành định dạng Việt Nam (ngày/tháng/năm)
            // Do hàm date() nhận vào là đối tượng thời gian, chúng ta cần sử dụng hàm strtotime() để chuyển đổi từ chuỗi có định dạng 'yyyy-mm-dd' trong MYSQL thành đối tượng ngày tháng
            date('d/m/Y', strtotime($rowKhuyenMai['km_tungay'])),    //vd: '2019-04-25'
            date('d/m/Y', strtotime($rowKhuyenMai['km_denngay'])));  //vd: '2019-05-10'
    }
    $dataKhuyenMai[] = array(
        'km_ma' => $rowKhuyenMai['km_ma'],
        'km_tomtat' => $km_tomtat,
    );
}
/* --- End Truy vấn dữ liệu Khuyến mãi --- */

// 2. Nếu người dùng có bấm nút Đăng ký thì thực thi câu lệnh UPDATE
if(isset($_POST['btnSave'])) 
{
    // Lấy dữ liệu người dùng hiệu chỉnh gởi từ REQUEST POST
    $ten = $_POST['product_name'];
    $gia = $_POST['sp_gia'];
    $giacu = $_POST['sp_giacu'];
    $motangan = $_POST['sp_mota_ngan'];
    $motachitiet = $_POST['sp_mota_chitiet'];
    $ngaycapnhat = $_POST['sp_ngaycapnhat'];
    $quantity = $_POST['sp_quantity'];
    $lsp_ma = $_POST['lsp_ma'];
    $nsx_ma = $_POST['nsx_ma'];
    $km_ma = $_POST['km_ma'];

    // Câu lệnh INSERT
    $sql = "INSERT INTO `sanpham` (product_name, sp_gia, sp_giacu, sp_mota_ngan, sp_mota_chitiet, sp_ngaycapnhat, sp_quantity, lsp_ma, nsx_ma, km_ma) VALUES ('$ten', $gia, $giacu, '$motangan', '$motachitiet', '$ngaycapnhat', $quantity, $lsp_ma, $nsx_ma, $km_ma);";
    
    // Thực thi INSERT
    mysqli_query($conn, $sql);

    // Đóng kết nối
    mysqli_close($conn);

    // Sau khi cập nhật dữ liệu, tự động điều hướng về trang Danh sách
    header('location:index.php');
}

// Yêu cầu `Twig` vẽ giao diện được viết trong file `backend/sanpham/create.html.twig`
echo $twig->render('backend/sanpham/create.html.twig', [
    'ds_loaisanpham' => $dataLoaiSanPham,
    'ds_nhasanxuat' => $dataNhaSanXuat,
    'ds_khuyenmai' => $dataKhuyenMai,
]);
```

- Tạo file template cho giao diện Thêm mới Sản phẩm `/php/twig/templates/backend/sanpham/create.html.twig`
```
+---php
|   \---twig                    <- Đây là thư mục gốc của dự án, các bạn có thể đặt tên các bạn...
|       \---templates
|           \---backend
|               \---sanpham     
|                   \---create.html.twig   <- Tạo file
```
- Nội dung file:
```html
{# Kế thừa layout backend #}
{% extends "backend/layouts/layout.html.twig" %}

{# Nội dung trong block title #}
{% block title %}
Thêm mới Sản phẩm
{% endblock %}
{# End Nội dung trong block title #}

{# Nội dung trong block headline #}
{% block headline %}
Thêm mới Sản phẩm
{% endblock %}
{# End Nội dung trong block headline #}

{# Nội dung trong block content #}
{% block content %}
<form name="frmsanpham" id="frmsanpham" method="post" action="/php/twig/backend/sanpham/create.php">
    <div class="form-group">
        <label for="sp_ma">Mã Sản phẩm</label>
        <input type="text" class="form-control" id="sp_ma" name="sp_ma" placeholder="Mã Sản phẩm" readonly>
        <small id="sp_maHelp" class="form-text text-muted">Mã Sản phẩm không được hiệu chỉnh.</small>
    </div>
    <div class="form-group">
        <label for="product_name">Tên Sản phẩm</label>
        <input type="text" class="form-control" id="product_name" name="product_name" placeholder="Tên Sản phẩm">
    </div>
    <div class="form-group">
        <label for="sp_gia">Giá Sản phẩm</label>
        <input type="text" class="form-control" id="sp_gia" name="sp_gia" placeholder="Giá Sản phẩm">
    </div>
    <div class="form-group">
        <label for="sp_giacu">Giá cũ Sản phẩm</label>
        <input type="text" class="form-control" id="sp_giacu" name="sp_giacu" placeholder="Giá cũ Sản phẩm">
    </div>
    <div class="form-group">
        <label for="sp_mota_ngan">Mô tả ngắn</label>
        <input type="text" class="form-control" id="sp_mota_ngan" name="sp_mota_ngan" placeholder="Mô tả ngắn Sản phẩm">
    </div>
    <div class="form-group">
        <label for="sp_mota_chitiet">Mô tả chi tiết</label>
        <input type="text" class="form-control" id="sp_mota_chitiet" name="sp_mota_chitiet" placeholder="Mô tả chi tiết Sản phẩm">
    </div>
    <div class="form-group">
        <label for="sp_ngaycapnhat">Ngày cập nhật</label>
        <input type="text" class="form-control" id="sp_ngaycapnhat" name="sp_ngaycapnhat" placeholder="Ngày cập nhật Sản phẩm">
    </div>
    <div class="form-group">
        <label for="sp_quantity">Số lượng</label>
        <input type="text" class="form-control" id="sp_quantity" name="sp_quantity" placeholder="Số lượng Sản phẩm">
    </div>
    <div class="form-group">
        <label for="lsp_ma">Loại sản phẩm</label>
        <select class="form-control" id="lsp_ma" name="lsp_ma">
            {% for loaisanpham in ds_loaisanpham %}
            <option value="{{ loaisanpham.lsp_ma }}">{{ loaisanpham.lproduct_name }}</option>
            {% endfor %}
        </select>
    </div>
    <div class="form-group">
        <label for="nsx_ma">Nhà sản xuất</label>
        <select class="form-control" id="nsx_ma" name="nsx_ma">
            {% for nhasanxuat in ds_nhasanxuat %}
            <option value="{{ nhasanxuat.nsx_ma }}">{{ nhasanxuat.nsx_ten }}</option>
            {% endfor %}
        </select>
    </div>
    <div class="form-group">
        <label for="km_ma">Khuyến mãi</label>
        <select class="form-control" id="km_ma" name="km_ma">
            <option value="">Không áp dụng khuyến mãi</option>
            {% for khuyenmai in ds_khuyenmai %}
            <option value="{{ khuyenmai.km_ma }}">{{ khuyenmai.km_tomtat }}</option>
            {% endfor %}
        </select>
    </div>
    <button class="btn btn-primary" name="btnSave">Cập nhật</button>
</form>
{% endblock %}
{# End Nội dung trong block content #}
```

## Step 4: tạo chức năng `edit` dùng để hiển thị màn hình form sửa Sản phẩm
- Tạo file `/php/twig/backend/sanpham/edit.php` để xử lý logic/nghiệp vụ
```
+---php
|   \---twig                    <- Đây là thư mục gốc của dự án, các bạn có thể đặt tên các bạn...
|       \---backend
|           \---sanpham     
|               \---edit.php   <- Tạo file
```
- Nội dung file:
```php
<?php
// Include file cấu hình ban đầu của `Twig`
require_once __DIR__.'/../../bootstrap.php';

// Truy vấn database
// 1. Include file cấu hình kết nối đến database, khởi tạo kết nối $conn
include_once(__DIR__.'/../../dbconnect.php');

/* --- 
   --- 2.Truy vấn dữ liệu Loại sản phẩm 
   --- 
*/
// Chuẩn bị câu truy vấn Loại sản phẩm
$sqlLoaiSanPham = "select * from `loaisanpham`";

// Thực thi câu truy vấn SQL để lấy về dữ liệu
$resultLoaiSanPham = mysqli_query($conn, $sqlLoaiSanPham);

// Khi thực thi các truy vấn dạng SELECT, dữ liệu lấy về cần phải phân tích để sử dụng
// Thông thường, chúng ta sẽ sử dụng vòng lặp while để duyệt danh sách các dòng dữ liệu được SELECT
// Ta sẽ tạo 1 mảng array để chứa các dữ liệu được trả về
$dataLoaiSanPham = [];
while($rowLoaiSanPham = mysqli_fetch_array($resultLoaiSanPham, MYSQLI_ASSOC))
{
    $dataLoaiSanPham[] = array(
        'lsp_ma' => $rowLoaiSanPham['lsp_ma'],
        'lproduct_name' => $rowLoaiSanPham['lproduct_name'],
        'lsp_mota' => $rowLoaiSanPham['lsp_mota'],
    );
}
/* --- End Truy vấn dữ liệu Loại sản phẩm --- */

/* --- 
   --- 3. Truy vấn dữ liệu Nhà sản xuất 
   --- 
*/
// Chuẩn bị câu truy vấn Nhà sản xuất
$sqlNhaSanXuat = "select * from `nhasanxuat`";

// Thực thi câu truy vấn SQL để lấy về dữ liệu
$resultNhaSanXuat = mysqli_query($conn, $sqlNhaSanXuat);

// Khi thực thi các truy vấn dạng SELECT, dữ liệu lấy về cần phải phân tích để sử dụng
// Thông thường, chúng ta sẽ sử dụng vòng lặp while để duyệt danh sách các dòng dữ liệu được SELECT
// Ta sẽ tạo 1 mảng array để chứa các dữ liệu được trả về
$dataNhaSanXuat = [];
while($rowNhaSanXuat = mysqli_fetch_array($resultNhaSanXuat, MYSQLI_ASSOC))
{
    $dataNhaSanXuat[] = array(
        'nsx_ma' => $rowNhaSanXuat['nsx_ma'],
        'nsx_ten' => $rowNhaSanXuat['nsx_ten'],
    );
}
/* --- End Truy vấn dữ liệu Nhà sản xuất --- */

/* --- 
   --- 4. Truy vấn dữ liệu Khuyến mãi
   --- 
*/
// Chuẩn bị câu truy vấn Khuyến mãi
$sqlKhuyenMai = "select * from `khuyenmai`";

// Thực thi câu truy vấn SQL để lấy về dữ liệu
$resultKhuyenMai = mysqli_query($conn, $sqlKhuyenMai);

// Khi thực thi các truy vấn dạng SELECT, dữ liệu lấy về cần phải phân tích để sử dụng
// Thông thường, chúng ta sẽ sử dụng vòng lặp while để duyệt danh sách các dòng dữ liệu được SELECT
// Ta sẽ tạo 1 mảng array để chứa các dữ liệu được trả về
$dataKhuyenMai = [];
while($rowKhuyenMai = mysqli_fetch_array($resultKhuyenMai, MYSQLI_ASSOC))
{
    $km_tomtat = '';
    if(!empty($rowKhuyenMai['km_ten'])) {
        // Sử dụng hàm sprintf() để chuẩn bị mẫu câu với các giá trị truyền vào tương ứng từng vị trí placeholder
        $km_tomtat = sprintf("Khuyến mãi %s, nội dung: %s, thời gian: %s-%s", 
            $rowKhuyenMai['km_ten'],
            $rowKhuyenMai['km_noidung'],
            // Sử dụng hàm date($format, $timestamp) để chuyển đổi ngày thành định dạng Việt Nam (ngày/tháng/năm)
            // Do hàm date() nhận vào là đối tượng thời gian, chúng ta cần sử dụng hàm strtotime() để chuyển đổi từ chuỗi có định dạng 'yyyy-mm-dd' trong MYSQL thành đối tượng ngày tháng
            date('d/m/Y', strtotime($rowKhuyenMai['km_tungay'])),    //vd: '2019-04-25'
            date('d/m/Y', strtotime($rowKhuyenMai['km_denngay'])));  //vd: '2019-05-10'
    }
    $dataKhuyenMai[] = array(
        'km_ma' => $rowKhuyenMai['km_ma'],
        'km_tomtat' => $km_tomtat,
    );
}
/* --- End Truy vấn dữ liệu Khuyến mãi --- */

/* --- 
   --- 5. Truy vấn dữ liệu Sản phẩm theo khóa chính
   --- 
*/
// Chuẩn bị câu truy vấn $sqlSelect, lấy dữ liệu ban đầu của record cần update
// Lấy giá trị khóa chính được truyền theo dạng QueryString Parameter key1=value1&key2=value2...
$sp_ma = $_GET['sp_ma'];
$sqlSelect = "SELECT * FROM `sanpham` WHERE sp_ma=$sp_ma;";

// Thực thi câu truy vấn SQL để lấy về dữ liệu ban đầu của record cần update
$resultSelect = mysqli_query($conn, $sqlSelect);
$sanphamRow = mysqli_fetch_array($resultSelect, MYSQLI_ASSOC); // 1 record
/* --- End Truy vấn dữ liệu Sản phẩm theo khóa chính --- */

// 2. Nếu người dùng có bấm nút Đăng ký thì thực thi câu lệnh UPDATE
if(isset($_POST['btnSave'])) 
{
    // Lấy dữ liệu người dùng hiệu chỉnh gởi từ REQUEST POST
    $ten = $_POST['product_name'];
    $gia = $_POST['sp_gia'];
    $giacu = $_POST['sp_giacu'];
    $motangan = $_POST['sp_mota_ngan'];
    $motachitiet = $_POST['sp_mota_chitiet'];
    $ngaycapnhat = $_POST['sp_ngaycapnhat'];
    $quantity = $_POST['sp_quantity'];
    $lsp_ma = $_POST['lsp_ma'];
    $nsx_ma = $_POST['nsx_ma'];
    $km_ma = empty($_POST['km_ma']) ? 'NULL' : $_POST['km_ma'];

    // Câu lệnh INSERT
    $sql = "UPDATE `sanpham` SET product_name='$ten', sp_gia=$gia, sp_giacu=$giacu, sp_mota_ngan='$motangan', sp_mota_chitiet='$motachitiet', sp_ngaycapnhat='$ngaycapnhat', sp_quantity=$quantity, lsp_ma=$lsp_ma, nsx_ma=$nsx_ma, km_ma=$km_ma WHERE sp_ma=$sp_ma;";
    
    // Thực thi INSERT
    mysqli_query($conn, $sql);

    // Đóng kết nối
    mysqli_close($conn);

    // Sau khi cập nhật dữ liệu, tự động điều hướng về trang Danh sách
    header('location:index.php');
}

// Yêu cầu `Twig` vẽ giao diện được viết trong file `backend/sanpham/edit.html.twig`
echo $twig->render('backend/sanpham/edit.html.twig', [
    'ds_loaisanpham' => $dataLoaiSanPham,
    'ds_nhasanxuat' => $dataNhaSanXuat,
    'ds_khuyenmai' => $dataKhuyenMai,
    'sanpham' => $sanphamRow,
]);
```

- Tạo file template cho giao diện Sửa Loại sản phẩm `/php/twig/templates/backend/sanpham/edit.html.twig`
```
+---php
|   \---twig                    <- Đây là thư mục gốc của dự án, các bạn có thể đặt tên các bạn...
|       \---templates
|           \---backend
|               \---sanpham     
|                   \---edit.html.twig   <- Tạo file
```
- Nội dung file:
```html
{# Kế thừa layout backend #}
{% extends "backend/layouts/layout.html.twig" %}

{# Nội dung trong block title #}
{% block title %}
Sửa Sản phẩm
{% endblock %}
{# End Nội dung trong block title #}

{# Nội dung trong block headline #}
{% block headline %}
Sửa Sản phẩm
{% endblock %}
{# End Nội dung trong block headline #}

{# Nội dung trong block content #}
{% block content %}
<form name="frmsanpham" id="frmsanpham" method="post" action="/php/twig/backend/sanpham/edit.php?sp_ma={{ sanpham.sp_ma }}"">
    <div class="form-group">
        <label for="sp_ma">Mã Sản phẩm</label>
        <input type="text" class="form-control" id="sp_ma" name="sp_ma" placeholder="Mã Sản phẩm" readonly value="{{ sanpham.sp_ma }}">
        <small id="sp_maHelp" class="form-text text-muted">Mã Sản phẩm không được hiệu chỉnh.</small>
    </div>
    <div class="form-group">
        <label for="product_name">Tên Sản phẩm</label>
        <input type="text" class="form-control" id="product_name" name="product_name" placeholder="Tên Sản phẩm" value="{{ sanpham.product_name }}">
    </div>
    <div class="form-group">
        <label for="sp_gia">Giá Sản phẩm</label>
        <input type="text" class="form-control" id="sp_gia" name="sp_gia" placeholder="Giá Sản phẩm" value="{{ sanpham.sp_gia }}">
    </div>
    <div class="form-group">
        <label for="sp_giacu">Giá cũ Sản phẩm</label>
        <input type="text" class="form-control" id="sp_giacu" name="sp_giacu" placeholder="Giá cũ Sản phẩm" value="{{ sanpham.sp_giacu }}">
    </div>
    <div class="form-group">
        <label for="sp_mota_ngan">Mô tả ngắn</label>
        <input type="text" class="form-control" id="sp_mota_ngan" name="sp_mota_ngan" placeholder="Mô tả ngắn Sản phẩm" value="{{ sanpham.sp_mota_ngan }}">
    </div>
    <div class="form-group">
        <label for="sp_mota_chitiet">Mô tả chi tiết</label>
        <input type="text" class="form-control" id="sp_mota_chitiet" name="sp_mota_chitiet" placeholder="Mô tả chi tiết Sản phẩm" value="{{ sanpham.sp_mota_chitiet }}">
    </div>
    <div class="form-group">
        <label for="sp_ngaycapnhat">Ngày cập nhật</label>
        <input type="text" class="form-control" id="sp_ngaycapnhat" name="sp_ngaycapnhat" placeholder="Ngày cập nhật Sản phẩm" value="{{ sanpham.sp_ngaycapnhat }}">
    </div>
    <div class="form-group">
        <label for="sp_quantity">Số lượng</label>
        <input type="text" class="form-control" id="sp_quantity" name="sp_quantity" placeholder="Số lượng Sản phẩm" value="{{ sanpham.sp_quantity }}">
    </div>
    <div class="form-group">
        <label for="lsp_ma">Loại sản phẩm</label>
        <select class="form-control" id="lsp_ma" name="lsp_ma">
            {% for loaisanpham in ds_loaisanpham %}
                {% if loaisanpham.lsp_ma == sanpham.lsp_ma %}
                <option value="{{ loaisanpham.lsp_ma }}" selected>{{ loaisanpham.lproduct_name }}</option>
                {% else %}
                <option value="{{ loaisanpham.lsp_ma }}">{{ loaisanpham.lproduct_name }}</option>
                {% endif %}
            {% endfor %}
        </select>
    </div>
    <div class="form-group">
        <label for="nsx_ma">Nhà sản xuất</label>
        <select class="form-control" id="nsx_ma" name="nsx_ma">
            {% for nhasanxuat in ds_nhasanxuat %}
                {% if nhasanxuat.nsx_ma == sanpham.nsx_ma %}
                <option value="{{ nhasanxuat.nsx_ma }}" selected>{{ nhasanxuat.nsx_ten }}</option>
                {% else %}
                <option value="{{ nhasanxuat.nsx_ma }}">{{ nhasanxuat.nsx_ten }}</option>
                {% endif %}
            {% endfor %}
        </select>
    </div>
    <div class="form-group">
        <label for="km_ma">Khuyến mãi</label>
        <select class="form-control" id="km_ma" name="km_ma">
            <option value="">Không áp dụng khuyến mãi</option>
            {% for khuyenmai in ds_khuyenmai %}
                {% if khuyenmai.km_ma == sanpham.km_ma %}
                <option value="{{ khuyenmai.km_ma }}" selected>{{ khuyenmai.km_tomtat }}</option>
                {% else %}
                <option value="{{ khuyenmai.km_ma }}">{{ khuyenmai.km_tomtat }}</option>
                {% endif %}
            {% endfor %}
        </select>
    </div>
    <button class="btn btn-primary" name="btnSave">Cập nhật</button>
</form>
{% endblock %}
{# End Nội dung trong block content #}
```

## Step 5: tạo chức năng `delete` dùng để xóa Sản phẩm
- Tạo file `/php/twig/backend/sanpham/delete.php` để xử lý logic/nghiệp vụ
```
+---php
|   \---twig                    <- Đây là thư mục gốc của dự án, các bạn có thể đặt tên các bạn...
|       \---backend
|           \---sanpham     
|               \---delete.php   <- Tạo file
```
- Nội dung file:
```php
<?php
// Truy vấn database
// 1. Include file cấu hình kết nối đến database, khởi tạo kết nối $conn
include_once(__DIR__.'/../../dbconnect.php');

// 2. Chuẩn bị câu truy vấn $sql
// Lấy giá trị khóa chính được truyền theo dạng QueryString Parameter key1=value1&key2=value2...
$sp_ma = $_GET['sp_ma'];
$sql = "DELETE FROM `sanpham` WHERE sp_ma=" . $sp_ma;

// 3. Thực thi câu lệnh DELETE
$result = mysqli_query($conn, $sql);

// 4. Đóng kết nối
mysqli_close($conn);
    
// Sau khi cập nhật dữ liệu, tự động điều hướng về trang Danh sách
header('location:index.php');
```

## Kiểm tra ứng dụng
- Truy cập địa chỉ: [http://learning.nentang.vn/php/twig//backend/sanpham/index.php](http://learning.nentang.vn/php/twig//backend/sanpham/index.php)

# Bài học trước
[Bài học 4](./readme-lession4.md)

# Bài học tiếp theo
[Bài học 6](./readme-lession6.md)