<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Xử lý Request POST trong PHP | Nền Tảng .vn</title>
</head>
<body>
  <h1>Form đăng ký | NenTang.vn</h1>

  <!-- 
    1. Thuộc tính action="" dùng để chỉ định địa chỉ file PHP - nơi sẽ nhận dữ liệu từ CLIENT gởi đến và xử lý theo các LOGIC nào đó...
    2. Phương thức (method) dùng để gởi request có thể sử dụng: GET hoặc POST
      * Nếu sử dụng phương thức POST:
      - Dữ liệu trong FORM sẽ được truyền đóng gói và đính kèm trong Request Body khi gởi đến Server
        http://domain/action.php

      Ví dụ: http://localhost/hoc-php/xu-ly-form-dang-ky.php
  -->
  <form name="frmRegister" id="frmRegister" action="xu-ly-dang-ky.php" method="POST">
    <table border="1" width="600px" cellspacing="0px" cellpadding="10px">
      <tr>
        <td width="120px">Tài khoản:</td>
        <td>
          <!-- 
            Thuộc tính name="" cần có trong các thành phần Nhập liệu (input, select, ...)
            FORM sẽ đóng gói dữ liệu người dùng (End User) nhập liệu vào đúng tên được đặt trong thuộc tính name=""
            Ví dụ: đặt tên là name="username"
          -->
          <input type="text" name="username" id="username" />
        </td>
      </tr>
      <tr>
        <td>Mật khẩu:</td>
        <td>
          <!-- 
            Thuộc tính name="" cần có trong các thành phần Nhập liệu (input, select, ...)
            FORM sẽ đóng gói dữ liệu người dùng (End User) nhập liệu vào đúng tên được đặt trong thuộc tính name=""
            Ví dụ: đặt tên là name="password"
          -->
          <input type="text" name="password" id="password" />
        </td>
      </tr>
      <tr>
        <td>Họ tên:</td>
        <td>
          <!-- 
            Thuộc tính name="" cần có trong các thành phần Nhập liệu (input, select, ...)
            FORM sẽ đóng gói dữ liệu người dùng (End User) nhập liệu vào đúng tên được đặt trong thuộc tính name=""
            Ví dụ: đặt tên là name="full_name"
          -->
          <input type="text" name="full_name" id="full_name" />
        </td>
      </tr>
      <tr>
        <td colspan="2" align="center">
          <input type="submit" value="Đăng ký" />
        </td>
      </tr>
    </table>
  </form>
</body>
</html>