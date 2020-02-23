<?php
// Include file cấu hình ban đầu của `Twig`
require_once __DIR__ . '/../../bootstrap.php';

// Truy vấn database
// 1. Include file cấu hình kết nối đến database, khởi tạo kết nối $conn
include_once(__DIR__ . '/../../dbconnect.php');

// Include thư viện các hàm tiện ích
include_once(__DIR__.'/../../app/helpers.php');

/* --- 
   --- 2.Truy vấn dữ liệu Sản phẩm 
   --- Lấy giá trị khóa chính được truyền theo dạng QueryString Parameter key1=value1&key2=value2...
   --- 
*/
$id = $_GET['id'];
$sqlSelectProduct = <<<EOT
    SELECT p.*
        , pd.discount_name, pd.discount_amount, pd.is_fixed, pd.start_date, pd.end_date
        , c.category_name
        , s.supplier_name
    FROM shop_products p
    JOIN shop_categories c ON p.category_id = c.id
    JOIN shop_suppliers s ON p.supplier_id = s.id
    LEFT JOIN shop_product_discounts pd ON pd.product_id = p.id
    WHERE p.id = $id;   
EOT;

// Thực thi câu truy vấn SQL để lấy về dữ liệu ban đầu của record 
$resultSelectProduct = mysqli_query($conn, $sqlSelectProduct);

// Khi thực thi các truy vấn dạng SELECT, dữ liệu lấy về cần phải phân tích để sử dụng
// Thông thường, chúng ta sẽ sử dụng vòng lặp while để duyệt danh sách các dòng dữ liệu được SELECT
// Ta sẽ tạo 1 mảng array để chứa các dữ liệu được trả về
$dataSelectProduct;
$rowNumSelectProduct = 1;
while ($row = mysqli_fetch_array($resultSelectProduct, MYSQLI_ASSOC)) {
    $dataSelectProduct = array(
        // Thông tin Sản phẩm
        'rowNum' => $rowNumSelectProduct, // sử dụng biến tự tăng để làm dữ liệu cột STT
        'id' => $row['id'],
        'product_name' => $row['product_name'],
        'image' => $row['image'],
        'short_description' => $row['short_description'],
        'description' => $row['description'],
        'list_price' => $row['list_price'],
        // Sử dụng hàm number_format(số tiền, số lẻ thập phân, dấu phân cách số lẻ, dấu phân cách hàng nghìn) để định dạng số khi hiển thị trên giao diện. 
        // Vd: 15800000 -> format thành 15,800,000.66 vnđ
        'list_price_formatted' => number_format($row['list_price'], 0, ".", ",") . ' vnđ',

        // Thông tin Khuyến mãi / Giảm giá
        'discount_name' => $row['discount_name'],
        'discount_amount' => $row['discount_amount'],
        'is_fixed' => $row['is_fixed'],
        'start_date' => $row['start_date'],
        'end_date' => $row['end_date'],

        // Thông tin Chuyên mục
        'category_name' => $row['category_name'],

        // Thông tin Nhà cung cấp
        'supplier_name' => $row['supplier_name'],
    );
}

// Tính toán Hiển thị Giảm giá
$now = date("Y-m-d H:i:s"); // Lấy thời gian hiện tại
// dd($now, $dataSelectProduct['start_date'], $dataSelectProduct['end_date']);
// Nếu thời gian Khuyến mãi còn hiệu lực -> tính toán lại giá cả
if($dataSelectProduct['start_date'] <= $now && $now <= $dataSelectProduct['end_date']) {
    // Tùy loại giảm giá. is_fixed: #True 1: giảm giá theo số tiền cụ thể; #False 0: giảm giá theo %
    if($dataSelectProduct['is_fixed']) {
        $dataSelectProduct['list_price_after_discount'] = $dataSelectProduct['list_price'] - $dataSelectProduct['discount_amount'];
    } else {
        $dataSelectProduct['list_price_after_discount'] = $dataSelectProduct['list_price'] * ((100 - $dataSelectProduct['discount_amount']) / 100);
    }
    $dataSelectProduct['list_price_after_discount_formatted'] = number_format($dataSelectProduct['list_price_after_discount'], 0, ".", ",") . ' vnđ';
    // dd($dataSelectProduct['list_price'], ((100 - $dataSelectProduct['discount_amount']) / 100), $dataSelectProduct['list_price'] * ((100 - $dataSelectProduct['discount_amount']) / 100), $dataSelectProduct['list_price_after_discount_formatted']);
}
/* --- End Truy vấn dữ liệu Sản phẩm --- */

/* --- 
   --- 3.Truy vấn dữ liệu Hình ảnh Sản phẩm 
   --- 
*/
$sqlSelectProductImages = <<<EOT
    SELECT product_id, image
    FROM shop_product_images
    WHERE product_id = $id;
EOT;

// Thực thi câu truy vấn SQL để lấy về dữ liệu ban đầu của record 
$resultSelectProductImages = mysqli_query($conn, $sqlSelectProductImages);

// Khi thực thi các truy vấn dạng SELECT, dữ liệu lấy về cần phải phân tích để sử dụng
// Thông thường, chúng ta sẽ sử dụng vòng lặp while để duyệt danh sách các dòng dữ liệu được SELECT
// Ta sẽ tạo 1 mảng array để chứa các dữ liệu được trả về
$dataSelectProductImages = [];
while ($row = mysqli_fetch_array($resultSelectProductImages, MYSQLI_ASSOC)) {
    $dataSelectProductImages[] = array(
        'product_id' => $row['product_id'],
        'image' => $row['image'],
    );
}
/* --- End Truy vấn dữ liệu Hình ảnh sản phẩm --- */

/* --- 
   --- 4.Truy vấn dữ liệu Đánh giá Sản phẩm 
   --- 
*/
$sqlSelectProductReviews = <<<EOT
    SELECT pr.rating, pr.`comment`
    , c.email
    FROM shop_product_reviews pr
    JOIN shop_customers c ON pr.customer_id = c.id
    WHERE product_id = $id;
EOT;

// Thực thi câu truy vấn SQL để lấy về dữ liệu ban đầu của record 
$resultSelectProductReviews = mysqli_query($conn, $sqlSelectProductReviews);

// Khi thực thi các truy vấn dạng SELECT, dữ liệu lấy về cần phải phân tích để sử dụng
// Thông thường, chúng ta sẽ sử dụng vòng lặp while để duyệt danh sách các dòng dữ liệu được SELECT
// Ta sẽ tạo 1 mảng array để chứa các dữ liệu được trả về
$dataSelectProductReviews = [];
$avgRating = 0;
$sumRating = 0;
$totalRating = 0;
while ($row = mysqli_fetch_array($resultSelectProductReviews, MYSQLI_ASSOC)) {
    $dataSelectProductReviews[] = array(
        'rating' => $row['rating'],
        'comment' => $row['comment'],
        'email' => $row['email'],
    );

    // Tính toán điểm trung bình đánh giá của sản phẩm
    // Công thức: (Tổng số điểm đánh giá / Tổng số lượt đánh giá)
    $sumRating += $row['rating'];
    $totalRating++;
}

// Group by theo `rating` trên mảng PHP
// Tạo dữ liệu thống kê ban đầu
// $dataReviewStatistics = array(
//     1 => array(
//         'rating' => 1,  // 1 sao
//         'total' => 0,   // mới khởi tạo, mặc định 0 lần đánh giá
//     ),
//     2 => array(
//         'rating' => 2,  // 2 sao
//         'total' => 0,   // mới khởi tạo, mặc định 0 lần đánh giá
//     ),
//     3 => array(
//         'rating' => 3,  // 3 sao
//         'total' => 0,   // mới khởi tạo, mặc định 0 lần đánh giá
//     ),
//     4 => array(
//         'rating' => 4,  // 4 sao
//         'total' => 0,   // mới khởi tạo, mặc định 0 lần đánh giá
//     ),
//     5 => array(
//         'rating' => 5,  // 5 sao
//         'total' => 0,   // mới khởi tạo, mặc định 0 lần đánh giá
//     ),
// );
$dataReviewStatistics = [];
$tmpData = [];
foreach($dataSelectProductReviews as $item) {
    $tmpData[$item['rating']][] = $item;
}
foreach($dataSelectProductReviews as $item) {
    $dataReviewStatistics[$item['rating']][] = $item;
}

// dd($dataSelectProductReviews, $tmpData, $dataReviewStatistics);
// foreach($dataReviewStatistics as $statisticKey => $statisticValue) {
//     if(isset($tmpData[$statisticValue['rating']])) {
//         // dd($statisticValue['rating'], $tmpData[$statisticValue['rating']], count($tmpData[$statisticValue['rating']]));
//         $dataReviewStatistics[$statisticKey]['total'] = count($tmpData[$statisticValue['rating']]);
//     }
// }

// foreach($tmpData as $key => $item) {
//     $dataReviewStatistics[] = array(
//         'rating' => $key,
//         'total' => count($item)
//     );
// }
// dd($dataSelectProductReviews, $tmpData, $dataReviewStatistics);

// Nếu có bất kỳ đánh giá nào cho sản phẩm này thì mới tính toán điểm trung bình Đánh giá
// Ngược lại => mặc định là 0 điểm
if(!empty($dataSelectProductReviews)) {
    // Sử dụng hàm làm tròn số round() để làm tròn số điểm đánh giá Trung bình
    // Ví dụ: tổng điểm rating (5) / tổng số lượt đánh giá (2) = 2.5 điểm
    //                                           => làm tròn thành 3 điểm
    $avgRating = round($sumRating / $totalRating);
}
// dd($sumRating, $totalRating, $avgRating);
/* --- End Truy vấn dữ liệu Hình ảnh sản phẩm --- */

// Hiệu chỉnh dữ liệu theo cấu trúc để tiện xử lý
$dataSelectProduct['avgRating'] = $avgRating;
$dataSelectProduct['totalRating'] = $totalRating;
$dataSelectProduct['list_reviews'] = $dataSelectProductReviews;
$dataSelectProduct['review_statistics'] = $dataReviewStatistics;

$dataSelectProduct['list_images'] = $dataSelectProductImages;

// Yêu cầu `Twig` vẽ giao diện được viết trong file `frontend/product/detail.html.twig`
// với dữ liệu truyền vào file giao diện được đặt tên là `product`
echo $twig->render('frontend/product/detail.html.twig', ['product' => $dataSelectProduct]);
