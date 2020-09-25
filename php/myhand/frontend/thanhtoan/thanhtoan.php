<?php
// Load các thư viện (packages) do Composer quản lý vào chương trình
require_once __DIR__.'/../../vendor/autoload.php';

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
    // Nếu đã đăng nhập, tạo Đơn hàng
    // 1. Phân tách lấy dữ liệu người dùng gởi từ REQUEST POST
    // Thông tin đơn hàng
    $kh_tendangnhap = $_SESSION['kh_tendangnhap_logged'];
    $dh_ngaylap = date('Y-m-d'); // Lấy ngày hiện tại theo định dạng yyyy-mm-dd
    $dh_ngaygiao = '';
    $dh_noigiao = '';
    $dh_trangthaithanhtoan = 0; // Mặc định là 0 chưa thanh toán
    $httt_ma = 1; // Mặc định là 1

    // Thông tin các dòng chi tiết đơn hàng
    $arr_sp_ma = $_POST['sp_ma'];                   // mảng array do đặt tên name="sp_ma[]"
    $arr_sp_dh_soluong = $_POST['sp_dh_soluong'];   // mảng array do đặt tên name="sp_dh_soluong[]"
    $arr_sp_dh_dongia = $_POST['sp_dh_dongia'];     // mảng array do đặt tên name="sp_dh_dongia[]"
    // var_dump($sp_ma);die;

    // 2. Thực hiện câu lệnh Tạo mới (INSERT) Đơn hàng
    // Câu lệnh INSERT
    $sqlInsertDonHang = <<<EOT
    INSERT INTO `dondathang` (`dh_ngaylap`, `dh_ngaygiao`, `dh_noigiao`, `dh_trangthaithanhtoan`, `httt_ma`, `kh_tendangnhap`) 
        VALUES ('$dh_ngaylap', '$dh_ngaygiao', N'$dh_noigiao', '$dh_trangthaithanhtoan', '$httt_ma', '$kh_tendangnhap')";
EOT;
    // print_r($sql); die;

    // Thực thi INSERT Đơn hàng
    mysqli_query($conn, $sqlInsertDonHang);

    // 3. Lấy ID Đơn hàng mới nhất vừa được thêm vào database
    // Do ID là tự động tăng (PRIMARY KEY và AUTO INCREMENT), nên chúng ta không biết được ID đă tăng đến số bao nhiêu?
    // Cần phải sử dụng biến `$conn->insert_id` để lấy về ID mới nhất
    // Nếu thực thi câu lệnh INSERT thành công thì cần lấy ID mới nhất của Đơn hàng để làm khóa ngoại trong Chi tiết đơn hàng
    $dh_ma = $conn->insert_id;

    // 4. Duyệt vòng lặp qua mảng các dòng Sản phẩm của chi tiết đơn hàng được gởi đến qua request POST
    for ($i = 0; $i < count($arr_sp_ma); $i++) {
        // 4.1. Chuẩn bị dữ liệu cho câu lệnh INSERT vào table `sanpham_dondathang`
        $sp_ma = $arr_sp_ma[$i];
        $sp_dh_soluong = $arr_sp_dh_soluong[$i];
        $sp_dh_dongia = $arr_sp_dh_dongia[$i];

        // 4.2. Câu lệnh INSERT
        $sqlInsertSanPhamDonDatHang = <<<EOT
        INSERT INTO `sanpham_dondathang` (`sp_ma`, `dh_ma`, `sp_dh_soluong`, `sp_dh_dongia`) 
            VALUES ($sp_ma, $dh_ma, $sp_dh_soluong, $sp_dh_dongia)";
EOT;

        // 4.3. Thực thi INSERT
        mysqli_query($conn, $sqlInsertSanPhamDonDatHang);
    }

    // 5. Gởi mail thông báo cho khách hàng về Đơn hàng đã đặt
    if (isset($_POST['btnGoiLoiNhan'])) {
        // Lấy dữ liệu người dùng hiệu chỉnh gởi từ REQUEST POST
        $email = $_POST['email'];
        $title = $_POST['title'];
        $message = $_POST['message'];

        // Gởi mail kích hoạt tài khoản
        $mail = new PHPMailer(true);                                // Passing `true` enables exceptions
        try {
            //Server settings
            // $mail->SMTPDebug = 2;                                   // Enable verbose debug output
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
            $mail->addAddress('phucuong@ctu.edu.vn');               // Add a recipient
            $mail->addReplyTo($email);
            // $mail->addCC('cc@example.com');
            // $mail->addBCC('bcc@example.com');
            //Attachments
            // $mail->addAttachment('/var/tmp/file.tar.gz');        // Add attachments
            // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');   // Optional name
            //Content
            $mail->isHTML(true);                                    // Set email format to HTML
            // Tiêu đề Mail
            $mail->Subject = "[Có người liên hệ] - $title";         
            // Nội dung Mail
            // Lưu ý khi thiết kế Mẫu gởi mail
            // - Chỉ nên sử dụng TABLE, TR, TD, và các định dạng cơ bản của CSS để thiết kế
            // - Các đường link/hình ảnh có sử dụng trong mẫu thiết kế MAIL phải là đường dẫn WEB có thật, ví dụ như logo,banner,...
            $body = <<<EOT
            <table border="1" width="100%">
                <tr>
                    <td colspan="2">
                        <img src="http://learning.nentang.vn/php/myhand/assets/shared/img/logo-nentang.jpg" style="width: 100px; height: 100px; border: 1px solid red;" />
                    </td>
                </tr>
                <tr>
                    <td>Có người liên hệ cần giúp đỡ. <br /></td>
                    <td>
                        Email của khách: $email <br />
                        Nội dung: <br />
                        $message
                    </td>
                </td>
            </table>
    EOT;
            $mail->Body    = $body;
            $mail->send();
        } catch (Exception $e) {
            echo 'Lỗi khi gởi mail: ', $mail->ErrorInfo;
        }
    }

    // 5. Thực thi hoàn tất, điều hướng về trang Danh sách
    echo '<script>location.href = "index.php";</script>';
}
