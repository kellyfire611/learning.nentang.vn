<?php
// Include file cấu hình ban đầu của `Twig`
require_once __DIR__ . '/../../bootstrap.php';

// Truy vấn database để lấy danh sách
// 1. Include file cấu hình kết nối đến database, khởi tạo kết nối $conn
include_once(__DIR__ . '/../../dbconnect.php');

// 2. Yêu cầu `Twig` vẽ giao diện được viết trong file `frontend/pages/about.html.twig`
echo $twig->render('frontend/pages/about.html.twig');
