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
                    <h1 class="h2">Thêm mới</h1>
                </div>

                <!-- Block content -->
                <?php
                // Truy vấn database
                // 1. Include file cấu hình kết nối đến database, khởi tạo kết nối $conn
                include_once(__DIR__ . '/../../../dbconnect.php');

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
                while ($rowLoaiSanPham = mysqli_fetch_array($resultLoaiSanPham, MYSQLI_ASSOC)) {
                    $dataLoaiSanPham[] = array(
                        'lsp_ma' => $rowLoaiSanPham['lsp_ma'],
                        'lsp_ten' => $rowLoaiSanPham['lsp_ten'],
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
                while ($rowNhaSanXuat = mysqli_fetch_array($resultNhaSanXuat, MYSQLI_ASSOC)) {
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
                while ($rowKhuyenMai = mysqli_fetch_array($resultKhuyenMai, MYSQLI_ASSOC)) {
                    $km_tomtat = '';
                    if (!empty($rowKhuyenMai['km_ten'])) {
                        // Sử dụng hàm sprintf() để chuẩn bị mẫu câu với các giá trị truyền vào tương ứng từng vị trí placeholder
                        $km_tomtat = sprintf(
                            "Khuyến mãi %s, nội dung: %s, thời gian: %s-%s",
                            $rowKhuyenMai['km_ten'],
                            $rowKhuyenMai['km_noidung'],
                            // Sử dụng hàm date($format, $timestamp) để chuyển đổi ngày thành định dạng Việt Nam (ngày/tháng/năm)
                            // Do hàm date() nhận vào là đối tượng thời gian, chúng ta cần sử dụng hàm strtotime() để chuyển đổi từ chuỗi có định dạng 'yyyy-mm-dd' trong MYSQL thành đối tượng ngày tháng
                            date('d/m/Y', strtotime($rowKhuyenMai['km_tungay'])),    //vd: '2019-04-25'
                            date('d/m/Y', strtotime($rowKhuyenMai['km_denngay']))
                        );  //vd: '2019-05-10'
                    }
                    $dataKhuyenMai[] = array(
                        'km_ma' => $rowKhuyenMai['km_ma'],
                        'km_tomtat' => $km_tomtat,
                    );
                }
                /* --- End Truy vấn dữ liệu Khuyến mãi --- */
                ?>

                <form name="frmsanpham" id="frmsanpham" method="post" action="">
                    <div class="form-group">
                        <label for="sp_ten">Tên Sản phẩm</label>
                        <input type="text" class="form-control" id="sp_ten" name="sp_ten" placeholder="Tên Sản phẩm" value="">
                    </div>
                    <div class="form-group">
                        <label for="sp_gia">Giá Sản phẩm</label>
                        <input type="text" class="form-control" id="sp_gia" name="sp_gia" placeholder="Giá Sản phẩm" value="">
                    </div>
                    <div class="form-group">
                        <label for="sp_giacu">Giá cũ Sản phẩm</label>
                        <input type="text" class="form-control" id="sp_giacu" name="sp_giacu" placeholder="Giá cũ Sản phẩm" value="">
                    </div>
                    <div class="form-group">
                        <label for="sp_mota_ngan">Mô tả ngắn</label>
                        <input type="text" class="form-control" id="sp_mota_ngan" name="sp_mota_ngan" placeholder="Mô tả ngắn Sản phẩm" value="">
                    </div>
                    <div class="form-group">
                        <label for="sp_mota_chitiet">Mô tả chi tiết</label>
                        <input type="text" class="form-control" id="sp_mota_chitiet" name="sp_mota_chitiet" placeholder="Mô tả chi tiết Sản phẩm" value="">
                    </div>
                    <div class="form-group">
                        <label for="sp_ngaycapnhat">Ngày cập nhật</label>
                        <input type="text" class="form-control" id="sp_ngaycapnhat" name="sp_ngaycapnhat" placeholder="Ngày cập nhật Sản phẩm" value="">
                    </div>
                    <div class="form-group">
                        <label for="sp_soluong">Số lượng</label>
                        <input type="text" class="form-control" id="sp_soluong" name="sp_soluong" placeholder="Số lượng Sản phẩm" value="">
                    </div>
                    <div class="form-group">
                        <label for="lsp_ma">Loại sản phẩm</label>
                        <select class="form-control" id="lsp_ma" name="lsp_ma">
                            <?php foreach ($dataLoaiSanPham as $loaisanpham) : ?>
                                <option value="<?= $loaisanpham['lsp_ma'] ?>"><?= $loaisanpham['lsp_ten'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="nsx_ma">Nhà sản xuất</label>
                        <select class="form-control" id="nsx_ma" name="nsx_ma">
                            <?php foreach ($dataNhaSanXuat as $nhasanxuat) : ?>
                                <option value="<?= $nhasanxuat['nsx_ma'] ?>"><?= $nhasanxuat['nsx_ten'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="km_ma">Khuyến mãi</label>
                        <select class="form-control" id="km_ma" name="km_ma">
                            <option value="">Không áp dụng khuyến mãi</option>
                            <?php foreach ($dataKhuyenMai as $khuyenmai) : ?>
                                <option value="<?= $khuyenmai['km_ma'] ?>"><?= $khuyenmai['km_tomtat'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <button class="btn btn-primary" name="btnSave">Lưu dữ liệu</button>
                </form>

                <?php
                // 2. Nếu người dùng có bấm nút Đăng ký thì thực thi câu lệnh UPDATE
                if (isset($_POST['btnSave'])) {
                    // Lấy dữ liệu người dùng hiệu chỉnh gởi từ REQUEST POST
                    $ten = $_POST['sp_ten'];
                    $gia = $_POST['sp_gia'];
                    $giacu = $_POST['sp_giacu'];
                    $motangan = $_POST['sp_mota_ngan'];
                    $motachitiet = $_POST['sp_mota_chitiet'];
                    $ngaycapnhat = $_POST['sp_ngaycapnhat'];
                    $soluong = $_POST['sp_soluong'];
                    $lsp_ma = $_POST['lsp_ma'];
                    $nsx_ma = $_POST['nsx_ma'];
                    $km_ma = $_POST['km_ma'];

                    // Câu lệnh INSERT
                    $sql = "INSERT INTO `sanpham` (sp_ten, sp_gia, sp_giacu, sp_mota_ngan, sp_mota_chitiet, sp_ngaycapnhat, sp_soluong, lsp_ma, nsx_ma, km_ma) VALUES ('$ten', $gia, $giacu, '$motangan', '$motachitiet', '$ngaycapnhat', $soluong, $lsp_ma, $nsx_ma, $km_ma);";

                    // Thực thi INSERT
                    mysqli_query($conn, $sql);

                    // Đóng kết nối
                    mysqli_close($conn);

                    // Sau khi cập nhật dữ liệu, tự động điều hướng về trang Danh sách
                    echo "<script>location.href = 'index.php';</script>";
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
</body>

</html>