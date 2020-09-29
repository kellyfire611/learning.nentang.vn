<?php
// Hiển thị tất cả lỗi trong PHP
// Chỉ nên hiển thị lỗi khi đang trong môi trường Phát triển (Development)
// Không nên hiển thị lỗi trên môi trường Triển khai (Production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Load các thư viện (packages) do Composer quản lý vào chương trình
require_once __DIR__ . '/../../vendor/autoload.php';

// Sử dụng thư viện PHP Mailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// hàm `session_id()` sẽ trả về giá trị SESSION_ID (tên file session do Web Server tự động tạo)
// - Nếu trả về Rỗng hoặc NULL => chưa có file Session tồn tại
if (session_id() === '') {
    // Yêu cầu Web Server tạo file Session để lưu trữ giá trị tương ứng với CLIENT (Web Browser đang gởi Request)
    session_start();
}

// Đã người dùng chưa đăng nhập -> hiển thị thông báo yêu cầu người dùng đăng nhập
if (!isset($_SESSION['kh_tendangnhap_logged']) || empty($_SESSION['kh_tendangnhap_logged'])) {
    echo 'Vui lòng Đăng nhập trước khi Thanh toán! <a href="/php/myhand/backend/auth/login.php">Click vào đây để đến trang Đăng nhập</a>';
    die;
} else {
    // Nếu giỏ hàng trong session rỗng, return
    if (!isset($_SESSION['giohangdata']) || empty($_SESSION['giohangdata'])) {
        echo 'Giỏ hàng rỗng. Vui lòng chọn Sản phẩm trước khi Thanh toán!';
        die;
    }

    // Nếu đã đăng nhập, tạo Đơn hàng
    // Truy vấn database
    // 1. Include file cấu hình kết nối đến database, khởi tạo kết nối $conn
    include_once(__DIR__ . '/../../dbconnect.php');

    /* --- 
    --- 2.Truy vấn dữ liệu Khách hàng 
    --- Lấy giá trị khóa chính được truyền theo dạng QueryString Parameter key1=value1&key2=value2...
    --- 
    */
    $kh_tendangnhap = $_SESSION['kh_tendangnhap_logged'];
    // var_dump($kh_tendangnhap);die;
    $sqlSelectKhachHang = <<<EOT
        SELECT *
        FROM `khachhang` kh
        WHERE kh.kh_tendangnhap = '$kh_tendangnhap'
EOT;
    // var_dump($sqlSelectKhachHang);die;

    // Thực thi câu truy vấn SQL để lấy về dữ liệu ban đầu của record 
    $resultSelectKhachHang = mysqli_query($conn, $sqlSelectKhachHang);

    // Khi thực thi các truy vấn dạng SELECT, dữ liệu lấy về cần phải phân tích để sử dụng
    // Thông thường, chúng ta sẽ sử dụng vòng lặp while để duyệt danh sách các dòng dữ liệu được SELECT
    // Ta sẽ tạo 1 mảng array để chứa các dữ liệu được trả về
    $khachhangRow;
    while ($row = mysqli_fetch_array($resultSelectKhachHang, MYSQLI_ASSOC)) {
        $khachhangRow = array(
            'kh_tendangnhap' => $row['kh_tendangnhap'],
            'kh_ten' => $row['kh_ten'],
            'kh_email' => $row['kh_email'],
            'kh_diachi' => $row['kh_diachi'],
        );
    }
    /* --- End Truy vấn dữ liệu Khách hàng --- */

    // Thông tin đơn hàng
    $dh_ngaylap = date('Y-m-d'); // Lấy ngày hiện tại theo định dạng yyyy-mm-dd
    $dh_ngaygiao = date('Y-m-d');
    $dh_noigiao = '';
    $dh_trangthaithanhtoan = 0; // Mặc định là 0 chưa thanh toán
    $httt_ma = 1; // Mặc định là 1

    // 2. Thực hiện câu lệnh Tạo mới (INSERT) Đơn hàng
    // Câu lệnh INSERT
    $sqlInsertDonHang = <<<EOT
    INSERT INTO `dondathang` (`dh_ngaylap`, `dh_ngaygiao`, `dh_noigiao`, `dh_trangthaithanhtoan`, `httt_ma`, `kh_tendangnhap`) 
        VALUES ('$dh_ngaylap', '$dh_ngaygiao', N'$dh_noigiao', '$dh_trangthaithanhtoan', '$httt_ma', '$kh_tendangnhap');
EOT;
    // print_r($sqlInsertDonHang); die;

    // Thực thi INSERT Đơn hàng
    mysqli_query($conn, $sqlInsertDonHang);

    // 3. Lấy ID Đơn hàng mới nhất vừa được thêm vào database
    // Do ID là tự động tăng (PRIMARY KEY và AUTO INCREMENT), nên chúng ta không biết được ID đă tăng đến số bao nhiêu?
    // Cần phải sử dụng biến `$conn->insert_id` để lấy về ID mới nhất
    // Nếu thực thi câu lệnh INSERT thành công thì cần lấy ID mới nhất của Đơn hàng để làm khóa ngoại trong Chi tiết đơn hàng
    $dh_ma = $conn->insert_id;
    // var_dump($dh_ma);die;

    // Thông tin các dòng chi tiết đơn hàng
    $giohangdata = $_SESSION['giohangdata'];

    // 4. Duyệt vòng lặp qua mảng các dòng Sản phẩm của chi tiết đơn hàng được gởi đến qua request POST
    foreach ($giohangdata as $item) {
        // 4.1. Chuẩn bị dữ liệu cho câu lệnh INSERT vào table `sanpham_dondathang`
        $sp_ma = $item['sp_ma'];
        $sp_dh_soluong = $item['soluong'];
        $sp_dh_dongia = $item['gia'];

        // 4.2. Câu lệnh INSERT
        $sqlInsertSanPhamDonDatHang = <<<EOT
        INSERT INTO `sanpham_dondathang` (`sp_ma`, `dh_ma`, `sp_dh_soluong`, `sp_dh_dongia`) 
            VALUES ($sp_ma, $dh_ma, $sp_dh_soluong, $sp_dh_dongia);
EOT;

        // 4.3. Thực thi INSERT
        mysqli_query($conn, $sqlInsertSanPhamDonDatHang);
    }

    // 5. Gởi mail thông báo cho khách hàng về Đơn hàng đã đặt
    $mail = new PHPMailer(true);                                // Passing `true` enables exceptions
    try {
        //Server settings
        $mail->SMTPDebug = 2;                                   // Enable verbose debug output
        $mail->isSMTP();                                        // Set mailer to use SMTP
        $mail->Host = 'smtp.gmail.com';                         // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                                 // Enable SMTP authentication
        $mail->Username = 'hotro.nentangtoituonglai@gmail.com'; // SMTP username
        $mail->Password = 'yjkkdiyfjwksktot';                   // SMTP password
        $mail->SMTPSecure = 'tls';                              // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 587;                                      // TCP port to connect to
        $mail->CharSet = "UTF-8";
        // Bật chế bộ tự mình mã hóa SSL
        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );
        //Recipients
        $mail->setFrom('hotro.nentangtoituonglai@gmail.com', 'Mail Liên hệ');
        $mail->addAddress($khachhangRow['kh_email']);               // Add a recipient
        // $mail->addCC('cc@example.com');
        // $mail->addBCC('bcc@example.com');
        //Attachments
        // $mail->addAttachment('/var/tmp/file.tar.gz');        // Add attachments
        // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');   // Optional name
        //Content
        $mail->isHTML(true);                                    // Set email format to HTML

        // Tiêu đề Mail
        $mail->Subject = "[Có đơn hàng vừa thanh toán] - Mã đơn hàng $dh_ma";

        // Nội dung Mail
        // Lưu ý khi thiết kế Mẫu gởi mail
        // - Chỉ nên sử dụng TABLE, TR, TD, và các định dạng cơ bản của CSS để thiết kế
        // - Các đường link/hình ảnh có sử dụng trong mẫu thiết kế MAIL phải là đường dẫn WEB có thật, ví dụ như logo,banner,...
        $templateDonHang = '<ul>';
        $templateDonHang .= '<li>Họ tên khách hàng: ' . $khachhangRow['kh_ten'] . '</li>';
        $templateDonHang .= '<li>Địa chỉ khách hàng: ' . $khachhangRow['kh_diachi'] . '</li>';
        $templateDonHang .= '<ul>';

        $stt = 1;
        $templateChiTietDonHang = '<table border="1" width="100%">';
        $templateChiTietDonHang .= '<tr>';
        $templateChiTietDonHang .= '<td>STT</td>';
        $templateChiTietDonHang .= '<td>Sản phẩm</td>';
        $templateChiTietDonHang .= '<td>Số lượng</td>';
        $templateChiTietDonHang .= '<td>Giá</td>';
        $templateChiTietDonHang .= '<td>Thành tiền</td>';
        $templateChiTietDonHang .= '</tr>';
        foreach ($giohangdata as $item) {
            $templateChiTietDonHang .= '<tr>';
            $templateChiTietDonHang .= '<td>' . $stt . '</td>';
            $templateChiTietDonHang .= '<td>' . $item['sp_ten'] . '</td>';
            $templateChiTietDonHang .= '<td>' . $item['soluong'] . '</td>';
            $templateChiTietDonHang .= '<td>' . $item['gia'] . '</td>';
            $templateChiTietDonHang .= '<td>' . ($item['soluong'] * $item['gia']) . '</td>';
            $templateChiTietDonHang .= '</tr>';
            $stt++;
        }
        $templateChiTietDonHang .= '</table>';

        $body = <<<EOT
            <table border="1" width="100%">
                <tr>
                    <td colspan="2">
                        <img src="http://learning.nentang.vn/php/myhand/assets/shared/img/logo-nentang.jpg" style="width: 100px; height: 100px; border: 1px solid red;" />
                    </td>
                </tr>
                <tr>
                    <td>Có Đơn hàng vừa thanh toán</td>
                    <td>
                        <h2>Thông tin đơn hàng</h2>
                        $templateDonHang

                        <h2>Chi tiết đơn hàng</h2>
                        $templateChiTietDonHang
                    </td>
                </td>
            </table>
EOT;
        $mail->Body    = $body;
        $mail->send();
    } catch (Exception $e) {
        echo 'Lỗi khi gởi mail: ', $mail->ErrorInfo;
    }

    // 5. Thực thi hoàn tất, điều hướng về trang Danh sách
    // Hủy dữ liệu giỏ hàng trong session
    unset($_SESSION['giohangdata']);
    echo 'Đặt hàng thành công. <a href="/php/myhand/frontend/">Bấm vào đây để quay về trang chủ</a>';
}
