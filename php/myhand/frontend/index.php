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
    <?php include_once(__DIR__ . '/layouts/styles.php'); ?>

    <link href="/php/myhand/assets/frontend/css/style.css" type="text/css" rel="stylesheet" />

    <style>
        .homepage-slider-img {
            width: 100%;
            height: 450px;
            object-fit: cover;
        }
    </style>
</head>

<body class="d-flex flex-column h-100">
    <!-- header -->
    <?php include_once(__DIR__ . '/layouts/partials/header.php'); ?>
    <!-- end header -->

    <main role="main" class="mb-2">
        <!-- Block content -->
        <?php
        // Hiển thị tất cả lỗi trong PHP
        // Chỉ nên hiển thị lỗi khi đang trong môi trường Phát triển (Development)
        // Không nên hiển thị lỗi trên môi trường Triển khai (Production)
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);

        // Truy vấn database để lấy danh sách
        // 1. Include file cấu hình kết nối đến database, khởi tạo kết nối $conn
        include_once(__DIR__ . '/../dbconnect.php');

        // 2. Chuẩn bị câu truy vấn $sql
        $sqlDanhSachSanPham = <<<EOT
        SELECT sp.sp_ma, sp.sp_ten, sp.sp_gia, sp.sp_giacu, sp.sp_mota_ngan, sp.sp_soluong, lsp.lsp_ten, MAX(hsp.hsp_tentaptin) AS hsp_tentaptin
        FROM `sanpham` sp
        JOIN `loaisanpham` lsp ON sp.lsp_ma = lsp.lsp_ma
        LEFT JOIN `hinhsanpham` hsp ON sp.sp_ma = hsp.sp_ma
        GROUP BY sp.sp_ma, sp.sp_ten, sp.sp_gia, sp.sp_giacu, sp.sp_mota_ngan, sp.sp_soluong, lsp.lsp_ten
EOT;

        // 3. Thực thi câu truy vấn SQL để lấy về dữ liệu
        $result = mysqli_query($conn, $sqlDanhSachSanPham);

        // 4. Khi thực thi các truy vấn dạng SELECT, dữ liệu lấy về cần phải phân tích để sử dụng
        // Thông thường, chúng ta sẽ sử dụng vòng lặp while để duyệt danh sách các dòng dữ liệu được SELECT
        // Ta sẽ tạo 1 mảng array để chứa các dữ liệu được trả về
        $dataDanhSachSanPham = [];
        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $dataDanhSachSanPham[] = array(
                'sp_ma' => $row['sp_ma'],
                'sp_ten' => $row['sp_ten'],
                'sp_gia' => number_format($row['sp_gia'], 2, ".", ",") . ' vnđ',
                'sp_giacu' => number_format($row['sp_giacu'], 2, ".", ","),
                'sp_mota_ngan' => $row['sp_mota_ngan'],
                'sp_soluong' => $row['sp_soluong'],
                'lsp_ten' => $row['lsp_ten'],
                'hsp_tentaptin' => $row['hsp_tentaptin'],
            );
        }

        ?>

        <!-- Carousel - Slider -->
        <div id="myCarousel" class="carousel slide" data-ride="carousel">
            <ol class="carousel-indicators">
                <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
                <li data-target="#myCarousel" data-slide-to="1"></li>
                <li data-target="#myCarousel" data-slide-to="2"></li>
            </ol>
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="/php/myhand/assets/frontend/img/slider-1.jpg" class="img-fluid homepage-slider-img" />
                    <div class="container">
                        <div class="carousel-caption text-left">
                            <h1>Nền Tảng - Nơi mua sắm tuyệt vời</h1>

                        </div>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="/php/myhand/assets/frontend/img/slider-2.jpg" class="img-fluid homepage-slider-img" />
                    <div class="container">
                        <div class="carousel-caption">
                            <h1>Hàng triệu sản phẩm - Lựa chọn mỏi tay</h1>

                        </div>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="/php/myhand/assets/frontend/img/slider-3.jpg" class="img-fluid homepage-slider-img" />
                    <div class="container">
                        <div class="carousel-caption text-right">
                            <h1>Chất lượng là Hàng đầu.</h1>

                        </div>
                    </div>
                </div>
            </div>
            <a class="carousel-control-prev" href="#myCarousel" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
            </a>
            <a class="carousel-control-next" href="#myCarousel" role="button" data-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </a>
        </div>

        <!-- Tính năng Marketing -->
        <div class="container marketing">
            <!-- Three columns of text below the carousel -->
            <div class="row">
                <div class="col-lg-4 text-center">
                    <img class="bd-placeholder-img rounded-circle" width="140" height="140" src="/php/myhand/assets/frontend/img/icon-1.png" />
                    <h2>Đặt hàng</h2>
                    <p>Chọn sản phẩm bạn yêu thích, và Đặt hàng.</p>
                </div><!-- /.col-lg-4 -->
                <div class="col-lg-4 text-center">
                    <img class="bd-placeholder-img rounded-circle" width="140" height="140" src="/php/myhand/assets/frontend/img/icon-2.png" />
                    <h2>Tạo đơn hàng</h2>
                    <p>Theo dõi đơn hàng của bạn.</p>
                </div><!-- /.col-lg-4 -->
                <div class="col-lg-4 text-center">
                    <img class="bd-placeholder-img rounded-circle" width="140" height="140" src="/php/myhand/assets/frontend/img/icon-3.png" />
                    <h2>Giao hàng</h2>
                    <p>Giao hàng tận nơi.</p>
                </div><!-- /.col-lg-4 -->
            </div><!-- /.row -->


            <!-- START THE FEATURETTES -->

            <hr class="featurette-divider">

            <div class="row featurette">
                <div class="col-md-7">
                    <h2 class="featurette-heading">Đặt hàng, Tạo đơn hàng, Giao hàng <span class="text-muted">Nhanh chóng</span>
                    </h2>
                    <p class="lead">Nơi mua sắm tuyệt vời cho mọi lứa tuổi.</p>
                </div>
                <div class="col-md-5">
                    <img src="/php/myhand/assets/frontend/img/marketing-1.png" class="img-fluid" />
                </div>
            </div>

            <hr class="featurette-divider">

            <div class="row featurette">
                <div class="col-md-7 order-md-2">
                    <h2 class="featurette-heading">Báo cáo Doanh thu tuyệt vời <span class="text-muted">Theo dõi đơn hàng của
                            bạn.</span></h2>
                    <p class="lead">Hệ thống theo dõi đơn hàng chi tiết, thông tin mọi lúc mọi nơi.</p>
                </div>
                <div class="col-md-5 order-md-1">
                    <img src="/php/myhand/assets/frontend/img/marketing-2.png" class="img-fluid" />
                </div>
            </div>

            <hr class="featurette-divider">

            <!-- /END THE FEATURETTES -->

        </div>

        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="slider slider-nav slider-new-sanphams">

                    </div>
                </div>
            </div>
        </div>

        <!-- Danh sách sản phẩm -->
        <section class="jumbotron text-center">
            <div class="container">
                <h1 class="jumbotron-heading">Danh sách Sản phẩm</h1>
                <p class="lead text-muted">Các sản phẩm với chất lượng, uy tín, cam kết từ nhà Sản xuất, phân phối và bảo hành
                    chính hãng.</p>
            </div>
        </section>

        <!-- Giải thuật duyệt và render Danh sách sản phẩm theo dòng, cột của Bootstrap -->
        <div class="sanphams py-5 bg-light">
            <div class="container">
                <div class="row row-cols-3">
                    <?php foreach ($dataDanhSachSanPham as $sanpham) : ?>
                        <div class="col">
                            <div class="card mb-4 shadow-sm">
                                <div class="card-header">
                                    <div class="ribbon-wrapper">
                                        <div class="ribbon red">MỚI</div>
                                    </div>

                                    <!-- Nếu có hình sản phẩm thì hiển thị -->
                                    <?php if (!empty($sanpham['hsp_tentaptin'])) : ?>
                                        <div class="container-img">
                                            <a href="/php/myhand/frontend/sanpham/detail.php?sp_ma=<?= $sanpham['sp_ma'] ?>">
                                                <img class="bd-placeholder-img card-img-top img-fluid" width="100%" height="350" src="/php/myhand/assets/uploads/products/<?= $sanpham['hsp_tentaptin'] ?>" />
                                            </a>
                                        </div>
                                        <!-- Nếu không có hình sản phẩm thì hiển thị ảnh mặc định -->
                                    <?php else : ?>
                                        <div class="container-img">
                                            <a href="/php/myhand/frontend/sanpham/detail.php?sp_ma=<?= $sanpham['sp_ma'] ?>">
                                                <img class="bd-placeholder-img card-img-top img-fluid" width="100%" height="350" src="/php/myhand/assets/shared/img/default-image_600.png" />
                                            </a>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="card-body">
                                    <a href="/php/myhand/frontend/sanpham/detail.php?sp_ma=<?= $sanpham['sp_ma'] ?>">
                                        <h5><?= $sanpham['sp_ten'] ?></h5>
                                    </a>
                                    <h6><?= $sanpham['lsp_ten'] ?></h6>
                                    <p class="card-text"><?= $sanpham['sp_mota_ngan'] ?></p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="btn-group">
                                            <a class="btn btn-sm btn-outline-secondary" href="/php/myhand/frontend/sanpham/detail.php?sp_ma=<?= $sanpham['sp_ma'] ?>">Xem chi tiết</a>
                                        </div>
                                        <small class="text-muted text-right">
                                            <s><?= $sanpham['sp_giacu'] ?></s>
                                            <b><?= $sanpham['sp_gia'] ?></b>
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <!-- End block content -->
    </main>

    <!-- footer -->
    <?php include_once(__DIR__ . '/layouts/partials/footer.php'); ?>
    <!-- end footer -->

    <!-- Nhúng file quản lý phần SCRIPT JAVASCRIPT -->
    <?php include_once(__DIR__ . '/layouts/scripts.php'); ?>

    <!-- Các file Javascript sử dụng riêng cho trang này, liên kết tại đây -->
</body>

</html>