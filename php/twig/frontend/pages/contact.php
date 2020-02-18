<?php
// Include file cấu hình ban đầu của `Twig`
require_once __DIR__.'/../../bootstrap.php';

// Truy vấn database để lấy danh sách
// 1. Include file cấu hình kết nối đến database, khởi tạo kết nối $conn
include_once(__DIR__.'/../../dbconnect.php');

// Include thư viện các hàm tiện ích
include_once(__DIR__.'/../../app/helpers.php');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if(!isset($_POST['btnGoiLoiNhan']))
{
    // Yêu cầu `Twig` vẽ giao diện được viết trong file `frontend/pages/contact.html.twig`
    // với dữ liệu truyền vào file giao diện được đặt tên
    echo $twig->render('frontend/pages/contact.html.twig');
    return;
}

// Lấy dữ liệu người dùng hiệu chỉnh gởi từ REQUEST POST
$email = $_POST['email'];
$title = $_POST['title'];
$message = $_POST['message'];

// Gởi mail kích hoạt tài khoản
$mail = new PHPMailer(true);                               // Passing `true` enables exceptions
try {
    //Server settings
    //$mail->SMTPDebug = 2;                                // Enable verbose debug output
    $mail->isSMTP();                                       // Set mailer to use SMTP
    $mail->Host = 'smtp.gmail.com';                        // Specify main and backup SMTP servers
    $mail->SMTPAuth = true;                                // Enable SMTP authentication
    $mail->Username = 'tester.mail.nentang@gmail.com';     // SMTP username
    $mail->Password = 'cumauahhoyahpxzl';                  // SMTP password
    $mail->SMTPSecure = 'tls';                             // Enable TLS encryption, `ssl` also accepted
    $mail->Port = 587;                                     // TCP port to connect to
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
    $mail->setFrom('tester.mail.nentang@gmail.com', 'Test Mail');
    $mail->addAddress('phucuong@ctu.edu.vn');                // Add a recipient
    $mail->addReplyTo($email);
    // $mail->addCC('cc@example.com');
    // $mail->addBCC('bcc@example.com');

    //Attachments
    // $mail->addAttachment('/var/tmp/file.tar.gz');        // Add attachments
    // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');   // Optional name

    //Content
    $mail->isHTML(true);                                    // Set email format to HTML
    $mail->Subject = "[Có người liên hệ] - $title";
    $siteUrl = siteURL();
    $body = <<<EOT
    Có người liên hệ cần giúp đỡ. <br />
    Email của khách: $email <br />
    Nội dung: <br />
    $message
EOT;
    $mail->Body    = $body;

    $mail->send();
} catch (Exception $e) {
    echo 'Lỗi khi gởi mail: ', $mail->ErrorInfo;
}

// Sau khi cập nhật dữ liệu, tự động điều hướng về trang Đăng ký thành công
// header("location:register-success.php?kh_tendangnhap=$kh_tendangnhap");