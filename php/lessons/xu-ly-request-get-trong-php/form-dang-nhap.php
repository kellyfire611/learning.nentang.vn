<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Xử lý Request GET trong PHP | Nền Tảng .vn</title>
</head>
<body>
  <h1>Form đăng nhập | NenTang.vn</h1>

  <!-- 
    1. Thuộc tính action="" dùng để chỉ định địa chỉ file PHP - nơi sẽ nhận dữ liệu từ CLIENT gởi đến và xử lý theo các LOGIC nào đó...
    2. Phương thức (method) dùng để gởi request có thể sử dụng: GET hoặc POST
      * Nếu sử dụng phương thức GET:
      - Dữ liệu trong FORM sẽ được truyền theo dạng tham số trên địa chỉ URL theo định dạng sau:
        http://domain/action.php?param1=value1&param2=value2...

      - Trong đó:
        + Các thông tin được gởi từ FORM đến SERVER (file action được chỉ định) sẽ được phân cách bởi dấu ?
        + Các tham số `param1`, `param2` là thuộc tính name của các thành phần Nhập liệu (inputs) trong FORM
                      `value1`, `value2` là những thông tin người dùng (End user) nhập liệu trong FORM
                      ...
        + Các thành phần Tham số sẽ được phân cách nhau bởi dấu &

      Ví dụ: http://localhost/hoc-php/xu-ly-form-dang-nhap.php?username=dnpcuong&password=123456
  -->
  <form name="frmLogin" id="frmLogin" action="xu-ly-dang-nhap.php" method="GET">
    <table border="1" width="300px" cellspacing="0px" cellpadding="10px">
      <tr>
        <td>Tài khoản:</td>
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
        <td colspan="2" align="center">
          <input type="submit" value="Đăng nhập" />
        </td>
      </tr>
    </table>
  </form>
</body>
</html>