<!DOCTYPE html>
<html lang="vi">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="keywords" content="Nền tảng,HTML,CSS,XML,JavaScript, Lập trình C#, Lập trình, Web, Kiến thức, Đồ án">
  <meta name="author" content="Dương Nguyễn Phú Cường">
  <meta name="description" content="Cung cấp các kiến thức Nền tảng, cơ bản về Lập trình, Lập trình web, Lập trình di động, Cơ sở dữ liệu, ...">
  <meta property="og:locale" content="vi_VN">
  <meta property="og:type" content="website">
  <meta property="og:title" content="Nền tảng Kiến thức">
  <meta property="og:description" content="Cung cấp các kiến thức Nền tảng, cơ bản về Lập trình, Lập trình web, Lập trình di động, Cơ sở dữ liệu, ...">
  <meta property="og:url" content="https://nentang.vn/">
  <meta property="og:site_name" content="Nền tảng Kiến thức">
  <title>Bài tập tạo trang Bảng điểm Sinh viên tham dự kỳ thi kết thúc môn WEB | NenTang.vn</title>

  <style>
    .dong-canhbao {
      background: #fd00003d;
      color: red;
    }
  </style>
</head>

<body>
  <h1>Nền tảng Kiến thức | https://nentang.vn</h1>
  <?php
  // Khởi tạo danh sách Sinh viên dự thi
  $danhsachSinhVien = [
    [
      'sv_id' => 1,
      'sv_ma' => 'SV001',
      'sv_hoten' => 'Lê Nguyễn Văn Duy',
      'lop_ma' => 'TKB-001',
      'diem_lt' => 40,
      'diem_th' => 90,
    ],
    [
      'sv_id' => 2,
      'sv_ma' => 'SV002',
      'sv_hoten' => 'Trần Thị Diệu Thúy',
      'lop_ma' => 'TKB-001',
      'diem_lt' => 95,
      'diem_th' => 50,
    ],
    [
      'sv_id' => 3,
      'sv_ma' => 'SV003',
      'sv_hoten' => 'Vũ Thành Phương',
      'lop_ma' => 'TKB-002',
      'diem_lt' => 75,
      'diem_th' => 75,
    ],
    [
      'sv_id' => 4,
      'sv_ma' => 'SV004',
      'sv_hoten' => 'Đỗ Xuân Hiếu',
      'lop_ma' => 'TKB-002',
      'diem_lt' => 90,
      'diem_th' => 40,
    ],
    [
      'sv_id' => 5,
      'sv_ma' => 'SV005',
      'sv_hoten' => 'Nguyễn Hoa Liên',
      'lop_ma' => 'TKB-002',
      'diem_lt' => 10,
      'diem_th' => 10,
    ],
    [
      'sv_id' => 6,
      'sv_ma' => 'SV006',
      'sv_hoten' => 'Võ Phan Khánh Chi',
      'lop_ma' => 'TKB-002',
      'diem_lt' => 90,
      'diem_th' => 65,
    ],
    [
      'sv_id' => 7,
      'sv_ma' => 'SV007',
      'sv_hoten' => 'Nguyễn Trần Bình Ðạt',
      'lop_ma' => 'TKB-003',
      'diem_lt' => 15,
      'diem_th' => 45,
    ],
    [
      'sv_id' => 8,
      'sv_ma' => 'SV008',
      'sv_hoten' => 'Nguyễn Trúc Lâm',
      'lop_ma' => 'TKB-003',
      'diem_lt' => 55,
      'diem_th' => 90,
    ],
    [
      'sv_id' => 9,
      'sv_ma' => 'SV009',
      'sv_hoten' => 'Nguyễn Bùi Thiên Thảo',
      'lop_ma' => 'TKB-003',
      'diem_lt' => 25,
      'diem_th' => 60,
    ],
    [
      'sv_id' => 10,
      'sv_ma' => 'SV010',
      'sv_hoten' => 'Phạm Thị Nữ',
      'lop_ma' => 'TKB-004',
      'diem_lt' => 80,
      'diem_th' => 80,
    ],
  ];

  // Lọc trong mảng (array) các Sinh viên có ĐTB >= 40
  $danhsachSinhVien_ThiDat = array_filter($danhsachSinhVien, function ($v, $k) {
    $dtb = ($v['diem_lt'] + $v['diem_th']) / 2;
    return $dtb >= 40;
  }, ARRAY_FILTER_USE_BOTH);

  // Lọc trong mảng (array) các Sinh viên có ĐTB < 40
  $danhsachSinhVien_KhongDat = array_filter($danhsachSinhVien, function ($v, $k) {
    $dtb = ($v['diem_lt'] + $v['diem_th']) / 2;
    return $dtb < 40;
  }, ARRAY_FILTER_USE_BOTH);

  // Sắp xếp theo điểm TB
  $danhsachSinhVien_dasapxep = $danhsachSinhVien;
  function compa($svA, $svB) {
    $dtb_svA = ($svA['diem_lt'] + $svA['diem_th']) / 2;
    $dtb_svB = ($svB['diem_lt'] + $svB['diem_th']) / 2;

    if ($dtb_svA == $dtb_svB) {
      return 0;
    }
    return ($dtb_svA > $dtb_svB) ? -1 : 1;
  };
  usort($danhsachSinhVien_dasapxep, "compa");

  // Lấy top 5 sinh viên
  $danhsachSinhVien_Top5 = array_slice($danhsachSinhVien_dasapxep, 0, 5);
  ?>

  <h2>Bảng điểm Sinh viên tham dự kỳ thi Kết thúc môn WEB</h2>
  <table border="1" width="800px">
    <tbody>
      <tr bgcolor="#9e9e9e">
        <td align="center"><strong>STT</strong></td>
        <td align="center"><strong>Lớp</strong></td>
        <td align="center"><strong>Mã Sinh viên </strong></td>
        <td align="center"><strong>Họ tên Sinh viên</strong></td>
        <td align="center"><strong>Điểm </strong><strong>Lý thuyết</strong></td>
        <td align="center"><strong>Điểm Thực hành</strong></td>
        <td align="center"><strong>Điểm Trung bình</strong></td>
      </tr>
      <?php foreach ($danhsachSinhVien as $index => $sv) : ?>
        <?php
        $dtb = ($sv['diem_lt'] + $sv['diem_th']) / 2;
        ?>
        <?php if ($dtb < 40) : ?>
          <tr class="dong-canhbao">
          <?php else : ?>
          <tr>
          <?php endif; ?>
          <td align="center"><?= $index + 1 ?></td>
          <td align="left"><?= $sv['lop_ma'] ?></td>
          <td align="left"><?= $sv['sv_ma'] ?></td>
          <td align="left"><?= $sv['sv_hoten'] ?></td>
          <td align="right"><?= $sv['diem_lt'] ?></td>
          <td align="right"><?= $sv['diem_th'] ?></td>
          <td align="right"><?= $dtb ?></td>
          </tr>
        <?php endforeach; ?>
    </tbody>
  </table>

  <p>
    <u><b>Lưu ý:</b></u><br />
    - Điểm thi >= 40 thì Kết quả <font color="blue"><b>ĐẠT</b></font>
  </p>

  <h2>Kết quả Thi (&gt;=40đ thì ĐẠT):</h2>
  <ul>
    <li>Các Sinh viên thi <font color="blue"><strong>ĐẠT</strong></font></span>:
      <ul>
        <?php foreach ($danhsachSinhVien_ThiDat as $sv) : ?>
          <li><?= $sv['sv_hoten'] ?> (<?= $sv['sv_ma'] ?>)</li>
        <?php endforeach; ?>
      </ul>
    </li>
    <li>Các Sinh viên thi <strong>
        <font color="red">CHƯA ĐẠT</font>
      </strong>:
      <ul type="square">
        <?php foreach ($danhsachSinhVien_KhongDat as $sv) : ?>
          <li><?= $sv['sv_hoten'] ?> (<?= $sv['sv_ma'] ?>)</li>
        <?php endforeach; ?>
      </ul>
    </li>
  </ul>
  <h2>Danh sách top 5 Sinh viên có điểm thi Trung bình Cao nhất:</h2>
  <ol>
    <?php foreach ($danhsachSinhVien_Top5 as $sv) : ?>
      <li><?= $sv['sv_hoten'] ?> (<?= $sv['sv_ma'] ?>), điểm thi TB: <?= ($sv['diem_lt'] + $sv['diem_th']) / 2 ?></li>
    <?php endforeach; ?>
  </ol>
</body>

</html>