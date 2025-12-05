-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th12 05, 2025 lúc 06:13 AM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `duan`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `bac_sis`
--

CREATE TABLE `bac_sis` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ho_ten` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `chuyen_khoa` varchar(255) NOT NULL,
  `kinh_nghiem` int(11) NOT NULL DEFAULT 0 COMMENT 'Số năm kinh nghiệm',
  `mo_ta` text DEFAULT NULL,
  `trang_thai` enum('Đang hoạt động','Ngừng hoạt động') NOT NULL DEFAULT 'Đang hoạt động',
  `so_dien_thoai` varchar(255) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `dia_chi` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `bac_sis`
--

INSERT INTO `bac_sis` (`id`, `user_id`, `ho_ten`, `email`, `chuyen_khoa`, `kinh_nghiem`, `mo_ta`, `trang_thai`, `so_dien_thoai`, `avatar`, `dia_chi`, `created_at`, `updated_at`) VALUES
(2, NULL, 'Bùi Thanh Phước', 'Buiphuoc@gmail.com', 'Sản', 0, NULL, 'Đang hoạt động', '0123456789', 'avatars/oq6ESFOlzpOgPh3CySyyEbenBOrvmTBG3GujRFuE.jpg', NULL, '2025-10-27 08:33:39', '2025-11-30 04:06:13'),
(4, NULL, 'Nguyễn Chí Thanh', 'Chithanh@gmail.com', 'Phụ Khoa', 0, NULL, 'Đang hoạt động', '0398219340', 'avatars/TPxydOFdRODVNgKWa7GLBzt0xlVtYZRCIE8GWhFn.jpg', NULL, '2025-10-27 08:33:53', '2025-11-30 04:06:37'),
(10, 30, 'Võ Thị Diễm Hằng', 'vo-thi-diem-hang.10@gmail.com', 'Phụ Khoa', 4, NULL, 'Đang hoạt động', '0968177630', 'avatars/muu5syjIJSvXUaUALyjdmoto4LUlxP9ey2ught0l.jpg', NULL, '2025-11-04 02:37:58', '2025-11-30 04:04:48'),
(11, 34, 'Bác sĩ 2', 'bac-si-2.11@gmail.com', 'Sản Khoa', 5, NULL, 'Đang hoạt động', '0987654321', 'avatars/B5EDFU6EMMBZkQuoCEIenV5IeCxXc4jSeptkzd7D.jpg', NULL, '2025-11-04 03:19:00', '2025-11-30 04:04:27');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `bac_si_chuyen_khoa`
--

CREATE TABLE `bac_si_chuyen_khoa` (
  `bac_si_id` bigint(20) UNSIGNED NOT NULL,
  `chuyen_khoa_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `bac_si_chuyen_khoa`
--

INSERT INTO `bac_si_chuyen_khoa` (`bac_si_id`, `chuyen_khoa_id`) VALUES
(2, 1),
(4, 3),
(11, 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `bac_si_phong`
--

CREATE TABLE `bac_si_phong` (
  `bac_si_id` bigint(20) UNSIGNED NOT NULL,
  `phong_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `bac_si_phong`
--

INSERT INTO `bac_si_phong` (`bac_si_id`, `phong_id`) VALUES
(2, 2),
(2, 4),
(10, 3),
(11, 1),
(11, 4);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `bai_viets`
--

CREATE TABLE `bai_viets` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `danh_muc_id` bigint(20) UNSIGNED DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `excerpt` varchar(255) DEFAULT NULL,
  `content` longtext NOT NULL,
  `status` enum('draft','published') NOT NULL DEFAULT 'draft',
  `published_at` timestamp NULL DEFAULT NULL,
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_description` text DEFAULT NULL,
  `thumbnail` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `bai_viets`
--

INSERT INTO `bai_viets` (`id`, `user_id`, `danh_muc_id`, `title`, `slug`, `excerpt`, `content`, `status`, `published_at`, `meta_title`, `meta_description`, `thumbnail`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 21, 1, '5 dấu hiệu nhận biết mang thai sớm chính xác nhất', '5-dau-hieu-nhan-biet-mang-thai-som-chinh-xac-nhat', 'Làm sao để biết mình đã có thai? Dưới đây là 5 dấu hiệu cơ thể báo hiệu sớm nhất bạn cần biết.', 'Khoa sản là gì?\r\nKhoa sản, còn được biết đến với tên gọi là sản khoa, là một lĩnh vực chuyên môn trong ngành y học, chuyên về việc chăm sóc và quản lý sức khỏe của phụ nữ trước, trong và sau quá trình mang thai, sinh nở. Lĩnh vực này không chỉ tập trung vào việc đảm bảo sự an toàn và sức khỏe cho mẹ và bé trong suốt quá trình thai kỳ và sinh nở mà còn bao gồm việc phòng ngừa, chẩn đoán và điều trị các biến chứng có thể xảy ra. Khoa sản kết hợp sự hiểu biết sâu sắc về sinh lý học, dược học và các kỹ thuật phẫu thuật để cung cấp cho các bà mẹ và em bé sự chăm sóc tốt nhất có thể. Ngoài ra, lĩnh vực này cũng thường xuyên phối hợp với các chuyên ngành khác như nhi khoa, y học nội tổng hợp và tâm lý học để đáp ứng nhu cầu đa dạng của phụ nữ trong giai đoạn quan trọng này của cuộc đời.\r\n\r\nKhác biệt với các chuyên ngành khác trong ngành y học như nội khoa, ngoại khoa hay nhi khoa, khoa sản tập trung vào việc theo dõi và đảm bảo sức khỏe cho cả mẹ và bé trong suốt quá trình thai kỳ và sau khi sinh nở. Trong khi đó, nội khoa chủ yếu điều trị các bệnh lý nội tạng không cần phẫu thuật, ngoại khoa tập trung vào điều trị bằng phẫu thuật và nhi khoa chăm sóc sức khỏe cho trẻ em. Khoa sản không chỉ giới hạn ở việc sinh đẻ mà còn bao gồm cả việc tư vấn và điều trị về sức khỏe sinh sản, phòng ngừa và xử lý các vấn đề liên quan đến cơ quan sinh dục nữ, mang lại cho phụ nữ sự an tâm và sức khỏe tốt nhất để họ có thể đảm nhận vai trò làm mẹ một cách trọn vẹn nhất.', 'published', '2025-11-16 08:07:00', '5 dấu hiệu mang thai sớm - Phòng khám Sản phụ khoa', 'Trễ kinh, buồn nôn, thay đổi vùng ngực... là những dấu hiệu mang thai sớm. Xem chi tiết tư vấn từ bác sĩ.', 'https://cdn.nhathuoclongchau.com.vn/unsafe/800x0/khoa_san_la_gi_tam_quan_trong_cua_khoa_san_trong_suc_khoe_sinh_san_o_nu_gioi1_1d2c573ee2.jpg', '2025-11-17 01:08:26', '2025-12-05 02:35:45', NULL),
(2, 21, NULL, 'Bài test lịch đăng', 'bai-test-lich-dang', NULL, '<p><i><strong>Test auto publish</strong></i></p>', 'published', '2025-12-05 02:40:00', NULL, NULL, NULL, '2025-12-05 02:37:23', '2025-12-05 02:39:32', '2025-12-05 02:39:32'),
(3, 21, 1, 'Lịch khám thai định kỳ chuẩn mẹ bầu cần nhớ', 'lich-kham-thai-dinh-ky-chuan-me-bau-can-nho', 'Việc khám thai đúng lịch giúp bác sĩ theo dõi sát sao sự phát triển của thai nhi và phát hiện sớm các bất thường.', '<p><strong>Mốc 1 (Tuần 5-8):</strong> Xác định có thai, vị trí thai và tim thai.</p><p><strong>Mốc 2 (Tuần 11-13):</strong> Đo độ mờ da gáy (sàng lọc Down).</p><p><strong>Mốc 3 (Tuần 20-22):</strong> Siêu âm 4D khảo sát dị tật hình thái.</p>', 'published', '2025-12-05 02:58:00', 'Lịch khám thai chuẩn 2025 - Những mốc quan trọng', 'Mẹ bầu cần khám thai vào những tuần nào? Xem ngay lịch khám thai chi tiết để đảm bảo thai kỳ khỏe mạnh.', 'http://127.0.0.1:8000/storage/uploads/posts/1764902080_win-20221201-23-47-35-pro.jpg', '2025-12-05 02:58:11', '2025-12-05 02:58:38', NULL),
(4, 21, 2, '5 dấu hiệu nhận biết tiểu đường thai kỳ sớm nhất', '5-dau-hieu-nhan-biet-tieu-duong-thai-ky-som-nhat', 'Tiểu đường thai kỳ nếu không phát hiện sớm có thể gây biến chứng nguy hiểm cho cả mẹ và bé.', '<p><i><strong>Thường xuyên khát nước, đi tiểu nhiều lần, mệt mỏi, sụt cân hoặc tăng cân bất thường...</strong></i></p>', 'published', '2025-12-05 03:06:00', NULL, NULL, NULL, '2025-12-05 03:00:59', '2025-12-05 03:08:51', '2025-12-05 03:08:51');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `bai_viet_tag`
--

CREATE TABLE `bai_viet_tag` (
  `bai_viet_id` bigint(20) UNSIGNED NOT NULL,
  `tag_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `bai_viet_tag`
--

INSERT INTO `bai_viet_tag` (`bai_viet_id`, `tag_id`) VALUES
(1, 1),
(2, 1),
(3, 4),
(4, 2);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `benh_ans`
--

CREATE TABLE `benh_ans` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `bac_si_id` bigint(20) UNSIGNED NOT NULL,
  `lich_hen_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ngay_kham` date NOT NULL DEFAULT '2025-11-04',
  `tieu_de` varchar(255) NOT NULL,
  `trieu_chung` text DEFAULT NULL,
  `chuan_doan` text DEFAULT NULL,
  `dieu_tri` text DEFAULT NULL,
  `ghi_chu` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `benh_ans`
--

INSERT INTO `benh_ans` (`id`, `user_id`, `bac_si_id`, `lich_hen_id`, `ngay_kham`, `tieu_de`, `trieu_chung`, `chuan_doan`, `dieu_tri`, `ghi_chu`, `created_at`, `updated_at`) VALUES
(1, 31, 10, NULL, '2025-11-04', 'Xét nghiệm máu', 'Đau đầu buồn nôn', 'Rối loạn nội tiết', 'Chuyển đến phòng B01', 'Nhớ đem đầy đủ giấy tờ', '2025-11-04 03:00:38', '2025-11-04 03:00:38'),
(2, 33, 11, 5, '2025-11-09', 'Testt', 'Test', 'Test', 'Test', 'Test', '2025-11-05 22:33:44', '2025-11-05 22:33:44'),
(3, 35, 11, 5, '2025-11-09', 'Thanh toán/Hóa đơn', 'Thanh toán/Hóa đơn', 'Thanh toán/Hóa đơn', 'Thanh toán/Hóa đơn', 'Thanh toán/Hóa đơn', '2025-11-06 21:18:10', '2025-11-06 21:18:10'),
(4, 35, 11, 11, '2025-11-23', 'Tốt cái lèn', 'Testt', 'Testt', 'Testt', 'Testt', '2025-11-19 21:56:34', '2025-11-19 23:31:56'),
(5, 35, 10, 9, '2025-11-21', 'Testt', 'Testt', 'Testt', 'Testt', 'Testt', '2025-11-20 00:36:12', '2025-11-20 00:36:12'),
(6, 35, 11, 12, '2025-11-22', '3:12 20/11/2025', '3:12 20/11/2025', '3:12 20/11/2025', '3:12 20/11/2025', '3:12 20/11/2025', '2025-11-20 01:13:05', '2025-11-20 01:13:05'),
(7, 38, 11, NULL, '2025-11-28', 'Testt', 'Testt', 'Testt', 'Testt', 'Testt', '2025-11-27 11:35:10', '2025-11-27 11:35:10'),
(8, 36, 2, 18, '2025-12-04', 'Hoàn tiền', 'Hoàn tiền', 'Hoàn tiền', 'Hoàn tiền', 'Hoàn tiền', '2025-12-04 07:39:08', '2025-12-04 07:39:08');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `benh_an_audits`
--

CREATE TABLE `benh_an_audits` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `benh_an_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `action` varchar(20) NOT NULL,
  `old_values` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`old_values`)),
  `new_values` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`new_values`)),
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `benh_an_audits`
--

INSERT INTO `benh_an_audits` (`id`, `benh_an_id`, `user_id`, `action`, `old_values`, `new_values`, `ip_address`, `user_agent`, `created_at`, `updated_at`) VALUES
(1, 4, 21, 'updated', '{\"id\":4,\"user_id\":35,\"bac_si_id\":11,\"lich_hen_id\":11,\"ngay_kham\":\"2025-11-23T00:00:00.000000Z\",\"tieu_de\":\"Testt\",\"trieu_chung\":\"Testt\",\"chuan_doan\":\"Testt\",\"dieu_tri\":\"Testt\",\"ghi_chu\":\"Testt\",\"created_at\":\"2025-11-20T04:56:34.000000Z\",\"updated_at\":\"2025-11-20T04:56:34.000000Z\"}', '{\"id\":4,\"user_id\":\"35\",\"bac_si_id\":\"11\",\"lich_hen_id\":\"11\",\"ngay_kham\":\"2025-11-23 00:00:00\",\"tieu_de\":\"T\\u1ed1t c\\u00e1i l\\u00e8n\",\"trieu_chung\":\"Testt\",\"chuan_doan\":\"Testt\",\"dieu_tri\":\"Testt\",\"ghi_chu\":\"Testt\",\"created_at\":\"2025-11-20 04:56:34\",\"updated_at\":\"2025-11-20 06:31:56\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-19 23:31:56', '2025-11-19 23:31:56'),
(2, 4, 21, 'files_uploaded', NULL, '{\"description\":\"Upload 2 t\\u1ec7p \\u0111\\u00ednh k\\u00e8m\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-19 23:32:51', '2025-11-19 23:32:51'),
(3, 5, 21, 'created', NULL, '{\"user_id\":\"35\",\"bac_si_id\":\"10\",\"lich_hen_id\":\"9\",\"ngay_kham\":\"2025-11-21 00:00:00\",\"tieu_de\":\"Testt\",\"trieu_chung\":\"Testt\",\"chuan_doan\":\"Testt\",\"dieu_tri\":\"Testt\",\"ghi_chu\":\"Testt\",\"updated_at\":\"2025-11-20 07:36:12\",\"created_at\":\"2025-11-20 07:36:12\",\"id\":5}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-20 00:36:12', '2025-11-20 00:36:12'),
(4, 5, 21, 'prescription_created', NULL, '{\"description\":\"K\\u00ea \\u0111\\u01a1n thu\\u1ed1c #7 v\\u1edbi 1 lo\\u1ea1i thu\\u1ed1c\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-20 00:38:42', '2025-11-20 00:38:42'),
(5, 5, 21, 'test_uploaded', NULL, '{\"description\":\"Upload k\\u1ebft qu\\u1ea3 x\\u00e9t nghi\\u1ec7m: N\\u1ee9ng kh\\u1ea9n\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-20 00:38:58', '2025-11-20 00:38:58'),
(6, 6, 21, 'created', NULL, '{\"user_id\":\"35\",\"bac_si_id\":\"11\",\"lich_hen_id\":\"12\",\"ngay_kham\":\"2025-11-22 00:00:00\",\"tieu_de\":\"3:12 20\\/11\\/2025\",\"trieu_chung\":\"3:12 20\\/11\\/2025\",\"chuan_doan\":\"3:12 20\\/11\\/2025\",\"dieu_tri\":\"3:12 20\\/11\\/2025\",\"ghi_chu\":\"3:12 20\\/11\\/2025\",\"updated_at\":\"2025-11-20 08:13:05\",\"created_at\":\"2025-11-20 08:13:05\",\"id\":6}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-20 01:13:05', '2025-11-20 01:13:05'),
(7, 7, 21, 'created', NULL, '{\"user_id\":\"38\",\"bac_si_id\":\"11\",\"lich_hen_id\":null,\"ngay_kham\":\"2025-11-28 00:00:00\",\"tieu_de\":\"Testt\",\"trieu_chung\":\"Testt\",\"chuan_doan\":\"Testt\",\"dieu_tri\":\"Testt\",\"ghi_chu\":\"Testt\",\"updated_at\":\"2025-11-27 18:35:10\",\"created_at\":\"2025-11-27 18:35:10\",\"id\":7}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-27 11:35:10', '2025-11-27 11:35:10'),
(8, 8, 21, 'created', NULL, '{\"user_id\":\"36\",\"bac_si_id\":\"2\",\"lich_hen_id\":\"18\",\"ngay_kham\":\"2025-12-04 00:00:00\",\"tieu_de\":\"Ho\\u00e0n ti\\u1ec1n\",\"trieu_chung\":\"Ho\\u00e0n ti\\u1ec1n\",\"chuan_doan\":\"Ho\\u00e0n ti\\u1ec1n\",\"dieu_tri\":\"Ho\\u00e0n ti\\u1ec1n\",\"ghi_chu\":\"Ho\\u00e0n ti\\u1ec1n\",\"updated_at\":\"2025-12-04 14:39:08\",\"created_at\":\"2025-12-04 14:39:08\",\"id\":8}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-04 07:39:08', '2025-12-04 07:39:08');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `benh_an_files`
--

CREATE TABLE `benh_an_files` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `benh_an_id` bigint(20) UNSIGNED NOT NULL,
  `ten_file` varchar(255) NOT NULL,
  `path` varchar(255) NOT NULL,
  `disk` varchar(50) NOT NULL DEFAULT 'public',
  `loai_mime` varchar(255) DEFAULT NULL,
  `size_bytes` bigint(20) UNSIGNED DEFAULT NULL,
  `uploaded_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `benh_an_files`
--

INSERT INTO `benh_an_files` (`id`, `benh_an_id`, `ten_file`, `path`, `disk`, `loai_mime`, `size_bytes`, `uploaded_by`, `created_at`, `updated_at`) VALUES
(4, 1, 'WIN_20231120_19_14_14_Pro.mp4', 'benh_an/CUTV7V1UrZn49fwLKYwzHTqpJqo9Sm8yNeoNtCaR.mp4', 'public', 'video/mp4', 7210365, 21, '2025-11-04 03:31:43', '2025-11-04 03:31:43'),
(5, 1, 'WIN_20230721_10_41_13_Pro.jpg', 'benh_an/zxZQEie5Q7RJuPKVzEPa85vQrSfVJex8pvPWSJ0A.jpg', 'public', 'image/jpeg', 120735, 21, '2025-11-04 11:04:48', '2025-11-04 11:04:48'),
(6, 1, 'WIN_20231120_19_13_57_Pro.jpg', 'benh_an/8Hm58WJbt35aanIyrHiFRwl5kXyui7x3uN4VHeAo.jpg', 'public', 'image/jpeg', 129772, 21, '2025-11-04 11:04:48', '2025-11-04 11:04:48'),
(7, 1, 'WIN_20231120_19_14_14_Pro.mp4', 'benh_an/hU2DOJ9JTtAJhyHoCENjG5aUE2EhRP1tQs5ys7dn.mp4', 'public', 'video/mp4', 7210365, 21, '2025-11-04 11:04:48', '2025-11-04 11:04:48'),
(8, 2, 'WIN_20230605_23_44_09_Pro.jpg', 'benh_an/i7BWCE78iCRukFoDgvvsd8lHvPzGM7qq45dwapbI.jpg', 'public', 'image/jpeg', 113880, 21, '2025-11-05 22:33:44', '2025-11-05 22:33:44'),
(9, 2, 'WIN_20230707_22_07_38_Pro.jpg', 'benh_an/AeHMJiL8X1PgshBRoH7SpHEsNrMuJ9Q50uwWlCCi.jpg', 'public', 'image/jpeg', 106237, 21, '2025-11-05 22:33:44', '2025-11-05 22:33:44'),
(10, 3, 'WIN_20230605_23_44_09_Pro.jpg', 'benh_an/tu9BkhENFwm0GsjIkfzXqCRzeapJcaXThjTsfu71.jpg', 'public', 'image/jpeg', 113880, 21, '2025-11-06 21:18:10', '2025-11-06 21:18:10'),
(11, 3, 'WIN_20230707_22_07_38_Pro.jpg', 'benh_an/Re0gDoFx1b4fQUhZf0LlnvuqQpqwSM2jYDnyvWPI.jpg', 'public', 'image/jpeg', 106237, 21, '2025-11-06 21:18:10', '2025-11-06 21:18:10'),
(13, 4, 'WIN_20230605_23_44_09_Pro.jpg', 'files/V0tmf0CGH7h2u4ROOHlAioPD5notpeLDqoTt9nN3.jpg', 'benh_an_private', 'image/jpeg', 113880, 21, '2025-11-19 23:32:51', '2025-11-19 23:32:51'),
(14, 4, 'WIN_20230707_22_07_38_Pro.jpg', 'files/b3tmApDjgHpzWYVs7YHzOG6zdZnMArUHvomBCoFA.jpg', 'benh_an_private', 'image/jpeg', 106237, 21, '2025-11-19 23:32:51', '2025-11-19 23:32:51'),
(15, 5, 'WIN_20221201_23_47_35_Pro.jpg', 'files/urJPJEdvAurs5iDaYTfsCWMiiLtOcTbVbZqQaSCe.jpg', 'benh_an_private', 'image/jpeg', 126794, 21, '2025-11-20 00:36:12', '2025-11-20 00:36:12'),
(16, 6, 'WIN_20240403_10_44_14_Pro.jpg', 'files/di79nmyFwirC3SREF25hJQqJwFO4H5vfsJndgVEk.jpg', 'benh_an_private', 'image/jpeg', 144149, 21, '2025-11-20 01:13:05', '2025-11-20 01:13:05'),
(17, 7, 'WIN_20230707_22_07_38_Pro.jpg', 'files/btjavTWTGqHbvyMA8wItZpYNWqi7wTHg0AC9XQGb.jpg', 'benh_an_private', 'image/jpeg', 106237, 21, '2025-11-27 11:35:10', '2025-11-27 11:35:10'),
(18, 8, 'WIN_20230707_22_07_38_Pro.jpg', 'files/ITTxOj4Vp3VmkLQq40FPDzc8UZ0Cvf5NG0jSvE7K.jpg', 'benh_an_private', 'image/jpeg', 106237, 21, '2025-12-04 07:39:08', '2025-12-04 07:39:08');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `ca_dieu_chinh_bac_sis`
--

CREATE TABLE `ca_dieu_chinh_bac_sis` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `bac_si_id` bigint(20) UNSIGNED NOT NULL,
  `ngay` date NOT NULL,
  `gio_bat_dau` time NOT NULL,
  `gio_ket_thuc` time NOT NULL,
  `hanh_dong` enum('add','modify','cancel') NOT NULL DEFAULT 'add',
  `ly_do` varchar(255) DEFAULT NULL,
  `meta` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`meta`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `ca_dieu_chinh_bac_sis`
--

INSERT INTO `ca_dieu_chinh_bac_sis` (`id`, `bac_si_id`, `ngay`, `gio_bat_dau`, `gio_ket_thuc`, `hanh_dong`, `ly_do`, `meta`, `created_at`, `updated_at`) VALUES
(1, 10, '2025-12-05', '08:00:00', '10:00:00', 'cancel', 'Bận đột xuất', NULL, '2025-12-04 06:32:41', '2025-12-04 06:32:41');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `ca_lam_viec_nhan_viens`
--

CREATE TABLE `ca_lam_viec_nhan_viens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nhan_vien_id` bigint(20) UNSIGNED NOT NULL,
  `ngay` date NOT NULL,
  `bat_dau` time NOT NULL,
  `ket_thuc` time NOT NULL,
  `ghi_chu` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `ca_lam_viec_nhan_viens`
--

INSERT INTO `ca_lam_viec_nhan_viens` (`id`, `nhan_vien_id`, `ngay`, `bat_dau`, `ket_thuc`, `ghi_chu`, `created_at`, `updated_at`) VALUES
(3, 1, '2025-11-15', '07:00:00', '12:00:00', 'test 13/11', '2025-11-13 01:58:05', '2025-11-13 01:58:05'),
(5, 1, '2025-11-21', '08:00:00', '15:00:00', 'test', '2025-11-20 20:10:49', '2025-11-20 20:10:49'),
(6, 5, '2025-11-21', '08:00:00', '17:00:00', 'yêu anh thanh', '2025-11-20 20:49:55', '2025-11-20 20:49:55');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `chuyen_khoas`
--

CREATE TABLE `chuyen_khoas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `ten` varchar(255) NOT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `mo_ta` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `chuyen_khoas`
--

INSERT INTO `chuyen_khoas` (`id`, `ten`, `slug`, `mo_ta`, `created_at`, `updated_at`) VALUES
(1, 'Sản khoa', 'san-khoa', 'Chăm sóc thai kỳ và sinh nở', '2025-11-16 23:59:56', '2025-11-16 23:59:56'),
(2, 'Phụ khoa', 'phu-khoa', 'Các bệnh lý phụ nữ', '2025-11-17 00:00:47', '2025-11-17 00:00:47'),
(3, 'Hiếm muộn', 'hiem-muon', 'Tư vấn và điều trị vô sinh', '2025-11-17 00:01:15', '2025-11-17 00:01:15'),
(4, 'Kế hoạch hóa', 'ke-hoach-hoa', 'Tư vấn tránh thai, đặt vòng', '2025-11-17 00:01:39', '2025-11-17 00:01:39');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `coupons`
--

CREATE TABLE `coupons` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `ma_giam_gia` varchar(255) NOT NULL COMMENT 'Mã coupon (VD: KHAI2025, GIAM50K)',
  `ten` varchar(255) NOT NULL COMMENT 'Tên chương trình',
  `mo_ta` text DEFAULT NULL,
  `loai` enum('phan_tram','tien_mat') NOT NULL DEFAULT 'phan_tram' COMMENT '% hoặc VNĐ',
  `gia_tri` decimal(15,2) NOT NULL COMMENT 'Giá trị giảm (% hoặc VNĐ)',
  `giam_toi_da` decimal(15,2) DEFAULT NULL COMMENT 'Giảm tối đa (cho loại %)',
  `don_toi_thieu` decimal(15,2) DEFAULT NULL COMMENT 'Giá trị đơn hàng tối thiểu',
  `ngay_bat_dau` date NOT NULL COMMENT 'Ngày bắt đầu hiệu lực',
  `ngay_ket_thuc` date NOT NULL COMMENT 'Ngày hết hạn',
  `so_lan_su_dung_toi_da` int(11) DEFAULT NULL COMMENT 'Số lần sử dụng tối đa (null = không giới hạn)',
  `so_lan_da_su_dung` int(11) NOT NULL DEFAULT 0 COMMENT 'Số lần đã sử dụng',
  `kich_hoat` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `danh_mucs`
--

CREATE TABLE `danh_mucs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_description` text DEFAULT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `danh_mucs`
--

INSERT INTO `danh_mucs` (`id`, `name`, `slug`, `meta_title`, `meta_description`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Cẩm nang Mẹ bầu', 'cam-nang-me-bau', 'Cẩm nang Mẹ bầu - Kiến thức mang thai chuẩn Y khoa', 'Tổng hợp các bài viết hướng dẫn chăm sóc sức khỏe cho mẹ và bé trong suốt thai kỳ.', 'Chuyên mục chia sẻ kiến thức từ bác sĩ chuyên khoa.', '2025-11-17 01:05:25', '2025-11-17 01:05:25'),
(2, 'Sức khỏe Phụ khoa', 'suc-khoe-phu-khoa', 'Tư vấn & Điều trị bệnh Phụ khoa uy tín', 'Giải đáp các vấn đề thầm kín, bệnh lý viêm nhiễm và sức khỏe sinh sản cho chị em phụ nữ.', 'Các bài viết về bệnh lý phụ khoa thường gặp.', '2025-12-05 02:54:17', '2025-12-05 02:54:17'),
(3, 'Kế hoạch hóa gia đình', 'ke-hoach-hoa-gia-dinh', 'Các biện pháp tránh thai an toàn & hiệu quả', 'Thông tin về thuốc tránh thai, đặt vòng, cấy que và tư vấn sức khỏe sinh sản.', 'Tư vấn kế hoạch hóa gia đình.', '2025-12-05 02:54:48', '2025-12-05 02:54:48');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `dich_vus`
--

CREATE TABLE `dich_vus` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `ten_dich_vu` varchar(255) NOT NULL,
  `mo_ta` text DEFAULT NULL,
  `gia` decimal(10,2) NOT NULL,
  `thoi_gian_uoc_tinh` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `dich_vus`
--

INSERT INTO `dich_vus` (`id`, `ten_dich_vu`, `mo_ta`, `gia`, `thoi_gian_uoc_tinh`, `created_at`, `updated_at`) VALUES
(2, 'Sinh', 'qqqqqqqqqqqqqqqqqqq', 50000.00, 40, '2025-10-27 08:34:25', '2025-10-27 08:34:25'),
(3, 'Siêu Âm', 'wwwwwwwwwwwwwww', 600000.00, 60, '2025-10-27 08:34:38', '2025-11-11 04:17:53'),
(4, 'Full Combo', 'eeeeeeeeeeeeeeeee', 1800000.00, 90, '2025-10-27 08:34:55', '2025-11-11 04:18:03'),
(5, 'Vệ Sinh', 'rrrrrrrrrrrrrrrrrr', 60000.00, 45, '2025-10-27 08:35:15', '2025-10-27 08:35:15'),
(6, 'Lâm sàn', 'qqqqqqqqqqqqqqqq', 200000.00, 20, '2025-11-21 09:35:10', '2025-11-21 09:35:10');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `don_hangs`
--

CREATE TABLE `don_hangs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `ma_don_hang` varchar(255) NOT NULL COMMENT 'Mã đơn hàng tự động',
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `coupon_id` bigint(20) UNSIGNED DEFAULT NULL,
  `tong_tien` decimal(15,2) NOT NULL DEFAULT 0.00 COMMENT 'Tổng tiền hàng',
  `giam_gia` decimal(15,2) NOT NULL DEFAULT 0.00 COMMENT 'Số tiền giảm giá',
  `thanh_toan` decimal(15,2) NOT NULL DEFAULT 0.00 COMMENT 'Số tiền phải thanh toán (tong - giam)',
  `trang_thai` enum('Chờ xử lý','Đã xác nhận','Đang giao','Hoàn thành','Đã hủy') NOT NULL DEFAULT 'Chờ xử lý',
  `trang_thai_thanh_toan` enum('Chưa thanh toán','Đã thanh toán','Hoàn tiền') NOT NULL DEFAULT 'Chưa thanh toán',
  `dia_chi_giao` varchar(255) DEFAULT NULL COMMENT 'Địa chỉ giao hàng',
  `sdt_nguoi_nhan` varchar(255) DEFAULT NULL,
  `ghi_chu` text DEFAULT NULL,
  `ngay_dat` timestamp NOT NULL DEFAULT current_timestamp(),
  `ngay_giao_du_kien` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `don_hang_items`
--

CREATE TABLE `don_hang_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `don_hang_id` bigint(20) UNSIGNED NOT NULL,
  `thuoc_id` bigint(20) UNSIGNED NOT NULL,
  `so_luong` int(11) NOT NULL DEFAULT 1,
  `don_gia` decimal(15,2) NOT NULL COMMENT 'Giá bán tại thời điểm đặt',
  `thanh_tien` decimal(15,2) NOT NULL COMMENT 'so_luong * don_gia',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `don_thuocs`
--

CREATE TABLE `don_thuocs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `benh_an_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `bac_si_id` bigint(20) UNSIGNED DEFAULT NULL,
  `lich_hen_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ghi_chu` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `don_thuocs`
--

INSERT INTO `don_thuocs` (`id`, `benh_an_id`, `user_id`, `bac_si_id`, `lich_hen_id`, `ghi_chu`, `created_at`, `updated_at`) VALUES
(1, 1, 31, 10, NULL, 'test 17/11', '2025-11-17 00:33:10', '2025-11-17 00:33:10'),
(2, 1, 31, 10, NULL, 'test 17/11', '2025-11-17 00:34:11', '2025-11-17 00:34:11'),
(3, 3, 35, 11, 5, NULL, '2025-11-17 00:35:39', '2025-11-17 00:35:39'),
(4, 2, 33, 11, 5, NULL, '2025-11-17 00:36:16', '2025-11-17 00:36:16'),
(5, 1, 31, 10, NULL, NULL, '2025-11-17 00:46:09', '2025-11-17 00:46:09'),
(6, 4, 35, 11, 11, NULL, '2025-11-19 23:12:14', '2025-11-19 23:12:14'),
(7, 5, 35, 10, 9, NULL, '2025-11-20 00:38:42', '2025-11-20 00:38:42');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `don_thuoc_items`
--

CREATE TABLE `don_thuoc_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `don_thuoc_id` bigint(20) UNSIGNED NOT NULL,
  `thuoc_id` bigint(20) UNSIGNED NOT NULL,
  `so_luong` int(11) NOT NULL,
  `lieu_dung` varchar(255) DEFAULT NULL,
  `cach_dung` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `hoan_tiens`
--

CREATE TABLE `hoan_tiens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `hoa_don_id` bigint(20) UNSIGNED NOT NULL,
  `so_tien` decimal(12,2) NOT NULL,
  `ly_do` varchar(255) DEFAULT NULL,
  `trang_thai` varchar(255) NOT NULL DEFAULT 'Đang xử lý',
  `provider` varchar(255) DEFAULT NULL,
  `provider_ref` varchar(255) DEFAULT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`payload`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `hoan_tiens`
--

INSERT INTO `hoan_tiens` (`id`, `hoa_don_id`, `so_tien`, `ly_do`, `trang_thai`, `provider`, `provider_ref`, `payload`, `created_at`, `updated_at`) VALUES
(1, 26, 600000.00, 'Hủy lịch hẹn', 'Hoàn thành', 'hoan_cong', 'REFUND-20251204142721-26', '{\"user_id\":21,\"user_name\":\"Admin\",\"created_at\":\"2025-12-04 14:27:21\"}', '2025-12-04 07:27:21', '2025-12-04 07:27:21');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `hoa_dons`
--

CREATE TABLE `hoa_dons` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `ma_hoa_don` varchar(255) DEFAULT NULL,
  `lich_hen_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `tong_tien` decimal(12,2) NOT NULL DEFAULT 0.00,
  `so_tien_da_thanh_toan` decimal(12,2) NOT NULL DEFAULT 0.00,
  `so_tien_con_lai` decimal(12,2) NOT NULL DEFAULT 0.00,
  `so_tien_da_hoan` decimal(12,2) NOT NULL DEFAULT 0.00,
  `trang_thai` varchar(255) NOT NULL DEFAULT 'Chưa thanh toán',
  `status` varchar(255) NOT NULL DEFAULT 'unpaid',
  `phuong_thuc` varchar(255) DEFAULT NULL,
  `ghi_chu` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `hoa_dons`
--

INSERT INTO `hoa_dons` (`id`, `ma_hoa_don`, `lich_hen_id`, `user_id`, `tong_tien`, `so_tien_da_thanh_toan`, `so_tien_con_lai`, `so_tien_da_hoan`, `trang_thai`, `status`, `phuong_thuc`, `ghi_chu`, `created_at`, `updated_at`) VALUES
(7, NULL, 5, 31, 30000.00, 0.00, 0.00, 0.00, 'Đã thanh toán', 'unpaid', 'Tiền mặt', '', '2025-11-05 23:51:08', '2025-11-06 00:14:15'),
(8, NULL, 4, 31, 40000.00, 0.00, 0.00, 0.00, 'Đã thanh toán', 'unpaid', 'VNPAY', NULL, '2025-11-05 23:51:11', '2025-11-06 00:09:09'),
(9, NULL, 3, 31, 50000.00, 0.00, 0.00, 0.00, 'Đã thanh toán', 'unpaid', 'VNPAY', NULL, '2025-11-05 23:51:14', '2025-11-06 00:12:03'),
(12, NULL, 6, 31, 25000.00, 0.00, 0.00, 0.00, 'Đã thanh toán', 'unpaid', 'VNPAY', NULL, '2025-11-06 00:23:23', '2025-11-06 00:24:02'),
(14, NULL, 2, 31, 100000.00, 0.00, 0.00, 0.00, 'Đã thanh toán', 'unpaid', 'MOMO', NULL, '2025-11-06 02:14:56', '2025-11-06 18:58:51'),
(16, NULL, 7, 35, 600000.00, 0.00, 0.00, 0.00, 'Đã thanh toán', 'unpaid', 'MOMO', NULL, '2025-11-07 01:39:13', '2025-11-07 01:40:31'),
(18, NULL, 9, 35, 60000.00, 0.00, 0.00, 0.00, 'Đã thanh toán', 'unpaid', 'MOMO', 'Tự động tạo khi lịch hẹn được xác nhận', '2025-11-07 10:09:54', '2025-11-07 10:10:55'),
(19, NULL, 11, 35, 60000.00, 0.00, 0.00, 0.00, 'Đã thanh toán', 'unpaid', 'VNPAY', NULL, '2025-11-20 00:51:39', '2025-11-20 00:54:57'),
(20, NULL, 12, 35, 1800000.00, 0.00, 0.00, 0.00, 'Đã thanh toán', 'unpaid', 'MOMO', NULL, '2025-11-20 01:06:57', '2025-11-20 01:18:04'),
(22, NULL, 14, 36, 1800000.00, 0.00, 0.00, 0.00, 'Đã thanh toán', 'unpaid', 'VNPAY', NULL, '2025-11-20 01:56:43', '2025-11-20 02:04:58'),
(23, NULL, 15, 36, 600000.00, 0.00, 0.00, 0.00, 'Đã thanh toán', 'unpaid', 'MOMO', NULL, '2025-11-20 02:08:32', '2025-11-20 02:09:23'),
(24, NULL, 16, 36, 60000.00, 0.00, 0.00, 0.00, 'Đã thanh toán', 'unpaid', 'MOMO', NULL, '2025-11-20 02:25:53', '2025-11-20 02:29:50'),
(25, NULL, 17, 36, 600000.00, 0.00, 0.00, 0.00, 'Đã thanh toán', 'unpaid', 'VNPAY', '', '2025-11-20 02:46:31', '2025-11-28 06:48:56'),
(26, 'INV-20251204-00001', 18, 36, 600000.00, 1200000.00, 0.00, 600000.00, 'Hoàn một phần', 'partial_refund', 'Tiền mặt', '', '2025-12-04 07:12:40', '2025-12-04 07:35:19');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `jobs`
--

INSERT INTO `jobs` (`id`, `queue`, `payload`, `attempts`, `reserved_at`, `available_at`, `created_at`) VALUES
(26, 'default', '{\"uuid\":\"316b025a-0e7d-4e24-949c-ecfeec80b92e\",\"displayName\":\"App\\\\Mail\\\\HoaDonDaThanhToan\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":15:{s:8:\\\"mailable\\\";O:26:\\\"App\\\\Mail\\\\HoaDonDaThanhToan\\\":3:{s:6:\\\"hoaDon\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:17:\\\"App\\\\Models\\\\HoaDon\\\";s:2:\\\"id\\\";i:26;s:9:\\\"relations\\\";a:2:{i:0;s:7:\\\"lichHen\\\";i:1;s:4:\\\"user\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:18:\\\"tn822798@gmail.com\\\";}}s:6:\\\"mailer\\\";s:4:\\\"smtp\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:3:\\\"job\\\";N;}\"}}', 0, NULL, 1764832486, 1764832486),
(27, 'default', '{\"uuid\":\"cb518a48-e7ca-4cea-95e8-6aa4a6554630\",\"displayName\":\"App\\\\Mail\\\\LichHenDaXacNhan\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":15:{s:8:\\\"mailable\\\";O:25:\\\"App\\\\Mail\\\\LichHenDaXacNhan\\\":3:{s:7:\\\"lichHen\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:18:\\\"App\\\\Models\\\\LichHen\\\";s:2:\\\"id\\\";i:18;s:9:\\\"relations\\\";a:4:{i:0;s:6:\\\"hoaDon\\\";i:1;s:4:\\\"user\\\";i:2;s:6:\\\"dichVu\\\";i:3;s:5:\\\"bacSi\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:18:\\\"tn822798@gmail.com\\\";}}s:6:\\\"mailer\\\";s:4:\\\"smtp\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:3:\\\"job\\\";N;}\"}}', 0, NULL, 1764832521, 1764832521);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `lich_hens`
--

CREATE TABLE `lich_hens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `bac_si_id` bigint(20) UNSIGNED NOT NULL,
  `dich_vu_id` bigint(20) UNSIGNED NOT NULL,
  `tong_tien` decimal(12,2) NOT NULL DEFAULT 0.00,
  `ngay_hen` date NOT NULL,
  `thoi_gian_hen` time NOT NULL,
  `ghi_chu` text DEFAULT NULL,
  `trang_thai` varchar(255) NOT NULL DEFAULT 'Chờ xác nhận',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `payment_status` varchar(255) NOT NULL DEFAULT 'Chưa thanh toán',
  `payment_method` varchar(255) DEFAULT NULL,
  `paid_at` timestamp NULL DEFAULT NULL,
  `cancelled_by` bigint(20) UNSIGNED DEFAULT NULL,
  `cancelled_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `lich_hens`
--

INSERT INTO `lich_hens` (`id`, `user_id`, `bac_si_id`, `dich_vu_id`, `tong_tien`, `ngay_hen`, `thoi_gian_hen`, `ghi_chu`, `trang_thai`, `created_at`, `updated_at`, `payment_status`, `payment_method`, `paid_at`, `cancelled_by`, `cancelled_at`) VALUES
(2, 31, 11, 2, 0.00, '2025-11-05', '09:00:00', 'test', 'Hoàn thành', '2025-11-04 10:03:28', '2025-11-05 09:48:25', 'Chưa thanh toán', NULL, NULL, NULL, NULL),
(3, 31, 11, 3, 0.00, '2025-11-05', '12:00:00', 'ssss', 'Đã hủy', '2025-11-04 10:04:46', '2025-11-05 09:48:22', 'Chưa thanh toán', NULL, NULL, NULL, NULL),
(4, 31, 11, 2, 0.00, '2025-11-05', '11:00:00', 'test lạy Chúa', 'Đã xác nhận', '2025-11-04 10:27:44', '2025-11-04 10:29:49', 'Chưa thanh toán', NULL, NULL, NULL, NULL),
(5, 31, 4, 2, 0.00, '2025-11-11', '06:00:00', 'tốt', 'Chờ xác nhận', '2025-11-05 19:20:04', '2025-11-11 03:17:18', 'Chưa thanh toán', NULL, NULL, NULL, NULL),
(6, 31, 2, 3, 0.00, '2025-11-05', '11:00:00', 'Test', 'Đã hủy', '2025-11-05 22:35:08', '2025-11-11 03:17:33', 'Chưa thanh toán', NULL, NULL, NULL, NULL),
(7, 35, 11, 5, 0.00, '2025-11-10', '09:30:00', 'Thanh toán/Hóa đơn', 'Đã xác nhận', '2025-11-06 21:27:32', '2025-11-07 01:40:31', 'Đã thanh toán', 'MOMO', '2025-11-07 01:40:31', NULL, NULL),
(9, 35, 11, 5, 0.00, '2025-11-21', '12:15:00', 'Xin Chúa', 'Đã xác nhận', '2025-11-07 10:09:07', '2025-11-20 01:14:03', 'Đã thanh toán', 'MOMO', '2025-11-07 10:10:55', NULL, NULL),
(10, 31, 11, 3, 0.00, '2025-11-15', '15:00:00', 'test 13/11', 'Chờ xác nhận', '2025-11-12 21:48:50', '2025-11-12 21:50:23', 'Chưa thanh toán', NULL, NULL, NULL, NULL),
(11, 35, 4, 3, 0.00, '2025-11-20', '15:00:00', NULL, 'Đã hủy', '2025-11-17 04:43:49', '2025-11-20 01:13:30', 'Đã thanh toán', 'VNPAY', '2025-11-20 00:54:57', NULL, NULL),
(12, 35, 11, 4, 1800000.00, '2025-11-22', '08:30:00', '3:12 20/11/2025', 'Hoàn thành', '2025-11-20 01:06:39', '2025-11-20 01:32:40', 'Đã thanh toán', 'MOMO', '2025-11-20 01:18:04', NULL, NULL),
(14, 36, 10, 4, 1800000.00, '2025-11-24', '10:00:00', '3h56 20/11/2025', 'Đã xác nhận', '2025-11-20 01:56:43', '2025-11-20 02:45:51', 'Đã thanh toán', 'VNPAY', '2025-11-20 02:04:58', NULL, NULL),
(15, 36, 4, 3, 600000.00, '2025-11-23', '06:00:00', '4h08----20/11/2025', 'Chờ xác nhận', '2025-11-20 02:08:32', '2025-11-20 02:09:23', 'Đã thanh toán', 'MOMO', '2025-11-20 02:09:23', NULL, NULL),
(16, 36, 2, 5, 60000.00, '2025-11-20', '10:31:00', '4h25----20/11/2025', 'Hoàn thành', '2025-11-20 02:25:53', '2025-11-20 02:44:03', 'Đã thanh toán', 'MOMO', '2025-11-20 02:29:50', NULL, NULL),
(17, 36, 10, 3, 600000.00, '2025-11-29', '08:00:00', 'tttt', 'Đã xác nhận', '2025-11-20 02:46:31', '2025-11-28 06:58:58', 'Đã thanh toán', 'VNPAY', '2025-11-28 06:48:56', NULL, NULL),
(18, 36, 2, 3, 600000.00, '2025-12-04', '16:01:00', 'hoàn tiền', 'Chờ xác nhận', '2025-12-04 07:12:40', '2025-12-04 07:35:19', 'Hoàn một phần', 'Tiền mặt', '2025-12-04 07:25:48', NULL, NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `lich_lam_viecs`
--

CREATE TABLE `lich_lam_viecs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `bac_si_id` bigint(20) UNSIGNED NOT NULL,
  `phong_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ngay_trong_tuan` tinyint(4) NOT NULL,
  `thoi_gian_bat_dau` time NOT NULL,
  `thoi_gian_ket_thuc` time NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `lich_lam_viecs`
--

INSERT INTO `lich_lam_viecs` (`id`, `bac_si_id`, `phong_id`, `ngay_trong_tuan`, `thoi_gian_bat_dau`, `thoi_gian_ket_thuc`, `created_at`, `updated_at`) VALUES
(1, 2, NULL, 2, '08:20:00', '17:00:00', '2025-10-27 08:37:33', '2025-10-27 08:37:33'),
(2, 2, NULL, 3, '09:00:00', '15:00:00', '2025-10-27 08:38:19', '2025-10-27 08:38:19'),
(3, 2, NULL, 5, '07:00:00', '18:00:00', '2025-10-27 08:38:40', '2025-10-27 08:38:40'),
(7, 4, NULL, 0, '06:00:00', '12:00:00', '2025-10-27 08:40:34', '2025-10-27 08:40:34'),
(10, 2, NULL, 4, '07:01:00', '17:01:00', '2025-11-03 05:01:36', '2025-11-03 05:01:36'),
(11, 2, NULL, 6, '07:01:00', '17:01:00', '2025-11-03 05:01:49', '2025-11-03 05:01:49'),
(12, 2, NULL, 0, '07:01:00', '15:01:00', '2025-11-03 05:02:01', '2025-11-03 05:02:01'),
(14, 11, NULL, 1, '08:00:00', '17:00:00', '2025-11-04 03:21:53', '2025-11-04 03:21:53'),
(15, 11, NULL, 2, '08:00:00', '17:00:00', '2025-11-04 03:22:06', '2025-11-04 03:22:06'),
(16, 11, NULL, 3, '09:00:00', '18:00:00', '2025-11-04 03:22:25', '2025-11-04 03:22:25'),
(17, 11, NULL, 6, '08:00:00', '17:00:00', '2025-11-11 04:35:10', '2025-11-11 04:35:10'),
(18, 2, 1, 1, '08:00:00', '17:00:00', '2025-11-17 00:30:26', '2025-11-17 00:30:26'),
(19, 10, 1, 1, '08:00:00', '17:00:00', '2025-11-20 01:56:07', '2025-11-20 01:56:07'),
(20, 11, 1, 4, '08:00:00', '17:00:00', '2025-11-27 06:23:43', '2025-11-27 06:23:43'),
(21, 10, 3, 5, '08:00:00', '17:00:00', '2025-12-04 06:29:16', '2025-12-04 06:29:16');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `lich_nghis`
--

CREATE TABLE `lich_nghis` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `bac_si_id` bigint(20) UNSIGNED NOT NULL,
  `ngay` date NOT NULL,
  `bat_dau` time NOT NULL,
  `ket_thuc` time NOT NULL,
  `ly_do` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `lich_nghis`
--

INSERT INTO `lich_nghis` (`id`, `bac_si_id`, `ngay`, `bat_dau`, `ket_thuc`, `ly_do`, `created_at`, `updated_at`) VALUES
(2, 11, '2025-11-14', '08:00:00', '10:00:00', 'Từ quê lên không kịp xe', '2025-11-11 04:44:36', '2025-11-11 04:44:36'),
(3, 2, '2025-11-27', '02:00:00', '03:00:00', 'Chở mẹ đi mua thuốc', '2025-11-27 06:32:05', '2025-11-27 06:32:05');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `login_audits`
--

CREATE TABLE `login_audits` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `ip` varchar(45) NOT NULL,
  `user_agent` text DEFAULT NULL,
  `status` enum('success','failed') NOT NULL DEFAULT 'failed',
  `reason` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `login_audits`
--

INSERT INTO `login_audits` (`id`, `user_id`, `email`, `ip`, `user_agent`, `status`, `reason`, `created_at`, `updated_at`) VALUES
(1, 21, 'Admin@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', NULL, '2025-11-20 22:45:48', '2025-11-20 22:45:48'),
(2, 21, 'Admin@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', NULL, '2025-11-22 03:21:05', '2025-11-22 03:21:05'),
(3, 37, 'vothidiemhang2020qng@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'failed', 'account_locked', '2025-11-22 03:25:22', '2025-11-22 03:25:22'),
(4, 37, 'vothidiemhang2020qng@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', NULL, '2025-11-22 03:25:37', '2025-11-22 03:25:37'),
(5, 37, 'vothidiemhang2020qng@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', NULL, '2025-11-22 03:27:05', '2025-11-22 03:27:05'),
(6, 37, 'vothidiemhang2020qng@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', NULL, '2025-11-22 03:27:31', '2025-11-22 03:27:31'),
(7, 37, 'vothidiemhang2020qng@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', NULL, '2025-11-22 03:30:44', '2025-11-22 03:30:44'),
(8, 21, 'Admin@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', NULL, '2025-11-23 08:06:12', '2025-11-23 08:06:12'),
(9, 21, 'Admin@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', NULL, '2025-11-24 02:55:32', '2025-11-24 02:55:32'),
(10, 36, 'tn822798@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', NULL, '2025-11-24 03:04:59', '2025-11-24 03:04:59'),
(11, 21, 'Admin@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', NULL, '2025-11-25 06:13:31', '2025-11-25 06:13:31'),
(12, 36, 'tn822798@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', NULL, '2025-11-25 06:16:14', '2025-11-25 06:16:14'),
(13, 36, 'tn822798@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', NULL, '2025-11-25 07:32:40', '2025-11-25 07:32:40'),
(14, 36, 'tn822798@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'failed', 'invalid_credentials', '2025-11-25 08:02:27', '2025-11-25 08:02:27'),
(15, 36, 'tn822798@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', NULL, '2025-11-25 08:02:32', '2025-11-25 08:02:32'),
(16, 36, 'tn822798@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', NULL, '2025-11-25 08:27:14', '2025-11-25 08:27:14'),
(17, 36, 'tn822798@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', NULL, '2025-11-25 08:39:47', '2025-11-25 08:39:47'),
(18, 37, 'vothidiemhang2020qng@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'failed', 'invalid_credentials', '2025-11-25 08:40:07', '2025-11-25 08:40:07'),
(19, 37, 'vothidiemhang2020qng@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', NULL, '2025-11-25 08:40:11', '2025-11-25 08:40:11'),
(20, 37, 'vothidiemhang2020qng@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', NULL, '2025-11-25 08:41:22', '2025-11-25 08:41:22'),
(21, 37, 'vothidiemhang2020qng@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', NULL, '2025-11-25 08:45:57', '2025-11-25 08:45:57'),
(22, 37, 'vothidiemhang2020qng@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', NULL, '2025-11-25 08:46:48', '2025-11-25 08:46:48'),
(23, 36, 'tn822798@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', NULL, '2025-11-25 08:49:44', '2025-11-25 08:49:44'),
(24, 37, 'vothidiemhang2020qng@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', NULL, '2025-11-25 08:50:12', '2025-11-25 08:50:12'),
(25, 21, 'Admin@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', NULL, '2025-11-25 11:18:47', '2025-11-25 11:18:47'),
(26, 21, 'Admin@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', NULL, '2025-11-26 00:47:04', '2025-11-26 00:47:04'),
(27, 21, 'Admin@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', NULL, '2025-11-26 02:52:49', '2025-11-26 02:52:49'),
(28, 21, 'Admin@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', NULL, '2025-11-27 04:06:37', '2025-11-27 04:06:37'),
(29, 21, 'Admin@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', NULL, '2025-11-27 09:52:21', '2025-11-27 09:52:21'),
(30, 31, 'Patient1@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', NULL, '2025-11-27 09:54:46', '2025-11-27 09:54:46'),
(31, 36, 'tn822798@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', NULL, '2025-11-27 09:55:13', '2025-11-27 09:55:13'),
(32, 36, 'tn822798@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', NULL, '2025-11-27 09:56:19', '2025-11-27 09:56:19'),
(33, 31, 'Patient1@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', NULL, '2025-11-27 10:26:42', '2025-11-27 10:26:42'),
(34, 31, 'Patient1@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', NULL, '2025-11-27 10:41:43', '2025-11-27 10:41:43'),
(35, 36, 'tn822798@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', NULL, '2025-11-27 10:42:08', '2025-11-27 10:42:08'),
(36, 21, 'Admin@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', NULL, '2025-11-27 10:44:11', '2025-11-27 10:44:11'),
(37, 36, 'tn822798@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', NULL, '2025-11-27 11:18:52', '2025-11-27 11:18:52'),
(38, 36, 'tn822798@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', NULL, '2025-11-27 11:23:13', '2025-11-27 11:23:13'),
(39, 38, 'Patient3@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', NULL, '2025-11-27 11:27:33', '2025-11-27 11:27:33'),
(40, 21, 'Admin@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', NULL, '2025-11-28 06:03:06', '2025-11-28 06:03:06'),
(41, 34, 'bac-si-2.11@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', NULL, '2025-11-28 06:06:15', '2025-11-28 06:06:15'),
(42, 21, 'Admin@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', NULL, '2025-11-28 06:08:01', '2025-11-28 06:08:01'),
(43, 34, 'bac-si-2.11@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', NULL, '2025-11-28 06:08:15', '2025-11-28 06:08:15'),
(44, 30, 'vo-thi-diem-hang.10@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', NULL, '2025-11-28 06:09:38', '2025-11-28 06:09:38'),
(45, 30, 'vo-thi-diem-hang.10@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', NULL, '2025-11-28 06:26:58', '2025-11-28 06:26:58'),
(46, 21, 'Admin@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', NULL, '2025-11-28 07:02:34', '2025-11-28 07:02:34'),
(47, 31, 'Patient1@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', NULL, '2025-11-28 07:18:27', '2025-11-28 07:18:27'),
(48, 36, 'tn822798@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', NULL, '2025-11-28 07:18:51', '2025-11-28 07:18:51'),
(49, 36, 'tn822798@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', NULL, '2025-11-28 07:36:23', '2025-11-28 07:36:23'),
(50, 36, 'tn822798@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', NULL, '2025-11-28 07:39:40', '2025-11-28 07:39:40'),
(51, 36, 'tn822798@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', NULL, '2025-11-28 07:41:18', '2025-11-28 07:41:18'),
(52, 36, 'tn822798@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', NULL, '2025-11-28 07:50:48', '2025-11-28 07:50:48'),
(53, 31, 'Patient1@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', NULL, '2025-11-28 07:51:08', '2025-11-28 07:51:08'),
(54, 38, 'Patient3@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', NULL, '2025-11-28 07:51:20', '2025-11-28 07:51:20'),
(55, 30, 'vo-thi-diem-hang.10@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', NULL, '2025-11-28 07:56:08', '2025-11-28 07:56:08'),
(56, 36, 'tn822798@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', NULL, '2025-11-28 08:02:22', '2025-11-28 08:02:22'),
(57, 30, 'vo-thi-diem-hang.10@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', NULL, '2025-11-28 08:03:27', '2025-11-28 08:03:27'),
(58, 36, 'tn822798@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', NULL, '2025-11-28 08:04:50', '2025-11-28 08:04:50'),
(59, 38, 'Patient3@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', NULL, '2025-11-28 08:05:22', '2025-11-28 08:05:22'),
(60, 37, 'vothidiemhang2020qng@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', NULL, '2025-11-28 08:06:12', '2025-11-28 08:06:12'),
(61, 21, 'Admin@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', NULL, '2025-11-28 15:21:47', '2025-11-28 15:21:47'),
(62, 21, 'Admin@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', NULL, '2025-11-29 02:57:50', '2025-11-29 02:57:50'),
(63, 38, 'Patient3@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', NULL, '2025-11-29 04:13:32', '2025-11-29 04:13:32'),
(64, 21, 'Admin@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', NULL, '2025-11-29 04:40:07', '2025-11-29 04:40:07'),
(65, 38, 'Patient3@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', NULL, '2025-11-29 04:40:22', '2025-11-29 04:40:22'),
(66, 21, 'Admin@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', NULL, '2025-11-30 03:29:39', '2025-11-30 03:29:39'),
(67, 38, 'Patient3@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', NULL, '2025-11-30 03:50:17', '2025-11-30 03:50:17'),
(68, 21, 'Admin@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', NULL, '2025-12-04 06:17:58', '2025-12-04 06:17:58'),
(69, 30, 'vo-thi-diem-hang.10@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', NULL, '2025-12-04 06:18:41', '2025-12-04 06:18:41'),
(70, 38, 'Patient3@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', NULL, '2025-12-04 06:35:18', '2025-12-04 06:35:18'),
(71, 30, 'vo-thi-diem-hang.10@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', NULL, '2025-12-04 06:49:40', '2025-12-04 06:49:40'),
(72, 38, 'Patient3@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', NULL, '2025-12-04 06:50:07', '2025-12-04 06:50:07'),
(73, 36, 'tn822798@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', NULL, '2025-12-04 06:59:42', '2025-12-04 06:59:42'),
(74, 36, 'tn822798@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', NULL, '2025-12-04 11:09:55', '2025-12-04 11:09:55'),
(75, 21, 'Admin@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', NULL, '2025-12-05 02:18:02', '2025-12-05 02:18:02'),
(76, 38, 'Patient3@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', NULL, '2025-12-05 02:33:31', '2025-12-05 02:33:31'),
(77, 38, 'Patient3@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', NULL, '2025-12-05 02:59:02', '2025-12-05 02:59:02'),
(78, 36, 'tn822798@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', NULL, '2025-12-05 03:23:02', '2025-12-05 03:23:02'),
(79, 38, 'Patient3@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', NULL, '2025-12-05 03:45:22', '2025-12-05 03:45:22'),
(80, 38, 'Patient3@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', NULL, '2025-12-05 04:10:52', '2025-12-05 04:10:52'),
(81, 36, 'tn822798@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'success', NULL, '2025-12-05 04:16:17', '2025-12-05 04:16:17');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2025_10_08_150521_create_bac_sis_table', 1),
(6, '2025_10_08_160313_create_dich_vus_table', 1),
(7, '2025_10_08_170625_create_lich_hens_table', 1),
(8, '2025_10_08_181011_add_role_to_users_table', 1),
(9, '2025_10_08_192322_create_lich_lam_viecs_table', 1),
(10, '2025_10_27_200000_add_unique_index_to_lich_hens', 1),
(11, '2025_11_03_000000_create_lich_nghis_table', 2),
(13, '2025_11_03_010000_add_user_id_to_bac_sis', 3),
(14, '2025_11_03_130343_add_user_id_to_bac_sis_table', 4),
(15, '2025_11_04_084258_update_users_table_set_role_defaults', 4),
(16, '2025_11_04_190000_create_benh_ans_table', 4),
(17, '2025_11_04_190100_create_benh_an_files_table', 4),
(18, '2025_11_05_120000_backfill_lich_hens_status_to_vn', 5),
(19, '2025_11_05_130000_create_hoa_dons_table', 6),
(20, '2025_11_05_130100_create_thanh_toans_table', 6),
(21, '2025_11_07_000002_create_hoan_tiens_table', 7),
(22, '2025_11_07_000000_add_payment_columns_to_lich_hens_table', 8),
(23, '2025_11_07_010000_rename_payment_columns_on_lich_hens_table', 9),
(24, '2025_11_10_120000_add_cancel_columns_to_lich_hens', 10),
(26, '2025_11_11_093527_add_missing_columns_to_bac_sis_table', 11),
(27, '2025_11_13_120001_create_thuocs_table', 12),
(28, '2025_11_13_120002_create_don_thuocs_table', 12),
(29, '2025_11_13_120003_create_xet_nghiems_table', 12),
(30, '2025_11_13_170001_create_nhan_viens_table', 13),
(31, '2025_11_13_170002_create_ca_lam_viec_nhan_viens_table', 13),
(32, '2025_11_13_180000_add_unique_user_to_nhan_viens', 14),
(33, '2025_11_14_090000_create_ca_dieu_chinh_bac_sis_table', 15),
(34, '2025_11_14_230000_add_time_columns_to_lich_hens', 16),
(35, '2025_11_02_235900_create_lich_nghis_table_if_not_exists', 17),
(36, '2025_11_17_000001_create_nha_cung_caps_table', 18),
(37, '2025_11_17_000002_create_thuoc_khos_table', 18),
(38, '2025_11_17_000003_create_phieu_nhaps_table', 18),
(39, '2025_11_17_000004_create_phieu_xuats_table', 18),
(40, '2025_11_17_100100_create_chuyen_khoas_and_phongs', 19),
(41, '2025_11_17_100200_add_phong_id_to_lich_lam_viecs', 19),
(42, '2025_11_17_110000_create_danh_mucs_table', 20),
(43, '2025_11_17_110100_create_tags_table', 20),
(44, '2025_11_17_110200_create_bai_viets_table', 20),
(45, '2025_11_17_110300_create_bai_viet_tag_table', 20),
(46, '2025_11_17_110400_reinforce_lich_hen_slot_uniqueness', 21),
(47, '2025_11_17_104853_create_jobs_table', 22),
(48, '2025_11_17_104907_create_jobs_table', 23),
(49, '2025_11_20_054524_create_benh_an_audits_table', 24),
(50, '2025_11_20_055120_add_disk_to_benh_an_files_table', 25),
(51, '2025_11_20_100000_create_payment_logs_table', 26),
(52, '2025_11_20_100100_add_idempotency_key_to_thanh_toans_table', 26),
(53, '2025_11_20_073110_add_disk_to_xet_nghiems_table', 27),
(54, '2025_11_20_110000_add_tong_tien_to_lich_hens_table', 28),
(55, '2025_11_21_000001_create_nhan_vien_audits_table', 29),
(56, '2025_11_21_041434_create_permission_tables', 30),
(57, '2025_11_21_050000_add_account_security_to_users', 31),
(58, '2025_11_21_050100_create_login_audits_table', 31),
(59, '2025_11_30_105600_add_avatar_to_bac_sis_table', 32),
(60, '2025_12_04_140000_add_status_fields_to_hoa_dons_table', 33),
(62, '2025_12_04_145328_add_ton_toi_thieu_to_thuocs_table', 34),
(63, '2025_12_04_150308_create_coupons_table', 34),
(64, '2025_12_04_150320_create_don_hangs_table', 35),
(65, '2025_12_04_150330_create_don_hang_items_table', 35),
(66, '2025_12_04_155210_create_nha_cung_cap_thuoc_table', 36),
(67, '2025_12_04_160000_fix_phieu_nhaps_foreign_key', 37),
(68, '2025_12_04_160100_fix_phieu_nhap_items_foreign_key', 38),
(69, '2025_12_04_160200_fix_phieu_xuat_items_foreign_key', 38),
(70, '2025_12_04_150000_add_status_to_phongs_table', 39),
(71, '2025_12_05_092931_add_deleted_at_to_bai_viets_table', 40),
(72, '2025_12_05_101210_create_patient_profiles_table', 41),
(73, '2025_12_05_101211_create_notification_preferences_table', 41);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\User', 21),
(2, 'App\\Models\\User', 21),
(4, 'App\\Models\\User', 30),
(4, 'App\\Models\\User', 34),
(5, 'App\\Models\\User', 37),
(6, 'App\\Models\\User', 36),
(6, 'App\\Models\\User', 38),
(11, 'App\\Models\\User', 37);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `nhan_viens`
--

CREATE TABLE `nhan_viens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ho_ten` varchar(255) NOT NULL,
  `chuc_vu` varchar(255) DEFAULT NULL,
  `so_dien_thoai` varchar(255) DEFAULT NULL,
  `email_cong_viec` varchar(255) DEFAULT NULL,
  `ngay_sinh` date DEFAULT NULL,
  `gioi_tinh` varchar(10) DEFAULT NULL,
  `avatar_path` varchar(255) DEFAULT NULL,
  `trang_thai` varchar(20) NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `nhan_viens`
--

INSERT INTO `nhan_viens` (`id`, `user_id`, `ho_ten`, `chuc_vu`, `so_dien_thoai`, `email_cong_viec`, `ngay_sinh`, `gioi_tinh`, `avatar_path`, `trang_thai`, `created_at`, `updated_at`) VALUES
(1, 32, 'Võ Thẹn Hỳ', 'yêu anh', '0147852369', 'VTH@gmail.com', '2000-01-01', 'nu', 'nv_avatar/cNEwrV8QKmh5sVP61xVQF0BuKSSopNQhoEgdObyQ.jpg', 'active', '2025-11-13 01:53:03', '2025-11-27 07:15:47'),
(5, 37, 'Nhân viên Võ', 'Y tá Kim', '0968177630', 'vothidiemhang2020qng@gmail.com', '2004-12-12', 'nu', 'nv_avatar/EpAC6W9hBaCLMZM0XfEONdGi4bhgIwhidtY9lFm3.jpg', 'active', '2025-11-20 20:41:46', '2025-11-27 07:40:32');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `nhan_vien_audits`
--

CREATE TABLE `nhan_vien_audits` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nhan_vien_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `action` varchar(50) NOT NULL,
  `old_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`old_data`)),
  `new_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`new_data`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `nhan_vien_audits`
--

INSERT INTO `nhan_vien_audits` (`id`, `nhan_vien_id`, `user_id`, `action`, `old_data`, `new_data`, `created_at`, `updated_at`) VALUES
(2, 1, 21, 'updated', '{\"chuc_vu\":\"B\\u00fa canh anh thu\",\"updated_at\":\"2025-11-13T08:53:03.000000Z\"}', '{\"chuc_vu\":\"y\\u00eau anh\",\"updated_at\":\"2025-11-21 03:09:52\"}', '2025-11-20 20:09:52', '2025-11-20 20:09:52'),
(3, 5, 21, 'created', NULL, '{\"ho_ten\":\"Nh\\u00e2n vi\\u00ean V\\u00f5\",\"chuc_vu\":\"Y t\\u00e1 Kim\",\"so_dien_thoai\":\"0968177630\",\"email_cong_viec\":\"vothidiemhang2020qng@gmail.com\",\"ngay_sinh\":\"2004-12-12\",\"gioi_tinh\":\"nu\",\"avatar_path\":\"nv_avatar\\/pgrri4zHZts4XtQkZLisj5araVgMc9eT1qNuPrh1.jpg\",\"user_id\":37,\"updated_at\":\"2025-11-21 03:41:46\",\"created_at\":\"2025-11-21 03:41:46\",\"id\":5}', '2025-11-20 20:41:46', '2025-11-20 20:41:46'),
(4, 5, 21, 'updated', '{\"trang_thai\":\"active\",\"updated_at\":\"2025-11-21T03:41:46.000000Z\"}', '{\"trang_thai\":\"inactive\",\"updated_at\":\"2025-11-21 03:52:42\"}', '2025-11-20 20:52:42', '2025-11-20 20:52:42'),
(5, 5, 21, 'updated', '{\"trang_thai\":\"inactive\",\"updated_at\":\"2025-11-21T03:52:42.000000Z\"}', '{\"trang_thai\":\"active\",\"updated_at\":\"2025-11-21 03:53:12\"}', '2025-11-20 20:53:12', '2025-11-20 20:53:12'),
(6, 5, 21, 'updated', '{\"trang_thai\":\"inactive\",\"updated_at\":\"2025-11-21T03:53:12.000000Z\"}', '{\"trang_thai\":\"active\",\"updated_at\":\"2025-11-21 04:07:35\"}', '2025-11-20 21:07:35', '2025-11-20 21:07:35'),
(7, 5, 21, 'updated', '{\"trang_thai\":\"active\",\"updated_at\":\"2025-11-20T21:07:35.000000Z\"}', '{\"trang_thai\":\"inactive\",\"updated_at\":\"2025-11-27 13:58:08\"}', '2025-11-27 06:58:08', '2025-11-27 06:58:08'),
(8, 5, 21, 'updated', '{\"trang_thai\":\"inactive\",\"updated_at\":\"2025-11-27T06:58:08.000000Z\"}', '{\"trang_thai\":\"active\",\"updated_at\":\"2025-11-27 14:15:13\"}', '2025-11-27 07:15:13', '2025-11-27 07:15:13'),
(9, 5, 21, 'updated', '{\"trang_thai\":\"active\",\"updated_at\":\"2025-11-27T07:15:13.000000Z\"}', '{\"trang_thai\":\"disabled\",\"updated_at\":\"2025-11-27 14:15:18\"}', '2025-11-27 07:15:18', '2025-11-27 07:15:18'),
(10, 5, 21, 'updated', '{\"trang_thai\":\"disabled\",\"updated_at\":\"2025-11-27T07:15:18.000000Z\"}', '{\"trang_thai\":\"on_leave\",\"updated_at\":\"2025-11-27 14:15:22\"}', '2025-11-27 07:15:22', '2025-11-27 07:15:22'),
(11, 5, 21, 'updated', '{\"trang_thai\":\"on_leave\",\"updated_at\":\"2025-11-27T07:15:22.000000Z\"}', '{\"trang_thai\":\"active\",\"updated_at\":\"2025-11-27 14:15:37\"}', '2025-11-27 07:15:37', '2025-11-27 07:15:37'),
(12, 1, 21, 'updated', '{\"trang_thai\":\"inactive\",\"updated_at\":\"2025-11-20T20:09:52.000000Z\"}', '{\"trang_thai\":\"disabled\",\"updated_at\":\"2025-11-27 14:15:41\"}', '2025-11-27 07:15:41', '2025-11-27 07:15:41'),
(13, 1, 21, 'updated', '{\"trang_thai\":\"disabled\",\"updated_at\":\"2025-11-27T07:15:41.000000Z\"}', '{\"trang_thai\":\"active\",\"updated_at\":\"2025-11-27 14:15:47\"}', '2025-11-27 07:15:47', '2025-11-27 07:15:47'),
(14, 5, 21, 'updated', '{\"trang_thai\":\"active\",\"updated_at\":\"2025-11-27T07:15:37.000000Z\"}', '{\"trang_thai\":\"inactive\",\"updated_at\":\"2025-11-27 14:35:33\"}', '2025-11-27 07:35:33', '2025-11-27 07:35:33'),
(15, 5, 21, 'updated', '{\"trang_thai\":\"inactive\",\"updated_at\":\"2025-11-27T07:35:33.000000Z\"}', '{\"trang_thai\":\"active\",\"updated_at\":\"2025-11-27 14:35:36\"}', '2025-11-27 07:35:36', '2025-11-27 07:35:36'),
(16, 5, 21, 'updated', '{\"avatar_path\":\"nv_avatar\\/pgrri4zHZts4XtQkZLisj5araVgMc9eT1qNuPrh1.jpg\",\"updated_at\":\"2025-11-27T07:35:36.000000Z\"}', '{\"avatar_path\":\"nv_avatar\\/EpAC6W9hBaCLMZM0XfEONdGi4bhgIwhidtY9lFm3.jpg\",\"updated_at\":\"2025-11-27 14:40:32\"}', '2025-11-27 07:40:32', '2025-11-27 07:40:32');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `nha_cung_caps`
--

CREATE TABLE `nha_cung_caps` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `ten` varchar(255) NOT NULL,
  `dia_chi` varchar(255) DEFAULT NULL,
  `so_dien_thoai` varchar(30) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `ghi_chu` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `nha_cung_caps`
--

INSERT INTO `nha_cung_caps` (`id`, `ten`, `dia_chi`, `so_dien_thoai`, `email`, `ghi_chu`, `created_at`, `updated_at`) VALUES
(3, 'Zuellig Pharma', 'TP.HCM', '0287654321', 'info@zuellig.com', NULL, '2025-12-04 10:40:17', '2025-12-04 10:40:17'),
(4, 'Dược phẩm Vimedimex', 'Quận 1, TP.HCM', '028 3844 4633', 'contact@vimedimex.com', NULL, '2025-12-04 10:43:52', '2025-12-04 10:43:52');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `nha_cung_cap_thuoc`
--

CREATE TABLE `nha_cung_cap_thuoc` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nha_cung_cap_id` bigint(20) UNSIGNED NOT NULL,
  `thuoc_id` bigint(20) UNSIGNED NOT NULL,
  `gia_nhap_mac_dinh` decimal(15,2) DEFAULT NULL COMMENT 'Giá nhập mặc định từ NCC này',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `nha_cung_cap_thuoc`
--

INSERT INTO `nha_cung_cap_thuoc` (`id`, `nha_cung_cap_id`, `thuoc_id`, `gia_nhap_mac_dinh`, `created_at`, `updated_at`) VALUES
(1, 3, 5, 15000.00, '2025-12-04 10:40:53', '2025-12-04 10:40:53'),
(2, 4, 6, 12000.00, '2025-12-04 10:44:19', '2025-12-04 10:44:19'),
(3, 4, 7, 10000.00, '2025-12-04 10:46:09', '2025-12-04 10:46:09');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `notification_preferences`
--

CREATE TABLE `notification_preferences` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `email_appointment_reminder` tinyint(1) NOT NULL DEFAULT 1,
  `email_appointment_confirmed` tinyint(1) NOT NULL DEFAULT 1,
  `email_appointment_cancelled` tinyint(1) NOT NULL DEFAULT 1,
  `email_test_results` tinyint(1) NOT NULL DEFAULT 1,
  `email_promotions` tinyint(1) NOT NULL DEFAULT 0,
  `sms_appointment_reminder` tinyint(1) NOT NULL DEFAULT 0,
  `sms_appointment_confirmed` tinyint(1) NOT NULL DEFAULT 0,
  `reminder_hours_before` int(11) NOT NULL DEFAULT 24 COMMENT 'Nhắc trước bao nhiêu giờ',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `notification_preferences`
--

INSERT INTO `notification_preferences` (`id`, `user_id`, `email_appointment_reminder`, `email_appointment_confirmed`, `email_appointment_cancelled`, `email_test_results`, `email_promotions`, `sms_appointment_reminder`, `sms_appointment_confirmed`, `reminder_hours_before`, `created_at`, `updated_at`) VALUES
(1, 36, 1, 1, 1, 1, 0, 0, 0, 24, '2025-12-05 04:37:24', '2025-12-05 04:37:24');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `patient_profiles`
--

CREATE TABLE `patient_profiles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `nhom_mau` varchar(255) DEFAULT NULL COMMENT 'A, B, AB, O, A+, A-, ...',
  `chieu_cao` decimal(5,2) DEFAULT NULL COMMENT 'cm',
  `can_nang` decimal(5,2) DEFAULT NULL COMMENT 'kg',
  `allergies` text DEFAULT NULL COMMENT 'Dị ứng (JSON array)',
  `tien_su_benh` text DEFAULT NULL COMMENT 'Tiền sử bệnh lý',
  `thuoc_dang_dung` text DEFAULT NULL COMMENT 'Thuốc đang sử dụng',
  `benh_man_tinh` text DEFAULT NULL COMMENT 'Bệnh mạn tính',
  `emergency_contact_name` varchar(255) DEFAULT NULL,
  `emergency_contact_phone` varchar(255) DEFAULT NULL,
  `emergency_contact_relation` varchar(255) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `patient_profiles`
--

INSERT INTO `patient_profiles` (`id`, `user_id`, `nhom_mau`, `chieu_cao`, `can_nang`, `allergies`, `tien_su_benh`, `thuoc_dang_dung`, `benh_man_tinh`, `emergency_contact_name`, `emergency_contact_phone`, `emergency_contact_relation`, `avatar`, `created_at`, `updated_at`) VALUES
(1, 36, 'A', 170.00, 65.00, '[\"Penicillin\",\"Ph\\u1ea5n hoa\",\"H\\u1ea3i s\\u1ea3n\"]', 'Phẫu thuật ruột thừa 2020', 'Vitamin C', 'Không', 'Nguyễn Văn A', '0398219340', 'Vợ', 'avatars/w3ySyRmzoyXphvU4nPc5tYT6D7QQJuQ4V0DGtIAv.jpg', '2025-12-05 03:23:48', '2025-12-05 04:36:52'),
(2, 38, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'avatars/qonVp1TTXsRdUyrjTIE7E8ibPneR51vwQpszH6MX.jpg', '2025-12-05 04:11:17', '2025-12-05 04:11:17');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `payment_logs`
--

CREATE TABLE `payment_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `hoa_don_id` bigint(20) UNSIGNED DEFAULT NULL,
  `provider` varchar(255) NOT NULL,
  `event_type` varchar(255) NOT NULL,
  `idempotency_key` varchar(255) DEFAULT NULL,
  `transaction_ref` varchar(255) DEFAULT NULL,
  `result_code` varchar(255) DEFAULT NULL,
  `result_message` text DEFAULT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`payload`)),
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `payment_logs`
--

INSERT INTO `payment_logs` (`id`, `hoa_don_id`, `provider`, `event_type`, `idempotency_key`, `transaction_ref`, `result_code`, `result_message`, `payload`, `ip_address`, `user_agent`, `created_at`, `updated_at`) VALUES
(1, 19, 'VNPAY', 'request', NULL, NULL, NULL, NULL, '{\"vnp_Version\":\"2.1.0\",\"vnp_TmnCode\":\"I2U8WK7F\",\"vnp_Amount\":6000000,\"vnp_Command\":\"pay\",\"vnp_CreateDate\":\"20251120075435\",\"vnp_CurrCode\":\"VND\",\"vnp_IpAddr\":\"127.0.0.1\",\"vnp_Locale\":\"vn\",\"vnp_OrderInfo\":\"Thanh toan hoa don #19\",\"vnp_OrderType\":\"billpayment\",\"vnp_ReturnUrl\":\"http:\\/\\/127.0.0.1:8000\\/payment\\/vnpay-return\",\"vnp_TxnRef\":\"19\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-20 00:54:35', '2025-11-20 00:54:35'),
(2, 19, 'VNPAY', 'return', NULL, 'VNP15272341', '00', NULL, '{\"vnp_Amount\":\"6000000\",\"vnp_BankCode\":\"NCB\",\"vnp_BankTranNo\":\"VNP15272341\",\"vnp_CardType\":\"ATM\",\"vnp_OrderInfo\":\"Thanh toan hoa don #19\",\"vnp_PayDate\":\"20251120145454\",\"vnp_ResponseCode\":\"00\",\"vnp_TmnCode\":\"I2U8WK7F\",\"vnp_TransactionNo\":\"15272341\",\"vnp_TransactionStatus\":\"00\",\"vnp_TxnRef\":\"19\",\"vnp_SecureHash\":\"50467225b528f23163e833ca27a879e9f06acd5c3f9eda6364c9403ab8d9a2b5552e86271b2ccafa223992137e444f660026341168d1a75f7c0abd3913d97147\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-20 00:54:57', '2025-11-20 00:54:57'),
(3, 18, 'MOMO', 'request', NULL, NULL, NULL, NULL, '{\"partnerCode\":\"MOMOBKUN20180529\",\"partnerName\":\"Test\",\"storeId\":\"MomoTestStore\",\"requestId\":\"1763625943\",\"amount\":\"60000\",\"orderId\":\"18_1763625943\",\"orderInfo\":\"Thanh to\\u00e1n h\\u00f3a \\u0111\\u01a1n #18\",\"redirectUrl\":\"http:\\/\\/127.0.0.1:8000\\/payment\\/momo-return\",\"ipnUrl\":\"http:\\/\\/127.0.0.1:8000\\/payment\\/momo-ipn\",\"lang\":\"vi\",\"extraData\":\"\",\"requestType\":\"payWithMethod\",\"signature\":\"411de459666ef62bde5fa946efc18881d7945cfa248dc6c581ca4103f1a827e7\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-20 01:05:43', '2025-11-20 01:05:43'),
(4, 20, 'MOMO', 'request', NULL, NULL, NULL, NULL, '{\"partnerCode\":\"MOMOBKUN20180529\",\"partnerName\":\"Test\",\"storeId\":\"MomoTestStore\",\"requestId\":\"1763626628\",\"amount\":\"1800000\",\"orderId\":\"20_1763626628\",\"orderInfo\":\"Thanh to\\u00e1n h\\u00f3a \\u0111\\u01a1n #20\",\"redirectUrl\":\"http:\\/\\/127.0.0.1:8000\\/payment\\/momo-return\",\"ipnUrl\":\"http:\\/\\/127.0.0.1:8000\\/payment\\/momo-ipn\",\"lang\":\"vi\",\"extraData\":\"\",\"requestType\":\"payWithMethod\",\"signature\":\"e864f553968394716b9dfa38a2f53c399cc64ab9bed46d92b935663e58d75ec9\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-20 01:17:08', '2025-11-20 01:17:08'),
(5, 20, 'MOMO', 'return', NULL, '4612700362', '0', 'Successful.', '{\"partnerCode\":\"MOMOBKUN20180529\",\"orderId\":\"20_1763626628\",\"requestId\":\"1763626628\",\"amount\":\"1800000\",\"orderInfo\":\"Thanh to\\u00e1n h\\u00f3a \\u0111\\u01a1n #20\",\"orderType\":\"momo_wallet\",\"transId\":\"4612700362\",\"resultCode\":\"0\",\"message\":\"Successful.\",\"payType\":\"napas\",\"responseTime\":\"1763626681409\",\"extraData\":null,\"signature\":\"7d6459ed292b05748ab89630baea9eb2bdb58ac4add3d9b966d0cbbec994a183\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-20 01:18:04', '2025-11-20 01:18:04'),
(6, 22, 'VNPAY', 'request', NULL, NULL, NULL, NULL, '{\"vnp_Version\":\"2.1.0\",\"vnp_TmnCode\":\"I2U8WK7F\",\"vnp_Amount\":180000000,\"vnp_Command\":\"pay\",\"vnp_CreateDate\":\"20251120090430\",\"vnp_CurrCode\":\"VND\",\"vnp_IpAddr\":\"127.0.0.1\",\"vnp_Locale\":\"vn\",\"vnp_OrderInfo\":\"Thanh toan hoa don #22\",\"vnp_OrderType\":\"billpayment\",\"vnp_ReturnUrl\":\"http:\\/\\/127.0.0.1:8000\\/payment\\/vnpay-return\",\"vnp_TxnRef\":\"22\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-20 02:04:30', '2025-11-20 02:04:30'),
(7, 22, 'VNPAY', 'return', NULL, 'VNP15272621', '00', NULL, '{\"vnp_Amount\":\"180000000\",\"vnp_BankCode\":\"NCB\",\"vnp_BankTranNo\":\"VNP15272621\",\"vnp_CardType\":\"ATM\",\"vnp_OrderInfo\":\"Thanh toan hoa don #22\",\"vnp_PayDate\":\"20251120160454\",\"vnp_ResponseCode\":\"00\",\"vnp_TmnCode\":\"I2U8WK7F\",\"vnp_TransactionNo\":\"15272621\",\"vnp_TransactionStatus\":\"00\",\"vnp_TxnRef\":\"22\",\"vnp_SecureHash\":\"128c084d517bad6051ac077c9c3b6375d24530dcc62d8b831d2b46eeeac7be5b3537296029180bf0968c19ea27219484b5184b5001869f427c269a27dbb00992\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-20 02:04:58', '2025-11-20 02:04:58'),
(8, 23, 'MOMO', 'request', NULL, NULL, NULL, NULL, '{\"partnerCode\":\"MOMOBKUN20180529\",\"partnerName\":\"Test\",\"storeId\":\"MomoTestStore\",\"requestId\":\"1763629719\",\"amount\":\"600000\",\"orderId\":\"23_1763629719\",\"orderInfo\":\"Thanh to\\u00e1n h\\u00f3a \\u0111\\u01a1n #23\",\"redirectUrl\":\"http:\\/\\/127.0.0.1:8000\\/payment\\/momo-return\",\"ipnUrl\":\"http:\\/\\/127.0.0.1:8000\\/payment\\/momo-ipn\",\"lang\":\"vi\",\"extraData\":\"\",\"requestType\":\"payWithMethod\",\"signature\":\"e9156364c060a51cc65459121ca3e572e1f598f3f8264af9040f170ecbaf3680\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-20 02:08:39', '2025-11-20 02:08:39'),
(9, 23, 'MOMO', 'return', NULL, '4612712221', '0', 'Successful.', '{\"partnerCode\":\"MOMOBKUN20180529\",\"orderId\":\"23_1763629719\",\"requestId\":\"1763629719\",\"amount\":\"600000\",\"orderInfo\":\"Thanh to\\u00e1n h\\u00f3a \\u0111\\u01a1n #23\",\"orderType\":\"momo_wallet\",\"transId\":\"4612712221\",\"resultCode\":\"0\",\"message\":\"Successful.\",\"payType\":\"napas\",\"responseTime\":\"1763629760084\",\"extraData\":null,\"signature\":\"b87d94511b6af6c3f189fe37a9c29cc744477cfc72cf1123a7553a5c7de26ace\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-20 02:09:23', '2025-11-20 02:09:23'),
(10, 24, 'MOMO', 'request', NULL, NULL, NULL, NULL, '{\"partnerCode\":\"MOMOBKUN20180529\",\"partnerName\":\"Test\",\"storeId\":\"MomoTestStore\",\"requestId\":\"1763630771\",\"amount\":\"60000\",\"orderId\":\"24_1763630771\",\"orderInfo\":\"Thanh to\\u00e1n h\\u00f3a \\u0111\\u01a1n #24\",\"redirectUrl\":\"http:\\/\\/127.0.0.1:8000\\/payment\\/momo-return\",\"ipnUrl\":\"http:\\/\\/127.0.0.1:8000\\/payment\\/momo-ipn\",\"lang\":\"vi\",\"extraData\":\"\",\"requestType\":\"payWithMethod\",\"signature\":\"89dbbfc52fd822ef9f5ced28c981acfa1723e0e8b76193500f7e8ce2a2170692\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-20 02:26:11', '2025-11-20 02:26:11'),
(11, 24, 'MOMO', 'return', NULL, '4612704244', '0', 'Successful.', '{\"partnerCode\":\"MOMOBKUN20180529\",\"orderId\":\"24_1763630771\",\"requestId\":\"1763630771\",\"amount\":\"60000\",\"orderInfo\":\"Thanh to\\u00e1n h\\u00f3a \\u0111\\u01a1n #24\",\"orderType\":\"momo_wallet\",\"transId\":\"4612704244\",\"resultCode\":\"0\",\"message\":\"Successful.\",\"payType\":\"napas\",\"responseTime\":\"1763630987567\",\"extraData\":null,\"signature\":\"cf3adfd7afb078aa9bc0106e5bd596ffa3c16d06db4c50af078b4a255658c3ae\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-20 02:29:50', '2025-11-20 02:29:50'),
(12, 25, 'MOMO', 'request', NULL, NULL, NULL, NULL, '{\"partnerCode\":\"MOMOBKUN20180529\",\"partnerName\":\"Test\",\"storeId\":\"MomoTestStore\",\"requestId\":\"1764312159\",\"amount\":\"600000\",\"orderId\":\"25_1764312159\",\"orderInfo\":\"Thanh to\\u00e1n h\\u00f3a \\u0111\\u01a1n #25\",\"redirectUrl\":\"http:\\/\\/127.0.0.1:8000\\/payment\\/momo-return\",\"ipnUrl\":\"http:\\/\\/127.0.0.1:8000\\/payment\\/momo-ipn\",\"lang\":\"vi\",\"extraData\":\"\",\"requestType\":\"payWithMethod\",\"signature\":\"6c2f42b19bba56a2bc82c3da20fee25b120dcf06b822971f3eec17b12fa666b5\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-28 06:42:39', '2025-11-28 06:42:39'),
(13, 25, 'MOMO', 'return', NULL, '4618476110', '0', 'Successful.', '{\"partnerCode\":\"MOMOBKUN20180529\",\"orderId\":\"25_1764312159\",\"requestId\":\"1764312159\",\"amount\":\"600000\",\"orderInfo\":\"Thanh to\\u00e1n h\\u00f3a \\u0111\\u01a1n #25\",\"orderType\":\"momo_wallet\",\"transId\":\"4618476110\",\"resultCode\":\"0\",\"message\":\"Successful.\",\"payType\":\"napas\",\"responseTime\":\"1764312228190\",\"extraData\":null,\"signature\":\"7670d709097577e21216639a8f67e96e818dd3f1054e35a4c6124ad10e7255c8\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-28 06:43:53', '2025-11-28 06:43:53'),
(14, 25, 'VNPAY', 'request', NULL, NULL, NULL, NULL, '{\"vnp_Version\":\"2.1.0\",\"vnp_TmnCode\":\"I2U8WK7F\",\"vnp_Amount\":60000000,\"vnp_Command\":\"pay\",\"vnp_CreateDate\":\"20251128134616\",\"vnp_CurrCode\":\"VND\",\"vnp_IpAddr\":\"127.0.0.1\",\"vnp_Locale\":\"vn\",\"vnp_OrderInfo\":\"Thanh toan hoa don #25\",\"vnp_OrderType\":\"billpayment\",\"vnp_ReturnUrl\":\"http:\\/\\/127.0.0.1:8000\\/payment\\/vnpay-return\",\"vnp_TxnRef\":\"25\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-28 06:46:16', '2025-11-28 06:46:16'),
(15, 25, 'VNPAY', 'return', NULL, 'VNP15308282', '00', NULL, '{\"vnp_Amount\":\"60000000\",\"vnp_BankCode\":\"NCB\",\"vnp_BankTranNo\":\"VNP15308282\",\"vnp_CardType\":\"ATM\",\"vnp_OrderInfo\":\"Thanh toan hoa don #25\",\"vnp_PayDate\":\"20251128134750\",\"vnp_ResponseCode\":\"00\",\"vnp_TmnCode\":\"I2U8WK7F\",\"vnp_TransactionNo\":\"15308282\",\"vnp_TransactionStatus\":\"00\",\"vnp_TxnRef\":\"25\",\"vnp_SecureHash\":\"442f882e0b20d9ce112de78fd97948eba019812494a8c8d84f5daef70544db88a516b2d31e58d3eb0ffb578ac9d1b8010c2ca9b2070295c57a1054ff726b0ec9\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-28 06:48:56', '2025-11-28 06:48:56'),
(16, 26, 'VNPAY', 'request', NULL, NULL, NULL, NULL, '{\"vnp_Version\":\"2.1.0\",\"vnp_TmnCode\":\"I2U8WK7F\",\"vnp_Amount\":60000000,\"vnp_Command\":\"pay\",\"vnp_CreateDate\":\"20251204141325\",\"vnp_CurrCode\":\"VND\",\"vnp_IpAddr\":\"127.0.0.1\",\"vnp_Locale\":\"vn\",\"vnp_OrderInfo\":\"Thanh toan hoa don #26\",\"vnp_OrderType\":\"billpayment\",\"vnp_ReturnUrl\":\"http:\\/\\/127.0.0.1:8000\\/payment\\/vnpay-return\",\"vnp_TxnRef\":\"26\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-04 07:13:25', '2025-12-04 07:13:25'),
(17, 26, 'VNPAY', 'return', NULL, 'VNP15319472', '00', NULL, '{\"vnp_Amount\":\"60000000\",\"vnp_BankCode\":\"NCB\",\"vnp_BankTranNo\":\"VNP15319472\",\"vnp_CardType\":\"ATM\",\"vnp_OrderInfo\":\"Thanh toan hoa don #26\",\"vnp_PayDate\":\"20251204141438\",\"vnp_ResponseCode\":\"00\",\"vnp_TmnCode\":\"I2U8WK7F\",\"vnp_TransactionNo\":\"15319472\",\"vnp_TransactionStatus\":\"00\",\"vnp_TxnRef\":\"26\",\"vnp_SecureHash\":\"19a9a2551a53fa168e8b1a9176fbcee7f3f1704368123402d5484587436b130e210413a8eb486b8a1e5a003c3a7f1092f2cf2133d8e820a8799283b2c8719ef6\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-04 07:14:45', '2025-12-04 07:14:45');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'view-users', 'web', '2025-11-20 22:22:36', '2025-11-20 22:22:36'),
(2, 'create-users', 'web', '2025-11-20 22:22:36', '2025-11-20 22:22:36'),
(3, 'edit-users', 'web', '2025-11-20 22:22:36', '2025-11-20 22:22:36'),
(4, 'delete-users', 'web', '2025-11-20 22:22:36', '2025-11-20 22:22:36'),
(5, 'lock-users', 'web', '2025-11-20 22:22:36', '2025-11-20 22:22:36'),
(6, 'unlock-users', 'web', '2025-11-20 22:22:36', '2025-11-20 22:22:36'),
(7, 'assign-roles', 'web', '2025-11-20 22:22:36', '2025-11-20 22:22:36'),
(8, 'view-roles', 'web', '2025-11-20 22:22:36', '2025-11-20 22:22:36'),
(9, 'create-roles', 'web', '2025-11-20 22:22:36', '2025-11-20 22:22:36'),
(10, 'edit-roles', 'web', '2025-11-20 22:22:36', '2025-11-20 22:22:36'),
(11, 'delete-roles', 'web', '2025-11-20 22:22:36', '2025-11-20 22:22:36'),
(12, 'view-permissions', 'web', '2025-11-20 22:22:36', '2025-11-20 22:22:36'),
(13, 'create-permissions', 'web', '2025-11-20 22:22:36', '2025-11-20 22:22:36'),
(14, 'delete-permissions', 'web', '2025-11-20 22:22:36', '2025-11-20 22:22:36'),
(15, 'view-doctors', 'web', '2025-11-20 22:22:36', '2025-11-20 22:22:36'),
(16, 'create-doctors', 'web', '2025-11-20 22:22:36', '2025-11-20 22:22:36'),
(17, 'edit-doctors', 'web', '2025-11-20 22:22:36', '2025-11-20 22:22:36'),
(18, 'delete-doctors', 'web', '2025-11-20 22:22:36', '2025-11-20 22:22:36'),
(19, 'manage-schedules', 'web', '2025-11-20 22:22:36', '2025-11-20 22:22:36'),
(20, 'view-appointments', 'web', '2025-11-20 22:22:36', '2025-11-20 22:22:36'),
(21, 'create-appointments', 'web', '2025-11-20 22:22:36', '2025-11-20 22:22:36'),
(22, 'edit-appointments', 'web', '2025-11-20 22:22:36', '2025-11-20 22:22:36'),
(23, 'cancel-appointments', 'web', '2025-11-20 22:22:36', '2025-11-20 22:22:36'),
(24, 'confirm-appointments', 'web', '2025-11-20 22:22:36', '2025-11-20 22:22:36'),
(25, 'view-medical-records', 'web', '2025-11-20 22:22:36', '2025-11-20 22:22:36'),
(26, 'create-medical-records', 'web', '2025-11-20 22:22:36', '2025-11-20 22:22:36'),
(27, 'edit-medical-records', 'web', '2025-11-20 22:22:36', '2025-11-20 22:22:36'),
(28, 'delete-medical-records', 'web', '2025-11-20 22:22:36', '2025-11-20 22:22:36'),
(29, 'view-services', 'web', '2025-11-20 22:22:36', '2025-11-20 22:22:36'),
(30, 'create-services', 'web', '2025-11-20 22:22:36', '2025-11-20 22:22:36'),
(31, 'edit-services', 'web', '2025-11-20 22:22:36', '2025-11-20 22:22:36'),
(32, 'delete-services', 'web', '2025-11-20 22:22:36', '2025-11-20 22:22:36'),
(33, 'view-medicines', 'web', '2025-11-20 22:22:36', '2025-11-20 22:22:36'),
(34, 'create-medicines', 'web', '2025-11-20 22:22:36', '2025-11-20 22:22:36'),
(35, 'edit-medicines', 'web', '2025-11-20 22:22:36', '2025-11-20 22:22:36'),
(36, 'delete-medicines', 'web', '2025-11-20 22:22:36', '2025-11-20 22:22:36'),
(37, 'manage-inventory', 'web', '2025-11-20 22:22:36', '2025-11-20 22:22:36'),
(38, 'view-inventory-reports', 'web', '2025-11-20 22:22:36', '2025-11-20 22:22:36'),
(39, 'view-staff', 'web', '2025-11-20 22:22:36', '2025-11-20 22:22:36'),
(40, 'create-staff', 'web', '2025-11-20 22:22:36', '2025-11-20 22:22:36'),
(41, 'edit-staff', 'web', '2025-11-20 22:22:36', '2025-11-20 22:22:36'),
(42, 'delete-staff', 'web', '2025-11-20 22:22:36', '2025-11-20 22:22:36'),
(43, 'view-staff-shifts', 'web', '2025-11-20 22:22:36', '2025-11-20 22:22:36'),
(44, 'assign-staff-shifts', 'web', '2025-11-20 22:22:36', '2025-11-20 22:22:36'),
(45, 'view-invoices', 'web', '2025-11-20 22:22:36', '2025-11-20 22:22:36'),
(46, 'create-invoices', 'web', '2025-11-20 22:22:36', '2025-11-20 22:22:36'),
(47, 'edit-invoices', 'web', '2025-11-20 22:22:36', '2025-11-20 22:22:36'),
(48, 'delete-invoices', 'web', '2025-11-20 22:22:36', '2025-11-20 22:22:36'),
(49, 'process-payments', 'web', '2025-11-20 22:22:36', '2025-11-20 22:22:36'),
(50, 'refund-payments', 'web', '2025-11-20 22:22:36', '2025-11-20 22:22:36'),
(51, 'view-payment-logs', 'web', '2025-11-20 22:22:36', '2025-11-20 22:22:36'),
(52, 'view-reports', 'web', '2025-11-20 22:22:36', '2025-11-20 22:22:36'),
(53, 'view-revenue-reports', 'web', '2025-11-20 22:22:36', '2025-11-20 22:22:36'),
(54, 'view-appointment-reports', 'web', '2025-11-20 22:22:36', '2025-11-20 22:22:36'),
(55, 'export-data', 'web', '2025-11-20 22:22:36', '2025-11-20 22:22:36'),
(56, 'view-dashboard', 'web', '2025-11-25 07:21:51', '2025-11-25 07:21:51'),
(57, 'view-posts', 'web', '2025-11-26 00:47:45', '2025-11-26 00:47:45');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `phieu_nhaps`
--

CREATE TABLE `phieu_nhaps` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `ma_phieu` varchar(255) NOT NULL,
  `ngay_nhap` date NOT NULL,
  `nha_cung_cap_id` bigint(20) UNSIGNED DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `tong_tien` decimal(14,2) NOT NULL DEFAULT 0.00,
  `ghi_chu` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `phieu_nhaps`
--

INSERT INTO `phieu_nhaps` (`id`, `ma_phieu`, `ngay_nhap`, `nha_cung_cap_id`, `user_id`, `tong_tien`, `ghi_chu`, `created_at`, `updated_at`) VALUES
(4, 'PN20251204174547', '2025-12-04', 4, 21, 600000.00, NULL, '2025-12-04 10:45:47', '2025-12-04 10:45:47'),
(5, 'PN20251204174710', '2025-12-04', 4, 21, 1000000.00, NULL, '2025-12-04 10:47:10', '2025-12-04 10:47:10'),
(6, 'PN20251204174740', '2025-12-04', 3, 21, 2250000.00, NULL, '2025-12-04 10:47:40', '2025-12-04 10:47:40');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `phieu_nhap_items`
--

CREATE TABLE `phieu_nhap_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `phieu_nhap_id` bigint(20) UNSIGNED NOT NULL,
  `thuoc_id` bigint(20) UNSIGNED NOT NULL,
  `ma_lo` varchar(255) DEFAULT NULL,
  `han_su_dung` date DEFAULT NULL,
  `so_luong` int(11) NOT NULL,
  `don_gia` decimal(12,2) NOT NULL,
  `thanh_tien` decimal(14,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `phieu_nhap_items`
--

INSERT INTO `phieu_nhap_items` (`id`, `phieu_nhap_id`, `thuoc_id`, `ma_lo`, `han_su_dung`, `so_luong`, `don_gia`, `thanh_tien`, `created_at`, `updated_at`) VALUES
(5, 4, 6, 'L001', '2027-07-15', 50, 12000.00, 600000.00, '2025-12-04 10:45:47', '2025-12-04 10:45:47'),
(6, 5, 7, 'L002', '2026-12-12', 100, 10000.00, 1000000.00, '2025-12-04 10:47:10', '2025-12-04 10:47:10'),
(7, 6, 5, 'L003', '2029-10-18', 150, 15000.00, 2250000.00, '2025-12-04 10:47:40', '2025-12-04 10:47:40');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `phieu_xuats`
--

CREATE TABLE `phieu_xuats` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `ma_phieu` varchar(255) NOT NULL,
  `ngay_xuat` date NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `doi_tuong` varchar(255) DEFAULT NULL,
  `tong_tien` decimal(14,2) NOT NULL DEFAULT 0.00,
  `ghi_chu` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `phieu_xuats`
--

INSERT INTO `phieu_xuats` (`id`, `ma_phieu`, `ngay_xuat`, `user_id`, `doi_tuong`, `tong_tien`, `ghi_chu`, `created_at`, `updated_at`) VALUES
(2, 'PX20251204174852', '2025-12-04', 21, 'Nguyễn Văn A', 400000.00, NULL, '2025-12-04 10:48:52', '2025-12-04 10:48:52'),
(3, 'PX20251204174921', '2025-12-04', 21, 'Nguyễn Văn B', 600000.00, NULL, '2025-12-04 10:49:21', '2025-12-04 10:49:21');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `phieu_xuat_items`
--

CREATE TABLE `phieu_xuat_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `phieu_xuat_id` bigint(20) UNSIGNED NOT NULL,
  `thuoc_id` bigint(20) UNSIGNED NOT NULL,
  `so_luong` int(11) NOT NULL,
  `don_gia` decimal(12,2) NOT NULL,
  `thanh_tien` decimal(14,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `phieu_xuat_items`
--

INSERT INTO `phieu_xuat_items` (`id`, `phieu_xuat_id`, `thuoc_id`, `so_luong`, `don_gia`, `thanh_tien`, `created_at`, `updated_at`) VALUES
(2, 2, 6, 20, 20000.00, 400000.00, '2025-12-04 10:48:52', '2025-12-04 10:48:52'),
(3, 3, 7, 30, 20000.00, 600000.00, '2025-12-04 10:49:21', '2025-12-04 10:49:21');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `phongs`
--

CREATE TABLE `phongs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `ten` varchar(255) NOT NULL,
  `loai` varchar(255) DEFAULT NULL,
  `mo_ta` text DEFAULT NULL,
  `trang_thai` enum('Sẵn sàng','Đang sử dụng','Bảo trì','Tạm ngưng') NOT NULL DEFAULT 'Sẵn sàng' COMMENT 'Trạng thái phòng',
  `vi_tri` varchar(255) DEFAULT NULL COMMENT 'Vị trí phòng (Tầng 1, Tầng 2, etc.)',
  `dien_tich` decimal(8,2) DEFAULT NULL COMMENT 'Diện tích phòng (m²)',
  `suc_chua` int(11) DEFAULT NULL COMMENT 'Sức chứa tối đa (số người)',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `phongs`
--

INSERT INTO `phongs` (`id`, `ten`, `loai`, `mo_ta`, `trang_thai`, `vi_tri`, `dien_tich`, `suc_chua`, `created_at`, `updated_at`) VALUES
(1, 'P.101 - Khám thai', 'phong_kham', 'Phòng khám tầng 1', 'Tạm ngưng', 'Tầng 1', 25.50, 5, '2025-11-17 00:24:48', '2025-12-04 11:48:52'),
(2, 'P.Xét nghiệm máu', 'phong_xet_nghiem', 'Khu cận lâm sàng', 'Đang sử dụng', NULL, NULL, NULL, '2025-11-17 00:25:48', '2025-12-04 11:38:41'),
(3, 'P.Siêu âm 4D', 'phong_chuc_nang', 'Máy siêu âm màu', 'Bảo trì', NULL, NULL, NULL, '2025-11-17 00:26:08', '2025-12-04 11:39:34'),
(4, 'Phòng Khám Tổng Quát 1', 'kham_benh', 'Phòng khám tổng quát, có đầy đủ trang thiết bị', 'Sẵn sàng', 'Tầng 1', 25.00, 5, '2025-12-04 11:34:20', '2025-12-04 11:34:20');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'super-admin', 'web', '2025-11-20 22:24:28', '2025-11-20 22:24:28'),
(2, 'admin', 'web', '2025-11-20 22:24:28', '2025-11-20 22:24:28'),
(3, 'manager', 'web', '2025-11-20 22:24:28', '2025-11-20 22:24:28'),
(4, 'doctor', 'web', '2025-11-20 22:24:28', '2025-11-20 22:24:28'),
(5, 'staff', 'web', '2025-11-20 22:24:28', '2025-11-20 22:24:28'),
(6, 'patient', 'web', '2025-11-20 22:24:28', '2025-11-20 22:24:28'),
(7, 'accountant', 'web', '2025-11-20 22:24:29', '2025-11-20 22:24:29'),
(8, 'pharmacist', 'web', '2025-11-20 22:24:29', '2025-11-20 22:24:29'),
(11, 'Le_Tan_Thu_Ngan', 'web', '2025-11-25 07:14:41', '2025-11-25 07:14:41');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `role_has_permissions`
--

INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
(1, 1),
(1, 2),
(1, 3),
(2, 1),
(2, 2),
(3, 1),
(3, 2),
(4, 1),
(5, 1),
(5, 2),
(6, 1),
(6, 2),
(7, 1),
(8, 1),
(9, 1),
(10, 1),
(11, 1),
(12, 1),
(13, 1),
(14, 1),
(15, 1),
(15, 2),
(15, 3),
(16, 1),
(16, 2),
(17, 1),
(17, 2),
(17, 3),
(18, 1),
(19, 1),
(19, 2),
(19, 3),
(20, 1),
(20, 2),
(20, 3),
(20, 4),
(20, 5),
(20, 6),
(21, 1),
(21, 2),
(21, 5),
(22, 1),
(22, 2),
(22, 3),
(22, 4),
(22, 5),
(23, 1),
(23, 2),
(23, 3),
(24, 1),
(24, 2),
(24, 3),
(24, 4),
(25, 1),
(25, 4),
(25, 5),
(26, 1),
(26, 4),
(27, 1),
(27, 4),
(28, 1),
(29, 1),
(29, 2),
(29, 3),
(29, 4),
(29, 5),
(29, 6),
(30, 1),
(30, 2),
(31, 1),
(31, 2),
(32, 1),
(33, 1),
(33, 4),
(33, 8),
(34, 1),
(34, 8),
(35, 1),
(35, 8),
(36, 1),
(37, 1),
(37, 8),
(38, 1),
(38, 8),
(39, 1),
(39, 2),
(39, 3),
(40, 1),
(40, 2),
(41, 1),
(41, 2),
(42, 1),
(43, 1),
(43, 2),
(43, 3),
(44, 1),
(44, 2),
(45, 1),
(45, 2),
(45, 3),
(45, 5),
(45, 7),
(45, 11),
(46, 1),
(46, 2),
(46, 7),
(47, 1),
(47, 2),
(47, 7),
(48, 1),
(49, 1),
(49, 2),
(49, 3),
(49, 7),
(50, 1),
(50, 7),
(51, 1),
(51, 2),
(51, 7),
(52, 1),
(52, 2),
(52, 3),
(52, 7),
(53, 1),
(53, 2),
(53, 3),
(53, 7),
(54, 1),
(54, 2),
(54, 3),
(55, 1),
(55, 2),
(55, 7),
(56, 1),
(56, 2),
(56, 11),
(57, 2);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tags`
--

CREATE TABLE `tags` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `tags`
--

INSERT INTO `tags` (`id`, `name`, `slug`, `created_at`, `updated_at`) VALUES
(1, 'Dinh dưỡng thai kỳ', 'dinh-duong-thai-ky', '2025-11-17 01:04:32', '2025-11-17 01:04:32'),
(2, 'Tiểu đường thai kỳ', 'tieu-duong-thai-ky', '2025-12-05 02:55:10', '2025-12-05 02:55:10'),
(3, 'Viêm âm đạo', 'viem-am-dao', '2025-12-05 02:55:15', '2025-12-05 02:55:15'),
(4, 'Khám thai định kỳ', 'kham-thai-dinh-ky', '2025-12-05 02:55:19', '2025-12-05 02:55:19'),
(5, 'Vô sinh hiếm muộn', 'vo-sinh-hiem-muon', '2025-12-05 02:55:28', '2025-12-05 02:55:28');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `thanh_toans`
--

CREATE TABLE `thanh_toans` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `hoa_don_id` bigint(20) UNSIGNED NOT NULL,
  `provider` varchar(255) NOT NULL,
  `so_tien` decimal(12,2) NOT NULL,
  `tien_te` varchar(255) NOT NULL DEFAULT 'VND',
  `trang_thai` varchar(255) NOT NULL DEFAULT 'pending',
  `transaction_ref` varchar(255) DEFAULT NULL,
  `idempotency_key` varchar(255) DEFAULT NULL,
  `paid_at` timestamp NULL DEFAULT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`payload`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `thanh_toans`
--

INSERT INTO `thanh_toans` (`id`, `hoa_don_id`, `provider`, `so_tien`, `tien_te`, `trang_thai`, `transaction_ref`, `idempotency_key`, `paid_at`, `payload`, `created_at`, `updated_at`) VALUES
(7, 8, 'VNPAY', 40000.00, 'VND', 'Thành công', 'VNP15242232', NULL, '2025-11-06 00:09:09', NULL, '2025-11-06 00:09:09', '2025-11-06 00:09:09'),
(8, 9, 'VNPAY', 50000.00, 'VND', 'Thành công', 'VNP15242247', NULL, '2025-11-06 00:12:03', NULL, '2025-11-06 00:12:03', '2025-11-06 00:12:03'),
(9, 7, 'cash', 30000.00, 'VND', 'succeeded', 'CASH-20251106071415', NULL, '2025-11-06 00:14:15', '{\"note\":null}', '2025-11-06 00:14:15', '2025-11-06 00:14:15'),
(10, 12, 'VNPAY', 25000.00, 'VND', 'Thành công', 'VNP15242290', NULL, '2025-11-06 00:24:02', NULL, '2025-11-06 00:24:02', '2025-11-06 00:24:02'),
(11, 14, 'MOMO', 100000.00, 'VND', 'Thành công', '4608585075', NULL, '2025-11-06 18:58:51', NULL, '2025-11-06 18:58:51', '2025-11-06 18:58:51'),
(15, 16, 'MOMO', 600000.00, 'VND', 'Thành công', '4608849923', NULL, '2025-11-07 01:40:31', NULL, '2025-11-07 01:40:31', '2025-11-07 01:40:31'),
(20, 18, 'MOMO', 60000.00, 'VND', 'Thành công', '4608964320', NULL, '2025-11-07 10:10:55', NULL, '2025-11-07 10:10:55', '2025-11-07 10:10:55'),
(21, 19, 'VNPAY', 60000.00, 'VND', 'Thành công', 'VNP15272341', '19_VNP15272341', '2025-11-20 00:54:57', NULL, '2025-11-20 00:54:57', '2025-11-20 00:54:57'),
(22, 20, 'MOMO', 1800000.00, 'VND', 'Thành công', '4612700362', '20_4612700362', '2025-11-20 01:18:04', NULL, '2025-11-20 01:18:04', '2025-11-20 01:18:04'),
(23, 22, 'VNPAY', 1800000.00, 'VND', 'Thành công', 'VNP15272621', '22_VNP15272621', '2025-11-20 02:04:58', NULL, '2025-11-20 02:04:58', '2025-11-20 02:04:58'),
(24, 23, 'MOMO', 600000.00, 'VND', 'Thành công', '4612712221', '23_4612712221', '2025-11-20 02:09:23', NULL, '2025-11-20 02:09:23', '2025-11-20 02:09:23'),
(25, 24, 'MOMO', 60000.00, 'VND', 'Thành công', '4612704244', '24_4612704244', '2025-11-20 02:29:50', NULL, '2025-11-20 02:29:50', '2025-11-20 02:29:50'),
(26, 25, 'cash', 600000.00, 'VND', 'succeeded', 'CASH-20251128134214', NULL, '2025-11-28 06:42:14', '{\"note\":null}', '2025-11-28 06:42:14', '2025-11-28 06:42:14'),
(27, 25, 'VNPAY', 600000.00, 'VND', 'Thành công', 'VNP15308282', '25_VNP15308282', '2025-11-28 06:48:56', NULL, '2025-11-28 06:48:56', '2025-11-28 06:48:56'),
(28, 26, 'VNPAY', 600000.00, 'VND', 'Thành công', 'VNP15319472', '26_VNP15319472', '2025-12-04 07:14:45', NULL, '2025-12-04 07:14:45', '2025-12-04 07:14:45'),
(29, 26, 'cash', 600000.00, 'VND', 'succeeded', 'CASH-20251204142548', NULL, '2025-12-04 07:25:48', '{\"note\":null}', '2025-12-04 07:25:48', '2025-12-04 07:25:48');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `thuocs`
--

CREATE TABLE `thuocs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `ten` varchar(255) NOT NULL,
  `hoat_chat` varchar(255) DEFAULT NULL,
  `ham_luong` varchar(255) DEFAULT NULL,
  `don_vi` varchar(255) NOT NULL DEFAULT 'viên',
  `gia_tham_khao` decimal(12,2) DEFAULT NULL,
  `ton_toi_thieu` int(11) DEFAULT NULL COMMENT 'Ngưỡng cảnh báo tồn kho thấp',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `thuocs`
--

INSERT INTO `thuocs` (`id`, `ten`, `hoat_chat`, `ham_luong`, `don_vi`, `gia_tham_khao`, `ton_toi_thieu`, `created_at`, `updated_at`) VALUES
(5, 'Paracetamol', 'Paracetamol', '500mg', 'Viên', 6000.00, NULL, '2025-12-04 10:16:58', '2025-12-04 10:17:20'),
(6, 'Amoxicillin', 'Amoxicillin', '500mg', 'Viên', 3500.00, NULL, '2025-12-04 10:22:26', '2025-12-04 10:24:35'),
(7, 'Duphaston', 'Dydrogesterone', '10mg', 'Viên', 18000.00, NULL, '2025-12-04 10:23:26', '2025-12-04 10:24:47');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `thuoc_khos`
--

CREATE TABLE `thuoc_khos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `thuoc_id` bigint(20) UNSIGNED NOT NULL,
  `ma_lo` varchar(255) DEFAULT NULL,
  `han_su_dung` date DEFAULT NULL,
  `so_luong` int(11) NOT NULL DEFAULT 0,
  `gia_nhap` decimal(12,2) NOT NULL DEFAULT 0.00,
  `gia_xuat` decimal(12,2) NOT NULL DEFAULT 0.00,
  `nha_cung_cap_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `thuoc_khos`
--

INSERT INTO `thuoc_khos` (`id`, `thuoc_id`, `ma_lo`, `han_su_dung`, `so_luong`, `gia_nhap`, `gia_xuat`, `nha_cung_cap_id`, `created_at`, `updated_at`) VALUES
(5, 6, 'L001', '2027-07-15', 30, 12000.00, 12000.00, 4, '2025-12-04 10:45:47', '2025-12-04 10:48:52'),
(6, 7, 'L002', '2026-12-12', 70, 10000.00, 10000.00, 4, '2025-12-04 10:47:10', '2025-12-04 10:49:21'),
(7, 5, 'L003', '2029-10-18', 150, 15000.00, 15000.00, 3, '2025-12-04 10:47:40', '2025-12-04 10:47:40');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `locked_at` timestamp NULL DEFAULT NULL,
  `locked_until` timestamp NULL DEFAULT NULL,
  `must_change_password` tinyint(1) NOT NULL DEFAULT 0,
  `last_login_at` timestamp NULL DEFAULT NULL,
  `login_attempts` int(11) NOT NULL DEFAULT 0,
  `last_login_ip` varchar(45) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `role` varchar(255) NOT NULL DEFAULT 'patient'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `locked_at`, `locked_until`, `must_change_password`, `last_login_at`, `login_attempts`, `last_login_ip`, `created_at`, `updated_at`, `role`) VALUES
(21, 'Admin', 'Admin@gmail.com', NULL, '$2y$10$WciFB/wsj9Qvjg8l6iUxsOIjGZm5UFJXjAivamXlwDBAlAZfSZdyC', NULL, NULL, NULL, 0, '2025-12-05 02:18:02', 0, '127.0.0.1', '2025-10-27 08:29:43', '2025-12-05 02:18:02', 'admin'),
(30, 'Võ Thị Diễm Hằng', 'vo-thi-diem-hang.10@gmail.com', NULL, '$2y$10$b/iOfFWVgth1b.AZxAd0JuEk1hrGJQ9ZHrRZnC1omUUyvcVX1jYKO', NULL, NULL, NULL, 0, '2025-12-04 06:49:40', 0, '127.0.0.1', '2025-11-04 02:37:58', '2025-12-04 06:49:40', 'doctor'),
(31, 'Bệnh nhân 1', 'Patient1@gmail.com', NULL, '$2y$10$alQbaYP.EqXhC3t07r9fEOC3r8N0NlI5e9QjMoE8rNPIAviWuPS26', NULL, NULL, NULL, 0, '2025-11-28 07:51:08', 0, '127.0.0.1', '2025-11-04 02:49:17', '2025-11-28 07:51:08', 'patient'),
(32, 'Nhân viên', 'Staff@gmail.com', NULL, '$2y$10$qNgMxPiExJCL96JW7X1oVOmBBiLVF6a8t.BWxtuqOsx9OIZNv7as6', NULL, NULL, NULL, 0, NULL, 0, NULL, '2025-11-04 02:53:09', '2025-11-04 02:53:09', 'staff'),
(33, 'Bệnh nhân 2', 'Patient2@gmail.com', NULL, '$2y$10$Uf82vR0N6c2ZFb6acO6ie.rJPGXPLYV.tvqm4zx7zEWRqcMMDuJ/C', NULL, NULL, NULL, 0, NULL, 0, NULL, '2025-11-04 03:17:19', '2025-11-04 03:17:19', 'patient'),
(34, 'Bác sĩ 2', 'bac-si-2.11@gmail.com', NULL, '$2y$10$j.fLKLmhCgmtZKmqLbZew.1R00lIl/KA9oGMNqjqYT5.cLbnu166W', NULL, NULL, NULL, 0, '2025-11-28 06:08:15', 0, '127.0.0.1', '2025-11-04 03:19:00', '2025-11-28 06:08:15', 'doctor'),
(35, 'Ngoãnh Chí Thiên', 'henvaemhen@gmail.com', NULL, '$2y$10$VV3v57c1/X0a9q3YwnF1euIdv3ffIQIde9w7REOuggdBQtzHkkDeO', NULL, NULL, NULL, 0, NULL, 0, NULL, '2025-11-06 21:13:33', '2025-11-06 21:13:33', 'patient'),
(36, 'Nguyễn Thích', 'tn822798@gmail.com', NULL, '$2y$10$TOH36mrBmRe.tZwE8XE4KOJVWhUvjSqxZXEN7KNtB7gTD71f.LHQu', NULL, NULL, NULL, 1, '2025-12-05 04:16:17', 0, '127.0.0.1', '2025-11-20 01:49:19', '2025-12-05 04:16:17', 'patient'),
(37, 'Nhân viên Võ', 'vothidiemhang2020qng@gmail.com', NULL, '$2y$10$wqIVbhQjPtjblosomwkoXubcp0gBAZWEsCBvh.p8Pstzlkz250vJC', 'ruuHtqQYiPJuqUojrAb5G4tTBPsQzbhrnqj9llwhKhaCSNEpUF1U8QYjDZjK', NULL, NULL, 1, '2025-11-28 08:06:12', 0, '127.0.0.1', '2025-11-20 20:41:40', '2025-11-28 08:06:12', 'staff'),
(38, 'Bệnh nhân 3', 'Patient3@gmail.com', NULL, '$2y$10$Tv8v7lIQi5uTtmIjRYY7.ehH.67ReLaZubDvBkAEQVAFVRw9CMXhe', NULL, NULL, NULL, 0, '2025-12-05 04:10:52', 0, '127.0.0.1', '2025-11-27 11:26:44', '2025-12-05 04:10:52', 'patient');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `xet_nghiems`
--

CREATE TABLE `xet_nghiems` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `benh_an_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `bac_si_id` bigint(20) UNSIGNED DEFAULT NULL,
  `loai` varchar(255) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `disk` varchar(50) NOT NULL DEFAULT 'public',
  `mo_ta` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `xet_nghiems`
--

INSERT INTO `xet_nghiems` (`id`, `benh_an_id`, `user_id`, `bac_si_id`, `loai`, `file_path`, `disk`, `mo_ta`, `created_at`, `updated_at`) VALUES
(1, 1, 31, 10, 'Nứng khẩn', 'xet_nghiem/w5Nj22m17LA0FehWAj0KCU4UVaxBupQRUSmGflST.jpg', 'public', 'Nhận gấp', '2025-11-12 22:37:00', '2025-11-12 22:37:00'),
(2, 1, 31, 10, 'Máu đông', 'xet_nghiem/qq6vVviC8Xs8MCVeRAom2CnytutoBG0uaNmox1P9.jpg', 'public', 'Thay máu', '2025-11-17 00:46:44', '2025-11-17 00:46:44'),
(3, 4, 35, 11, 'Nứng khẩn', 'xet_nghiem/pJeEX92npfsoeTCxOM2DJLe2kkkgGDuxzTnZWHKx.jpg', 'public', 'Nhận gấp', '2025-11-19 23:13:36', '2025-11-19 23:13:36'),
(4, 5, 35, 10, 'Nứng khẩn', 'xet_nghiem/9vxuAo8b31Gz97Gzv7JSbN2pzZ5CDJt7MV5f3JY1.jpg', 'benh_an_private', 'Nhận gấp', '2025-11-20 00:38:58', '2025-11-20 00:38:58');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `bac_sis`
--
ALTER TABLE `bac_sis`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bac_sis_user_id_foreign` (`user_id`);

--
-- Chỉ mục cho bảng `bac_si_chuyen_khoa`
--
ALTER TABLE `bac_si_chuyen_khoa`
  ADD PRIMARY KEY (`bac_si_id`,`chuyen_khoa_id`),
  ADD KEY `bac_si_chuyen_khoa_chuyen_khoa_id_foreign` (`chuyen_khoa_id`);

--
-- Chỉ mục cho bảng `bac_si_phong`
--
ALTER TABLE `bac_si_phong`
  ADD PRIMARY KEY (`bac_si_id`,`phong_id`),
  ADD KEY `bac_si_phong_phong_id_foreign` (`phong_id`);

--
-- Chỉ mục cho bảng `bai_viets`
--
ALTER TABLE `bai_viets`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `bai_viets_slug_unique` (`slug`),
  ADD KEY `bai_viets_user_id_foreign` (`user_id`),
  ADD KEY `bai_viets_danh_muc_id_foreign` (`danh_muc_id`),
  ADD KEY `bai_viets_status_published_at_index` (`status`,`published_at`);

--
-- Chỉ mục cho bảng `bai_viet_tag`
--
ALTER TABLE `bai_viet_tag`
  ADD PRIMARY KEY (`bai_viet_id`,`tag_id`),
  ADD KEY `bai_viet_tag_tag_id_foreign` (`tag_id`);

--
-- Chỉ mục cho bảng `benh_ans`
--
ALTER TABLE `benh_ans`
  ADD PRIMARY KEY (`id`),
  ADD KEY `benh_ans_bac_si_id_foreign` (`bac_si_id`),
  ADD KEY `benh_ans_lich_hen_id_foreign` (`lich_hen_id`),
  ADD KEY `benh_ans_user_id_bac_si_id_ngay_kham_index` (`user_id`,`bac_si_id`,`ngay_kham`);

--
-- Chỉ mục cho bảng `benh_an_audits`
--
ALTER TABLE `benh_an_audits`
  ADD PRIMARY KEY (`id`),
  ADD KEY `benh_an_audits_user_id_foreign` (`user_id`),
  ADD KEY `benh_an_audits_benh_an_id_created_at_index` (`benh_an_id`,`created_at`),
  ADD KEY `benh_an_audits_action_index` (`action`);

--
-- Chỉ mục cho bảng `benh_an_files`
--
ALTER TABLE `benh_an_files`
  ADD PRIMARY KEY (`id`),
  ADD KEY `benh_an_files_benh_an_id_foreign` (`benh_an_id`),
  ADD KEY `benh_an_files_uploaded_by_foreign` (`uploaded_by`);

--
-- Chỉ mục cho bảng `ca_dieu_chinh_bac_sis`
--
ALTER TABLE `ca_dieu_chinh_bac_sis`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ca_dieu_chinh_bac_sis_bac_si_id_ngay_index` (`bac_si_id`,`ngay`);

--
-- Chỉ mục cho bảng `ca_lam_viec_nhan_viens`
--
ALTER TABLE `ca_lam_viec_nhan_viens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ca_lam_viec_nhan_viens_nhan_vien_id_foreign` (`nhan_vien_id`);

--
-- Chỉ mục cho bảng `chuyen_khoas`
--
ALTER TABLE `chuyen_khoas`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `coupons`
--
ALTER TABLE `coupons`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `coupons_ma_giam_gia_unique` (`ma_giam_gia`),
  ADD KEY `coupons_ngay_bat_dau_ngay_ket_thuc_index` (`ngay_bat_dau`,`ngay_ket_thuc`),
  ADD KEY `coupons_kich_hoat_index` (`kich_hoat`);

--
-- Chỉ mục cho bảng `danh_mucs`
--
ALTER TABLE `danh_mucs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `danh_mucs_slug_unique` (`slug`);

--
-- Chỉ mục cho bảng `dich_vus`
--
ALTER TABLE `dich_vus`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `don_hangs`
--
ALTER TABLE `don_hangs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `don_hangs_ma_don_hang_unique` (`ma_don_hang`),
  ADD KEY `don_hangs_user_id_foreign` (`user_id`),
  ADD KEY `don_hangs_coupon_id_foreign` (`coupon_id`),
  ADD KEY `don_hangs_trang_thai_index` (`trang_thai`),
  ADD KEY `don_hangs_trang_thai_thanh_toan_index` (`trang_thai_thanh_toan`);

--
-- Chỉ mục cho bảng `don_hang_items`
--
ALTER TABLE `don_hang_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `don_hang_items_don_hang_id_foreign` (`don_hang_id`),
  ADD KEY `don_hang_items_thuoc_id_foreign` (`thuoc_id`);

--
-- Chỉ mục cho bảng `don_thuocs`
--
ALTER TABLE `don_thuocs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `don_thuocs_benh_an_id_foreign` (`benh_an_id`),
  ADD KEY `don_thuocs_user_id_foreign` (`user_id`),
  ADD KEY `don_thuocs_bac_si_id_foreign` (`bac_si_id`),
  ADD KEY `don_thuocs_lich_hen_id_foreign` (`lich_hen_id`);

--
-- Chỉ mục cho bảng `don_thuoc_items`
--
ALTER TABLE `don_thuoc_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `don_thuoc_items_don_thuoc_id_foreign` (`don_thuoc_id`),
  ADD KEY `don_thuoc_items_thuoc_id_foreign` (`thuoc_id`);

--
-- Chỉ mục cho bảng `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Chỉ mục cho bảng `hoan_tiens`
--
ALTER TABLE `hoan_tiens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hoan_tiens_hoa_don_id_foreign` (`hoa_don_id`);

--
-- Chỉ mục cho bảng `hoa_dons`
--
ALTER TABLE `hoa_dons`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `hoa_dons_lich_hen_id_unique` (`lich_hen_id`),
  ADD UNIQUE KEY `hoa_dons_ma_hoa_don_unique` (`ma_hoa_don`),
  ADD KEY `hoa_dons_user_id_trang_thai_index` (`user_id`,`trang_thai`),
  ADD KEY `hoa_dons_status_index` (`status`),
  ADD KEY `hoa_dons_ma_hoa_don_index` (`ma_hoa_don`);

--
-- Chỉ mục cho bảng `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Chỉ mục cho bảng `lich_hens`
--
ALTER TABLE `lich_hens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `lich_hens_unique_bacsi_ngay_gio` (`bac_si_id`,`ngay_hen`,`thoi_gian_hen`),
  ADD KEY `lich_hens_dich_vu_id_foreign` (`dich_vu_id`),
  ADD KEY `lich_hens_cancelled_by_foreign` (`cancelled_by`),
  ADD KEY `lich_hens_bacsi_ngay_idx` (`bac_si_id`,`ngay_hen`),
  ADD KEY `lich_hens_user_ngay_idx` (`user_id`,`ngay_hen`),
  ADD KEY `lich_hens_status_idx` (`trang_thai`),
  ADD KEY `lich_hens_ngay_gio_idx` (`ngay_hen`,`thoi_gian_hen`);

--
-- Chỉ mục cho bảng `lich_lam_viecs`
--
ALTER TABLE `lich_lam_viecs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lich_lam_viecs_bac_si_id_foreign` (`bac_si_id`),
  ADD KEY `lich_lam_viecs_phong_id_foreign` (`phong_id`);

--
-- Chỉ mục cho bảng `lich_nghis`
--
ALTER TABLE `lich_nghis`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lich_nghis_bac_si_id_ngay_index` (`bac_si_id`,`ngay`);

--
-- Chỉ mục cho bảng `login_audits`
--
ALTER TABLE `login_audits`
  ADD PRIMARY KEY (`id`),
  ADD KEY `login_audits_user_id_created_at_index` (`user_id`,`created_at`),
  ADD KEY `login_audits_ip_index` (`ip`),
  ADD KEY `login_audits_status_index` (`status`);

--
-- Chỉ mục cho bảng `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  ADD KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Chỉ mục cho bảng `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  ADD KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Chỉ mục cho bảng `nhan_viens`
--
ALTER TABLE `nhan_viens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nhan_viens_email_cong_viec_unique` (`email_cong_viec`),
  ADD UNIQUE KEY `nhan_viens_user_id_unique` (`user_id`);

--
-- Chỉ mục cho bảng `nhan_vien_audits`
--
ALTER TABLE `nhan_vien_audits`
  ADD PRIMARY KEY (`id`),
  ADD KEY `nhan_vien_audits_user_id_foreign` (`user_id`),
  ADD KEY `nhan_vien_audits_nhan_vien_id_created_at_index` (`nhan_vien_id`,`created_at`);

--
-- Chỉ mục cho bảng `nha_cung_caps`
--
ALTER TABLE `nha_cung_caps`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `nha_cung_cap_thuoc`
--
ALTER TABLE `nha_cung_cap_thuoc`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nha_cung_cap_thuoc_nha_cung_cap_id_thuoc_id_unique` (`nha_cung_cap_id`,`thuoc_id`),
  ADD KEY `nha_cung_cap_thuoc_thuoc_id_foreign` (`thuoc_id`);

--
-- Chỉ mục cho bảng `notification_preferences`
--
ALTER TABLE `notification_preferences`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `notification_preferences_user_id_unique` (`user_id`);

--
-- Chỉ mục cho bảng `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`email`);

--
-- Chỉ mục cho bảng `patient_profiles`
--
ALTER TABLE `patient_profiles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `patient_profiles_user_id_foreign` (`user_id`);

--
-- Chỉ mục cho bảng `payment_logs`
--
ALTER TABLE `payment_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `payment_logs_hoa_don_id_event_type_index` (`hoa_don_id`,`event_type`),
  ADD KEY `payment_logs_provider_transaction_ref_index` (`provider`,`transaction_ref`),
  ADD KEY `payment_logs_idempotency_key_index` (`idempotency_key`),
  ADD KEY `payment_logs_created_at_index` (`created_at`);

--
-- Chỉ mục cho bảng `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`);

--
-- Chỉ mục cho bảng `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Chỉ mục cho bảng `phieu_nhaps`
--
ALTER TABLE `phieu_nhaps`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `phieu_nhaps_ma_phieu_unique` (`ma_phieu`),
  ADD KEY `phieu_nhaps_user_id_foreign` (`user_id`),
  ADD KEY `phieu_nhaps_nha_cung_cap_id_foreign` (`nha_cung_cap_id`);

--
-- Chỉ mục cho bảng `phieu_nhap_items`
--
ALTER TABLE `phieu_nhap_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `phieu_nhap_items_phieu_nhap_id_foreign` (`phieu_nhap_id`),
  ADD KEY `phieu_nhap_items_thuoc_id_foreign` (`thuoc_id`);

--
-- Chỉ mục cho bảng `phieu_xuats`
--
ALTER TABLE `phieu_xuats`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `phieu_xuats_ma_phieu_unique` (`ma_phieu`),
  ADD KEY `phieu_xuats_user_id_foreign` (`user_id`);

--
-- Chỉ mục cho bảng `phieu_xuat_items`
--
ALTER TABLE `phieu_xuat_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `phieu_xuat_items_phieu_xuat_id_foreign` (`phieu_xuat_id`),
  ADD KEY `phieu_xuat_items_thuoc_id_foreign` (`thuoc_id`);

--
-- Chỉ mục cho bảng `phongs`
--
ALTER TABLE `phongs`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`);

--
-- Chỉ mục cho bảng `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `role_has_permissions_role_id_foreign` (`role_id`);

--
-- Chỉ mục cho bảng `tags`
--
ALTER TABLE `tags`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tags_slug_unique` (`slug`);

--
-- Chỉ mục cho bảng `thanh_toans`
--
ALTER TABLE `thanh_toans`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `thanh_toans_idempotency_key_unique` (`idempotency_key`),
  ADD KEY `thanh_toans_provider_transaction_ref_index` (`provider`,`transaction_ref`),
  ADD KEY `thanh_toans_hoa_don_id_trang_thai_index` (`hoa_don_id`,`trang_thai`);

--
-- Chỉ mục cho bảng `thuocs`
--
ALTER TABLE `thuocs`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `thuoc_khos`
--
ALTER TABLE `thuoc_khos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `thuoc_khos_thuoc_id_foreign` (`thuoc_id`),
  ADD KEY `thuoc_khos_nha_cung_cap_id_foreign` (`nha_cung_cap_id`);

--
-- Chỉ mục cho bảng `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `users_locked_at_index` (`locked_at`),
  ADD KEY `users_last_login_at_index` (`last_login_at`);

--
-- Chỉ mục cho bảng `xet_nghiems`
--
ALTER TABLE `xet_nghiems`
  ADD PRIMARY KEY (`id`),
  ADD KEY `xet_nghiems_benh_an_id_foreign` (`benh_an_id`),
  ADD KEY `xet_nghiems_user_id_foreign` (`user_id`),
  ADD KEY `xet_nghiems_bac_si_id_foreign` (`bac_si_id`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `bac_sis`
--
ALTER TABLE `bac_sis`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT cho bảng `bai_viets`
--
ALTER TABLE `bai_viets`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `benh_ans`
--
ALTER TABLE `benh_ans`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT cho bảng `benh_an_audits`
--
ALTER TABLE `benh_an_audits`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT cho bảng `benh_an_files`
--
ALTER TABLE `benh_an_files`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT cho bảng `ca_dieu_chinh_bac_sis`
--
ALTER TABLE `ca_dieu_chinh_bac_sis`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `ca_lam_viec_nhan_viens`
--
ALTER TABLE `ca_lam_viec_nhan_viens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT cho bảng `chuyen_khoas`
--
ALTER TABLE `chuyen_khoas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `coupons`
--
ALTER TABLE `coupons`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `danh_mucs`
--
ALTER TABLE `danh_mucs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `dich_vus`
--
ALTER TABLE `dich_vus`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT cho bảng `don_hangs`
--
ALTER TABLE `don_hangs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `don_hang_items`
--
ALTER TABLE `don_hang_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `don_thuocs`
--
ALTER TABLE `don_thuocs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT cho bảng `don_thuoc_items`
--
ALTER TABLE `don_thuoc_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT cho bảng `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `hoan_tiens`
--
ALTER TABLE `hoan_tiens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `hoa_dons`
--
ALTER TABLE `hoa_dons`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT cho bảng `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT cho bảng `lich_hens`
--
ALTER TABLE `lich_hens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT cho bảng `lich_lam_viecs`
--
ALTER TABLE `lich_lam_viecs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT cho bảng `lich_nghis`
--
ALTER TABLE `lich_nghis`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `login_audits`
--
ALTER TABLE `login_audits`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=82;

--
-- AUTO_INCREMENT cho bảng `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=74;

--
-- AUTO_INCREMENT cho bảng `nhan_viens`
--
ALTER TABLE `nhan_viens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT cho bảng `nhan_vien_audits`
--
ALTER TABLE `nhan_vien_audits`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT cho bảng `nha_cung_caps`
--
ALTER TABLE `nha_cung_caps`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `nha_cung_cap_thuoc`
--
ALTER TABLE `nha_cung_cap_thuoc`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `notification_preferences`
--
ALTER TABLE `notification_preferences`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `patient_profiles`
--
ALTER TABLE `patient_profiles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `payment_logs`
--
ALTER TABLE `payment_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT cho bảng `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT cho bảng `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `phieu_nhaps`
--
ALTER TABLE `phieu_nhaps`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT cho bảng `phieu_nhap_items`
--
ALTER TABLE `phieu_nhap_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT cho bảng `phieu_xuats`
--
ALTER TABLE `phieu_xuats`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `phieu_xuat_items`
--
ALTER TABLE `phieu_xuat_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `phongs`
--
ALTER TABLE `phongs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT cho bảng `tags`
--
ALTER TABLE `tags`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT cho bảng `thanh_toans`
--
ALTER TABLE `thanh_toans`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT cho bảng `thuocs`
--
ALTER TABLE `thuocs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT cho bảng `thuoc_khos`
--
ALTER TABLE `thuoc_khos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT cho bảng `xet_nghiems`
--
ALTER TABLE `xet_nghiems`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `bac_sis`
--
ALTER TABLE `bac_sis`
  ADD CONSTRAINT `bac_sis_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Các ràng buộc cho bảng `bac_si_chuyen_khoa`
--
ALTER TABLE `bac_si_chuyen_khoa`
  ADD CONSTRAINT `bac_si_chuyen_khoa_bac_si_id_foreign` FOREIGN KEY (`bac_si_id`) REFERENCES `bac_sis` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bac_si_chuyen_khoa_chuyen_khoa_id_foreign` FOREIGN KEY (`chuyen_khoa_id`) REFERENCES `chuyen_khoas` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `bac_si_phong`
--
ALTER TABLE `bac_si_phong`
  ADD CONSTRAINT `bac_si_phong_bac_si_id_foreign` FOREIGN KEY (`bac_si_id`) REFERENCES `bac_sis` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bac_si_phong_phong_id_foreign` FOREIGN KEY (`phong_id`) REFERENCES `phongs` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `bai_viets`
--
ALTER TABLE `bai_viets`
  ADD CONSTRAINT `bai_viets_danh_muc_id_foreign` FOREIGN KEY (`danh_muc_id`) REFERENCES `danh_mucs` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `bai_viets_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `bai_viet_tag`
--
ALTER TABLE `bai_viet_tag`
  ADD CONSTRAINT `bai_viet_tag_bai_viet_id_foreign` FOREIGN KEY (`bai_viet_id`) REFERENCES `bai_viets` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bai_viet_tag_tag_id_foreign` FOREIGN KEY (`tag_id`) REFERENCES `tags` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `benh_ans`
--
ALTER TABLE `benh_ans`
  ADD CONSTRAINT `benh_ans_bac_si_id_foreign` FOREIGN KEY (`bac_si_id`) REFERENCES `bac_sis` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `benh_ans_lich_hen_id_foreign` FOREIGN KEY (`lich_hen_id`) REFERENCES `lich_hens` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `benh_ans_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `benh_an_audits`
--
ALTER TABLE `benh_an_audits`
  ADD CONSTRAINT `benh_an_audits_benh_an_id_foreign` FOREIGN KEY (`benh_an_id`) REFERENCES `benh_ans` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `benh_an_audits_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Các ràng buộc cho bảng `benh_an_files`
--
ALTER TABLE `benh_an_files`
  ADD CONSTRAINT `benh_an_files_benh_an_id_foreign` FOREIGN KEY (`benh_an_id`) REFERENCES `benh_ans` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `benh_an_files_uploaded_by_foreign` FOREIGN KEY (`uploaded_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Các ràng buộc cho bảng `ca_dieu_chinh_bac_sis`
--
ALTER TABLE `ca_dieu_chinh_bac_sis`
  ADD CONSTRAINT `ca_dieu_chinh_bac_sis_bac_si_id_foreign` FOREIGN KEY (`bac_si_id`) REFERENCES `bac_sis` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `ca_lam_viec_nhan_viens`
--
ALTER TABLE `ca_lam_viec_nhan_viens`
  ADD CONSTRAINT `ca_lam_viec_nhan_viens_nhan_vien_id_foreign` FOREIGN KEY (`nhan_vien_id`) REFERENCES `nhan_viens` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `don_hangs`
--
ALTER TABLE `don_hangs`
  ADD CONSTRAINT `don_hangs_coupon_id_foreign` FOREIGN KEY (`coupon_id`) REFERENCES `coupons` (`id`),
  ADD CONSTRAINT `don_hangs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Các ràng buộc cho bảng `don_hang_items`
--
ALTER TABLE `don_hang_items`
  ADD CONSTRAINT `don_hang_items_don_hang_id_foreign` FOREIGN KEY (`don_hang_id`) REFERENCES `don_hangs` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `don_hang_items_thuoc_id_foreign` FOREIGN KEY (`thuoc_id`) REFERENCES `thuocs` (`id`);

--
-- Các ràng buộc cho bảng `don_thuocs`
--
ALTER TABLE `don_thuocs`
  ADD CONSTRAINT `don_thuocs_bac_si_id_foreign` FOREIGN KEY (`bac_si_id`) REFERENCES `bac_sis` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `don_thuocs_benh_an_id_foreign` FOREIGN KEY (`benh_an_id`) REFERENCES `benh_ans` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `don_thuocs_lich_hen_id_foreign` FOREIGN KEY (`lich_hen_id`) REFERENCES `lich_hens` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `don_thuocs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Các ràng buộc cho bảng `don_thuoc_items`
--
ALTER TABLE `don_thuoc_items`
  ADD CONSTRAINT `don_thuoc_items_don_thuoc_id_foreign` FOREIGN KEY (`don_thuoc_id`) REFERENCES `don_thuocs` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `don_thuoc_items_thuoc_id_foreign` FOREIGN KEY (`thuoc_id`) REFERENCES `thuocs` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `hoan_tiens`
--
ALTER TABLE `hoan_tiens`
  ADD CONSTRAINT `hoan_tiens_hoa_don_id_foreign` FOREIGN KEY (`hoa_don_id`) REFERENCES `hoa_dons` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `hoa_dons`
--
ALTER TABLE `hoa_dons`
  ADD CONSTRAINT `hoa_dons_lich_hen_id_foreign` FOREIGN KEY (`lich_hen_id`) REFERENCES `lich_hens` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `hoa_dons_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `lich_hens`
--
ALTER TABLE `lich_hens`
  ADD CONSTRAINT `lich_hens_bac_si_id_foreign` FOREIGN KEY (`bac_si_id`) REFERENCES `bac_sis` (`id`),
  ADD CONSTRAINT `lich_hens_cancelled_by_foreign` FOREIGN KEY (`cancelled_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `lich_hens_dich_vu_id_foreign` FOREIGN KEY (`dich_vu_id`) REFERENCES `dich_vus` (`id`),
  ADD CONSTRAINT `lich_hens_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Các ràng buộc cho bảng `lich_lam_viecs`
--
ALTER TABLE `lich_lam_viecs`
  ADD CONSTRAINT `lich_lam_viecs_bac_si_id_foreign` FOREIGN KEY (`bac_si_id`) REFERENCES `bac_sis` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `lich_lam_viecs_phong_id_foreign` FOREIGN KEY (`phong_id`) REFERENCES `phongs` (`id`) ON DELETE SET NULL;

--
-- Các ràng buộc cho bảng `lich_nghis`
--
ALTER TABLE `lich_nghis`
  ADD CONSTRAINT `lich_nghis_bac_si_id_foreign` FOREIGN KEY (`bac_si_id`) REFERENCES `bac_sis` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `login_audits`
--
ALTER TABLE `login_audits`
  ADD CONSTRAINT `login_audits_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Các ràng buộc cho bảng `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `nhan_viens`
--
ALTER TABLE `nhan_viens`
  ADD CONSTRAINT `nhan_viens_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Các ràng buộc cho bảng `nhan_vien_audits`
--
ALTER TABLE `nhan_vien_audits`
  ADD CONSTRAINT `nhan_vien_audits_nhan_vien_id_foreign` FOREIGN KEY (`nhan_vien_id`) REFERENCES `nhan_viens` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `nhan_vien_audits_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Các ràng buộc cho bảng `nha_cung_cap_thuoc`
--
ALTER TABLE `nha_cung_cap_thuoc`
  ADD CONSTRAINT `nha_cung_cap_thuoc_nha_cung_cap_id_foreign` FOREIGN KEY (`nha_cung_cap_id`) REFERENCES `nha_cung_caps` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `nha_cung_cap_thuoc_thuoc_id_foreign` FOREIGN KEY (`thuoc_id`) REFERENCES `thuocs` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `notification_preferences`
--
ALTER TABLE `notification_preferences`
  ADD CONSTRAINT `notification_preferences_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `patient_profiles`
--
ALTER TABLE `patient_profiles`
  ADD CONSTRAINT `patient_profiles_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `payment_logs`
--
ALTER TABLE `payment_logs`
  ADD CONSTRAINT `payment_logs_hoa_don_id_foreign` FOREIGN KEY (`hoa_don_id`) REFERENCES `hoa_dons` (`id`) ON DELETE SET NULL;

--
-- Các ràng buộc cho bảng `phieu_nhaps`
--
ALTER TABLE `phieu_nhaps`
  ADD CONSTRAINT `phieu_nhaps_nha_cung_cap_id_foreign` FOREIGN KEY (`nha_cung_cap_id`) REFERENCES `nha_cung_caps` (`id`),
  ADD CONSTRAINT `phieu_nhaps_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Các ràng buộc cho bảng `phieu_nhap_items`
--
ALTER TABLE `phieu_nhap_items`
  ADD CONSTRAINT `phieu_nhap_items_phieu_nhap_id_foreign` FOREIGN KEY (`phieu_nhap_id`) REFERENCES `phieu_nhaps` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `phieu_nhap_items_thuoc_id_foreign` FOREIGN KEY (`thuoc_id`) REFERENCES `thuocs` (`id`);

--
-- Các ràng buộc cho bảng `phieu_xuats`
--
ALTER TABLE `phieu_xuats`
  ADD CONSTRAINT `phieu_xuats_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Các ràng buộc cho bảng `phieu_xuat_items`
--
ALTER TABLE `phieu_xuat_items`
  ADD CONSTRAINT `phieu_xuat_items_phieu_xuat_id_foreign` FOREIGN KEY (`phieu_xuat_id`) REFERENCES `phieu_xuats` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `phieu_xuat_items_thuoc_id_foreign` FOREIGN KEY (`thuoc_id`) REFERENCES `thuocs` (`id`);

--
-- Các ràng buộc cho bảng `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `thanh_toans`
--
ALTER TABLE `thanh_toans`
  ADD CONSTRAINT `thanh_toans_hoa_don_id_foreign` FOREIGN KEY (`hoa_don_id`) REFERENCES `hoa_dons` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `thuoc_khos`
--
ALTER TABLE `thuoc_khos`
  ADD CONSTRAINT `thuoc_khos_nha_cung_cap_id_foreign` FOREIGN KEY (`nha_cung_cap_id`) REFERENCES `nha_cung_caps` (`id`),
  ADD CONSTRAINT `thuoc_khos_thuoc_id_foreign` FOREIGN KEY (`thuoc_id`) REFERENCES `thuocs` (`id`);

--
-- Các ràng buộc cho bảng `xet_nghiems`
--
ALTER TABLE `xet_nghiems`
  ADD CONSTRAINT `xet_nghiems_bac_si_id_foreign` FOREIGN KEY (`bac_si_id`) REFERENCES `bac_sis` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `xet_nghiems_benh_an_id_foreign` FOREIGN KEY (`benh_an_id`) REFERENCES `benh_ans` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `xet_nghiems_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
