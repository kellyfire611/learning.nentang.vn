<?php
// Tạo kết nối
// Tham số của hàm mysqli_connect(hostname/ip, username, password, database_name)
// 1. hostname/ip: tên host hoặc IP database server
// - ví dụ: '127.0.0.1' tương đương 'localhost': là địa chỉ máy cục bộ
//   port mặc định khi sử dụng MySQL là 3306
//   nếu sử dụng port khác, ví dụ 3307 thì giá trị truyền vào là '127.0.0.1:3307'
// 2. username: tên tài khoản được phép truy cập vào database server
// 3. password: mật khẩu tài khoản được phép truy cập vào database server
// 4. database_name: tên database bạn muốn truy cập đến
$conn = mysqli_connect('156.67.222.148', 'u883604362_temp', 'TempDb@#123', 'u883604362_temp') or die('Xin lỗi, database không kết nối được.');

// Tùy chỉnh kết nối
// Set charset là utf-8 đối với kết nối này. Dùng để gõ tiếng Việt, Nhật, Thái, Trung Quốc ...
// Lưu ý: gõ với bộ gõ UNIKEY, bảng mã là UNICODE
$conn->query("SET NAMES 'utf8mb4'"); 
$conn->query("SET CHARACTER SET utf8mb4");  
$conn->query("SET SESSION collation_connection = 'utf8mb4_unicode_ci'"); 