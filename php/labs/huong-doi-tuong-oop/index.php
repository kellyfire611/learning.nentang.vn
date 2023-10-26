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
  <title>Bài tập tạo class hướng đối tượng OOP | NenTang.vn</title>

  <style>
  </style>
</head>

<body>
  <h1>Hướng đối tượng OOP | https://nentang.vn</h1>
  <?php
  // Tạo class (tạo khuôn) Con người
  class ConNguoi {
    public $hoten;
    public $tuoi;
    public $gioi_tinh;
    private $chieu_cao;
    private $can_nang;
    protected $so_thich;

    // Hàm khởi tạo
    // public function __construct($ht, $t, $gt, $cc, $cn, $st) {
    //   $this->hoten = $ht;
    //   $this->tuoi = $t;
    //   $this->gioi_tinh = $gt;
    //   $this->chieu_cao = $cc;
    //   $this->can_nang = $cn;
    //   $this->so_thich = $st;
    // }

    public function chao_hoi() {
      echo "Xin chào, tôi tên là $this->hoten; tuổi: $this->tuoi; giới tính: $this->gioi_tinh; có chiều cao: $this->chieu_cao; cân nặng: $this->can_nang; sở thích: $this->so_thich. Rất vui được gặp bạn....<br />";
    }

    public function chay_nhay() {
      echo "Tôi đang chạyyyyyyyyyyy và nhảyyyyyyyyy<br />";
    }
  }

  // Class kế thừa
  class SinhVien extends ConNguoi {
    public $ma_sv;
    public $diem_thi_lt;
    public $diem_thi_th;

    public function lam_bai_thi() {
      echo "Tôi là sinh viên có mã số: $this->ma_sv; họ tên: $this->hoten; đang làm bài thi...<br />";
    }

    public function hoi_bai($noidung) {
      echo "Sinh viên đang hỏi bài với nội dung: $noidung<br />";
    }
  }

  // Class kế thừa
  class GiangVien extends ConNguoi {
    public $ma_gv;
    public $ngay_cong_tac;
    public $he_so_luong;

    public function chan_bai() {
      echo "Tôi là giảng viên có mã số: $this->ma_gv; họ tên: $this->hoten; đang chấm bài thi...<br />";
    }

    public function tra_bai($noidung) {
      echo "Tôi đang trả lời cho sinh viên với nội dung: $noidung<br />";
    }
  }

  // Tạo đối tượng (instance) Con người
  $nguoi1 = new SinhVien();
  $nguoi1->hoten = 'Dương Nguyễn Phú Cường';
  $nguoi1->tuoi = 33;
  $nguoi1->gioi_tinh = false; // #true: Nữ; #false: Nam
  // $nguoi1->chieu_cao = 1.63;
  $nguoi1->chao_hoi();
  $nguoi1->chay_nhay();

  // Tạo đối tượng (instance) Giảng viên
  $nguoi1 = new SinhVien();
  $nguoi1->hoten = 'Dương Nguyễn Phú Cường';
  $nguoi1->tuoi = 33;
  $nguoi1->gioi_tinh = false; // #true: Nữ; #false: Nam
  // $nguoi1->chieu_cao = 1.63;
  $nguoi1->chao_hoi();
  $nguoi1->chay_nhay();
  ?>
</body>

</html>