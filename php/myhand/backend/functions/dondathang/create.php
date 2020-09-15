<?php
// hàm `session_id()` sẽ trả về giá trị SESSION_ID (tên file session do Web Server tự động tạo)
// - Nếu trả về Rỗng hoặc NULL => chưa có file Session tồn tại
if (session_id() === '') {
  // Yêu cầu Web Server tạo file Session để lưu trữ giá trị tương ứng với CLIENT (Web Browser đang gởi Request)
  session_start();
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NenTang.vn</title>

    <!-- Nhúng file Quản lý các Liên kết CSS dùng chung cho toàn bộ trang web -->
    <?php include_once(__DIR__ . '/../../layouts/styles.php'); ?>
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
                // Hiển thị tất cả lỗi trong PHP
                // Chỉ nên hiển thị lỗi khi đang trong môi trường Phát triển (Development)
                // Không nên hiển thị lỗi trên môi trường Triển khai (Production)
                ini_set('display_errors', 1);
                ini_set('display_startup_errors', 1);
                error_reporting(E_ALL);

                // Truy vấn database
                // 1. Include file cấu hình kết nối đến database, khởi tạo kết nối $conn
                include_once(__DIR__ . '/../../../dbconnect.php');

                /*  --- 
                --- 2.Truy vấn dữ liệu Hình thức Thanh toán
                --- 
                */
                // Chuẩn bị câu truy vấn
                $sqlHinhThucThanhToan = "select * from `hinhthucthanhtoan`";

                // Thực thi câu truy vấn SQL để lấy về dữ liệu
                $resultHinhThucThanhToan = mysqli_query($conn, $sqlHinhThucThanhToan);

                // Khi thực thi các truy vấn dạng SELECT, dữ liệu lấy về cần phải phân tích để sử dụng
                // Thông thường, chúng ta sẽ sử dụng vòng lặp while để duyệt danh sách các dòng dữ liệu được SELECT
                // Ta sẽ tạo 1 mảng array để chứa các dữ liệu được trả về
                $dataHinhThucThanhToan = [];
                while ($rowHinhThucThanhToan = mysqli_fetch_array($resultHinhThucThanhToan, MYSQLI_ASSOC)) {
                    $dataHinhThucThanhToan[] = array(
                        'httt_ma' => $rowHinhThucThanhToan['httt_ma'],
                        'httt_ten' => $rowHinhThucThanhToan['httt_ten'],
                    );
                }
                /* --- End Truy vấn dữ liệu Hình thức Thanh toán --- */

                /*  --- 
                --- 3.Truy vấn dữ liệu Khách hàng
                --- 
                */
                // Chuẩn bị câu truy vấn
                $sqlKhachHang = "select * from `khachhang`";

                // Thực thi câu truy vấn SQL để lấy về dữ liệu
                $resultKhachHang = mysqli_query($conn, $sqlKhachHang);

                // Khi thực thi các truy vấn dạng SELECT, dữ liệu lấy về cần phải phân tích để sử dụng
                // Thông thường, chúng ta sẽ sử dụng vòng lặp while để duyệt danh sách các dòng dữ liệu được SELECT
                // Ta sẽ tạo 1 mảng array để chứa các dữ liệu được trả về
                $dataKhachHang = [];
                while ($rowKhachHang = mysqli_fetch_array($resultKhachHang, MYSQLI_ASSOC)) {
                    // Sử dụng hàm sprintf() để chuẩn bị mẫu câu với các giá trị truyền vào tương ứng từng vị trí placeholder
                    $kh_tomtat = sprintf(
                        "Họ tên %s, số điện thoại: %s",
                        $rowKhachHang['kh_ten'],
                        $rowKhachHang['kh_dienthoai'],
                    );

                    $dataKhachHang[] = array(
                        'kh_tendangnhap' => $rowKhachHang['kh_tendangnhap'],
                        'kh_tomtat' => $kh_tomtat,
                    );
                }
                /* --- End Truy vấn dữ liệu Hình thức Thanh toán --- */

                /*  --- 
                --- 4.Truy vấn dữ liệu sản phẩm 
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
                while ($rowSanPham = mysqli_fetch_array($resultSanPham, MYSQLI_ASSOC)) {
                    $dataSanPham[] = array(
                        'sp_ma' => $rowSanPham['sp_ma'],
                        'sp_gia' => $rowSanPham['sp_gia'],
                        'sp_ten' => $rowSanPham['sp_ten'],
                    );
                }
                // var_dump($dataSanPham);die;
                /* --- End Truy vấn dữ liệu sản phẩm --- */
                ?>

                <!-- Form cho phép người dùng upload file lên Server bắt buộc phải có thuộc tính enctype="multipart/form-data" -->
                <form name="frmhinhsanpham" id="frmhinhanpham" method="post" action="" enctype="multipart/form-data">
                    <fieldset id="donHangContainer">
                        <legend>Thông tin Đơn hàng</legend>
                        <div class="form-row">
                            <!-- <div class="col">
                                <div class="form-group">
                                    <label>Mã Đơn hàng</label>
                                    <input type="text" name="dh_ma" id="dh_ma" class="form-control" />
                                </div>
                            </div> -->
                            <div class="col">
                                <div class="form-group">
                                    <label>Khách hàng</label>
                                    <select name="kh_tendangnhap" id="kh_tendangnhap" class="form-control">
                                        <option value="">Vui lòng chọn Khách hàng</option>
                                        <?php foreach ($dataKhachHang as $khachhang) : ?>
                                            <option value="<?= $khachhang['kh_tendangnhap'] ?>"><?= $khachhang['kh_tomtat'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col">
                                <div class="form-group">
                                    <label>Ngày lập</label>
                                    <input type="text" name="dh_ngaylap" id="dh_ngaylap" class="form-control" />
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label>Ngày giao</label>
                                    <input type="text" name="dh_ngaygiao" id="dh_ngaygiao" class="form-control" />
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label>Nơi giao</label>
                                    <input type="text" name="dh_noigiao" id="dh_noigiao" class="form-control" />
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col">
                                <div class="form-group">
                                    <label>Trạng thái thanh toán</label><br />
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" name="dh_trangthaithanhtoan" id="dh_trangthaithanhtoan-1" class="custom-control-input" value="0" checked>
                                        <label class="custom-control-label" for="dh_trangthaithanhtoan-1">Chưa thanh toán</label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" name="dh_trangthaithanhtoan" id="dh_trangthaithanhtoan-2" class="custom-control-input" value="1">
                                        <label class="custom-control-label" for="dh_trangthaithanhtoan-2">Đã thanh toán</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label>Hình thức thanh toán</label>
                                    <select name="httt_ma" id="httt_ma" class="form-control">
                                    <option value="">Vui lòng chọn Hình thức thanh toán</option>
                                        <?php foreach ($dataHinhThucThanhToan as $httt) : ?>
                                            <option value="<?= $httt['httt_ma'] ?>"><?= $httt['httt_ten'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </fieldset>

                    <fieldset id="chiTietDonHangContainer">
                        <legend>Thông tin Chi tiết Đơn hàng</legend>
                        <div class="form-row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="sp_ma">Sản phẩm</label>
                                    <select class="form-control" id="sp_ma" name="sp_ma">
                                        <option value="">Vui lòng chọn Sản phẩm</option>
                                        <?php foreach ($dataSanPham as $sanpham) : ?>
                                            <option value="<?= $sanpham['sp_ma'] ?>" data-sp_gia="<?= $sanpham['sp_gia'] ?>"><?= $sanpham['sp_ten'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label>Số lượng</label>
                                    <input type="text" name="soluong" id="soluong" class="form-control" />
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label>Xử lý</label><br />
                                    <button type="button" id="btnThemSanPham" class="btn btn-secondary">Thêm vào đơn hàng</button>
                                </div>
                            </div>
                        </div>

                        <table id="tblChiTietDonHang" class="table table-bordered">
                            <thead>
                                <th>Sản phẩm</th>
                                <th>Số lượng</th>
                                <th>Đơn giá</th>
                                <th>Thành tiền</th>
                                <th>Hành động</th>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </fieldset>

                    <button class="btn btn-primary" name="btnSave">Lưu</button>
                    <a href="index.php" class="btn btn-outline-secondary" name="btnBack" id="btnBack">Quay về</a>
                </form>

                <?php
                // Nếu người dùng có bấm nút Đăng ký thì thực thi câu lệnh
                if (isset($_POST['btnSave'])) {
                    // 1. Phân tách lấy dữ liệu người dùng gởi từ REQUEST POST
                    // Thông tin đơn hàng
                    $kh_tendangnhap = $_POST['kh_tendangnhap'];
                    $dh_ngaylap = $_POST['dh_ngaylap'];
                    $dh_ngaygiao = $_POST['dh_ngaygiao'];
                    $dh_noigiao = $_POST['dh_noigiao'];
                    $dh_trangthaithanhtoan = $_POST['dh_trangthaithanhtoan'];
                    $httt_ma = $_POST['httt_ma'];

                    // Thông tin các dòng chi tiết đơn hàng
                    $arr_sp_ma = $_POST['sp_ma'];                   // mảng array do đặt tên name="sp_ma[]"
                    $arr_sp_dh_soluong = $_POST['sp_dh_soluong'];   // mảng array do đặt tên name="sp_dh_soluong[]"
                    $arr_sp_dh_dongia = $_POST['sp_dh_dongia'];     // mảng array do đặt tên name="sp_dh_dongia[]"
                    // var_dump($sp_ma);die;

                    // 2. Thực hiện câu lệnh Tạo mới (INSERT) Đơn hàng
                    // Câu lệnh INSERT
                    $sqlInsertDonHang = "INSERT INTO `dondathang` (`dh_ngaylap`, `dh_ngaygiao`, `dh_noigiao`, `dh_trangthaithanhtoan`, `httt_ma`, `kh_tendangnhap`) VALUES ('$dh_ngaylap', '$dh_ngaygiao', N'$dh_noigiao', '$dh_trangthaithanhtoan', '$httt_ma', '$kh_tendangnhap')";
                    // print_r($sql); die;

                    // Thực thi INSERT Đơn hàng
                    mysqli_query($conn, $sqlInsertDonHang);

                    // 3. Lấy ID Đơn hàng mới nhất vừa được thêm vào database
                    // Do ID là tự động tăng (PRIMARY KEY và AUTO INCREMENT), nên chúng ta không biết được ID đă tăng đến số bao nhiêu?
                    // Cần phải sử dụng biến `$conn->insert_id` để lấy về ID mới nhất
                    // Nếu thực thi câu lệnh INSERT thành công thì cần lấy ID mới nhất của Đơn hàng để làm khóa ngoại trong Chi tiết đơn hàng
                    $dh_ma = $conn->insert_id;

                    // 4. Duyệt vòng lặp qua mảng các dòng Sản phẩm của chi tiết đơn hàng được gởi đến qua request POST
                    for($i = 0; $i < count($arr_sp_ma); $i++) {
                        // 4.1. Chuẩn bị dữ liệu cho câu lệnh INSERT vào table `sanpham_dondathang`
                        $sp_ma = $arr_sp_ma[$i];
                        $sp_dh_soluong = $arr_sp_dh_soluong[$i];
                        $sp_dh_dongia = $arr_sp_dh_dongia[$i];

                        // 4.2. Câu lệnh INSERT
                        $sqlInsertSanPhamDonDatHang = "INSERT INTO `sanpham_dondathang` (`sp_ma`, `dh_ma`, `sp_dh_soluong`, `sp_dh_dongia`) VALUES ($sp_ma, $dh_ma, $sp_dh_soluong, $sp_dh_dongia)";

                        // 4.3. Thực thi INSERT
                        mysqli_query($conn, $sqlInsertSanPhamDonDatHang);
                    }

                    // 5. Thực thi hoàn tất, điều hướng về trang Danh sách
                    echo '<script>location.href = "index.php";</script>';
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
    <script>
        // Đăng ký sự kiện Click nút Thêm Sản phẩm
        $('#btnThemSanPham').click(function() {
            // debugger;
            // Lấy thông tin Sản phẩm
            var sp_ma = $('#sp_ma').val();
            var sp_gia = $('#sp_ma option:selected').data('sp_gia');
            var sp_ten = $('#sp_ma option:selected').text();
            var soluong = $('#soluong').val();
            var thanhtien = (soluong * sp_gia);
            
            // Tạo mẫu giao diện HTML Table Row
            var htmlTemplate = '<tr>'; 
            htmlTemplate += '<td>' + sp_ten + '<input type="hidden" name="sp_ma[]" value="' + sp_ma + '"/></td>';
            htmlTemplate += '<td>' + soluong + '<input type="hidden" name="sp_dh_soluong[]" value="' + soluong + '"/></td>';
            htmlTemplate += '<td>' + sp_gia + '<input type="hidden" name="sp_dh_dongia[]" value="' + sp_gia + '"/></td>';
            htmlTemplate += '<td>' + thanhtien + '</td>';
            htmlTemplate += '<td><button type="button" class="btn btn-danger btn-delete-row">Xóa</button></td>';
            htmlTemplate += '</tr>';

            // Thêm vào TABLE BODY
            $('#tblChiTietDonHang tbody').append(htmlTemplate);

            // Clear
            $('#sp_ma').val('');
            $('#soluong').val('');
        });

        // Đăng ký sự kiện cho tất cả các nút XÓA có sử dụng class .btn-delete-row
        $('#chiTietDonHangContainer').on('click', '.btn-delete-row', function() {
            // Ta có cấu trúc
            // <tr>
            //    <td>
            //        <button class="btn-delete-row"></button>     <--- $(this) chính là đối tượng đang được người dùng click
            //    </td>
            // </tr>
            
            // Từ nút người dùng click -> tìm lên phần tử cha -> phần tử cha
            // Xóa dòng TR
            $(this).parent().parent()[0].remove();
        });
    </script>
</body>

</html>