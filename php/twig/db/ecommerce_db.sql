-- --------------------------------------------------------
-- Host:                         sql167.main-hosting.eu.
-- Server version:               10.2.23-MariaDB - MariaDB Server
-- Server OS:                    Linux
-- HeidiSQL Version:             10.2.0.5599
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


-- Dumping database structure for u883604362_php
CREATE DATABASE IF NOT EXISTS `u883604362_php` /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci */;
USE `u883604362_php`;

-- Dumping structure for table u883604362_php.chudegopy
CREATE TABLE IF NOT EXISTS `chudegopy` (
  `cdgy_ma` int(11) NOT NULL,
  `cdgy_ten` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`cdgy_ma`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table u883604362_php.chudegopy: ~0 rows (approximately)
/*!40000 ALTER TABLE `chudegopy` DISABLE KEYS */;
/*!40000 ALTER TABLE `chudegopy` ENABLE KEYS */;

-- Dumping structure for table u883604362_php.dondathang
CREATE TABLE IF NOT EXISTS `dondathang` (
  `dh_ma` int(11) NOT NULL AUTO_INCREMENT,
  `dh_ngaylap` datetime NOT NULL,
  `dh_ngaygiao` datetime DEFAULT NULL,
  `dh_noigiao` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `dh_trangthaithanhtoan` int(11) NOT NULL,
  `httt_ma` int(11) NOT NULL,
  `kh_tendangnhap` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`dh_ma`),
  KEY `dondathang_khachhang_idx` (`kh_tendangnhap`),
  KEY `dondathang_hinhthucthanhtoan_idx` (`httt_ma`),
  CONSTRAINT `dondathang_hinhthucthanhtoan` FOREIGN KEY (`httt_ma`) REFERENCES `hinhthucthanhtoan` (`httt_ma`),
  CONSTRAINT `dondathang_khachhang` FOREIGN KEY (`kh_tendangnhap`) REFERENCES `khachhang` (`kh_tendangnhap`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table u883604362_php.dondathang: ~5 rows (approximately)
/*!40000 ALTER TABLE `dondathang` DISABLE KEYS */;
INSERT INTO `dondathang` (`dh_ma`, `dh_ngaylap`, `dh_ngaygiao`, `dh_noigiao`, `dh_trangthaithanhtoan`, `httt_ma`, `kh_tendangnhap`) VALUES
	(4, '2019-06-11 20:48:10', '2019-06-15 00:00:00', 'Cần Thơ', 0, 1, 'dnpcuong'),
	(5, '2019-06-11 04:48:59', '2019-06-20 00:00:00', 'Cần Thơ', 1, 1, 'dnpcuong');
/*!40000 ALTER TABLE `dondathang` ENABLE KEYS */;

-- Dumping structure for table u883604362_php.gopy
CREATE TABLE IF NOT EXISTS `gopy` (
  `gy_ma` int(11) NOT NULL,
  `gy_hoten` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `gy_email` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `gy_diachi` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `gy_dienthoai` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `gy_tieude` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `gy_noidung` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `gy_ngaygopy` datetime DEFAULT NULL,
  `cdgy_ma` int(11) DEFAULT NULL,
  PRIMARY KEY (`gy_ma`),
  KEY `gopy_chudegopy_idx` (`cdgy_ma`),
  CONSTRAINT `gopy_chudegopy` FOREIGN KEY (`cdgy_ma`) REFERENCES `chudegopy` (`cdgy_ma`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table u883604362_php.gopy: ~0 rows (approximately)
/*!40000 ALTER TABLE `gopy` DISABLE KEYS */;
/*!40000 ALTER TABLE `gopy` ENABLE KEYS */;

-- Dumping structure for table u883604362_php.hinhsanpham
CREATE TABLE IF NOT EXISTS `hinhsanpham` (
  `hsp_ma` int(11) NOT NULL AUTO_INCREMENT,
  `hsp_tentaptin` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `sp_ma` int(11) NOT NULL,
  PRIMARY KEY (`hsp_ma`),
  KEY `fk_hinhsanpham_sanpham1_idx` (`sp_ma`),
  CONSTRAINT `fk_hinhsanpham_sanpham1` FOREIGN KEY (`sp_ma`) REFERENCES `sanpham` (`sp_ma`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table u883604362_php.hinhsanpham: ~6 rows (approximately)
/*!40000 ALTER TABLE `hinhsanpham` DISABLE KEYS */;
INSERT INTO `hinhsanpham` (`hsp_ma`, `hsp_tentaptin`, `sp_ma`) VALUES
	(25, 'samsung-galaxy-tab.jpg', 7),
	(26, 'samsung-galaxy-tab-2', 7),
	(27, 'samsung-galaxy-tab-3.jpg', 7),
	(29, 'nokia-asha-311.jpg', 6),
	(30, 'samsung-s3.webp', 1),
	(31, 'samsung-galaxy-tab-4.jpg', 7),
	(32, 'ipad4.png', 2),
	(36, 'iphone5-white.jpeg', 4),
	(37, 'samsung-galaxy-tab-10.jpg', 5),
	(38, 'iphone5.jpg', 3);
/*!40000 ALTER TABLE `hinhsanpham` ENABLE KEYS */;

-- Dumping structure for table u883604362_php.hinhthucthanhtoan
CREATE TABLE IF NOT EXISTS `hinhthucthanhtoan` (
  `httt_ma` int(11) NOT NULL AUTO_INCREMENT,
  `httt_ten` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`httt_ma`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table u883604362_php.hinhthucthanhtoan: ~0 rows (approximately)
/*!40000 ALTER TABLE `hinhthucthanhtoan` DISABLE KEYS */;
INSERT INTO `hinhthucthanhtoan` (`httt_ma`, `httt_ten`) VALUES
	(1, 'Tiền mặt'),
	(2, 'Chuyển khoản'),
	(3, 'Ship COD');
/*!40000 ALTER TABLE `hinhthucthanhtoan` ENABLE KEYS */;

-- Dumping structure for table u883604362_php.khachhang
CREATE TABLE IF NOT EXISTS `khachhang` (
  `kh_tendangnhap` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `kh_matkhau` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `kh_ten` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `kh_gioitinh` int(11) NOT NULL,
  `kh_diachi` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `kh_dienthoai` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `kh_email` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `kh_ngaysinh` int(11) DEFAULT NULL,
  `kh_thangsinh` int(11) DEFAULT NULL,
  `kh_namsinh` int(11) NOT NULL,
  `kh_cmnd` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `kh_makichhoat` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `kh_trangthai` int(11) NOT NULL,
  `kh_quantri` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`kh_tendangnhap`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table u883604362_php.khachhang: ~5 rows (approximately)
/*!40000 ALTER TABLE `khachhang` DISABLE KEYS */;
INSERT INTO `khachhang` (`kh_tendangnhap`, `kh_matkhau`, `kh_ten`, `kh_gioitinh`, `kh_diachi`, `kh_dienthoai`, `kh_email`, `kh_ngaysinh`, `kh_thangsinh`, `kh_namsinh`, `kh_cmnd`, `kh_makichhoat`, `kh_trangthai`, `kh_quantri`) VALUES
	('dinhduyvo', 'fcea920f7412b5da7be0cf42b8c93759', 'Vo Dinh Duy', 0, 'Can Tho', '07103.273.34433', 'vdduy@ctu.edu.vn', 2, 2, 1985, '', '', 1, 0),
	('dnpcuong', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'Dương Nguyễn Phú Cường', 0, '130 Xô Viết Nghệ Tỉnh', '0915659223', 'phucuong@ctu.edu.vn', 11, 6, 1989, '362209685', '4a5c874f8c4446145989ca515bd158669b0596c6', 1, 0),
	('nta', '123', 'Nguyễn Thị A', 0, 'Số 20 - Phan Đình Phùng', '01234.234.234', 'nta@gmail.com', NULL, NULL, 1990, NULL, NULL, 1, 0),
	('usermoi', 'fcea920f7412b5da7be0cf42b8c93759', 'Nguoi dung moi', 0, 'Can Tho', '07103-273.344', 'vdduy@ctu.edu.vn', 2, 2, 1985, '', '44766fb4dd4e4977e75a9321cbc6413e', 0, 0),
	('vdduy', 'fcea920f7412b5da7be0cf42b8c93759', 'Vo Dinh Duy', 0, 'Can Tho', '0975107705', 'vdduy@ctu.edu.vn', 2, 2, 1985, '', 'â€zcnl82qbuj', 1, 0);
/*!40000 ALTER TABLE `khachhang` ENABLE KEYS */;

-- Dumping structure for table u883604362_php.khuyenmai
CREATE TABLE IF NOT EXISTS `khuyenmai` (
  `km_ma` int(11) NOT NULL AUTO_INCREMENT,
  `km_ten` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `km_noidung` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `km_tungay` date DEFAULT NULL,
  `km_denngay` date DEFAULT NULL,
  PRIMARY KEY (`km_ma`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table u883604362_php.khuyenmai: ~0 rows (approximately)
/*!40000 ALTER TABLE `khuyenmai` DISABLE KEYS */;
/*!40000 ALTER TABLE `khuyenmai` ENABLE KEYS */;

-- Dumping structure for table u883604362_php.loaisanpham
CREATE TABLE IF NOT EXISTS `loaisanpham` (
  `lsp_ma` int(11) NOT NULL AUTO_INCREMENT,
  `lsp_ten` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `lsp_mota` varchar(500) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`lsp_ma`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table u883604362_php.loaisanpham: ~5 rows (approximately)
/*!40000 ALTER TABLE `loaisanpham` DISABLE KEYS */;
INSERT INTO `loaisanpham` (`lsp_ma`, `lsp_ten`, `lsp_mota`) VALUES
	(1, 'Máy tính bảng', '0'),
	(2, 'Máy tính xách tay', '0'),
	(3, 'Điện thoại', '0'),
	(4, 'Linh phụ kiện', '0');
/*!40000 ALTER TABLE `loaisanpham` ENABLE KEYS */;

-- Dumping structure for table u883604362_php.nhasanxuat
CREATE TABLE IF NOT EXISTS `nhasanxuat` (
  `nsx_ma` int(11) NOT NULL AUTO_INCREMENT,
  `nsx_ten` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`nsx_ma`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table u883604362_php.nhasanxuat: ~4 rows (approximately)
/*!40000 ALTER TABLE `nhasanxuat` DISABLE KEYS */;
INSERT INTO `nhasanxuat` (`nsx_ma`, `nsx_ten`) VALUES
	(1, 'Apple'),
	(2, 'Samsung'),
	(3, 'HTC'),
	(4, 'Nokia');
/*!40000 ALTER TABLE `nhasanxuat` ENABLE KEYS */;

-- Dumping structure for table u883604362_php.sanpham
CREATE TABLE IF NOT EXISTS `sanpham` (
  `sp_ma` int(11) NOT NULL AUTO_INCREMENT,
  `sp_ten` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `sp_gia` decimal(12,2) DEFAULT NULL,
  `sp_giacu` decimal(12,2) DEFAULT NULL,
  `sp_mota_ngan` varchar(1000) COLLATE utf8_unicode_ci NOT NULL,
  `sp_mota_chitiet` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `sp_ngaycapnhat` datetime NOT NULL,
  `sp_soluong` int(11) DEFAULT NULL,
  `lsp_ma` int(11) NOT NULL,
  `nsx_ma` int(11) NOT NULL,
  `km_ma` int(11) DEFAULT NULL,
  PRIMARY KEY (`sp_ma`),
  KEY `sanpham_loaisanpham_idx` (`lsp_ma`),
  KEY `sanpham_nhasanxuat_idx` (`nsx_ma`),
  KEY `sanpham_khuyenmai_idx` (`km_ma`),
  CONSTRAINT `sanpham_khuyenmai` FOREIGN KEY (`km_ma`) REFERENCES `khuyenmai` (`km_ma`),
  CONSTRAINT `sanpham_loaisanpham` FOREIGN KEY (`lsp_ma`) REFERENCES `loaisanpham` (`lsp_ma`),
  CONSTRAINT `sanpham_nhasanxuat` FOREIGN KEY (`nsx_ma`) REFERENCES `nhasanxuat` (`nsx_ma`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table u883604362_php.sanpham: ~0 rows (approximately)
/*!40000 ALTER TABLE `sanpham` DISABLE KEYS */;
INSERT INTO `sanpham` (`sp_ma`, `sp_ten`, `sp_gia`, `sp_giacu`, `sp_mota_ngan`, `sp_mota_chitiet`, `sp_ngaycapnhat`, `sp_soluong`, `lsp_ma`, `nsx_ma`, `km_ma`) VALUES
	(1, 'Samsung Galaxy S3', 12000000.00, 12600000.00, 'Sản phẩm của Samsung năm 2013', 'Cấu hình: CPU Dual Core – Ram 1 GB', '2012-12-22 11:20:30', 17, 3, 2, NULL),
	(2, 'Apple Ipad 4 Wifi 16GB', 11800000.00, 12000000.00, 'CPU  Dual-core Cortex-A9 tốc độ 1GHz', 'Màn hình 9.7 inch, cảm ứng điện dung đa điểm', '2013-01-12 10:04:45', 100, 1, 1, NULL),
	(3, 'Apple iPhone 5 16GB', 14890000.00, NULL, 'CPU: Dual-core 1 GHz', 'Chi tiết iPhone 5', '2013-01-16 17:13:45', 0, 3, 1, NULL),
	(4, 'Apple iPhone 5 16GB White', 14990000.00, NULL, 'CPU: Dual-core 1 GHz - Màu trắng', 'Chi tiết iPhone 5', '2013-01-16 17:14:55', 10, 3, 1, NULL),
	(5, 'Samsung Galaxy Tab 10.1 3G 16G', 10990000.00, 12000000.00, 'Màn hình 10.1 inch cảm ứng đa điểm', 'Vi xử lý Dual-core 1 Cortex-A9 tốc độ 1GHz', '2013-01-17 14:18:03', 6, 1, 2, NULL),
	(6, 'Nokia Asha 311', 2699000.00, 3000000.00, 'Điện thoại di động Nokia Asha 311', 'Màn hình QVGA, 3.0 inches', '2013-01-17 14:19:10', 25, 3, 3, NULL),
	(7, 'Samsung Galaxy Tab 2 7.0', 7500000.00, 7950000.00, 'Máy tính bảng Samsung Galaxy Tab 2 7.0 ', 'Màn hình 7 inch Cảm ứng điện dung', '2013-01-28 10:42:08', 13, 1, 2, NULL);
/*!40000 ALTER TABLE `sanpham` ENABLE KEYS */;

-- Dumping structure for table u883604362_php.sanpham_dondathang
CREATE TABLE IF NOT EXISTS `sanpham_dondathang` (
  `sp_ma` int(11) NOT NULL,
  `dh_ma` int(11) NOT NULL,
  `sp_dh_soluong` int(11) NOT NULL,
  `sp_dh_dongia` decimal(12,2) NOT NULL,
  PRIMARY KEY (`sp_ma`,`dh_ma`),
  KEY `sanpham_donhang_sanpham_idx` (`sp_ma`),
  KEY `sanpham_donhang_dondathang_idx` (`dh_ma`),
  CONSTRAINT `sanpham_donhang_dondathang` FOREIGN KEY (`dh_ma`) REFERENCES `dondathang` (`dh_ma`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `sanpham_donhang_sanpham` FOREIGN KEY (`sp_ma`) REFERENCES `sanpham` (`sp_ma`) ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table u883604362_php.sanpham_dondathang: ~0 rows (approximately)
/*!40000 ALTER TABLE `sanpham_dondathang` DISABLE KEYS */;
INSERT INTO `sanpham_dondathang` (`sp_ma`, `dh_ma`, `sp_dh_soluong`, `sp_dh_dongia`) VALUES
	(1, 5, 3, 12000000.00),
	(5, 5, 2, 10990000.00),
	(7, 4, 1, 7500000.00);
/*!40000 ALTER TABLE `sanpham_dondathang` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
