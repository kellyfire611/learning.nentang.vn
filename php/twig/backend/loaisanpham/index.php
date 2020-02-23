<?php
// Include file cấu hình ban đầu của `Twig`
require_once __DIR__ . '/../../bootstrap.php';

// Truy vấn database để lấy danh sách
// 1. Include file cấu hình kết nối đến database, khởi tạo kết nối $conn
include_once(__DIR__ . '/../../dbconnect.php');
include_once(__DIR__ . '/../../Paginator.php');

// 2. Chuẩn bị câu truy vấn $sql
$stt = 1;
$sql = "select * from `loaisanpham`";

// 3. Thực thi câu truy vấn SQL để lấy về dữ liệu (Có phân trang - Pagination)
$limit      = (isset($_GET['limit'])) ? $_GET['limit'] : 5;
$page       = (isset($_GET['page'])) ? $_GET['page'] : 1;
$paginator  = new Paginator($twig, $conn, $sql);
$data       = $paginator->getData($limit, $page);
// dd($results);
//$result = mysqli_query($conn, $sql);

// 4. Khi thực thi các truy vấn dạng SELECT, dữ liệu lấy về cần phải phân tích để sử dụng
// Thông thường, chúng ta sẽ sử dụng vòng lặp while để duyệt danh sách các dòng dữ liệu được SELECT
// Ta sẽ tạo 1 mảng array để chứa các dữ liệu được trả về
// $data = [];
// while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
//     $data[] = array(
//         'lsp_ma' => $row['lsp_ma'],
//         'lproduct_name' => $row['lproduct_name'],
//         'lsp_mota' => $row['lsp_mota'],
//     );
// }

// Yêu cầu `Twig` vẽ giao diện được viết trong file `backend/loaisanpham/loaisanpham.html.twig`
// với dữ liệu truyền vào file giao diện được đặt tên là `ds_loaisanpham`
echo $twig->render('backend/loaisanpham/index.html.twig', [
    'ds_loaisanpham' => $data,
    'paginator' => $paginator
]);
