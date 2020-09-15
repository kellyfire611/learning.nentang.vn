<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow">
  <a class="navbar-brand" href="/">Nền tảng</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item active">
        <a class="nav-link" href="/php/myhand/backend/pages/dashboard.php">Quản trị</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="/php/myhand/frontend/pages/about.php">Giới thiệu</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="/php/myhand/frontend/pages/contact.php">Liên hệ</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="/php/myhand/frontend/thanhtoan/giohang.php">Giỏ hàng</a>
      </li>
    </ul>

    <ul class="navbar-nav px-3 ml-auto">

      <?php
      // Đã đăng nhập rồi -> hiển thị tên Người dùng và menu Đăng xuất
      if (isset($_SESSION['kh_tendangnhap_logged']) && !empty($_SESSION['kh_tendangnhap_logged'])) :
      ?>
        <li class="nav-item text-nowrap">
          <a class="nav-link">Chào <?= $_SESSION['kh_tendangnhap_logged']; ?></a>
        </li>
        <li class="nav-item text-nowrap">
          <a class="nav-link" href="/php/myhand/backend/auth/logout.php">Đăng xuất</a>
        </li>
      <?php else : ?>
        <li class="nav-item text-nowrap">
          <a class="nav-link" href="/php/myhand/backend/auth/login.php">Đăng nhập</a>
        </li>
      <?php endif; ?>
    </ul>
  </div>
</nav>