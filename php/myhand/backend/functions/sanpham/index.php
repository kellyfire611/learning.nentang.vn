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
                    <h1 class="h2">Danh sách</h1>
                </div>

                <!-- Block content -->
                <?php
                // Truy vấn database để lấy danh sách
                // 1. Include file cấu hình kết nối đến database, khởi tạo kết nối $conn
                include_once(__DIR__ . '/../../../dbconnect.php');

                // 2. Chuẩn bị câu truy vấn $sql
                // Sử dụng HEREDOC của PHP để tạo câu truy vấn SQL với dạng dễ đọc, thân thiện với việc bảo trì code
                $sql = <<<EOT
    SELECT sp.*
        , lsp.lsp_ten
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
                $formatedData = [];
                while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                    $km_tomtat = '';
                    if (!empty($row['km_ten'])) {
                        // Sử dụng hàm sprintf() để chuẩn bị mẫu câu với các giá trị truyền vào tương ứng từng vị trí placeholder
                        $km_tomtat = sprintf(
                            "Khuyến mãi %s, nội dung: %s, thời gian: %s-%s",
                            $row['km_ten'],
                            $row['km_noidung'],
                            // Sử dụng hàm date($format, $timestamp) để chuyển đổi ngày thành định dạng Việt Nam (ngày/tháng/năm)
                            // Do hàm date() nhận vào là đối tượng thời gian, chúng ta cần sử dụng hàm strtotime() 
                            // để chuyển đổi từ chuỗi có định dạng 'yyyy-mm-dd' trong MYSQL thành đối tượng ngày tháng
                            date('d/m/Y', strtotime($row['km_tungay'])),    //vd: '2019-04-25'
                            date('d/m/Y', strtotime($row['km_denngay']))
                        );  //vd: '2019-05-10'
                    }
                    $formatedData[] = array(
                        'sp_ma' => $row['sp_ma'],
                        'sp_ten' => $row['sp_ten'],
                        // Sử dụng hàm number_format(số tiền, số lẻ thập phân, dấu phân cách số lẻ, dấu phân cách hàng nghìn) 
                        // để định dạng số khi hiển thị trên giao diện. 
                        // Vd: 15800000 -> format thành 15,800,000.66 vnđ
                        'sp_gia' => number_format($row['sp_gia'], 2, ".", ",") . ' vnđ',
                        'sp_giacu' => number_format($row['sp_giacu'], 2, ".", ",") . ' vnđ',
                        'sp_mota_ngan' => $row['sp_mota_ngan'],
                        'sp_mota_chitiet' => $row['sp_mota_chitiet'],
                        'sp_ngaycapnhat' => date('d/m/Y H:i:s', strtotime($row['sp_ngaycapnhat'])),
                        'sp_soluong' => number_format($row['sp_soluong'], 0, ".", ","),
                        'lsp_ma' => $row['lsp_ma'],
                        'nsx_ma' => $row['nsx_ma'],
                        'km_ma' => $row['km_ma'],
                        // Các cột dữ liệu lấy từ liên kết khóa ngoại
                        'lsp_ten' => $row['lsp_ten'],
                        'nsx_ten' => $row['nsx_ten'],
                        'km_tomtat' => $km_tomtat,
                    );
                }
                ?>

                <!-- Nút thêm mới, bấm vào sẽ hiển thị form nhập thông tin Thêm mới -->
                <a href="create.php" class="btn btn-primary">
                    Thêm mới
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
                        <?php foreach($formatedData as $sanpham): ?>
                        <tr>
                            <td><?= $sanpham['sp_ma'] ?></td>
                            <td><?= $sanpham['sp_ten'] ?></td>
                            <td><?= $sanpham['sp_gia'] ?></td>
                            <td><?= $sanpham['sp_giacu'] ?></td>
                            <td>
                                <?= $sanpham['sp_mota_ngan'] ?>
                                <p>
                                    <?= $sanpham['sp_mota_chitiet'] ?>
                                </p>
                            </td>
                            <td><?= $sanpham['sp_ngaycapnhat'] ?></td>
                            <td><?= $sanpham['sp_soluong'] ?></td>
                            <td><?= $sanpham['lsp_ten'] ?></td>
                            <td><?= $sanpham['nsx_ten'] ?></td>
                            <td><?= $sanpham['km_tomtat'] ?></td>
                            <td>
                                <!-- Nút sửa, bấm vào sẽ hiển thị form hiệu chỉnh thông tin dựa vào khóa chính `sp_ma` -->
                                <a href="edit.php?sp_ma=<?= $sanpham['sp_ma'] ?>" class="btn btn-warning">
                                    Sửa
                                </a>

                                <!-- Nút xóa, bấm vào sẽ xóa thông tin dựa vào khóa chính `sp_ma` -->
                                <a href="delete.php?sp_ma=<?= $sanpham['sp_ma'] ?>" class="btn btn-danger">
                                    Xóa
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
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