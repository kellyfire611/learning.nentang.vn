<?php
// Include file cấu hình ban đầu của `Twig`
require_once __DIR__ . '/../../bootstrap.php';

// Truy vấn database để lấy danh sách
// 1. Include file cấu hình kết nối đến database, khởi tạo kết nối $conn
include_once(__DIR__ . '/../../dbconnect.php');

// Lấy thông tin người dùng gởi đến
$voucher_code = $_POST['voucher_code'];
$checkout_data = $_POST['checkout_data'];

/* --- 
--- Truy vấn dữ liệu thông tin Voucher
--- 
*/
// Câu lệnh SELECT thông tin voucher
$sqlSelectVoucher = <<<EOT
    SELECT *
    FROM shop_vouchers v
    WHERE v.voucher_code = $voucher_code
    LIMIT 1;
EOT;

// Thực thi câu truy vấn SQL để lấy về dữ liệu ban đầu của record 
$resultSelectVoucher = mysqli_query($conn, $sqlSelectVoucher);

// Khi thực thi các truy vấn dạng SELECT, dữ liệu lấy về cần phải phân tích để sử dụng
// Thông thường, chúng ta sẽ sử dụng vòng lặp while để duyệt danh sách các dòng dữ liệu được SELECT
// Ta sẽ tạo 1 mảng array để chứa các dữ liệu được trả về
$dataSelectVoucher = [];
while ($row = mysqli_fetch_array($resultSelectVoucher, MYSQLI_ASSOC)) {
    $dataSelectVoucher = array(
        'id' => $row['id'],
        'voucher_code' => $row['voucher_code'],
        'voucher_name' => $row['voucher_name'],
        'description' => $row['description'],
        'uses' => $row['uses'],
        'max_uses' => $row['max_uses'],
        'max_uses_user' => $row['max_uses_user'],
        'type' => $row['type'],
        'discount_amount' => $row['discount_amount'],
        'is_fixed' => $row['is_fixed'],
        'start_date' => $row['start_date'],
        'end_date' => $row['end_date'],
        'created_at' => $row['created_at'],
        'updated_at' => $row['updated_at'],
        'deleted_at' => $row['deleted_at'],
    );
}
/* --- End Truy vấn dữ liệu Hình ảnh sản phẩm --- */

// Tạo biến trả về kết quả JSON cho Client
$result = [
    'status_code' => 200,
    'msg' => 'Okey'
];

// Kiểm tra Voucher có tồn tại không?
if(empty($dataSelectVoucher)) {
    $result['status_code'] = 500;
    $result['msg'] = 'Không tìm thấy Voucher tồn tại!';
    return json_encode($result);
}

// Kiểm tra Voucher còn hiệu lực không?
$now = date("Y-m-d H:i:s"); // Lấy thời gian hiện tại
if(($dataSelectVoucher['start_date'] <= $now && $now <= $dataSelectVoucher['end_date']) == false) {
    // Không còn hiệu lực Voucher -> báo lỗi cho Client xử lý
    $result['status_code'] = 500;
    $result['msg'] = 'Xin lỗi, Voucher của bạn đã hết thời hạn hiệu lực. Vui lòng thử lại Voucher khác...!!!';
    return json_encode($result);
}

// Kiểm tra Voucher đã sử dụng hết số lần hay chưa?
if($dataSelectVoucher['max_uses'] > 0 && $dataSelectVoucher['uses'] >= $dataSelectVoucher['max_uses']) {
    // Nếu có cấu hình số lượng tối đa được sử dụng Voucher: `max_uses` > 0
    // VÀ hiện tại Voucher này đã được sử dụng hết: `uses` >= `max_uses`
    // => báo lỗi cho Client xử lý
    $result['status_code'] = 500;
    $result['msg'] = 'Xin lỗi, Voucher của bạn đã hết lượt sử dụng. Vui lòng thử lại Voucher khác...!!!';
    return json_encode($result);
}

// Kiểm tra Sản phẩm Khách hàng đang Thanh toán có thuộc Danh mục các Sản phẩm được áp dụng Voucher hay không?

$sql = <<<EOT
    -- Các Khách hàng được áp dụng Voucher
    SELECT *
    FROM shop_vouchers v
    JOIN shop_customer_vouchers cv ON cv.voucher_id = v.id
    WHERE v.voucher_code = $voucher_code;

    -- Các Sản phẩm được áp dụng Voucher
    SELECT *
    FROM shop_vouchers v
    JOIN shop_product_vouchers pv ON pv.voucher_id = v.id;
EOT;

echo json_encode($_SESSION['cartdata']);
