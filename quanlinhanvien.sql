-- phpMyAdmin SQL Dump
-- version 4.6.6
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jun 29, 2024 at 01:17 PM
-- Server version: 5.7.17-log
-- PHP Version: 5.6.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `quanlinhanvien`
--

-- --------------------------------------------------------

--
-- Table structure for table `chuong_trinh_dao_tao`
--

CREATE TABLE `chuong_trinh_dao_tao` (
  `chuong_trinh_id` int(11) NOT NULL,
  `ten_chuong_trinh` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `doi_tuong` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `thoi_luong` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `hinh_thuc` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `chuong_trinh_dao_tao`
--

INSERT INTO `chuong_trinh_dao_tao` (`chuong_trinh_id`, `ten_chuong_trinh`, `doi_tuong`, `thoi_luong`, `hinh_thuc`) VALUES
(1, 'Chương trình 2', 'Đối tượng 1', '120p', 'Online'),
(2, 'Chương trình 1', '1', '1', '1'),
(3, 'Chương trình 1', 'Quản lý', '120p', 'Online'),
(4, 'Chương trình 3', 'Giáo viên', '120', 'Tại chỗ'),
(5, 'Chương trình đào tạo 5', 'Nhân viên', '690', 'Trực tuyến'),
(6, 'Chương trinh 6', 'Nhân viên', '120', 'Tại chỗ'),
(7, 'Chương trình 7', 'Nhân viên', '233', 'Tại chỗ');

-- --------------------------------------------------------

--
-- Table structure for table `dang_ky_dao_tao`
--

CREATE TABLE `dang_ky_dao_tao` (
  `dang_ky_id` int(11) NOT NULL,
  `tai_khoan_id` int(11) DEFAULT NULL,
  `chuong_trinh_id` int(11) DEFAULT NULL,
  `ngay_dang_ky` date DEFAULT NULL,
  `trang_thai` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `dang_ky_dao_tao`
--

INSERT INTO `dang_ky_dao_tao` (`dang_ky_id`, `tai_khoan_id`, `chuong_trinh_id`, `ngay_dang_ky`, `trang_thai`) VALUES
(1, 5, 2, '2024-06-27', 'Đã duyệt'),
(2, 11, 1, '2024-06-27', 'Đã duyệt'),
(3, 11, 4, '2024-06-28', 'Đã duyệt'),
(4, 11, 2, '2024-06-28', 'Đã duyệt'),
(5, 11, 2, '2024-06-28', 'Đã duyệt'),
(6, 11, 3, '2024-06-28', 'Đã duyệt'),
(7, 12, 5, '2024-06-28', 'Đã duyệt'),
(8, 12, 4, '2024-06-28', 'Đã duyệt'),
(9, 12, 3, '2024-06-28', 'Đã duyệt'),
(10, 12, 1, '2024-06-28', 'Đã duyệt'),
(11, 11, 5, '2024-06-28', 'Đã duyệt'),
(12, 11, 5, '2024-06-28', 'Đã duyệt'),
(13, 11, 7, '2024-06-28', 'Đã duyệt');

-- --------------------------------------------------------

--
-- Table structure for table `danh_gia_dao_tao`
--

CREATE TABLE `danh_gia_dao_tao` (
  `danh_gia_id` int(11) NOT NULL,
  `nhan_vien_id` int(11) DEFAULT NULL,
  `chuong_trinh_id` int(11) DEFAULT NULL,
  `loai_danh_gia` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `diem_danh_gia` int(11) DEFAULT NULL,
  `nhan_xet` text COLLATE utf8_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `danh_gia_dao_tao`
--

INSERT INTO `danh_gia_dao_tao` (`danh_gia_id`, `nhan_vien_id`, `chuong_trinh_id`, `loai_danh_gia`, `diem_danh_gia`, `nhan_xet`) VALUES
(4, 5, 1, '2', 22, '22');

-- --------------------------------------------------------

--
-- Table structure for table `giang_vien`
--

CREATE TABLE `giang_vien` (
  `giang_vien_id` int(11) NOT NULL,
  `chuyen_mon` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `tai_khoan_id` int(11) DEFAULT NULL,
  `ngay_vao_dao_tao` date DEFAULT NULL,
  `trinh_do_hoc_van` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `kinh_nghiem_giang_day` int(11) DEFAULT NULL,
  `noi_cong_tac` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `dia_chi` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `giang_vien`
--

INSERT INTO `giang_vien` (`giang_vien_id`, `chuyen_mon`, `tai_khoan_id`, `ngay_vao_dao_tao`, `trinh_do_hoc_van`, `kinh_nghiem_giang_day`, `noi_cong_tac`, `dia_chi`) VALUES
(1, 'XXX', 6, '0000-00-00', '2', 2, '2', '2'),
(2, 'XXX', 9, '2024-06-28', '12/12', 23, '2', '2');

-- --------------------------------------------------------

--
-- Table structure for table `lich_trinh_dao_tao`
--

CREATE TABLE `lich_trinh_dao_tao` (
  `lich_trinh_id` int(11) NOT NULL,
  `chuong_trinh_id` int(11) DEFAULT NULL,
  `ngay_bat_dau` date DEFAULT NULL,
  `ngay_ket_thuc` date DEFAULT NULL,
  `dia_diem` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `lich_trinh_dao_tao`
--

INSERT INTO `lich_trinh_dao_tao` (`lich_trinh_id`, `chuong_trinh_id`, `ngay_bat_dau`, `ngay_ket_thuc`, `dia_diem`) VALUES
(1, 2, '2024-05-30', '2024-07-06', 'Cần Thơ'),
(2, 1, '2024-06-04', '2024-06-13', 'Cần Thơ');

-- --------------------------------------------------------

--
-- Table structure for table `nhu_cau_dao_tao`
--

CREATE TABLE `nhu_cau_dao_tao` (
  `nhu_cau_id` int(11) NOT NULL,
  `nhan_vien_id` int(11) DEFAULT NULL,
  `loai_ky_nang` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `muc_ky_nang` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `nhan_xet_quan_ly` text COLLATE utf8_unicode_ci,
  `ket_qua_khao_sat` text COLLATE utf8_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `nhu_cau_dao_tao`
--

INSERT INTO `nhu_cau_dao_tao` (`nhu_cau_id`, `nhan_vien_id`, `loai_ky_nang`, `muc_ky_nang`, `nhan_xet_quan_ly`, `ket_qua_khao_sat`) VALUES
(6, 5, 'kien_thuc', 'co_ban', 'Tốt', 'Đạt 9.5/10');

-- --------------------------------------------------------

--
-- Table structure for table `noi_dung_dao_tao`
--

CREATE TABLE `noi_dung_dao_tao` (
  `noi_dung_id` int(11) NOT NULL,
  `chuong_trinh_id` int(11) DEFAULT NULL,
  `loai_noi_dung` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `tieu_de` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `mo_ta` text COLLATE utf8_unicode_ci,
  `duong_dan_tap_tin` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `chu_de` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `noi_dung_dao_tao`
--

INSERT INTO `noi_dung_dao_tao` (`noi_dung_id`, `chuong_trinh_id`, `loai_noi_dung`, `tieu_de`, `mo_ta`, `duong_dan_tap_tin`, `chu_de`) VALUES
(2, 1, 'theo_chu_de', '222', '22', 'uploads/YeuCau2806.docx', '2'),
(3, 1, 'theo_chu_de', '1', '33', 'uploads/LuuY.docx', '1'),
(4, 1, 'theo_chu_de', '222', 'ss', 'uploads/NgoDinhThanhNhan_TES (1).pdf', '1'),
(5, 1, 'theo_chu_de', 'Demo', '22', 'uploads/LuuY.docx', '22');

-- --------------------------------------------------------

--
-- Table structure for table `phan_cong_giang_vien`
--

CREATE TABLE `phan_cong_giang_vien` (
  `phan_cong_id` int(11) NOT NULL,
  `chuong_trinh_id` int(11) DEFAULT NULL,
  `giang_vien_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `phan_cong_giang_vien`
--

INSERT INTO `phan_cong_giang_vien` (`phan_cong_id`, `chuong_trinh_id`, `giang_vien_id`) VALUES
(22, 1, 2);

-- --------------------------------------------------------

--
-- Table structure for table `tai_khoan`
--

CREATE TABLE `tai_khoan` (
  `id_tai_khoan` int(11) NOT NULL,
  `ten` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `phong_ban` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `vi_tri` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `so_dien_thoai` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `vai_tro` enum('QuanTriVien','NhanVien','GiangVien') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'NhanVien',
  `ten_dang_nhap` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `mat_khau` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `tai_khoan`
--

INSERT INTO `tai_khoan` (`id_tai_khoan`, `ten`, `phong_ban`, `vi_tri`, `email`, `so_dien_thoai`, `vai_tro`, `ten_dang_nhap`, `mat_khau`) VALUES
(5, 'Ngô Thanh Tân', '1', '1', 'tripvang123@gmail.com', '09999999999', 'QuanTriVien', 'admin', '21232f297a57a5a743894a0e4a801fc3'),
(6, 'Nguyễn Thị Giảng Viên', '123', '123', 'test@gmail.com', '09999999999', 'GiangVien', 'giangvien', '2f830951c2e27fcf934a92d091971a02'),
(9, 'Ngô Thanh B', '123', '123', 'tripvang1234@gmail.com', '0900000022', 'GiangVien', 'giangvien123', 'cdc65669b88161faf5a31babd3e1e0dd'),
(11, 'Nhân viên', '12', '12', 'tripvang12365@gmail.com', '09999999999', 'NhanVien', 'nhanvien', '2a2fa4fe2fa737f129ef2d127b861b7e'),
(12, 'Nhân viên test 1', '24', '123', 'test1@gmail.com', '09999999999', 'NhanVien', 'nhanvien1', 'fcf321d09609565b7a1ce6e70f1f5056');

-- --------------------------------------------------------

--
-- Table structure for table `thong_bao`
--

CREATE TABLE `thong_bao` (
  `id` int(11) NOT NULL,
  `tai_khoan_id` int(11) DEFAULT NULL,
  `noi_dung` text COLLATE utf8_unicode_ci,
  `trang_thai` tinyint(1) DEFAULT '0',
  `thoi_gian` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `thong_bao`
--

INSERT INTO `thong_bao` (`id`, `tai_khoan_id`, `noi_dung`, `trang_thai`, `thoi_gian`) VALUES
(4, 6, 'Bạn đã được phân công vào chương trình đào tạo mới.', 1, '2024-06-29 12:09:04'),
(5, 6, 'Bạn đã bị xóa phân công khỏi chương trình đào tạo.', 1, '2024-06-29 12:09:43'),
(6, 6, 'Bạn đã được phân công vào chương trình đào tạo mới.', 1, '2024-06-29 12:10:59'),
(7, 5, 'Bạn đã bị xóa phân công khỏi chương trình đào tạo.', 0, '2024-06-29 12:15:35'),
(8, 5, 'Bạn đã bị xóa phân công khỏi chương trình đào tạo.', 0, '2024-06-29 12:15:41'),
(9, 6, 'Bạn đã được phân công vào chương trình đào tạo mới.', 1, '2024-06-29 12:16:32'),
(10, 6, 'Bạn đã bị xóa phân công khỏi chương trình đào tạo.', 1, '2024-06-29 12:20:17'),
(11, 9, 'Bạn đã được phân công vào chương trình đào tạo mới.', 0, '2024-06-29 12:20:52'),
(12, 9, 'Bạn đã bị xóa phân công khỏi chương trình đào tạo.', 0, '2024-06-29 12:53:01'),
(13, 9, 'Bạn đã được phân công vào chương trình đào tạo mới.', 0, '2024-06-29 12:53:12');

-- --------------------------------------------------------

--
-- Table structure for table `tien_do_hoc_tap`
--

CREATE TABLE `tien_do_hoc_tap` (
  `tien_do_id` int(11) NOT NULL,
  `nhan_vien_id` int(11) DEFAULT NULL,
  `chuong_trinh_id` int(11) DEFAULT NULL,
  `noi_dung_id` int(11) DEFAULT NULL,
  `trang_thai` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ngay_cap_nhat` date DEFAULT NULL,
  `ghi_chu` text COLLATE utf8_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `tien_do_hoc_tap`
--

INSERT INTO `tien_do_hoc_tap` (`tien_do_id`, `nhan_vien_id`, `chuong_trinh_id`, `noi_dung_id`, `trang_thai`, `ngay_cap_nhat`, `ghi_chu`) VALUES
(1, 11, 4, 4, 'Đã hoàn thành', '2024-06-28', '1'),
(2, 11, 3, 3, 'Chưa hoàn thành', '2024-06-28', 'cccfsdfs'),
(3, 12, 1, 3, 'HoanThanh', '2024-06-28', 'Hoàn thành xuất sắc'),
(4, 12, 1, 5, 'HoanThanh', '2024-06-28', 'Check');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `chuong_trinh_dao_tao`
--
ALTER TABLE `chuong_trinh_dao_tao`
  ADD PRIMARY KEY (`chuong_trinh_id`);

--
-- Indexes for table `dang_ky_dao_tao`
--
ALTER TABLE `dang_ky_dao_tao`
  ADD PRIMARY KEY (`dang_ky_id`),
  ADD KEY `tai_khoan_id` (`tai_khoan_id`),
  ADD KEY `chuong_trinh_id` (`chuong_trinh_id`);

--
-- Indexes for table `danh_gia_dao_tao`
--
ALTER TABLE `danh_gia_dao_tao`
  ADD PRIMARY KEY (`danh_gia_id`),
  ADD KEY `nhan_vien_id` (`nhan_vien_id`),
  ADD KEY `chuong_trinh_id` (`chuong_trinh_id`);

--
-- Indexes for table `giang_vien`
--
ALTER TABLE `giang_vien`
  ADD PRIMARY KEY (`giang_vien_id`),
  ADD KEY `fk_giang_vien_tai_khoan` (`tai_khoan_id`);

--
-- Indexes for table `lich_trinh_dao_tao`
--
ALTER TABLE `lich_trinh_dao_tao`
  ADD PRIMARY KEY (`lich_trinh_id`),
  ADD KEY `chuong_trinh_id` (`chuong_trinh_id`);

--
-- Indexes for table `nhu_cau_dao_tao`
--
ALTER TABLE `nhu_cau_dao_tao`
  ADD PRIMARY KEY (`nhu_cau_id`),
  ADD KEY `nhan_vien_id` (`nhan_vien_id`);

--
-- Indexes for table `noi_dung_dao_tao`
--
ALTER TABLE `noi_dung_dao_tao`
  ADD PRIMARY KEY (`noi_dung_id`),
  ADD KEY `chuong_trinh_id` (`chuong_trinh_id`);

--
-- Indexes for table `phan_cong_giang_vien`
--
ALTER TABLE `phan_cong_giang_vien`
  ADD PRIMARY KEY (`phan_cong_id`),
  ADD KEY `chuong_trinh_id` (`chuong_trinh_id`),
  ADD KEY `giang_vien_id` (`giang_vien_id`);

--
-- Indexes for table `tai_khoan`
--
ALTER TABLE `tai_khoan`
  ADD PRIMARY KEY (`id_tai_khoan`),
  ADD UNIQUE KEY `ten_dang_nhap` (`ten_dang_nhap`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `thong_bao`
--
ALTER TABLE `thong_bao`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tai_khoan_id` (`tai_khoan_id`);

--
-- Indexes for table `tien_do_hoc_tap`
--
ALTER TABLE `tien_do_hoc_tap`
  ADD PRIMARY KEY (`tien_do_id`),
  ADD KEY `nhan_vien_id` (`nhan_vien_id`),
  ADD KEY `chuong_trinh_id` (`chuong_trinh_id`),
  ADD KEY `noi_dung_id` (`noi_dung_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `chuong_trinh_dao_tao`
--
ALTER TABLE `chuong_trinh_dao_tao`
  MODIFY `chuong_trinh_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `dang_ky_dao_tao`
--
ALTER TABLE `dang_ky_dao_tao`
  MODIFY `dang_ky_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
--
-- AUTO_INCREMENT for table `danh_gia_dao_tao`
--
ALTER TABLE `danh_gia_dao_tao`
  MODIFY `danh_gia_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `giang_vien`
--
ALTER TABLE `giang_vien`
  MODIFY `giang_vien_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `lich_trinh_dao_tao`
--
ALTER TABLE `lich_trinh_dao_tao`
  MODIFY `lich_trinh_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `nhu_cau_dao_tao`
--
ALTER TABLE `nhu_cau_dao_tao`
  MODIFY `nhu_cau_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `noi_dung_dao_tao`
--
ALTER TABLE `noi_dung_dao_tao`
  MODIFY `noi_dung_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `phan_cong_giang_vien`
--
ALTER TABLE `phan_cong_giang_vien`
  MODIFY `phan_cong_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;
--
-- AUTO_INCREMENT for table `tai_khoan`
--
ALTER TABLE `tai_khoan`
  MODIFY `id_tai_khoan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;
--
-- AUTO_INCREMENT for table `thong_bao`
--
ALTER TABLE `thong_bao`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
--
-- AUTO_INCREMENT for table `tien_do_hoc_tap`
--
ALTER TABLE `tien_do_hoc_tap`
  MODIFY `tien_do_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `dang_ky_dao_tao`
--
ALTER TABLE `dang_ky_dao_tao`
  ADD CONSTRAINT `dang_ky_dao_tao_ibfk_1` FOREIGN KEY (`tai_khoan_id`) REFERENCES `tai_khoan` (`id_tai_khoan`),
  ADD CONSTRAINT `dang_ky_dao_tao_ibfk_2` FOREIGN KEY (`chuong_trinh_id`) REFERENCES `chuong_trinh_dao_tao` (`chuong_trinh_id`);

--
-- Constraints for table `danh_gia_dao_tao`
--
ALTER TABLE `danh_gia_dao_tao`
  ADD CONSTRAINT `danh_gia_dao_tao_ibfk_1` FOREIGN KEY (`nhan_vien_id`) REFERENCES `tai_khoan` (`id_tai_khoan`),
  ADD CONSTRAINT `danh_gia_dao_tao_ibfk_2` FOREIGN KEY (`chuong_trinh_id`) REFERENCES `chuong_trinh_dao_tao` (`chuong_trinh_id`);

--
-- Constraints for table `giang_vien`
--
ALTER TABLE `giang_vien`
  ADD CONSTRAINT `fk_giang_vien_tai_khoan` FOREIGN KEY (`tai_khoan_id`) REFERENCES `tai_khoan` (`id_tai_khoan`) ON DELETE SET NULL;

--
-- Constraints for table `lich_trinh_dao_tao`
--
ALTER TABLE `lich_trinh_dao_tao`
  ADD CONSTRAINT `lich_trinh_dao_tao_ibfk_1` FOREIGN KEY (`chuong_trinh_id`) REFERENCES `chuong_trinh_dao_tao` (`chuong_trinh_id`);

--
-- Constraints for table `nhu_cau_dao_tao`
--
ALTER TABLE `nhu_cau_dao_tao`
  ADD CONSTRAINT `nhu_cau_dao_tao_ibfk_1` FOREIGN KEY (`nhan_vien_id`) REFERENCES `tai_khoan` (`id_tai_khoan`);

--
-- Constraints for table `noi_dung_dao_tao`
--
ALTER TABLE `noi_dung_dao_tao`
  ADD CONSTRAINT `noi_dung_dao_tao_ibfk_1` FOREIGN KEY (`chuong_trinh_id`) REFERENCES `chuong_trinh_dao_tao` (`chuong_trinh_id`);

--
-- Constraints for table `phan_cong_giang_vien`
--
ALTER TABLE `phan_cong_giang_vien`
  ADD CONSTRAINT `phan_cong_giang_vien_ibfk_1` FOREIGN KEY (`chuong_trinh_id`) REFERENCES `chuong_trinh_dao_tao` (`chuong_trinh_id`),
  ADD CONSTRAINT `phan_cong_giang_vien_ibfk_2` FOREIGN KEY (`giang_vien_id`) REFERENCES `giang_vien` (`giang_vien_id`);

--
-- Constraints for table `thong_bao`
--
ALTER TABLE `thong_bao`
  ADD CONSTRAINT `thong_bao_ibfk_1` FOREIGN KEY (`tai_khoan_id`) REFERENCES `tai_khoan` (`id_tai_khoan`);

--
-- Constraints for table `tien_do_hoc_tap`
--
ALTER TABLE `tien_do_hoc_tap`
  ADD CONSTRAINT `tien_do_hoc_tap_ibfk_1` FOREIGN KEY (`nhan_vien_id`) REFERENCES `tai_khoan` (`id_tai_khoan`),
  ADD CONSTRAINT `tien_do_hoc_tap_ibfk_2` FOREIGN KEY (`chuong_trinh_id`) REFERENCES `chuong_trinh_dao_tao` (`chuong_trinh_id`),
  ADD CONSTRAINT `tien_do_hoc_tap_ibfk_3` FOREIGN KEY (`noi_dung_id`) REFERENCES `noi_dung_dao_tao` (`noi_dung_id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
