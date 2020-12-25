<!-- Tiêu đề của Trang web, được quản lý bởi biến $PAGE_TITLE trong file "layouts/config.php" -->
<title><?= $PAGE_TITLE; ?></title>

<!-- Nhúng file Quản lý phần META -->
<?php include_once(__DIR__.'/meta.php'); ?>

<!-- Nhúng file Quản lý các Liên kết CSS dùng chung cho toàn bộ trang web -->
<?php include_once(__DIR__.'/styles.php'); ?>