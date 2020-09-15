<?php
// hàm `session_id()` sẽ trả về giá trị SESSION_ID (tên file session do Web Server tự động tạo)
// - Nếu trả về Rỗng hoặc NULL => chưa có file Session tồn tại
if (session_id() === '') {
  // Yêu cầu Web Server tạo file Session để lưu trữ giá trị tương ứng với CLIENT (Web Browser đang gởi Request)
  session_start();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Paper CSS -->
    <link rel="stylesheet" href="/php/myhand/assets/vendor/paper-css/paper.css" type="text/css" />

    <!-- Block title - Đục lỗ trên giao diện bố cục chung, đặt tên là `title` -->
    <title>Nền tảng - Kiến thức cơ bản về WEB</title>
    <!-- End block title -->

    <!-- Định khổ giấy: A5, A4 or A3 -->
    <style>
        @page {
            size: A4
        }
    </style>
</head>

<body class="A4">

    <?php
    // Truy vấn database
    // 1. Include file cấu hình kết nối đến database, khởi tạo kết nối $conn
    include_once(__DIR__ . '/../../../dbconnect.php');

    /* --- 
    --- 2. Truy vấn dữ liệu Đơn hàng theo khóa chính
    --- 
    */
    // Chuẩn bị câu truy vấn $sqlSelect, lấy dữ liệu ban đầu của record cần update
    // Lấy giá trị khóa chính được truyền theo dạng QueryString Parameter key1=value1&key2=value2...
    $dh_ma = $_GET['dh_ma'];

    // Câu lệnh SQL Query lấy thông tin Đơn hàng
    $sqlSelectDonDatHang = <<<EOT
SELECT 
    ddh.dh_ma, ddh.dh_ngaylap, ddh.dh_ngaygiao, ddh.dh_noigiao, ddh.dh_trangthaithanhtoan, httt.httt_ten, kh.kh_ten, kh.kh_dienthoai
    , SUM(spddh.sp_dh_soluong * spddh.sp_dh_dongia) AS TongThanhTien
FROM `dondathang` ddh
JOIN `sanpham_dondathang` spddh ON ddh.dh_ma = spddh.dh_ma
JOIN `khachhang` kh ON ddh.kh_tendangnhap = kh.kh_tendangnhap
JOIN `hinhthucthanhtoan` httt ON ddh.httt_ma = httt.httt_ma
WHERE ddh.dh_ma=$dh_ma
GROUP BY ddh.dh_ma, ddh.dh_ngaylap, ddh.dh_ngaygiao, ddh.dh_noigiao, ddh.dh_trangthaithanhtoan, httt.httt_ten, kh.kh_ten, kh.kh_dienthoai
EOT;

    // Thực thi câu truy vấn SQL để lấy về dữ liệu ban đầu của record
    $resultSelectDonDatHang = mysqli_query($conn, $sqlSelectDonDatHang);
    $dataDonDatHang;
    while ($row = mysqli_fetch_array($resultSelectDonDatHang, MYSQLI_ASSOC)) {
        $dataDonDatHang = array(
            'dh_ma' => $row['dh_ma'],
            'dh_ngaylap' => date('d/m/Y H:i:s', strtotime($row['dh_ngaylap'])),
            'dh_ngaygiao' => empty($row['dh_ngaygiao']) ? '' : date('d/m/Y H:i:s', strtotime($row['dh_ngaygiao'])),
            'dh_noigiao' => $row['dh_noigiao'],
            'dh_trangthaithanhtoan' => $row['dh_trangthaithanhtoan'],
            'httt_ten' => $row['httt_ten'],
            'kh_ten' => $row['kh_ten'],
            'kh_dienthoai' => $row['kh_dienthoai'],
            'TongThanhTien' => number_format($row['TongThanhTien'], 2, ".", ",") . ' vnđ',
        );
    }
    /* --- End Truy vấn dữ liệu Đơn hàng --- */

    /* --- 
    --- 3. Truy vấn dữ liệu Chi tiết Đơn hàng theo khóa chính
    --- 
    */
    // Lấy dữ liệu Sản phẩm đơn đặt hàng
    $sqlSelectSanPham = <<<EOT
SELECT 
    sp.sp_ma, sp.sp_ten, spddh.sp_dh_dongia, spddh.sp_dh_soluong
    , lsp.lsp_ten, nsx.nsx_ten
FROM `sanpham_dondathang` spddh
JOIN `sanpham` sp ON spddh.sp_ma = sp.sp_ma
JOIN `loaisanpham` lsp ON sp.lsp_ma = lsp.lsp_ma
JOIN `nhasanxuat` nsx ON sp.nsx_ma = nsx.nsx_ma
WHERE spddh.dh_ma=$dh_ma
EOT;

    // Thực thi câu truy vấn SQL để lấy về dữ liệu ban đầu của record cần update
    $resultSelectSanPham = mysqli_query($conn, $sqlSelectSanPham);
    $dataSanPham = [];
    while ($row = mysqli_fetch_array($resultSelectSanPham, MYSQLI_ASSOC)) {
        $dataSanPham[] = array(
            'sp_ma' => $row['sp_ma'],
            'sp_ten' => $row['sp_ten'],
            'sp_dh_dongia' => $row['sp_dh_dongia'],
            'sp_dh_soluong' => $row['sp_dh_soluong'],
            'lsp_ten' => $row['lsp_ten'],
            'nsx_ten' => $row['nsx_ten'],
        );
    }
    /* --- End Truy vấn dữ liệu Chi tiết Đơn hàng --- */

    // 4. Hiệu chỉnh dữ liệu theo cấu trúc để tiện xử lý
    $dataDonDatHang['danhsachsanpham'] = $dataSanPham;
    ?>

    <!-- Block content - Đục lỗ trên giao diện bố cục chung, đặt tên là `content` -->
    <!-- Each sheet element should have the class "sheet" -->
    <!-- "padding-**mm" is optional: you can set 10, 15, 20 or 25 -->
    <section class="sheet padding-10mm">
        <!-- Thông tin Cửa hàng -->
        <table border="0" width="100%" cellspacing="0">
            <tbody>
                <tr>
                    <td align="center"><img src="/php/myhand/assets/shared/img/logo-nentang.jpg" width="100px" height="100px" /></td>
                    <td align="center">
                        <b style="font-size: 2em;">Nền tảng - Hành trang tới Tương lai</b><br />
                        <small>Cung cấp kiến thức nền tảng về Lập trình, Thiết kế Web, Cơ sở dữ liệu</small><br />
                        <small>Giúp các bạn có niềm tin, hành trang kiến thức vững vàng trên con đường trở thành Nhà phát
                            triển
                            Phần mềm</small>
                    </td>
                </tr>
            </tbody>
        </table>

        <!-- Thông tin đơn hàng -->
        <p><i><u>Thông tin Đơn hàng</u></i></p>
        <table border="0" width="100%" cellspacing="0">
            <tbody>
                <tr>
                    <td width="30%">Khách hàng:</td>
                    <td><b><?= $dataDonDatHang['kh_ten'] ?>
                            (<?= $dataDonDatHang['kh_dienthoai'] ?>)</b></td>
                </tr>
                <tr>
                    <td>Ngày lập:</td>
                    <td><b><?= $dataDonDatHang['dh_ngaylap'] ?></b></td>
                </tr>
                <tr>
                    <td>Hình thức thanh toán:</td>
                    <td><b><?= $dataDonDatHang['httt_ten'] ?></b></td>
                </tr>
                <tr>
                    <td>Tổng thành tiền:</td>
                    <td><b><?= $dataDonDatHang['TongThanhTien'] ?></b></td>
                </tr>
            </tbody>
        </table>

        <!-- Thông tin sản phẩm -->
        <p><i><u>Chi tiết đơn hàng</u></i></p>
        <table border="1" width="100%" cellspacing="0" cellpadding="5">
            <thead>
                <tr>
                    <th>STT</th>
                    <th>Sản phẩm</th>
                    <th>Số lượng</th>
                    <th>Đơn giá</th>
                    <th>Thành tiền</th>
                </tr>
            </thead>
            <tbody>
                <?php $stt = 1; ?>
                <?php foreach($dataDonDatHang['danhsachsanpham'] as $sanpham): ?>
                <tr>
                    <td align="center"><?= $stt; ?></td>
                    <td>
                        <b><?= $sanpham['sp_ten'] ?></b><br />
                        <small><i><?= $sanpham['lsp_ten'] ?></i></small><br />
                        <small><i><?= $sanpham['nsx_ten'] ?></i></small>
                    </td>
                    <td align="right"><?= $sanpham['sp_dh_soluong'] ?></td>
                    <td align="right"><?= $sanpham['sp_dh_dongia'] ?></td>
                    <td align="right"><?= $sanpham['sp_dh_soluong'] * $sanpham['sp_dh_dongia'] ?></td>
                </tr>
                <?php $stt++; ?>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4" align="right"><b>Tổng thành tiền</b></td>
                    <td align="right"><b><?= $dataDonDatHang['TongThanhTien'] ?></b></td>
                </tr>
            </tfoot>
        </table>

        <!-- Thông tin Footer -->
        <br />
        <table border="0" width="100%">
            <tbody>
                <tr>
                    <td align="center">
                        <small>Xin cám ơn Quý khách đã ủng hộ Cửa hàng, Chúc Quý khách An Khang, Thịnh Vượng!</small>
                    </td>
                </tr>
            </tbody>
        </table>
    </section>
    <!-- End block content -->
</body>

</html>