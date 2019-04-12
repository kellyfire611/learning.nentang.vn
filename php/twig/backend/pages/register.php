<?php
// Include file cấu hình ban đầu của `Twig`
require_once __DIR__.'/../../bootstrap.php';

// Truy vấn database
// 1. Include file cấu hình kết nối đến database, khởi tạo kết nối $conn
include_once(__DIR__.'/../../dbconnect.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// 2. Nếu người dùng có bấm nút Đăng ký thì thực thi câu lệnh INSERT
if(isset($_POST['btnDangKy'])) 
{
    // Lấy dữ liệu người dùng hiệu chỉnh gởi từ REQUEST POST
    $kh_tendangnhap = $_POST['kh_tendangnhap'];
    $kh_matkhau = $_POST['kh_matkhau'];
    $kh_ten = $_POST['kh_ten'];
    $kh_gioitinh = $_POST['kh_gioitinh'];
    $kh_diachi = $_POST['kh_diachi'];
    $kh_dienthoai = $_POST['kh_dienthoai'];
    $kh_email = $_POST['kh_email'];
    $kh_ngaysinh = $_POST['kh_ngaysinh'];
    $kh_thangsinh = $_POST['kh_thangsinh'];
    $kh_namsinh = $_POST['kh_namsinh'];
    $kh_cmnd = $_POST['kh_cmnd'];

    // Sử dụng Mã hóa MD5 hoặc SHA1 để mã hóa chuỗi
    // echo md5(time());
    // Output: 447c13ce896b820f353bec47248675b3
    // echo sha1(time());
    // Output: 6c2cef9fe21832a232da7386e4775654b77c7797
    $kh_makichhoat = sha1(time());
    $kh_trangthai = 0; // Mặc định khi đăng ký sẽ chưa kích hoạt tài khoản
    $kh_quantri = 0; // Mặc định khi đăng ký sẽ không có quyền quản trị

    // Câu lệnh INSERT
    $sql = "INSERT INTO khachhang(kh_tendangnhap, kh_matkhau, kh_ten, kh_gioitinh, kh_diachi, kh_dienthoai, kh_email, kh_ngaysinh, kh_thangsinh, kh_namsinh, kh_cmnd, kh_makichhoat, kh_trangthai, kh_quantri) VALUES ('$kh_tendangnhap', '$kh_matkhau', '$kh_ten', $kh_gioitinh, '$kh_diachi', '$kh_dienthoai', '$kh_email', $kh_ngaysinh, $kh_thangsinh, $kh_namsinh, '$kh_cmnd', '$kh_makichhoat', $kh_trangthai, $kh_quantri)";

    // Thực thi SELECT
    $result = mysqli_query($conn, $sql);

    // Đóng kết nối
    mysqli_close($conn);

    // Gởi mail kích hoạt tài khoản
    $mail = new PHPMailer(true);                              // Passing `true` enables exceptions
    try {
        //Server settings
        $mail->SMTPDebug = 2;                                 // Enable verbose debug output
        $mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->Host = 'smtp.gmail.com';                       // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = 'tester.mail.nentang@gmail.com';    // SMTP username
        $mail->Password = 'wpmhdjierkdngniu';                 // SMTP password
        $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 587;                                    // TCP port to connect to
    
        //Recipients
        $mail->setFrom('tester.mail.nentang@gmail.com', 'Test Mail');
        $mail->addAddress($kh_email);                         // Add a recipient
        $mail->addReplyTo('tester.mail.nentang@gmail.com', 'Test Mail');
        // $mail->addCC('cc@example.com');
        // $mail->addBCC('bcc@example.com');
    
        //Attachments
        // $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
        // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
    
        //Content
        $mail->isHTML(true);                                  // Set email format to HTML
        $mail->Subject = 'Thông báo kích hoạt tài khoản';
        $mail->Body    = 'This is the HTML message body <b>in bold!</b>';
        $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
    
        $mail->send();
    } catch (Exception $e) {
        echo 'Lỗi khi gởi mail: ', $mail->ErrorInfo;
    }

    // Sau khi cập nhật dữ liệu, tự động điều hướng về trang Đăng ký thành công
    //header("location:register-success.php?kh_tendangnhap=$kh_tendangnhap");
}

// Yêu cầu `Twig` vẽ giao diện được viết trong file `backend/pages/register.html.twig`
echo $twig->render('backend/pages/register.html.twig');