-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th12 20, 2025 lúc 05:18 AM
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
-- Cấu trúc bảng cho bảng `activity_log`
--

CREATE TABLE `activity_log` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `log_name` varchar(255) DEFAULT NULL,
  `description` text NOT NULL,
  `subject_type` varchar(255) DEFAULT NULL,
  `event` varchar(255) DEFAULT NULL,
  `subject_id` bigint(20) UNSIGNED DEFAULT NULL,
  `causer_type` varchar(255) DEFAULT NULL,
  `causer_id` bigint(20) UNSIGNED DEFAULT NULL,
  `properties` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`properties`)),
  `batch_uuid` char(36) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `activity_log`
--

INSERT INTO `activity_log` (`id`, `log_name`, `description`, `subject_type`, `event`, `subject_id`, `causer_type`, `causer_id`, `properties`, `batch_uuid`, `created_at`, `updated_at`) VALUES
(1, 'default', 'Nhân viên check-in bệnh nhân', 'App\\Models\\LichHen', NULL, 3, 'App\\Models\\User', 149, '{\"old_status\":\"\\u0110\\u00e3 x\\u00e1c nh\\u1eadn\",\"new_status\":\"\\u0110\\u00e3 check-in\"}', NULL, '2025-12-13 07:38:09', '2025-12-13 07:38:09'),
(2, 'default', 'Nhân viên gọi bệnh nhân vào khám', 'App\\Models\\LichHen', NULL, 3, 'App\\Models\\User', 149, '{\"action\":\"call_next\"}', NULL, '2025-12-13 07:40:21', '2025-12-13 07:40:21'),
(3, 'default', 'Nhân viên check-in bệnh nhân', 'App\\Models\\LichHen', NULL, 8, 'App\\Models\\User', 149, '[]', NULL, '2025-12-14 08:28:08', '2025-12-14 08:28:08'),
(4, 'default', 'Nhân viên gọi bệnh nhân vào khám', 'App\\Models\\LichHen', NULL, 8, 'App\\Models\\User', 149, '{\"action\":\"call_next\"}', NULL, '2025-12-14 08:28:12', '2025-12-14 08:28:12'),
(5, 'default', 'Nhân viên check-in bệnh nhân', 'App\\Models\\LichHen', NULL, 1, 'App\\Models\\User', 25, '[]', NULL, '2025-12-19 17:48:15', '2025-12-19 17:48:15');

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
(1, 1, 'TS.BS Nguyễn Thị Lan Anh', 'lananh@vietcare.com', 'Sản Khoa', 25, 'Nguyên Trưởng khoa Sản bệnh viện Từ Dũ. Chuyên gia hàng đầu về quản lý thai kỳ nguy cơ cao (tiền sản giật, đái tháo đường thai kỳ) và đỡ sinh khó.', 'Đang hoạt động', '0909111001', NULL, 'Quận 3, TP.HCM', '2025-12-19 16:00:44', '2025-12-19 16:00:44'),
(2, 2, 'ThS.BS Phạm Văn Hùng', 'hunghoang@vietcare.com', 'Sản Khoa', 12, 'Thạc sĩ Y khoa chuyên ngành Sản phụ khoa, từng tu nghiệp tại Pháp. Nổi tiếng \"mát tay\" trong đỡ sinh thường, may thẩm mỹ tầng sinh môn và phẫu thuật lấy thai.', 'Đang hoạt động', '0909111002', NULL, 'Quận 7, TP.HCM', '2025-12-19 16:00:44', '2025-12-19 16:00:44'),
(3, 3, 'BSCKII Trần Thu Hà', 'hatran@vietcare.com', 'Phụ Khoa', 18, 'Chuyên gia phẫu thuật nội soi phụ khoa (bóc u xơ tử cung, u nang buồng trứng). Điều trị chuyên sâu các bệnh lý sàn chậu, sa tử cung và són tiểu ở phụ nữ.', 'Đang hoạt động', '0909111003', NULL, 'Quận 10, TP.HCM', '2025-12-19 16:00:44', '2025-12-19 16:00:44'),
(4, 4, 'BS.CKI Nguyễn Thanh Vân', 'vannguyen@vietcare.com', 'Phụ Khoa', 10, 'Chuyên sâu về soi cổ tử cung, điều trị lộ tuyến và các bệnh viêm nhiễm phụ khoa tái phát. Tư vấn sức khỏe tiền mãn kinh.', 'Đang hoạt động', '0909111004', NULL, 'Quận Tân Bình, TP.HCM', '2025-12-19 16:00:44', '2025-12-19 16:00:44'),
(5, 5, 'TS.BS Hoàng Minh Tuấn', 'tuanhoang@vietcare.com', 'Hiếm muộn & Vô sinh', 20, 'Nguyên Phó Giám đốc Trung tâm Hỗ trợ sinh sản Quốc gia. \"Bàn tay vàng\" điều trị vô sinh nam và thực hiện kỹ thuật IVF/ICSI với tỷ lệ thành công cao.', 'Đang hoạt động', '0909111005', NULL, 'TP. Thủ Đức, TP.HCM', '2025-12-19 16:00:44', '2025-12-19 16:00:44'),
(6, 6, 'ThS.BS Võ Thị Ngọc', 'ngocvo@vietcare.com', 'Hiếm muộn & Vô sinh', 15, 'Chuyên gia về nội tiết sinh sản. Rất giỏi trong việc kích trứng, canh niêm mạc và điều trị hội chứng buồng trứng đa nang (PCOS) cho các cặp đôi mong con.', 'Đang hoạt động', '0909111006', NULL, 'Quận 5, TP.HCM', '2025-12-19 16:00:44', '2025-12-19 16:00:44'),
(7, 7, 'BS.CKI Phạm Thanh Thúy', 'thuypham@vietcare.com', 'Siêu âm & Chẩn đoán hình ảnh', 10, 'Có chứng chỉ FMF Quốc tế (London). Chuyên siêu âm 4D/5D tầm soát dị tật thai nhi sớm và siêu âm Doppler tim thai, mạch máu.', 'Đang hoạt động', '0909111007', NULL, 'Quận 1, TP.HCM', '2025-12-19 16:00:44', '2025-12-19 16:00:44'),
(8, 8, 'ThS.BS Nguyễn Hữu Phước', 'phuocnguyen@vietcare.com', 'Sàng lọc trước sinh', 9, 'Chuyên gia Di truyền học. Tư vấn chuyên sâu về các kết quả sàng lọc NIPT, Double Test, Triple Test và chọc ối chẩn đoán bất thường nhiễm sắc thể.', 'Đang hoạt động', '0909111008', NULL, 'Quận Bình Thạnh, TP.HCM', '2025-12-19 16:00:44', '2025-12-19 16:00:44'),
(9, 9, 'BS.CKI Đỗ Mỹ Linh', 'linhdo@vietcare.com', 'Kế hoạch hóa gia đình', 12, 'Chuyên thực hiện các thủ thuật tránh thai hiện đại: Cấy que Implanon, đặt vòng nội tiết Mirena. Thao tác nhẹ nhàng, không đau, tư vấn tận tình.', 'Đang hoạt động', '0909111009', NULL, 'Quận Phú Nhuận, TP.HCM', '2025-12-19 16:00:44', '2025-12-19 16:00:44'),
(10, 10, 'ThS.BS Lê Thị Mai', 'maile@vietcare.com', 'Sản Khoa', 8, 'Bác sĩ trẻ, nhiệt huyết, cập nhật liên tục các phương pháp thai giáo và sinh nở hiện đại (da kề da, kẹp rốn chậm). Được nhiều mẹ bầu trẻ tin tưởng.', 'Đang hoạt động', '0909111010', NULL, 'Quận 4, TP.HCM', '2025-12-19 16:00:44', '2025-12-19 16:00:44'),
(11, 11, 'BS.CKI Trần Văn Minh', 'minh.xetnghiem@vietcare.com', 'Xét nghiệm', 15, 'Trưởng khoa Xét nghiệm. Chuyên gia về Huyết học và Vi sinh. Đảm bảo quy trình xét nghiệm đạt chuẩn ISO 15189, kết quả chính xác và nhanh chóng.', 'Đang hoạt động', '0909111011', NULL, 'Quận 8, TP.HCM', '2025-12-19 16:00:44', '2025-12-19 16:00:44'),
(12, 12, 'ThS.BS Nguyễn Ngọc Lan', 'lan.thammy@vietcare.com', 'Sàn chậu & Thẩm mỹ nữ', 10, 'Chuyên gia phục hồi sàn chậu sau sinh và thẩm mỹ vùng kín. Rất mát tay trong các thủ thuật làm hồng, se khít và điều trị són tiểu không phẫu thuật.', 'Đang hoạt động', '0909111012', NULL, 'Quận 2, TP.HCM', '2025-12-19 16:00:44', '2025-12-19 16:00:44');

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
(1, 1),
(2, 1),
(3, 2),
(4, 2),
(5, 3),
(6, 3),
(7, 4),
(8, 5),
(9, 6),
(10, 1),
(11, 8),
(12, 7);

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
(1, 1),
(2, 2),
(3, 4),
(5, 7),
(6, 8),
(7, 3),
(8, 10),
(9, 5),
(11, 9),
(12, 6);

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
(1, 1, 1, 'Chế độ dinh dưỡng vàng cho bà bầu 3 tháng đầu: Ăn đúng để con khỏe, mẹ không tăng cân', 'che-do-dinh-duong-vang-cho-ba-bau-3-thang-dau-an-dung-de-co', '3 tháng đầu là giai đoạn quan trọng nhất để hình thành các cơ quan của thai nhi. Cùng tìm hiểu thực đơn chuẩn giúp mẹ khỏe, bé phát triển toàn diện và giảm nghén hiệu quả.', 'Mang thai 3 tháng đầu (tam cá nguyệt thứ nhất) là giai đoạn quan trọng nhất để hình thành các cơ quan thiết yếu của thai nhi như tim, não và tủy sống. Tuy nhiên, đây cũng là giai đoạn mẹ bầu dễ bị nghén nhất. Vậy làm sao để ăn uống đủ chất mà vẫn thoải mái?\n\n1. Axit Folic – \"Thần dược\" ngăn ngừa dị tật\n\nNếu có một chất dinh dưỡng bắt buộc phải bổ sung ngay khi biết tin có thai, đó chính là Axit Folic (Vitamin B9). Dưỡng chất này đóng vai trò then chốt trong việc ngăn ngừa các dị tật ống thần kinh ở thai nhi (nứt đốt sống, vô sọ).\n\nNhu cầu khuyến nghị: 400mcg - 600mcg/ngày.\n\nThực phẩm giàu Folate: Các loại rau màu xanh đậm (súp lơ, cải bó xôi), các loại đậu, ngũ cốc nguyên hạt và trái cây họ cam quýt.\n\n2. Protein và Sắt – Xây dựng tế bào máu\n\nThể tích máu của mẹ sẽ tăng lên 50% trong thai kỳ để nuôi dưỡng bào thai. Do đó, thiếu sắt sẽ dẫn đến thiếu máu, gây mệt mỏi và chóng mặt. Mẹ nên bổ sung: Thịt bò nạc, ức gà, cá hồi (đã nấu chín kỹ), trứng gà và các loại hạt.\n\n3. Danh sách thực phẩm cần \"Tuyệt đối tránh\"\n\nĐể đảm bảo an toàn cho thai nhi, mẹ bầu 3 tháng đầu cần loại bỏ ngay các món sau khỏi thực đơn:\n\nThực phẩm sống: Sushi, gỏi cá, trứng lòng đào, thịt tái (nguy cơ nhiễm khuẩn Salmonella, E.coli).\n\nRau củ gây co thắt tử cung: Rau răm, đu đủ xanh, dứa (thơm), ngải cứu.\n\nChất kích thích: Rượu, bia, thuốc lá và hạn chế tối đa Cafein.\n\n4. Mẹo nhỏ giúp mẹ vượt qua cơn nghén\n\nNếu bạn bị nôn nghén nặng, hãy chia nhỏ bữa ăn thành 5-6 bữa/ngày thay vì 3 bữa chính. Luôn chuẩn bị sẵn bánh quy gừng hoặc uống nước chanh ấm vào buổi sáng để giảm cảm giác buồn nôn.', 'published', '2025-12-19 16:00:46', 'Dinh dưỡng bà bầu 3 tháng đầu: Ăn gì để vào con không vào mẹ?', 'Hướng dẫn chi tiết thực đơn cho mẹ bầu 3 tháng đầu. Danh sách thực phẩm giàu Axit Folic, Sắt và những món ăn cần kiêng kỵ tuyệt đối để tránh sảy thai.', NULL, '2025-12-19 16:00:46', '2025-12-19 16:00:46', NULL),
(2, 1, 2, 'Quy trình Thụ tinh trong ống nghiệm (IVF) chuẩn Châu Âu tại Phòng khám', 'quy-trinh-thu-tinh-trong-ong-nghiem-ivf-chuan-chau-au-tai', 'Giải đáp chi tiết quy trình IVF chuẩn y khoa, từ bước kích trứng, chọc hút đến chuyển phôi. Hy vọng mới cho các cặp vợ chồng mong con với tỷ lệ thành công cao.', 'Thụ tinh trong ống nghiệm (IVF) là kỹ thuật hỗ trợ sinh sản hiện đại nhất hiện nay, mang lại hy vọng cho hàng triệu cặp vợ chồng hiếm muộn. Tại phòng khám của chúng tôi, quy trình IVF được thực hiện khép kín với hệ thống phòng Lab đạt chuẩn ISO.\n\nGiai đoạn 1: Kích thích buồng trứng (Ngày 2 của chu kỳ)\n\nBác sĩ sẽ chỉ định tiêm thuốc kích thích buồng trứng liên tục trong khoảng 9-11 ngày. Mục đích là để thu được số lượng nang noãn tối ưu (thay vì chỉ 1 trứng rụng như chu kỳ tự nhiên). Trong thời gian này, bạn sẽ được siêu âm và xét nghiệm máu 3-4 lần để theo dõi sự phát triển của nang trứng.\n\nGiai đoạn 2: Chọc hút trứng và Lấy tinh trùng\n\nKhi nang trứng đạt kích thước chuẩn, mũi tiêm rụng trứng sẽ được thực hiện. 36 giờ sau, bác sĩ tiến hành chọc hút trứng. Quy trình này diễn ra nhẹ nhàng dưới sự hỗ trợ của gây mê, chỉ mất khoảng 15-20 phút. Song song đó, người chồng sẽ được lấy mẫu tinh trùng để lọc rửa, chọn ra những \'chiến binh\' khỏe mạnh nhất.\n\nGiai đoạn 3: Tạo phôi và Nuôi cấy phôi\n\nTrứng và tinh trùng được kết hợp trong đĩa cấy tại phòng Lab. Các chuyên viên phôi học sẽ theo dõi quá trình phân chia tế bào:\n\nPhôi ngày 3: Phôi có khoảng 6-8 tế bào.\n\nPhôi ngày 5 (Phôi nang): Phôi có hàng trăm tế bào, khả năng làm tổ cao hơn.\n\nGiai đoạn 4: Chuyển phôi và Thử thai\n\nBác sĩ dùng một ống thông (catheter) rất nhỏ, mềm để đưa phôi vào buồng tử cung người mẹ. Đây là thủ thuật không đau. Sau 14 ngày, mẹ có thể xét nghiệm Beta-HCG để đón nhận tin vui.', 'published', '2025-12-19 16:00:46', 'Quy trình thụ tinh trong ống nghiệm (IVF) chuẩn Châu Âu - Tỷ lệ đậu thai cao', 'Tìm hiểu quy trình IVF khép kín tại phòng khám: Kích trứng, chọc hút, nuôi cấy phôi và chuyển phôi. Giải pháp tối ưu cho các cặp vợ chồng hiếm muộn lâu năm.', NULL, '2025-12-19 16:00:46', '2025-12-19 16:00:46', NULL),
(3, 1, 3, 'So sánh Double Test, Triple Test và NIPT: Mẹ bầu nên chọn gói nào?', 'so-sanh-double-test-triple-test-va-nipt-me-bau-nen-chon-go', 'So sánh ưu nhược điểm của các phương pháp sàng lọc trước sinh phổ biến hiện nay. Tại sao NIPT lại được nhiều mẹ bầu lựa chọn dù chi phí cao hơn?', 'Sàng lọc trước sinh là bước không thể thiếu để phát hiện sớm các dị tật bẩm sinh do bất thường nhiễm sắc thể (NST). Hiện nay có 3 phương pháp phổ biến, vậy đâu là lựa chọn tốt nhất cho mẹ?\n\n1. Double Test (Sàng lọc quý I)\n\nThời điểm: Tuần thai 11 - 13 tuần 6 ngày.\n\nCách thức: Kết hợp siêu âm đo độ mờ da gáy và xét nghiệm máu mẹ.\n\nĐộ chính xác: Khoảng 80 - 85%.\n\nPhát hiện: Hội chứng Down, Edwards, Patau.\n\n2. Triple Test (Sàng lọc quý II)\n\nThời điểm: Tuần thai 15 - 18.\n\nCách thức: Xét nghiệm 3 chỉ số sinh hóa trong máu mẹ.\n\nĐộ chính xác: Thấp hơn Double Test (khoảng 70%).\n\nPhát hiện: Nguy cơ dị tật ống thần kinh và các hội chứng NST.\n\n3. NIPT (Sàng lọc trước sinh không xâm lấn - Cao cấp)\n\nĐây là phương pháp tiên tiến nhất hiện nay, phân tích ADN tự do của thai nhi (cfDNA) có trong máu mẹ.\n\nThời điểm: Thực hiện rất sớm, từ tuần thai thứ 9.\n\nĐộ chính xác: > 99%. Gần như tuyệt đối.\n\nƯu điểm vượt trội: Sàng lọc được toàn bộ 23 cặp NST, phát hiện cả các đột biến vi mất đoạn mà siêu âm hay xét nghiệm thường không thấy.\n\nAn toàn: Chỉ lấy 7-10ml máu mẹ, hoàn toàn không xâm lấn, không gây hại cho thai nhi.\n\nKết luận\n\nNếu có điều kiện kinh tế, các chuyên gia khuyến cáo mẹ nên chọn NIPT ngay từ tuần thứ 10 để an tâm tuyệt đối suốt thai kỳ, giảm thiểu việc phải chọc ối không cần thiết.', 'published', '2025-12-19 16:00:46', 'So sánh Double Test, Triple Test và NIPT: Mẹ bầu nên chọn gói nào?', 'Phân tích ưu nhược điểm và độ chính xác của các phương pháp sàng lọc dị tật thai nhi. Tại sao bác sĩ khuyên dùng NIPT từ tuần thứ 9?', NULL, '2025-12-19 16:00:46', '2025-12-19 16:00:46', NULL),
(4, 1, 4, '[HOT] Chào đón Giáng Sinh - Tặng gói quà sơ sinh 5 Triệu khi đăng ký Thai sản trọn gói', 'hot-chao-don-giang-sinh-tang-goi-qua-so-sinh-5-trieu-khi', 'Tri ân khách hàng dịp cuối năm, phòng khám dành tặng hàng ngàn voucher giảm giá và quà tặng sơ sinh cao cấp khi đăng ký gói theo dõi thai kỳ trong tháng 12.', '<p>Thấu hiểu nỗi lo chi phí của các gia đình trẻ, Phòng khám Sản-Phụ khoa xin gửi đến chương trình ưu đãi lớn nhất năm: \"Giáng sinh an lành - Đón rồng con khỏe mạnh\". 🎁 Chi tiết ưu đãi: GIẢM TRỰC TIẾP 20% chi phí khi đăng ký Gói theo dõi thai kỳ từ tuần 12. TẶNG NGAY gói sàng lọc sơ sinh (lấy máu gót chân) cho bé sau sinh trị giá 2.000.000đ. Miễn phí 01 lần siêu âm 4D VIP (có ghi đĩa/gửi file video). Tặng bộ quà tặng mẹ &amp; bé cao cấp: Balo bỉm sữa, quần áo sơ sinh... ⏰ Thời gian và Điều kiện áp dụng: Chương trình diễn ra từ: 10/12/2025 đến hết 31/12/2025. Áp dụng cho khách hàng đặt cọc online hoặc đến trực tiếp phòng khám.</p>', 'published', '2025-12-19 16:00:00', '[HOT] Ưu đãi thai sản trọn gói tháng 12: Giảm 20% + Tặng quà 5 Triệu', 'Chương trình tri ân lớn nhất năm. Giảm ngay 20% chi phí thai sản trọn gói, tặng gói sàng lọc sơ sinh và bộ quà tặng cao cấp cho mẹ và bé.', 'http://127.0.0.1:8000/storage/uploads/posts/1765385894_tai-xuong-2.jfif', '2025-12-19 16:00:46', '2025-12-19 16:29:17', NULL);

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
(1, 2),
(2, 3),
(3, 2),
(3, 4),
(4, 5),
(4, 6);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `benh_ans`
--

CREATE TABLE `benh_ans` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `bac_si_id` bigint(20) UNSIGNED NOT NULL,
  `lich_hen_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ngay_kham` date NOT NULL DEFAULT '2025-12-19',
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
(1, 23, 4, 1, '2025-12-20', 'Khám Gói Khám Phụ khoa Tổng quát (VIP)', 'Khám Gói Khám Phụ khoa Tổng quát (VIP)', 'Khám Gói Khám Phụ khoa Tổng quát (VIP)', 'Khám Gói Khám Phụ khoa Tổng quát (VIP)', 'Bệnh án được tạo tự động khi bắt đầu khám', '2025-12-19 17:48:37', '2025-12-19 17:49:03');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `benh_an_audits`
--

CREATE TABLE `benh_an_audits` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `benh_an_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `action` varchar(100) NOT NULL,
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
(1, 1, 4, 'created', NULL, '{\"user_id\":23,\"bac_si_id\":4,\"lich_hen_id\":1,\"ngay_kham\":\"2025-12-20 00:48:37\",\"tieu_de\":\"Kh\\u00e1m G\\u00f3i Kh\\u00e1m Ph\\u1ee5 khoa T\\u1ed5ng qu\\u00e1t (VIP)\",\"trieu_chung\":\"\",\"chuan_doan\":\"\",\"dieu_tri\":\"\",\"ghi_chu\":\"B\\u1ec7nh \\u00e1n \\u0111\\u01b0\\u1ee3c t\\u1ea1o t\\u1ef1 \\u0111\\u1ed9ng khi b\\u1eaft \\u0111\\u1ea7u kh\\u00e1m\",\"updated_at\":\"2025-12-20 00:48:37\",\"created_at\":\"2025-12-20 00:48:37\",\"id\":1}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-19 17:48:37', '2025-12-19 17:48:37'),
(2, 1, 4, 'updated', '{\"id\":1,\"user_id\":23,\"bac_si_id\":4,\"lich_hen_id\":1,\"ngay_kham\":\"2025-12-19T17:00:00.000000Z\",\"tieu_de\":\"Kh\\u00e1m G\\u00f3i Kh\\u00e1m Ph\\u1ee5 khoa T\\u1ed5ng qu\\u00e1t (VIP)\",\"trieu_chung\":\"\",\"chuan_doan\":\"\",\"dieu_tri\":\"\",\"ghi_chu\":\"B\\u1ec7nh \\u00e1n \\u0111\\u01b0\\u1ee3c t\\u1ea1o t\\u1ef1 \\u0111\\u1ed9ng khi b\\u1eaft \\u0111\\u1ea7u kh\\u00e1m\",\"created_at\":\"2025-12-19T17:48:37.000000Z\",\"updated_at\":\"2025-12-19T17:48:37.000000Z\"}', '{\"id\":1,\"user_id\":\"23\",\"bac_si_id\":\"4\",\"lich_hen_id\":\"1\",\"ngay_kham\":\"2025-12-20 00:00:00\",\"tieu_de\":\"Kh\\u00e1m G\\u00f3i Kh\\u00e1m Ph\\u1ee5 khoa T\\u1ed5ng qu\\u00e1t (VIP)\",\"trieu_chung\":\"Kh\\u00e1m G\\u00f3i Kh\\u00e1m Ph\\u1ee5 khoa T\\u1ed5ng qu\\u00e1t (VIP)\",\"chuan_doan\":\"Kh\\u00e1m G\\u00f3i Kh\\u00e1m Ph\\u1ee5 khoa T\\u1ed5ng qu\\u00e1t (VIP)\",\"dieu_tri\":\"Kh\\u00e1m G\\u00f3i Kh\\u00e1m Ph\\u1ee5 khoa T\\u1ed5ng qu\\u00e1t (VIP)\",\"ghi_chu\":\"B\\u1ec7nh \\u00e1n \\u0111\\u01b0\\u1ee3c t\\u1ea1o t\\u1ef1 \\u0111\\u1ed9ng khi b\\u1eaft \\u0111\\u1ea7u kh\\u00e1m\",\"created_at\":\"2025-12-20 00:48:37\",\"updated_at\":\"2025-12-20 00:49:03\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-19 17:49:03', '2025-12-19 17:49:03'),
(3, 1, 25, 'medicine_dispensed', NULL, '{\"description\":\"C\\u1ea5p thu\\u1ed1c cho \\u0111\\u01a1n #1\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', '2025-12-20 02:02:43', '2025-12-20 02:02:43'),
(4, 1, NULL, 'tai_kham_created_by_doctor', NULL, '{\"test\":1}', '127.0.0.1', 'Symfony', '2025-12-20 04:09:40', '2025-12-20 04:09:40'),
(5, 1, 23, 'tai_kham_confirmed_by_patient', '{\"tai_kham\":{\"id\":2,\"benh_an_id\":1,\"user_id\":23,\"bac_si_id\":4,\"lich_hen_id\":null,\"ngay_tai_kham\":\"2025-12-22T17:00:00.000000Z\",\"thoi_gian_tai_kham\":\"13:40:00\",\"so_ngay_du_kien\":2,\"ly_do\":\"Huy\\u1ebft \\u00e1p v\\u00e0 \\u0111\\u01b0\\u1eddng huy\\u1ebft cao. Y\\u00eau c\\u1ea7u l\\u00e0m th\\u00eam x\\u00e9t nghi\\u1ec7m dung n\\u1ea1p \\u0111\\u01b0\\u1eddng v\\u00e0 protein ni\\u1ec7u. H\\u1eb9n t\\u00e1i kh\\u00e1m sau 3 ng\\u00e0y.\",\"ghi_chu\":\"Huy\\u1ebft \\u00e1p v\\u00e0 \\u0111\\u01b0\\u1eddng huy\\u1ebft cao. Y\\u00eau c\\u1ea7u l\\u00e0m th\\u00eam x\\u00e9t nghi\\u1ec7m dung n\\u1ea1p \\u0111\\u01b0\\u1eddng v\\u00e0 protein ni\\u1ec7u. H\\u1eb9n t\\u00e1i kh\\u00e1m sau 3 ng\\u00e0y.\",\"trang_thai\":\"Ch\\u1edd x\\u00e1c nh\\u1eadn\",\"created_by_role\":\"doctor\",\"created_at\":\"2025-12-20T04:06:20.000000Z\",\"updated_at\":\"2025-12-20T04:06:20.000000Z\",\"deleted_at\":null,\"benh_an\":{\"id\":1,\"user_id\":23,\"bac_si_id\":4,\"lich_hen_id\":1,\"ngay_kham\":\"2025-12-19T17:00:00.000000Z\",\"tieu_de\":\"Kh\\u00e1m G\\u00f3i Kh\\u00e1m Ph\\u1ee5 khoa T\\u1ed5ng qu\\u00e1t (VIP)\",\"trieu_chung\":\"Kh\\u00e1m G\\u00f3i Kh\\u00e1m Ph\\u1ee5 khoa T\\u1ed5ng qu\\u00e1t (VIP)\",\"chuan_doan\":\"Kh\\u00e1m G\\u00f3i Kh\\u00e1m Ph\\u1ee5 khoa T\\u1ed5ng qu\\u00e1t (VIP)\",\"dieu_tri\":\"Kh\\u00e1m G\\u00f3i Kh\\u00e1m Ph\\u1ee5 khoa T\\u1ed5ng qu\\u00e1t (VIP)\",\"ghi_chu\":\"B\\u1ec7nh \\u00e1n \\u0111\\u01b0\\u1ee3c t\\u1ea1o t\\u1ef1 \\u0111\\u1ed9ng khi b\\u1eaft \\u0111\\u1ea7u kh\\u00e1m\",\"created_at\":\"2025-12-19T17:48:37.000000Z\",\"updated_at\":\"2025-12-19T17:49:03.000000Z\"},\"bac_si\":{\"id\":4,\"user_id\":4,\"ho_ten\":\"BS.CKI Nguy\\u1ec5n Thanh V\\u00e2n\",\"email\":\"vannguyen@vietcare.com\",\"chuyen_khoa\":\"Ph\\u1ee5 Khoa\",\"kinh_nghiem\":10,\"mo_ta\":\"Chuy\\u00ean s\\u00e2u v\\u1ec1 soi c\\u1ed5 t\\u1eed cung, \\u0111i\\u1ec1u tr\\u1ecb l\\u1ed9 tuy\\u1ebfn v\\u00e0 c\\u00e1c b\\u1ec7nh vi\\u00eam nhi\\u1ec5m ph\\u1ee5 khoa t\\u00e1i ph\\u00e1t. T\\u01b0 v\\u1ea5n s\\u1ee9c kh\\u1ecfe ti\\u1ec1n m\\u00e3n kinh.\",\"trang_thai\":\"\\u0110ang ho\\u1ea1t \\u0111\\u1ed9ng\",\"so_dien_thoai\":\"0909111004\",\"avatar\":null,\"dia_chi\":\"Qu\\u1eadn T\\u00e2n B\\u00ecnh, TP.HCM\",\"created_at\":\"2025-12-19T16:00:44.000000Z\",\"updated_at\":\"2025-12-19T16:00:44.000000Z\",\"user\":{\"id\":4,\"name\":\"BS.CKI Nguy\\u1ec5n Thanh V\\u00e2n\",\"email\":\"vannguyen@vietcare.com\",\"avatar\":null,\"so_dien_thoai\":\"0909111004\",\"ngay_sinh\":null,\"gioi_tinh\":null,\"email_verified_at\":\"2025-12-19T16:00:44.000000Z\",\"locked_at\":null,\"locked_until\":null,\"must_change_password\":false,\"last_login_at\":\"2025-12-20T01:08:39.000000Z\",\"login_attempts\":0,\"last_login_ip\":\"127.0.0.1\",\"created_at\":\"2025-12-19T16:00:44.000000Z\",\"updated_at\":\"2025-12-20T01:08:39.000000Z\",\"role\":\"doctor\"}},\"user\":{\"id\":23,\"name\":\"Nguy\\u1ec5n Ch\\u00ed Thanh\",\"email\":\"tn822798@gmail.com\",\"avatar\":null,\"so_dien_thoai\":null,\"ngay_sinh\":null,\"gioi_tinh\":null,\"email_verified_at\":null,\"locked_at\":null,\"locked_until\":null,\"must_change_password\":false,\"last_login_at\":\"2025-12-20T01:08:21.000000Z\",\"login_attempts\":0,\"last_login_ip\":\"127.0.0.1\",\"created_at\":\"2025-12-19T16:11:58.000000Z\",\"updated_at\":\"2025-12-20T01:08:21.000000Z\",\"role\":\"patient\"}}}', '{\"tai_kham\":{\"id\":2,\"benh_an_id\":1,\"user_id\":23,\"bac_si_id\":4,\"lich_hen_id\":null,\"ngay_tai_kham\":\"2025-12-22T17:00:00.000000Z\",\"thoi_gian_tai_kham\":\"13:40:00\",\"so_ngay_du_kien\":2,\"ly_do\":\"Huy\\u1ebft \\u00e1p v\\u00e0 \\u0111\\u01b0\\u1eddng huy\\u1ebft cao. Y\\u00eau c\\u1ea7u l\\u00e0m th\\u00eam x\\u00e9t nghi\\u1ec7m dung n\\u1ea1p \\u0111\\u01b0\\u1eddng v\\u00e0 protein ni\\u1ec7u. H\\u1eb9n t\\u00e1i kh\\u00e1m sau 3 ng\\u00e0y.\",\"ghi_chu\":\"Huy\\u1ebft \\u00e1p v\\u00e0 \\u0111\\u01b0\\u1eddng huy\\u1ebft cao. Y\\u00eau c\\u1ea7u l\\u00e0m th\\u00eam x\\u00e9t nghi\\u1ec7m dung n\\u1ea1p \\u0111\\u01b0\\u1eddng v\\u00e0 protein ni\\u1ec7u. H\\u1eb9n t\\u00e1i kh\\u00e1m sau 3 ng\\u00e0y.\",\"trang_thai\":\"\\u0110\\u00e3 x\\u00e1c nh\\u1eadn\",\"created_by_role\":\"doctor\",\"created_at\":\"2025-12-20T04:06:20.000000Z\",\"updated_at\":\"2025-12-20T04:10:54.000000Z\",\"deleted_at\":null,\"benh_an\":{\"id\":1,\"user_id\":23,\"bac_si_id\":4,\"lich_hen_id\":1,\"ngay_kham\":\"2025-12-19T17:00:00.000000Z\",\"tieu_de\":\"Kh\\u00e1m G\\u00f3i Kh\\u00e1m Ph\\u1ee5 khoa T\\u1ed5ng qu\\u00e1t (VIP)\",\"trieu_chung\":\"Kh\\u00e1m G\\u00f3i Kh\\u00e1m Ph\\u1ee5 khoa T\\u1ed5ng qu\\u00e1t (VIP)\",\"chuan_doan\":\"Kh\\u00e1m G\\u00f3i Kh\\u00e1m Ph\\u1ee5 khoa T\\u1ed5ng qu\\u00e1t (VIP)\",\"dieu_tri\":\"Kh\\u00e1m G\\u00f3i Kh\\u00e1m Ph\\u1ee5 khoa T\\u1ed5ng qu\\u00e1t (VIP)\",\"ghi_chu\":\"B\\u1ec7nh \\u00e1n \\u0111\\u01b0\\u1ee3c t\\u1ea1o t\\u1ef1 \\u0111\\u1ed9ng khi b\\u1eaft \\u0111\\u1ea7u kh\\u00e1m\",\"created_at\":\"2025-12-19T17:48:37.000000Z\",\"updated_at\":\"2025-12-19T17:49:03.000000Z\"},\"bac_si\":{\"id\":4,\"user_id\":4,\"ho_ten\":\"BS.CKI Nguy\\u1ec5n Thanh V\\u00e2n\",\"email\":\"vannguyen@vietcare.com\",\"chuyen_khoa\":\"Ph\\u1ee5 Khoa\",\"kinh_nghiem\":10,\"mo_ta\":\"Chuy\\u00ean s\\u00e2u v\\u1ec1 soi c\\u1ed5 t\\u1eed cung, \\u0111i\\u1ec1u tr\\u1ecb l\\u1ed9 tuy\\u1ebfn v\\u00e0 c\\u00e1c b\\u1ec7nh vi\\u00eam nhi\\u1ec5m ph\\u1ee5 khoa t\\u00e1i ph\\u00e1t. T\\u01b0 v\\u1ea5n s\\u1ee9c kh\\u1ecfe ti\\u1ec1n m\\u00e3n kinh.\",\"trang_thai\":\"\\u0110ang ho\\u1ea1t \\u0111\\u1ed9ng\",\"so_dien_thoai\":\"0909111004\",\"avatar\":null,\"dia_chi\":\"Qu\\u1eadn T\\u00e2n B\\u00ecnh, TP.HCM\",\"created_at\":\"2025-12-19T16:00:44.000000Z\",\"updated_at\":\"2025-12-19T16:00:44.000000Z\",\"user\":{\"id\":4,\"name\":\"BS.CKI Nguy\\u1ec5n Thanh V\\u00e2n\",\"email\":\"vannguyen@vietcare.com\",\"avatar\":null,\"so_dien_thoai\":\"0909111004\",\"ngay_sinh\":null,\"gioi_tinh\":null,\"email_verified_at\":\"2025-12-19T16:00:44.000000Z\",\"locked_at\":null,\"locked_until\":null,\"must_change_password\":false,\"last_login_at\":\"2025-12-20T01:08:39.000000Z\",\"login_attempts\":0,\"last_login_ip\":\"127.0.0.1\",\"created_at\":\"2025-12-19T16:00:44.000000Z\",\"updated_at\":\"2025-12-20T01:08:39.000000Z\",\"role\":\"doctor\"}},\"user\":{\"id\":23,\"name\":\"Nguy\\u1ec5n Ch\\u00ed Thanh\",\"email\":\"tn822798@gmail.com\",\"avatar\":null,\"so_dien_thoai\":null,\"ngay_sinh\":null,\"gioi_tinh\":null,\"email_verified_at\":null,\"locked_at\":null,\"locked_until\":null,\"must_change_password\":false,\"last_login_at\":\"2025-12-20T01:08:21.000000Z\",\"login_attempts\":0,\"last_login_ip\":\"127.0.0.1\",\"created_at\":\"2025-12-19T16:11:58.000000Z\",\"updated_at\":\"2025-12-20T01:08:21.000000Z\",\"role\":\"patient\"}}}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-20 04:10:54', '2025-12-20 04:10:54'),
(6, 1, 25, 'tai_kham_booked', '{\"tai_kham\":{\"id\":2,\"benh_an_id\":1,\"user_id\":23,\"bac_si_id\":4,\"lich_hen_id\":null,\"ngay_tai_kham\":\"2025-12-22T17:00:00.000000Z\",\"thoi_gian_tai_kham\":\"13:40:00\",\"so_ngay_du_kien\":2,\"ly_do\":\"Huy\\u1ebft \\u00e1p v\\u00e0 \\u0111\\u01b0\\u1eddng huy\\u1ebft cao. Y\\u00eau c\\u1ea7u l\\u00e0m th\\u00eam x\\u00e9t nghi\\u1ec7m dung n\\u1ea1p \\u0111\\u01b0\\u1eddng v\\u00e0 protein ni\\u1ec7u. H\\u1eb9n t\\u00e1i kh\\u00e1m sau 3 ng\\u00e0y.\",\"ghi_chu\":\"Huy\\u1ebft \\u00e1p v\\u00e0 \\u0111\\u01b0\\u1eddng huy\\u1ebft cao. Y\\u00eau c\\u1ea7u l\\u00e0m th\\u00eam x\\u00e9t nghi\\u1ec7m dung n\\u1ea1p \\u0111\\u01b0\\u1eddng v\\u00e0 protein ni\\u1ec7u. H\\u1eb9n t\\u00e1i kh\\u00e1m sau 3 ng\\u00e0y.\",\"trang_thai\":\"\\u0110\\u00e3 x\\u00e1c nh\\u1eadn\",\"created_by_role\":\"doctor\",\"created_at\":\"2025-12-20T04:06:20.000000Z\",\"updated_at\":\"2025-12-20T04:10:54.000000Z\",\"deleted_at\":null,\"benh_an\":{\"id\":1,\"user_id\":23,\"bac_si_id\":4,\"lich_hen_id\":1,\"ngay_kham\":\"2025-12-19T17:00:00.000000Z\",\"tieu_de\":\"Kh\\u00e1m G\\u00f3i Kh\\u00e1m Ph\\u1ee5 khoa T\\u1ed5ng qu\\u00e1t (VIP)\",\"trieu_chung\":\"Kh\\u00e1m G\\u00f3i Kh\\u00e1m Ph\\u1ee5 khoa T\\u1ed5ng qu\\u00e1t (VIP)\",\"chuan_doan\":\"Kh\\u00e1m G\\u00f3i Kh\\u00e1m Ph\\u1ee5 khoa T\\u1ed5ng qu\\u00e1t (VIP)\",\"dieu_tri\":\"Kh\\u00e1m G\\u00f3i Kh\\u00e1m Ph\\u1ee5 khoa T\\u1ed5ng qu\\u00e1t (VIP)\",\"ghi_chu\":\"B\\u1ec7nh \\u00e1n \\u0111\\u01b0\\u1ee3c t\\u1ea1o t\\u1ef1 \\u0111\\u1ed9ng khi b\\u1eaft \\u0111\\u1ea7u kh\\u00e1m\",\"created_at\":\"2025-12-19T17:48:37.000000Z\",\"updated_at\":\"2025-12-19T17:49:03.000000Z\",\"lich_hen\":{\"id\":1,\"user_id\":23,\"bac_si_id\":4,\"dich_vu_id\":14,\"ten_benh_nhan\":null,\"sdt_benh_nhan\":null,\"email_benh_nhan\":null,\"ngay_sinh_benh_nhan\":null,\"tong_tien\":\"2500000.00\",\"ngay_hen\":\"2025-12-19T17:00:00.000000Z\",\"thoi_gian_hen\":\"01:00:00\",\"ghi_chu\":null,\"trang_thai\":\"Ho\\u00e0n th\\u00e0nh\",\"checked_in_at\":\"2025-12-19T17:48:11.000000Z\",\"checked_in_by\":25,\"thoi_gian_bat_dau_kham\":\"2025-12-19T17:48:37.000000Z\",\"completed_at\":\"2025-12-19T17:58:52.000000Z\",\"created_at\":\"2025-12-19T17:46:19.000000Z\",\"updated_at\":\"2025-12-20T01:37:05.000000Z\",\"payment_status\":\"\\u0110\\u00e3 thanh to\\u00e1n\",\"payment_method\":\"MOMO\",\"paid_at\":\"2025-12-20T01:37:05.000000Z\",\"cancelled_by\":null,\"cancelled_at\":null},\"user\":{\"id\":23,\"name\":\"Nguy\\u1ec5n Ch\\u00ed Thanh\",\"email\":\"tn822798@gmail.com\",\"avatar\":null,\"so_dien_thoai\":null,\"ngay_sinh\":null,\"gioi_tinh\":null,\"email_verified_at\":null,\"locked_at\":null,\"locked_until\":null,\"must_change_password\":false,\"last_login_at\":\"2025-12-20T01:08:21.000000Z\",\"login_attempts\":0,\"last_login_ip\":\"127.0.0.1\",\"created_at\":\"2025-12-19T16:11:58.000000Z\",\"updated_at\":\"2025-12-20T01:08:21.000000Z\",\"role\":\"patient\"}},\"user\":{\"id\":23,\"name\":\"Nguy\\u1ec5n Ch\\u00ed Thanh\",\"email\":\"tn822798@gmail.com\",\"avatar\":null,\"so_dien_thoai\":null,\"ngay_sinh\":null,\"gioi_tinh\":null,\"email_verified_at\":null,\"locked_at\":null,\"locked_until\":null,\"must_change_password\":false,\"last_login_at\":\"2025-12-20T01:08:21.000000Z\",\"login_attempts\":0,\"last_login_ip\":\"127.0.0.1\",\"created_at\":\"2025-12-19T16:11:58.000000Z\",\"updated_at\":\"2025-12-20T01:08:21.000000Z\",\"role\":\"patient\"},\"bac_si\":{\"id\":4,\"user_id\":4,\"ho_ten\":\"BS.CKI Nguy\\u1ec5n Thanh V\\u00e2n\",\"email\":\"vannguyen@vietcare.com\",\"chuyen_khoa\":\"Ph\\u1ee5 Khoa\",\"kinh_nghiem\":10,\"mo_ta\":\"Chuy\\u00ean s\\u00e2u v\\u1ec1 soi c\\u1ed5 t\\u1eed cung, \\u0111i\\u1ec1u tr\\u1ecb l\\u1ed9 tuy\\u1ebfn v\\u00e0 c\\u00e1c b\\u1ec7nh vi\\u00eam nhi\\u1ec5m ph\\u1ee5 khoa t\\u00e1i ph\\u00e1t. T\\u01b0 v\\u1ea5n s\\u1ee9c kh\\u1ecfe ti\\u1ec1n m\\u00e3n kinh.\",\"trang_thai\":\"\\u0110ang ho\\u1ea1t \\u0111\\u1ed9ng\",\"so_dien_thoai\":\"0909111004\",\"avatar\":null,\"dia_chi\":\"Qu\\u1eadn T\\u00e2n B\\u00ecnh, TP.HCM\",\"created_at\":\"2025-12-19T16:00:44.000000Z\",\"updated_at\":\"2025-12-19T16:00:44.000000Z\",\"user\":{\"id\":4,\"name\":\"BS.CKI Nguy\\u1ec5n Thanh V\\u00e2n\",\"email\":\"vannguyen@vietcare.com\",\"avatar\":null,\"so_dien_thoai\":\"0909111004\",\"ngay_sinh\":null,\"gioi_tinh\":null,\"email_verified_at\":\"2025-12-19T16:00:44.000000Z\",\"locked_at\":null,\"locked_until\":null,\"must_change_password\":false,\"last_login_at\":\"2025-12-20T01:08:39.000000Z\",\"login_attempts\":0,\"last_login_ip\":\"127.0.0.1\",\"created_at\":\"2025-12-19T16:00:44.000000Z\",\"updated_at\":\"2025-12-20T01:08:39.000000Z\",\"role\":\"doctor\"}}}}', '{\"tai_kham\":{\"id\":2,\"benh_an_id\":1,\"user_id\":23,\"bac_si_id\":4,\"lich_hen_id\":2,\"ngay_tai_kham\":\"2025-12-22T17:00:00.000000Z\",\"thoi_gian_tai_kham\":\"13:40:00\",\"so_ngay_du_kien\":2,\"ly_do\":\"Huy\\u1ebft \\u00e1p v\\u00e0 \\u0111\\u01b0\\u1eddng huy\\u1ebft cao. Y\\u00eau c\\u1ea7u l\\u00e0m th\\u00eam x\\u00e9t nghi\\u1ec7m dung n\\u1ea1p \\u0111\\u01b0\\u1eddng v\\u00e0 protein ni\\u1ec7u. H\\u1eb9n t\\u00e1i kh\\u00e1m sau 3 ng\\u00e0y.\",\"ghi_chu\":\"Huy\\u1ebft \\u00e1p v\\u00e0 \\u0111\\u01b0\\u1eddng huy\\u1ebft cao. Y\\u00eau c\\u1ea7u l\\u00e0m th\\u00eam x\\u00e9t nghi\\u1ec7m dung n\\u1ea1p \\u0111\\u01b0\\u1eddng v\\u00e0 protein ni\\u1ec7u. H\\u1eb9n t\\u00e1i kh\\u00e1m sau 3 ng\\u00e0y.\",\"trang_thai\":\"\\u0110\\u00e3 \\u0111\\u1eb7t l\\u1ecbch\",\"created_by_role\":\"doctor\",\"created_at\":\"2025-12-20T04:06:20.000000Z\",\"updated_at\":\"2025-12-20T04:12:45.000000Z\",\"deleted_at\":null,\"benh_an\":{\"id\":1,\"user_id\":23,\"bac_si_id\":4,\"lich_hen_id\":1,\"ngay_kham\":\"2025-12-19T17:00:00.000000Z\",\"tieu_de\":\"Kh\\u00e1m G\\u00f3i Kh\\u00e1m Ph\\u1ee5 khoa T\\u1ed5ng qu\\u00e1t (VIP)\",\"trieu_chung\":\"Kh\\u00e1m G\\u00f3i Kh\\u00e1m Ph\\u1ee5 khoa T\\u1ed5ng qu\\u00e1t (VIP)\",\"chuan_doan\":\"Kh\\u00e1m G\\u00f3i Kh\\u00e1m Ph\\u1ee5 khoa T\\u1ed5ng qu\\u00e1t (VIP)\",\"dieu_tri\":\"Kh\\u00e1m G\\u00f3i Kh\\u00e1m Ph\\u1ee5 khoa T\\u1ed5ng qu\\u00e1t (VIP)\",\"ghi_chu\":\"B\\u1ec7nh \\u00e1n \\u0111\\u01b0\\u1ee3c t\\u1ea1o t\\u1ef1 \\u0111\\u1ed9ng khi b\\u1eaft \\u0111\\u1ea7u kh\\u00e1m\",\"created_at\":\"2025-12-19T17:48:37.000000Z\",\"updated_at\":\"2025-12-19T17:49:03.000000Z\"},\"user\":{\"id\":23,\"name\":\"Nguy\\u1ec5n Ch\\u00ed Thanh\",\"email\":\"tn822798@gmail.com\",\"avatar\":null,\"so_dien_thoai\":null,\"ngay_sinh\":null,\"gioi_tinh\":null,\"email_verified_at\":null,\"locked_at\":null,\"locked_until\":null,\"must_change_password\":false,\"last_login_at\":\"2025-12-20T01:08:21.000000Z\",\"login_attempts\":0,\"last_login_ip\":\"127.0.0.1\",\"created_at\":\"2025-12-19T16:11:58.000000Z\",\"updated_at\":\"2025-12-20T01:08:21.000000Z\",\"role\":\"patient\"},\"bac_si\":{\"id\":4,\"user_id\":4,\"ho_ten\":\"BS.CKI Nguy\\u1ec5n Thanh V\\u00e2n\",\"email\":\"vannguyen@vietcare.com\",\"chuyen_khoa\":\"Ph\\u1ee5 Khoa\",\"kinh_nghiem\":10,\"mo_ta\":\"Chuy\\u00ean s\\u00e2u v\\u1ec1 soi c\\u1ed5 t\\u1eed cung, \\u0111i\\u1ec1u tr\\u1ecb l\\u1ed9 tuy\\u1ebfn v\\u00e0 c\\u00e1c b\\u1ec7nh vi\\u00eam nhi\\u1ec5m ph\\u1ee5 khoa t\\u00e1i ph\\u00e1t. T\\u01b0 v\\u1ea5n s\\u1ee9c kh\\u1ecfe ti\\u1ec1n m\\u00e3n kinh.\",\"trang_thai\":\"\\u0110ang ho\\u1ea1t \\u0111\\u1ed9ng\",\"so_dien_thoai\":\"0909111004\",\"avatar\":null,\"dia_chi\":\"Qu\\u1eadn T\\u00e2n B\\u00ecnh, TP.HCM\",\"created_at\":\"2025-12-19T16:00:44.000000Z\",\"updated_at\":\"2025-12-19T16:00:44.000000Z\"}}}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', '2025-12-20 04:12:45', '2025-12-20 04:12:45');

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
(1, 1, 'screenshot_1765433090.png', 'files/5UCFXXUvoUMcjZHRM7fsSvzUKGJl8Q6SZbyoRm2j.png', 'benh_an_private', 'image/png', 630260, 4, '2025-12-19 17:49:03', '2025-12-19 17:49:03');

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
(1, 1, '2025-12-21', '07:30:00', '11:30:00', 'add', 'Thêm ca Chủ nhật theo yêu cầu', NULL, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(2, 4, '2025-12-20', '00:00:00', '17:00:00', 'add', 'Thêm ca test', NULL, '2025-12-19 17:45:14', '2025-12-19 17:45:14');

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
(3, 2, '2025-12-19', '08:00:00', '17:00:00', 'Nhân viên CSKH', '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(4, 3, '2025-12-19', '17:30:00', '20:30:00', 'Lễ tân', '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(5, 4, '2025-12-19', '08:00:00', '16:00:00', 'Điều dưỡng trưởng', '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(6, 5, '2025-12-19', '08:00:00', '16:30:00', 'Nữ hộ sinh', '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(7, 6, '2025-12-19', '08:00:00', '17:00:00', 'Điều dưỡng đa khoa', '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(8, 7, '2025-12-19', '08:00:00', '17:00:00', 'Thu ngân', '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(9, 8, '2025-12-19', '07:30:00', '11:30:00', 'Dược sĩ', '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(10, 9, '2025-12-19', '09:00:00', '18:00:00', 'Quản lý phòng khám', '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(11, 10, '2025-12-19', '09:00:00', '18:00:00', 'Nhân viên IT / Kỹ thuật', '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(13, 3, '2025-12-20', '17:30:00', '20:30:00', 'Lễ tân', '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(14, 4, '2025-12-20', '08:00:00', '12:00:00', 'Điều dưỡng trưởng', '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(15, 7, '2025-12-20', '08:00:00', '12:00:00', 'Thu ngân', '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(16, 8, '2025-12-20', '07:30:00', '11:30:00', 'Dược sĩ', '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(19, 2, '2025-12-22', '08:00:00', '17:00:00', 'Nhân viên CSKH', '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(20, 4, '2025-12-22', '08:00:00', '16:00:00', 'Điều dưỡng trưởng', '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(21, 5, '2025-12-22', '08:00:00', '16:30:00', 'Nữ hộ sinh', '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(22, 6, '2025-12-22', '08:00:00', '17:00:00', 'Điều dưỡng đa khoa', '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(23, 7, '2025-12-22', '08:00:00', '17:00:00', 'Thu ngân', '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(24, 8, '2025-12-22', '07:30:00', '11:30:00', 'Dược sĩ', '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(25, 9, '2025-12-22', '09:00:00', '18:00:00', 'Quản lý phòng khám', '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(26, 10, '2025-12-22', '09:00:00', '18:00:00', 'Nhân viên IT / Kỹ thuật', '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(29, 2, '2025-12-23', '08:00:00', '17:00:00', 'Nhân viên CSKH', '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(30, 3, '2025-12-23', '17:30:00', '20:30:00', 'Lễ tân', '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(31, 4, '2025-12-23', '08:00:00', '16:00:00', 'Điều dưỡng trưởng', '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(32, 5, '2025-12-23', '08:00:00', '16:30:00', 'Nữ hộ sinh', '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(33, 6, '2025-12-23', '08:00:00', '17:00:00', 'Điều dưỡng đa khoa', '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(34, 7, '2025-12-23', '08:00:00', '17:00:00', 'Thu ngân', '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(35, 8, '2025-12-23', '07:30:00', '11:30:00', 'Dược sĩ', '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(36, 9, '2025-12-23', '09:00:00', '18:00:00', 'Quản lý phòng khám', '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(37, 10, '2025-12-23', '09:00:00', '18:00:00', 'Nhân viên IT / Kỹ thuật', '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(40, 2, '2025-12-24', '08:00:00', '17:00:00', 'Nhân viên CSKH', '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(41, 3, '2025-12-24', '17:30:00', '20:30:00', 'Lễ tân', '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(42, 4, '2025-12-24', '08:00:00', '16:00:00', 'Điều dưỡng trưởng', '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(43, 5, '2025-12-24', '08:00:00', '16:30:00', 'Nữ hộ sinh', '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(44, 6, '2025-12-24', '08:00:00', '17:00:00', 'Điều dưỡng đa khoa', '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(45, 7, '2025-12-24', '08:00:00', '17:00:00', 'Thu ngân', '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(46, 8, '2025-12-24', '07:30:00', '11:30:00', 'Dược sĩ', '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(47, 9, '2025-12-24', '09:00:00', '18:00:00', 'Quản lý phòng khám', '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(48, 10, '2025-12-24', '09:00:00', '18:00:00', 'Nhân viên IT / Kỹ thuật', '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(51, 2, '2025-12-25', '08:00:00', '17:00:00', 'Nhân viên CSKH', '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(52, 3, '2025-12-25', '17:30:00', '20:30:00', 'Lễ tân', '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(53, 4, '2025-12-25', '08:00:00', '16:00:00', 'Điều dưỡng trưởng', '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(54, 5, '2025-12-25', '08:00:00', '16:30:00', 'Nữ hộ sinh', '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(55, 6, '2025-12-25', '08:00:00', '17:00:00', 'Điều dưỡng đa khoa', '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(56, 7, '2025-12-25', '08:00:00', '17:00:00', 'Thu ngân', '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(57, 8, '2025-12-25', '07:30:00', '11:30:00', 'Dược sĩ', '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(58, 9, '2025-12-25', '09:00:00', '18:00:00', 'Quản lý phòng khám', '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(59, 10, '2025-12-25', '09:00:00', '18:00:00', 'Nhân viên IT / Kỹ thuật', '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(62, 2, '2025-12-26', '08:00:00', '17:00:00', 'Nhân viên CSKH', '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(63, 3, '2025-12-26', '17:30:00', '20:30:00', 'Lễ tân', '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(64, 4, '2025-12-26', '08:00:00', '16:00:00', 'Điều dưỡng trưởng', '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(65, 5, '2025-12-26', '08:00:00', '16:30:00', 'Nữ hộ sinh', '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(66, 6, '2025-12-26', '08:00:00', '17:00:00', 'Điều dưỡng đa khoa', '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(67, 7, '2025-12-26', '08:00:00', '17:00:00', 'Thu ngân', '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(68, 8, '2025-12-26', '07:30:00', '11:30:00', 'Dược sĩ', '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(69, 9, '2025-12-26', '09:00:00', '18:00:00', 'Quản lý phòng khám', '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(70, 10, '2025-12-26', '09:00:00', '18:00:00', 'Nhân viên IT / Kỹ thuật', '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(72, 3, '2025-12-27', '17:30:00', '20:30:00', 'Lễ tân', '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(73, 4, '2025-12-27', '08:00:00', '12:00:00', 'Điều dưỡng trưởng', '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(74, 7, '2025-12-27', '08:00:00', '12:00:00', 'Thu ngân', '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(75, 8, '2025-12-27', '07:30:00', '11:30:00', 'Dược sĩ', '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(78, 2, '2025-12-29', '08:00:00', '17:00:00', 'Nhân viên CSKH', '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(79, 4, '2025-12-29', '08:00:00', '16:00:00', 'Điều dưỡng trưởng', '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(80, 5, '2025-12-29', '08:00:00', '16:30:00', 'Nữ hộ sinh', '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(81, 6, '2025-12-29', '08:00:00', '17:00:00', 'Điều dưỡng đa khoa', '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(82, 7, '2025-12-29', '08:00:00', '17:00:00', 'Thu ngân', '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(83, 8, '2025-12-29', '07:30:00', '11:30:00', 'Dược sĩ', '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(84, 9, '2025-12-29', '09:00:00', '18:00:00', 'Quản lý phòng khám', '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(85, 10, '2025-12-29', '09:00:00', '18:00:00', 'Nhân viên IT / Kỹ thuật', '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(88, 2, '2025-12-30', '08:00:00', '17:00:00', 'Nhân viên CSKH', '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(89, 3, '2025-12-30', '17:30:00', '20:30:00', 'Lễ tân', '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(90, 4, '2025-12-30', '08:00:00', '16:00:00', 'Điều dưỡng trưởng', '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(91, 5, '2025-12-30', '08:00:00', '16:30:00', 'Nữ hộ sinh', '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(92, 6, '2025-12-30', '08:00:00', '17:00:00', 'Điều dưỡng đa khoa', '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(93, 7, '2025-12-30', '08:00:00', '17:00:00', 'Thu ngân', '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(94, 8, '2025-12-30', '07:30:00', '11:30:00', 'Dược sĩ', '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(95, 9, '2025-12-30', '09:00:00', '18:00:00', 'Quản lý phòng khám', '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(96, 10, '2025-12-30', '09:00:00', '18:00:00', 'Nhân viên IT / Kỹ thuật', '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(99, 2, '2025-12-31', '08:00:00', '17:00:00', 'Nhân viên CSKH', '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(100, 3, '2025-12-31', '17:30:00', '20:30:00', 'Lễ tân', '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(101, 4, '2025-12-31', '08:00:00', '16:00:00', 'Điều dưỡng trưởng', '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(102, 5, '2025-12-31', '08:00:00', '16:30:00', 'Nữ hộ sinh', '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(103, 6, '2025-12-31', '08:00:00', '17:00:00', 'Điều dưỡng đa khoa', '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(104, 7, '2025-12-31', '08:00:00', '17:00:00', 'Thu ngân', '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(105, 8, '2025-12-31', '07:30:00', '11:30:00', 'Dược sĩ', '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(106, 9, '2025-12-31', '09:00:00', '18:00:00', 'Quản lý phòng khám', '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(107, 10, '2025-12-31', '09:00:00', '18:00:00', 'Nhân viên IT / Kỹ thuật', '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(110, 2, '2026-01-01', '08:00:00', '17:00:00', 'Nhân viên CSKH', '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(111, 3, '2026-01-01', '17:30:00', '20:30:00', 'Lễ tân', '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(112, 4, '2026-01-01', '08:00:00', '16:00:00', 'Điều dưỡng trưởng', '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(113, 5, '2026-01-01', '08:00:00', '16:30:00', 'Nữ hộ sinh', '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(114, 6, '2026-01-01', '08:00:00', '17:00:00', 'Điều dưỡng đa khoa', '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(115, 7, '2026-01-01', '08:00:00', '17:00:00', 'Thu ngân', '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(116, 8, '2026-01-01', '07:30:00', '11:30:00', 'Dược sĩ', '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(117, 9, '2026-01-01', '09:00:00', '18:00:00', 'Quản lý phòng khám', '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(118, 10, '2026-01-01', '09:00:00', '18:00:00', 'Nhân viên IT / Kỹ thuật', '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(119, 11, '2025-12-19', '07:30:00', '11:30:00', 'Trưởng lễ tân', '2025-12-19 16:43:11', '2025-12-19 16:43:11'),
(120, 11, '2025-12-19', '13:30:00', '17:00:00', 'Trưởng lễ tân', '2025-12-19 16:43:11', '2025-12-19 16:43:11'),
(121, 11, '2025-12-20', '07:30:00', '11:30:00', 'Trưởng lễ tân', '2025-12-19 16:43:11', '2025-12-19 16:43:11'),
(122, 11, '2025-12-22', '07:30:00', '11:30:00', 'Trưởng lễ tân', '2025-12-19 16:43:11', '2025-12-19 16:43:11'),
(123, 11, '2025-12-22', '13:30:00', '17:00:00', 'Trưởng lễ tân', '2025-12-19 16:43:11', '2025-12-19 16:43:11'),
(124, 11, '2025-12-23', '07:30:00', '11:30:00', 'Trưởng lễ tân', '2025-12-19 16:43:11', '2025-12-19 16:43:11'),
(125, 11, '2025-12-23', '13:30:00', '17:00:00', 'Trưởng lễ tân', '2025-12-19 16:43:11', '2025-12-19 16:43:11'),
(126, 11, '2025-12-24', '07:30:00', '11:30:00', 'Trưởng lễ tân', '2025-12-19 16:43:11', '2025-12-19 16:43:11'),
(127, 11, '2025-12-24', '13:30:00', '17:00:00', 'Trưởng lễ tân', '2025-12-19 16:43:11', '2025-12-19 16:43:11'),
(128, 11, '2025-12-25', '07:30:00', '11:30:00', 'Trưởng lễ tân', '2025-12-19 16:43:11', '2025-12-19 16:43:11'),
(129, 11, '2025-12-25', '13:30:00', '17:00:00', 'Trưởng lễ tân', '2025-12-19 16:43:11', '2025-12-19 16:43:11'),
(130, 11, '2025-12-26', '07:30:00', '11:30:00', 'Trưởng lễ tân', '2025-12-19 16:43:11', '2025-12-19 16:43:11'),
(131, 11, '2025-12-26', '13:30:00', '17:00:00', 'Trưởng lễ tân', '2025-12-19 16:43:11', '2025-12-19 16:43:11'),
(132, 11, '2025-12-27', '07:30:00', '11:30:00', 'Trưởng lễ tân', '2025-12-19 16:43:11', '2025-12-19 16:43:11'),
(133, 11, '2025-12-29', '07:30:00', '11:30:00', 'Trưởng lễ tân', '2025-12-19 16:43:11', '2025-12-19 16:43:11'),
(134, 11, '2025-12-29', '13:30:00', '17:00:00', 'Trưởng lễ tân', '2025-12-19 16:43:11', '2025-12-19 16:43:11'),
(135, 11, '2025-12-30', '07:30:00', '11:30:00', 'Trưởng lễ tân', '2025-12-19 16:43:11', '2025-12-19 16:43:11'),
(136, 11, '2025-12-30', '13:30:00', '17:00:00', 'Trưởng lễ tân', '2025-12-19 16:43:11', '2025-12-19 16:43:11'),
(137, 11, '2025-12-31', '07:30:00', '11:30:00', 'Trưởng lễ tân', '2025-12-19 16:43:11', '2025-12-19 16:43:11'),
(138, 11, '2025-12-31', '13:30:00', '17:00:00', 'Trưởng lễ tân', '2025-12-19 16:43:11', '2025-12-19 16:43:11'),
(139, 11, '2026-01-01', '07:30:00', '11:30:00', 'Trưởng lễ tân', '2025-12-19 16:43:11', '2025-12-19 16:43:11'),
(140, 11, '2026-01-01', '13:30:00', '17:00:00', 'Trưởng lễ tân', '2025-12-19 16:43:11', '2025-12-19 16:43:11');

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
(1, 'Sản Khoa', 'san-khoa', 'Chuyên sâu về theo dõi thai kỳ, quản lý thai nghén nguy cơ cao (tiền sản giật, đái tháo đường thai kỳ), tư vấn dinh dưỡng và chuẩn bị cho cuộc vượt cạn an toàn.', '2025-12-19 16:00:43', '2025-12-19 16:00:43'),
(2, 'Phụ Khoa', 'phu-khoa', 'Khám và điều trị các bệnh lý viêm nhiễm phụ khoa, lộ tuyến cổ tử cung, u xơ tử cung, u nang buồng trứng, rối loạn kinh nguyệt và sức khỏe tiền mãn kinh.', '2025-12-19 16:00:43', '2025-12-19 16:00:43'),
(3, 'Hiếm muộn & Vô sinh', 'hiem-muon-vo-sinh', 'Tư vấn sức khỏe sinh sản cặp đôi, khám tìm nguyên nhân chậm con. Thực hiện các kỹ thuật hỗ trợ sinh sản như bơm tinh trùng (IUI) và tư vấn thụ tinh ống nghiệm (IVF).', '2025-12-19 16:00:43', '2025-12-19 16:00:43'),
(4, 'Siêu âm & Chẩn đoán hình ảnh', 'sieu-am-chan-doan-hinh-anh', 'Thực hiện các kỹ thuật chẩn đoán hình ảnh hiện đại: Siêu âm thai 4D/5D hình thái học, siêu âm Doppler màu tim thai, siêu âm tuyến vú và đầu dò âm đạo.', '2025-12-19 16:00:43', '2025-12-19 16:00:43'),
(5, 'Sàng lọc trước sinh', 'sang-loc-truoc-sinh', 'Chuyên khoa Di truyền học. Thực hiện và tư vấn các xét nghiệm NIPT, Double Test, Triple Test, chọc ối để phát hiện sớm các dị tật bẩm sinh ở thai nhi.', '2025-12-19 16:00:43', '2025-12-19 16:00:43'),
(6, 'Kế hoạch hóa gia đình', 'ke-hoach-hoa-gia-dinh', 'Tư vấn và thực hiện các biện pháp tránh thai an toàn, hiện đại: Cấy que tránh thai Implanon, đặt vòng nội tiết Mirena, tiêm thuốc tránh thai.', '2025-12-19 16:00:43', '2025-12-19 16:00:43'),
(7, 'Sàn chậu & Thẩm mỹ nữ', 'san-chau-tham-my-nu', 'Điều trị các bệnh lý sa tạng chậu, són tiểu sau sinh. Thực hiện các dịch vụ thẩm mỹ, trẻ hóa vùng kín và phục hồi chức năng sàn chậu cho phụ nữ sau sinh.', '2025-12-19 16:00:43', '2025-12-19 16:00:43'),
(8, 'Xét nghiệm', 'xet-nghiem', 'Trung tâm xét nghiệm thực hiện các chỉ định cận lâm sàng: Huyết học, Sinh hóa, Miễn dịch, Vi sinh và Nội tiết tố phục vụ cho chẩn đoán của bác sĩ lâm sàng.', '2025-12-19 16:00:43', '2025-12-19 16:00:43');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `chuyen_khoa_dich_vu`
--

CREATE TABLE `chuyen_khoa_dich_vu` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `chuyen_khoa_id` bigint(20) UNSIGNED NOT NULL,
  `dich_vu_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `chuyen_khoa_dich_vu`
--

INSERT INTO `chuyen_khoa_dich_vu` (`id`, `chuyen_khoa_id`, `dich_vu_id`, `created_at`, `updated_at`) VALUES
(1, 1, 1, NULL, NULL),
(2, 2, 2, NULL, NULL),
(3, 3, 3, NULL, NULL),
(4, 6, 3, NULL, NULL),
(5, 3, 4, NULL, NULL),
(6, 1, 5, NULL, NULL),
(7, 4, 5, NULL, NULL),
(8, 1, 6, NULL, NULL),
(9, 2, 6, NULL, NULL),
(10, 4, 6, NULL, NULL),
(11, 3, 6, NULL, NULL),
(12, 2, 7, NULL, NULL),
(13, 4, 7, NULL, NULL),
(14, 5, 8, NULL, NULL),
(15, 1, 8, NULL, NULL),
(16, 2, 9, NULL, NULL),
(17, 5, 9, NULL, NULL),
(18, 1, 10, NULL, NULL),
(19, 3, 10, NULL, NULL),
(20, 8, 10, NULL, NULL),
(21, 6, 11, NULL, NULL),
(22, 3, 12, NULL, NULL),
(23, 2, 13, NULL, NULL),
(24, 8, 13, NULL, NULL),
(25, 2, 14, NULL, NULL),
(26, 4, 14, NULL, NULL),
(27, 8, 14, NULL, NULL),
(28, 3, 15, NULL, NULL),
(29, 8, 15, NULL, NULL),
(30, 3, 16, NULL, NULL),
(31, 1, 16, NULL, NULL),
(32, 8, 16, NULL, NULL),
(33, 3, 17, NULL, NULL),
(34, 8, 17, NULL, NULL),
(35, 2, 18, NULL, NULL),
(36, 8, 18, NULL, NULL),
(37, 1, 19, NULL, NULL),
(38, 8, 19, NULL, NULL),
(39, 7, 20, NULL, NULL),
(40, 7, 21, NULL, NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `chuyen_khoa_loai_sieu_am`
--

CREATE TABLE `chuyen_khoa_loai_sieu_am` (
  `chuyen_khoa_id` bigint(20) UNSIGNED NOT NULL,
  `loai_sieu_am_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `chuyen_khoa_loai_sieu_am`
--

INSERT INTO `chuyen_khoa_loai_sieu_am` (`chuyen_khoa_id`, `loai_sieu_am_id`) VALUES
(1, 1),
(1, 2),
(1, 3),
(1, 4),
(1, 5),
(1, 6),
(1, 12),
(2, 7),
(2, 8),
(2, 10),
(2, 11),
(3, 9),
(3, 10),
(4, 1),
(4, 2),
(4, 3),
(4, 4),
(4, 5),
(4, 6),
(4, 7),
(4, 8),
(4, 9),
(4, 10),
(4, 11),
(4, 12),
(5, 4),
(5, 5),
(5, 6);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `chuyen_khoa_loai_xet_nghiem`
--

CREATE TABLE `chuyen_khoa_loai_xet_nghiem` (
  `chuyen_khoa_id` bigint(20) UNSIGNED NOT NULL,
  `loai_xet_nghiem_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `chuyen_khoa_loai_xet_nghiem`
--

INSERT INTO `chuyen_khoa_loai_xet_nghiem` (`chuyen_khoa_id`, `loai_xet_nghiem_id`) VALUES
(1, 1),
(1, 3),
(1, 4),
(1, 7),
(1, 8),
(1, 9),
(1, 10),
(2, 5),
(2, 7),
(2, 9),
(2, 11),
(3, 1),
(3, 2),
(3, 3),
(5, 11),
(8, 1),
(8, 2),
(8, 3),
(8, 4),
(8, 5),
(8, 6),
(8, 7),
(8, 8),
(8, 9),
(8, 10),
(8, 11);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `chuyen_khoa_loai_x_quang`
--

CREATE TABLE `chuyen_khoa_loai_x_quang` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `chuyen_khoa_id` bigint(20) UNSIGNED NOT NULL,
  `loai_x_quang_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `chuyen_khoa_loai_x_quang`
--

INSERT INTO `chuyen_khoa_loai_x_quang` (`id`, `chuyen_khoa_id`, `loai_x_quang_id`, `created_at`, `updated_at`) VALUES
(1, 4, 1, NULL, NULL),
(2, 4, 2, NULL, NULL),
(3, 4, 3, NULL, NULL),
(4, 4, 4, NULL, NULL),
(5, 4, 5, NULL, NULL),
(6, 4, 6, NULL, NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `conversations`
--

CREATE TABLE `conversations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `benh_nhan_id` bigint(20) UNSIGNED NOT NULL,
  `bac_si_id` bigint(20) UNSIGNED NOT NULL,
  `lich_hen_id` bigint(20) UNSIGNED DEFAULT NULL,
  `tieu_de` varchar(255) DEFAULT NULL,
  `trang_thai` enum('Đang hoạt động','Đã đóng','Bị khóa') NOT NULL DEFAULT 'Đang hoạt động',
  `last_message_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `conversations`
--

INSERT INTO `conversations` (`id`, `benh_nhan_id`, `bac_si_id`, `lich_hen_id`, `tieu_de`, `trang_thai`, `last_message_at`, `created_at`, `updated_at`) VALUES
(1, 23, 4, 1, 'Tư vấn với BS.CKI Nguyễn Thanh Vân', 'Đang hoạt động', '2025-12-20 01:38:35', '2025-12-20 01:38:05', '2025-12-20 01:38:35');

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

--
-- Đang đổ dữ liệu cho bảng `coupons`
--

INSERT INTO `coupons` (`id`, `ma_giam_gia`, `ten`, `mo_ta`, `loai`, `gia_tri`, `giam_toi_da`, `don_toi_thieu`, `ngay_bat_dau`, `ngay_ket_thuc`, `so_lan_su_dung_toi_da`, `so_lan_da_su_dung`, `kich_hoat`, `created_at`, `updated_at`) VALUES
(1, 'CHAOBANMOI', 'Giảm 10% cho khách mới', 'Giảm 10% cho khách mới', 'phan_tram', 10.00, NULL, 0.00, '2025-11-19', '2026-06-19', NULL, 0, 1, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(2, 'KHAMTHAI', 'Giảm 50k cho gói khám thai', 'Giảm 50k cho gói khám thai', 'tien_mat', 50000.00, NULL, 200000.00, '2025-11-19', '2026-06-19', NULL, 0, 1, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(3, 'TRIAN', 'Ưu đãi cho khách cũ', 'Ưu đãi cho khách cũ', 'phan_tram', 5.00, NULL, 0.00, '2025-11-19', '2026-06-19', NULL, 0, 1, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(4, 'SINHNHAT', 'Quà sinh nhật', 'Quà sinh nhật', 'tien_mat', 100000.00, NULL, 0.00, '2024-12-19', '2027-12-19', NULL, 0, 1, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(5, 'TRONGGIO', 'Giảm 10% giờ vàng', 'Giảm 10% giờ vàng', 'phan_tram', 10.00, NULL, 0.00, '2025-11-19', '2026-03-19', NULL, 0, 1, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(6, 'DICHVU50', 'Giảm 50% dịch vụ chọn lọc', 'Giảm 50% dịch vụ chọn lọc', 'phan_tram', 50.00, NULL, 500000.00, '2025-11-19', '2026-06-19', NULL, 0, 1, '2025-12-19 16:00:46', '2025-12-19 16:00:46');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `danh_gias`
--

CREATE TABLE `danh_gias` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `bac_si_id` bigint(20) UNSIGNED NOT NULL,
  `lich_hen_id` bigint(20) UNSIGNED DEFAULT NULL,
  `rating` tinyint(3) UNSIGNED NOT NULL,
  `noi_dung` text DEFAULT NULL,
  `trang_thai` enum('pending','approved','rejected') NOT NULL DEFAULT 'approved',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `danh_gias`
--

INSERT INTO `danh_gias` (`id`, `user_id`, `bac_si_id`, `lich_hen_id`, `rating`, `noi_dung`, `trang_thai`, `created_at`, `updated_at`) VALUES
(1, 23, 4, 1, 5, 'Bác sĩ quá mát tay, trộm vía mẹ tròn con vuông', 'approved', '2025-12-20 01:37:59', '2025-12-20 01:37:59');

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
(1, 'Cẩm nang Thai kỳ', 'cam-nang-thai-ky', NULL, NULL, NULL, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(2, 'Vô sinh - Hiếm muộn', 'vo-sinh-hiem-muon', NULL, NULL, NULL, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(3, 'Tin tức Y khoa', 'tin-tuc-y-khoa', NULL, NULL, NULL, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(4, 'Hoạt động Phòng khám', 'hoat-dong-phong-kham', NULL, NULL, NULL, '2025-12-19 16:00:46', '2025-12-19 16:00:46');

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
(1, 'Khám Thai định kỳ & Tư vấn dinh dưỡng', 'Khám lâm sàng, đo bề cao tử cung, vòng bụng, nghe tim thai bằng Doppler. Tư vấn dinh dưỡng và lịch tiêm phòng cho mẹ bầu.', 200000.00, 15, '2025-12-19 16:00:43', '2025-12-19 16:00:43'),
(2, 'Khám Phụ khoa tổng quát', 'Kiểm tra cơ quan sinh dục ngoài và trong, phát hiện sớm các bệnh lý viêm nhiễm, u xơ, u nang. Bao gồm phí dụng cụ dùng một lần.', 250000.00, 20, '2025-12-19 16:00:43', '2025-12-19 16:00:43'),
(3, 'Khám & Tư vấn sức khỏe Tiền hôn nhân', 'Gói khám sức khỏe sinh sản tổng quát cho cả vợ và chồng trước khi cưới. Tư vấn di truyền và chuẩn bị mang thai an toàn.', 800000.00, 30, '2025-12-19 16:00:43', '2025-12-19 16:00:43'),
(4, 'Tư vấn chuyên sâu Vô sinh - Hiếm muộn', 'Bác sĩ chuyên khoa xem hồ sơ cũ, tư vấn phác đồ điều trị và chỉ định các xét nghiệm chuyên sâu cần thiết cho các cặp vợ chồng mong con.', 500000.00, 45, '2025-12-19 16:00:43', '2025-12-19 16:00:43'),
(5, 'Siêu âm thai 5D (Hình thái học)', 'Công nghệ dựng hình 5D sắc nét, khảo sát dị tật thai nhi toàn diện (mặt, tim, chi...). Tặng kèm file video và hình ảnh bé qua Zalo/Email.', 500000.00, 30, '2025-12-19 16:00:43', '2025-12-19 16:00:43'),
(6, 'Siêu âm đầu dò âm đạo (Transvaginal)', 'Kỹ thuật siêu âm qua đường âm đạo giúp quan sát tử cung, buồng trứng rõ nét nhất. Phát hiện thai sớm, u nang buồng trứng, đa nang.', 300000.00, 15, '2025-12-19 16:00:43', '2025-12-19 16:00:43'),
(7, 'Siêu âm Tuyến vú 2 bên', 'Tầm soát nang vú, nhân xơ tuyến vú lành tính/ác tính bằng sóng siêu âm. Không đau, không xâm lấn.', 300000.00, 15, '2025-12-19 16:00:43', '2025-12-19 16:00:43'),
(8, 'Sàng lọc trước sinh không xâm lấn (NIPT - 23 cặp NST)', 'Sàng lọc toàn bộ 23 cặp nhiễm sắc thể từ máu mẹ, phát hiện các bất thường vi mất đoạn với độ chính xác >99%. An toàn tuyệt đối cho thai nhi.', 6500000.00, 10, '2025-12-19 16:00:43', '2025-12-19 16:00:43'),
(9, 'Xét nghiệm tế bào cổ tử cung (Pap Smear - ThinPrep)', 'Tầm soát ung thư cổ tử cung phương pháp mới. Phát hiện sớm các biến đổi tế bào tiền ung thư. Khuyên dùng định kỳ hàng năm.', 400000.00, 10, '2025-12-19 16:00:43', '2025-12-19 16:00:43'),
(10, 'Định lượng Beta-hCG (Máu)', 'Xét nghiệm máu chẩn đoán thai sớm chính xác nhất (có thể phát hiện trước khi chậm kinh). Theo dõi sự phát triển của thai giai đoạn đầu.', 150000.00, 5, '2025-12-19 16:00:43', '2025-12-19 16:00:43'),
(11, 'Cấy que tránh thai Implanon (3 năm)', 'Que cấy tránh thai nội tiết (xuất xứ Mỹ/Châu Âu), hiệu quả ngừa thai lên đến 3 năm. Thủ thuật nhanh, không đau (có gây tê tại chỗ).', 3200000.00, 20, '2025-12-19 16:00:43', '2025-12-19 16:00:43'),
(12, 'Bơm tinh trùng vào buồng tử cung (IUI)', 'Kỹ thuật lọc rửa và bơm tinh trùng vào buồng tử cung. Hỗ trợ sinh sản cho các cặp vợ chồng hiếm muộn nhẹ, tinh trùng yếu.', 3500000.00, 60, '2025-12-19 16:00:43', '2025-12-19 16:00:43'),
(13, 'Gói Tầm soát Ung thư Cổ tử cung (Cơ bản)', 'Bao gồm: Khám phụ khoa, Soi cổ tử cung kỹ thuật số, Xét nghiệm Pap Smear và Test nhanh dịch âm đạo.', 1200000.00, 30, '2025-12-19 16:00:43', '2025-12-19 16:00:43'),
(14, 'Gói Khám Phụ khoa Tổng quát (VIP)', 'Khám lâm sàng, Siêu âm đầu dò, Soi tươi dịch âm đạo, Tầm soát ung thư (HPV + Pap Smear), Siêu âm tuyến vú.', 2500000.00, 45, '2025-12-19 16:00:43', '2025-12-19 16:00:43'),
(15, 'Xét nghiệm dự trữ buồng trứng (AMH)', 'Chỉ số quan trọng nhất để đánh giá khả năng sinh sản của phụ nữ. Bắt buộc thực hiện trước khi làm IVF/IUI.', 800000.00, 10, '2025-12-19 16:00:43', '2025-12-19 16:00:43'),
(16, 'Bộ xét nghiệm Nội tiết tố nữ (6 chỉ số)', 'Kiểm tra 6 chỉ số: FSH, LH, Estradiol, Prolactin, Progesterone, Testosterone. Đánh giá rối loạn kinh nguyệt và rụng trứng.', 1500000.00, 15, '2025-12-19 16:00:43', '2025-12-19 16:00:43'),
(17, 'Tinh dịch đồ', 'Phân tích số lượng, khả năng di động và hình dạng của tinh trùng. Bước đầu tiên khám vô sinh nam.', 350000.00, 60, '2025-12-19 16:00:43', '2025-12-19 16:00:43'),
(18, 'Xét nghiệm Bệnh lây truyền (STD Panel)', 'Xét nghiệm PCR đa mồi phát hiện 9 tác nhân lây qua đường tình dục: Lậu, Giang mai, Chlamydia, Nấm, Trichomonas...', 1800000.00, 20, '2025-12-19 16:00:43', '2025-12-19 16:00:43'),
(19, 'Xét nghiệm Rubella (IgM/IgG)', 'Tầm soát kháng thể Rubella cho phụ nữ chuẩn bị mang thai hoặc đang mang thai 3 tháng đầu.', 300000.00, 10, '2025-12-19 16:00:43', '2025-12-19 16:00:43'),
(20, 'Tập vật lý trị liệu sàn chậu (1 buổi)', 'Bài tập chuyên sâu với máy tập Biofeedback giúp co hồi cơ sàn chậu, chống sa tử cung và són tiểu sau sinh.', 400000.00, 45, '2025-12-19 16:00:43', '2025-12-19 16:00:43'),
(21, 'Làm hồng & Trẻ hóa vùng kín (Laser)', 'Sử dụng công nghệ Laser CO2 Fractional giúp se khít, làm hồng và trẻ hóa vùng kín không phẫu thuật.', 5000000.00, 60, '2025-12-19 16:00:43', '2025-12-19 16:00:43');

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
  `trang_thai_thanh_toan` enum('Chưa thanh toán','Đang thanh toán','Đã thanh toán','Hoàn tiền') DEFAULT 'Chưa thanh toán',
  `phuong_thuc_thanh_toan` varchar(255) DEFAULT NULL,
  `thanh_toan_at` timestamp NULL DEFAULT NULL,
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
  `nguoi_cap_thuoc_id` bigint(20) UNSIGNED DEFAULT NULL,
  `bac_si_id` bigint(20) UNSIGNED DEFAULT NULL,
  `lich_hen_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ghi_chu` varchar(255) DEFAULT NULL,
  `ghi_chu_cap_thuoc` varchar(1000) DEFAULT NULL,
  `trang_thai` varchar(255) DEFAULT NULL,
  `ngay_cap_thuoc` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `don_thuocs`
--

INSERT INTO `don_thuocs` (`id`, `benh_an_id`, `user_id`, `nguoi_cap_thuoc_id`, `bac_si_id`, `lich_hen_id`, `ghi_chu`, `ghi_chu_cap_thuoc`, `trang_thai`, `ngay_cap_thuoc`, `created_at`, `updated_at`) VALUES
(1, 1, 23, 25, 4, 1, 'Khám Gói Khám Phụ khoa Tổng quát (VIP)', '- Theo dõi thai kỳ, sức khỏe', 'da_cap_thuoc', '2025-12-20 09:02:43', '2025-12-19 17:49:35', '2025-12-20 02:02:43');

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

--
-- Đang đổ dữ liệu cho bảng `don_thuoc_items`
--

INSERT INTO `don_thuoc_items` (`id`, `don_thuoc_id`, `thuoc_id`, `so_luong`, `lieu_dung`, `cach_dung`, `created_at`, `updated_at`) VALUES
(1, 1, 142, 1, '2 viên/lần', 'Uống sau ăn', '2025-12-19 17:49:35', '2025-12-19 17:49:35');

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

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `hoa_dons`
--

CREATE TABLE `hoa_dons` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `ma_hoa_don` varchar(255) DEFAULT NULL,
  `lich_hen_id` bigint(20) UNSIGNED DEFAULT NULL,
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
  `updated_at` timestamp NULL DEFAULT NULL,
  `coupon_id` bigint(20) UNSIGNED DEFAULT NULL,
  `giam_gia` decimal(12,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `hoa_dons`
--

INSERT INTO `hoa_dons` (`id`, `ma_hoa_don`, `lich_hen_id`, `user_id`, `tong_tien`, `so_tien_da_thanh_toan`, `so_tien_con_lai`, `so_tien_da_hoan`, `trang_thai`, `status`, `phuong_thuc`, `ghi_chu`, `created_at`, `updated_at`, `coupon_id`, `giam_gia`) VALUES
(1, 'INV-20251220-00001', 1, 23, 2870000.00, 2870000.00, 0.00, 0.00, 'Đã thanh toán', 'paid', 'MOMO', 'Khám hoàn thành - ', '2025-12-19 17:46:19', '2025-12-20 01:37:05', NULL, 0.00),
(2, 'INV-20251220-00002', 2, 23, 2500000.00, 0.00, 2500000.00, 0.00, 'Chưa thanh toán', 'unpaid', NULL, 'Tạo từ yêu cầu tái khám #2', '2025-12-20 04:12:45', '2025-12-20 04:12:45', NULL, 0.00);

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
(1, 'default', '{\"uuid\":\"b9294f6b-ce60-45c8-868c-79cd30934b6b\",\"displayName\":\"App\\\\Mail\\\\HoaDonDaThanhToan\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":15:{s:8:\\\"mailable\\\";O:26:\\\"App\\\\Mail\\\\HoaDonDaThanhToan\\\":3:{s:6:\\\"hoaDon\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:17:\\\"App\\\\Models\\\\HoaDon\\\";s:2:\\\"id\\\";i:1;s:9:\\\"relations\\\";a:2:{i:0;s:7:\\\"lichHen\\\";i:1;s:4:\\\"user\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:18:\\\"tn822798@gmail.com\\\";}}s:6:\\\"mailer\\\";s:4:\\\"smtp\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:3:\\\"job\\\";N;}\"}}', 0, NULL, 1766166442, 1766166442),
(2, 'default', '{\"uuid\":\"a94cb835-461e-42ad-b488-72108ebe8b61\",\"displayName\":\"App\\\\Mail\\\\HoaDonDaThanhToan\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":15:{s:8:\\\"mailable\\\";O:26:\\\"App\\\\Mail\\\\HoaDonDaThanhToan\\\":3:{s:6:\\\"hoaDon\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:17:\\\"App\\\\Models\\\\HoaDon\\\";s:2:\\\"id\\\";i:1;s:9:\\\"relations\\\";a:2:{i:0;s:7:\\\"lichHen\\\";i:1;s:4:\\\"user\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:18:\\\"tn822798@gmail.com\\\";}}s:6:\\\"mailer\\\";s:4:\\\"smtp\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:3:\\\"job\\\";N;}\"}}', 0, NULL, 1766168427, 1766168427),
(3, 'default', '{\"uuid\":\"bfb4d9e9-457d-4b1b-a648-7ff3a79dc9cd\",\"displayName\":\"App\\\\Mail\\\\HoaDonDaThanhToan\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":15:{s:8:\\\"mailable\\\";O:26:\\\"App\\\\Mail\\\\HoaDonDaThanhToan\\\":3:{s:6:\\\"hoaDon\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:17:\\\"App\\\\Models\\\\HoaDon\\\";s:2:\\\"id\\\";i:1;s:9:\\\"relations\\\";a:2:{i:0;s:7:\\\"lichHen\\\";i:1;s:4:\\\"user\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:18:\\\"tn822798@gmail.com\\\";}}s:6:\\\"mailer\\\";s:4:\\\"smtp\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:3:\\\"job\\\";N;}\"}}', 0, NULL, 1766194625, 1766194625);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `lich_hens`
--

CREATE TABLE `lich_hens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `bac_si_id` bigint(20) UNSIGNED NOT NULL,
  `dich_vu_id` bigint(20) UNSIGNED NOT NULL,
  `ten_benh_nhan` varchar(255) DEFAULT NULL,
  `sdt_benh_nhan` varchar(255) DEFAULT NULL,
  `email_benh_nhan` varchar(255) DEFAULT NULL,
  `ngay_sinh_benh_nhan` date DEFAULT NULL,
  `tong_tien` decimal(12,2) NOT NULL DEFAULT 0.00,
  `ngay_hen` date NOT NULL,
  `thoi_gian_hen` time NOT NULL,
  `ghi_chu` text DEFAULT NULL,
  `trang_thai` varchar(255) NOT NULL DEFAULT 'Chờ xác nhận',
  `checked_in_at` timestamp NULL DEFAULT NULL,
  `checked_in_by` bigint(20) UNSIGNED DEFAULT NULL,
  `thoi_gian_bat_dau_kham` timestamp NULL DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
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

INSERT INTO `lich_hens` (`id`, `user_id`, `bac_si_id`, `dich_vu_id`, `ten_benh_nhan`, `sdt_benh_nhan`, `email_benh_nhan`, `ngay_sinh_benh_nhan`, `tong_tien`, `ngay_hen`, `thoi_gian_hen`, `ghi_chu`, `trang_thai`, `checked_in_at`, `checked_in_by`, `thoi_gian_bat_dau_kham`, `completed_at`, `created_at`, `updated_at`, `payment_status`, `payment_method`, `paid_at`, `cancelled_by`, `cancelled_at`) VALUES
(1, 23, 4, 14, NULL, NULL, NULL, NULL, 2500000.00, '2025-12-20', '01:00:00', NULL, 'Hoàn thành', '2025-12-19 17:48:11', 25, '2025-12-19 17:48:37', '2025-12-19 17:58:52', '2025-12-19 17:46:19', '2025-12-20 01:37:05', 'Đã thanh toán', 'MOMO', '2025-12-20 01:37:05', NULL, NULL),
(2, 23, 4, 14, NULL, NULL, NULL, NULL, 2500000.00, '2025-12-23', '13:40:00', 'Đặt lịch tái khám #2', 'Đã xác nhận', NULL, NULL, NULL, NULL, '2025-12-20 04:12:45', '2025-12-20 04:12:45', 'Chưa thanh toán', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `lich_lam_viecs`
--

CREATE TABLE `lich_lam_viecs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `bac_si_id` bigint(20) UNSIGNED NOT NULL,
  `phong_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ngay_trong_tuan` tinyint(4) DEFAULT NULL,
  `thoi_gian_bat_dau` time DEFAULT NULL,
  `thoi_gian_ket_thuc` time DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `lich_lam_viecs`
--

INSERT INTO `lich_lam_viecs` (`id`, `bac_si_id`, `phong_id`, `ngay_trong_tuan`, `thoi_gian_bat_dau`, `thoi_gian_ket_thuc`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, '07:30:00', '11:30:00', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(2, 1, 1, 1, '13:30:00', '17:00:00', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(3, 1, 1, 2, '07:30:00', '11:30:00', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(4, 1, 1, 2, '13:30:00', '17:00:00', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(5, 1, 1, 3, '07:30:00', '11:30:00', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(6, 1, 1, 3, '13:30:00', '17:00:00', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(7, 1, 1, 4, '07:30:00', '11:30:00', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(8, 1, 1, 4, '13:30:00', '17:00:00', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(9, 1, 1, 5, '07:30:00', '11:30:00', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(10, 1, 1, 5, '13:30:00', '17:00:00', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(11, 1, 1, 6, '07:30:00', '11:30:00', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(12, 3, 4, 1, '07:30:00', '11:30:00', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(13, 3, 4, 1, '13:30:00', '17:00:00', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(14, 3, 4, 2, '07:30:00', '11:30:00', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(15, 3, 4, 2, '13:30:00', '17:00:00', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(16, 3, 4, 3, '07:30:00', '11:30:00', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(17, 3, 4, 3, '13:30:00', '17:00:00', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(18, 3, 4, 4, '07:30:00', '11:30:00', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(19, 3, 4, 4, '13:30:00', '17:00:00', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(20, 3, 4, 5, '07:30:00', '11:30:00', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(21, 3, 4, 5, '13:30:00', '17:00:00', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(22, 3, 4, 6, '07:30:00', '11:30:00', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(23, 5, 7, 1, '07:30:00', '11:30:00', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(24, 5, 7, 1, '13:30:00', '17:00:00', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(25, 5, 7, 2, '07:30:00', '11:30:00', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(26, 5, 7, 2, '13:30:00', '17:00:00', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(27, 5, 7, 3, '07:30:00', '11:30:00', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(28, 5, 7, 3, '13:30:00', '17:00:00', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(29, 5, 7, 4, '07:30:00', '11:30:00', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(30, 5, 7, 4, '13:30:00', '17:00:00', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(31, 5, 7, 5, '07:30:00', '11:30:00', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(32, 5, 7, 5, '13:30:00', '17:00:00', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(33, 5, 7, 6, '07:30:00', '11:30:00', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(34, 11, 9, 1, '07:30:00', '11:30:00', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(35, 11, 9, 1, '13:30:00', '17:00:00', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(36, 11, 9, 2, '07:30:00', '11:30:00', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(37, 11, 9, 2, '13:30:00', '17:00:00', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(38, 11, 9, 3, '07:30:00', '11:30:00', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(39, 11, 9, 3, '13:30:00', '17:00:00', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(40, 11, 9, 4, '07:30:00', '11:30:00', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(41, 11, 9, 4, '13:30:00', '17:00:00', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(42, 11, 9, 5, '07:30:00', '11:30:00', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(43, 11, 9, 5, '13:30:00', '17:00:00', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(44, 11, 9, 6, '07:30:00', '11:30:00', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(45, 2, 2, 1, '07:30:00', '11:30:00', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(46, 2, 2, 1, '13:30:00', '17:00:00', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(47, 2, 2, 1, '17:30:00', '20:30:00', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(48, 2, 2, 3, '07:30:00', '11:30:00', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(49, 2, 2, 3, '13:30:00', '17:00:00', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(50, 2, 2, 3, '17:30:00', '20:30:00', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(51, 2, 2, 5, '07:30:00', '11:30:00', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(52, 2, 2, 5, '13:30:00', '17:00:00', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(53, 2, 2, 5, '17:30:00', '20:30:00', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(54, 2, 2, 2, '07:30:00', '11:30:00', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(55, 2, 2, 2, '13:30:00', '17:00:00', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(56, 2, 2, 4, '07:30:00', '11:30:00', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(57, 2, 2, 4, '13:30:00', '17:00:00', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(58, 2, 2, 6, '07:30:00', '11:30:00', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(59, 2, 2, 6, '13:30:00', '17:00:00', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(60, 4, 4, 1, '07:30:00', '11:30:00', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(61, 4, 4, 1, '13:30:00', '17:00:00', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(62, 4, 4, 1, '17:30:00', '20:30:00', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(63, 4, 4, 3, '07:30:00', '11:30:00', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(64, 4, 4, 3, '13:30:00', '17:00:00', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(65, 4, 4, 3, '17:30:00', '20:30:00', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(66, 4, 4, 5, '07:30:00', '11:30:00', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(67, 4, 4, 5, '13:30:00', '17:00:00', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(68, 4, 4, 5, '17:30:00', '20:30:00', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(69, 4, 4, 2, '07:30:00', '11:30:00', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(70, 4, 4, 2, '13:30:00', '17:00:00', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(71, 4, 4, 4, '07:30:00', '11:30:00', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(72, 4, 4, 4, '13:30:00', '17:00:00', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(73, 4, 4, 6, '07:30:00', '11:30:00', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(74, 4, 4, 6, '13:30:00', '17:00:00', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(75, 6, 8, 1, '07:30:00', '11:30:00', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(76, 6, 8, 1, '13:30:00', '17:00:00', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(77, 6, 8, 1, '17:30:00', '20:30:00', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(78, 6, 8, 3, '07:30:00', '11:30:00', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(79, 6, 8, 3, '13:30:00', '17:00:00', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(80, 6, 8, 3, '17:30:00', '20:30:00', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(81, 6, 8, 5, '07:30:00', '11:30:00', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(82, 6, 8, 5, '13:30:00', '17:00:00', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(83, 6, 8, 5, '17:30:00', '20:30:00', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(84, 6, 8, 2, '07:30:00', '11:30:00', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(85, 6, 8, 2, '13:30:00', '17:00:00', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(86, 6, 8, 4, '07:30:00', '11:30:00', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(87, 6, 8, 4, '13:30:00', '17:00:00', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(88, 6, 8, 6, '07:30:00', '11:30:00', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(89, 6, 8, 6, '13:30:00', '17:00:00', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(90, 7, 3, 1, '07:30:00', '11:30:00', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(91, 7, 3, 1, '13:30:00', '17:00:00', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(92, 7, 3, 1, '17:30:00', '20:30:00', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(93, 7, 3, 3, '07:30:00', '11:30:00', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(94, 7, 3, 3, '13:30:00', '17:00:00', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(95, 7, 3, 3, '17:30:00', '20:30:00', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(96, 7, 3, 5, '07:30:00', '11:30:00', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(97, 7, 3, 5, '13:30:00', '17:00:00', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(98, 7, 3, 5, '17:30:00', '20:30:00', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(99, 7, 3, 2, '07:30:00', '11:30:00', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(100, 7, 3, 2, '13:30:00', '17:00:00', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(101, 7, 3, 4, '07:30:00', '11:30:00', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(102, 7, 3, 4, '13:30:00', '17:00:00', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(103, 7, 3, 6, '07:30:00', '11:30:00', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(104, 7, 3, 6, '13:30:00', '17:00:00', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(105, 10, 2, 2, '07:30:00', '11:30:00', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(106, 10, 2, 2, '13:30:00', '17:00:00', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(107, 10, 2, 2, '17:30:00', '20:30:00', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(108, 10, 2, 4, '07:30:00', '11:30:00', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(109, 10, 2, 4, '13:30:00', '17:00:00', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(110, 10, 2, 4, '17:30:00', '20:30:00', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(111, 10, 2, 6, '07:30:00', '11:30:00', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(112, 10, 2, 6, '13:30:00', '17:00:00', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(113, 10, 2, 6, '17:30:00', '20:30:00', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(114, 10, 2, 1, '13:30:00', '17:00:00', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(115, 10, 2, 1, '17:30:00', '20:30:00', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(116, 10, 2, 3, '13:30:00', '17:00:00', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(117, 10, 2, 3, '17:30:00', '20:30:00', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(118, 10, 2, 5, '13:30:00', '17:00:00', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(119, 10, 2, 5, '17:30:00', '20:30:00', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(120, 10, 2, 0, '07:30:00', '11:30:00', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(121, 9, 5, 2, '07:30:00', '11:30:00', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(122, 9, 5, 2, '13:30:00', '17:00:00', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(123, 9, 5, 2, '17:30:00', '20:30:00', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(124, 9, 5, 4, '07:30:00', '11:30:00', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(125, 9, 5, 4, '13:30:00', '17:00:00', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(126, 9, 5, 4, '17:30:00', '20:30:00', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(127, 9, 5, 6, '07:30:00', '11:30:00', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(128, 9, 5, 6, '13:30:00', '17:00:00', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(129, 9, 5, 6, '17:30:00', '20:30:00', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(130, 9, 5, 1, '13:30:00', '17:00:00', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(131, 9, 5, 1, '17:30:00', '20:30:00', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(132, 9, 5, 3, '13:30:00', '17:00:00', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(133, 9, 5, 3, '17:30:00', '20:30:00', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(134, 9, 5, 5, '13:30:00', '17:00:00', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(135, 9, 5, 5, '17:30:00', '20:30:00', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(136, 9, 5, 0, '07:30:00', '11:30:00', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(137, 8, 10, 2, '07:30:00', '11:30:00', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(138, 8, 10, 2, '13:30:00', '17:00:00', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(139, 8, 10, 2, '17:30:00', '20:30:00', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(140, 8, 10, 4, '07:30:00', '11:30:00', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(141, 8, 10, 4, '13:30:00', '17:00:00', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(142, 8, 10, 4, '17:30:00', '20:30:00', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(143, 8, 10, 6, '07:30:00', '11:30:00', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(144, 8, 10, 6, '13:30:00', '17:00:00', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(145, 8, 10, 6, '17:30:00', '20:30:00', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(146, 8, 10, 1, '13:30:00', '17:00:00', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(147, 8, 10, 1, '17:30:00', '20:30:00', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(148, 8, 10, 3, '13:30:00', '17:00:00', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(149, 8, 10, 3, '17:30:00', '20:30:00', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(150, 8, 10, 5, '13:30:00', '17:00:00', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(151, 8, 10, 5, '17:30:00', '20:30:00', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(152, 8, 10, 0, '07:30:00', '11:30:00', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(153, 12, 6, 2, '07:30:00', '11:30:00', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(154, 12, 6, 2, '13:30:00', '17:00:00', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(155, 12, 6, 2, '17:30:00', '20:30:00', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(156, 12, 6, 4, '07:30:00', '11:30:00', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(157, 12, 6, 4, '13:30:00', '17:00:00', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(158, 12, 6, 4, '17:30:00', '20:30:00', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(159, 12, 6, 6, '07:30:00', '11:30:00', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(160, 12, 6, 6, '13:30:00', '17:00:00', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(161, 12, 6, 6, '17:30:00', '20:30:00', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(162, 12, 6, 1, '13:30:00', '17:00:00', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(163, 12, 6, 1, '17:30:00', '20:30:00', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(164, 12, 6, 3, '13:30:00', '17:00:00', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(165, 12, 6, 3, '17:30:00', '20:30:00', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(166, 12, 6, 5, '13:30:00', '17:00:00', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(167, 12, 6, 5, '17:30:00', '20:30:00', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(168, 12, 6, 0, '07:30:00', '11:30:00', '2025-12-19 16:00:46', '2025-12-19 16:00:46');

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
(1, 2, '2025-12-20', '07:30:00', '11:30:00', 'Hội thảo', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(2, 2, '2025-12-20', '13:30:00', '17:00:00', 'Hội thảo', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(3, 7, '2025-12-21', '00:00:00', '23:59:59', 'Việc riêng', '2025-12-19 16:00:46', '2025-12-19 16:00:46');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `loai_sieu_ams`
--

CREATE TABLE `loai_sieu_ams` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `ten` varchar(255) NOT NULL,
  `mo_ta` text DEFAULT NULL,
  `gia_mac_dinh` decimal(12,2) NOT NULL DEFAULT 0.00,
  `thoi_gian_uoc_tinh` int(11) NOT NULL DEFAULT 30,
  `phong_id` bigint(20) UNSIGNED DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `loai_sieu_ams`
--

INSERT INTO `loai_sieu_ams` (`id`, `ten`, `mo_ta`, `gia_mac_dinh`, `thoi_gian_uoc_tinh`, `phong_id`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Siêu âm thai 2D', 'Siêu âm thai thông thường, kiểm tra phát triển thai nhi cơ bản: đo CRL, BPD, FL, AC, HC. Theo dõi nhịp tim thai, nước ối và nhau thai.', 200000.00, 20, 3, 1, '2025-12-19 15:59:05', '2025-12-19 16:00:44'),
(2, 'Siêu âm thai 3D/4D', 'Siêu âm thai 3 chiều, 4 chiều để quan sát rõ hơn hình ảnh thai nhi, khuôn mặt, chi. Ghi lại video động để gia đình lưu niệm.', 500000.00, 30, 3, 1, '2025-12-19 15:59:05', '2025-12-19 16:00:44'),
(3, 'Siêu âm Doppler thai', 'Đánh giá tuần hoàn máu thai: động mạch rốn, động mạch não giữa, tĩnh mạch ống, động mạch tử cung. Phát hiện sớm thiếu oxy thai.', 350000.00, 25, 3, 1, '2025-12-19 15:59:05', '2025-12-19 16:00:44'),
(4, 'Siêu âm hình thái học thai (20-24 tuần)', 'Siêu âm tầm soát dị tật cấu trúc thai nhi giai đoạn 20-24 tuần: tim, não, cột sống, chi, môi, khẩu cái. Chuẩn FMF London.', 600000.00, 45, 3, 1, '2025-12-19 15:59:05', '2025-12-19 16:00:44'),
(5, 'Siêu âm sàng lọc sớm (11-13 tuần)', 'Đo độ mờ da gáy (NT), xương mũi, Doppler tĩnh mạch ống. Ước tính nguy cơ Down, Edward, Patau. Là phần quan trọng của Double Test/Triple Test.', 400000.00, 30, 3, 1, '2025-12-19 15:59:05', '2025-12-19 16:00:44'),
(6, 'Siêu âm tim thai', 'Siêu âm chuyên sâu tim thai, đánh giá 4 buồng, đường ra/vào, van tim. Phát hiện dị tật tim bẩm sinh. Bắt buộc khi có tiền sử gia đình hoặc Double Test bất thường.', 700000.00, 40, 3, 1, '2025-12-19 15:59:05', '2025-12-19 16:00:44'),
(7, 'Siêu âm phụ khoa qua bụng', 'Siêu âm tử cung, buồng trứng qua thành bụng. Thường dùng cho trinh nữ hoặc không thể siêu âm qua âm đạo.', 150000.00, 15, 3, 1, '2025-12-19 15:59:05', '2025-12-19 16:00:44'),
(8, 'Siêu âm tử cung qua đường âm đạo', 'Siêu âm nội soi phụ khoa (Transvaginal), hình ảnh rõ nét hơn. Phát hiện u xơ tử cung, u nang buồng trứng, polyp nội mạc tử cung, viêm nhiễm.', 250000.00, 20, 3, 1, '2025-12-19 15:59:05', '2025-12-19 16:00:44'),
(9, 'Siêu âm đếm noãn', 'Theo dõi phát triển nang trứng trong chu kỳ kinh để dự đoán ngày rụng trứng (Follicular tracking). Cần thiết cho giao hợp có chỉ định hoặc IUI.', 150000.00, 15, 3, 1, '2025-12-19 15:59:05', '2025-12-19 16:00:44'),
(10, 'Siêu âm vòi trứng - SIS', 'Đánh giá độ thông thoáng vòi trứng bằng cách bơm dung dịch muối sinh lý vào buồng tử cung (Saline Infusion Sonography). Thay thế chụp buồng tử cung có thuốc cản quang.', 450000.00, 30, 3, 1, '2025-12-19 15:59:05', '2025-12-19 16:00:44'),
(11, 'Siêu âm vú 2 bên', 'Tầm soát u nang, u xơ, khối đặc tuyến vú. Phân biệt khối lành tính/ác tính. Khuyên làm định kỳ hàng năm cho phụ nữ từ 35 tuổi.', 250000.00, 20, 3, 1, '2025-12-19 15:59:05', '2025-12-19 16:00:44'),
(12, 'Siêu âm hậu sản', 'Kiểm tra tử cung phục hồi sau sinh (6 tuần sau sinh), phát hiện sót rau/màng nhau, nhiễm trùng buồng tử cung.', 180000.00, 20, 3, 1, '2025-12-19 15:59:05', '2025-12-19 16:00:44');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `loai_xet_nghiems`
--

CREATE TABLE `loai_xet_nghiems` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `ten` varchar(255) NOT NULL,
  `ma` varchar(255) DEFAULT NULL,
  `mo_ta` text DEFAULT NULL,
  `thoi_gian_uoc_tinh` int(10) UNSIGNED NOT NULL DEFAULT 30,
  `gia` decimal(12,2) NOT NULL DEFAULT 0.00,
  `phong_id` bigint(20) UNSIGNED DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `loai_xet_nghiems`
--

INSERT INTO `loai_xet_nghiems` (`id`, `ten`, `ma`, `mo_ta`, `thoi_gian_uoc_tinh`, `gia`, `phong_id`, `active`, `created_at`, `updated_at`) VALUES
(1, 'Định lượng Beta-hCG (Máu)', 'XN_BETA_HCG', 'Chẩn đoán thai sớm và theo dõi phát triển thai giai đoạn đầu.', 5, 150000.00, 9, 1, '2025-12-19 16:00:44', '2025-12-19 16:00:44'),
(2, 'Xét nghiệm dự trữ buồng trứng (AMH)', 'XN_AMH', 'Đánh giá dự trữ buồng trứng, hỗ trợ chẩn đoán và điều trị hiếm muộn.', 10, 800000.00, 9, 1, '2025-12-19 16:00:44', '2025-12-19 16:00:44'),
(3, 'Bộ xét nghiệm Nội tiết tố nữ (6 chỉ số)', 'XN_NOI_TIET_TO_NU_6', 'FSH, LH, Estradiol, Prolactin, Progesterone, Testosterone.', 15, 1500000.00, 9, 1, '2025-12-19 16:00:44', '2025-12-19 16:00:44'),
(4, 'Xét nghiệm Rubella (IgM/IgG)', 'XN_RUBELLA_IGM_IGG', 'Tầm soát kháng thể Rubella trước/sớm trong thai kỳ.', 10, 300000.00, 9, 1, '2025-12-19 16:00:44', '2025-12-19 16:00:44'),
(5, 'Xét nghiệm Bệnh lây truyền (STD Panel)', 'XN_STD_PANEL', 'PCR đa mồi sàng lọc các tác nhân lây qua đường tình dục.', 20, 1800000.00, 9, 1, '2025-12-19 16:00:44', '2025-12-19 16:00:44'),
(6, 'Công thức máu (CBC)', 'XN_CONG_THUC_MAU', 'Đánh giá hồng cầu, bạch cầu, tiểu cầu; hỗ trợ chẩn đoán thiếu máu/viêm.', 10, 120000.00, 9, 1, '2025-12-19 16:00:44', '2025-12-19 16:00:44'),
(7, 'Tổng phân tích nước tiểu (10 thông số)', 'XN_TONG_PHAN_TICH_NUOC_TIEU', 'Sàng lọc viêm tiết niệu, đường huyết niệu, protein niệu...', 10, 80000.00, 9, 1, '2025-12-19 16:00:44', '2025-12-19 16:00:44'),
(8, 'Đường huyết lúc đói', 'XN_DUONG_HUYET_LUC_DOI', 'Tầm soát rối loạn đường huyết/đái tháo đường.', 10, 70000.00, 9, 1, '2025-12-19 16:00:44', '2025-12-19 16:00:44'),
(9, 'HIV Ab/Ag (test nhanh)', 'XN_HIV_AB', 'Sàng lọc HIV Ab/Ag theo quy trình chuẩn an toàn.', 15, 120000.00, 9, 1, '2025-12-19 16:00:44', '2025-12-19 16:00:44'),
(10, 'HBsAg (Viêm gan B)', 'XN_HBSAG', 'Sàng lọc viêm gan B, đặc biệt quan trọng trong thai kỳ.', 15, 120000.00, 9, 1, '2025-12-19 16:00:44', '2025-12-19 16:00:44'),
(11, 'Xét nghiệm tế bào cổ tử cung (Pap Smear - ThinPrep)', 'XN_PAP_SMEAR_THINPREP', 'Tầm soát ung thư cổ tử cung, phát hiện sớm biến đổi tế bào.', 10, 400000.00, 9, 1, '2025-12-19 16:00:44', '2025-12-19 16:00:44');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `loai_x_quangs`
--

CREATE TABLE `loai_x_quangs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `ten` varchar(255) NOT NULL,
  `ma` varchar(255) DEFAULT NULL,
  `mo_ta` text DEFAULT NULL,
  `thoi_gian_uoc_tinh` int(10) UNSIGNED NOT NULL DEFAULT 15,
  `gia` decimal(12,2) NOT NULL DEFAULT 0.00,
  `phong_id` bigint(20) UNSIGNED DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `loai_x_quangs`
--

INSERT INTO `loai_x_quangs` (`id`, `ten`, `ma`, `mo_ta`, `thoi_gian_uoc_tinh`, `gia`, `phong_id`, `active`, `created_at`, `updated_at`) VALUES
(1, 'X-Quang ngực thẳng', 'XQ_NGUC_THANG', 'Đánh giá phổi, tim, lồng ngực; hỗ trợ chẩn đoán viêm phổi, tràn dịch màng phổi, bất thường tim phổi.', 10, 180000.00, 3, 1, '2025-12-20 01:24:27', '2025-12-20 01:24:27'),
(2, 'X-Quang cột sống cổ', 'XQ_COT_SONG_CO', 'Đánh giá thoái hóa, chấn thương, lệch trục cột sống cổ.', 15, 220000.00, 3, 1, '2025-12-20 01:24:27', '2025-12-20 01:24:27'),
(3, 'X-Quang cột sống thắt lưng', 'XQ_COT_SONG_LUNG', 'Đánh giá thoái hóa, trượt đốt sống, chấn thương cột sống thắt lưng.', 15, 250000.00, 3, 1, '2025-12-20 01:24:27', '2025-12-20 01:24:27'),
(4, 'X-Quang khung chậu', 'XQ_KHUNG_CHAU', 'Đánh giá xương chậu, khớp háng; hỗ trợ chẩn đoán chấn thương, thoái hóa.', 15, 240000.00, 3, 1, '2025-12-20 01:24:27', '2025-12-20 01:24:27'),
(5, 'X-Quang bàn tay', 'XQ_BAN_TAY', 'Đánh giá gãy xương, trật khớp, thoái hóa khớp bàn tay.', 10, 150000.00, 3, 1, '2025-12-20 01:24:27', '2025-12-20 01:24:27'),
(6, 'X-Quang bàn chân', 'XQ_BAN_CHAN', 'Đánh giá gãy xương, tổn thương khớp bàn chân.', 10, 150000.00, 3, 1, '2025-12-20 01:24:27', '2025-12-20 01:24:27');

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
(1, NULL, 'tn822798@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'failed', 'invalid_credentials', '2025-12-19 16:11:30', '2025-12-19 16:11:30'),
(2, 4, 'vannguyen@vietcare.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'failed', 'invalid_credentials', '2025-12-19 16:12:29', '2025-12-19 16:12:29'),
(3, 4, 'vannguyen@vietcare.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'success', NULL, '2025-12-19 16:12:42', '2025-12-19 16:12:42'),
(4, NULL, 'Admin@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', 'failed', 'invalid_credentials', '2025-12-19 16:13:17', '2025-12-19 16:13:17'),
(5, NULL, 'henvaemhen@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', 'failed', 'invalid_credentials', '2025-12-19 16:14:02', '2025-12-19 16:14:02'),
(6, NULL, 'henvaemhen@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', 'failed', 'invalid_credentials', '2025-12-19 16:17:06', '2025-12-19 16:17:06'),
(7, 24, 'Admin@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', 'success', NULL, '2025-12-19 16:18:13', '2025-12-19 16:18:13'),
(8, 24, 'Admin@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', 'success', NULL, '2025-12-19 16:18:58', '2025-12-19 16:18:58'),
(9, 24, 'Admin@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', 'success', NULL, '2025-12-19 16:27:01', '2025-12-19 16:27:01'),
(10, 23, 'tn822798@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'success', NULL, '2025-12-19 16:37:10', '2025-12-19 16:37:10'),
(11, 25, 'henvaemhen@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', 'success', NULL, '2025-12-19 16:39:08', '2025-12-19 16:39:08'),
(12, 23, 'tn822798@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'success', NULL, '2025-12-20 01:08:21', '2025-12-20 01:08:21'),
(13, 4, 'vannguyen@vietcare.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'success', NULL, '2025-12-20 01:08:39', '2025-12-20 01:08:39'),
(14, 24, 'Admin@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', 'success', NULL, '2025-12-20 01:10:09', '2025-12-20 01:10:09'),
(15, 25, 'henvaemhen@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', 'success', NULL, '2025-12-20 01:11:38', '2025-12-20 01:11:38');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `messages`
--

CREATE TABLE `messages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `conversation_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `noi_dung` text DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `file_name` varchar(255) DEFAULT NULL,
  `file_type` varchar(255) DEFAULT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `messages`
--

INSERT INTO `messages` (`id`, `conversation_id`, `user_id`, `noi_dung`, `file_path`, `file_name`, `file_type`, `is_read`, `read_at`, `created_at`, `updated_at`) VALUES
(1, 1, 23, 'Bác ơi', NULL, NULL, NULL, 1, '2025-12-20 01:38:31', '2025-12-20 01:38:10', '2025-12-20 01:38:31'),
(2, 1, 4, 'sao đây', NULL, NULL, NULL, 0, NULL, '2025-12-20 01:38:35', '2025-12-20 01:38:35');

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
(5, '2025_10_08_150521_create_bac_sis_table', 2),
(6, '2025_10_08_160313_create_dich_vus_table', 2),
(7, '2025_10_08_170625_create_lich_hens_table', 2),
(8, '2025_10_08_181011_add_role_to_users_table', 2),
(9, '2025_10_08_192322_create_lich_lam_viecs_table', 2),
(10, '2025_10_27_200000_add_unique_index_to_lich_hens', 2),
(11, '2025_11_02_235900_create_lich_nghis_table_if_not_exists', 2),
(12, '2025_11_03_000000_create_lich_nghis_table', 2),
(13, '2025_11_03_010000_add_user_id_to_bac_sis', 2),
(14, '2025_11_03_130343_add_user_id_to_bac_sis_table', 2),
(15, '2025_11_04_084258_update_users_table_set_role_defaults', 2),
(16, '2025_11_04_190000_create_benh_ans_table', 2),
(17, '2025_11_04_190100_create_benh_an_files_table', 2),
(18, '2025_11_05_120000_backfill_lich_hens_status_to_vn', 2),
(19, '2025_11_05_130000_create_hoa_dons_table', 2),
(20, '2025_11_05_130100_create_thanh_toans_table', 2),
(21, '2025_11_07_000000_add_payment_columns_to_lich_hens_table', 2),
(22, '2025_11_07_000002_create_hoan_tiens_table', 2),
(23, '2025_11_07_010000_rename_payment_columns_on_lich_hens_table', 2),
(24, '2025_11_10_120000_add_cancel_columns_to_lich_hens', 2),
(25, '2025_11_11_093527_add_missing_columns_to_bac_sis_table', 2),
(26, '2025_11_13_120001_create_thuocs_table', 2),
(27, '2025_11_13_120002_create_don_thuocs_table', 2),
(28, '2025_11_13_120003_create_xet_nghiems_table', 2),
(29, '2025_11_13_170001_create_nhan_viens_table', 2),
(30, '2025_11_13_170002_create_ca_lam_viec_nhan_viens_table', 2),
(31, '2025_11_13_180000_add_unique_user_to_nhan_viens', 2),
(32, '2025_11_14_090000_create_ca_dieu_chinh_bac_sis_table', 2),
(33, '2025_11_14_230000_add_time_columns_to_lich_hens', 2),
(34, '2025_11_17_000001_create_nha_cung_caps_table', 2),
(35, '2025_11_17_000002_create_thuoc_khos_table', 2),
(36, '2025_11_17_000003_create_phieu_nhaps_table', 2),
(37, '2025_11_17_000004_create_phieu_xuats_table', 2),
(38, '2025_11_17_100100_create_chuyen_khoas_and_phongs', 2),
(39, '2025_11_17_100200_add_phong_id_to_lich_lam_viecs', 2),
(40, '2025_11_17_104853_create_jobs_table', 2),
(41, '2025_11_17_104907_create_jobs_table', 2),
(42, '2025_11_17_110000_create_danh_mucs_table', 2),
(43, '2025_11_17_110100_create_tags_table', 2),
(44, '2025_11_17_110200_create_bai_viets_table', 2),
(45, '2025_11_17_110300_create_bai_viet_tag_table', 2),
(46, '2025_11_17_110400_reinforce_lich_hen_slot_uniqueness', 2),
(47, '2025_11_20_054524_create_benh_an_audits_table', 2),
(48, '2025_11_20_055120_add_disk_to_benh_an_files_table', 2),
(49, '2025_11_20_073110_add_disk_to_xet_nghiems_table', 2),
(50, '2025_11_20_100000_create_payment_logs_table', 2),
(51, '2025_11_20_100100_add_idempotency_key_to_thanh_toans_table', 2),
(52, '2025_11_20_110000_add_tong_tien_to_lich_hens_table', 2),
(53, '2025_11_21_000001_create_nhan_vien_audits_table', 2),
(54, '2025_11_21_041434_create_permission_tables', 2),
(55, '2025_11_21_050000_add_account_security_to_users', 2),
(56, '2025_11_21_050100_create_login_audits_table', 2),
(57, '2025_11_30_105600_add_avatar_to_bac_sis_table', 2),
(58, '2025_12_04_140000_add_status_fields_to_hoa_dons_table', 2),
(59, '2025_12_04_145328_add_ton_toi_thieu_to_thuocs_table', 2),
(60, '2025_12_04_150000_add_status_to_phongs_table', 2),
(61, '2025_12_04_150308_create_coupons_table', 2),
(62, '2025_12_04_150320_create_don_hangs_table', 2),
(63, '2025_12_04_150330_create_don_hang_items_table', 2),
(64, '2025_12_04_155210_create_nha_cung_cap_thuoc_table', 2),
(65, '2025_12_04_160000_fix_phieu_nhaps_foreign_key', 2),
(66, '2025_12_04_160100_fix_phieu_nhap_items_foreign_key', 2),
(67, '2025_12_04_160200_fix_phieu_xuat_items_foreign_key', 2),
(68, '2025_12_05_092931_add_deleted_at_to_bai_viets_table', 2),
(69, '2025_12_05_101210_create_patient_profiles_table', 2),
(70, '2025_12_05_101211_create_notification_preferences_table', 2),
(71, '2025_12_05_144344_create_danh_gias_table', 2),
(72, '2025_12_05_150000_create_conversations_table', 2),
(73, '2025_12_05_150001_create_messages_table', 2),
(74, '2025_12_05_204006_create_notifications_table', 2),
(75, '2025_12_06_155207_add_sms_appointment_cancelled_to_notification_preferences_table', 2),
(76, '2025_12_07_133150_add_workflow_status_to_lich_hens_table', 2),
(77, '2025_12_07_133230_add_status_to_xet_nghiems_table', 2),
(78, '2025_12_08_000001_optimize_xet_nghiems_table', 2),
(79, '2025_12_08_174915_add_patient_info_to_users_table', 2),
(80, '2025_12_09_123000_add_started_at_to_lich_hens_table', 2),
(81, '2025_12_09_130926_create_activity_log_table', 2),
(82, '2025_12_09_130927_add_event_column_to_activity_log_table', 2),
(83, '2025_12_09_130928_add_batch_uuid_column_to_activity_log_table', 2),
(84, '2025_12_10_161500_add_avatar_to_users_table', 2),
(85, '2025_12_11_000001_create_chuyen_khoa_dich_vu_table', 2),
(86, '2025_12_12_200000_create_slot_locks_table', 2),
(87, '2025_12_12_201000_add_patient_info_to_lich_hens', 2),
(88, '2025_12_12_202000_add_coupon_to_hoa_dons', 2),
(89, '2025_12_13_000001_add_checked_in_by_to_lich_hens', 2),
(90, '2025_12_15_152209_add_payable_to_thanh_toans_table', 2),
(91, '2025_12_15_152314_add_payment_columns_to_don_hangs_table', 2),
(92, '2025_12_15_152807_update_trang_thai_thanh_toan_enum_in_don_hangs_table', 2),
(93, '2025_12_15_153022_add_payable_to_payment_logs_table', 2),
(94, '2025_12_15_153434_make_hoa_don_id_nullable_in_thanh_toans_table', 2),
(95, '2025_12_15_170000_make_lich_hen_nullable_on_hoa_dons_table', 2),
(96, '2025_12_19_100000_create_loai_sieu_ams_table', 2),
(97, '2025_12_19_100100_create_sieu_ams_table', 2),
(98, '2025_12_19_101200_add_disk_to_xet_nghiems_table', 2),
(99, '2025_12_19_140000_create_loai_xet_nghiems_table', 2),
(100, '2025_12_19_200000_make_file_path_nullable_in_xet_nghiems_table', 2),
(101, '2025_12_19_224327_create_chuyen_khoa_loai_sieu_am_table', 2),
(102, '2025_12_19_225805_add_phong_id_to_loai_sieu_ams_table', 3),
(103, '2025_01_15_100000_create_loai_sieu_ams_table', 4),
(104, '2025_01_15_100100_create_sieu_ams_table', 4),
(105, '2025_12_20_000001_create_loai_x_quangs_table', 5),
(106, '2025_12_20_000002_create_chuyen_khoa_loai_x_quang_table', 5),
(107, '2025_12_20_000003_create_x_quangs_table', 5),
(108, '2025_12_20_100000_add_dispensing_fields_to_don_thuocs_table', 6),
(109, '2025_12_20_000001_create_theo_doi_thai_kys_table', 7),
(110, '2025_12_20_130000_create_tai_khams_table', 8),
(111, '2025_12_20_160000_alter_benh_an_audits_action_length', 9);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `model_has_permissions`
--

INSERT INTO `model_has_permissions` (`permission_id`, `model_type`, `model_id`) VALUES
(57, 'App\\Models\\User', 24);

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
(1, 'App\\Models\\User', 24),
(2, 'App\\Models\\User', 24),
(4, 'App\\Models\\User', 1),
(4, 'App\\Models\\User', 2),
(4, 'App\\Models\\User', 3),
(4, 'App\\Models\\User', 4),
(4, 'App\\Models\\User', 5),
(4, 'App\\Models\\User', 6),
(4, 'App\\Models\\User', 7),
(4, 'App\\Models\\User', 8),
(4, 'App\\Models\\User', 9),
(4, 'App\\Models\\User', 10),
(4, 'App\\Models\\User', 11),
(4, 'App\\Models\\User', 12),
(5, 'App\\Models\\User', 14),
(5, 'App\\Models\\User', 15),
(5, 'App\\Models\\User', 16),
(5, 'App\\Models\\User', 17),
(5, 'App\\Models\\User', 18),
(5, 'App\\Models\\User', 19),
(5, 'App\\Models\\User', 20),
(5, 'App\\Models\\User', 21),
(5, 'App\\Models\\User', 22),
(5, 'App\\Models\\User', 25),
(6, 'App\\Models\\User', 23);

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
(2, 14, 'Nguyễn Thị Hồng Hạnh', 'Nhân viên CSKH', '0909222002', 'hanh.nguyen@vietcare.com', '2000-10-20', 'Nữ', NULL, 'Đang làm', '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(3, 15, 'Trần Thị Kim Dung', 'Lễ tân', '0909222003', 'dung.tran@vietcare.com', '1999-08-15', 'Nữ', NULL, 'Đang làm', '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(4, 16, 'Phạm Thị Thu Thảo', 'Điều dưỡng trưởng', '0909222004', 'thao.pham@vietcare.com', '1985-02-05', 'Nữ', NULL, 'Đang làm', '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(5, 17, 'Lê Thị Thanh Trúc', 'Nữ hộ sinh', '0909222005', 'truc.le@vietcare.com', '1995-11-10', 'Nữ', NULL, 'Đang làm', '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(6, 18, 'Nguyễn Ngọc Huyền', 'Điều dưỡng đa khoa', '0909222006', 'huyen.nguyen@vietcare.com', '1997-07-22', 'Nữ', NULL, 'Đang làm', '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(7, 19, 'Vũ Thị Minh Anh', 'Thu ngân', '0909222007', 'anh.vu@vietcare.com', '1996-04-30', 'Nữ', NULL, 'Đang làm', '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(8, 20, 'Hoàng Văn Nam', 'Dược sĩ', '0909222008', 'nam.hoang@vietcare.com', '1990-09-14', 'Nam', NULL, 'Đang làm', '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(9, 21, 'Nguyễn Thu Phương', 'Quản lý phòng khám', '0909222009', 'phuong.nguyen@vietcare.com', '1980-01-01', 'Nữ', NULL, 'Đang làm', '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(10, 22, 'Trần Quốc Bảo', 'Nhân viên IT / Kỹ thuật', '0909222010', 'bao.tran@vietcare.com', '1995-12-18', 'Nam', NULL, 'Đang làm', '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(11, 25, 'Lê Minh Nhật', 'Trưởng lễ tân', '0123654897', 'henvaemhen@gmail.com', '2004-12-12', 'nam', 'nv_avatar/QfiZElnLOZ8wtxphGmyS6YRhdRjUyF8yFWoKRWL9.png', 'active', '2025-12-19 16:38:20', '2025-12-19 16:38:20');

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
(2, 2, NULL, 'created', NULL, '{\"email_cong_viec\":\"hanh.nguyen@vietcare.com\",\"user_id\":14,\"ho_ten\":\"Nguy\\u1ec5n Th\\u1ecb H\\u1ed3ng H\\u1ea1nh\",\"chuc_vu\":\"Nh\\u00e2n vi\\u00ean CSKH\",\"so_dien_thoai\":\"0909222002\",\"ngay_sinh\":\"2000-10-20\",\"gioi_tinh\":\"N\\u1eef\",\"trang_thai\":\"\\u0110ang l\\u00e0m\",\"updated_at\":\"2025-12-19 23:00:45\",\"created_at\":\"2025-12-19 23:00:45\",\"id\":2}', '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(3, 3, NULL, 'created', NULL, '{\"email_cong_viec\":\"dung.tran@vietcare.com\",\"user_id\":15,\"ho_ten\":\"Tr\\u1ea7n Th\\u1ecb Kim Dung\",\"chuc_vu\":\"L\\u1ec5 t\\u00e2n\",\"so_dien_thoai\":\"0909222003\",\"ngay_sinh\":\"1999-08-15\",\"gioi_tinh\":\"N\\u1eef\",\"trang_thai\":\"\\u0110ang l\\u00e0m\",\"updated_at\":\"2025-12-19 23:00:45\",\"created_at\":\"2025-12-19 23:00:45\",\"id\":3}', '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(4, 4, NULL, 'created', NULL, '{\"email_cong_viec\":\"thao.pham@vietcare.com\",\"user_id\":16,\"ho_ten\":\"Ph\\u1ea1m Th\\u1ecb Thu Th\\u1ea3o\",\"chuc_vu\":\"\\u0110i\\u1ec1u d\\u01b0\\u1ee1ng tr\\u01b0\\u1edfng\",\"so_dien_thoai\":\"0909222004\",\"ngay_sinh\":\"1985-02-05\",\"gioi_tinh\":\"N\\u1eef\",\"trang_thai\":\"\\u0110ang l\\u00e0m\",\"updated_at\":\"2025-12-19 23:00:45\",\"created_at\":\"2025-12-19 23:00:45\",\"id\":4}', '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(5, 5, NULL, 'created', NULL, '{\"email_cong_viec\":\"truc.le@vietcare.com\",\"user_id\":17,\"ho_ten\":\"L\\u00ea Th\\u1ecb Thanh Tr\\u00fac\",\"chuc_vu\":\"N\\u1eef h\\u1ed9 sinh\",\"so_dien_thoai\":\"0909222005\",\"ngay_sinh\":\"1995-11-10\",\"gioi_tinh\":\"N\\u1eef\",\"trang_thai\":\"\\u0110ang l\\u00e0m\",\"updated_at\":\"2025-12-19 23:00:45\",\"created_at\":\"2025-12-19 23:00:45\",\"id\":5}', '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(6, 6, NULL, 'created', NULL, '{\"email_cong_viec\":\"huyen.nguyen@vietcare.com\",\"user_id\":18,\"ho_ten\":\"Nguy\\u1ec5n Ng\\u1ecdc Huy\\u1ec1n\",\"chuc_vu\":\"\\u0110i\\u1ec1u d\\u01b0\\u1ee1ng \\u0111a khoa\",\"so_dien_thoai\":\"0909222006\",\"ngay_sinh\":\"1997-07-22\",\"gioi_tinh\":\"N\\u1eef\",\"trang_thai\":\"\\u0110ang l\\u00e0m\",\"updated_at\":\"2025-12-19 23:00:45\",\"created_at\":\"2025-12-19 23:00:45\",\"id\":6}', '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(7, 7, NULL, 'created', NULL, '{\"email_cong_viec\":\"anh.vu@vietcare.com\",\"user_id\":19,\"ho_ten\":\"V\\u0169 Th\\u1ecb Minh Anh\",\"chuc_vu\":\"Thu ng\\u00e2n\",\"so_dien_thoai\":\"0909222007\",\"ngay_sinh\":\"1996-04-30\",\"gioi_tinh\":\"N\\u1eef\",\"trang_thai\":\"\\u0110ang l\\u00e0m\",\"updated_at\":\"2025-12-19 23:00:45\",\"created_at\":\"2025-12-19 23:00:45\",\"id\":7}', '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(8, 8, NULL, 'created', NULL, '{\"email_cong_viec\":\"nam.hoang@vietcare.com\",\"user_id\":20,\"ho_ten\":\"Ho\\u00e0ng V\\u0103n Nam\",\"chuc_vu\":\"D\\u01b0\\u1ee3c s\\u0129\",\"so_dien_thoai\":\"0909222008\",\"ngay_sinh\":\"1990-09-14\",\"gioi_tinh\":\"Nam\",\"trang_thai\":\"\\u0110ang l\\u00e0m\",\"updated_at\":\"2025-12-19 23:00:45\",\"created_at\":\"2025-12-19 23:00:45\",\"id\":8}', '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(9, 9, NULL, 'created', NULL, '{\"email_cong_viec\":\"phuong.nguyen@vietcare.com\",\"user_id\":21,\"ho_ten\":\"Nguy\\u1ec5n Thu Ph\\u01b0\\u01a1ng\",\"chuc_vu\":\"Qu\\u1ea3n l\\u00fd ph\\u00f2ng kh\\u00e1m\",\"so_dien_thoai\":\"0909222009\",\"ngay_sinh\":\"1980-01-01\",\"gioi_tinh\":\"N\\u1eef\",\"trang_thai\":\"\\u0110ang l\\u00e0m\",\"updated_at\":\"2025-12-19 23:00:45\",\"created_at\":\"2025-12-19 23:00:45\",\"id\":9}', '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(10, 10, NULL, 'created', NULL, '{\"email_cong_viec\":\"bao.tran@vietcare.com\",\"user_id\":22,\"ho_ten\":\"Tr\\u1ea7n Qu\\u1ed1c B\\u1ea3o\",\"chuc_vu\":\"Nh\\u00e2n vi\\u00ean IT \\/ K\\u1ef9 thu\\u1eadt\",\"so_dien_thoai\":\"0909222010\",\"ngay_sinh\":\"1995-12-18\",\"gioi_tinh\":\"Nam\",\"trang_thai\":\"\\u0110ang l\\u00e0m\",\"updated_at\":\"2025-12-19 23:00:45\",\"created_at\":\"2025-12-19 23:00:45\",\"id\":10}', '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(12, 11, 24, 'created', NULL, '{\"ho_ten\":\"L\\u00ea Minh Nh\\u1eadt\",\"chuc_vu\":\"Tr\\u01b0\\u1edfng l\\u1ec5 t\\u00e2n\",\"so_dien_thoai\":\"0123654897\",\"email_cong_viec\":\"henvaemhen@gmail.com\",\"ngay_sinh\":\"2004-12-12\",\"gioi_tinh\":\"nam\",\"avatar_path\":\"nv_avatar\\/QfiZElnLOZ8wtxphGmyS6YRhdRjUyF8yFWoKRWL9.png\",\"user_id\":25,\"updated_at\":\"2025-12-19 23:38:20\",\"created_at\":\"2025-12-19 23:38:20\",\"id\":11}', '2025-12-19 16:38:20', '2025-12-19 16:38:20');

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
(1, 'Zuellig Pharma Vietnam', 'TP.HCM', '09091000', 'supplier1@vietcare.com', NULL, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(2, 'DKSH Vietnam', 'TP.HCM', '09091001', 'supplier2@vietcare.com', NULL, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(3, 'Dược Hậu Giang', 'TP.HCM', '09091002', 'supplier3@vietcare.com', NULL, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(4, 'Traphaco', 'TP.HCM', '09091003', 'supplier4@vietcare.com', NULL, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(5, 'Vinapharm', 'TP.HCM', '09091004', 'supplier5@vietcare.com', NULL, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(6, 'Công ty Vật tư Y tế Sài Gòn', 'TP.HCM', '09091005', 'supplier6@vietcare.com', NULL, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(7, 'Công ty Vật tư Y tế Hà Nội', 'TP.HCM', '09091006', 'supplier7@vietcare.com', NULL, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(8, 'Công ty Vật tư Y tế Trung Tâm', 'TP.HCM', '09091007', 'supplier8@vietcare.com', NULL, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(9, 'Hóa chất Roche Vietnam', 'TP.HCM', '09091008', 'supplier9@vietcare.com', NULL, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(10, 'Hóa chất Abbott Vietnam', 'TP.HCM', '09091009', 'supplier10@vietcare.com', NULL, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(11, 'Hóa chất Biomed', 'TP.HCM', '09091010', 'supplier11@vietcare.com', NULL, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(12, 'Công ty Thiết bị Y tế MedTech', 'TP.HCM', '09091011', 'supplier12@vietcare.com', NULL, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(13, 'Công ty Thiết bị Y tế VN', 'TP.HCM', '09091012', 'supplier13@vietcare.com', NULL, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(14, 'Nhà thuốc An Bình', 'TP.HCM', '09091013', 'supplier14@vietcare.com', NULL, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(15, 'Nhà thuốc Bảo An', 'TP.HCM', '09091014', 'supplier15@vietcare.com', NULL, '2025-12-19 16:00:45', '2025-12-19 16:00:45');

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
(1, 9, 1, 9701.00, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(2, 13, 1, 9701.00, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(3, 12, 2, 9969.00, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(4, 14, 2, 9969.00, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(5, 2, 3, 3679.00, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(6, 9, 3, 3679.00, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(7, 1, 4, 3683.00, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(8, 8, 4, 3683.00, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(9, 7, 5, 6880.00, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(10, 14, 5, 6880.00, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(11, 2, 6, 8601.00, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(12, 15, 6, 8601.00, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(13, 14, 7, 6366.00, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(14, 15, 7, 6366.00, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(15, 3, 8, 5268.00, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(16, 11, 8, 5268.00, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(17, 2, 9, 1183.00, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(18, 3, 9, 1183.00, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(19, 4, 10, 4574.00, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(20, 5, 10, 4574.00, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(21, 9, 11, 4027.00, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(22, 10, 11, 4027.00, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(23, 4, 12, 5265.00, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(24, 15, 12, 5265.00, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(25, 5, 13, 4658.00, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(26, 11, 13, 4658.00, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(27, 6, 14, 4618.00, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(28, 8, 14, 4618.00, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(29, 10, 15, 4263.00, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(30, 13, 15, 4263.00, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(31, 10, 16, 6515.00, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(32, 13, 16, 6515.00, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(33, 3, 17, 8953.00, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(34, 5, 17, 8953.00, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(35, 7, 18, 7067.00, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(36, 15, 18, 7067.00, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(37, 1, 19, 6205.00, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(38, 5, 19, 6205.00, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(39, 6, 20, 4146.00, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(40, 7, 20, 4146.00, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(41, 4, 21, 5668.00, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(42, 11, 21, 5668.00, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(43, 2, 22, 4770.00, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(44, 5, 22, 4770.00, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(45, 9, 23, 7241.00, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(46, 10, 23, 7241.00, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(47, 2, 24, 5457.00, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(48, 11, 24, 5457.00, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(49, 8, 25, 3610.00, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(50, 9, 25, 3610.00, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(51, 3, 26, 1664.00, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(52, 12, 26, 1664.00, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(53, 12, 27, 7079.00, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(54, 14, 27, 7079.00, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(55, 8, 28, 8432.00, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(56, 13, 28, 8432.00, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(57, 2, 29, 9395.00, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(58, 6, 29, 9395.00, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(59, 2, 30, 6347.00, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(60, 10, 30, 6347.00, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(61, 5, 31, 3409.00, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(62, 11, 31, 3409.00, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(63, 3, 32, 818.00, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(64, 11, 32, 818.00, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(65, 9, 33, 4596.00, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(66, 12, 33, 4596.00, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(67, 10, 34, 8097.00, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(68, 15, 34, 8097.00, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(69, 4, 35, 4722.00, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(70, 9, 35, 4722.00, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(71, 1, 36, 2259.00, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(72, 12, 36, 2259.00, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(73, 4, 37, 5184.00, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(74, 8, 37, 5184.00, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(75, 9, 38, 5818.00, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(76, 11, 38, 5818.00, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(77, 2, 39, 5786.00, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(78, 13, 39, 5786.00, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(79, 3, 40, 9119.00, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(80, 4, 40, 9119.00, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(81, 2, 41, 9416.00, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(82, 6, 41, 9416.00, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(83, 8, 42, 6334.00, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(84, 12, 42, 6334.00, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(85, 1, 43, 3183.00, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(86, 14, 43, 3183.00, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(87, 8, 44, 9694.00, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(88, 11, 44, 9694.00, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(89, 3, 45, 5753.00, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(90, 10, 45, 5753.00, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(91, 4, 46, 5550.00, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(92, 14, 46, 5550.00, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(93, 8, 47, 7560.00, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(94, 9, 47, 7560.00, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(95, 6, 48, 3762.00, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(96, 11, 48, 3762.00, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(97, 4, 49, 4275.00, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(98, 5, 49, 4275.00, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(99, 4, 50, 8946.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(100, 8, 50, 8946.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(101, 4, 51, 4939.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(102, 15, 51, 4939.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(103, 7, 52, 6389.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(104, 12, 52, 6389.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(105, 8, 53, 7789.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(106, 15, 53, 7789.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(107, 4, 54, 6064.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(108, 9, 54, 6064.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(109, 9, 55, 3096.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(110, 11, 55, 3096.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(111, 9, 56, 2992.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(112, 14, 56, 2992.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(113, 1, 57, 6694.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(114, 5, 57, 6694.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(115, 4, 58, 1597.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(116, 11, 58, 1597.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(117, 1, 59, 6949.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(118, 15, 59, 6949.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(119, 6, 60, 1200.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(120, 7, 60, 1200.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(121, 4, 61, 8527.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(122, 15, 61, 8527.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(123, 3, 62, 6274.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(124, 13, 62, 6274.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(125, 5, 63, 5519.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(126, 8, 63, 5519.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(127, 7, 64, 4375.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(128, 13, 64, 4375.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(129, 3, 65, 1376.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(130, 5, 65, 1376.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(131, 8, 66, 7019.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(132, 11, 66, 7019.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(133, 1, 67, 4223.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(134, 6, 67, 4223.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(135, 4, 68, 966.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(136, 13, 68, 966.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(137, 2, 69, 3256.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(138, 14, 69, 3256.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(139, 1, 70, 983.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(140, 5, 70, 983.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(141, 5, 71, 6272.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(142, 12, 71, 6272.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(143, 8, 72, 2667.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(144, 13, 72, 2667.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(145, 7, 73, 4348.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(146, 15, 73, 4348.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(147, 5, 74, 4381.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(148, 14, 74, 4381.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(149, 6, 75, 2383.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(150, 14, 75, 2383.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(151, 1, 76, 6466.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(152, 2, 76, 6466.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(153, 7, 77, 8999.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(154, 12, 77, 8999.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(155, 4, 78, 6805.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(156, 9, 78, 6805.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(157, 1, 79, 1371.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(158, 10, 79, 1371.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(159, 9, 80, 9122.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(160, 15, 80, 9122.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(161, 5, 81, 3789.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(162, 15, 81, 3789.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(163, 1, 82, 3704.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(164, 15, 82, 3704.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(165, 2, 83, 1035.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(166, 5, 83, 1035.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(167, 2, 84, 2368.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(168, 5, 84, 2368.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(169, 2, 85, 2710.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(170, 7, 85, 2710.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(171, 4, 86, 1813.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(172, 5, 86, 1813.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(173, 5, 87, 2044.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(174, 13, 87, 2044.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(175, 11, 88, 6894.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(176, 15, 88, 6894.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(177, 1, 89, 6279.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(178, 8, 89, 6279.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(179, 8, 90, 6190.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(180, 14, 90, 6190.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(181, 8, 91, 2932.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(182, 9, 91, 2932.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(183, 9, 92, 6614.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(184, 14, 92, 6614.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(185, 8, 93, 2038.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(186, 14, 93, 2038.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(187, 4, 94, 5980.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(188, 8, 94, 5980.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(189, 9, 95, 9090.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(190, 11, 95, 9090.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(191, 5, 96, 1499.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(192, 6, 96, 1499.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(193, 7, 97, 2486.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(194, 9, 97, 2486.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(195, 2, 98, 1055.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(196, 3, 98, 1055.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(197, 1, 99, 9486.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(198, 3, 99, 9486.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(199, 1, 100, 1703.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(200, 12, 100, 1703.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(201, 7, 101, 8452.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(202, 15, 101, 8452.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(203, 9, 102, 7585.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(204, 13, 102, 7585.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(205, 3, 103, 8263.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(206, 13, 103, 8263.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(207, 9, 104, 8838.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(208, 15, 104, 8838.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(209, 6, 105, 6774.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(210, 14, 105, 6774.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(211, 3, 106, 1810.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(212, 10, 106, 1810.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(213, 5, 107, 3798.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(214, 8, 107, 3798.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(215, 4, 108, 9431.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(216, 14, 108, 9431.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(217, 3, 109, 1127.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(218, 11, 109, 1127.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(219, 12, 110, 1237.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(220, 15, 110, 1237.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(221, 3, 111, 4413.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(222, 9, 111, 4413.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(223, 1, 112, 6783.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(224, 12, 112, 6783.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(225, 3, 113, 5670.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(226, 12, 113, 5670.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(227, 11, 114, 5633.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(228, 13, 114, 5633.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(229, 4, 115, 4184.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(230, 8, 115, 4184.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(231, 5, 116, 930.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(232, 11, 116, 930.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(233, 6, 117, 2928.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(234, 11, 117, 2928.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(235, 9, 118, 3180.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(236, 11, 118, 3180.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(237, 2, 119, 4670.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(238, 10, 119, 4670.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(239, 6, 120, 7930.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(240, 13, 120, 7930.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(241, 12, 121, 9323.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(242, 13, 121, 9323.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(243, 8, 122, 1885.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(244, 10, 122, 1885.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(245, 4, 123, 2600.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(246, 11, 123, 2600.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(247, 8, 124, 6458.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(248, 14, 124, 6458.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(249, 4, 125, 6965.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(250, 6, 125, 6965.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(251, 10, 126, 2194.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(252, 13, 126, 2194.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(253, 8, 127, 6400.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(254, 11, 127, 6400.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(255, 2, 128, 6511.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(256, 5, 128, 6511.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(257, 1, 129, 5781.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(258, 11, 129, 5781.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(259, 3, 130, 6452.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(260, 14, 130, 6452.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(261, 6, 131, 7433.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(262, 7, 131, 7433.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(263, 8, 132, 8303.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(264, 14, 132, 8303.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(265, 7, 133, 6600.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(266, 14, 133, 6600.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(267, 1, 134, 6171.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(268, 8, 134, 6171.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(269, 1, 135, 4761.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(270, 6, 135, 4761.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(271, 2, 136, 2424.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(272, 8, 136, 2424.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(273, 10, 137, 2458.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(274, 12, 137, 2458.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(275, 5, 138, 3521.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(276, 7, 138, 3521.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(277, 7, 139, 2156.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(278, 9, 139, 2156.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(279, 2, 140, 5313.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(280, 10, 140, 5313.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(281, 11, 141, 1949.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(282, 13, 141, 1949.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(283, 4, 142, 9858.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(284, 15, 142, 9858.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(285, 3, 143, 6780.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(286, 5, 143, 6780.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(287, 8, 144, 2556.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(288, 11, 144, 2556.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(289, 11, 145, 3805.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(290, 14, 145, 3805.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(291, 5, 146, 5392.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(292, 7, 146, 5392.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(293, 8, 147, 3773.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(294, 9, 147, 3773.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(295, 9, 148, 8500.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(296, 10, 148, 8500.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(297, 2, 149, 3168.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(298, 10, 149, 3168.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(299, 4, 150, 5165.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(300, 7, 150, 5165.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(301, 1, 151, 42705.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(302, 11, 151, 42705.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(303, 6, 152, 41046.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(304, 14, 152, 41046.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(305, 8, 153, 49664.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(306, 10, 153, 49664.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(307, 1, 154, 37945.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(308, 5, 154, 37945.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(309, 8, 155, 27606.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(310, 12, 155, 27606.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(311, 9, 156, 49106.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(312, 11, 156, 49106.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(313, 4, 157, 8195.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(314, 15, 157, 8195.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(315, 9, 158, 30379.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(316, 10, 158, 30379.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(317, 5, 159, 8417.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(318, 12, 159, 8417.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(319, 11, 160, 15106.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(320, 15, 160, 15106.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(321, 7, 161, 15070.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(322, 15, 161, 15070.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(323, 8, 162, 19187.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(324, 12, 162, 19187.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(325, 2, 163, 40108.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(326, 5, 163, 40108.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(327, 2, 164, 38513.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(328, 3, 164, 38513.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(329, 5, 165, 46940.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(330, 12, 165, 46940.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(331, 4, 166, 26020.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(332, 6, 166, 26020.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(333, 10, 167, 9578.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(334, 12, 167, 9578.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(335, 3, 168, 46057.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(336, 5, 168, 46057.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(337, 10, 169, 6904.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(338, 15, 169, 6904.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(339, 1, 170, 21423.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(340, 12, 170, 21423.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(341, 9, 171, 48325.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(342, 11, 171, 48325.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(343, 1, 172, 45776.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(344, 4, 172, 45776.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(345, 6, 173, 32785.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(346, 9, 173, 32785.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(347, 3, 174, 37050.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(348, 12, 174, 37050.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(349, 10, 175, 39470.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(350, 14, 175, 39470.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(351, 6, 176, 28640.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(352, 12, 176, 28640.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(353, 6, 177, 7272.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(354, 10, 177, 7272.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(355, 1, 178, 26141.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(356, 7, 178, 26141.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(357, 3, 179, 23953.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(358, 6, 179, 23953.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(359, 1, 180, 34453.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(360, 7, 180, 34453.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(361, 12, 181, 44688.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(362, 13, 181, 44688.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(363, 6, 182, 8532.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(364, 12, 182, 8532.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(365, 1, 183, 7511.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(366, 7, 183, 7511.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(367, 7, 184, 42479.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(368, 11, 184, 42479.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(369, 5, 185, 13013.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(370, 10, 185, 13013.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(371, 6, 186, 26749.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(372, 7, 186, 26749.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(373, 13, 187, 23516.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(374, 14, 187, 23516.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(375, 4, 188, 36289.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(376, 6, 188, 36289.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(377, 7, 189, 48910.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(378, 8, 189, 48910.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(379, 2, 190, 48526.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(380, 15, 190, 48526.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(381, 11, 191, 21922.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(382, 12, 191, 21922.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(383, 8, 192, 11481.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(384, 14, 192, 11481.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(385, 4, 193, 13201.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(386, 12, 193, 13201.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(387, 5, 194, 28796.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(388, 9, 194, 28796.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(389, 2, 195, 48546.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(390, 10, 195, 48546.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(391, 4, 196, 44470.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(392, 8, 196, 44470.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(393, 6, 197, 24752.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(394, 15, 197, 24752.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(395, 8, 198, 22019.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(396, 11, 198, 22019.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(397, 4, 199, 9962.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(398, 5, 199, 9962.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(399, 2, 200, 23707.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(400, 11, 200, 23707.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `notifications`
--

CREATE TABLE `notifications` (
  `id` char(36) NOT NULL,
  `type` varchar(255) NOT NULL,
  `notifiable_type` varchar(255) NOT NULL,
  `notifiable_id` bigint(20) UNSIGNED NOT NULL,
  `data` text NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `notifications`
--

INSERT INTO `notifications` (`id`, `type`, `notifiable_type`, `notifiable_id`, `data`, `read_at`, `created_at`, `updated_at`) VALUES
('05358c89-6b9b-4935-9f54-3dd4d4c45499', 'App\\Notifications\\MedicalUltrasoundRequested', 'App\\Models\\User', 21, '{\"title\":\"C\\u00f3 ch\\u1ec9 \\u0111\\u1ecbnh si\\u00eau \\u00e2m m\\u1edbi\",\"message\":\"M\\u1ed9t ca si\\u00eau \\u00e2m m\\u1edbi v\\u1eeba \\u0111\\u01b0\\u1ee3c ch\\u1ec9 \\u0111\\u1ecbnh. Vui l\\u00f2ng v\\u00e0o danh s\\u00e1ch ch\\u1edd \\u0111\\u1ec3 x\\u1eed l\\u00fd.\",\"action_url\":\"http:\\/\\/127.0.0.1:8000\\/staff\\/sieu-am\\/1\",\"meta\":{\"module\":\"sieu_am\",\"sieu_am_id\":1}}', NULL, '2025-12-19 17:58:38', '2025-12-19 17:58:38'),
('0aa89208-0f21-401f-8dcf-1cfe375508bf', 'App\\Notifications\\MedicalUltrasoundRequested', 'App\\Models\\User', 25, '{\"title\":\"C\\u00f3 ch\\u1ec9 \\u0111\\u1ecbnh si\\u00eau \\u00e2m m\\u1edbi\",\"message\":\"M\\u1ed9t ca si\\u00eau \\u00e2m m\\u1edbi v\\u1eeba \\u0111\\u01b0\\u1ee3c ch\\u1ec9 \\u0111\\u1ecbnh. Vui l\\u00f2ng v\\u00e0o danh s\\u00e1ch ch\\u1edd \\u0111\\u1ec3 x\\u1eed l\\u00fd.\",\"action_url\":\"http:\\/\\/127.0.0.1:8000\\/staff\\/sieu-am\\/1\",\"meta\":{\"module\":\"sieu_am\",\"sieu_am_id\":1}}', '2025-12-20 01:40:59', '2025-12-19 17:58:38', '2025-12-20 01:40:59'),
('1007f11b-7675-49c1-9171-b295fc0ea90e', 'App\\Notifications\\MedicalUltrasoundRequested', 'App\\Models\\User', 17, '{\"title\":\"C\\u00f3 ch\\u1ec9 \\u0111\\u1ecbnh si\\u00eau \\u00e2m m\\u1edbi\",\"message\":\"M\\u1ed9t ca si\\u00eau \\u00e2m m\\u1edbi v\\u1eeba \\u0111\\u01b0\\u1ee3c ch\\u1ec9 \\u0111\\u1ecbnh. Vui l\\u00f2ng v\\u00e0o danh s\\u00e1ch ch\\u1edd \\u0111\\u1ec3 x\\u1eed l\\u00fd.\",\"action_url\":\"http:\\/\\/127.0.0.1:8000\\/staff\\/sieu-am\\/1\",\"meta\":{\"module\":\"sieu_am\",\"sieu_am_id\":1}}', NULL, '2025-12-19 17:58:38', '2025-12-19 17:58:38'),
('2073a13a-49ab-41c9-8c4f-3a315c91d0bc', 'App\\Notifications\\MedicalUltrasoundRequested', 'App\\Models\\User', 20, '{\"title\":\"C\\u00f3 ch\\u1ec9 \\u0111\\u1ecbnh si\\u00eau \\u00e2m m\\u1edbi\",\"message\":\"M\\u1ed9t ca si\\u00eau \\u00e2m m\\u1edbi v\\u1eeba \\u0111\\u01b0\\u1ee3c ch\\u1ec9 \\u0111\\u1ecbnh. Vui l\\u00f2ng v\\u00e0o danh s\\u00e1ch ch\\u1edd \\u0111\\u1ec3 x\\u1eed l\\u00fd.\",\"action_url\":\"http:\\/\\/127.0.0.1:8000\\/staff\\/sieu-am\\/1\",\"meta\":{\"module\":\"sieu_am\",\"sieu_am_id\":1}}', NULL, '2025-12-19 17:58:38', '2025-12-19 17:58:38'),
('3b51a3da-ac02-4f7a-8b05-120d502a0aeb', 'App\\Notifications\\MedicalUltrasoundRequested', 'App\\Models\\User', 23, '{\"title\":\"B\\u1ea1n c\\u00f3 ch\\u1ec9 \\u0111\\u1ecbnh si\\u00eau \\u00e2m m\\u1edbi\",\"message\":\"B\\u00e1c s\\u0129 \\u0111\\u00e3 t\\u1ea1o ch\\u1ec9 \\u0111\\u1ecbnh si\\u00eau \\u00e2m. Vui l\\u00f2ng theo d\\u00f5i tr\\u1ea1ng th\\u00e1i v\\u00e0 k\\u1ebft qu\\u1ea3 khi c\\u00f3.\",\"action_url\":\"http:\\/\\/127.0.0.1:8000\\/sieu-am\\/1\",\"meta\":{\"module\":\"sieu_am\",\"sieu_am_id\":1}}', '2025-12-19 17:59:50', '2025-12-19 17:58:37', '2025-12-19 17:59:50'),
('3b6374ec-03a3-4279-b1e3-19b3382cc38e', 'App\\Notifications\\MedicalUltrasoundResultUploaded', 'App\\Models\\User', 4, '{\"title\":\"C\\u00f3 k\\u1ebft qu\\u1ea3 si\\u00eau \\u00e2m m\\u1edbi\",\"message\":\"K\\u1ef9 thu\\u1eadt vi\\u00ean \\u0111\\u00e3 upload k\\u1ebft qu\\u1ea3 si\\u00eau \\u00e2m. Vui l\\u00f2ng v\\u00e0o xem v\\u00e0 nh\\u1eadn x\\u00e9t.\",\"action_url\":\"http:\\/\\/127.0.0.1:8000\\/doctor\\/sieu-am\\/1\",\"meta\":{\"module\":\"sieu_am\",\"sieu_am_id\":1}}', '2025-12-19 18:14:55', '2025-12-19 18:02:22', '2025-12-19 18:14:55'),
('424b402f-57dc-4c0d-8c80-0decf8b870ff', 'App\\Notifications\\MedicalUltrasoundRequested', 'App\\Models\\User', 15, '{\"title\":\"C\\u00f3 ch\\u1ec9 \\u0111\\u1ecbnh si\\u00eau \\u00e2m m\\u1edbi\",\"message\":\"M\\u1ed9t ca si\\u00eau \\u00e2m m\\u1edbi v\\u1eeba \\u0111\\u01b0\\u1ee3c ch\\u1ec9 \\u0111\\u1ecbnh. Vui l\\u00f2ng v\\u00e0o danh s\\u00e1ch ch\\u1edd \\u0111\\u1ec3 x\\u1eed l\\u00fd.\",\"action_url\":\"http:\\/\\/127.0.0.1:8000\\/staff\\/sieu-am\\/1\",\"meta\":{\"module\":\"sieu_am\",\"sieu_am_id\":1}}', NULL, '2025-12-19 17:58:38', '2025-12-19 17:58:38'),
('44309695-e539-44ff-b7c1-7bfcb982cacb', 'App\\Notifications\\CustomNotification', 'App\\Models\\User', 4, '{\"title\":\"C\\u00f3 l\\u1ecbch t\\u00e1i kh\\u00e1m m\\u1edbi\",\"message\":\"Y\\u00eau c\\u1ea7u t\\u00e1i kh\\u00e1m #2 \\u0111\\u00e3 \\u0111\\u01b0\\u1ee3c \\u0111\\u1eb7t l\\u1ecbch.\",\"action_url\":\"http:\\/\\/127.0.0.1:8000\\/doctor\\/tai-kham\\/2\\/show\"}', '2025-12-20 04:13:05', '2025-12-20 04:12:45', '2025-12-20 04:13:05'),
('55be1f70-8ae8-4889-8581-eb7df647a826', 'App\\Notifications\\MedicalUltrasoundReviewed', 'App\\Models\\User', 23, '{\"title\":\"B\\u00e1c s\\u0129 \\u0111\\u00e3 nh\\u1eadn x\\u00e9t k\\u1ebft qu\\u1ea3 si\\u00eau \\u00e2m\",\"message\":\"B\\u00e1c s\\u0129 \\u0111\\u00e3 c\\u1eadp nh\\u1eadt nh\\u1eadn x\\u00e9t cho k\\u1ebft qu\\u1ea3 si\\u00eau \\u00e2m c\\u1ee7a b\\u1ea1n.\",\"action_url\":\"http:\\/\\/127.0.0.1:8000\\/sieu-am\\/1\",\"meta\":{\"module\":\"sieu_am\",\"sieu_am_id\":1}}', '2025-12-19 18:04:02', '2025-12-19 18:03:06', '2025-12-19 18:04:02'),
('7ce18eac-7fd0-4696-bb66-79951519bba0', 'App\\Notifications\\MedicalUltrasoundRequested', 'App\\Models\\User', 18, '{\"title\":\"C\\u00f3 ch\\u1ec9 \\u0111\\u1ecbnh si\\u00eau \\u00e2m m\\u1edbi\",\"message\":\"M\\u1ed9t ca si\\u00eau \\u00e2m m\\u1edbi v\\u1eeba \\u0111\\u01b0\\u1ee3c ch\\u1ec9 \\u0111\\u1ecbnh. Vui l\\u00f2ng v\\u00e0o danh s\\u00e1ch ch\\u1edd \\u0111\\u1ec3 x\\u1eed l\\u00fd.\",\"action_url\":\"http:\\/\\/127.0.0.1:8000\\/staff\\/sieu-am\\/1\",\"meta\":{\"module\":\"sieu_am\",\"sieu_am_id\":1}}', NULL, '2025-12-19 17:58:38', '2025-12-19 17:58:38'),
('85ccfbd6-d345-457a-922c-aaafcdcb34c6', 'App\\Notifications\\MedicalUltrasoundRequested', 'App\\Models\\User', 22, '{\"title\":\"C\\u00f3 ch\\u1ec9 \\u0111\\u1ecbnh si\\u00eau \\u00e2m m\\u1edbi\",\"message\":\"M\\u1ed9t ca si\\u00eau \\u00e2m m\\u1edbi v\\u1eeba \\u0111\\u01b0\\u1ee3c ch\\u1ec9 \\u0111\\u1ecbnh. Vui l\\u00f2ng v\\u00e0o danh s\\u00e1ch ch\\u1edd \\u0111\\u1ec3 x\\u1eed l\\u00fd.\",\"action_url\":\"http:\\/\\/127.0.0.1:8000\\/staff\\/sieu-am\\/1\",\"meta\":{\"module\":\"sieu_am\",\"sieu_am_id\":1}}', NULL, '2025-12-19 17:58:38', '2025-12-19 17:58:38'),
('894103c8-718a-4351-bfbc-aa3886c932e6', 'App\\Notifications\\CustomNotification', 'App\\Models\\User', 4, '{\"title\":\"B\\u1ec7nh nh\\u00e2n x\\u00e1c nh\\u1eadn t\\u00e1i kh\\u00e1m\",\"message\":\"Y\\u00eau c\\u1ea7u t\\u00e1i kh\\u00e1m #2 \\u0111\\u00e3 \\u0111\\u01b0\\u1ee3c b\\u1ec7nh nh\\u00e2n x\\u00e1c nh\\u1eadn.\",\"action_url\":\"http:\\/\\/127.0.0.1:8000\\/doctor\\/tai-kham\\/2\\/show\"}', '2025-12-20 04:11:40', '2025-12-20 04:10:54', '2025-12-20 04:11:40'),
('8ab102f3-762a-4163-98ef-767303f30b8e', 'App\\Notifications\\MedicalUltrasoundResultUploaded', 'App\\Models\\User', 23, '{\"title\":\"\\u0110\\u00e3 c\\u00f3 k\\u1ebft qu\\u1ea3 si\\u00eau \\u00e2m\",\"message\":\"K\\u1ebft qu\\u1ea3 si\\u00eau \\u00e2m \\u0111\\u00e3 \\u0111\\u01b0\\u1ee3c c\\u1eadp nh\\u1eadt. B\\u1ea1n c\\u00f3 th\\u1ec3 xem v\\u00e0 t\\u1ea3i file k\\u1ebft qu\\u1ea3.\",\"action_url\":\"http:\\/\\/127.0.0.1:8000\\/sieu-am\\/1\",\"meta\":{\"module\":\"sieu_am\",\"sieu_am_id\":1}}', '2025-12-19 18:03:59', '2025-12-19 18:02:22', '2025-12-19 18:03:59'),
('e188c109-7a13-43d1-b587-876aee516c9f', 'App\\Notifications\\MedicalUltrasoundRequested', 'App\\Models\\User', 14, '{\"title\":\"C\\u00f3 ch\\u1ec9 \\u0111\\u1ecbnh si\\u00eau \\u00e2m m\\u1edbi\",\"message\":\"M\\u1ed9t ca si\\u00eau \\u00e2m m\\u1edbi v\\u1eeba \\u0111\\u01b0\\u1ee3c ch\\u1ec9 \\u0111\\u1ecbnh. Vui l\\u00f2ng v\\u00e0o danh s\\u00e1ch ch\\u1edd \\u0111\\u1ec3 x\\u1eed l\\u00fd.\",\"action_url\":\"http:\\/\\/127.0.0.1:8000\\/staff\\/sieu-am\\/1\",\"meta\":{\"module\":\"sieu_am\",\"sieu_am_id\":1}}', NULL, '2025-12-19 17:58:38', '2025-12-19 17:58:38'),
('fcc6bf22-0611-4a93-9762-052a9e29e966', 'App\\Notifications\\MedicalUltrasoundRequested', 'App\\Models\\User', 19, '{\"title\":\"C\\u00f3 ch\\u1ec9 \\u0111\\u1ecbnh si\\u00eau \\u00e2m m\\u1edbi\",\"message\":\"M\\u1ed9t ca si\\u00eau \\u00e2m m\\u1edbi v\\u1eeba \\u0111\\u01b0\\u1ee3c ch\\u1ec9 \\u0111\\u1ecbnh. Vui l\\u00f2ng v\\u00e0o danh s\\u00e1ch ch\\u1edd \\u0111\\u1ec3 x\\u1eed l\\u00fd.\",\"action_url\":\"http:\\/\\/127.0.0.1:8000\\/staff\\/sieu-am\\/1\",\"meta\":{\"module\":\"sieu_am\",\"sieu_am_id\":1}}', NULL, '2025-12-19 17:58:38', '2025-12-19 17:58:38'),
('fd4f33e9-4eb1-4391-bc50-914ef04db9eb', 'App\\Notifications\\MedicalUltrasoundRequested', 'App\\Models\\User', 16, '{\"title\":\"C\\u00f3 ch\\u1ec9 \\u0111\\u1ecbnh si\\u00eau \\u00e2m m\\u1edbi\",\"message\":\"M\\u1ed9t ca si\\u00eau \\u00e2m m\\u1edbi v\\u1eeba \\u0111\\u01b0\\u1ee3c ch\\u1ec9 \\u0111\\u1ecbnh. Vui l\\u00f2ng v\\u00e0o danh s\\u00e1ch ch\\u1edd \\u0111\\u1ec3 x\\u1eed l\\u00fd.\",\"action_url\":\"http:\\/\\/127.0.0.1:8000\\/staff\\/sieu-am\\/1\",\"meta\":{\"module\":\"sieu_am\",\"sieu_am_id\":1}}', NULL, '2025-12-19 17:58:38', '2025-12-19 17:58:38'),
('ffb8eb58-4895-43e8-9394-a0609bdbcf78', 'App\\Notifications\\CustomNotification', 'App\\Models\\User', 23, '{\"title\":\"\\u0110\\u00e3 \\u0111\\u1eb7t l\\u1ecbch t\\u00e1i kh\\u00e1m\",\"message\":\"Y\\u00eau c\\u1ea7u t\\u00e1i kh\\u00e1m #2 \\u0111\\u00e3 \\u0111\\u01b0\\u1ee3c \\u0111\\u1eb7t l\\u1ecbch.\",\"action_url\":\"http:\\/\\/127.0.0.1:8000\\/tai-kham\\/2\"}', '2025-12-20 04:13:28', '2025-12-20 04:12:45', '2025-12-20 04:13:28');

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
  `sms_appointment_cancelled` tinyint(1) NOT NULL DEFAULT 0,
  `reminder_hours_before` int(11) NOT NULL DEFAULT 24 COMMENT 'Nhắc trước bao nhiêu giờ',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `payment_logs`
--

CREATE TABLE `payment_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `hoa_don_id` bigint(20) UNSIGNED DEFAULT NULL,
  `payable_type` varchar(255) DEFAULT NULL,
  `payable_id` bigint(20) UNSIGNED DEFAULT NULL,
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

INSERT INTO `payment_logs` (`id`, `hoa_don_id`, `payable_type`, `payable_id`, `provider`, `event_type`, `idempotency_key`, `transaction_ref`, `result_code`, `result_message`, `payload`, `ip_address`, `user_agent`, `created_at`, `updated_at`) VALUES
(1, 1, NULL, NULL, 'VNPAY', 'request', NULL, NULL, NULL, NULL, '{\"vnp_Version\":\"2.1.0\",\"vnp_TmnCode\":\"I2U8WK7F\",\"vnp_Amount\":250000000,\"vnp_Command\":\"pay\",\"vnp_CreateDate\":\"20251220004634\",\"vnp_CurrCode\":\"VND\",\"vnp_IpAddr\":\"127.0.0.1\",\"vnp_Locale\":\"vn\",\"vnp_OrderInfo\":\"Thanh toan hoa don #1\",\"vnp_OrderType\":\"billpayment\",\"vnp_ReturnUrl\":\"http:\\/\\/127.0.0.1:8000\\/payment\\/vnpay-return\",\"vnp_TxnRef\":\"1\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-19 17:46:34', '2025-12-19 17:46:34'),
(2, 1, NULL, NULL, 'VNPAY', 'return', NULL, 'VNP15355638', '00', NULL, '{\"vnp_Amount\":\"250000000\",\"vnp_BankCode\":\"NCB\",\"vnp_BankTranNo\":\"VNP15355638\",\"vnp_CardType\":\"ATM\",\"vnp_OrderInfo\":\"Thanh toan hoa don #1\",\"vnp_PayDate\":\"20251220004714\",\"vnp_ResponseCode\":\"00\",\"vnp_TmnCode\":\"I2U8WK7F\",\"vnp_TransactionNo\":\"15355638\",\"vnp_TransactionStatus\":\"00\",\"vnp_TxnRef\":\"1\",\"vnp_SecureHash\":\"1ba21d5f088601bfe4a6acb9793fa29851dc4e245fac0afffa700be8ed6d3ffeb854e7a6f00293baf8df7f18e5f075647479a55d251becdc50e9a051a3103a9e\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-19 17:47:22', '2025-12-19 17:47:22'),
(3, 1, NULL, NULL, 'MOMO', 'request', NULL, NULL, NULL, NULL, '{\"partnerCode\":\"MOMOBKUN20180529\",\"partnerName\":\"Test\",\"storeId\":\"MomoTestStore\",\"requestId\":\"1766168359\",\"amount\":\"220000\",\"orderId\":\"1_1766168359\",\"orderInfo\":\"Thanh to\\u00e1n h\\u00f3a \\u0111\\u01a1n #1\",\"redirectUrl\":\"http:\\/\\/127.0.0.1:8000\\/payment\\/momo-return\",\"ipnUrl\":\"http:\\/\\/127.0.0.1:8000\\/payment\\/momo-ipn\",\"lang\":\"vi\",\"extraData\":\"\",\"requestType\":\"payWithMethod\",\"signature\":\"a8b66e63538efba9a7232ffbf12ef8d8beded466b0d59685b8bed48f69e843c9\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-19 18:19:19', '2025-12-19 18:19:19'),
(4, 1, NULL, NULL, 'MOMO', 'return', NULL, '4633649724', '0', 'Successful.', '{\"partnerCode\":\"MOMOBKUN20180529\",\"orderId\":\"1_1766168359\",\"requestId\":\"1766168359\",\"amount\":\"220000\",\"orderInfo\":\"Thanh to\\u00e1n h\\u00f3a \\u0111\\u01a1n #1\",\"orderType\":\"momo_wallet\",\"transId\":\"4633649724\",\"resultCode\":\"0\",\"message\":\"Successful.\",\"payType\":\"napas\",\"responseTime\":\"1766168421692\",\"extraData\":null,\"signature\":\"aa73d4189216c145f891097d79ebd35609c007ddfdb8bee0867186c27ae6112e\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-19 18:20:27', '2025-12-19 18:20:27'),
(5, 1, NULL, NULL, 'VNPAY', 'request', NULL, NULL, NULL, NULL, '{\"vnp_Version\":\"2.1.0\",\"vnp_TmnCode\":\"I2U8WK7F\",\"vnp_Amount\":15000000,\"vnp_Command\":\"pay\",\"vnp_CreateDate\":\"20251220083601\",\"vnp_CurrCode\":\"VND\",\"vnp_IpAddr\":\"127.0.0.1\",\"vnp_Locale\":\"vn\",\"vnp_OrderInfo\":\"Thanh toan hoa don #1\",\"vnp_OrderType\":\"billpayment\",\"vnp_ReturnUrl\":\"http:\\/\\/127.0.0.1:8000\\/payment\\/vnpay-return\",\"vnp_TxnRef\":\"1\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-20 01:36:01', '2025-12-20 01:36:01'),
(6, 1, NULL, NULL, 'MOMO', 'request', NULL, NULL, NULL, NULL, '{\"partnerCode\":\"MOMOBKUN20180529\",\"partnerName\":\"Test\",\"storeId\":\"MomoTestStore\",\"requestId\":\"1766194564\",\"amount\":\"150000\",\"orderId\":\"1_1766194564\",\"orderInfo\":\"Thanh to\\u00e1n h\\u00f3a \\u0111\\u01a1n #1\",\"redirectUrl\":\"http:\\/\\/127.0.0.1:8000\\/payment\\/momo-return\",\"ipnUrl\":\"http:\\/\\/127.0.0.1:8000\\/payment\\/momo-ipn\",\"lang\":\"vi\",\"extraData\":\"\",\"requestType\":\"payWithMethod\",\"signature\":\"15089622cf5411a5954d2bff20e17114b43cdd4db38c64e0a2f10ef948776f0c\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-20 01:36:04', '2025-12-20 01:36:04'),
(7, 1, NULL, NULL, 'MOMO', 'return', NULL, '4633864111', '0', 'Successful.', '{\"partnerCode\":\"MOMOBKUN20180529\",\"orderId\":\"1_1766194564\",\"requestId\":\"1766194564\",\"amount\":\"150000\",\"orderInfo\":\"Thanh to\\u00e1n h\\u00f3a \\u0111\\u01a1n #1\",\"orderType\":\"momo_wallet\",\"transId\":\"4633864111\",\"resultCode\":\"0\",\"message\":\"Successful.\",\"payType\":\"napas\",\"responseTime\":\"1766194620482\",\"extraData\":null,\"signature\":\"9095ecab942ca7173fb898fd92b895d942153a14234546af04b9d69f570fc0e2\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-20 01:37:05', '2025-12-20 01:37:05');

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
(1, 'view-users', 'web', '2025-12-19 16:00:43', '2025-12-19 16:00:43'),
(2, 'create-users', 'web', '2025-12-19 16:00:43', '2025-12-19 16:00:43'),
(3, 'edit-users', 'web', '2025-12-19 16:00:43', '2025-12-19 16:00:43'),
(4, 'delete-users', 'web', '2025-12-19 16:00:43', '2025-12-19 16:00:43'),
(5, 'lock-users', 'web', '2025-12-19 16:00:43', '2025-12-19 16:00:43'),
(6, 'unlock-users', 'web', '2025-12-19 16:00:43', '2025-12-19 16:00:43'),
(7, 'assign-roles', 'web', '2025-12-19 16:00:43', '2025-12-19 16:00:43'),
(8, 'view-roles', 'web', '2025-12-19 16:00:43', '2025-12-19 16:00:43'),
(9, 'create-roles', 'web', '2025-12-19 16:00:43', '2025-12-19 16:00:43'),
(10, 'edit-roles', 'web', '2025-12-19 16:00:43', '2025-12-19 16:00:43'),
(11, 'delete-roles', 'web', '2025-12-19 16:00:43', '2025-12-19 16:00:43'),
(12, 'view-permissions', 'web', '2025-12-19 16:00:43', '2025-12-19 16:00:43'),
(13, 'create-permissions', 'web', '2025-12-19 16:00:43', '2025-12-19 16:00:43'),
(14, 'delete-permissions', 'web', '2025-12-19 16:00:43', '2025-12-19 16:00:43'),
(15, 'view-doctors', 'web', '2025-12-19 16:00:43', '2025-12-19 16:00:43'),
(16, 'create-doctors', 'web', '2025-12-19 16:00:43', '2025-12-19 16:00:43'),
(17, 'edit-doctors', 'web', '2025-12-19 16:00:43', '2025-12-19 16:00:43'),
(18, 'delete-doctors', 'web', '2025-12-19 16:00:43', '2025-12-19 16:00:43'),
(19, 'manage-schedules', 'web', '2025-12-19 16:00:43', '2025-12-19 16:00:43'),
(20, 'view-appointments', 'web', '2025-12-19 16:00:43', '2025-12-19 16:00:43'),
(21, 'create-appointments', 'web', '2025-12-19 16:00:43', '2025-12-19 16:00:43'),
(22, 'edit-appointments', 'web', '2025-12-19 16:00:43', '2025-12-19 16:00:43'),
(23, 'cancel-appointments', 'web', '2025-12-19 16:00:43', '2025-12-19 16:00:43'),
(24, 'confirm-appointments', 'web', '2025-12-19 16:00:43', '2025-12-19 16:00:43'),
(25, 'view-medical-records', 'web', '2025-12-19 16:00:43', '2025-12-19 16:00:43'),
(26, 'create-medical-records', 'web', '2025-12-19 16:00:43', '2025-12-19 16:00:43'),
(27, 'edit-medical-records', 'web', '2025-12-19 16:00:43', '2025-12-19 16:00:43'),
(28, 'delete-medical-records', 'web', '2025-12-19 16:00:43', '2025-12-19 16:00:43'),
(29, 'view-services', 'web', '2025-12-19 16:00:43', '2025-12-19 16:00:43'),
(30, 'create-services', 'web', '2025-12-19 16:00:43', '2025-12-19 16:00:43'),
(31, 'edit-services', 'web', '2025-12-19 16:00:43', '2025-12-19 16:00:43'),
(32, 'delete-services', 'web', '2025-12-19 16:00:43', '2025-12-19 16:00:43'),
(33, 'view-medicines', 'web', '2025-12-19 16:00:43', '2025-12-19 16:00:43'),
(34, 'create-medicines', 'web', '2025-12-19 16:00:43', '2025-12-19 16:00:43'),
(35, 'edit-medicines', 'web', '2025-12-19 16:00:43', '2025-12-19 16:00:43'),
(36, 'delete-medicines', 'web', '2025-12-19 16:00:43', '2025-12-19 16:00:43'),
(37, 'manage-inventory', 'web', '2025-12-19 16:00:43', '2025-12-19 16:00:43'),
(38, 'view-inventory-reports', 'web', '2025-12-19 16:00:43', '2025-12-19 16:00:43'),
(39, 'view-staff', 'web', '2025-12-19 16:00:43', '2025-12-19 16:00:43'),
(40, 'create-staff', 'web', '2025-12-19 16:00:43', '2025-12-19 16:00:43'),
(41, 'edit-staff', 'web', '2025-12-19 16:00:43', '2025-12-19 16:00:43'),
(42, 'delete-staff', 'web', '2025-12-19 16:00:43', '2025-12-19 16:00:43'),
(43, 'view-staff-shifts', 'web', '2025-12-19 16:00:43', '2025-12-19 16:00:43'),
(44, 'assign-staff-shifts', 'web', '2025-12-19 16:00:43', '2025-12-19 16:00:43'),
(45, 'view-invoices', 'web', '2025-12-19 16:00:43', '2025-12-19 16:00:43'),
(46, 'create-invoices', 'web', '2025-12-19 16:00:43', '2025-12-19 16:00:43'),
(47, 'edit-invoices', 'web', '2025-12-19 16:00:43', '2025-12-19 16:00:43'),
(48, 'delete-invoices', 'web', '2025-12-19 16:00:43', '2025-12-19 16:00:43'),
(49, 'process-payments', 'web', '2025-12-19 16:00:43', '2025-12-19 16:00:43'),
(50, 'refund-payments', 'web', '2025-12-19 16:00:43', '2025-12-19 16:00:43'),
(51, 'view-payment-logs', 'web', '2025-12-19 16:00:43', '2025-12-19 16:00:43'),
(52, 'view-reports', 'web', '2025-12-19 16:00:43', '2025-12-19 16:00:43'),
(53, 'view-revenue-reports', 'web', '2025-12-19 16:00:43', '2025-12-19 16:00:43'),
(54, 'view-appointment-reports', 'web', '2025-12-19 16:00:43', '2025-12-19 16:00:43'),
(55, 'export-data', 'web', '2025-12-19 16:00:43', '2025-12-19 16:00:43'),
(56, 'view-dashboard', 'web', '2025-12-19 16:00:43', '2025-12-19 16:00:43'),
(57, 'send-notifications', 'web', '2025-12-19 16:00:43', '2025-12-19 16:00:43');

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
(1, 'PN-SEED-1', '2025-10-06', 4, NULL, 8760531.00, 'Seed PN #1', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(2, 'PN-SEED-2', '2025-10-23', 7, NULL, 16243385.00, 'Seed PN #2', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(3, 'PN-SEED-3', '2025-12-12', 15, NULL, 11057171.00, 'Seed PN #3', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(4, 'PN-SEED-4', '2025-10-27', 6, NULL, 16586942.00, 'Seed PN #4', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(5, 'PN-SEED-5', '2025-12-08', 14, NULL, 18156026.00, 'Seed PN #5', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(6, 'PN-SEED-6', '2025-11-10', 6, NULL, 8421378.00, 'Seed PN #6', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(7, 'PN-SEED-7', '2025-10-16', 4, NULL, 11132818.00, 'Seed PN #7', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(8, 'PN-SEED-8', '2025-11-26', 13, NULL, 16358984.00, 'Seed PN #8', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(9, 'PN-SEED-9', '2025-10-05', 3, NULL, 14823100.00, 'Seed PN #9', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(10, 'PN-SEED-10', '2025-11-28', 8, NULL, 14786647.00, 'Seed PN #10', '2025-12-19 16:00:46', '2025-12-19 16:00:46');

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
(1, 1, 156, 'LOT-PN-1-566', '2027-01-19', 381, 7292.00, 2778252.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(2, 1, 106, 'LOT-PN-1-63', '2026-06-19', 183, 3400.00, 622200.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(3, 1, 89, 'LOT-PN-1-347', '2026-10-19', 462, 1171.00, 541002.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(4, 1, 170, 'LOT-PN-1-481', '2028-08-19', 131, 7174.00, 939794.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(5, 1, 70, 'LOT-PN-1-352', '2026-12-19', 23, 2507.00, 57661.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(6, 1, 32, 'LOT-PN-1-531', '2028-03-19', 160, 7770.00, 1243200.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(7, 1, 37, 'LOT-PN-1-39', '2028-07-19', 198, 8868.00, 1755864.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(8, 1, 108, 'LOT-PN-1-686', '2028-03-19', 242, 3399.00, 822558.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(9, 2, 82, 'LOT-PN-2-401', '2026-08-19', 198, 1679.00, 332442.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(10, 2, 106, 'LOT-PN-2-759', '2027-09-19', 122, 4238.00, 517036.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(11, 2, 195, 'LOT-PN-2-421', '2028-06-19', 452, 9181.00, 4149812.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(12, 2, 189, 'LOT-PN-2-718', '2028-01-19', 389, 8170.00, 3178130.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(13, 2, 2, 'LOT-PN-2-40', '2026-09-19', 385, 6685.00, 2573725.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(14, 2, 128, 'LOT-PN-2-492', '2026-06-19', 112, 9320.00, 1043840.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(15, 2, 134, 'LOT-PN-2-3', '2026-06-19', 481, 6756.00, 3249636.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(16, 2, 85, 'LOT-PN-2-68', '2027-03-19', 50, 796.00, 39800.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(17, 2, 137, 'LOT-PN-2-59', '2027-02-19', 422, 1415.00, 597130.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(18, 2, 24, 'LOT-PN-2-303', '2028-08-19', 63, 8918.00, 561834.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(19, 3, 58, 'LOT-PN-3-521', '2026-11-19', 77, 4710.00, 362670.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(20, 3, 71, 'LOT-PN-3-434', '2026-11-19', 448, 7785.00, 3487680.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(21, 3, 166, 'LOT-PN-3-45', '2027-10-19', 56, 1933.00, 108248.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(22, 3, 185, 'LOT-PN-3-52', '2027-11-19', 88, 9535.00, 839080.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(23, 3, 165, 'LOT-PN-3-302', '2026-11-19', 337, 4322.00, 1456514.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(24, 3, 103, 'LOT-PN-3-470', '2026-08-19', 425, 8411.00, 3574675.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(25, 3, 75, 'LOT-PN-3-540', '2026-09-19', 154, 7976.00, 1228304.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(26, 4, 122, 'LOT-PN-4-248', '2027-05-19', 453, 4230.00, 1916190.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(27, 4, 77, 'LOT-PN-4-47', '2027-07-19', 434, 4643.00, 2015062.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(28, 4, 30, 'LOT-PN-4-171', '2028-10-19', 293, 6140.00, 1799020.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(29, 4, 74, 'LOT-PN-4-918', '2027-04-19', 33, 9053.00, 298749.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(30, 4, 178, 'LOT-PN-4-847', '2028-03-19', 361, 6017.00, 2172137.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(31, 4, 73, 'LOT-PN-4-942', '2028-12-19', 96, 7287.00, 699552.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(32, 4, 63, 'LOT-PN-4-204', '2027-11-19', 189, 9107.00, 1721223.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(33, 4, 107, 'LOT-PN-4-453', '2026-06-19', 114, 7231.00, 824334.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(34, 4, 170, 'LOT-PN-4-82', '2027-05-19', 125, 1771.00, 221375.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(35, 4, 144, 'LOT-PN-4-18', '2027-12-19', 92, 8249.00, 758908.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(36, 4, 186, 'LOT-PN-4-356', '2028-09-19', 266, 2804.00, 745864.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(37, 4, 81, 'LOT-PN-4-253', '2027-10-19', 494, 6912.00, 3414528.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(38, 5, 193, 'LOT-PN-5-553', '2028-09-19', 246, 2401.00, 590646.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(39, 5, 112, 'LOT-PN-5-973', '2027-12-19', 236, 3570.00, 842520.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(40, 5, 106, 'LOT-PN-5-600', '2028-02-19', 225, 6467.00, 1455075.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(41, 5, 128, 'LOT-PN-5-989', '2027-09-19', 274, 7406.00, 2029244.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(42, 5, 44, 'LOT-PN-5-89', '2027-08-19', 301, 5006.00, 1506806.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(43, 5, 168, 'LOT-PN-5-435', '2028-11-19', 499, 2954.00, 1474046.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(44, 5, 137, 'LOT-PN-5-681', '2026-11-19', 246, 928.00, 228288.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(45, 5, 27, 'LOT-PN-5-92', '2028-05-19', 103, 4689.00, 482967.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(46, 5, 169, 'LOT-PN-5-514', '2027-07-19', 347, 9051.00, 3140697.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(47, 5, 5, 'LOT-PN-5-368', '2028-09-19', 198, 4110.00, 813780.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(48, 5, 124, 'LOT-PN-5-180', '2026-10-19', 52, 4159.00, 216268.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(49, 5, 103, 'LOT-PN-5-26', '2028-10-19', 210, 695.00, 145950.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(50, 5, 172, 'LOT-PN-5-627', '2027-10-19', 157, 9818.00, 1541426.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(51, 5, 35, 'LOT-PN-5-254', '2028-06-19', 475, 7427.00, 3527825.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(52, 5, 120, 'LOT-PN-5-362', '2027-05-19', 108, 1486.00, 160488.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(53, 6, 22, 'LOT-PN-6-900', '2027-11-19', 80, 9845.00, 787600.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(54, 6, 20, 'LOT-PN-6-240', '2026-10-19', 296, 9073.00, 2685608.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(55, 6, 8, 'LOT-PN-6-290', '2028-12-19', 60, 9116.00, 546960.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(56, 6, 29, 'LOT-PN-6-668', '2028-12-19', 49, 4130.00, 202370.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(57, 6, 130, 'LOT-PN-6-599', '2026-10-19', 88, 4861.00, 427768.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(58, 6, 191, 'LOT-PN-6-718', '2027-02-19', 76, 4308.00, 327408.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(59, 6, 119, 'LOT-PN-6-420', '2028-12-19', 383, 4756.00, 1821548.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(60, 6, 161, 'LOT-PN-6-630', '2028-05-19', 457, 2698.00, 1232986.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(61, 6, 111, 'LOT-PN-6-677', '2026-10-19', 255, 1526.00, 389130.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(62, 7, 150, 'LOT-PN-7-759', '2026-06-19', 71, 820.00, 58220.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(63, 7, 3, 'LOT-PN-7-538', '2028-03-19', 213, 1873.00, 398949.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(64, 7, 12, 'LOT-PN-7-164', '2028-10-19', 199, 8441.00, 1679759.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(65, 7, 179, 'LOT-PN-7-705', '2027-06-19', 267, 1460.00, 389820.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(66, 7, 7, 'LOT-PN-7-153', '2028-02-19', 28, 8540.00, 239120.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(67, 7, 119, 'LOT-PN-7-788', '2028-04-19', 72, 9265.00, 667080.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(68, 7, 148, 'LOT-PN-7-466', '2027-07-19', 127, 6103.00, 775081.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(69, 7, 177, 'LOT-PN-7-471', '2027-02-19', 314, 5504.00, 1728256.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(70, 7, 65, 'LOT-PN-7-825', '2027-04-19', 277, 8167.00, 2262259.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(71, 7, 107, 'LOT-PN-7-805', '2027-05-19', 375, 6660.00, 2497500.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(72, 7, 95, 'LOT-PN-7-265', '2026-08-19', 214, 2041.00, 436774.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(73, 8, 99, 'LOT-PN-8-884', '2027-12-19', 288, 4294.00, 1236672.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(74, 8, 197, 'LOT-PN-8-517', '2027-03-19', 288, 1771.00, 510048.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(75, 8, 36, 'LOT-PN-8-697', '2026-10-19', 90, 9614.00, 865260.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(76, 8, 2, 'LOT-PN-8-863', '2027-02-19', 403, 9249.00, 3727347.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(77, 8, 66, 'LOT-PN-8-654', '2028-07-19', 48, 5577.00, 267696.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(78, 8, 151, 'LOT-PN-8-329', '2028-04-19', 458, 5852.00, 2680216.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(79, 8, 130, 'LOT-PN-8-331', '2027-06-19', 166, 1403.00, 232898.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(80, 8, 13, 'LOT-PN-8-101', '2026-08-19', 164, 7749.00, 1270836.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(81, 8, 165, 'LOT-PN-8-835', '2028-11-19', 454, 2194.00, 996076.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(82, 8, 84, 'LOT-PN-8-243', '2027-01-19', 225, 3986.00, 896850.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(83, 8, 182, 'LOT-PN-8-74', '2026-07-19', 113, 7479.00, 845127.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(84, 8, 187, 'LOT-PN-8-773', '2027-06-19', 295, 8746.00, 2580070.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(85, 8, 166, 'LOT-PN-8-991', '2026-10-19', 96, 2603.00, 249888.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(86, 9, 136, 'LOT-PN-9-475', '2028-03-19', 152, 7348.00, 1116896.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(87, 9, 170, 'LOT-PN-9-384', '2028-06-19', 496, 7065.00, 3504240.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(88, 9, 24, 'LOT-PN-9-63', '2027-04-19', 94, 9252.00, 869688.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(89, 9, 102, 'LOT-PN-9-634', '2027-02-19', 186, 662.00, 123132.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(90, 9, 164, 'LOT-PN-9-355', '2028-11-19', 105, 3738.00, 392490.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(91, 9, 30, 'LOT-PN-9-680', '2027-04-19', 440, 7051.00, 3102440.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(92, 9, 115, 'LOT-PN-9-797', '2026-10-19', 317, 6804.00, 2156868.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(93, 9, 95, 'LOT-PN-9-949', '2026-10-19', 233, 1959.00, 456447.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(94, 9, 40, 'LOT-PN-9-190', '2028-11-19', 46, 9447.00, 434562.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(95, 9, 86, 'LOT-PN-9-690', '2027-09-19', 289, 5417.00, 1565513.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(96, 9, 96, 'LOT-PN-9-941', '2028-10-19', 317, 2969.00, 941173.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(97, 9, 124, 'LOT-PN-9-43', '2026-07-19', 243, 657.00, 159651.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(98, 10, 16, 'LOT-PN-10-388', '2027-11-19', 279, 3994.00, 1114326.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(99, 10, 72, 'LOT-PN-10-684', '2027-01-19', 183, 4439.00, 812337.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(100, 10, 88, 'LOT-PN-10-277', '2028-02-19', 239, 5989.00, 1431371.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(101, 10, 18, 'LOT-PN-10-625', '2028-02-19', 340, 3688.00, 1253920.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(102, 10, 196, 'LOT-PN-10-977', '2026-07-19', 255, 6966.00, 1776330.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(103, 10, 22, 'LOT-PN-10-65', '2027-03-19', 357, 9340.00, 3334380.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(104, 10, 98, 'LOT-PN-10-133', '2028-06-19', 275, 2065.00, 567875.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(105, 10, 28, 'LOT-PN-10-282', '2028-11-19', 66, 2310.00, 152460.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(106, 10, 174, 'LOT-PN-10-901', '2028-09-19', 80, 6831.00, 546480.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(107, 10, 2, 'LOT-PN-10-903', '2026-10-19', 282, 9042.00, 2549844.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(108, 10, 173, 'LOT-PN-10-361', '2028-12-19', 442, 2822.00, 1247324.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46');

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
(1, 'PX-SEED-1', '2025-12-01', NULL, 'Khach le', 2381073.00, 'Seed PX #1', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(2, 'PX-SEED-2', '2025-12-12', NULL, 'Khach le', 1353762.00, 'Seed PX #2', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(3, 'PX-SEED-3', '2025-11-25', NULL, 'Khach le', 1782985.00, 'Seed PX #3', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(4, 'PX-SEED-4', '2025-12-07', NULL, 'Khach le', 2427175.00, 'Seed PX #4', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(5, 'PX-SEED-5', '2025-12-08', NULL, 'Khach le', 2772562.00, 'Seed PX #5', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(6, 'PX-SEED-6', '2025-12-03', NULL, 'Khach le', 2190744.00, 'Seed PX #6', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(7, 'PX-SEED-7', '2025-12-13', NULL, 'Khach le', 1598503.00, 'Seed PX #7', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(8, 'PX-SEED-8', '2025-12-17', NULL, 'Khach le', 1608674.00, 'Seed PX #8', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(9, 'PX-SEED-9', '2025-12-04', NULL, 'Khach le', 1534938.00, 'Seed PX #9', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(10, 'PX-SEED-10', '2025-12-05', NULL, 'Khach le', 3199764.00, 'Seed PX #10', '2025-12-19 16:00:46', '2025-12-19 16:00:46');

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
(1, 1, 95, 94, 11768.00, 1106192.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(2, 1, 59, 23, 1045.00, 24035.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(3, 1, 29, 49, 1508.00, 73892.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(4, 1, 29, 19, 1508.00, 28652.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(5, 1, 186, 79, 2928.00, 231312.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(6, 1, 51, 13, 10125.00, 131625.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(7, 1, 10, 85, 6674.00, 567290.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(8, 1, 188, 55, 3965.00, 218075.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(9, 2, 36, 18, 11288.00, 203184.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(10, 2, 133, 7, 5474.00, 38318.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(11, 2, 126, 96, 4013.00, 385248.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(12, 2, 41, 82, 8866.00, 727012.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(13, 3, 152, 74, 9506.00, 703444.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(14, 3, 176, 23, 7219.00, 166037.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(15, 3, 153, 22, 8358.00, 183876.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(16, 3, 65, 36, 11826.00, 425736.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(17, 3, 114, 34, 5502.00, 187068.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(18, 3, 89, 34, 3436.00, 116824.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(19, 4, 12, 41, 4680.00, 191880.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(20, 4, 30, 3, 6463.00, 19389.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(21, 4, 144, 53, 5960.00, 315880.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(22, 4, 111, 98, 6755.00, 661990.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(23, 4, 177, 79, 10524.00, 831396.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(24, 4, 133, 23, 8504.00, 195592.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(25, 4, 154, 31, 6808.00, 211048.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(26, 5, 97, 95, 5944.00, 564680.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(27, 5, 190, 2, 11235.00, 22470.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(28, 5, 93, 75, 5070.00, 380250.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(29, 5, 91, 51, 5736.00, 292536.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(30, 5, 62, 75, 9390.00, 704250.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(31, 5, 193, 14, 10035.00, 140490.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(32, 5, 136, 15, 11632.00, 174480.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(33, 5, 128, 58, 8507.00, 493406.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(34, 6, 117, 89, 7457.00, 663673.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(35, 6, 107, 61, 8208.00, 500688.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(36, 6, 144, 5, 4278.00, 21390.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(37, 6, 185, 76, 8954.00, 680504.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(38, 6, 27, 33, 9833.00, 324489.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(39, 7, 184, 32, 11767.00, 376544.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(40, 7, 121, 44, 7458.00, 328152.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(41, 7, 112, 61, 7907.00, 482327.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(42, 7, 3, 76, 2964.00, 225264.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(43, 7, 106, 16, 6639.00, 106224.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(44, 7, 138, 22, 3636.00, 79992.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(45, 8, 72, 40, 2141.00, 85640.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(46, 8, 7, 28, 2853.00, 79884.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(47, 8, 7, 60, 2853.00, 171180.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(48, 8, 113, 48, 11025.00, 529200.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(49, 8, 182, 60, 7154.00, 429240.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(50, 8, 93, 30, 10451.00, 313530.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(51, 9, 32, 81, 5579.00, 451899.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(52, 9, 191, 66, 10517.00, 694122.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(53, 9, 73, 79, 4923.00, 388917.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(54, 10, 31, 73, 9573.00, 698829.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(55, 10, 169, 52, 5571.00, 289692.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(56, 10, 86, 60, 11012.00, 660720.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(57, 10, 74, 33, 9719.00, 320727.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(58, 10, 74, 66, 9719.00, 641454.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(59, 10, 197, 27, 1223.00, 33021.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(60, 10, 17, 87, 6383.00, 555321.00, '2025-12-19 16:00:46', '2025-12-19 16:00:46');

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
(1, 'Phòng khám Sản 01 (VIP)', 'phong_kham', 'Phòng khám tiêu chuẩn VIP, không gian riêng tư, trang bị ghế khám thông minh.', 'Sẵn sàng', 'Tầng 1, Khu A (Cạnh lễ tân)', 25.00, 3, '2025-12-19 16:00:44', '2025-12-19 16:00:44'),
(2, 'Phòng khám Sản 02', 'phong_kham', 'Chuyên khám thai định kỳ và tư vấn dinh dưỡng.', 'Sẵn sàng', 'Tầng 1, Khu A', 20.00, 3, '2025-12-19 16:00:44', '2025-12-19 16:00:44'),
(3, 'Phòng Siêu âm Chẩn đoán hình ảnh', 'phong_xet_nghiem', 'Trang bị máy siêu âm Voluson E10 hiện đại nhất, màn hình LED lớn cho gia đình cùng xem.', 'Sẵn sàng', 'Tầng 1, Khu B (Đối diện phòng khám Sản)', 30.00, 5, '2025-12-19 16:00:44', '2025-12-19 16:00:44'),
(4, 'Phòng khám Phụ khoa 01', 'phong_kham', 'Phòng khám phụ khoa tổng quát, có khu vực thay đồ kín đáo cho khách hàng.', 'Sẵn sàng', 'Tầng 2, Khu A', 20.00, 3, '2025-12-19 16:00:44', '2025-12-19 16:00:44'),
(5, 'Phòng Thủ thuật Kế hoạch hóa GĐ', 'phong_thu_thuat', 'Chuyên thực hiện cấy que, đặt vòng, hút thai an toàn. Đảm bảo tiêu chuẩn vô khuẩn tuyệt đối.', 'Sẵn sàng', 'Tầng 2, Khu B (Khu vực vô trùng)', 35.00, 4, '2025-12-19 16:00:44', '2025-12-19 16:00:44'),
(6, 'Phòng Laser Thẩm mỹ & Sàn chậu', 'phong_chuc_nang', 'Trang bị máy Laser CO2 và máy tập sàn chậu Biofeedback. Không gian Spa thư giãn.', 'Sẵn sàng', 'Tầng 2, Khu C (Khu vực yên tĩnh)', 25.00, 2, '2025-12-19 16:00:44', '2025-12-19 16:00:44'),
(7, 'Phòng Tư vấn Vô sinh - Hiếm muộn', 'phong_kham', 'Không gian tư vấn riêng tư, kín đáo cho các cặp đôi.', 'Sẵn sàng', 'Tầng 3, Khu A', 25.00, 4, '2025-12-19 16:00:44', '2025-12-19 16:00:44'),
(8, 'Phòng Lab IUI & Lọc rửa tinh trùng', 'phong_thu_thuat', 'Phòng Lab đạt chuẩn ISO, kiểm soát nhiệt độ và độ ẩm nghiêm ngặt để nuôi cấy phôi/tinh trùng.', 'Sẵn sàng', 'Tầng 3, Khu B (Cách ly đặc biệt)', 40.00, 5, '2025-12-19 16:00:44', '2025-12-19 16:00:44'),
(9, 'Trung tâm Xét nghiệm (Labo)', 'phong_xet_nghiem', 'Hệ thống máy xét nghiệm tự động hoàn toàn (Roche/Abbott).', 'Sẵn sàng', 'Tầng 3, Khu C', 50.00, 10, '2025-12-19 16:00:44', '2025-12-19 16:00:44'),
(10, 'Phòng Tư vấn Sàng lọc Trước sinh', 'phong_kham', 'Tư vấn chuyên sâu về kết quả NIPT và chọc ối.', 'Sẵn sàng', 'Tầng 1, Khu A (Gần khu lấy máu)', 15.00, 3, '2025-12-19 16:00:44', '2025-12-19 16:00:44');

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
(1, 'super-admin', 'web', '2025-12-19 16:00:43', '2025-12-19 16:00:43'),
(2, 'admin', 'web', '2025-12-19 16:00:43', '2025-12-19 16:00:43'),
(3, 'manager', 'web', '2025-12-19 16:00:43', '2025-12-19 16:00:43'),
(4, 'doctor', 'web', '2025-12-19 16:00:43', '2025-12-19 16:00:43'),
(5, 'staff', 'web', '2025-12-19 16:00:43', '2025-12-19 16:00:43'),
(6, 'patient', 'web', '2025-12-19 16:00:43', '2025-12-19 16:00:43'),
(7, 'accountant', 'web', '2025-12-19 16:00:43', '2025-12-19 16:00:43'),
(8, 'pharmacist', 'web', '2025-12-19 16:00:43', '2025-12-19 16:00:43');

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
(25, 2),
(25, 4),
(25, 5),
(26, 1),
(26, 2),
(26, 4),
(27, 1),
(27, 2),
(27, 4),
(28, 1),
(28, 2),
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
(56, 3),
(56, 5),
(57, 1),
(57, 2);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `sieu_ams`
--

CREATE TABLE `sieu_ams` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `benh_an_id` bigint(20) UNSIGNED NOT NULL,
  `bac_si_chi_dinh_id` bigint(20) UNSIGNED NOT NULL,
  `loai_sieu_am_id` bigint(20) UNSIGNED DEFAULT NULL,
  `bac_si_sieu_am_id` bigint(20) UNSIGNED DEFAULT NULL,
  `phong_id` bigint(20) UNSIGNED DEFAULT NULL,
  `loai` varchar(255) NOT NULL,
  `mo_ta` text DEFAULT NULL,
  `gia` decimal(12,2) NOT NULL DEFAULT 0.00,
  `file_path` varchar(255) DEFAULT NULL,
  `disk` varchar(255) NOT NULL DEFAULT 'sieu_am_private',
  `ket_qua` text DEFAULT NULL,
  `nhan_xet` text DEFAULT NULL,
  `trang_thai` varchar(50) NOT NULL DEFAULT 'pending',
  `ngay_chi_dinh` timestamp NOT NULL DEFAULT current_timestamp(),
  `ngay_thuc_hien` timestamp NULL DEFAULT NULL,
  `ngay_hoan_thanh` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `sieu_ams`
--

INSERT INTO `sieu_ams` (`id`, `user_id`, `benh_an_id`, `bac_si_chi_dinh_id`, `loai_sieu_am_id`, `bac_si_sieu_am_id`, `phong_id`, `loai`, `mo_ta`, `gia`, `file_path`, `disk`, `ket_qua`, `nhan_xet`, `trang_thai`, `ngay_chi_dinh`, `ngay_thuc_hien`, `ngay_hoan_thanh`, `created_at`, `updated_at`) VALUES
(1, 23, 1, 4, 7, NULL, 3, 'Siêu âm phụ khoa qua bụng', 'Khám Gói Khám Phụ khoa Tổng quát (VIP)', 150000.00, 'sieu_am/VtlQIGtNmG32jErRat0nH054A9BinbnktNgZmWlZ.png', 'sieu_am_private', 'Tư vấn chuyên sâu Vô sinh - Hiếm muộn', 'Tư vấn chuyên sâu Vô sinh - Hiếm muộn', 'completed', '2025-12-19 17:58:37', '2025-12-19 18:02:22', '2025-12-19 18:02:22', '2025-12-19 17:58:37', '2025-12-19 18:03:06');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `slot_locks`
--

CREATE TABLE `slot_locks` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `bac_si_id` bigint(20) UNSIGNED NOT NULL,
  `ngay` date NOT NULL,
  `gio` time NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
(1, 'Dinh dưỡng bà bầu', 'dinh-duong-ba-bau', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(2, 'Lịch khám thai', 'lich-kham-thai', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(3, 'Thụ tinh ống nghiệm (IVF)', 'thu-tinh-ong-nghiem-ivf', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(4, 'Sàng lọc trước sinh', 'sang-loc-truoc-sinh', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(5, 'Ưu đãi', 'uu-dai', '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(6, 'Sale', 'sale', '2025-12-19 16:00:46', '2025-12-19 16:00:46');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tai_khams`
--

CREATE TABLE `tai_khams` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `benh_an_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `bac_si_id` bigint(20) UNSIGNED DEFAULT NULL,
  `lich_hen_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ngay_tai_kham` date DEFAULT NULL,
  `thoi_gian_tai_kham` time DEFAULT NULL,
  `so_ngay_du_kien` tinyint(3) UNSIGNED DEFAULT NULL,
  `ly_do` text DEFAULT NULL,
  `ghi_chu` text DEFAULT NULL,
  `trang_thai` varchar(255) NOT NULL DEFAULT 'Chờ xác nhận',
  `created_by_role` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `tai_khams`
--

INSERT INTO `tai_khams` (`id`, `benh_an_id`, `user_id`, `bac_si_id`, `lich_hen_id`, `ngay_tai_kham`, `thoi_gian_tai_kham`, `so_ngay_du_kien`, `ly_do`, `ghi_chu`, `trang_thai`, `created_by_role`, `created_at`, `updated_at`, `deleted_at`) VALUES
(2, 1, 23, 4, 2, '2025-12-23', '13:40:00', 2, 'Huyết áp và đường huyết cao. Yêu cầu làm thêm xét nghiệm dung nạp đường và protein niệu. Hẹn tái khám sau 3 ngày.', 'Huyết áp và đường huyết cao. Yêu cầu làm thêm xét nghiệm dung nạp đường và protein niệu. Hẹn tái khám sau 3 ngày.', 'Đã đặt lịch', 'doctor', '2025-12-20 04:06:20', '2025-12-20 04:12:45', NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `thanh_toans`
--

CREATE TABLE `thanh_toans` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `hoa_don_id` bigint(20) UNSIGNED DEFAULT NULL,
  `payable_type` varchar(255) DEFAULT NULL,
  `payable_id` bigint(20) UNSIGNED DEFAULT NULL,
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

INSERT INTO `thanh_toans` (`id`, `hoa_don_id`, `payable_type`, `payable_id`, `provider`, `so_tien`, `tien_te`, `trang_thai`, `transaction_ref`, `idempotency_key`, `paid_at`, `payload`, `created_at`, `updated_at`) VALUES
(1, 1, NULL, NULL, 'VNPAY', 2500000.00, 'VND', 'Thành công', 'VNP15355638', '1_VNP15355638', '2025-12-19 17:47:22', NULL, '2025-12-19 17:47:22', '2025-12-19 17:47:22'),
(2, 1, NULL, NULL, 'MOMO', 220000.00, 'VND', 'Thành công', '4633649724', '1_4633649724', '2025-12-19 18:20:27', NULL, '2025-12-19 18:20:27', '2025-12-19 18:20:27'),
(3, 1, NULL, NULL, 'MOMO', 150000.00, 'VND', 'Thành công', '4633864111', '1_4633864111', '2025-12-20 01:37:05', NULL, '2025-12-20 01:37:05', '2025-12-20 01:37:05');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `theo_doi_thai_kys`
--

CREATE TABLE `theo_doi_thai_kys` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `benh_an_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `bac_si_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ngay_theo_doi` date DEFAULT NULL,
  `tuan_thai` tinyint(3) UNSIGNED DEFAULT NULL,
  `can_nang_kg` decimal(5,2) DEFAULT NULL,
  `huyet_ap_tam_thu` smallint(5) UNSIGNED DEFAULT NULL,
  `huyet_ap_tam_truong` smallint(5) UNSIGNED DEFAULT NULL,
  `nhip_tim_thai` smallint(5) UNSIGNED DEFAULT NULL,
  `duong_huyet` decimal(5,2) DEFAULT NULL,
  `huyet_sac_to` decimal(5,2) DEFAULT NULL,
  `trieu_chung` text DEFAULT NULL,
  `ghi_chu` text DEFAULT NULL,
  `nhan_xet` text DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `disk` varchar(255) DEFAULT NULL,
  `trang_thai` varchar(255) NOT NULL DEFAULT 'submitted',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `theo_doi_thai_kys`
--

INSERT INTO `theo_doi_thai_kys` (`id`, `benh_an_id`, `user_id`, `bac_si_id`, `ngay_theo_doi`, `tuan_thai`, `can_nang_kg`, `huyet_ap_tam_thu`, `huyet_ap_tam_truong`, `nhip_tim_thai`, `duong_huyet`, `huyet_sac_to`, `trieu_chung`, `ghi_chu`, `nhan_xet`, `file_path`, `disk`, `trang_thai`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 23, 4, '2025-12-20', 16, 52.00, 110, 70, 150, 85.00, 12.50, 'Ăn ngon miệng, hết nghén, thỉnh thoảng đau lưng nhẹ.', 'Thai nhi phát triển tốt, đã nghe rõ tim thai.', 'Tốt lắm', 'theo_doi_thai_ky/USOemspvtrH9Zj8H8OwihWkGEBFw0Ey9FSm8Yjss.png', 'benh_an_private', 'reviewed', '2025-12-20 02:32:55', '2025-12-20 02:33:55', NULL);

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
(1, 'Paracetamol 500mg', 'Paracetamol', '500mg', 'viên', 9701.00, 47, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(2, 'Ibuprofen 200mg - Lot02', 'Ibuprofen', '200mg', 'viên', 9969.00, 36, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(3, 'Amoxicillin 500mg - Lot03', 'Amoxicillin', '500mg', 'viên', 3679.00, 123, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(4, 'Azithromycin 250mg - Lot04', 'Azithromycin', '250mg', 'viên', 3683.00, 197, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(5, 'Doxycycline 100mg - Lot05', 'Doxycycline', '100mg', 'viên', 6880.00, 20, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(6, 'Metronidazole 500mg - Lot06', 'Metronidazole', '500mg', 'viên', 8601.00, 45, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(7, 'Cefuroxime 250mg - Lot07', 'Cefuroxime', '250mg', 'viên', 6366.00, 122, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(8, 'Cefixime 400mg - Lot08', 'Cefixime', '400mg', 'viên', 5268.00, 89, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(9, 'Cefadroxil 500mg - Lot09', 'Cefadroxil', '500mg', 'viên', 1183.00, 39, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(10, 'Ceftriaxone 1g - Lot10', 'Ceftriaxone', '1g', 'ống', 4574.00, 47, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(11, 'Omeprazole 20mg - Lot11', 'Omeprazole', '20mg', 'viên', 4027.00, 23, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(12, 'Pantoprazole 40mg - Lot12', 'Pantoprazole', '40mg', 'viên', 5265.00, 138, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(13, 'Ranitidine 150mg - Lot13', 'Ranitidine', '150mg', 'viên', 4658.00, 33, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(14, 'Tranexamic 500mg - Lot14', 'Tranexamic', '500mg', 'viên', 4618.00, 107, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(15, 'Ferrous sulfate 325mg - Lot15', 'Ferrous', '325mg', 'viên', 4263.00, 71, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(16, 'Vitamin C 500mg - Lot16', 'Vitamin', '500mg', 'viên', 6515.00, 125, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(17, 'Vitamin D3 2000IU - Lot17', 'Vitamin', '2000IU', 'ống', 8953.00, 133, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(18, 'Calcium 600mg - Lot18', 'Calcium', '600mg', 'viên', 7067.00, 137, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(19, 'Utrogestan 200mg - Lot19', 'Utrogestan', '200mg', 'viên', 6205.00, 134, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(20, 'Duphaston 10mg - Lot20', 'Duphaston', '10mg', 'viên', 4146.00, 199, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(21, 'Levothyroxine 50mcg - Lot21', 'Levothyroxine', NULL, 'ống', 5668.00, 157, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(22, 'Progesterone 100mg - Lot22', 'Progesterone', '100mg', 'viên', 4770.00, 113, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(23, 'Nitrofurantoin 100mg - Lot23', 'Nitrofurantoin', '100mg', 'viên', 7241.00, 182, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(24, 'Fluconazole 150mg - Lot24', 'Fluconazole', '150mg', 'viên', 5457.00, 196, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(25, 'Ketoconazole 200mg - Lot25', 'Ketoconazole', '200mg', 'viên', 3610.00, 21, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(26, 'Paracetamol 500mg - Lot26', 'Paracetamol', '500mg', 'viên', 1664.00, 155, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(27, 'Ibuprofen 200mg - Lot27', 'Ibuprofen', '200mg', 'viên', 7079.00, 41, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(28, 'Amoxicillin 500mg - Lot28', 'Amoxicillin', '500mg', 'viên', 8432.00, 54, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(29, 'Azithromycin 250mg - Lot29', 'Azithromycin', '250mg', 'viên', 9395.00, 57, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(30, 'Doxycycline 100mg - Lot30', 'Doxycycline', '100mg', 'viên', 6347.00, 31, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(31, 'Metronidazole 500mg - Lot31', 'Metronidazole', '500mg', 'viên', 3409.00, 153, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(32, 'Cefuroxime 250mg - Lot32', 'Cefuroxime', '250mg', 'viên', 818.00, 191, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(33, 'Cefixime 400mg - Lot33', 'Cefixime', '400mg', 'viên', 4596.00, 158, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(34, 'Cefadroxil 500mg - Lot34', 'Cefadroxil', '500mg', 'viên', 8097.00, 88, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(35, 'Ceftriaxone 1g - Lot35', 'Ceftriaxone', '1g', 'ống', 4722.00, 132, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(36, 'Omeprazole 20mg - Lot36', 'Omeprazole', '20mg', 'viên', 2259.00, 172, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(37, 'Pantoprazole 40mg - Lot37', 'Pantoprazole', '40mg', 'viên', 5184.00, 28, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(38, 'Ranitidine 150mg - Lot38', 'Ranitidine', '150mg', 'viên', 5818.00, 90, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(39, 'Tranexamic 500mg - Lot39', 'Tranexamic', '500mg', 'viên', 5786.00, 192, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(40, 'Ferrous sulfate 325mg - Lot40', 'Ferrous', '325mg', 'viên', 9119.00, 32, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(41, 'Vitamin C 500mg - Lot41', 'Vitamin', '500mg', 'viên', 9416.00, 169, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(42, 'Vitamin D3 2000IU - Lot42', 'Vitamin', '2000IU', 'ống', 6334.00, 107, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(43, 'Calcium 600mg - Lot43', 'Calcium', '600mg', 'viên', 3183.00, 166, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(44, 'Utrogestan 200mg - Lot44', 'Utrogestan', '200mg', 'viên', 9694.00, 133, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(45, 'Duphaston 10mg - Lot45', 'Duphaston', '10mg', 'viên', 5753.00, 130, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(46, 'Levothyroxine 50mcg - Lot46', 'Levothyroxine', NULL, 'ống', 5550.00, 85, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(47, 'Progesterone 100mg - Lot47', 'Progesterone', '100mg', 'viên', 7560.00, 78, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(48, 'Nitrofurantoin 100mg - Lot48', 'Nitrofurantoin', '100mg', 'viên', 3762.00, 140, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(49, 'Fluconazole 150mg - Lot49', 'Fluconazole', '150mg', 'viên', 4275.00, 142, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(50, 'Ketoconazole 200mg - Lot50', 'Ketoconazole', '200mg', 'viên', 8946.00, 51, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(51, 'Paracetamol 500mg - Lot51', 'Paracetamol', '500mg', 'viên', 4939.00, 58, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(52, 'Ibuprofen 200mg - Lot52', 'Ibuprofen', '200mg', 'viên', 6389.00, 178, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(53, 'Amoxicillin 500mg - Lot53', 'Amoxicillin', '500mg', 'viên', 7789.00, 156, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(54, 'Azithromycin 250mg - Lot54', 'Azithromycin', '250mg', 'viên', 6064.00, 179, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(55, 'Doxycycline 100mg - Lot55', 'Doxycycline', '100mg', 'viên', 3096.00, 121, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(56, 'Metronidazole 500mg - Lot56', 'Metronidazole', '500mg', 'viên', 2992.00, 12, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(57, 'Cefuroxime 250mg - Lot57', 'Cefuroxime', '250mg', 'viên', 6694.00, 59, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(58, 'Cefixime 400mg - Lot58', 'Cefixime', '400mg', 'viên', 1597.00, 180, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(59, 'Cefadroxil 500mg - Lot59', 'Cefadroxil', '500mg', 'viên', 6949.00, 13, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(60, 'Ceftriaxone 1g - Lot60', 'Ceftriaxone', '1g', 'ống', 1200.00, 73, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(61, 'Omeprazole 20mg - Lot61', 'Omeprazole', '20mg', 'viên', 8527.00, 119, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(62, 'Pantoprazole 40mg - Lot62', 'Pantoprazole', '40mg', 'viên', 6274.00, 56, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(63, 'Ranitidine 150mg - Lot63', 'Ranitidine', '150mg', 'viên', 5519.00, 159, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(64, 'Tranexamic 500mg - Lot64', 'Tranexamic', '500mg', 'viên', 4375.00, 84, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(65, 'Ferrous sulfate 325mg - Lot65', 'Ferrous', '325mg', 'viên', 1376.00, 44, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(66, 'Vitamin C 500mg - Lot66', 'Vitamin', '500mg', 'viên', 7019.00, 63, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(67, 'Vitamin D3 2000IU - Lot67', 'Vitamin', '2000IU', 'ống', 4223.00, 199, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(68, 'Calcium 600mg - Lot68', 'Calcium', '600mg', 'viên', 966.00, 70, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(69, 'Utrogestan 200mg - Lot69', 'Utrogestan', '200mg', 'viên', 3256.00, 10, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(70, 'Duphaston 10mg - Lot70', 'Duphaston', '10mg', 'viên', 983.00, 102, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(71, 'Levothyroxine 50mcg - Lot71', 'Levothyroxine', NULL, 'ống', 6272.00, 29, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(72, 'Progesterone 100mg - Lot72', 'Progesterone', '100mg', 'viên', 2667.00, 109, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(73, 'Nitrofurantoin 100mg - Lot73', 'Nitrofurantoin', '100mg', 'viên', 4348.00, 71, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(74, 'Fluconazole 150mg - Lot74', 'Fluconazole', '150mg', 'viên', 4381.00, 47, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(75, 'Ketoconazole 200mg - Lot75', 'Ketoconazole', '200mg', 'viên', 2383.00, 147, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(76, 'Paracetamol 500mg - Lot76', 'Paracetamol', '500mg', 'viên', 6466.00, 40, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(77, 'Ibuprofen 200mg - Lot77', 'Ibuprofen', '200mg', 'viên', 8999.00, 110, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(78, 'Amoxicillin 500mg - Lot78', 'Amoxicillin', '500mg', 'viên', 6805.00, 137, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(79, 'Azithromycin 250mg - Lot79', 'Azithromycin', '250mg', 'viên', 1371.00, 82, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(80, 'Doxycycline 100mg - Lot80', 'Doxycycline', '100mg', 'viên', 9122.00, 59, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(81, 'Metronidazole 500mg - Lot81', 'Metronidazole', '500mg', 'viên', 3789.00, 122, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(82, 'Cefuroxime 250mg - Lot82', 'Cefuroxime', '250mg', 'viên', 3704.00, 175, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(83, 'Cefixime 400mg - Lot83', 'Cefixime', '400mg', 'viên', 1035.00, 54, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(84, 'Cefadroxil 500mg - Lot84', 'Cefadroxil', '500mg', 'viên', 2368.00, 92, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(85, 'Ceftriaxone 1g - Lot85', 'Ceftriaxone', '1g', 'ống', 2710.00, 76, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(86, 'Omeprazole 20mg - Lot86', 'Omeprazole', '20mg', 'viên', 1813.00, 112, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(87, 'Pantoprazole 40mg - Lot87', 'Pantoprazole', '40mg', 'viên', 2044.00, 180, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(88, 'Ranitidine 150mg - Lot88', 'Ranitidine', '150mg', 'viên', 6894.00, 33, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(89, 'Tranexamic 500mg - Lot89', 'Tranexamic', '500mg', 'viên', 6279.00, 50, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(90, 'Ferrous sulfate 325mg - Lot90', 'Ferrous', '325mg', 'viên', 6190.00, 49, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(91, 'Vitamin C 500mg - Lot91', 'Vitamin', '500mg', 'viên', 2932.00, 97, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(92, 'Vitamin D3 2000IU - Lot92', 'Vitamin', '2000IU', 'ống', 6614.00, 49, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(93, 'Calcium 600mg - Lot93', 'Calcium', '600mg', 'viên', 2038.00, 82, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(94, 'Utrogestan 200mg - Lot94', 'Utrogestan', '200mg', 'viên', 5980.00, 153, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(95, 'Duphaston 10mg - Lot95', 'Duphaston', '10mg', 'viên', 9090.00, 72, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(96, 'Levothyroxine 50mcg - Lot96', 'Levothyroxine', NULL, 'ống', 1499.00, 137, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(97, 'Progesterone 100mg - Lot97', 'Progesterone', '100mg', 'viên', 2486.00, 138, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(98, 'Nitrofurantoin 100mg - Lot98', 'Nitrofurantoin', '100mg', 'viên', 1055.00, 186, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(99, 'Fluconazole 150mg - Lot99', 'Fluconazole', '150mg', 'viên', 9486.00, 137, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(100, 'Ketoconazole 200mg - Lot100', 'Ketoconazole', '200mg', 'viên', 1703.00, 104, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(101, 'Paracetamol 500mg - Lot101', 'Paracetamol', '500mg', 'viên', 8452.00, 71, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(102, 'Ibuprofen 200mg - Lot102', 'Ibuprofen', '200mg', 'viên', 7585.00, 192, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(103, 'Amoxicillin 500mg - Lot103', 'Amoxicillin', '500mg', 'viên', 8263.00, 64, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(104, 'Azithromycin 250mg - Lot104', 'Azithromycin', '250mg', 'viên', 8838.00, 164, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(105, 'Doxycycline 100mg - Lot105', 'Doxycycline', '100mg', 'viên', 6774.00, 139, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(106, 'Metronidazole 500mg - Lot106', 'Metronidazole', '500mg', 'viên', 1810.00, 82, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(107, 'Cefuroxime 250mg - Lot107', 'Cefuroxime', '250mg', 'viên', 3798.00, 175, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(108, 'Cefixime 400mg - Lot108', 'Cefixime', '400mg', 'viên', 9431.00, 15, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(109, 'Cefadroxil 500mg - Lot109', 'Cefadroxil', '500mg', 'viên', 1127.00, 189, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(110, 'Ceftriaxone 1g - Lot110', 'Ceftriaxone', '1g', 'ống', 1237.00, 173, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(111, 'Omeprazole 20mg - Lot111', 'Omeprazole', '20mg', 'viên', 4413.00, 181, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(112, 'Pantoprazole 40mg - Lot112', 'Pantoprazole', '40mg', 'viên', 6783.00, 28, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(113, 'Ranitidine 150mg - Lot113', 'Ranitidine', '150mg', 'viên', 5670.00, 191, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(114, 'Tranexamic 500mg - Lot114', 'Tranexamic', '500mg', 'viên', 5633.00, 169, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(115, 'Ferrous sulfate 325mg - Lot115', 'Ferrous', '325mg', 'viên', 4184.00, 12, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(116, 'Vitamin C 500mg - Lot116', 'Vitamin', '500mg', 'viên', 930.00, 91, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(117, 'Vitamin D3 2000IU - Lot117', 'Vitamin', '2000IU', 'ống', 2928.00, 74, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(118, 'Calcium 600mg - Lot118', 'Calcium', '600mg', 'viên', 3180.00, 149, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(119, 'Utrogestan 200mg - Lot119', 'Utrogestan', '200mg', 'viên', 4670.00, 85, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(120, 'Duphaston 10mg - Lot120', 'Duphaston', '10mg', 'viên', 7930.00, 115, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(121, 'Levothyroxine 50mcg - Lot121', 'Levothyroxine', NULL, 'ống', 9323.00, 165, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(122, 'Progesterone 100mg - Lot122', 'Progesterone', '100mg', 'viên', 1885.00, 42, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(123, 'Nitrofurantoin 100mg - Lot123', 'Nitrofurantoin', '100mg', 'viên', 2600.00, 145, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(124, 'Fluconazole 150mg - Lot124', 'Fluconazole', '150mg', 'viên', 6458.00, 112, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(125, 'Ketoconazole 200mg - Lot125', 'Ketoconazole', '200mg', 'viên', 6965.00, 116, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(126, 'Paracetamol 500mg - Lot126', 'Paracetamol', '500mg', 'viên', 2194.00, 130, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(127, 'Ibuprofen 200mg - Lot127', 'Ibuprofen', '200mg', 'viên', 6400.00, 155, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(128, 'Amoxicillin 500mg - Lot128', 'Amoxicillin', '500mg', 'viên', 6511.00, 121, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(129, 'Azithromycin 250mg - Lot129', 'Azithromycin', '250mg', 'viên', 5781.00, 33, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(130, 'Doxycycline 100mg - Lot130', 'Doxycycline', '100mg', 'viên', 6452.00, 174, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(131, 'Metronidazole 500mg - Lot131', 'Metronidazole', '500mg', 'viên', 7433.00, 182, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(132, 'Cefuroxime 250mg - Lot132', 'Cefuroxime', '250mg', 'viên', 8303.00, 167, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(133, 'Cefixime 400mg - Lot133', 'Cefixime', '400mg', 'viên', 6600.00, 183, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(134, 'Cefadroxil 500mg - Lot134', 'Cefadroxil', '500mg', 'viên', 6171.00, 173, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(135, 'Ceftriaxone 1g - Lot135', 'Ceftriaxone', '1g', 'ống', 4761.00, 39, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(136, 'Omeprazole 20mg - Lot136', 'Omeprazole', '20mg', 'viên', 2424.00, 146, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(137, 'Pantoprazole 40mg - Lot137', 'Pantoprazole', '40mg', 'viên', 2458.00, 11, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(138, 'Ranitidine 150mg - Lot138', 'Ranitidine', '150mg', 'viên', 3521.00, 62, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(139, 'Tranexamic 500mg - Lot139', 'Tranexamic', '500mg', 'viên', 2156.00, 47, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(140, 'Ferrous sulfate 325mg - Lot140', 'Ferrous', '325mg', 'viên', 5313.00, 68, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(141, 'Vitamin C 500mg - Lot141', 'Vitamin', '500mg', 'viên', 1949.00, 17, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(142, 'Vitamin D3 2000IU - Lot142', 'Vitamin', '2000IU', 'ống', 9858.00, 199, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(143, 'Calcium 600mg - Lot143', 'Calcium', '600mg', 'viên', 6780.00, 174, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(144, 'Utrogestan 200mg - Lot144', 'Utrogestan', '200mg', 'viên', 2556.00, 110, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(145, 'Duphaston 10mg - Lot145', 'Duphaston', '10mg', 'viên', 3805.00, 35, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(146, 'Levothyroxine 50mcg - Lot146', 'Levothyroxine', NULL, 'ống', 5392.00, 177, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(147, 'Progesterone 100mg - Lot147', 'Progesterone', '100mg', 'viên', 3773.00, 44, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(148, 'Nitrofurantoin 100mg - Lot148', 'Nitrofurantoin', '100mg', 'viên', 8500.00, 56, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(149, 'Fluconazole 150mg - Lot149', 'Fluconazole', '150mg', 'viên', 3168.00, 165, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(150, 'Ketoconazole 200mg - Lot150', 'Ketoconazole', '200mg', 'viên', 5165.00, 151, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(151, 'Găng tay y tế S', NULL, NULL, 'cái', 42705.00, 30, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(152, 'Găng tay y tế M - Pack 2', NULL, NULL, 'cái', 41046.00, 47, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(153, 'Găng tay y tế L - Pack 3', NULL, NULL, 'cái', 49664.00, 5, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(154, 'Bơm kim tiêm 1ml - Pack 4', NULL, NULL, 'cái', 37945.00, 29, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(155, 'Bơm kim tiêm 5ml - Pack 5', NULL, NULL, 'cái', 27606.00, 41, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(156, 'Bơm kim tiêm 10ml - Pack 6', NULL, NULL, 'cái', 49106.00, 41, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(157, 'Bông gòn 50g - Pack 7', NULL, NULL, 'cái', 8195.00, 21, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(158, 'Cồn 70% - Pack 8', NULL, NULL, 'cái', 30379.00, 10, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(159, 'Gel siêu âm 5L - Pack 9', NULL, NULL, 'cái', 8417.00, 42, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(160, 'Que thử thai - Pack 10', NULL, NULL, 'cái', 15106.00, 17, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(161, 'Que thử rụng trứng - Pack 11', NULL, NULL, 'cái', 15070.00, 45, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(162, 'Kim tiêm 21G - Pack 12', NULL, NULL, 'cái', 19187.00, 47, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(163, 'Kim tiêm 23G - Pack 13', NULL, NULL, 'cái', 40108.00, 27, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(164, 'Khẩu trang N95 - Pack 14', NULL, NULL, 'cái', 38513.00, 11, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(165, 'Khẩu trang y tế 3 lớp - Pack 15', NULL, NULL, 'cái', 46940.00, 29, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(166, 'Gạc vô trùng 10x10 - Pack 16', NULL, NULL, 'cái', 26020.00, 43, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(167, 'Túi đựng máu - Pack 17', NULL, NULL, 'cái', 9578.00, 25, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(168, 'Hộp đựng dụng cụ tiểu phẫu - Pack 18', NULL, NULL, 'cái', 46057.00, 35, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(169, 'Dao mổ 10 - Pack 19', NULL, NULL, 'cái', 6904.00, 21, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(170, 'Dụng cụ lấy máu - Pack 20', NULL, NULL, 'cái', 21423.00, 50, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(171, 'Bồn đựng mẫu - Pack 21', NULL, NULL, 'cái', 48325.00, 28, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(172, 'Găng tay y tế S - Pack 22', NULL, NULL, 'cái', 45776.00, 43, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(173, 'Găng tay y tế M - Pack 23', NULL, NULL, 'cái', 32785.00, 20, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(174, 'Găng tay y tế L - Pack 24', NULL, NULL, 'cái', 37050.00, 9, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(175, 'Bơm kim tiêm 1ml - Pack 25', NULL, NULL, 'cái', 39470.00, 38, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(176, 'Bơm kim tiêm 5ml - Pack 26', NULL, NULL, 'cái', 28640.00, 22, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(177, 'Bơm kim tiêm 10ml - Pack 27', NULL, NULL, 'cái', 7272.00, 9, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(178, 'Bông gòn 50g - Pack 28', NULL, NULL, 'cái', 26141.00, 10, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(179, 'Cồn 70% - Pack 29', NULL, NULL, 'cái', 23953.00, 50, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(180, 'Gel siêu âm 5L - Pack 30', NULL, NULL, 'cái', 34453.00, 46, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(181, 'Que thử thai - Pack 31', NULL, NULL, 'cái', 44688.00, 46, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(182, 'Que thử rụng trứng - Pack 32', NULL, NULL, 'cái', 8532.00, 36, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(183, 'Kim tiêm 21G - Pack 33', NULL, NULL, 'cái', 7511.00, 12, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(184, 'Kim tiêm 23G - Pack 34', NULL, NULL, 'cái', 42479.00, 25, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(185, 'Khẩu trang N95 - Pack 35', NULL, NULL, 'cái', 13013.00, 13, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(186, 'Khẩu trang y tế 3 lớp - Pack 36', NULL, NULL, 'cái', 26749.00, 43, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(187, 'Gạc vô trùng 10x10 - Pack 37', NULL, NULL, 'cái', 23516.00, 43, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(188, 'Túi đựng máu - Pack 38', NULL, NULL, 'cái', 36289.00, 20, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(189, 'Hộp đựng dụng cụ tiểu phẫu - Pack 39', NULL, NULL, 'cái', 48910.00, 17, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(190, 'Dao mổ 10 - Pack 40', NULL, NULL, 'cái', 48526.00, 16, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(191, 'Dụng cụ lấy máu - Pack 41', NULL, NULL, 'cái', 21922.00, 38, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(192, 'Bồn đựng mẫu - Pack 42', NULL, NULL, 'cái', 11481.00, 19, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(193, 'Găng tay y tế S - Pack 43', NULL, NULL, 'cái', 13201.00, 41, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(194, 'Găng tay y tế M - Pack 44', NULL, NULL, 'cái', 28796.00, 49, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(195, 'Găng tay y tế L - Pack 45', NULL, NULL, 'cái', 48546.00, 19, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(196, 'Bơm kim tiêm 1ml - Pack 46', NULL, NULL, 'cái', 44470.00, 12, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(197, 'Bơm kim tiêm 5ml - Pack 47', NULL, NULL, 'cái', 24752.00, 40, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(198, 'Bơm kim tiêm 10ml - Pack 48', NULL, NULL, 'cái', 22019.00, 31, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(199, 'Bông gòn 50g - Pack 49', NULL, NULL, 'cái', 9962.00, 32, '2025-12-19 16:00:45', '2025-12-19 16:00:45'),
(200, 'Cồn 70% - Pack 50', NULL, NULL, 'cái', 23707.00, 10, '2025-12-19 16:00:45', '2025-12-19 16:00:45');

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
(1, 156, 'LOT-PN-1-566', '2027-03-19', 381, 7292.00, 10208.80, 4, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(2, 106, 'LOT-PN-1-63', '2027-03-19', 183, 3400.00, 4760.00, 4, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(3, 89, 'LOT-PN-1-347', '2027-12-19', 428, 1171.00, 1639.40, 4, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(4, 170, 'LOT-PN-1-481', '2026-10-19', 131, 7174.00, 10043.60, 4, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(5, 70, 'LOT-PN-1-352', '2027-07-19', 23, 2507.00, 3509.80, 4, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(6, 32, 'LOT-PN-1-531', '2028-01-19', 79, 7770.00, 10878.00, 4, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(7, 37, 'LOT-PN-1-39', '2027-05-19', 198, 8868.00, 12415.20, 4, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(8, 108, 'LOT-PN-1-686', '2027-04-19', 242, 3399.00, 4758.60, 4, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(9, 82, 'LOT-PN-2-401', '2027-07-19', 198, 1679.00, 2350.60, 7, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(10, 106, 'LOT-PN-2-759', '2026-12-19', 106, 4238.00, 5933.20, 7, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(11, 195, 'LOT-PN-2-421', '2026-08-19', 452, 9181.00, 12853.40, 7, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(12, 189, 'LOT-PN-2-718', '2027-07-19', 389, 8170.00, 11438.00, 7, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(13, 2, 'LOT-PN-2-40', '2026-07-19', 385, 6685.00, 9359.00, 7, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(14, 128, 'LOT-PN-2-492', '2028-11-19', 112, 9320.00, 13048.00, 7, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(15, 134, 'LOT-PN-2-3', '2027-10-19', 481, 6756.00, 9458.40, 7, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(16, 85, 'LOT-PN-2-68', '2027-02-19', 50, 796.00, 1114.40, 7, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(17, 137, 'LOT-PN-2-59', '2027-08-19', 422, 1415.00, 1981.00, 7, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(18, 24, 'LOT-PN-2-303', '2027-04-19', 63, 8918.00, 12485.20, 7, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(19, 58, 'LOT-PN-3-521', '2026-11-19', 77, 4710.00, 6594.00, 15, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(20, 71, 'LOT-PN-3-434', '2028-08-19', 448, 7785.00, 10899.00, 15, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(21, 166, 'LOT-PN-3-45', '2026-12-19', 56, 1933.00, 2706.20, 15, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(22, 185, 'LOT-PN-3-52', '2027-05-19', 12, 9535.00, 13349.00, 15, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(23, 165, 'LOT-PN-3-302', '2028-09-19', 337, 4322.00, 6050.80, 15, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(24, 103, 'LOT-PN-3-470', '2026-12-19', 425, 8411.00, 11775.40, 15, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(25, 75, 'LOT-PN-3-540', '2028-07-19', 154, 7976.00, 11166.40, 15, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(26, 122, 'LOT-PN-4-248', '2027-01-19', 453, 4230.00, 5922.00, 6, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(27, 77, 'LOT-PN-4-47', '2026-09-19', 434, 4643.00, 6500.20, 6, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(28, 30, 'LOT-PN-4-171', '2028-07-19', 293, 6140.00, 8596.00, 6, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(29, 74, 'LOT-PN-4-918', '2027-07-19', 0, 9053.00, 12674.20, 6, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(30, 178, 'LOT-PN-4-847', '2026-10-19', 361, 6017.00, 8423.80, 6, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(31, 73, 'LOT-PN-4-942', '2027-04-19', 17, 7287.00, 10201.80, 6, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(32, 63, 'LOT-PN-4-204', '2028-09-19', 189, 9107.00, 12749.80, 6, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(33, 107, 'LOT-PN-4-453', '2026-10-19', 53, 7231.00, 10123.40, 6, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(34, 170, 'LOT-PN-4-82', '2027-07-19', 125, 1771.00, 2479.40, 6, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(35, 144, 'LOT-PN-4-18', '2028-08-19', 34, 8249.00, 11548.60, 6, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(36, 186, 'LOT-PN-4-356', '2027-07-19', 187, 2804.00, 3925.60, 6, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(37, 81, 'LOT-PN-4-253', '2027-07-19', 494, 6912.00, 9676.80, 6, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(38, 193, 'LOT-PN-5-553', '2028-09-19', 232, 2401.00, 3361.40, 14, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(39, 112, 'LOT-PN-5-973', '2027-07-19', 175, 3570.00, 4998.00, 14, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(40, 106, 'LOT-PN-5-600', '2028-11-19', 225, 6467.00, 9053.80, 14, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(41, 128, 'LOT-PN-5-989', '2026-10-19', 216, 7406.00, 10368.40, 14, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(42, 44, 'LOT-PN-5-89', '2027-01-19', 301, 5006.00, 7008.40, 14, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(43, 168, 'LOT-PN-5-435', '2028-06-19', 499, 2954.00, 4135.60, 14, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(44, 137, 'LOT-PN-5-681', '2028-04-19', 246, 928.00, 1299.20, 14, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(45, 27, 'LOT-PN-5-92', '2026-09-19', 70, 4689.00, 6564.60, 14, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(46, 169, 'LOT-PN-5-514', '2026-09-19', 295, 9051.00, 12671.40, 14, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(47, 5, 'LOT-PN-5-368', '2027-03-19', 198, 4110.00, 5754.00, 14, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(48, 124, 'LOT-PN-5-180', '2027-05-19', 52, 4159.00, 5822.60, 14, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(49, 103, 'LOT-PN-5-26', '2028-06-19', 210, 695.00, 973.00, 14, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(50, 172, 'LOT-PN-5-627', '2026-10-19', 157, 9818.00, 13745.20, 14, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(51, 35, 'LOT-PN-5-254', '2027-12-19', 475, 7427.00, 10397.80, 14, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(52, 120, 'LOT-PN-5-362', '2027-03-19', 108, 1486.00, 2080.40, 14, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(53, 22, 'LOT-PN-6-900', '2028-01-19', 80, 9845.00, 13783.00, 6, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(54, 20, 'LOT-PN-6-240', '2028-05-19', 296, 9073.00, 12702.20, 6, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(55, 8, 'LOT-PN-6-290', '2028-01-19', 60, 9116.00, 12762.40, 6, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(56, 29, 'LOT-PN-6-668', '2028-03-19', 0, 4130.00, 5782.00, 6, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(57, 130, 'LOT-PN-6-599', '2026-07-19', 88, 4861.00, 6805.40, 6, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(58, 191, 'LOT-PN-6-718', '2027-12-19', 10, 4308.00, 6031.20, 6, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(59, 119, 'LOT-PN-6-420', '2028-05-19', 383, 4756.00, 6658.40, 6, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(60, 161, 'LOT-PN-6-630', '2028-03-19', 457, 2698.00, 3777.20, 6, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(61, 111, 'LOT-PN-6-677', '2026-06-19', 157, 1526.00, 2136.40, 6, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(62, 150, 'LOT-PN-7-759', '2028-03-19', 71, 820.00, 1148.00, 4, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(63, 3, 'LOT-PN-7-538', '2028-12-19', 137, 1873.00, 2622.20, 4, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(64, 12, 'LOT-PN-7-164', '2027-09-19', 158, 8441.00, 11817.40, 4, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(65, 179, 'LOT-PN-7-705', '2027-08-19', 267, 1460.00, 2044.00, 4, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(66, 7, 'LOT-PN-7-153', '2028-06-19', 0, 8540.00, 11956.00, 4, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(67, 119, 'LOT-PN-7-788', '2028-03-19', 72, 9265.00, 12971.00, 4, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(68, 148, 'LOT-PN-7-466', '2027-03-19', 127, 6103.00, 8544.20, 4, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(69, 177, 'LOT-PN-7-471', '2027-08-19', 235, 5504.00, 7705.60, 4, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(70, 65, 'LOT-PN-7-825', '2028-10-19', 241, 8167.00, 11433.80, 4, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(71, 107, 'LOT-PN-7-805', '2027-01-19', 375, 6660.00, 9324.00, 4, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(72, 95, 'LOT-PN-7-265', '2027-11-19', 214, 2041.00, 2857.40, 4, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(73, 99, 'LOT-PN-8-884', '2028-03-19', 288, 4294.00, 6011.60, 13, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(74, 197, 'LOT-PN-8-517', '2026-12-19', 261, 1771.00, 2479.40, 13, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(75, 36, 'LOT-PN-8-697', '2028-10-19', 72, 9614.00, 13459.60, 13, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(76, 2, 'LOT-PN-8-863', '2027-04-19', 403, 9249.00, 12948.60, 13, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(77, 66, 'LOT-PN-8-654', '2027-07-19', 48, 5577.00, 7807.80, 13, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(78, 151, 'LOT-PN-8-329', '2028-12-19', 458, 5852.00, 8192.80, 13, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(79, 130, 'LOT-PN-8-331', '2026-11-19', 166, 1403.00, 1964.20, 13, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(80, 13, 'LOT-PN-8-101', '2027-07-19', 164, 7749.00, 10848.60, 13, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(81, 165, 'LOT-PN-8-835', '2028-09-19', 454, 2194.00, 3071.60, 13, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(82, 84, 'LOT-PN-8-243', '2028-01-19', 225, 3986.00, 5580.40, 13, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(83, 182, 'LOT-PN-8-74', '2026-08-19', 53, 7479.00, 10470.60, 13, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(84, 187, 'LOT-PN-8-773', '2028-12-19', 295, 8746.00, 12244.40, 13, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(85, 166, 'LOT-PN-8-991', '2028-12-19', 96, 2603.00, 3644.20, 13, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(86, 136, 'LOT-PN-9-475', '2028-04-19', 137, 7348.00, 10287.20, 3, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(87, 170, 'LOT-PN-9-384', '2027-06-19', 496, 7065.00, 9891.00, 3, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(88, 24, 'LOT-PN-9-63', '2026-09-19', 94, 9252.00, 12952.80, 3, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(89, 102, 'LOT-PN-9-634', '2026-12-19', 186, 662.00, 926.80, 3, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(90, 164, 'LOT-PN-9-355', '2028-11-19', 105, 3738.00, 5233.20, 3, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(91, 30, 'LOT-PN-9-680', '2028-06-19', 437, 7051.00, 9871.40, 3, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(92, 115, 'LOT-PN-9-797', '2028-02-19', 317, 6804.00, 9525.60, 3, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(93, 95, 'LOT-PN-9-949', '2027-03-19', 139, 1959.00, 2742.60, 3, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(94, 40, 'LOT-PN-9-190', '2028-06-19', 46, 9447.00, 13225.80, 3, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(95, 86, 'LOT-PN-9-690', '2028-04-19', 229, 5417.00, 7583.80, 3, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(96, 96, 'LOT-PN-9-941', '2027-04-19', 317, 2969.00, 4156.60, 3, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(97, 124, 'LOT-PN-9-43', '2027-07-19', 243, 657.00, 919.80, 3, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(98, 16, 'LOT-PN-10-388', '2026-11-19', 279, 3994.00, 5591.60, 8, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(99, 72, 'LOT-PN-10-684', '2028-07-19', 143, 4439.00, 6214.60, 8, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(100, 88, 'LOT-PN-10-277', '2026-06-19', 239, 5989.00, 8384.60, 8, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(101, 18, 'LOT-PN-10-625', '2027-06-19', 340, 3688.00, 5163.20, 8, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(102, 196, 'LOT-PN-10-977', '2027-02-19', 255, 6966.00, 9752.40, 8, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(103, 22, 'LOT-PN-10-65', '2027-12-19', 357, 9340.00, 13076.00, 8, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(104, 98, 'LOT-PN-10-133', '2027-05-19', 275, 2065.00, 2891.00, 8, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(105, 28, 'LOT-PN-10-282', '2027-10-19', 66, 2310.00, 3234.00, 8, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(106, 174, 'LOT-PN-10-901', '2026-07-19', 80, 6831.00, 9563.40, 8, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(107, 2, 'LOT-PN-10-903', '2027-07-19', 282, 9042.00, 12658.80, 8, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(108, 173, 'LOT-PN-10-361', '2028-05-19', 442, 2822.00, 3950.80, 8, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(109, 50, 'LOT-NEAR-2759', '2025-12-29', 5, 8946.00, 11629.80, 2, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(110, 59, 'LOT-NEAR-2672', '2025-12-29', 1, 6949.00, 9033.70, 6, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(111, 55, 'LOT-NEAR-6796', '2025-12-29', 10, 3096.00, 4024.80, 9, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(112, 132, 'LOT-NEAR-7063', '2025-12-27', 9, 8303.00, 10793.90, 7, '2025-12-19 16:00:46', '2025-12-19 16:00:46'),
(113, 111, 'LOT-NEAR-1613', '2025-12-30', 9, 4413.00, 5736.90, 15, '2025-12-19 16:00:46', '2025-12-19 16:00:46');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `so_dien_thoai` varchar(15) DEFAULT NULL COMMENT 'Số điện thoại liên lạc',
  `ngay_sinh` date DEFAULT NULL COMMENT 'Ngày sinh',
  `gioi_tinh` enum('Nam','Nữ','Khác') DEFAULT NULL COMMENT 'Giới tính',
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

INSERT INTO `users` (`id`, `name`, `email`, `avatar`, `so_dien_thoai`, `ngay_sinh`, `gioi_tinh`, `email_verified_at`, `password`, `remember_token`, `locked_at`, `locked_until`, `must_change_password`, `last_login_at`, `login_attempts`, `last_login_ip`, `created_at`, `updated_at`, `role`) VALUES
(1, 'TS.BS Nguyễn Thị Lan Anh', 'lananh@vietcare.com', NULL, '0909111001', NULL, NULL, '2025-12-19 16:00:44', '$2y$10$dZFn7pG3r4cNw3NggNgPNeLgXjmQXKo5hCczNUTrN8pxUTXxhkMrG', NULL, NULL, NULL, 0, NULL, 0, NULL, '2025-12-19 16:00:44', '2025-12-19 16:00:44', 'doctor'),
(2, 'ThS.BS Phạm Văn Hùng', 'hunghoang@vietcare.com', NULL, '0909111002', NULL, NULL, '2025-12-19 16:00:44', '$2y$10$Ea0gzCWkvixDyMhkm1OXxeIkWwdrCBQ2dCeZ4NU0ovRgW8R2xlvUm', NULL, NULL, NULL, 0, NULL, 0, NULL, '2025-12-19 16:00:44', '2025-12-19 16:00:44', 'doctor'),
(3, 'BSCKII Trần Thu Hà', 'hatran@vietcare.com', NULL, '0909111003', NULL, NULL, '2025-12-19 16:00:44', '$2y$10$MvfhW56wfMos8jGeBrBVweiZ4J62Xbq6dH3UvpF0mTI.vzYtBNqxC', NULL, NULL, NULL, 0, NULL, 0, NULL, '2025-12-19 16:00:44', '2025-12-19 16:00:44', 'doctor'),
(4, 'BS.CKI Nguyễn Thanh Vân', 'vannguyen@vietcare.com', NULL, '0909111004', NULL, NULL, '2025-12-19 16:00:44', '$2y$10$H6tWbZDrC.wg81ON1IpfHeSUxFnW64xkmuiD.VOPyR0k972pIm3ja', NULL, NULL, NULL, 0, '2025-12-20 01:08:39', 0, '127.0.0.1', '2025-12-19 16:00:44', '2025-12-20 01:08:39', 'doctor'),
(5, 'TS.BS Hoàng Minh Tuấn', 'tuanhoang@vietcare.com', NULL, '0909111005', NULL, NULL, '2025-12-19 16:00:44', '$2y$10$jzfmL/8cPhH98raVGuUVk.Z8VRHgnGbqmiNgdrRsu/XelNv8og4z2', NULL, NULL, NULL, 0, NULL, 0, NULL, '2025-12-19 16:00:44', '2025-12-19 16:00:44', 'doctor'),
(6, 'ThS.BS Võ Thị Ngọc', 'ngocvo@vietcare.com', NULL, '0909111006', NULL, NULL, '2025-12-19 16:00:44', '$2y$10$oOgqn.evDo7viqaT5DXBvOhb1HCjxoMQ1ZnTgfnIq1xNqMNUqVCO2', NULL, NULL, NULL, 0, NULL, 0, NULL, '2025-12-19 16:00:44', '2025-12-19 16:00:44', 'doctor'),
(7, 'BS.CKI Phạm Thanh Thúy', 'thuypham@vietcare.com', NULL, '0909111007', NULL, NULL, '2025-12-19 16:00:44', '$2y$10$KCgg6bLiWm.s7uGt0mbAg.CoeQXD.Fc1N4nB5xUqvBj8ASX67U/NG', NULL, NULL, NULL, 0, NULL, 0, NULL, '2025-12-19 16:00:44', '2025-12-19 16:00:44', 'doctor'),
(8, 'ThS.BS Nguyễn Hữu Phước', 'phuocnguyen@vietcare.com', NULL, '0909111008', NULL, NULL, '2025-12-19 16:00:44', '$2y$10$2OBJrCqBmdCzSWV6qZJhXek7oXOuXdXww7CeN.I9noppNWQ4uNrkW', NULL, NULL, NULL, 0, NULL, 0, NULL, '2025-12-19 16:00:44', '2025-12-19 16:00:44', 'doctor'),
(9, 'BS.CKI Đỗ Mỹ Linh', 'linhdo@vietcare.com', NULL, '0909111009', NULL, NULL, '2025-12-19 16:00:44', '$2y$10$euSzfENh5Rj/aD8rplmN6OjoiZITB/g4bcsrvOu7HrLwsQDvZqUXa', NULL, NULL, NULL, 0, NULL, 0, NULL, '2025-12-19 16:00:44', '2025-12-19 16:00:44', 'doctor'),
(10, 'ThS.BS Lê Thị Mai', 'maile@vietcare.com', NULL, '0909111010', NULL, NULL, '2025-12-19 16:00:44', '$2y$10$YLZL4UjkCBceEVvotMv8quReWvGSAAGMhIKpfO6w/U2AaHtjbt7SG', NULL, NULL, NULL, 0, NULL, 0, NULL, '2025-12-19 16:00:44', '2025-12-19 16:00:44', 'doctor'),
(11, 'BS.CKI Trần Văn Minh', 'minh.xetnghiem@vietcare.com', NULL, '0909111011', NULL, NULL, '2025-12-19 16:00:44', '$2y$10$VTv8UbL/4BGUfeSC6IYO2eKcC.bwmaBRb6mmY2HdEc9qwl.pvTleu', NULL, NULL, NULL, 0, NULL, 0, NULL, '2025-12-19 16:00:44', '2025-12-19 16:00:44', 'doctor'),
(12, 'ThS.BS Nguyễn Ngọc Lan', 'lan.thammy@vietcare.com', NULL, '0909111012', NULL, NULL, '2025-12-19 16:00:44', '$2y$10$kpeTwkyvXZZa82jgUpRVN.nP4Tx7Mcv85oZtmqbIHof860Vsjq7dO', NULL, NULL, NULL, 0, NULL, 0, NULL, '2025-12-19 16:00:44', '2025-12-19 16:00:44', 'doctor'),
(14, 'Nguyễn Thị Hồng Hạnh', 'hanh.nguyen@vietcare.com', NULL, '0909222002', '2000-10-20', 'Nữ', '2025-12-19 16:00:45', '$2y$10$ItoBjpODQPFUWlOfQO.sQudRwV3BX.VqoPjrlY0z0zt6BJSxx1b.i', NULL, NULL, NULL, 0, NULL, 0, NULL, '2025-12-19 16:00:45', '2025-12-19 16:00:45', 'staff'),
(15, 'Trần Thị Kim Dung', 'dung.tran@vietcare.com', NULL, '0909222003', '1999-08-15', 'Nữ', '2025-12-19 16:00:45', '$2y$10$EyRl8Tl2nedliIc06Xu5uu2BrLtZyd1BYCHHRYA1oWxMQXUH.Sn2O', NULL, NULL, NULL, 0, NULL, 0, NULL, '2025-12-19 16:00:45', '2025-12-19 16:00:45', 'staff'),
(16, 'Phạm Thị Thu Thảo', 'thao.pham@vietcare.com', NULL, '0909222004', '1985-02-05', 'Nữ', '2025-12-19 16:00:45', '$2y$10$SqRJI9frli0cxnGZEHQqaensLUYLolt/FDcl2qfXjMwbcKq6As40i', NULL, NULL, NULL, 0, NULL, 0, NULL, '2025-12-19 16:00:45', '2025-12-19 16:00:45', 'staff'),
(17, 'Lê Thị Thanh Trúc', 'truc.le@vietcare.com', NULL, '0909222005', '1995-11-10', 'Nữ', '2025-12-19 16:00:45', '$2y$10$YrJqfXrKyBAogHTi4jF8HO2kW9RiOgcb8QgilDP9HLVa.LWQRK3Uy', NULL, NULL, NULL, 0, NULL, 0, NULL, '2025-12-19 16:00:45', '2025-12-19 16:00:45', 'staff'),
(18, 'Nguyễn Ngọc Huyền', 'huyen.nguyen@vietcare.com', NULL, '0909222006', '1997-07-22', 'Nữ', '2025-12-19 16:00:45', '$2y$10$SX9/pR/E3zqek9cxjBzL1O8nRkNt2XCQ/LoobYW2eEm5kxBgYTbXG', NULL, NULL, NULL, 0, NULL, 0, NULL, '2025-12-19 16:00:45', '2025-12-19 16:00:45', 'staff'),
(19, 'Vũ Thị Minh Anh', 'anh.vu@vietcare.com', NULL, '0909222007', '1996-04-30', 'Nữ', '2025-12-19 16:00:45', '$2y$10$ufY4FXEcaoxp52FdOBqQ.eg8xPuUQp55oLTRbz6DU4NABWeP3Uvwu', NULL, NULL, NULL, 0, NULL, 0, NULL, '2025-12-19 16:00:45', '2025-12-19 16:00:45', 'staff'),
(20, 'Hoàng Văn Nam', 'nam.hoang@vietcare.com', NULL, '0909222008', '1990-09-14', 'Nam', '2025-12-19 16:00:45', '$2y$10$8tpsL5uATYNtRFIwSgyzUOdxSTBmPynO/X7GYXvDRS5JrlQfbmj1u', NULL, NULL, NULL, 0, NULL, 0, NULL, '2025-12-19 16:00:45', '2025-12-19 16:00:45', 'staff'),
(21, 'Nguyễn Thu Phương', 'phuong.nguyen@vietcare.com', NULL, '0909222009', '1980-01-01', 'Nữ', '2025-12-19 16:00:45', '$2y$10$cakk39U9DtVGnEdFr4otieXoz6ebvkvRYndWP1WLEFQHlq78Fj0A.', NULL, NULL, NULL, 0, NULL, 0, NULL, '2025-12-19 16:00:45', '2025-12-19 16:00:45', 'staff'),
(22, 'Trần Quốc Bảo', 'bao.tran@vietcare.com', NULL, '0909222010', '1995-12-18', 'Nam', '2025-12-19 16:00:45', '$2y$10$vODyi9NSkVCHcGgpmKX.Q.yzwgw9Cb45Aa3mn0dc.HqGCqDLUfV.i', NULL, NULL, NULL, 0, NULL, 0, NULL, '2025-12-19 16:00:45', '2025-12-19 16:00:45', 'staff'),
(23, 'Nguyễn Chí Thanh', 'tn822798@gmail.com', NULL, NULL, NULL, NULL, NULL, '$2y$10$ZsovaDUzfnDBfjItL9uOOuEs0P3bvF6saTDZ9rPb7qcliGf0HzW0i', NULL, NULL, NULL, 0, '2025-12-20 01:08:21', 0, '127.0.0.1', '2025-12-19 16:11:58', '2025-12-20 01:08:21', 'patient'),
(24, 'Admin', 'Admin@gmail.com', NULL, NULL, NULL, NULL, NULL, '$2y$10$YkHDlh4u3dgqvvo.0eDz3etB9/rggmPs0zX60PMDXAwj.aiTEESze', NULL, NULL, NULL, 0, '2025-12-20 01:10:09', 0, '127.0.0.1', '2025-12-19 16:17:34', '2025-12-20 01:10:09', 'admin'),
(25, 'Lê Minh Nhật', 'henvaemhen@gmail.com', NULL, NULL, NULL, NULL, NULL, '$2y$10$noM2sEJJTq5FsTnH/NCkUuDvjT2.2ub63WK9r9XhbweXyZ6.tJx.K', 'hPyVTeslwTmAKhJq7j4ORrCwTnnrEYsPWgLinhe16RMhWaOj3NrQV053E6AW', NULL, NULL, 0, '2025-12-20 01:11:38', 0, '127.0.0.1', '2025-12-19 16:38:16', '2025-12-20 01:11:38', 'staff');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `xet_nghiems`
--

CREATE TABLE `xet_nghiems` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `benh_an_id` bigint(20) UNSIGNED NOT NULL,
  `bac_si_id` bigint(20) UNSIGNED DEFAULT NULL,
  `loai_xet_nghiem_id` bigint(20) UNSIGNED DEFAULT NULL,
  `loai` varchar(255) NOT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `disk` varchar(255) DEFAULT NULL,
  `mo_ta` varchar(255) DEFAULT NULL,
  `gia` decimal(12,2) NOT NULL DEFAULT 0.00,
  `trang_thai` varchar(255) NOT NULL DEFAULT 'pending',
  `nhan_xet` text DEFAULT NULL,
  `ket_qua` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `xet_nghiems`
--

INSERT INTO `xet_nghiems` (`id`, `benh_an_id`, `bac_si_id`, `loai_xet_nghiem_id`, `loai`, `file_path`, `disk`, `mo_ta`, `gia`, `trang_thai`, `nhan_xet`, `ket_qua`, `created_at`, `updated_at`) VALUES
(1, 1, 4, 8, 'Đường huyết lúc đói', 'xet_nghiem/t8GMTeffNwCSYn8gD7HIUiPFcVxCSPzSYoLMHN8o.png', 'benh_an_private', 'Khám Gói Khám Phụ khoa Tổng quát (VIP)', 70000.00, 'completed', 'Tư vấn chuyên sâu Vô sinh - Hiếm muộn', 'Tư vấn chuyên sâu Vô sinh - Hiếm muộn', '2025-12-19 17:49:47', '2025-12-19 18:01:04');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `x_quangs`
--

CREATE TABLE `x_quangs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `benh_an_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `bac_si_id` bigint(20) UNSIGNED DEFAULT NULL,
  `loai_x_quang_id` bigint(20) UNSIGNED DEFAULT NULL,
  `loai` varchar(255) NOT NULL,
  `mo_ta` text DEFAULT NULL,
  `gia` decimal(12,2) NOT NULL DEFAULT 0.00,
  `ngay_chi_dinh` datetime DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `disk` varchar(255) DEFAULT NULL,
  `trang_thai` varchar(255) NOT NULL DEFAULT 'pending',
  `nhan_xet` text DEFAULT NULL,
  `ket_qua` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `x_quangs`
--

INSERT INTO `x_quangs` (`id`, `benh_an_id`, `user_id`, `bac_si_id`, `loai_x_quang_id`, `loai`, `mo_ta`, `gia`, `ngay_chi_dinh`, `file_path`, `disk`, `trang_thai`, `nhan_xet`, `ket_qua`, `created_at`, `updated_at`) VALUES
(1, 1, 23, 4, 6, 'X-Quang bàn chân', 'Chụp X-Quang để đánh giá', 150000.00, '2025-12-20 08:28:01', 'x_quang/mqJBWiZdlcTbXYc1l31QNBVYURWNoIQbHzBWbvLl.png', 'benh_an_private', 'completed', 'Chụp X-Quang để đánh giá', 'Chụp X-Quang để đánh giá', '2025-12-20 01:28:01', '2025-12-20 01:28:47');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `activity_log`
--
ALTER TABLE `activity_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subject` (`subject_type`,`subject_id`),
  ADD KEY `causer` (`causer_type`,`causer_id`),
  ADD KEY `activity_log_log_name_index` (`log_name`);

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
-- Chỉ mục cho bảng `chuyen_khoa_dich_vu`
--
ALTER TABLE `chuyen_khoa_dich_vu`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `chuyen_khoa_dich_vu_chuyen_khoa_id_dich_vu_id_unique` (`chuyen_khoa_id`,`dich_vu_id`),
  ADD KEY `chuyen_khoa_dich_vu_dich_vu_id_foreign` (`dich_vu_id`);

--
-- Chỉ mục cho bảng `chuyen_khoa_loai_sieu_am`
--
ALTER TABLE `chuyen_khoa_loai_sieu_am`
  ADD PRIMARY KEY (`chuyen_khoa_id`,`loai_sieu_am_id`),
  ADD KEY `chuyen_khoa_loai_sieu_am_loai_sieu_am_id_foreign` (`loai_sieu_am_id`);

--
-- Chỉ mục cho bảng `chuyen_khoa_loai_xet_nghiem`
--
ALTER TABLE `chuyen_khoa_loai_xet_nghiem`
  ADD PRIMARY KEY (`chuyen_khoa_id`,`loai_xet_nghiem_id`),
  ADD KEY `chuyen_khoa_loai_xet_nghiem_loai_xet_nghiem_id_foreign` (`loai_xet_nghiem_id`);

--
-- Chỉ mục cho bảng `chuyen_khoa_loai_x_quang`
--
ALTER TABLE `chuyen_khoa_loai_x_quang`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ck_lxq_unique` (`chuyen_khoa_id`,`loai_x_quang_id`),
  ADD KEY `chuyen_khoa_loai_x_quang_loai_x_quang_id_foreign` (`loai_x_quang_id`);

--
-- Chỉ mục cho bảng `conversations`
--
ALTER TABLE `conversations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `conversations_benh_nhan_id_bac_si_id_unique` (`benh_nhan_id`,`bac_si_id`),
  ADD KEY `conversations_bac_si_id_foreign` (`bac_si_id`),
  ADD KEY `conversations_lich_hen_id_foreign` (`lich_hen_id`),
  ADD KEY `conversations_benh_nhan_id_bac_si_id_index` (`benh_nhan_id`,`bac_si_id`),
  ADD KEY `conversations_trang_thai_index` (`trang_thai`),
  ADD KEY `conversations_last_message_at_index` (`last_message_at`);

--
-- Chỉ mục cho bảng `coupons`
--
ALTER TABLE `coupons`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `coupons_ma_giam_gia_unique` (`ma_giam_gia`),
  ADD KEY `coupons_ngay_bat_dau_ngay_ket_thuc_index` (`ngay_bat_dau`,`ngay_ket_thuc`),
  ADD KEY `coupons_kich_hoat_index` (`kich_hoat`);

--
-- Chỉ mục cho bảng `danh_gias`
--
ALTER TABLE `danh_gias`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `danh_gias_user_id_lich_hen_id_unique` (`user_id`,`lich_hen_id`),
  ADD KEY `danh_gias_lich_hen_id_foreign` (`lich_hen_id`),
  ADD KEY `danh_gias_bac_si_id_trang_thai_index` (`bac_si_id`,`trang_thai`),
  ADD KEY `danh_gias_rating_index` (`rating`);

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
  ADD KEY `don_thuocs_lich_hen_id_foreign` (`lich_hen_id`),
  ADD KEY `don_thuocs_nguoi_cap_thuoc_id_foreign` (`nguoi_cap_thuoc_id`);

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
  ADD KEY `hoa_dons_ma_hoa_don_index` (`ma_hoa_don`),
  ADD KEY `hoa_dons_coupon_id_foreign` (`coupon_id`);

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
  ADD KEY `lich_hens_ngay_gio_idx` (`ngay_hen`,`thoi_gian_hen`),
  ADD KEY `lich_hens_checked_in_by_foreign` (`checked_in_by`);

--
-- Chỉ mục cho bảng `lich_lam_viecs`
--
ALTER TABLE `lich_lam_viecs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lich_lam_viecs_bac_si_id_foreign` (`bac_si_id`),
  ADD KEY `lich_lam_viecs_ngay_trong_tuan_index` (`ngay_trong_tuan`),
  ADD KEY `lich_lam_viecs_phong_id_foreign` (`phong_id`);

--
-- Chỉ mục cho bảng `lich_nghis`
--
ALTER TABLE `lich_nghis`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lich_nghis_bac_si_id_ngay_index` (`bac_si_id`,`ngay`);

--
-- Chỉ mục cho bảng `loai_sieu_ams`
--
ALTER TABLE `loai_sieu_ams`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `loai_xet_nghiems`
--
ALTER TABLE `loai_xet_nghiems`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `loai_xet_nghiems_ma_unique` (`ma`),
  ADD KEY `loai_xet_nghiems_phong_id_foreign` (`phong_id`),
  ADD KEY `loai_xet_nghiems_ten_index` (`ten`);

--
-- Chỉ mục cho bảng `loai_x_quangs`
--
ALTER TABLE `loai_x_quangs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `loai_x_quangs_ma_unique` (`ma`),
  ADD KEY `loai_x_quangs_phong_id_foreign` (`phong_id`);

--
-- Chỉ mục cho bảng `login_audits`
--
ALTER TABLE `login_audits`
  ADD PRIMARY KEY (`id`),
  ADD KEY `login_audits_user_id_created_at_index` (`user_id`,`created_at`),
  ADD KEY `login_audits_ip_index` (`ip`),
  ADD KEY `login_audits_status_index` (`status`);

--
-- Chỉ mục cho bảng `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `messages_conversation_id_index` (`conversation_id`),
  ADD KEY `messages_conversation_id_created_at_index` (`conversation_id`,`created_at`),
  ADD KEY `messages_user_id_is_read_index` (`user_id`,`is_read`);

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
-- Chỉ mục cho bảng `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`);

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
  ADD KEY `payment_logs_created_at_index` (`created_at`),
  ADD KEY `payment_logs_payable_type_payable_id_index` (`payable_type`,`payable_id`);

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
-- Chỉ mục cho bảng `sieu_ams`
--
ALTER TABLE `sieu_ams`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sieu_ams_phong_id_foreign` (`phong_id`),
  ADD KEY `sieu_ams_user_id_trang_thai_index` (`user_id`,`trang_thai`),
  ADD KEY `sieu_ams_benh_an_id_trang_thai_index` (`benh_an_id`,`trang_thai`),
  ADD KEY `sieu_ams_bac_si_chi_dinh_id_index` (`bac_si_chi_dinh_id`),
  ADD KEY `sieu_ams_bac_si_sieu_am_id_index` (`bac_si_sieu_am_id`),
  ADD KEY `sieu_ams_loai_sieu_am_id_index` (`loai_sieu_am_id`),
  ADD KEY `sieu_ams_trang_thai_index` (`trang_thai`),
  ADD KEY `sieu_ams_ngay_chi_dinh_index` (`ngay_chi_dinh`);

--
-- Chỉ mục cho bảng `slot_locks`
--
ALTER TABLE `slot_locks`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slot_locks_bac_si_id_ngay_gio_unique` (`bac_si_id`,`ngay`,`gio`),
  ADD KEY `slot_locks_user_id_foreign` (`user_id`),
  ADD KEY `slot_locks_expires_at_index` (`expires_at`);

--
-- Chỉ mục cho bảng `tags`
--
ALTER TABLE `tags`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tags_slug_unique` (`slug`);

--
-- Chỉ mục cho bảng `tai_khams`
--
ALTER TABLE `tai_khams`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tai_khams_lich_hen_id_foreign` (`lich_hen_id`),
  ADD KEY `tai_khams_benh_an_id_trang_thai_index` (`benh_an_id`,`trang_thai`),
  ADD KEY `tai_khams_user_id_trang_thai_index` (`user_id`,`trang_thai`),
  ADD KEY `tai_khams_bac_si_id_trang_thai_index` (`bac_si_id`,`trang_thai`);

--
-- Chỉ mục cho bảng `thanh_toans`
--
ALTER TABLE `thanh_toans`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `thanh_toans_idempotency_key_unique` (`idempotency_key`),
  ADD KEY `thanh_toans_provider_transaction_ref_index` (`provider`,`transaction_ref`),
  ADD KEY `thanh_toans_hoa_don_id_trang_thai_index` (`hoa_don_id`,`trang_thai`),
  ADD KEY `thanh_toans_payable_type_payable_id_index` (`payable_type`,`payable_id`);

--
-- Chỉ mục cho bảng `theo_doi_thai_kys`
--
ALTER TABLE `theo_doi_thai_kys`
  ADD PRIMARY KEY (`id`),
  ADD KEY `theo_doi_thai_kys_benh_an_id_foreign` (`benh_an_id`),
  ADD KEY `theo_doi_thai_kys_user_id_foreign` (`user_id`),
  ADD KEY `theo_doi_thai_kys_bac_si_id_foreign` (`bac_si_id`);

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
  ADD KEY `idx_xet_nghiem_bac_si_status` (`bac_si_id`,`trang_thai`),
  ADD KEY `idx_xet_nghiem_benh_an_time` (`benh_an_id`,`created_at`),
  ADD KEY `xet_nghiems_loai_xet_nghiem_id_foreign` (`loai_xet_nghiem_id`);

--
-- Chỉ mục cho bảng `x_quangs`
--
ALTER TABLE `x_quangs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `x_quangs_benh_an_id_foreign` (`benh_an_id`),
  ADD KEY `x_quangs_user_id_foreign` (`user_id`),
  ADD KEY `x_quangs_bac_si_id_foreign` (`bac_si_id`),
  ADD KEY `x_quangs_loai_x_quang_id_foreign` (`loai_x_quang_id`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `activity_log`
--
ALTER TABLE `activity_log`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT cho bảng `bac_sis`
--
ALTER TABLE `bac_sis`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT cho bảng `bai_viets`
--
ALTER TABLE `bai_viets`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `benh_ans`
--
ALTER TABLE `benh_ans`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `benh_an_audits`
--
ALTER TABLE `benh_an_audits`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT cho bảng `benh_an_files`
--
ALTER TABLE `benh_an_files`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `ca_dieu_chinh_bac_sis`
--
ALTER TABLE `ca_dieu_chinh_bac_sis`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `ca_lam_viec_nhan_viens`
--
ALTER TABLE `ca_lam_viec_nhan_viens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=141;

--
-- AUTO_INCREMENT cho bảng `chuyen_khoas`
--
ALTER TABLE `chuyen_khoas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT cho bảng `chuyen_khoa_dich_vu`
--
ALTER TABLE `chuyen_khoa_dich_vu`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT cho bảng `chuyen_khoa_loai_x_quang`
--
ALTER TABLE `chuyen_khoa_loai_x_quang`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT cho bảng `conversations`
--
ALTER TABLE `conversations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `coupons`
--
ALTER TABLE `coupons`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT cho bảng `danh_gias`
--
ALTER TABLE `danh_gias`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `danh_mucs`
--
ALTER TABLE `danh_mucs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `dich_vus`
--
ALTER TABLE `dich_vus`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=147;

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `don_thuoc_items`
--
ALTER TABLE `don_thuoc_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `hoan_tiens`
--
ALTER TABLE `hoan_tiens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `hoa_dons`
--
ALTER TABLE `hoa_dons`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `lich_hens`
--
ALTER TABLE `lich_hens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `lich_lam_viecs`
--
ALTER TABLE `lich_lam_viecs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=169;

--
-- AUTO_INCREMENT cho bảng `lich_nghis`
--
ALTER TABLE `lich_nghis`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `loai_sieu_ams`
--
ALTER TABLE `loai_sieu_ams`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT cho bảng `loai_xet_nghiems`
--
ALTER TABLE `loai_xet_nghiems`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT cho bảng `loai_x_quangs`
--
ALTER TABLE `loai_x_quangs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT cho bảng `login_audits`
--
ALTER TABLE `login_audits`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT cho bảng `messages`
--
ALTER TABLE `messages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=112;

--
-- AUTO_INCREMENT cho bảng `nhan_viens`
--
ALTER TABLE `nhan_viens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT cho bảng `nhan_vien_audits`
--
ALTER TABLE `nhan_vien_audits`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT cho bảng `nha_cung_caps`
--
ALTER TABLE `nha_cung_caps`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT cho bảng `nha_cung_cap_thuoc`
--
ALTER TABLE `nha_cung_cap_thuoc`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=401;

--
-- AUTO_INCREMENT cho bảng `notification_preferences`
--
ALTER TABLE `notification_preferences`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `patient_profiles`
--
ALTER TABLE `patient_profiles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `payment_logs`
--
ALTER TABLE `payment_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT cho bảng `phieu_nhap_items`
--
ALTER TABLE `phieu_nhap_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=109;

--
-- AUTO_INCREMENT cho bảng `phieu_xuats`
--
ALTER TABLE `phieu_xuats`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT cho bảng `phieu_xuat_items`
--
ALTER TABLE `phieu_xuat_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT cho bảng `phongs`
--
ALTER TABLE `phongs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT cho bảng `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT cho bảng `sieu_ams`
--
ALTER TABLE `sieu_ams`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `slot_locks`
--
ALTER TABLE `slot_locks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `tags`
--
ALTER TABLE `tags`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT cho bảng `tai_khams`
--
ALTER TABLE `tai_khams`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `thanh_toans`
--
ALTER TABLE `thanh_toans`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `theo_doi_thai_kys`
--
ALTER TABLE `theo_doi_thai_kys`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `thuocs`
--
ALTER TABLE `thuocs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=201;

--
-- AUTO_INCREMENT cho bảng `thuoc_khos`
--
ALTER TABLE `thuoc_khos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=114;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT cho bảng `xet_nghiems`
--
ALTER TABLE `xet_nghiems`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `x_quangs`
--
ALTER TABLE `x_quangs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

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
-- Các ràng buộc cho bảng `chuyen_khoa_dich_vu`
--
ALTER TABLE `chuyen_khoa_dich_vu`
  ADD CONSTRAINT `chuyen_khoa_dich_vu_chuyen_khoa_id_foreign` FOREIGN KEY (`chuyen_khoa_id`) REFERENCES `chuyen_khoas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `chuyen_khoa_dich_vu_dich_vu_id_foreign` FOREIGN KEY (`dich_vu_id`) REFERENCES `dich_vus` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `chuyen_khoa_loai_sieu_am`
--
ALTER TABLE `chuyen_khoa_loai_sieu_am`
  ADD CONSTRAINT `chuyen_khoa_loai_sieu_am_chuyen_khoa_id_foreign` FOREIGN KEY (`chuyen_khoa_id`) REFERENCES `chuyen_khoas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `chuyen_khoa_loai_sieu_am_loai_sieu_am_id_foreign` FOREIGN KEY (`loai_sieu_am_id`) REFERENCES `loai_sieu_ams` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `chuyen_khoa_loai_xet_nghiem`
--
ALTER TABLE `chuyen_khoa_loai_xet_nghiem`
  ADD CONSTRAINT `chuyen_khoa_loai_xet_nghiem_chuyen_khoa_id_foreign` FOREIGN KEY (`chuyen_khoa_id`) REFERENCES `chuyen_khoas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `chuyen_khoa_loai_xet_nghiem_loai_xet_nghiem_id_foreign` FOREIGN KEY (`loai_xet_nghiem_id`) REFERENCES `loai_xet_nghiems` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `chuyen_khoa_loai_x_quang`
--
ALTER TABLE `chuyen_khoa_loai_x_quang`
  ADD CONSTRAINT `chuyen_khoa_loai_x_quang_chuyen_khoa_id_foreign` FOREIGN KEY (`chuyen_khoa_id`) REFERENCES `chuyen_khoas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `chuyen_khoa_loai_x_quang_loai_x_quang_id_foreign` FOREIGN KEY (`loai_x_quang_id`) REFERENCES `loai_x_quangs` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `conversations`
--
ALTER TABLE `conversations`
  ADD CONSTRAINT `conversations_bac_si_id_foreign` FOREIGN KEY (`bac_si_id`) REFERENCES `bac_sis` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `conversations_benh_nhan_id_foreign` FOREIGN KEY (`benh_nhan_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `conversations_lich_hen_id_foreign` FOREIGN KEY (`lich_hen_id`) REFERENCES `lich_hens` (`id`) ON DELETE SET NULL;

--
-- Các ràng buộc cho bảng `danh_gias`
--
ALTER TABLE `danh_gias`
  ADD CONSTRAINT `danh_gias_bac_si_id_foreign` FOREIGN KEY (`bac_si_id`) REFERENCES `bac_sis` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `danh_gias_lich_hen_id_foreign` FOREIGN KEY (`lich_hen_id`) REFERENCES `lich_hens` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `danh_gias_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

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
  ADD CONSTRAINT `don_thuocs_nguoi_cap_thuoc_id_foreign` FOREIGN KEY (`nguoi_cap_thuoc_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
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
  ADD CONSTRAINT `hoa_dons_coupon_id_foreign` FOREIGN KEY (`coupon_id`) REFERENCES `coupons` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `hoa_dons_lich_hen_id_foreign` FOREIGN KEY (`lich_hen_id`) REFERENCES `lich_hens` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `hoa_dons_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `lich_hens`
--
ALTER TABLE `lich_hens`
  ADD CONSTRAINT `lich_hens_bac_si_id_foreign` FOREIGN KEY (`bac_si_id`) REFERENCES `bac_sis` (`id`),
  ADD CONSTRAINT `lich_hens_cancelled_by_foreign` FOREIGN KEY (`cancelled_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `lich_hens_checked_in_by_foreign` FOREIGN KEY (`checked_in_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
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
-- Các ràng buộc cho bảng `loai_xet_nghiems`
--
ALTER TABLE `loai_xet_nghiems`
  ADD CONSTRAINT `loai_xet_nghiems_phong_id_foreign` FOREIGN KEY (`phong_id`) REFERENCES `phongs` (`id`) ON DELETE SET NULL;

--
-- Các ràng buộc cho bảng `loai_x_quangs`
--
ALTER TABLE `loai_x_quangs`
  ADD CONSTRAINT `loai_x_quangs_phong_id_foreign` FOREIGN KEY (`phong_id`) REFERENCES `phongs` (`id`) ON DELETE SET NULL;

--
-- Các ràng buộc cho bảng `login_audits`
--
ALTER TABLE `login_audits`
  ADD CONSTRAINT `login_audits_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Các ràng buộc cho bảng `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_conversation_id_foreign` FOREIGN KEY (`conversation_id`) REFERENCES `conversations` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `messages_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

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
-- Các ràng buộc cho bảng `sieu_ams`
--
ALTER TABLE `sieu_ams`
  ADD CONSTRAINT `sieu_ams_bac_si_chi_dinh_id_foreign` FOREIGN KEY (`bac_si_chi_dinh_id`) REFERENCES `bac_sis` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sieu_ams_bac_si_sieu_am_id_foreign` FOREIGN KEY (`bac_si_sieu_am_id`) REFERENCES `bac_sis` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `sieu_ams_benh_an_id_foreign` FOREIGN KEY (`benh_an_id`) REFERENCES `benh_ans` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sieu_ams_loai_sieu_am_id_foreign` FOREIGN KEY (`loai_sieu_am_id`) REFERENCES `loai_sieu_ams` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `sieu_ams_phong_id_foreign` FOREIGN KEY (`phong_id`) REFERENCES `phongs` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `sieu_ams_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `slot_locks`
--
ALTER TABLE `slot_locks`
  ADD CONSTRAINT `slot_locks_bac_si_id_foreign` FOREIGN KEY (`bac_si_id`) REFERENCES `bac_sis` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `slot_locks_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Các ràng buộc cho bảng `tai_khams`
--
ALTER TABLE `tai_khams`
  ADD CONSTRAINT `tai_khams_bac_si_id_foreign` FOREIGN KEY (`bac_si_id`) REFERENCES `bac_sis` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `tai_khams_benh_an_id_foreign` FOREIGN KEY (`benh_an_id`) REFERENCES `benh_ans` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tai_khams_lich_hen_id_foreign` FOREIGN KEY (`lich_hen_id`) REFERENCES `lich_hens` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `tai_khams_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Các ràng buộc cho bảng `thanh_toans`
--
ALTER TABLE `thanh_toans`
  ADD CONSTRAINT `thanh_toans_hoa_don_id_foreign` FOREIGN KEY (`hoa_don_id`) REFERENCES `hoa_dons` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `theo_doi_thai_kys`
--
ALTER TABLE `theo_doi_thai_kys`
  ADD CONSTRAINT `theo_doi_thai_kys_bac_si_id_foreign` FOREIGN KEY (`bac_si_id`) REFERENCES `bac_sis` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `theo_doi_thai_kys_benh_an_id_foreign` FOREIGN KEY (`benh_an_id`) REFERENCES `benh_ans` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `theo_doi_thai_kys_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

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
  ADD CONSTRAINT `xet_nghiems_loai_xet_nghiem_id_foreign` FOREIGN KEY (`loai_xet_nghiem_id`) REFERENCES `loai_xet_nghiems` (`id`) ON DELETE SET NULL;

--
-- Các ràng buộc cho bảng `x_quangs`
--
ALTER TABLE `x_quangs`
  ADD CONSTRAINT `x_quangs_bac_si_id_foreign` FOREIGN KEY (`bac_si_id`) REFERENCES `bac_sis` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `x_quangs_benh_an_id_foreign` FOREIGN KEY (`benh_an_id`) REFERENCES `benh_ans` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `x_quangs_loai_x_quang_id_foreign` FOREIGN KEY (`loai_x_quang_id`) REFERENCES `loai_x_quangs` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `x_quangs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
