-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th10 03, 2025 lúc 09:54 PM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `fivevac_db`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `bacsi`
--

CREATE TABLE `bacsi` (
  `MaBS` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `bacsi`
--

INSERT INTO `bacsi` (`MaBS`) VALUES
('ND001'),
('ND002'),
('ND003'),
('ND004'),
('ND005'),
('ND006'),
('ND007'),
('ND008'),
('ND009'),
('ND010'),
('ND011'),
('ND012'),
('ND013'),
('ND014'),
('ND015'),
('ND016'),
('ND017'),
('ND018');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `chinhanh`
--

CREATE TABLE `chinhanh` (
  `MaChiNhanh` varchar(50) NOT NULL,
  `TenChiNhanh` varchar(100) NOT NULL,
  `DiaChi` varchar(255) DEFAULT NULL,
  `TrangThaiHD` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `chinhanh`
--

INSERT INTO `chinhanh` (`MaChiNhanh`, `TenChiNhanh`, `DiaChi`, `TrangThaiHD`) VALUES
('CN001', 'Chi Nhánh Hà Nội', '123 Lê Lợi, Hà Nội', 'Hoạt động'),
('CN002', 'Chi Nhánh TP.HCM', '456 Trần Phú, TP.HCM', 'Hoạt động'),
('CN003', 'Chi Nhánh Đà Nẵng', '789 Nguyễn Trãi, Đà Nẵng', 'Hoạt động'),
('CN004', 'Chi Nhánh Hải Phòng', '101 Hai Bà Trưng, Hải Phòng', 'Hoạt động'),
('CN005', 'Chi Nhánh Cần Thơ', '202 Lý Thường Kiệt, Cần Thơ', 'Hoạt động');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `chitiettonkho`
--

CREATE TABLE `chitiettonkho` (
  `MaCTTK` varchar(50) NOT NULL,
  `MaChiNhanh` varchar(50) NOT NULL,
  `MaVacXin` varchar(50) DEFAULT NULL,
  `SoLuongHienTai` int(11) DEFAULT NULL,
  `SoLuongDaSuDung` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `chitiettonkho`
--

INSERT INTO `chitiettonkho` (`MaCTTK`, `MaChiNhanh`, `MaVacXin`, `SoLuongHienTai`, `SoLuongDaSuDung`) VALUES
('CTTK001', 'CN001', 'VX001', 43, 7),
('CTTK002', 'CN001', 'VX002', 43, 7),
('CTTK003', 'CN001', 'VX003', 43, 7),
('CTTK004', 'CN001', 'VX004', 43, 7),
('CTTK005', 'CN001', 'VX005', 43, 7),
('CTTK006', 'CN001', 'VX006', 43, 7),
('CTTK007', 'CN001', 'VX007', 43, 7),
('CTTK008', 'CN001', 'VX008', 43, 7),
('CTTK009', 'CN002', 'VX001', 43, 7),
('CTTK010', 'CN002', 'VX002', 43, 7),
('CTTK011', 'CN002', 'VX003', 43, 7),
('CTTK012', 'CN002', 'VX004', 43, 7),
('CTTK013', 'CN002', 'VX005', 43, 7),
('CTTK014', 'CN002', 'VX006', 43, 7),
('CTTK015', 'CN002', 'VX007', 43, 7),
('CTTK016', 'CN002', 'VX008', 43, 7),
('CTTK017', 'CN003', 'VX001', 43, 7),
('CTTK018', 'CN003', 'VX002', 43, 7),
('CTTK019', 'CN003', 'VX003', 43, 7),
('CTTK020', 'CN003', 'VX004', 43, 7),
('CTTK021', 'CN003', 'VX005', 43, 7),
('CTTK022', 'CN003', 'VX006', 43, 7),
('CTTK023', 'CN003', 'VX007', 43, 7),
('CTTK024', 'CN003', 'VX008', 43, 7),
('CTTK025', 'CN004', 'VX001', 43, 7),
('CTTK026', 'CN004', 'VX002', 43, 7),
('CTTK027', 'CN004', 'VX003', 43, 7),
('CTTK028', 'CN004', 'VX004', 43, 7),
('CTTK029', 'CN004', 'VX005', 43, 7),
('CTTK030', 'CN004', 'VX006', 43, 7),
('CTTK031', 'CN004', 'VX007', 43, 7),
('CTTK032', 'CN004', 'VX008', 43, 7),
('CTTK033', 'CN005', 'VX001', 43, 7),
('CTTK034', 'CN005', 'VX002', 43, 7),
('CTTK035', 'CN005', 'VX003', 43, 7),
('CTTK036', 'CN005', 'VX004', 43, 7),
('CTTK037', 'CN005', 'VX005', 43, 7),
('CTTK038', 'CN005', 'VX006', 43, 7),
('CTTK039', 'CN005', 'VX007', 43, 7),
('CTTK040', 'CN005', 'VX008', 43, 7);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `donhangonl`
--

CREATE TABLE `donhangonl` (
  `MaDHonl` varchar(50) NOT NULL,
  `MaTV` varchar(50) DEFAULT NULL,
  `MaChiNhanh` varchar(50) DEFAULT NULL,
  `MaVacXin` varchar(50) DEFAULT NULL,
  `NgayTao` date DEFAULT NULL,
  `ThanhTien` decimal(12,0) DEFAULT NULL,
  `HinhThucThanhToan` varchar(50) DEFAULT NULL,
  `TrangThaiDH` enum('Chờ xử lý','Đang xử lý','Thành công','Thất bại') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `donhangonl`
--

INSERT INTO `donhangonl` (`MaDHonl`, `MaTV`, `MaChiNhanh`, `MaVacXin`, `NgayTao`, `ThanhTien`, `HinhThucThanhToan`, `TrangThaiDH`) VALUES
('ONL001', 'ND032', 'CN001', 'VX001', '2025-01-01', 150000, 'Chuyển khoản', 'Thành công'),
('ONL002', 'ND033', 'CN002', 'VX002', '2025-01-02', 200000, 'Chuyển khoản', 'Thành công'),
('ONL003', 'ND034', 'CN003', 'VX003', '2025-01-03', 250000, 'Chuyển khoản', 'Thành công'),
('ONL004', 'ND035', 'CN004', 'VX004', '2025-01-04', 180000, 'Chuyển khoản', 'Thành công'),
('ONL005', 'ND036', 'CN005', 'VX005', '2025-01-05', 500000, 'Chuyển khoản', 'Thành công'),
('ONL006', 'ND037', 'CN001', 'VX006', '2025-02-01', 600000, 'Chuyển khoản', 'Thành công'),
('ONL007', 'ND038', 'CN002', 'VX007', '2025-02-02', 400000, 'Chuyển khoản', 'Thành công'),
('ONL008', 'ND039', 'CN003', 'VX008', '2025-02-03', 120000, 'Chuyển khoản', 'Thành công'),
('ONL009', 'ND040', 'CN004', 'VX001', '2025-02-04', 150000, 'Chuyển khoản', 'Thành công'),
('ONL010', 'ND041', 'CN005', 'VX002', '2025-02-05', 200000, 'Chuyển khoản', 'Thành công'),
('ONL011', 'ND042', 'CN001', 'VX003', '2025-03-01', 250000, 'Chuyển khoản', 'Thành công'),
('ONL012', 'ND043', 'CN002', 'VX004', '2025-03-02', 180000, 'Chuyển khoản', 'Thành công'),
('ONL013', 'ND044', 'CN003', 'VX005', '2025-03-03', 500000, 'Chuyển khoản', 'Thành công'),
('ONL014', 'ND045', 'CN004', 'VX006', '2025-03-04', 600000, 'Chuyển khoản', 'Thành công'),
('ONL015', 'ND046', 'CN005', 'VX007', '2025-03-05', 400000, 'Chuyển khoản', 'Thành công');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `donhangpos`
--

CREATE TABLE `donhangpos` (
  `MaDHpos` varchar(50) NOT NULL,
  `MaKH` varchar(50) DEFAULT NULL,
  `MaChiNhanh` varchar(50) DEFAULT NULL,
  `MaVacXin` varchar(50) DEFAULT NULL,
  `NgayTao` date DEFAULT NULL,
  `ThanhTien` decimal(12,0) DEFAULT NULL,
  `HinhThucThanhToan` varchar(50) DEFAULT NULL,
  `TrangThaiDH` enum('Chờ xử lý','Đang xử lý','Thành công','Thất bại') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `donhangpos`
--

INSERT INTO `donhangpos` (`MaDHpos`, `MaKH`, `MaChiNhanh`, `MaVacXin`, `NgayTao`, `ThanhTien`, `HinhThucThanhToan`, `TrangThaiDH`) VALUES
('POS001', 'KH001', 'CN001', 'VX001', '2025-01-01', 150000, 'Tiền mặt', 'Thành công'),
('POS002', 'KH002', 'CN001', 'VX002', '2025-01-02', 200000, 'Chuyển khoản', 'Thành công'),
('POS003', 'KH003', 'CN001', 'VX003', '2025-01-03', 250000, 'Tiền mặt', 'Thành công'),
('POS004', 'KH004', 'CN001', 'VX004', '2025-01-04', 180000, 'Chuyển khoản', 'Thành công'),
('POS005', 'KH005', 'CN001', 'VX005', '2025-01-05', 500000, 'Tiền mặt', 'Thành công'),
('POS006', 'KH006', 'CN001', 'VX006', '2025-01-06', 600000, 'Chuyển khoản', 'Thành công'),
('POS007', 'KH007', 'CN002', 'VX007', '2025-01-01', 400000, 'Tiền mặt', 'Thành công'),
('POS008', 'KH008', 'CN002', 'VX008', '2025-01-02', 120000, 'Chuyển khoản', 'Thành công'),
('POS009', 'KH009', 'CN002', 'VX001', '2025-01-03', 150000, 'Tiền mặt', 'Thành công'),
('POS010', 'KH010', 'CN002', 'VX002', '2025-01-04', 200000, 'Chuyển khoản', 'Thành công'),
('POS011', 'KH011', 'CN002', 'VX003', '2025-01-05', 250000, 'Tiền mặt', 'Thành công'),
('POS012', 'KH012', 'CN002', 'VX004', '2025-01-06', 180000, 'Chuyển khoản', 'Thành công'),
('POS013', 'KH013', 'CN003', 'VX005', '2025-01-01', 500000, 'Tiền mặt', 'Thành công'),
('POS014', 'KH014', 'CN003', 'VX006', '2025-01-02', 600000, 'Chuyển khoản', 'Thành công'),
('POS015', 'KH015', 'CN003', 'VX007', '2025-01-03', 400000, 'Tiền mặt', 'Thành công'),
('POS016', 'KH016', 'CN003', 'VX008', '2025-01-04', 120000, 'Chuyển khoản', 'Thành công'),
('POS017', 'KH017', 'CN003', 'VX001', '2025-01-05', 150000, 'Tiền mặt', 'Thành công'),
('POS018', 'KH018', 'CN003', 'VX002', '2025-01-06', 200000, 'Chuyển khoản', 'Thành công'),
('POS019', 'KH019', 'CN004', 'VX003', '2025-01-01', 250000, 'Tiền mặt', 'Thành công'),
('POS020', 'KH020', 'CN004', 'VX004', '2025-01-02', 180000, 'Chuyển khoản', 'Thành công'),
('POS021', 'KH021', 'CN004', 'VX005', '2025-01-03', 500000, 'Tiền mặt', 'Thành công'),
('POS022', 'KH022', 'CN004', 'VX006', '2025-01-04', 600000, 'Chuyển khoản', 'Thành công'),
('POS023', 'KH023', 'CN004', 'VX007', '2025-01-05', 400000, 'Tiền mặt', 'Thành công'),
('POS024', 'KH024', 'CN004', 'VX008', '2025-01-06', 120000, 'Chuyển khoản', 'Thành công'),
('POS025', 'KH025', 'CN005', 'VX001', '2025-01-01', 150000, 'Tiền mặt', 'Thành công'),
('POS026', 'KH026', 'CN005', 'VX002', '2025-01-02', 200000, 'Chuyển khoản', 'Thành công'),
('POS027', 'KH027', 'CN005', 'VX003', '2025-01-03', 250000, 'Tiền mặt', 'Thành công'),
('POS028', 'KH028', 'CN005', 'VX004', '2025-01-04', 180000, 'Chuyển khoản', 'Thành công'),
('POS029', 'KH029', 'CN005', 'VX005', '2025-01-05', 500000, 'Tiền mặt', 'Thành công'),
('POS030', 'KH030', 'CN005', 'VX006', '2025-01-06', 600000, 'Chuyển khoản', 'Thành công'),
('POS031', 'KH031', 'CN001', 'VX007', '2025-02-01', 400000, 'Tiền mặt', 'Thành công'),
('POS032', 'KH032', 'CN001', 'VX008', '2025-02-02', 120000, 'Chuyển khoản', 'Thành công'),
('POS033', 'KH033', 'CN001', 'VX001', '2025-02-03', 150000, 'Tiền mặt', 'Thành công'),
('POS034', 'KH034', 'CN001', 'VX002', '2025-02-04', 200000, 'Chuyển khoản', 'Thành công'),
('POS035', 'KH035', 'CN001', 'VX003', '2025-02-05', 250000, 'Tiền mặt', 'Thành công'),
('POS036', 'KH036', 'CN001', 'VX004', '2025-02-06', 180000, 'Chuyển khoản', 'Thành công'),
('POS037', 'KH037', 'CN002', 'VX005', '2025-02-01', 500000, 'Tiền mặt', 'Thành công'),
('POS038', 'KH038', 'CN002', 'VX006', '2025-02-02', 600000, 'Chuyển khoản', 'Thành công'),
('POS039', 'KH039', 'CN002', 'VX007', '2025-02-03', 400000, 'Tiền mặt', 'Thành công'),
('POS040', 'KH040', 'CN002', 'VX008', '2025-02-04', 120000, 'Chuyển khoản', 'Thành công'),
('POS041', 'KH041', 'CN002', 'VX001', '2025-02-05', 150000, 'Tiền mặt', 'Thành công'),
('POS042', 'KH042', 'CN002', 'VX002', '2025-02-06', 200000, 'Chuyển khoản', 'Thành công'),
('POS043', 'KH043', 'CN003', 'VX003', '2025-02-01', 250000, 'Tiền mặt', 'Thành công'),
('POS044', 'KH044', 'CN003', 'VX004', '2025-02-02', 180000, 'Chuyển khoản', 'Thành công'),
('POS045', 'KH045', 'CN003', 'VX005', '2025-02-03', 500000, 'Tiền mặt', 'Thành công'),
('POS046', 'KH046', 'CN003', 'VX006', '2025-02-04', 600000, 'Chuyển khoản', 'Thành công'),
('POS047', 'KH047', 'CN003', 'VX007', '2025-02-05', 400000, 'Tiền mặt', 'Thành công'),
('POS048', 'KH048', 'CN003', 'VX008', '2025-02-06', 120000, 'Chuyển khoản', 'Thành công'),
('POS049', 'KH049', 'CN004', 'VX001', '2025-02-01', 150000, 'Tiền mặt', 'Thành công'),
('POS050', 'KH050', 'CN004', 'VX002', '2025-02-02', 200000, 'Chuyển khoản', 'Thành công'),
('POS051', 'KH051', 'CN004', 'VX003', '2025-02-03', 250000, 'Tiền mặt', 'Thành công'),
('POS052', 'KH052', 'CN004', 'VX004', '2025-02-04', 180000, 'Chuyển khoản', 'Thành công'),
('POS053', 'KH053', 'CN004', 'VX005', '2025-02-05', 500000, 'Tiền mặt', 'Thành công'),
('POS054', 'KH054', 'CN004', 'VX006', '2025-02-06', 600000, 'Chuyển khoản', 'Thành công'),
('POS055', 'KH055', 'CN005', 'VX007', '2025-02-01', 400000, 'Tiền mặt', 'Thành công'),
('POS056', 'KH056', 'CN005', 'VX008', '2025-02-02', 120000, 'Chuyển khoản', 'Thành công'),
('POS057', 'KH057', 'CN005', 'VX001', '2025-02-03', 150000, 'Tiền mặt', 'Thành công'),
('POS058', 'KH058', 'CN005', 'VX002', '2025-02-04', 200000, 'Chuyển khoản', 'Thành công'),
('POS059', 'KH059', 'CN005', 'VX003', '2025-02-05', 250000, 'Tiền mặt', 'Thành công'),
('POS060', 'KH060', 'CN005', 'VX004', '2025-02-06', 180000, 'Chuyển khoản', 'Thành công'),
('POS061', 'KH061', 'CN001', 'VX005', '2025-03-01', 500000, 'Tiền mặt', 'Thành công'),
('POS062', 'KH062', 'CN001', 'VX006', '2025-03-02', 600000, 'Chuyển khoản', 'Thành công'),
('POS063', 'KH063', 'CN001', 'VX007', '2025-03-03', 400000, 'Tiền mặt', 'Thành công'),
('POS064', 'KH064', 'CN001', 'VX008', '2025-03-04', 120000, 'Chuyển khoản', 'Thành công'),
('POS065', 'KH065', 'CN001', 'VX001', '2025-03-05', 150000, 'Tiền mặt', 'Thành công'),
('POS066', 'KH066', 'CN001', 'VX002', '2025-03-06', 200000, 'Chuyển khoản', 'Thành công'),
('POS067', 'KH067', 'CN002', 'VX003', '2025-03-01', 250000, 'Tiền mặt', 'Thành công'),
('POS068', 'KH068', 'CN002', 'VX004', '2025-03-02', 180000, 'Chuyển khoản', 'Thành công'),
('POS069', 'KH069', 'CN002', 'VX005', '2025-03-03', 500000, 'Tiền mặt', 'Thành công'),
('POS070', 'KH070', 'CN002', 'VX006', '2025-03-04', 600000, 'Chuyển khoản', 'Thành công'),
('POS071', 'KH071', 'CN002', 'VX007', '2025-03-05', 400000, 'Tiền mặt', 'Thành công'),
('POS072', 'KH072', 'CN002', 'VX008', '2025-03-06', 120000, 'Chuyển khoản', 'Thành công'),
('POS073', 'KH073', 'CN003', 'VX001', '2025-03-01', 150000, 'Tiền mặt', 'Thành công'),
('POS074', 'KH074', 'CN003', 'VX002', '2025-03-02', 200000, 'Chuyển khoản', 'Thành công'),
('POS075', 'KH075', 'CN003', 'VX003', '2025-03-03', 250000, 'Tiền mặt', 'Thành công'),
('POS076', 'KH076', 'CN003', 'VX004', '2025-03-04', 180000, 'Chuyển khoản', 'Thành công'),
('POS077', 'KH077', 'CN003', 'VX005', '2025-03-05', 500000, 'Tiền mặt', 'Thành công'),
('POS078', 'KH078', 'CN003', 'VX006', '2025-03-06', 600000, 'Chuyển khoản', 'Thành công'),
('POS079', 'KH079', 'CN004', 'VX007', '2025-03-01', 400000, 'Tiền mặt', 'Thành công'),
('POS080', 'KH080', 'CN004', 'VX008', '2025-03-02', 120000, 'Chuyển khoản', 'Thành công'),
('POS081', 'KH081', 'CN004', 'VX001', '2025-03-03', 150000, 'Tiền mặt', 'Thành công'),
('POS082', 'KH082', 'CN004', 'VX002', '2025-03-04', 200000, 'Chuyển khoản', 'Thành công'),
('POS083', 'KH083', 'CN004', 'VX003', '2025-03-05', 250000, 'Tiền mặt', 'Thành công'),
('POS084', 'KH084', 'CN004', 'VX004', '2025-03-06', 180000, 'Chuyển khoản', 'Thành công'),
('POS085', 'KH085', 'CN005', 'VX005', '2025-03-01', 500000, 'Tiền mặt', 'Thành công'),
('POS086', 'KH001', 'CN005', 'VX006', '2025-03-02', 600000, 'Chuyển khoản', 'Thành công'),
('POS087', 'KH002', 'CN005', 'VX007', '2025-03-03', 400000, 'Tiền mặt', 'Thành công'),
('POS088', 'KH003', 'CN005', 'VX008', '2025-03-04', 120000, 'Chuyển khoản', 'Thành công'),
('POS089', 'KH004', 'CN005', 'VX001', '2025-03-05', 150000, 'Tiền mặt', 'Thành công'),
('POS090', 'KH005', 'CN005', 'VX002', '2025-03-06', 200000, 'Chuyển khoản', 'Thành công');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `lichhentiem`
--

CREATE TABLE `lichhentiem` (
  `MaLichHen` varchar(50) NOT NULL,
  `MaDHonl` varchar(50) NOT NULL,
  `NgayGio` datetime DEFAULT NULL,
  `GioMoi` datetime DEFAULT NULL,
  `TrangThai` enum('Đã tạo','Chờ phê duyệt','Chấp nhận','Từ chối') NOT NULL DEFAULT 'Đã tạo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `lichhentiem`
--

INSERT INTO `lichhentiem` (`MaLichHen`, `MaDHonl`, `NgayGio`, `GioMoi`, `TrangThai`) VALUES
('LH001', 'ONL001', '2025-01-02 09:00:00', NULL, 'Đã tạo'),
('LH002', 'ONL002', '2025-01-03 10:00:00', NULL, 'Đã tạo'),
('LH003', 'ONL003', '2025-01-04 11:00:00', NULL, 'Đã tạo'),
('LH004', 'ONL004', '2025-01-05 12:00:00', NULL, 'Đã tạo'),
('LH005', 'ONL005', '2025-01-06 13:00:00', NULL, 'Đã tạo'),
('LH006', 'ONL006', '2025-02-02 09:00:00', NULL, 'Đã tạo'),
('LH007', 'ONL007', '2025-02-03 10:00:00', NULL, 'Đã tạo'),
('LH008', 'ONL008', '2025-02-04 11:00:00', NULL, 'Đã tạo'),
('LH009', 'ONL009', '2025-02-05 12:00:00', NULL, 'Đã tạo'),
('LH010', 'ONL010', '2025-02-06 13:00:00', NULL, 'Đã tạo'),
('LH011', 'ONL011', '2025-03-02 09:00:00', NULL, 'Đã tạo'),
('LH012', 'ONL012', '2025-03-03 10:00:00', NULL, 'Đã tạo'),
('LH013', 'ONL013', '2025-03-04 11:00:00', NULL, 'Đã tạo'),
('LH014', 'ONL014', '2025-03-05 12:00:00', NULL, 'Đã tạo'),
('LH015', 'ONL015', '2025-03-06 13:00:00', NULL, 'Đã tạo');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `nguoidung`
--

CREATE TABLE `nguoidung` (
  `MaND` varchar(50) NOT NULL,
  `HoVaTen` varchar(100) NOT NULL,
  `NgaySinh` date DEFAULT NULL,
  `GioiTinh` enum('Nam','Nữ') DEFAULT NULL,
  `DiaChi` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `nguoidung`
--

INSERT INTO `nguoidung` (`MaND`, `HoVaTen`, `NgaySinh`, `GioiTinh`, `DiaChi`) VALUES
('ND001', 'Nguyễn Văn Hùng', '1980-03-15', 'Nam', '123 Lê Lợi, Hà Nội'),
('ND002', 'Trần Thị Ngân', '1985-07-20', 'Nữ', '456 Trần Phú, TP.HCM'),
('ND003', 'Lê Văn Sáng', '1978-11-10', 'Nam', '789 Nguyễn Trãi, Đà Nẵng'),
('ND004', 'Phạm Thị Huyền', '1983-05-25', 'Nữ', '101 Hai Bà Trưng, Hải Phòng'),
('ND005', 'Hoàng Văn Đạt', '1982-09-30', 'Nam', '202 Lý Thường Kiệt, Cần Thơ'),
('ND006', 'Vũ Thị Thơm', '1987-02-14', 'Nữ', '303 Trần Hưng Đạo, Hà Nội'),
('ND007', 'Đặng Văn Năng', '1980-06-18', 'Nam', '404 Phạm Văn Đồng, TP.HCM'),
('ND008', 'Bùi Thị Lành', '1984-12-22', 'Nữ', '505 Nguyễn Huệ, Đà Nẵng'),
('ND009', 'Nguyễn Văn Tấn', '1979-04-05', 'Nam', '606 Lê Duẩn, Hải Phòng'),
('ND010', 'Trần Thị Hảo', '1986-08-17', 'Nữ', '707 Hoàng Diệu, Cần Thơ'),
('ND011', 'Lê Văn Hợp', '1981-10-12', 'Nam', '808 Trần Phú, Hà Nội'),
('ND012', 'Phạm Thị Ngọ', '1985-01-27', 'Nữ', '909 Nguyễn Trãi, TP.HCM'),
('ND013', 'Hoàng Văn Khôi', '1983-03-19', 'Nam', '111 Lý Thường Kiệt, Đà Nẵng'),
('ND014', 'Vũ Thị Thắm', '1987-07-23', 'Nữ', '222 Trần Hưng Đạo, Hải Phòng'),
('ND015', 'Đặng Văn Lực', '1980-11-30', 'Nam', '333 Phạm Văn Đồng, Cần Thơ'),
('ND016', 'Bùi Thị Hằng', '1984-02-15', 'Nữ', '444 Nguyễn Huệ, Hà Nội'),
('ND017', 'Nguyễn Văn Quang', '1979-06-10', 'Nam', '555 Lê Duẩn, TP.HCM'),
('ND018', 'Trần Thị Phương', '1986-09-25', 'Nữ', '666 Hoàng Diệu, Đà Nẵng'),
('ND019', 'Nguyễn Văn Khải', '1975-04-01', 'Nam', '777 Lê Lợi, Hà Nội'),
('ND020', 'Trần Thị Minh', '1978-08-12', 'Nữ', '888 Trần Phú, TP.HCM'),
('ND021', 'Lê Văn Phúc', '1976-12-20', 'Nam', '999 Nguyễn Trãi, Đà Nẵng'),
('ND022', 'Phạm Văn Bảo', '1990-05-05', 'Nam', '111 Hai Bà Trưng, Hải Phòng'),
('ND023', 'Hoàng Thị Yến', '1992-09-15', 'Nữ', '222 Lý Thường Kiệt, Cần Thơ'),
('ND024', 'Vũ Văn Thái', '1991-03-22', 'Nam', '333 Trần Hưng Đạo, Hà Nội'),
('ND025', 'Đặng Thị Mãi', '1993-07-10', 'Nữ', '444 Phạm Văn Đồng, TP.HCM'),
('ND026', 'Bùi Văn Sơn', '1990-11-18', 'Nam', '555 Nguyễn Huệ, Đà Nẵng'),
('ND027', 'Nguyễn Thị Hoàn', '1992-02-25', 'Nữ', '666 Lê Duẩn, Hải Phòng'),
('ND028', 'Trần Văn Lượng', '1991-06-30', 'Nam', '777 Hoàng Diệu, Cần Thơ'),
('ND029', 'Lê Thị Ngọc', '1993-10-12', 'Nữ', '888 Trần Phú, Hà Nội'),
('ND030', 'Phạm Văn Tuấn', '1990-04-20', 'Nam', '999 Nguyễn Trãi, TP.HCM'),
('ND031', 'Hoàng Thị Lan', '1992-08-05', 'Nữ', '111 Lê Lợi, Đà Nẵng'),
('ND032', 'Nguyễn Thị Kiều', '1995-01-10', 'Nữ', '123 Trần Hưng Đạo, Hà Nội'),
('ND033', 'Trần Văn Hải', '1994-03-15', 'Nam', '234 Nguyễn Huệ, TP.HCM'),
('ND034', 'Lê Thị Thùy', '1996-07-20', 'Nữ', '345 Lê Duẩn, Đà Nẵng'),
('ND035', 'Phạm Văn Nẫm', '1993-11-25', 'Nam', '456 Hoàng Diệu, Hải Phòng'),
('ND036', 'Hoàng Thị Ánh', '1995-12-30', 'Nữ', '567 Trần Phú, Cần Thơ'),
('ND037', 'Vũ Văn Khoa', '1990-02-10', 'Nam', '678 Lê Lợi, Hà Nội'),
('ND038', 'Đặng Thị Làn', '1988-04-15', 'Nữ', '789 Trần Hưng Đạo, TP.HCM'),
('ND039', 'Bùi Văn Minh', '1992-12-30', 'Nam', '890 Nguyễn Huệ, Đà Nẵng'),
('ND040', 'Nguyễn Thị Hồng', '1994-06-05', 'Nữ', '901 Lê Duẩn, Hải Phòng'),
('ND041', 'Trần Văn Phước', '1993-08-20', 'Nam', '123 Hoàng Diệu, Cần Thơ'),
('ND042', 'Lê Thị Mẫn', '1995-03-10', 'Nữ', '234 Trần Phú, Hà Nội'),
('ND043', 'Phạm Văn Tuấn', '1992-09-15', 'Nam', '345 Lê Lợi, TP.HCM'),
('ND044', 'Hoàng Thị Yên', '1994-11-20', 'Nữ', '456 Nguyễn Trãi, Đà Nẵng'),
('ND045', 'Vũ Văn Lòng', '1993-05-25', 'Nam', '567 Trần Hưng Đạo, Hải Phòng'),
('ND046', 'Đặng Thị Hòa', '1995-07-30', 'Nữ', '678 Lê Duẩn, Cần Thơ'),
('ND047', 'Bùi Văn Nạm', '1992-01-05', 'Nam', '789 Hoàng Diệu, Hà Nội'),
('ND048', 'Nguyễn Thị Ngốc', '1994-02-10', 'Nữ', '890 Trần Phú, TP.HCM'),
('ND049', 'Trần Văn Khôi', '1993-03-15', 'Nam', '901 Lê Lợi, Đà Nẵng'),
('ND050', 'Lê Thị Làn', '1995-04-20', 'Nữ', '123 Nguyễn Huệ, Hải Phòng'),
('ND051', 'Phạm Văn Minh', '1992-05-25', 'Nam', '234 Trần Hưng Đạo, Cần Thơ'),
('ND052', 'Hoàng Thị Thư', '1994-06-30', 'Nữ', '345 Lê Duẩn, Hà Nội'),
('ND053', 'Vũ Văn Phúc', '1993-07-05', 'Nam', '456 Hoàng Diệu, TP.HCM'),
('ND054', 'Đặng Thị Mãi', '1995-08-10', 'Nữ', '567 Trần Phú, Đà Nẵng'),
('ND055', 'Bùi Văn Tuấn', '1992-09-15', 'Nam', '678 Lê Lợi, Hải Phòng'),
('ND056', 'Nguyễn Thị Yến', '1994-10-20', 'Nữ', '789 Nguyễn Trãi, Cần Thơ'),
('ND057', 'Trần Văn Lượng', '1993-11-25', 'Nam', '890 Trần Hưng Đạo, Hà Nội'),
('ND058', 'Lê Thị Hòa', '1995-12-30', 'Nữ', '901 Lê Duẩn, TP.HCM'),
('ND059', 'Phạm Văn Nẫm', '1992-01-05', 'Nam', '123 Hoàng Diệu, Đà Nẵng'),
('ND060', 'Hoàng Thị Ngọc', '1994-02-10', 'Nữ', '234 Trần Phú, Hải Phòng'),
('ND061', 'Vũ Văn Khôi', '1993-03-15', 'Nam', '345 Lê Lợi, Cần Thơ'),
('ND062', 'Đặng Thị Làn', '1995-04-20', 'Nữ', '456 Nguyễn Huệ, Hà Nội'),
('ND063', 'Bùi Văn Minh', '1992-05-25', 'Nam', '567 Trần Hưng Đạo, TP.HCM'),
('ND064', 'Nguyễn Thị Hồng', '1994-06-30', 'Nữ', '678 Lê Duẩn, Đà Nẵng'),
('ND065', 'Trần Văn Phước', '1993-07-05', 'Nam', '789 Hoàng Diệu, Hải Phòng'),
('ND066', 'Lê Thị Mẫn', '1995-08-10', 'Nữ', '890 Trần Phú, Cần Thơ'),
('ND067', 'Phạm Văn Tuấn', '1992-09-15', 'Nam', '901 Lê Lợi, Hà Nội'),
('ND068', 'Hoàng Thị Yên', '1994-10-20', 'Nữ', '123 Nguyễn Trãi, TP.HCM'),
('ND069', 'Vũ Văn Lòng', '1993-11-25', 'Nam', '234 Trần Hưng Đạo, Đà Nẵng'),
('ND070', 'Đặng Thị Hòa', '1995-12-30', 'Nữ', '345 Lê Duẩn, Hải Phòng'),
('ND071', 'Bùi Văn Nạm', '1992-01-05', 'Nam', '456 Hoàng Diệu, Cần Thơ'),
('ND072', 'Nguyễn Thị Ngốc', '1994-02-10', 'Nữ', '567 Trần Phú, Hà Nội'),
('ND073', 'Trần Văn Khôi', '1993-03-15', 'Nam', '678 Lê Lợi, TP.HCM'),
('ND074', 'Lê Thị Làn', '1995-04-20', 'Nữ', '789 Nguyễn Huệ, Đà Nẵng'),
('ND075', 'Phạm Văn Minh', '1992-05-25', 'Nam', '890 Trần Hưng Đạo, Hải Phòng'),
('ND076', 'Hoàng Thị Thư', '1994-06-30', 'Nữ', '901 Lê Duẩn, Cần Thơ'),
('ND077', 'Vũ Văn Phúc', '1993-07-05', 'Nam', '123 Hoàng Diệu, Hà Nội'),
('ND078', 'Đặng Thị Mãi', '1995-08-10', 'Nữ', '234 Trần Phú, TP.HCM'),
('ND079', 'Bùi Văn Tuấn', '1992-09-15', 'Nam', '345 Lê Lợi, Đà Nẵng'),
('ND080', 'Nguyễn Thị Yến', '1994-10-20', 'Nữ', '456 Nguyễn Trãi, Hải Phòng'),
('ND081', 'Trần Văn Lượng', '1993-11-25', 'Nam', '567 Trần Hưng Đạo, Cần Thơ');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `nhanviencskh`
--

CREATE TABLE `nhanviencskh` (
  `MaNV` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `nhanviencskh`
--

INSERT INTO `nhanviencskh` (`MaNV`) VALUES
('ND022'),
('ND023'),
('ND024'),
('ND025'),
('ND026'),
('ND027'),
('ND028'),
('ND029'),
('ND030'),
('ND031');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `phieutiem`
--

CREATE TABLE `phieutiem` (
  `MaPhieuTiem` varchar(50) NOT NULL,
  `MaKH` varchar(50) NOT NULL,
  `NgayTiem` date DEFAULT NULL,
  `GioTiem` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `phieutiem`
--

INSERT INTO `phieutiem` (`MaPhieuTiem`, `MaKH`, `NgayTiem`, `GioTiem`) VALUES
('PT001', 'KH001', '2025-01-01', '08:00:00'),
('PT002', 'KH002', '2025-01-02', '09:00:00'),
('PT003', 'KH003', '2025-01-03', '10:00:00'),
('PT004', 'KH004', '2025-01-04', '11:00:00'),
('PT005', 'KH005', '2025-01-05', '12:00:00'),
('PT006', 'KH006', '2025-01-06', '13:00:00'),
('PT007', 'KH007', '2025-01-01', '14:00:00'),
('PT008', 'KH008', '2025-01-02', '15:00:00'),
('PT009', 'KH009', '2025-01-03', '16:00:00'),
('PT010', 'KH010', '2025-01-04', '17:00:00'),
('PT011', 'KH011', '2025-01-05', '08:00:00'),
('PT012', 'KH012', '2025-01-06', '09:00:00'),
('PT013', 'KH013', '2025-01-01', '10:00:00'),
('PT014', 'KH014', '2025-01-02', '11:00:00'),
('PT015', 'KH015', '2025-01-03', '12:00:00'),
('PT016', 'KH016', '2025-01-04', '13:00:00'),
('PT017', 'KH017', '2025-01-05', '14:00:00'),
('PT018', 'KH018', '2025-01-06', '15:00:00'),
('PT019', 'KH019', '2025-01-01', '16:00:00'),
('PT020', 'KH020', '2025-01-02', '17:00:00'),
('PT021', 'KH021', '2025-01-03', '08:00:00'),
('PT022', 'KH022', '2025-01-04', '09:00:00'),
('PT023', 'KH023', '2025-01-05', '10:00:00'),
('PT024', 'KH024', '2025-01-06', '11:00:00'),
('PT025', 'KH025', '2025-01-01', '12:00:00'),
('PT026', 'KH026', '2025-01-02', '13:00:00'),
('PT027', 'KH027', '2025-01-03', '14:00:00'),
('PT028', 'KH028', '2025-01-04', '15:00:00'),
('PT029', 'KH029', '2025-01-05', '16:00:00'),
('PT030', 'KH030', '2025-01-06', '17:00:00'),
('PT031', 'KH031', '2025-02-01', '08:00:00'),
('PT032', 'KH032', '2025-02-02', '09:00:00'),
('PT033', 'KH033', '2025-02-03', '10:00:00'),
('PT034', 'KH034', '2025-02-04', '11:00:00'),
('PT035', 'KH035', '2025-02-05', '12:00:00'),
('PT036', 'KH036', '2025-02-06', '13:00:00'),
('PT037', 'KH037', '2025-02-01', '14:00:00'),
('PT038', 'KH038', '2025-02-02', '15:00:00'),
('PT039', 'KH039', '2025-02-03', '16:00:00'),
('PT040', 'KH040', '2025-02-04', '17:00:00'),
('PT041', 'KH041', '2025-02-05', '08:00:00'),
('PT042', 'KH042', '2025-02-06', '09:00:00'),
('PT043', 'KH043', '2025-02-01', '10:00:00'),
('PT044', 'KH044', '2025-02-02', '11:00:00'),
('PT045', 'KH045', '2025-02-03', '12:00:00'),
('PT046', 'KH046', '2025-02-04', '13:00:00'),
('PT047', 'KH047', '2025-02-05', '14:00:00'),
('PT048', 'KH048', '2025-02-06', '15:00:00'),
('PT049', 'KH049', '2025-02-01', '16:00:00'),
('PT050', 'KH050', '2025-02-02', '17:00:00'),
('PT051', 'KH051', '2025-02-03', '08:00:00'),
('PT052', 'KH052', '2025-02-04', '09:00:00'),
('PT053', 'KH053', '2025-02-05', '10:00:00'),
('PT054', 'KH054', '2025-02-06', '11:00:00'),
('PT055', 'KH055', '2025-02-01', '12:00:00'),
('PT056', 'KH056', '2025-02-02', '13:00:00'),
('PT057', 'KH057', '2025-02-03', '14:00:00'),
('PT058', 'KH058', '2025-02-04', '15:00:00'),
('PT059', 'KH059', '2025-02-05', '16:00:00'),
('PT060', 'KH060', '2025-02-06', '17:00:00'),
('PT061', 'KH061', '2025-03-01', '08:00:00'),
('PT062', 'KH062', '2025-03-02', '09:00:00'),
('PT063', 'KH063', '2025-03-03', '10:00:00'),
('PT064', 'KH064', '2025-03-04', '11:00:00'),
('PT065', 'KH065', '2025-03-05', '12:00:00'),
('PT066', 'KH066', '2025-03-06', '13:00:00'),
('PT067', 'KH067', '2025-03-01', '14:00:00'),
('PT068', 'KH068', '2025-03-02', '15:00:00'),
('PT069', 'KH069', '2025-03-03', '16:00:00'),
('PT070', 'KH070', '2025-03-04', '17:00:00'),
('PT071', 'KH071', '2025-03-05', '08:00:00'),
('PT072', 'KH072', '2025-03-06', '09:00:00'),
('PT073', 'KH073', '2025-03-01', '10:00:00'),
('PT074', 'KH074', '2025-03-02', '11:00:00'),
('PT075', 'KH075', '2025-03-03', '12:00:00'),
('PT076', 'KH076', '2025-03-04', '13:00:00'),
('PT077', 'KH077', '2025-03-05', '14:00:00'),
('PT078', 'KH078', '2025-03-06', '15:00:00'),
('PT079', 'KH079', '2025-03-01', '16:00:00'),
('PT080', 'KH080', '2025-03-02', '17:00:00'),
('PT081', 'KH081', '2025-03-03', '08:00:00'),
('PT082', 'KH082', '2025-03-04', '09:00:00'),
('PT083', 'KH083', '2025-03-05', '10:00:00'),
('PT084', 'KH084', '2025-03-06', '11:00:00'),
('PT085', 'KH085', '2025-03-01', '12:00:00'),
('PT086', 'KH001', '2025-03-02', '13:00:00'),
('PT087', 'KH002', '2025-03-03', '14:00:00'),
('PT088', 'KH003', '2025-03-04', '15:00:00'),
('PT089', 'KH004', '2025-03-05', '16:00:00'),
('PT090', 'KH005', '2025-03-06', '17:00:00'),
('PT091', 'KH001', '2025-01-02', '09:00:00'),
('PT092', 'KH002', '2025-01-03', '10:00:00'),
('PT093', 'KH003', '2025-01-04', '11:00:00'),
('PT094', 'KH004', '2025-01-05', '12:00:00'),
('PT095', 'KH005', '2025-01-06', '13:00:00'),
('PT096', 'KH006', '2025-02-02', '09:00:00'),
('PT097', 'KH007', '2025-02-03', '10:00:00'),
('PT098', 'KH008', '2025-02-04', '11:00:00'),
('PT099', 'KH009', '2025-02-05', '12:00:00'),
('PT100', 'KH010', '2025-02-06', '13:00:00'),
('PT101', 'KH011', '2025-03-02', '09:00:00'),
('PT102', 'KH012', '2025-03-03', '10:00:00'),
('PT103', 'KH013', '2025-03-04', '11:00:00'),
('PT104', 'KH014', '2025-03-05', '12:00:00'),
('PT105', 'KH015', '2025-03-06', '13:00:00');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `phieutuvan`
--

CREATE TABLE `phieutuvan` (
  `MaTuVan` varchar(50) NOT NULL,
  `MaTV` varchar(50) DEFAULT NULL,
  `MaBS` varchar(50) DEFAULT NULL,
  `NgayTao` date DEFAULT NULL,
  `NoiDungYeuCau` text DEFAULT NULL,
  `NgayPhanHoi` date DEFAULT NULL,
  `NoiDungPhanHoi` text DEFAULT NULL,
  `TrangThaiPhanHoi` enum('Chưa trả lời','Đã trả lời') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `phieutuvan`
--

INSERT INTO `phieutuvan` (`MaTuVan`, `MaTV`, `MaBS`, `NgayTao`, `NoiDungYeuCau`, `NgayPhanHoi`, `NoiDungPhanHoi`, `TrangThaiPhanHoi`) VALUES
('TV001', 'ND032', 'ND001', '2025-01-10', 'Tư vấn về vaccine Hepatitis B', '2025-01-11', 'Đã giải thích về liều lượng và lịch tiêm', 'Đã trả lời'),
('TV002', 'ND033', 'ND002', '2025-01-15', 'Hỏi về tác dụng phụ của vaccine Influenza', '2025-01-16', 'Các tác dụng phụ thường nhẹ, như đau tại chỗ tiêm', 'Đã trả lời'),
('TV003', 'ND034', 'ND003', '2025-02-01', 'Tư vấn vaccine MMR cho trẻ em', '2025-02-02', 'Khuyến nghị tiêm 2 liều cho trẻ', 'Đã trả lời'),
('TV004', 'ND035', 'ND004', '2025-02-05', 'Hỏi về vaccine DTP', '2025-02-06', 'Cung cấp thông tin về lịch tiêm DTP', 'Đã trả lời'),
('TV005', 'ND036', 'ND005', '2025-02-10', 'Tư vấn vaccine HPV cho nữ giới', '2025-02-11', 'Khuyến nghị tiêm trước 26 tuổi', 'Đã trả lời'),
('TV006', 'ND037', 'ND006', '2025-03-01', 'Hỏi về vaccine Pfizer-BioNTech', '2025-03-02', 'Giải thích về hiệu quả và an toàn', 'Đã trả lời'),
('TV007', 'ND038', 'ND007', '2025-03-05', 'Tư vấn vaccine AstraZeneca', '2025-03-06', 'Cung cấp thông tin về liều lượng', 'Đã trả lời'),
('TV008', 'ND039', 'ND008', '2025-03-10', 'Hỏi về vaccine Tetanus', '2025-03-11', 'Khuyến nghị tiêm nhắc lại 10 năm/lần', 'Đã trả lời'),
('TV009', 'ND040', 'ND009', '2025-03-15', 'Tư vấn về vaccine Polio', NULL, NULL, 'Chưa trả lời'),
('TV010', 'ND041', 'ND010', '2025-03-20', 'Hỏi về vaccine Hepatitis A', NULL, NULL, 'Chưa trả lời');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `quanhelienket`
--

CREATE TABLE `quanhelienket` (
  `MaQH` varchar(50) NOT NULL,
  `MaTV` varchar(50) DEFAULT NULL,
  `MaKH` varchar(50) DEFAULT NULL,
  `NgayLienKet` date DEFAULT NULL,
  `TrangThaiLienKet` enum('Chưa xác thực','Đã xác thực') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `quanhelienket`
--

INSERT INTO `quanhelienket` (`MaQH`, `MaTV`, `MaKH`, `NgayLienKet`, `TrangThaiLienKet`) VALUES
('QH001', 'ND032', 'KH036', '2025-01-10', 'Đã xác thực'),
('QH002', 'ND033', 'KH037', '2025-01-15', 'Đã xác thực'),
('QH003', 'ND034', 'KH038', '2025-02-01', 'Đã xác thực'),
('QH004', 'ND035', 'KH039', '2025-02-05', 'Đã xác thực'),
('QH005', 'ND036', 'KH040', '2025-02-10', 'Đã xác thực'),
('QH006', 'ND037', 'KH041', '2025-03-01', 'Đã xác thực');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `quantrivien`
--

CREATE TABLE `quantrivien` (
  `MaQTV` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `quantrivien`
--

INSERT INTO `quantrivien` (`MaQTV`) VALUES
('ND019'),
('ND020'),
('ND021');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `taikhoan`
--

CREATE TABLE `taikhoan` (
  `MaTK` varchar(50) NOT NULL,
  `MaND` varchar(50) NOT NULL,
  `SDT` varchar(20) NOT NULL,
  `MatKhau` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `taikhoan`
--

INSERT INTO `taikhoan` (`MaTK`, `MaND`, `SDT`, `MatKhau`) VALUES
('TK001', 'ND001', '0901000001', 'password123'),
('TK002', 'ND002', '0901000002', 'password123'),
('TK003', 'ND003', '0901000003', 'password123'),
('TK004', 'ND004', '0901000004', 'password123'),
('TK005', 'ND005', '0901000005', 'password123'),
('TK006', 'ND006', '0901000006', 'password123'),
('TK007', 'ND007', '0901000007', 'password123'),
('TK008', 'ND008', '0901000008', 'password123'),
('TK009', 'ND009', '0901000009', 'password123'),
('TK010', 'ND010', '0901000010', 'password123'),
('TK011', 'ND011', '0901000011', 'password123'),
('TK012', 'ND012', '0901000012', 'password123'),
('TK013', 'ND013', '0901000013', 'password123'),
('TK014', 'ND014', '0901000014', 'password123'),
('TK015', 'ND015', '0901000015', 'password123'),
('TK016', 'ND016', '0901000016', 'password123'),
('TK017', 'ND017', '0901000017', 'password123'),
('TK018', 'ND018', '0901000018', 'password123'),
('TK019', 'ND019', '0901000019', 'password123'),
('TK020', 'ND020', '0901000020', 'password123'),
('TK021', 'ND021', '0901000021', 'password123'),
('TK022', 'ND022', '0901000022', 'password123'),
('TK023', 'ND023', '0901000023', 'password123'),
('TK024', 'ND024', '0901000024', 'password123'),
('TK025', 'ND025', '0901000025', 'password123'),
('TK026', 'ND026', '0901000026', 'password123'),
('TK027', 'ND027', '0901000027', 'password123'),
('TK028', 'ND028', '0901000028', 'password123'),
('TK029', 'ND029', '0901000029', 'password123'),
('TK030', 'ND030', '0901000030', 'password123'),
('TK031', 'ND031', '0901000031', 'password123'),
('TK032', 'ND032', '0901000032', 'password123'),
('TK033', 'ND033', '0901000033', 'password123'),
('TK034', 'ND034', '0901000034', 'password123'),
('TK035', 'ND035', '0901000035', 'password123'),
('TK036', 'ND036', '0901000036', 'password123'),
('TK037', 'ND037', '0901000037', 'password123'),
('TK038', 'ND038', '0901000038', 'password123'),
('TK039', 'ND039', '0901000039', 'password123'),
('TK040', 'ND040', '0901000040', 'password123'),
('TK041', 'ND041', '0901000041', 'password123'),
('TK042', 'ND042', '0901000042', 'password123'),
('TK043', 'ND043', '0901000043', 'password123'),
('TK044', 'ND044', '0901000044', 'password123'),
('TK045', 'ND045', '0901000045', 'password123'),
('TK046', 'ND046', '0901000046', 'password123'),
('TK047', 'ND047', '0901000047', 'password123'),
('TK048', 'ND048', '0901000048', 'password123'),
('TK049', 'ND049', '0901000049', 'password123'),
('TK050', 'ND050', '0901000050', 'password123'),
('TK051', 'ND051', '0901000051', 'password123'),
('TK052', 'ND052', '0901000052', 'password123'),
('TK053', 'ND053', '0901000053', 'password123'),
('TK054', 'ND054', '0901000054', 'password123'),
('TK055', 'ND055', '0901000055', 'password123'),
('TK056', 'ND056', '0901000056', 'password123'),
('TK057', 'ND057', '0901000057', 'password123'),
('TK058', 'ND058', '0901000058', 'password123'),
('TK059', 'ND059', '0901000059', 'password123'),
('TK060', 'ND060', '0901000060', 'password123'),
('TK061', 'ND061', '0901000061', 'password123'),
('TK062', 'ND062', '0901000062', 'password123'),
('TK063', 'ND063', '0901000063', 'password123'),
('TK064', 'ND064', '0901000064', 'password123'),
('TK065', 'ND065', '0901000065', 'password123'),
('TK066', 'ND066', '0901000066', 'password123'),
('TK067', 'ND067', '0901000067', 'password123'),
('TK068', 'ND068', '0901000068', 'password123'),
('TK069', 'ND069', '0901000069', 'password123'),
('TK070', 'ND070', '0901000070', 'password123'),
('TK071', 'ND071', '0901000071', 'password123'),
('TK072', 'ND072', '0901000072', 'password123'),
('TK073', 'ND073', '0901000073', 'password123'),
('TK074', 'ND074', '0901000074', 'password123'),
('TK075', 'ND075', '0901000075', 'password123'),
('TK076', 'ND076', '0901000076', 'password123'),
('TK077', 'ND077', '0901000077', 'password123'),
('TK078', 'ND078', '0901000078', 'password123'),
('TK079', 'ND079', '0901000079', 'password123'),
('TK080', 'ND080', '0901000080', 'password123'),
('TK081', 'ND081', '0901000081', 'password123');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `thanhvien`
--

CREATE TABLE `thanhvien` (
  `MaTV` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `thanhvien`
--

INSERT INTO `thanhvien` (`MaTV`) VALUES
('ND032'),
('ND033'),
('ND034'),
('ND035'),
('ND036'),
('ND037'),
('ND038'),
('ND039'),
('ND040'),
('ND041'),
('ND042'),
('ND043'),
('ND044'),
('ND045'),
('ND046'),
('ND047'),
('ND048'),
('ND049'),
('ND050'),
('ND051'),
('ND052'),
('ND053'),
('ND054'),
('ND055'),
('ND056'),
('ND057'),
('ND058'),
('ND059'),
('ND060'),
('ND061'),
('ND062'),
('ND063'),
('ND064'),
('ND065'),
('ND066'),
('ND067'),
('ND068'),
('ND069'),
('ND070'),
('ND071'),
('ND072'),
('ND073'),
('ND074'),
('ND075'),
('ND076'),
('ND077'),
('ND078'),
('ND079'),
('ND080'),
('ND081');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `thongtinkhachhang`
--

CREATE TABLE `thongtinkhachhang` (
  `MaKH` varchar(50) NOT NULL,
  `HoTen` varchar(100) NOT NULL,
  `NgaySinh` date DEFAULT NULL,
  `GioiTinh` enum('Nam','Nữ') DEFAULT NULL,
  `DiaChi` varchar(255) DEFAULT NULL,
  `SDT` varchar(20) NOT NULL,
  `BenhNen` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `thongtinkhachhang`
--

INSERT INTO `thongtinkhachhang` (`MaKH`, `HoTen`, `NgaySinh`, `GioiTinh`, `DiaChi`, `SDT`, `BenhNen`) VALUES
('KH001', 'Nguyễn Thị Kiều', '1995-01-10', 'Nữ', '123 Trần Hưng Đạo, Hà Nội', '0901000032', NULL),
('KH002', 'Trần Văn Hải', '1994-03-15', 'Nam', '234 Nguyễn Huệ, TP.HCM', '0901000033', 'Tiểu đường'),
('KH003', 'Lê Thị Thùy', '1996-07-20', 'Nữ', '345 Lê Duẩn, Đà Nẵng', '0901000034', NULL),
('KH004', 'Phạm Văn Nẫm', '1993-11-25', 'Nam', '456 Hoàng Diệu, Hải Phòng', '0901000035', NULL),
('KH005', 'Hoàng Thị Ánh', '1995-12-30', 'Nữ', '567 Trần Phú, Cần Thơ', '0901000036', NULL),
('KH006', 'Vũ Văn Khoa', '1990-02-10', 'Nam', '678 Lê Lợi, Hà Nội', '0901000037', 'Cao huyết áp'),
('KH007', 'Đặng Thị Làn', '1988-04-15', 'Nữ', '789 Trần Hưng Đạo, TP.HCM', '0901000038', NULL),
('KH008', 'Bùi Văn Minh', '1992-12-30', 'Nam', '890 Nguyễn Huệ, Đà Nẵng', '0901000039', NULL),
('KH009', 'Nguyễn Thị Hồng', '1994-06-05', 'Nữ', '901 Lê Duẩn, Hải Phòng', '0901000040', NULL),
('KH010', 'Trần Văn Phước', '1993-08-20', 'Nam', '123 Hoàng Diệu, Cần Thơ', '0901000041', NULL),
('KH011', 'Lê Thị Mẫn', '1995-03-10', 'Nữ', '234 Trần Phú, Hà Nội', '0901000042', NULL),
('KH012', 'Phạm Văn Tuấn', '1992-09-15', 'Nam', '345 Lê Lợi, TP.HCM', '0901000043', NULL),
('KH013', 'Hoàng Thị Yên', '1994-11-20', 'Nữ', '456 Nguyễn Trãi, Đà Nẵng', '0901000044', NULL),
('KH014', 'Vũ Văn Lòng', '1993-05-25', 'Nam', '567 Trần Hưng Đạo, Hải Phòng', '0901000045', NULL),
('KH015', 'Đặng Thị Hòa', '1995-07-30', 'Nữ', '678 Lê Duẩn, Cần Thơ', '0901000046', NULL),
('KH016', 'Bùi Văn Nạm', '1992-01-05', 'Nam', '789 Hoàng Diệu, Hà Nội', '0901000047', NULL),
('KH017', 'Nguyễn Thị Ngốc', '1994-02-10', 'Nữ', '890 Trần Phú, TP.HCM', '0901000048', NULL),
('KH018', 'Trần Văn Khôi', '1993-03-15', 'Nam', '901 Lê Lợi, Đà Nẵng', '0901000049', NULL),
('KH019', 'Lê Thị Làn', '1995-04-20', 'Nữ', '123 Nguyễn Huệ, Hải Phòng', '0901000050', NULL),
('KH020', 'Phạm Văn Minh', '1992-05-25', 'Nam', '234 Trần Hưng Đạo, Cần Thơ', '0901000051', NULL),
('KH021', 'Hoàng Thị Thư', '1994-06-30', 'Nữ', '345 Lê Duẩn, Hà Nội', '0901000052', NULL),
('KH022', 'Vũ Văn Phúc', '1993-07-05', 'Nam', '456 Hoàng Diệu, TP.HCM', '0901000053', NULL),
('KH023', 'Đặng Thị Mãi', '1995-08-10', 'Nữ', '567 Trần Phú, Đà Nẵng', '0901000054', NULL),
('KH024', 'Bùi Văn Tuấn', '1992-09-15', 'Nam', '678 Lê Lợi, Hải Phòng', '0901000055', NULL),
('KH025', 'Nguyễn Thị Yến', '1994-10-20', 'Nữ', '789 Nguyễn Trãi, Cần Thơ', '0901000056', NULL),
('KH026', 'Trần Văn Lượng', '1993-11-25', 'Nam', '890 Trần Hưng Đạo, Hà Nội', '0901000057', NULL),
('KH027', 'Lê Thị Hòa', '1995-12-30', 'Nữ', '901 Lê Duẩn, TP.HCM', '0901000058', NULL),
('KH028', 'Phạm Văn Nẫm', '1992-01-05', 'Nam', '123 Hoàng Diệu, Đà Nẵng', '0901000059', NULL),
('KH029', 'Hoàng Thị Ngọc', '1994-02-10', 'Nữ', '234 Trần Phú, Hải Phòng', '0901000060', NULL),
('KH030', 'Vũ Văn Khôi', '1993-03-15', 'Nam', '345 Lê Lợi, Cần Thơ', '0901000061', NULL),
('KH031', 'Đặng Thị Làn', '1995-04-20', 'Nữ', '456 Nguyễn Huệ, Hà Nội', '0901000062', NULL),
('KH032', 'Bùi Văn Minh', '1992-05-25', 'Nam', '567 Trần Hưng Đạo, TP.HCM', '0901000063', NULL),
('KH033', 'Nguyễn Thị Hồng', '1994-06-30', 'Nữ', '678 Lê Duẩn, Đà Nẵng', '0901000064', NULL),
('KH034', 'Trần Văn Phước', '1993-07-05', 'Nam', '789 Hoàng Diệu, Hải Phòng', '0901000065', NULL),
('KH035', 'Lê Thị Mẫn', '1995-08-10', 'Nữ', '890 Trần Phú, Cần Thơ', '0901000066', NULL),
('KH036', 'Phạm Văn Khang', '1990-02-10', 'Nam', '901 Lê Lợi, Hà Nội', '0901000082', NULL),
('KH037', 'Hoàng Thị Lành', '1988-04-15', 'Nữ', '123 Trần Phú, TP.HCM', '0901000083', 'Cao huyết áp'),
('KH038', 'Vũ Văn Mạnh', '1992-12-30', 'Nam', '234 Nguyễn Huệ, Đà Nẵng', '0901000084', NULL),
('KH039', 'Đặng Thị Hồng', '1994-06-05', 'Nữ', '345 Lê Duẩn, Hải Phòng', '0901000085', NULL),
('KH040', 'Bùi Văn Phấn', '1993-08-20', 'Nam', '456 Hoàng Diệu, Cần Thơ', '0901000086', NULL),
('KH041', 'Nguyễn Thị Mẫn', '1995-03-10', 'Nữ', '567 Trần Phú, Hà Nội', '0901000087', NULL),
('KH042', 'Trần Văn Tuấn', '1992-09-15', 'Nam', '678 Lê Lợi, TP.HCM', '0901000088', NULL),
('KH043', 'Lê Thị Yến', '1994-11-20', 'Nữ', '789 Nguyễn Trãi, Đà Nẵng', '0901000089', NULL),
('KH044', 'Phạm Văn Lượng', '1993-05-25', 'Nam', '890 Trần Hưng Đạo, Hải Phòng', '0901000090', NULL),
('KH045', 'Hoàng Thị Hòa', '1995-07-30', 'Nữ', '901 Lê Duẩn, Cần Thơ', '0901000091', NULL),
('KH046', 'Vũ Văn Nẫm', '1992-01-05', 'Nam', '123 Hoàng Diệu, Hà Nội', '0901000092', NULL),
('KH047', 'Đặng Thị Ngốc', '1994-02-10', 'Nữ', '234 Trần Phú, TP.HCM', '0901000093', NULL),
('KH048', 'Bùi Văn Khôi', '1993-03-15', 'Nam', '345 Lê Lợi, Đà Nẵng', '0901000094', NULL),
('KH049', 'Nguyễn Thị Làn', '1995-04-20', 'Nữ', '456 Nguyễn Huệ, Hải Phòng', '0901000095', NULL),
('KH050', 'Trần Văn Minh', '1992-05-25', 'Nam', '567 Trần Hưng Đạo, Cần Thơ', '0901000096', NULL),
('KH051', 'Lê Thị Hồng', '1994-06-30', 'Nữ', '678 Lê Duẩn, Hà Nội', '0901000097', NULL),
('KH052', 'Phạm Văn Phúc', '1993-07-05', 'Nam', '789 Hoàng Diệu, TP.HCM', '0901000098', NULL),
('KH053', 'Hoàng Thị Mẫn', '1995-08-10', 'Nữ', '890 Trần Phú, Đà Nẵng', '0901000099', NULL),
('KH054', 'Vũ Văn Tuấn', '1992-09-15', 'Nam', '901 Lê Lợi, Hải Phòng', '0901000100', NULL),
('KH055', 'Đặng Thị Yến', '1994-10-20', 'Nữ', '123 Nguyễn Trãi, Cần Thơ', '0901000101', NULL),
('KH056', 'Bùi Văn Lượng', '1993-11-25', 'Nam', '234 Trần Hưng Đạo, Hà Nội', '0901000102', NULL),
('KH057', 'Nguyễn Thị Hòa', '1995-12-30', 'Nữ', '345 Lê Duẩn, TP.HCM', '0901000103', NULL),
('KH058', 'Trần Văn Nẫm', '1992-01-05', 'Nam', '456 Hoàng Diệu, Đà Nẵng', '0901000104', NULL),
('KH059', 'Lê Thị Ngốc', '1994-02-10', 'Nữ', '567 Trần Phú, Hải Phòng', '0901000105', NULL),
('KH060', 'Phạm Văn Khôi', '1993-03-15', 'Nam', '678 Lê Lợi, Cần Thơ', '0901000106', NULL),
('KH061', 'Hoàng Thị Làn', '1995-04-20', 'Nữ', '789 Nguyễn Huệ, Hà Nội', '0901000107', NULL),
('KH062', 'Vũ Văn Minh', '1992-05-25', 'Nam', '890 Trần Hưng Đạo, TP.HCM', '0901000108', NULL),
('KH063', 'Đặng Thị Hồng', '1994-06-30', 'Nữ', '901 Lê Duẩn, Đà Nẵng', '0901000109', NULL),
('KH064', 'Bùi Văn Phúc', '1993-07-05', 'Nam', '123 Hoàng Diệu, Hải Phòng', '0901000110', NULL),
('KH065', 'Nguyễn Thị Mẫn', '1995-08-10', 'Nữ', '234 Trần Phú, Cần Thơ', '0901000111', NULL),
('KH066', 'Trần Văn Tuấn', '1992-09-15', 'Nam', '345 Lê Lợi, Hà Nội', '0901000112', NULL),
('KH067', 'Lê Thị Yến', '1994-10-20', 'Nữ', '456 Nguyễn Trãi, TP.HCM', '0901000113', NULL),
('KH068', 'Phạm Văn Lượng', '1993-11-25', 'Nam', '567 Trần Hưng Đạo, Đà Nẵng', '0901000114', NULL),
('KH069', 'Hoàng Thị Hòa', '1995-12-30', 'Nữ', '678 Lê Duẩn, Hải Phòng', '0901000115', NULL),
('KH070', 'Vũ Văn Nẫm', '1992-01-05', 'Nam', '789 Hoàng Diệu, Cần Thơ', '0901000116', NULL),
('KH071', 'Đặng Thị Ngốc', '1994-02-10', 'Nữ', '890 Trần Phú, Hà Nội', '0901000117', NULL),
('KH072', 'Bùi Văn Khôi', '1993-03-15', 'Nam', '901 Lê Lợi, TP.HCM', '0901000118', NULL),
('KH073', 'Nguyễn Thị Làn', '1995-04-20', 'Nữ', '123 Nguyễn Huệ, Đà Nẵng', '0901000119', NULL),
('KH074', 'Trần Văn Minh', '1992-05-25', 'Nam', '234 Trần Hưng Đạo, Hải Phòng', '0901000120', NULL),
('KH075', 'Lê Thị Hồng', '1994-06-30', 'Nữ', '345 Lê Duẩn, Cần Thơ', '0901000121', NULL),
('KH076', 'Phạm Văn Phúc', '1993-07-05', 'Nam', '456 Hoàng Diệu, Hà Nội', '0901000122', NULL),
('KH077', 'Hoàng Thị Mẫn', '1995-08-10', 'Nữ', '567 Trần Phú, TP.HCM', '0901000123', NULL),
('KH078', 'Vũ Văn Tuấn', '1992-09-15', 'Nam', '678 Lê Lợi, Đà Nẵng', '0901000124', NULL),
('KH079', 'Đặng Thị Yến', '1994-10-20', 'Nữ', '789 Nguyễn Trãi, Hải Phòng', '0901000125', NULL),
('KH080', 'Bùi Văn Lượng', '1993-11-25', 'Nam', '890 Trần Hưng Đạo, Cần Thơ', '0901000126', NULL),
('KH081', 'Nguyễn Thị Hòa', '1995-12-30', 'Nữ', '901 Lê Duẩn, Hà Nội', '0901000127', NULL),
('KH082', 'Trần Văn Nẫm', '1992-01-05', 'Nam', '123 Hoàng Diệu, TP.HCM', '0901000128', NULL),
('KH083', 'Lê Thị Ngốc', '1994-02-10', 'Nữ', '234 Trần Phú, Đà Nẵng', '0901000129', NULL),
('KH084', 'Phạm Văn Khôi', '1993-03-15', 'Nam', '345 Lê Lợi, Hải Phòng', '0901000130', NULL),
('KH085', 'Hoàng Thị Làn', '1995-04-20', 'Nữ', '456 Nguyễn Huệ, Cần Thơ', '0901000131', NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `vacxin`
--

CREATE TABLE `vacxin` (
  `MaVacXin` varchar(50) NOT NULL,
  `TenVacXin` varchar(100) NOT NULL,
  `HSD` date DEFAULT NULL,
  `NSX` date DEFAULT NULL,
  `Gia` decimal(12,0) DEFAULT NULL,
  `Mota` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `vacxin`
--

INSERT INTO `vacxin` (`MaVacXin`, `TenVacXin`, `HSD`, `NSX`, `Gia`, `Mota`) VALUES
('VX001', 'Hepatitis B', '2026-12-31', '2024-01-01', 150000, 'Vaccine for Hepatitis B prevention'),
('VX002', 'Influenza', '2026-06-30', '2024-01-01', 200000, 'Seasonal flu vaccine'),
('VX003', 'MMR', '2026-09-30', '2024-01-01', 250000, 'Measles, Mumps, Rubella vaccine'),
('VX004', 'DTP', '2026-03-31', '2024-01-01', 180000, 'Diphtheria, Tetanus, Pertussis vaccine'),
('VX005', 'HPV', '2026-12-31', '2024-01-01', 500000, 'Human Papillomavirus vaccine'),
('VX006', 'Pfizer-BioNTech', '2026-06-30', '2024-01-01', 600000, 'COVID-19 mRNA vaccine'),
('VX007', 'AstraZeneca', '2026-03-31', '2024-01-01', 400000, 'COVID-19 viral vector vaccine'),
('VX008', 'Tetanus', '2026-12-31', '2024-01-01', 120000, 'Tetanus toxoid vaccine'),
('VX009', 'Polio', '2026-09-30', '2024-01-01', 150000, 'Poliovirus vaccine, inactive'),
('VX010', 'Hepatitis A', '2026-12-31', '2024-01-01', 300000, 'Vaccine for Hepatitis A prevention');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `bacsi`
--
ALTER TABLE `bacsi`
  ADD PRIMARY KEY (`MaBS`);

--
-- Chỉ mục cho bảng `chinhanh`
--
ALTER TABLE `chinhanh`
  ADD PRIMARY KEY (`MaChiNhanh`);

--
-- Chỉ mục cho bảng `chitiettonkho`
--
ALTER TABLE `chitiettonkho`
  ADD PRIMARY KEY (`MaCTTK`),
  ADD UNIQUE KEY `uniq_cttk_branch_vx` (`MaChiNhanh`,`MaVacXin`),
  ADD KEY `MaVacXin` (`MaVacXin`);

--
-- Chỉ mục cho bảng `donhangonl`
--
ALTER TABLE `donhangonl`
  ADD PRIMARY KEY (`MaDHonl`),
  ADD KEY `MaTV` (`MaTV`),
  ADD KEY `MaChiNhanh` (`MaChiNhanh`),
  ADD KEY `MaVacXin` (`MaVacXin`);

--
-- Chỉ mục cho bảng `donhangpos`
--
ALTER TABLE `donhangpos`
  ADD PRIMARY KEY (`MaDHpos`),
  ADD KEY `MaKH` (`MaKH`),
  ADD KEY `MaChiNhanh` (`MaChiNhanh`),
  ADD KEY `MaVacXin` (`MaVacXin`);

--
-- Chỉ mục cho bảng `lichhentiem`
--
ALTER TABLE `lichhentiem`
  ADD PRIMARY KEY (`MaLichHen`),
  ADD KEY `MaDHonl` (`MaDHonl`);

--
-- Chỉ mục cho bảng `nguoidung`
--
ALTER TABLE `nguoidung`
  ADD PRIMARY KEY (`MaND`);

--
-- Chỉ mục cho bảng `nhanviencskh`
--
ALTER TABLE `nhanviencskh`
  ADD PRIMARY KEY (`MaNV`);

--
-- Chỉ mục cho bảng `phieutiem`
--
ALTER TABLE `phieutiem`
  ADD PRIMARY KEY (`MaPhieuTiem`),
  ADD KEY `MaKH` (`MaKH`);

--
-- Chỉ mục cho bảng `phieutuvan`
--
ALTER TABLE `phieutuvan`
  ADD PRIMARY KEY (`MaTuVan`),
  ADD KEY `MaTV` (`MaTV`),
  ADD KEY `MaBS` (`MaBS`);

--
-- Chỉ mục cho bảng `quanhelienket`
--
ALTER TABLE `quanhelienket`
  ADD PRIMARY KEY (`MaQH`),
  ADD KEY `MaTV` (`MaTV`),
  ADD KEY `MaKH` (`MaKH`);

--
-- Chỉ mục cho bảng `quantrivien`
--
ALTER TABLE `quantrivien`
  ADD PRIMARY KEY (`MaQTV`);

--
-- Chỉ mục cho bảng `taikhoan`
--
ALTER TABLE `taikhoan`
  ADD PRIMARY KEY (`MaTK`),
  ADD UNIQUE KEY `uniq_taikhoan_sdt` (`SDT`),
  ADD KEY `MaND` (`MaND`);

--
-- Chỉ mục cho bảng `thanhvien`
--
ALTER TABLE `thanhvien`
  ADD PRIMARY KEY (`MaTV`);

--
-- Chỉ mục cho bảng `thongtinkhachhang`
--
ALTER TABLE `thongtinkhachhang`
  ADD PRIMARY KEY (`MaKH`);

--
-- Chỉ mục cho bảng `vacxin`
--
ALTER TABLE `vacxin`
  ADD PRIMARY KEY (`MaVacXin`);

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `bacsi`
--
ALTER TABLE `bacsi`
  ADD CONSTRAINT `bacsi_ibfk_1` FOREIGN KEY (`MaBS`) REFERENCES `nguoidung` (`MaND`);

--
-- Các ràng buộc cho bảng `chitiettonkho`
--
ALTER TABLE `chitiettonkho`
  ADD CONSTRAINT `chitiettonkho_ibfk_1` FOREIGN KEY (`MaChiNhanh`) REFERENCES `chinhanh` (`MaChiNhanh`),
  ADD CONSTRAINT `chitiettonkho_ibfk_2` FOREIGN KEY (`MaVacXin`) REFERENCES `vacxin` (`MaVacXin`);

--
-- Các ràng buộc cho bảng `donhangonl`
--
ALTER TABLE `donhangonl`
  ADD CONSTRAINT `donhangonl_ibfk_1` FOREIGN KEY (`MaTV`) REFERENCES `thanhvien` (`MaTV`),
  ADD CONSTRAINT `donhangonl_ibfk_2` FOREIGN KEY (`MaChiNhanh`) REFERENCES `chinhanh` (`MaChiNhanh`),
  ADD CONSTRAINT `donhangonl_ibfk_3` FOREIGN KEY (`MaVacXin`) REFERENCES `vacxin` (`MaVacXin`);

--
-- Các ràng buộc cho bảng `donhangpos`
--
ALTER TABLE `donhangpos`
  ADD CONSTRAINT `donhangpos_ibfk_1` FOREIGN KEY (`MaKH`) REFERENCES `thongtinkhachhang` (`MaKH`),
  ADD CONSTRAINT `donhangpos_ibfk_2` FOREIGN KEY (`MaChiNhanh`) REFERENCES `chinhanh` (`MaChiNhanh`),
  ADD CONSTRAINT `donhangpos_ibfk_3` FOREIGN KEY (`MaVacXin`) REFERENCES `vacxin` (`MaVacXin`);

--
-- Các ràng buộc cho bảng `lichhentiem`
--
ALTER TABLE `lichhentiem`
  ADD CONSTRAINT `lichhentiem_ibfk_1` FOREIGN KEY (`MaDHonl`) REFERENCES `donhangonl` (`MaDHonl`);

--
-- Các ràng buộc cho bảng `nhanviencskh`
--
ALTER TABLE `nhanviencskh`
  ADD CONSTRAINT `nhanviencskh_ibfk_1` FOREIGN KEY (`MaNV`) REFERENCES `nguoidung` (`MaND`);

--
-- Các ràng buộc cho bảng `phieutiem`
--
ALTER TABLE `phieutiem`
  ADD CONSTRAINT `phieutiem_ibfk_1` FOREIGN KEY (`MaKH`) REFERENCES `thongtinkhachhang` (`MaKH`);

--
-- Các ràng buộc cho bảng `phieutuvan`
--
ALTER TABLE `phieutuvan`
  ADD CONSTRAINT `phieutuvan_ibfk_1` FOREIGN KEY (`MaTV`) REFERENCES `thanhvien` (`MaTV`),
  ADD CONSTRAINT `phieutuvan_ibfk_2` FOREIGN KEY (`MaBS`) REFERENCES `bacsi` (`MaBS`);

--
-- Các ràng buộc cho bảng `quanhelienket`
--
ALTER TABLE `quanhelienket`
  ADD CONSTRAINT `quanhelienket_ibfk_1` FOREIGN KEY (`MaTV`) REFERENCES `thanhvien` (`MaTV`),
  ADD CONSTRAINT `quanhelienket_ibfk_2` FOREIGN KEY (`MaKH`) REFERENCES `thongtinkhachhang` (`MaKH`);

--
-- Các ràng buộc cho bảng `quantrivien`
--
ALTER TABLE `quantrivien`
  ADD CONSTRAINT `quantrivien_ibfk_1` FOREIGN KEY (`MaQTV`) REFERENCES `nguoidung` (`MaND`);

--
-- Các ràng buộc cho bảng `taikhoan`
--
ALTER TABLE `taikhoan`
  ADD CONSTRAINT `taikhoan_ibfk_1` FOREIGN KEY (`MaND`) REFERENCES `nguoidung` (`MaND`);

--
-- Các ràng buộc cho bảng `thanhvien`
--
ALTER TABLE `thanhvien`
  ADD CONSTRAINT `thanhvien_ibfk_1` FOREIGN KEY (`MaTV`) REFERENCES `nguoidung` (`MaND`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
