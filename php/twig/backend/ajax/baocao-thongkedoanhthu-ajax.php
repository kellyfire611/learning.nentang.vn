<?php
// Include file cấu hình ban đầu của `Twig`
require_once __DIR__.'/../../bootstrap.php';

// Truy vấn database để lấy danh sách
// 1. Include file cấu hình kết nối đến database, khởi tạo kết nối $conn
include_once(__DIR__.'/../../dbconnect.php');

// 2. Chuẩn bị câu truy vấn $sql
$sql = <<<EOT
    SELECT DATE(ddh.dh_ngaylap) as NgayTaoDonHang, SUM(sp_dh_soluong * sp_dh_dongia) AS TongThanhTien
    FROM `dondathang` ddh
    JOIN `sanpham_dondathang` spddh ON ddh.dh_ma = spddh.dh_ma
    GROUP BY DATE(ddh.dh_ngaylap)
EOT;

// 3. Thực thi câu truy vấn SQL để lấy về dữ liệu
$result = mysqli_query($conn, $sql);

// 4. Khi thực thi các truy vấn dạng SELECT, dữ liệu lấy về cần phải phân tích để sử dụng
// Thông thường, chúng ta sẽ sử dụng vòng lặp while để duyệt danh sách các dòng dữ liệu được SELECT
// Ta sẽ tạo 1 mảng array để chứa các dữ liệu được trả về
$data = [];
while($row = mysqli_fetch_array($result, MYSQLI_ASSOC))
{
    $data[] = array(
        'NgayTaoDonHang' => date('d/m/Y', strtotime($row['NgayTaoDonHang'])),
        'TongThanhTien' => $row['TongThanhTien'] 
    );
}

// Dữ liệu JSON, array PHP -> JSON 
echo json_encode($data);