<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="keywords" content="Nền tảng,HTML,CSS,XML,JavaScript, Lập trình C#, Lập trình, Web, Kiến thức, Đồ án">
  <meta name="author" content="Dương Nguyễn Phú Cường">
  <meta name="description" content="Cung cấp các kiến thức Nền tảng, cơ bản về Lập trình, Lập trình web, Lập trình di động, Cơ sở dữ liệu, ...">
  <title>Render dữ liệu danh sách Array bằng PHP | NenTang.vn</title>
</head>
<body>
  <h1>Render dữ liệu dạng danh sách Array bằng PHP</h1>

  <?php
  // Khai báo biến mảng (array) mô tả về thông tin về Sinh viên 1
  $arrSinhVien1 = array(
    'hoten'     => 'Dương Nguyễn Phú Cường', // key Họ tên          có value = 'Dương Nguyễn Phú Cường'
    'gioitinh'  => 'Nam',                    // key Giới tính       có value = 'Nam'
    'diemlt'    => 8,                        // key Điểm lý thuyết  có value = 8
    'diemth'    => 10                        // key Điểm thực hành  có value = 10
  );

  // Khai báo biến mảng (array) mô tả về thông tin về Sinh viên 2
  $arrSinhVien2 = array(
    'hoten'     => 'Trần Thị B',            // key Họ tên          có value = 'Trần Thị B'
    'gioitinh'  => 'Nữ',                    // key Giới tính       có value = 'Nữ'
    'diemlt'    => 6,                       // key Điểm lý thuyết  có value = 6
    'diemth'    => 7                        // key Điểm thực hành  có value = 7
  );

  // Khai báo biến mảng (array) mô tả về thông tin về Sinh viên 3
  $arrSinhVien3 = [
    'hoten'     => 'Phạm Văn C',            // key Họ tên          có value = 'Phạm Văn C'
    'gioitinh'  => 'Nam',                   // key Giới tính       có value = 'Nam'
    'diemlt'    => 7,                       // key Điểm lý thuyết  có value = 7
    'diemth'    => 9                        // key Điểm thực hành  có value = 9
  ];

  // Tạo mảng Danh sách (array) lớn chứa các mảng dữ liệu con
  // Danh sách có 3 dòng dữ liệu => tương đương với 3 array con
  $arrDanhSach = [
    $arrSinhVien1,
    $arrSinhVien2,
    $arrSinhVien3,
  ];
  ?>

  <!-- Render phong cách 1 (style viêt code) -->
  <h2>Danh sách Thông tin của Sinh viên</h2>
  <h3>Render theo phong cách 1 (style viết code) <span style="color: red;">foreach: và endforeach;</span></h3>
  <table border="1">
    <tr>
      <th>Họ tên</th>
      <th>Giới tính</th>
      <th>Điểm LT</th>
      <th>Điểm TH</th>
    </tr>

    <?php foreach($arrDanhSach as $sv): ?>
    <tr>
      <td><?php echo $sv['hoten']; ?></td>
      <td><?php echo $sv['gioitinh']; ?></td>
      <td><?= $sv['diemlt']; ?></td>
      <td><?= $sv['diemth']; ?></td>
    </tr>
    <?php endforeach; ?>

  </table>
  <hr />

  <!-- Render phong cách 2 (style viêt code) -->
  <h2>Danh sách Thông tin của Sinh viên</h2>
  <h3>Render theo phong cách 2 (style viết code) <span style="color: red;">foreach { và }</span></h3>
  <table border="1">
    <tr>
      <th>Họ tên</th>
      <th>Giới tính</th>
      <th>Điểm LT</th>
      <th>Điểm TH</th>
    </tr>

    <?php foreach($arrDanhSach as $sv) { ?>
    <tr>
      <td><?php echo $sv['hoten']; ?></td>
      <td><?php echo $sv['gioitinh']; ?></td>
      <td><?= $sv['diemlt']; ?></td>
      <td><?= $sv['diemth']; ?></td>
    </tr>
    <?php } ?>

  </table>
  <hr />

  <!-- Render phong cách 3 (style viêt code) -->
  <h2>Danh sách Thông tin của Sinh viên</h2>
  <h3>Render theo phong cách 3 (style viết code) <span style="color: red;">for: và endfor;</span></h3>
  <table border="1">
    <tr>
      <th>Họ tên</th>
      <th>Giới tính</th>
      <th>Điểm LT</th>
      <th>Điểm TH</th>
    </tr>

    <?php for($i = 0; $i < count($arrDanhSach); $i++): ?>
    <tr>
      <td><?php echo $arrDanhSach[$i]['hoten']; ?></td>
      <td><?php echo $arrDanhSach[$i]['gioitinh']; ?></td>
      <td><?= $arrDanhSach[$i]['diemlt']; ?></td>
      <td><?= $arrDanhSach[$i]['diemth']; ?></td>
    </tr>
    <?php endfor; ?>

  </table>
  <hr />

<!-- Render phong cách 4 (style viêt code) -->
<h2>Danh sách Thông tin của Sinh viên</h2>
<h3>Render theo phong cách 4 (style viết code) <span style="color: red;">for { và }</span></h3>
<table border="1">
  <tr>
    <th>Họ tên</th>
    <th>Giới tính</th>
    <th>Điểm LT</th>
    <th>Điểm TH</th>
  </tr>

  <?php for($i = 0; $i < count($arrDanhSach); $i++) { ?>
  <tr>
    <td><?php echo $arrDanhSach[$i]['hoten']; ?></td>
    <td><?php echo $arrDanhSach[$i]['gioitinh']; ?></td>
    <td><?= $arrDanhSach[$i]['diemlt']; ?></td>
    <td><?= $arrDanhSach[$i]['diemth']; ?></td>
  </tr>
  <?php } ?>

</table>
</body>
</html>