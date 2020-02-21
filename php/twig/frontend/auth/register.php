<?php
// Include file cấu hình ban đầu của `Twig`
require_once __DIR__ . '/../../bootstrap.php';

// Truy vấn database
// 1. Include file cấu hình kết nối đến database, khởi tạo kết nối $conn
include_once(__DIR__ . '/../../dbconnect.php');

// Include thư viện các hàm tiện ích
include_once(__DIR__ . '/../../app/helpers.php');

// Các class thư viện gởi mail
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


// 2. Người dùng mới truy cập trang lần đầu tiên (người dùng chưa gởi dữ liệu `btnRegister` - chưa nhấn `nút Đăng ký`) về Server
// có nghĩa là biến $_POST['btnRegister'] chưa được khởi tạo hoặc chưa có giá trị
// => hiển thị Form nhập liệu
if (!isset($_POST['btnRegister'])) {
    // Yêu cầu `Twig` vẽ giao diện được viết trong file `frontend/auth/register.html.twig`
    echo $twig->render('frontend/auth/register.html.twig');
    return;
}

// 2. Nếu người dùng có bấm nút Đăng ký thì thực thi câu lệnh INSERT
// Lấy dữ liệu người dùng hiệu chỉnh gởi từ REQUEST POST
$username = $_POST['username'];
$password = sha1($_POST['password']);                           // Mã hóa SHA1
$password_confirmation = sha1($_POST['password_confirmation']); // Mã hóa SHA1
$last_name = $_POST['last_name'];
$first_name = $_POST['first_name'];
$gender = $_POST['gender'];
$email = $_POST['email'];
$birthday = $_POST['birthday'];
$code = $_POST['code'];
$company = $_POST['company'];
$phone = $_POST['phone'];
$billing_address = $_POST['billing_address'];
$shipping_address = $_POST['shipping_address'];
$city = $_POST['city'];
$state = $_POST['state'];
$postal_code = $_POST['postal_code'];
$country = $_POST['country'];

// Tạo biến lỗi để chứa thông báo lỗi
$errors = [];

// --- Kiểm tra Tên tài khoản
// required (bắt buộc nhập <=> không được rỗng)
if (empty($username)) {
    $errors['username'][] = [
        'rule' => 'required',
        'rule_value' => true,
        'value' => $username,
        'msg' => 'Vui lòng nhập tên Tài khoản'
    ];
}
// minlength 3 (tối thiểu 3 ký tự)
if (!empty($username) && strlen($username) < 3) {
    $errors['username'][] = [
        'rule' => 'minlength',
        'rule_value' => 3,
        'value' => $username,
        'msg' => 'Tên tài khoản cấp phải có ít nhất 3 ký tự'
    ];
}
// maxlength 50 (tối đa 50 ký tự)
if (!empty($username) && strlen($username) > 50) {
    $errors['username'][] = [
        'rule' => 'maxlength',
        'rule_value' => 50,
        'value' => $username,
        'msg' => 'Tên tài khoản không được vượt quá 50 ký tự'
    ];
}

// Kiểm tra Mật khẩu
// required (bắt buộc nhập <=> không được rỗng)
if (empty($password)) {
    $errors['password'][] = [
        'rule' => 'required',
        'rule_value' => true,
        'value' => $password,
        'msg' => 'Vui lòng nhập Mật khẩu'
    ];
}

// --- Kiểm tra Mật khẩu xác nhận
// required (bắt buộc nhập <=> không được rỗng)
if (empty($password_confirmation)) {
    $errors['password_confirmation'][] = [
        'rule' => 'required',
        'rule_value' => true,
        'value' => $password_confirmation,
        'msg' => 'Vui lòng nhập Mật khẩu xác nhận'
    ];
}

// --- Kiểm tra Mật khẩu và Mật khẩu xác nhận phải giống nhau
if ($password != $password_confirmation) {
    $errors['compare_password_vs_password_confirmation'][] = [
        'rule' => 'required',
        'rule_value' => true,
        'value' => $password . ' <->' . $password_confirmation,
        'msg' => 'Mật khẩu và Mật khẩu xác nhận không giống nhau. Vui lòng kiểm tra lại.'
    ];
}

// --- Validate Ngày sinh
// required (bắt buộc nhập <=> không được rỗng)
if (empty($birthday)) {
    $errors['birthday'][] = [
        'rule' => 'required',
        'rule_value' => true,
        'value' => $birthday,
        'msg' => 'Vui lòng nhập Ngày sinh'
    ];
}

// --- Kiểm tra Upload avatar
$avatar_file_path = 'NULL';
// Nếu người dùng có chọn file để upload và file đã được upload lên thư mục tạm (temp) của Server thành công
if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] == 0) {
    // Đối với mỗi file được upload, sẽ có các thuộc tính như sau:
    // $_FILES['avatar']['name']     : Tên của file chúng ta upload
    // $_FILES['avatar']['type']     : Kiểu file mà chúng ta upload (hình ảnh, word, excel, pdf, txt, ...)
    // $_FILES['avatar']['tmp_name'] : Đường dẫn đến file tạm trên web server
    // $_FILES['avatar']['error']    : Trạng thái của file chúng ta upload, 0 => không có lỗi
    // $_FILES['avatar']['size']     : Kích thước của file chúng ta upload

    // Lấy thông tin file
    $fileName = $_FILES['avatar']['name'];
    $fileType = $_FILES["avatar"]["type"];
    $fileSize = $_FILES["avatar"]["size"];
    $filePathInfo = pathinfo($fileName);
    // echo $filePathInfo['dirname'] . '<br/>';   // Returns folder/directory
    // echo $filePathInfo['basename'] . '<br/>';  // Returns file.html
    // echo $filePathInfo['extension'] . '<br/>'; // Returns .html
    // echo $filePathInfo['filename'] . '<br/>';  // Returns file

    // Danh sách MIME type được phép upload lên Server
    // Mime Type là cho phép ấn định định dạng mở file cho trình duyệt.
    // VD bạn có upload lên 1 file đó đuôi là: .ttg và bạn muốn để trình duyệt khi truy cập file này thay vì download nó về thì mở trực tiếp trên trình duyệt xem như file text thì bạn điền:
    // Handler: text/plain
    // Extension: ttg
    // https://developer.mozilla.org/en-US/docs/Web/HTTP/Basics_of_HTTP/MIME_types/Complete_list_of_MIME_types
    $allowed = array("jpg" => "image/jpg", "jpeg" => "image/jpeg", "gif" => "image/gif", "png" => "image/png");

    // Kiểm tra đuôi file. Chỉ chấp nhận file ảnh (*.jpg|*.jpeg|*.gif|*.png)
    $ext = $filePathInfo['extension'];
    if(!array_key_exists($ext, $allowed)) {
        $errors['avatar'][] = [
            'rule' => 'file_upload_extension_allowed',
            'rule_value' => true,
            'value' => $ext,
            'msg' => 'Chỉ chấp nhận file ảnh (*.jpg|*.jpeg|*.gif|*.png). Vui lòng kiểm tra lại.'
        ];
    }

    // Kiểm tra dung lượng file upload - 5MB tối đa
    $maxsize = 5 * 1024 * 1024;
    if($fileSize > $maxsize) {
        $errors['avatar'][] = [
            'rule' => 'file_upload_max_size',
            'rule_value' => true,
            'value' => $fileSize,
            'msg' => 'Chỉ chấp nhận file tối đa 5Mb. Vui lòng kiểm tra lại.'
        ];
    }

    // Kiểm tra MIME type của file upload
    if(!in_array($fileType, $allowed)){
        $errors['avatar'][] = [
            'rule' => 'file_upload_mime_type_allowed',
            'rule_value' => true,
            'value' => $fileType,
            'msg' => 'Chỉ chấp nhận các MIME type (image/jpg|image/jpeg|image/gif|image/png). Vui lòng kiểm tra lại.'
        ];
    }
}

// Thông báo lỗi cụ thể người dùng mắc phải (nếu vi phạm bất kỳ quy luật kiểm tra ràng buộc)
// dd($errors);
if (!empty($errors)) {
    // Yêu cầu `Twig` vẽ giao diện được viết trong file `backend/auth/register.html.twig`
    // kèm theo dữ liệu thông báo lỗi
    echo $twig->render('frontend/auth/register.html.twig', [
        'errors' => $errors,
        'username_oldvalue' => $username,
        'last_name_oldvalue' => $last_name,
        'first_name_oldvalue' => $first_name,
        'gender_oldvalue' => $gender,
        'email_oldvalue' => $email,
        'birthday_oldvalue' => $birthday,
        'code_oldvalue' => $code,
        'company_oldvalue' => $company,
        'phone_oldvalue' => $phone,
        'billing_address_oldvalue' => $billing_address,
        'shipping_address_oldvalue' => $shipping_address,
        'city_oldvalue' => $city,
        'state_oldvalue' => $state,
        'postal_code_oldvalue' => $postal_code,
        'country_oldvalue' => $country,
    ]);
    return;
}

// Nếu người dùng có chọn file để upload và file đã được upload lên thư mục tạm (temp) của Server thành công
if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] == 0) {
    // Đường dẫn để chứa thư mục upload trên ứng dụng web của chúng ta. Các bạn có thể tùy chỉnh theo ý các bạn.
    // Ví dụ: các file upload sẽ được lưu vào thư mục ../../../assets/uploads/customers/avatars/
    $base_upload_dir = __DIR__ . "../../../assets/uploads/";
    $custom_upload_dir = "customers/avatars/";
    $upload_dir = $base_upload_dir . $custom_upload_dir;

    // Nếu thư mục chứa các file upload không tồn tại -> thì tạo mới thư mục
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0777, true); // Tạo thư mục và cấp quyền 0777
    }

    // Tên file lưu trên server sẽ được ghép thêm YYYYMMDDHHIISS
    // Ví dụ:
    // Tên file người dùng upload: hoahong.jpg
    // Tên lưu trên server      -> hoahong_20200222083030.jpg
    $avatar_file_name = $filePathInfo['filename'] . '_' . date('YmdHis') . '.' . $filePathInfo['extension'];

    // Tiến hành di chuyển file từ thư mục tạm trên server vào thư mục chúng ta muốn chứa các file uploads
    // Ví dụ: move file từ C:\xampp\tmp\php6091.tmp -> C:/xampp/htdocs/learning.nentang.vn/assets/uploads/hoahong_20200222083030.jpg
    move_uploaded_file($_FILES['avatar']['tmp_name'], $upload_dir . $avatar_file_name);

    // Lấy đường dẫn + tên file lưu vào trong database
    // 'customers/avatars/kellyfire_20200217161335.jpg'
    $avatar_file_path = '\'' . $custom_upload_dir . $avatar_file_name . '\'';
}

// Lấy thời gian hiện tại (yyyymmddHHmmss) và sử dụng Mã hóa SHA1 để mã hóa chuỗi Mã kích hoạt
// echo sha1(time());
// Output: 6c2cef9fe21832a232da7386e4775654b77c7797
$timeNow = time();
$timeNowEncrypted = sha1(time());
$activate_code = $timeNowEncrypted;

$status = 0; // Mặc định khi đăng ký sẽ chưa kích hoạt tài khoản (#0: chưa kích hoạt; #1: đã kích hoạt)
$created_at = date('Y-m-d H:i:s'); // Lấy ngày giờ hiện tại theo định dạng `Năm-Tháng-Ngày Giờ-Phút-Giây`. Vd: 2020-02-18 09:12:12
$updated_at = NULL;

// Câu lệnh INSERT
$sql = <<<EOT
    INSERT INTO shop_customers(username, password, last_name, first_name, gender, email, birthday, code, avatar, company, phone, billing_address, shipping_address, city, state, postal_code, country, remember_token, activate_code, `status`, created_at, updated_at)
    VALUES ('$username', '$password', '$last_name', '$first_name', $gender, '$email', '$birthday', '$code', $avatar_file_path, '$company', '$phone', '$billing_address', '$shipping_address', '$city', '$state', '$postal_code', '$country', NULL, '$activate_code', $status, '$created_at', NULL)
EOT;

// Thực thi INSERT
$result = mysqli_query($conn, $sql) or die("<b>Có lỗi khi thực thi câu lệnh SQL: </b>" . mysqli_error($conn) . "<br /><b>Câu lệnh vừa thực thi:</b></br>$sql");

// Đóng kết nối
mysqli_close($conn);

// Gởi mail kích hoạt tài khoản
$mail = new PHPMailer(true);                               // Passing `true` enables exceptions
try {
    //Server settings
    //$mail->SMTPDebug = 2;                                // Enable verbose debug output
    $mail->isSMTP();                                       // Set mailer to use SMTP
    $mail->Host = 'smtp.gmail.com';                        // Specify main and backup SMTP servers
    $mail->SMTPAuth = true;                                // Enable SMTP authentication
    $mail->Username = 'hotro.nentangtoituonglai@gmail.com';// SMTP username
    $mail->Password = 'apmcxgzjndlbjybj';                  // SMTP password
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
    $mail->setFrom('hotro.nentangtoituonglai@gmail.com', 'Mail từ Hệ thống Nền Tảng');
    $mail->addAddress($email);                          // Add a recipient
    $mail->addReplyTo('phucuong@ctu.edu.vn', 'Người quản trị Website');
    // $mail->addCC('cc@example.com');
    // $mail->addBCC('bcc@example.com');

    //Attachments
    // $mail->addAttachment('/var/tmp/file.tar.gz');        // Add attachments
    // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');   // Optional name

    //Content
    $mail->isHTML(true);                                    // Set email format to HTML
    $mail->Subject = 'Thông báo kích hoạt tài khoản';
    // Sử dụng hàm trong HELPER để lấy full đường dẫn đang cấu hình
    // Ví dụ: http://localhost:8000/
    $siteUrl = siteURL();
    $body = <<<EOT
    <table>
        <tr>
            <td style="text-align: center;">
                <h1 style="color: red; font-size: 16px; text-align: center;">NỀN TẢNG - HÀNH TRANG TỚI TƯƠNG LAI</h1>
                <img src="https://nentang.vn/wp-content/uploads/2019/06/logo-nentang.jpg" width="150px" height="150px" />
            </td>
        </tr>
        <tr>
            <td>
                Xin chào $first_name, cám ơn bạn đã đăng ký Hệ thống của chúng tôi. Vui lòng click vào liên kết sau để kích hoạt tài khoản!
                <a href="$siteUrl/frontend/auth/activate-user.php?email=$email&activate_code=$activate_code">Kích hoạt tài khoản</a>
            </td>
        </tr>
        <tr>
            <td>
                <ul>
                    <li>Liên hệ chúng tôi qua <a href="+84915659223">0915-659-223</a></li>
                    <li>Liên hệ chúng tôi qua <a href="mailto:phucuong@ctu.edu.vn">phucuong@ctu.edu.vn</a></li>
                    <li>Liên hệ chúng tôi qua <a href="http://m.me/nentangkienthuc">Facebook</a></li>
                    <li>Liên hệ chúng tôi qua <a href="http://zalo.me/2463423006009622944">Zalo</a></li>
                </ul>
            </td>
        </tr>
    </table>
EOT;
    $mail->Body    = $body;

    // Gởi mail
    $mail->send();
} catch (Exception $e) {
    echo 'Lỗi khi gởi mail: ', $mail->ErrorInfo;
}

// Sau khi cập nhật dữ liệu, và gởi mail thành công -> tự động điều hướng về trang Đăng ký thành công
header("location:register-success.php?email=$email");
