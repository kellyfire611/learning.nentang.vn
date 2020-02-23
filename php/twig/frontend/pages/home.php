<?php
// Include file cấu hình ban đầu của `Twig`
require_once __DIR__ . '/../../bootstrap.php';

// Truy vấn database để lấy danh sách
// 1. Include file cấu hình kết nối đến database, khởi tạo kết nối $conn
include_once(__DIR__ . '/../../dbconnect.php');
include_once(__DIR__ . '/../../Paginator.php');

// --- Danh sách top 5 Sản phẩm Nổi bật ---
// Chuẩn bị câu truy vấn $sql
$sqlSelectFeaturedProducts = <<<EOT
    SELECT p.id, p.product_name, p.image, p.short_description, p.list_price
        , pd.discount_name, pd.discount_amount, pd.is_fixed, pd.start_date, pd.end_date
        , c.category_name
        , s.supplier_name
    FROM shop_products p
    JOIN shop_categories c ON p.category_id = c.id
    JOIN shop_suppliers s ON p.supplier_id = s.id
    LEFT JOIN shop_product_discounts pd ON pd.product_id = p.id
    WHERE p.is_featured = TRUE
        AND p.discontinued = FALSE
        AND p.quantity_per_unit > 0    
    LIMIT 0,5
EOT;

// 3. Thực thi câu truy vấn SQL để lấy về dữ liệu
$resultSelectFeaturedProducts = mysqli_query($conn, $sqlSelectFeaturedProducts);

// 4. Khi thực thi các truy vấn dạng SELECT, dữ liệu lấy về cần phải phân tách để sử dụng
// Thông thường, chúng ta sẽ sử dụng vòng lặp while để duyệt danh sách các dòng dữ liệu được SELECT
// Ta sẽ tạo 1 mảng array để chứa các dữ liệu được trả về
$dataSelectFeaturedProducts = [];
$rowNumSelectFeaturedProducts = 1;
while ($row = mysqli_fetch_array($resultSelectFeaturedProducts, MYSQLI_ASSOC)) {
    $dataSelectFeaturedProducts[] = array(
        // Thông tin Sản phẩm
        'rowNum' => $rowNumSelectFeaturedProducts, // sử dụng biến tự tăng để làm dữ liệu cột STT
        'id' => $row['id'],
        'product_name' => $row['product_name'],
        'image' => $row['image'],
        'short_description' => $row['short_description'],
        'list_price' => $row['list_price'],

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
    $rowNumSelectFeaturedProducts++;
}
// -------------------------------------------

// --- Danh sách top 5 Sản phẩm Mới nhập về ---
// Chuẩn bị câu truy vấn $sql
$sqlSelectNewProducts = <<<EOT
    SELECT p.id, p.product_name, p.image, p.short_description, p.list_price
        , pd.discount_name, pd.discount_amount, pd.is_fixed, pd.start_date, pd.end_date
        , c.category_name
        , s.supplier_name
    FROM shop_products p
    JOIN shop_categories c ON p.category_id = c.id
    JOIN shop_suppliers s ON p.supplier_id = s.id
    LEFT JOIN shop_product_discounts pd ON pd.product_id = p.id
    WHERE p.is_new = TRUE
        AND p.discontinued = FALSE
        AND p.quantity_per_unit > 0    
    LIMIT 0,5
EOT;

// 3. Thực thi câu truy vấn SQL để lấy về dữ liệu
$resultSelectNewProducts = mysqli_query($conn, $sqlSelectNewProducts);

// 4. Khi thực thi các truy vấn dạng SELECT, dữ liệu lấy về cần phải phân tách để sử dụng
// Thông thường, chúng ta sẽ sử dụng vòng lặp while để duyệt danh sách các dòng dữ liệu được SELECT
// Ta sẽ tạo 1 mảng array để chứa các dữ liệu được trả về
$dataSelectNewProducts = [];
$rowNumSelectNewProducts = 1;
while ($row = mysqli_fetch_array($resultSelectNewProducts, MYSQLI_ASSOC)) {
    $dataSelectNewProducts[] = array(
        // Thông tin Sản phẩm
        'rowNum' => $rowNumSelectNewProducts, // sử dụng biến tự tăng để làm dữ liệu cột STT
        'id' => $row['id'],
        'product_name' => $row['product_name'],
        'image' => $row['image'],
        'short_description' => $row['short_description'],
        'list_price' => $row['list_price'],

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
    $rowNumSelectNewProducts++;
}
// -------------------------------------------

// --- Danh sách tất cả Sản phẩm ---
// 2. Chuẩn bị câu truy vấn $sql
$sql = <<<EOT
    SELECT p.id, p.product_name, p.image, p.short_description, p.list_price
        , pd.discount_name, pd.discount_amount, pd.is_fixed, pd.start_date, pd.end_date
        , c.category_name
        , s.supplier_name
    FROM shop_products p
    JOIN shop_categories c ON p.category_id = c.id
    JOIN shop_suppliers s ON p.supplier_id = s.id
    LEFT JOIN shop_product_discounts pd ON pd.product_id = p.id
    WHERE p.discontinued = FALSE
        AND p.quantity_per_unit > 0
EOT;

// 3. Thực thi câu truy vấn SQL để lấy về dữ liệu (Có phân trang - Pagination)
$limit        = (isset($_GET['limit'])) ? $_GET['limit'] : Config::$limit;
$page         = (isset($_GET['page'])) ? $_GET['page'] : Config::$page;
$paginator    = new Paginator($twig, $conn, $sql);
$dataProducts = $paginator->getData($limit, $page);

// Yêu cầu `Twig` vẽ giao diện được viết trong file `frontend/pages/home.html.twig`
// với dữ liệu truyền vào file giao diện được đặt tên
echo $twig->render('frontend/pages/home.html.twig', [
    'featuredProducts' => $dataSelectFeaturedProducts,
    'newProducts' => $dataSelectNewProducts,
    'products' => $dataProducts,
    'paginator' => $paginator
]);
