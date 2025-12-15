-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th12 13, 2025 lúc 03:54 PM
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
(2, 'default', 'Nhân viên gọi bệnh nhân vào khám', 'App\\Models\\LichHen', NULL, 3, 'App\\Models\\User', 149, '{\"action\":\"call_next\"}', NULL, '2025-12-13 07:40:21', '2025-12-13 07:40:21');

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
(105, 136, 'TS.BS Nguyễn Thị Lan Anh', 'lananh@vietcare.com', 'Sản Khoa', 25, 'Nguyên Trưởng khoa Sản bệnh viện Từ Dũ. Chuyên gia hàng đầu về quản lý thai kỳ nguy cơ cao (tiền sản giật, đái tháo đường thai kỳ) và đỡ sinh khó.', 'Đang hoạt động', '0909111001', 'avatars/NgwECmcOF5OsDRW5m7U47jEgpIwpJNGv9yBECZpz.png', 'Quận 3, TP.HCM', '2025-12-12 07:01:42', '2025-12-12 07:13:43'),
(106, 137, 'ThS.BS Phạm Văn Hùng', 'hunghoang@vietcare.com', 'Sản Khoa', 12, 'Thạc sĩ Y khoa chuyên ngành Sản phụ khoa, từng tu nghiệp tại Pháp. Nổi tiếng \"mát tay\" trong đỡ sinh thường, may thẩm mỹ tầng sinh môn và phẫu thuật lấy thai.', 'Đang hoạt động', '0909111002', 'avatars/Dc4rhJ9iMhQbGivllt2KH4SC3wcev2SoV5thMf9W.png', 'Quận 7, TP.HCM', '2025-12-12 07:01:42', '2025-12-13 04:29:47'),
(107, 138, 'BSCKII Trần Thu Hà', 'hatran@vietcare.com', 'Phụ Khoa', 18, 'Chuyên gia phẫu thuật nội soi phụ khoa (bóc u xơ tử cung, u nang buồng trứng). Điều trị chuyên sâu các bệnh lý sàn chậu, sa tử cung và són tiểu ở phụ nữ.', 'Đang hoạt động', '0909111003', 'avatars/msxfncVI0Y4IfkPa2P3Akg9wViu4eg8BkeeFI1kB.png', 'Quận 10, TP.HCM', '2025-12-12 07:01:42', '2025-12-12 07:13:11'),
(108, 139, 'BS.CKI Nguyễn Thanh Vân', 'vannguyen@vietcare.com', 'Phụ Khoa', 10, 'Chuyên sâu về soi cổ tử cung, điều trị lộ tuyến và các bệnh viêm nhiễm phụ khoa tái phát. Tư vấn sức khỏe tiền mãn kinh.', 'Đang hoạt động', '0909111004', 'avatars/Kn1lryOqsrl2hnJ2oZxSUZR41NdgYBACo1Bn2A2t.png', 'Quận Tân Bình, TP.HCM', '2025-12-12 07:01:42', '2025-12-12 07:12:50'),
(109, 140, 'TS.BS Hoàng Minh Tuấn', 'tuanhoang@vietcare.com', 'Hiếm muộn & Vô sinh', 20, 'Nguyên Phó Giám đốc Trung tâm Hỗ trợ sinh sản Quốc gia. \"Bàn tay vàng\" điều trị vô sinh nam và thực hiện kỹ thuật IVF/ICSI với tỷ lệ thành công cao.', 'Đang hoạt động', '0909111005', 'avatars/tFhDIqyGiJGubaeaPIwtHzZhVQKWUQl9ckcKskDv.png', 'TP. Thủ Đức, TP.HCM', '2025-12-12 07:01:42', '2025-12-12 07:12:33'),
(110, 141, 'ThS.BS Võ Thị Ngọc', 'ngocvo@vietcare.com', 'Hiếm muộn & Vô sinh', 15, 'Chuyên gia về nội tiết sinh sản. Rất giỏi trong việc kích trứng, canh niêm mạc và điều trị hội chứng buồng trứng đa nang (PCOS) cho các cặp đôi mong con.', 'Đang hoạt động', '0909111006', 'avatars/javPWxwi07wRO58pqLeC79LKbHTmU1zEmb5CkZA4.png', 'Quận 5, TP.HCM', '2025-12-12 07:01:42', '2025-12-12 07:16:27'),
(111, 142, 'BS.CKI Phạm Thanh Thúy', 'thuypham@vietcare.com', 'Siêu âm & Chẩn đoán hình ảnh', 10, 'Có chứng chỉ FMF Quốc tế (London). Chuyên siêu âm 4D/5D tầm soát dị tật thai nhi sớm và siêu âm Doppler tim thai, mạch máu.', 'Đang hoạt động', '0909111007', 'avatars/7Am7ipu0EktY6wXSN9cWfcRtVNtBNWiFqcAToFY6.png', 'Quận 1, TP.HCM', '2025-12-12 07:01:42', '2025-12-12 07:14:04'),
(112, 143, 'ThS.BS Nguyễn Hữu Phước', 'phuocnguyen@vietcare.com', 'Sàng lọc trước sinh', 9, 'Chuyên gia Di truyền học. Tư vấn chuyên sâu về các kết quả sàng lọc NIPT, Double Test, Triple Test và chọc ối chẩn đoán bất thường nhiễm sắc thể.', 'Đang hoạt động', '0909111008', 'avatars/RgY8tGvX7ZN7Q6Uar9Z5JF7hhcyxWgsgHpzMkK2m.png', 'Quận Bình Thạnh, TP.HCM', '2025-12-12 07:01:43', '2025-12-12 07:12:18'),
(113, 144, 'BS.CKI Đỗ Mỹ Linh', 'linhdo@vietcare.com', 'Kế hoạch hóa gia đình', 12, 'Chuyên thực hiện các thủ thuật tránh thai hiện đại: Cấy que Implanon, đặt vòng nội tiết Mirena. Thao tác nhẹ nhàng, không đau, tư vấn tận tình.', 'Đang hoạt động', '0909111009', 'avatars/rSIA5L9IxPl7e3ckaiw8iGmiuiajr6Cc8e6GZLYo.png', 'Quận Phú Nhuận, TP.HCM', '2025-12-12 07:01:43', '2025-12-12 07:12:04'),
(114, 145, 'ThS.BS Lê Thị Mai', 'maile@vietcare.com', 'Sản Khoa', 8, 'Bác sĩ trẻ, nhiệt huyết, cập nhật liên tục các phương pháp thai giáo và sinh nở hiện đại (da kề da, kẹp rốn chậm). Được nhiều mẹ bầu trẻ tin tưởng.', 'Đang hoạt động', '0909111010', 'avatars/vF1nqgNi2AZSwjxA7ii2rz5xqH1KPzDjDqeHD8nE.png', 'Quận 4, TP.HCM', '2025-12-12 07:01:43', '2025-12-12 07:11:49'),
(115, 146, 'BS.CKI Trần Văn Minh', 'minh.xetnghiem@vietcare.com', 'Xét nghiệm', 15, 'Trưởng khoa Xét nghiệm. Chuyên gia về Huyết học và Vi sinh. Đảm bảo quy trình xét nghiệm đạt chuẩn ISO 15189, kết quả chính xác và nhanh chóng.', 'Đang hoạt động', '0909111011', 'avatars/WkE01UOSwyyKFNjyZVn0lMBkR8lkTfFBCKc0UE4B.png', 'Quận 8, TP.HCM', '2025-12-12 07:01:43', '2025-12-12 07:11:35'),
(116, 147, 'ThS.BS Nguyễn Ngọc Lan', 'lan.thammy@vietcare.com', 'Sàn chậu & Thẩm mỹ nữ', 10, 'Chuyên gia phục hồi sàn chậu sau sinh và thẩm mỹ vùng kín. Rất mát tay trong các thủ thuật làm hồng, se khít và điều trị són tiểu không phẫu thuật.', 'Đang hoạt động', '0909111012', 'avatars/wlRgm2tm7BDT0t0gOOmYwIBEvVFpYVTRkxpq4Vaf.png', 'Quận 2, TP.HCM', '2025-12-12 07:01:43', '2025-12-12 07:11:14');

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
(105, 15),
(106, 15),
(107, 16),
(108, 16),
(109, 17),
(110, 17),
(111, 18),
(112, 19),
(113, 20),
(114, 15),
(115, 22),
(116, 21);

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
(105, 17),
(106, 18),
(107, 20),
(109, 23),
(110, 24),
(111, 19),
(112, 26),
(113, 21),
(115, 25),
(116, 22);

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
(1, 13, 1, 'Chế độ dinh dưỡng vàng cho bà bầu 3 tháng đầu: Ăn đúng để con khỏe, mẹ không tăng cân', 'che-do-dinh-duong-vang-cho-ba-bau-3-thang-dau-an-dung-de-co', '3 tháng đầu là giai đoạn quan trọng nhất để hình thành các cơ quan của thai nhi. Cùng tìm hiểu thực đơn chuẩn giúp mẹ khỏe, bé phát triển toàn diện và giảm nghén hiệu quả.', '<p>Mang thai 3 tháng đầu (tam cá nguyệt thứ nhất) là giai đoạn quan trọng nhất để hình thành các cơ quan thiết yếu của thai nhi như tim, não và tủy sống. Tuy nhiên, đây cũng là giai đoạn mẹ bầu dễ bị nghén nhất. Vậy làm sao để ăn uống đủ chất mà vẫn thoải mái?</p><p><strong>1. Axit Folic – \"Thần dược\" ngăn ngừa dị tật</strong></p><p>Nếu có một chất dinh dưỡng bắt buộc phải bổ sung ngay khi biết tin có thai, đó chính là Axit Folic (Vitamin B9). Dưỡng chất này đóng vai trò then chốt trong việc ngăn ngừa các dị tật ống thần kinh ở thai nhi (nứt đốt sống, vô sọ).</p><p><strong>Nhu cầu khuyến nghị:</strong> 400mcg - 600mcg/ngày.</p><p><strong>Thực phẩm giàu Folate:</strong> Các loại rau màu xanh đậm (súp lơ, cải bó xôi), các loại đậu, ngũ cốc nguyên hạt và trái cây họ cam quýt.</p><p><strong>2. Protein và Sắt – Xây dựng tế bào máu</strong></p><p>Thể tích máu của mẹ sẽ tăng lên 50% trong thai kỳ để nuôi dưỡng bào thai. Do đó, thiếu sắt sẽ dẫn đến thiếu máu, gây mệt mỏi và chóng mặt. Mẹ nên bổ sung: Thịt bò nạc, ức gà, cá hồi (đã nấu chín kỹ), trứng gà và các loại hạt.</p><p><strong>3. Danh sách thực phẩm cần \"Tuyệt đối tránh\"</strong></p><p>Để đảm bảo an toàn cho thai nhi, mẹ bầu 3 tháng đầu cần loại bỏ ngay các món sau khỏi thực đơn:</p><p><strong>Thực phẩm sống:</strong> Sushi, gỏi cá, trứng lòng đào, thịt tái (nguy cơ nhiễm khuẩn Salmonella, E.coli).</p><p><strong>Rau củ gây co thắt tử cung:</strong> Rau răm, đu đủ xanh, dứa (thơm), ngải cứu.</p><p><strong>Chất kích thích:</strong> Rượu, bia, thuốc lá và hạn chế tối đa Cafein.</p><p><strong>4. Mẹo nhỏ giúp mẹ vượt qua cơn nghén</strong></p><p>Nếu bạn bị nôn nghén nặng, hãy chia nhỏ bữa ăn thành 5-6 bữa/ngày thay vì 3 bữa chính. Luôn chuẩn bị sẵn bánh quy gừng hoặc uống nước chanh ấm vào buổi sáng để giảm cảm giác buồn nôn.</p>', 'published', '2025-12-12 08:30:00', 'Dinh dưỡng bà bầu 3 tháng đầu: Ăn gì để vào con không vào mẹ?', 'Hướng dẫn chi tiết thực đơn cho mẹ bầu 3 tháng đầu. Danh sách thực phẩm giàu Axit Folic, Sắt và những món ăn cần kiêng kỵ tuyệt đối để tránh sảy thai.', 'http://127.0.0.1:8000/storage/uploads/posts/1765385670_image-3-compressed2.jpg', '2025-12-12 08:30:17', '2025-12-12 08:35:13', NULL),
(2, 13, 2, 'Quy trình Thụ tinh trong ống nghiệm (IVF) chuẩn Châu Âu tại Phòng khám', 'quy-trinh-thu-tinh-trong-ong-nghiem-ivf-chuan-chau-au-tai', 'Giải đáp chi tiết quy trình IVF chuẩn y khoa, từ bước kích trứng, chọc hút đến chuyển phôi. Hy vọng mới cho các cặp vợ chồng mong con với tỷ lệ thành công cao.', '<p>Thụ tinh trong ống nghiệm (IVF) là kỹ thuật hỗ trợ sinh sản hiện đại nhất hiện nay, mang lại hy vọng cho hàng triệu cặp vợ chồng hiếm muộn. Tại phòng khám của chúng tôi, quy trình IVF được thực hiện khép kín với hệ thống phòng Lab đạt chuẩn ISO.</p><p><strong>Giai đoạn 1: Kích thích buồng trứng (Ngày 2 của chu kỳ)</strong></p><p>Bác sĩ sẽ chỉ định tiêm thuốc kích thích buồng trứng liên tục trong khoảng 9-11 ngày. Mục đích là để thu được số lượng nang noãn tối ưu (thay vì chỉ 1 trứng rụng như chu kỳ tự nhiên). Trong thời gian này, bạn sẽ được siêu âm và xét nghiệm máu 3-4 lần để theo dõi sự phát triển của nang trứng.</p><p><strong>Giai đoạn 2: Chọc hút trứng và Lấy tinh trùng</strong></p><p>Khi nang trứng đạt kích thước chuẩn, mũi tiêm rụng trứng sẽ được thực hiện. 36 giờ sau, bác sĩ tiến hành chọc hút trứng. Quy trình này diễn ra nhẹ nhàng dưới sự hỗ trợ của gây mê, chỉ mất khoảng 15-20 phút. Song song đó, người chồng sẽ được lấy mẫu tinh trùng để lọc rửa, chọn ra những \"chiến binh\" khỏe mạnh nhất.</p><p><strong>Giai đoạn 3: Tạo phôi và Nuôi cấy phôi</strong></p><p>Trứng và tinh trùng được kết hợp trong đĩa cấy tại phòng Lab. Các chuyên viên phôi học sẽ theo dõi quá trình phân chia tế bào:</p><p><strong>Phôi ngày 3:</strong> Phôi có khoảng 6-8 tế bào.</p><p><strong>Phôi ngày 5 (Phôi nang):</strong> Phôi có hàng trăm tế bào, khả năng làm tổ cao hơn.</p><p><strong>Giai đoạn 4: Chuyển phôi và Thử thai</strong></p><p>Bác sĩ dùng một ống thông (catheter) rất nhỏ, mềm để đưa phôi vào buồng tử cung người mẹ. Đây là thủ thuật không đau. Sau 14 ngày, mẹ có thể xét nghiệm Beta-HCG để đón nhận tin vui.</p><blockquote><p><strong>Lưu ý:</strong> Tỷ lệ thành công của IVF phụ thuộc rất lớn vào độ tuổi của người vợ và chất lượng phòng Lab. Hãy đặt lịch tư vấn sớm để không bỏ lỡ \"thời điểm vàng\".</p></blockquote>', 'published', '2025-12-12 08:30:00', 'Quy trình thụ tinh trong ống nghiệm (IVF) chuẩn Châu Âu - Tỷ lệ đậu thai cao', 'Tìm hiểu quy trình IVF khép kín tại phòng khám: Kích trứng, chọc hút, nuôi cấy phôi và chuyển phôi. Giải pháp tối ưu cho các cặp vợ chồng hiếm muộn lâu năm.', 'http://127.0.0.1:8000/storage/uploads/posts/1765385732_tai-xuong.jfif', '2025-12-12 08:30:17', '2025-12-12 08:33:25', NULL),
(3, 13, 3, 'So sánh Double Test, Triple Test và NIPT: Mẹ bầu nên chọn gói nào?', 'so-sanh-double-test-triple-test-va-nipt-me-bau-nen-chon-go', 'So sánh ưu nhược điểm của các phương pháp sàng lọc trước sinh phổ biến hiện nay. Tại sao NIPT lại được nhiều mẹ bầu lựa chọn dù chi phí cao hơn?', '<p>Sàng lọc trước sinh là bước không thể thiếu để phát hiện sớm các dị tật bẩm sinh do bất thường nhiễm sắc thể (NST). Hiện nay có 3 phương pháp phổ biến, vậy đâu là lựa chọn tốt nhất cho mẹ?</p><p><strong>1. Double Test (Sàng lọc quý I)</strong></p><p><strong>Thời điểm:</strong> Tuần thai 11 - 13 tuần 6 ngày.</p><p><strong>Cách thức:</strong> Kết hợp siêu âm đo độ mờ da gáy và xét nghiệm máu mẹ.</p><p><strong>Độ chính xác:</strong> Khoảng 80 - 85%.</p><p><strong>Phát hiện:</strong> Hội chứng Down, Edwards, Patau.</p><p><strong>2. Triple Test (Sàng lọc quý II)</strong></p><p><strong>Thời điểm:</strong> Tuần thai 15 - 18.</p><p><strong>Cách thức:</strong> Xét nghiệm 3 chỉ số sinh hóa trong máu mẹ.</p><p><strong>Độ chính xác:</strong> Thấp hơn Double Test (khoảng 70%).</p><p><strong>Phát hiện:</strong> Nguy cơ dị tật ống thần kinh và các hội chứng NST.</p><p><strong>3. NIPT (Sàng lọc trước sinh không xâm lấn - Cao cấp)</strong></p><p>Đây là phương pháp tiên tiến nhất hiện nay, phân tích ADN tự do của thai nhi (cfDNA) có trong máu mẹ.</p><p><strong>Thời điểm:</strong> Thực hiện rất sớm, từ tuần thai thứ 9.</p><p><strong>Độ chính xác:</strong> &gt; 99%. Gần như tuyệt đối.</p><p><strong>Ưu điểm vượt trội:</strong> Sàng lọc được toàn bộ 23 cặp NST, phát hiện cả các đột biến vi mất đoạn mà siêu âm hay xét nghiệm thường không thấy.</p><p><strong>An toàn:</strong> Chỉ lấy 7-10ml máu mẹ, hoàn toàn không xâm lấn, không gây hại cho thai nhi.</p><p><strong>Kết luận</strong></p><p>Nếu có điều kiện kinh tế, các chuyên gia khuyến cáo mẹ nên chọn <strong>NIPT</strong> ngay từ tuần thứ 10 để an tâm tuyệt đối suốt thai kỳ, giảm thiểu việc phải chọc ối không cần thiết.</p>', 'published', '2025-12-12 08:30:00', 'So sánh Double Test, Triple Test và NIPT: Mẹ bầu nên chọn gói nào?', 'Phân tích ưu nhược điểm và độ chính xác của các phương pháp sàng lọc dị tật thai nhi. Tại sao bác sĩ khuyên dùng NIPT từ tuần thứ 9?', 'http://127.0.0.1:8000/storage/uploads/posts/1765388323_cham-soc-suc-khoe-cho-ba-bau-02.jpg', '2025-12-12 08:30:17', '2025-12-12 08:34:07', NULL),
(4, 13, 4, '[HOT] Chào đón Giáng Sinh - Tặng gói quà sơ sinh 5 Triệu khi đăng ký Thai sản trọn gói', 'hot-chao-don-giang-sinh-tang-goi-qua-so-sinh-5-trieu-khi', 'Tri ân khách hàng dịp cuối năm, phòng khám dành tặng hàng ngàn voucher giảm giá và quà tặng sơ sinh cao cấp khi đăng ký gói theo dõi thai kỳ trong tháng 12.', '<p>Thấu hiểu nỗi lo chi phí của các gia đình trẻ, Phòng khám Sản-Phụ khoa xin gửi đến chương trình ưu đãi lớn nhất năm: <strong>\"Giáng sinh an lành - Đón rồng con khỏe mạnh\"</strong>.</p><p><strong>🎁 Chi tiết ưu đãi:</strong></p><p><strong>GIẢM TRỰC TIẾP 20%</strong> chi phí khi đăng ký Gói theo dõi thai kỳ từ tuần 12.</p><p><strong>TẶNG NGAY</strong> gói sàng lọc sơ sinh (lấy máu gót chân) cho bé sau sinh trị giá 2.000.000đ.</p><p>Miễn phí 01 lần siêu âm 4D VIP (có ghi đĩa/gửi file video).</p><p>Tặng bộ quà tặng mẹ &amp; bé cao cấp: Balo bỉm sữa, quần áo sơ sinh...</p><p><strong>⏰ Thời gian và Điều kiện áp dụng:</strong></p><p>Chương trình diễn ra từ: <strong>10/12/2025 đến hết 31/12/2025</strong>.</p><p>Áp dụng cho khách hàng đặt cọc online hoặc đến trực tiếp phòng khám.</p><p>👉 <strong>Đừng bỏ lỡ cơ hội chăm sóc thai kỳ chuẩn quốc tế với chi phí tiết kiệm nhất! Liên hệ Hotline ngay để được tư vấn.</strong></p>', 'published', '2025-12-12 08:30:00', '[HOT] Ưu đãi thai sản trọn gói tháng 12: Giảm 20% + Tặng quà 5 Triệu', 'Chương trình tri ân lớn nhất năm. Giảm ngay 20% chi phí thai sản trọn gói, tặng gói sàng lọc sơ sinh và bộ quà tặng cao cấp cho mẹ và bé.', 'http://127.0.0.1:8000/storage/uploads/posts/1765385894_tai-xuong-2.jfif', '2025-12-12 08:30:17', '2025-12-12 08:34:39', NULL);

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
  `ngay_kham` date NOT NULL DEFAULT '2025-12-12',
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
(1, 148, 108, 3, '2025-12-13', 'Khám Gói Khám Phụ khoa Tổng quát (VIP)', 'Test', 'Test', 'Test', 'Bệnh án được tạo tự động khi bắt đầu khám', '2025-12-13 07:56:24', '2025-12-13 07:59:05');

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
(1, 1, 139, 'created', NULL, '{\"user_id\":148,\"bac_si_id\":108,\"lich_hen_id\":3,\"ngay_kham\":\"2025-12-13 14:56:24\",\"tieu_de\":\"Kh\\u00e1m G\\u00f3i Kh\\u00e1m Ph\\u1ee5 khoa T\\u1ed5ng qu\\u00e1t (VIP)\",\"trieu_chung\":\"\",\"chuan_doan\":\"\",\"dieu_tri\":\"\",\"ghi_chu\":\"B\\u1ec7nh \\u00e1n \\u0111\\u01b0\\u1ee3c t\\u1ea1o t\\u1ef1 \\u0111\\u1ed9ng khi b\\u1eaft \\u0111\\u1ea7u kh\\u00e1m\",\"updated_at\":\"2025-12-13 14:56:24\",\"created_at\":\"2025-12-13 14:56:24\",\"id\":1}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-13 07:56:24', '2025-12-13 07:56:24'),
(2, 1, 139, 'updated', '{\"id\":1,\"user_id\":148,\"bac_si_id\":108,\"lich_hen_id\":3,\"ngay_kham\":\"2025-12-12T17:00:00.000000Z\",\"tieu_de\":\"Kh\\u00e1m G\\u00f3i Kh\\u00e1m Ph\\u1ee5 khoa T\\u1ed5ng qu\\u00e1t (VIP)\",\"trieu_chung\":\"\",\"chuan_doan\":\"\",\"dieu_tri\":\"\",\"ghi_chu\":\"B\\u1ec7nh \\u00e1n \\u0111\\u01b0\\u1ee3c t\\u1ea1o t\\u1ef1 \\u0111\\u1ed9ng khi b\\u1eaft \\u0111\\u1ea7u kh\\u00e1m\",\"created_at\":\"2025-12-13T07:56:24.000000Z\",\"updated_at\":\"2025-12-13T07:56:24.000000Z\"}', '{\"id\":1,\"user_id\":\"148\",\"bac_si_id\":\"108\",\"lich_hen_id\":\"3\",\"ngay_kham\":\"2025-12-13 00:00:00\",\"tieu_de\":\"Kh\\u00e1m G\\u00f3i Kh\\u00e1m Ph\\u1ee5 khoa T\\u1ed5ng qu\\u00e1t (VIP)\",\"trieu_chung\":\"Test\",\"chuan_doan\":\"Test\",\"dieu_tri\":\"Test\",\"ghi_chu\":\"B\\u1ec7nh \\u00e1n \\u0111\\u01b0\\u1ee3c t\\u1ea1o t\\u1ef1 \\u0111\\u1ed9ng khi b\\u1eaft \\u0111\\u1ea7u kh\\u00e1m\",\"created_at\":\"2025-12-13 14:56:24\",\"updated_at\":\"2025-12-13 14:59:05\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-13 07:59:05', '2025-12-13 07:59:05'),
(3, 1, 139, 'test_uploaded', NULL, '{\"description\":\"Upload k\\u1ebft qu\\u1ea3 x\\u00e9t nghi\\u1ec7m: Testt\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-13 08:04:38', '2025-12-13 08:04:38');

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
(1, 1, 'screenshot_1765433090.png', 'files/IYkIT19GKHTVlLQedQlczIYMiKL4EakFLKzRnl0i.png', 'benh_an_private', 'image/png', 630260, 139, '2025-12-13 07:59:05', '2025-12-13 07:59:05');

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
(2, 105, '2025-12-14', '07:30:00', '11:30:00', 'add', 'Thêm ca Chủ nhật theo yêu cầu', NULL, '2025-12-13 02:39:30', '2025-12-13 02:39:30');

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
(2, 9, '2025-12-13', '17:30:00', '20:30:00', 'Lễ tân', '2025-12-13 03:41:58', '2025-12-13 03:41:58'),
(3, 10, '2025-12-13', '08:00:00', '12:00:00', 'Điều dưỡng trưởng', '2025-12-13 03:41:58', '2025-12-13 03:41:58'),
(4, 13, '2025-12-13', '08:00:00', '12:00:00', 'Thu ngân', '2025-12-13 03:41:58', '2025-12-13 03:41:58'),
(5, 14, '2025-12-13', '07:30:00', '11:30:00', 'Dược sĩ', '2025-12-13 03:41:58', '2025-12-13 03:41:58'),
(8, 8, '2025-12-15', '08:00:00', '17:00:00', 'Nhân viên CSKH', '2025-12-13 03:41:58', '2025-12-13 03:41:58'),
(9, 10, '2025-12-15', '08:00:00', '16:00:00', 'Điều dưỡng trưởng', '2025-12-13 03:41:58', '2025-12-13 03:41:58'),
(10, 11, '2025-12-15', '08:00:00', '16:30:00', 'Nữ hộ sinh', '2025-12-13 03:41:58', '2025-12-13 03:41:58'),
(11, 12, '2025-12-15', '08:00:00', '17:00:00', 'Điều dưỡng đa khoa', '2025-12-13 03:41:58', '2025-12-13 03:41:58'),
(12, 13, '2025-12-15', '08:00:00', '17:00:00', 'Thu ngân', '2025-12-13 03:41:58', '2025-12-13 03:41:58'),
(13, 14, '2025-12-15', '07:30:00', '11:30:00', 'Dược sĩ', '2025-12-13 03:41:58', '2025-12-13 03:41:58'),
(14, 15, '2025-12-15', '09:00:00', '18:00:00', 'Quản lý phòng khám', '2025-12-13 03:41:58', '2025-12-13 03:41:58'),
(15, 16, '2025-12-15', '09:00:00', '18:00:00', 'Nhân viên IT / Kỹ thuật', '2025-12-13 03:41:58', '2025-12-13 03:41:58'),
(18, 8, '2025-12-16', '08:00:00', '17:00:00', 'Nhân viên CSKH', '2025-12-13 03:41:58', '2025-12-13 03:41:58'),
(19, 9, '2025-12-16', '17:30:00', '20:30:00', 'Lễ tân', '2025-12-13 03:41:58', '2025-12-13 03:41:58'),
(20, 10, '2025-12-16', '08:00:00', '16:00:00', 'Điều dưỡng trưởng', '2025-12-13 03:41:58', '2025-12-13 03:41:58'),
(21, 11, '2025-12-16', '08:00:00', '16:30:00', 'Nữ hộ sinh', '2025-12-13 03:41:58', '2025-12-13 03:41:58'),
(22, 12, '2025-12-16', '08:00:00', '17:00:00', 'Điều dưỡng đa khoa', '2025-12-13 03:41:58', '2025-12-13 03:41:58'),
(23, 13, '2025-12-16', '08:00:00', '17:00:00', 'Thu ngân', '2025-12-13 03:41:58', '2025-12-13 03:41:58'),
(24, 14, '2025-12-16', '07:30:00', '11:30:00', 'Dược sĩ', '2025-12-13 03:41:58', '2025-12-13 03:41:58'),
(25, 15, '2025-12-16', '09:00:00', '18:00:00', 'Quản lý phòng khám', '2025-12-13 03:41:58', '2025-12-13 03:41:58'),
(26, 16, '2025-12-16', '09:00:00', '18:00:00', 'Nhân viên IT / Kỹ thuật', '2025-12-13 03:41:58', '2025-12-13 03:41:58'),
(29, 8, '2025-12-17', '08:00:00', '17:00:00', 'Nhân viên CSKH', '2025-12-13 03:41:58', '2025-12-13 03:41:58'),
(30, 9, '2025-12-17', '17:30:00', '20:30:00', 'Lễ tân', '2025-12-13 03:41:58', '2025-12-13 03:41:58'),
(31, 10, '2025-12-17', '08:00:00', '16:00:00', 'Điều dưỡng trưởng', '2025-12-13 03:41:58', '2025-12-13 03:41:58'),
(32, 11, '2025-12-17', '08:00:00', '16:30:00', 'Nữ hộ sinh', '2025-12-13 03:41:58', '2025-12-13 03:41:58'),
(33, 12, '2025-12-17', '08:00:00', '17:00:00', 'Điều dưỡng đa khoa', '2025-12-13 03:41:58', '2025-12-13 03:41:58'),
(34, 13, '2025-12-17', '08:00:00', '17:00:00', 'Thu ngân', '2025-12-13 03:41:58', '2025-12-13 03:41:58'),
(35, 14, '2025-12-17', '07:30:00', '11:30:00', 'Dược sĩ', '2025-12-13 03:41:58', '2025-12-13 03:41:58'),
(36, 15, '2025-12-17', '09:00:00', '18:00:00', 'Quản lý phòng khám', '2025-12-13 03:41:58', '2025-12-13 03:41:58'),
(37, 16, '2025-12-17', '09:00:00', '18:00:00', 'Nhân viên IT / Kỹ thuật', '2025-12-13 03:41:58', '2025-12-13 03:41:58'),
(40, 8, '2025-12-18', '08:00:00', '17:00:00', 'Nhân viên CSKH', '2025-12-13 03:41:58', '2025-12-13 03:41:58'),
(41, 9, '2025-12-18', '17:30:00', '20:30:00', 'Lễ tân', '2025-12-13 03:41:58', '2025-12-13 03:41:58'),
(42, 10, '2025-12-18', '08:00:00', '16:00:00', 'Điều dưỡng trưởng', '2025-12-13 03:41:58', '2025-12-13 03:41:58'),
(43, 11, '2025-12-18', '08:00:00', '16:30:00', 'Nữ hộ sinh', '2025-12-13 03:41:58', '2025-12-13 03:41:58'),
(44, 12, '2025-12-18', '08:00:00', '17:00:00', 'Điều dưỡng đa khoa', '2025-12-13 03:41:58', '2025-12-13 03:41:58'),
(45, 13, '2025-12-18', '08:00:00', '17:00:00', 'Thu ngân', '2025-12-13 03:41:58', '2025-12-13 03:41:58'),
(46, 14, '2025-12-18', '07:30:00', '11:30:00', 'Dược sĩ', '2025-12-13 03:41:58', '2025-12-13 03:41:58'),
(47, 15, '2025-12-18', '09:00:00', '18:00:00', 'Quản lý phòng khám', '2025-12-13 03:41:58', '2025-12-13 03:41:58'),
(48, 16, '2025-12-18', '09:00:00', '18:00:00', 'Nhân viên IT / Kỹ thuật', '2025-12-13 03:41:58', '2025-12-13 03:41:58'),
(51, 8, '2025-12-19', '08:00:00', '17:00:00', 'Nhân viên CSKH', '2025-12-13 03:41:58', '2025-12-13 03:41:58'),
(52, 9, '2025-12-19', '17:30:00', '20:30:00', 'Lễ tân', '2025-12-13 03:41:58', '2025-12-13 03:41:58'),
(53, 10, '2025-12-19', '08:00:00', '16:00:00', 'Điều dưỡng trưởng', '2025-12-13 03:41:58', '2025-12-13 03:41:58'),
(54, 11, '2025-12-19', '08:00:00', '16:30:00', 'Nữ hộ sinh', '2025-12-13 03:41:58', '2025-12-13 03:41:58'),
(55, 12, '2025-12-19', '08:00:00', '17:00:00', 'Điều dưỡng đa khoa', '2025-12-13 03:41:58', '2025-12-13 03:41:58'),
(56, 13, '2025-12-19', '08:00:00', '17:00:00', 'Thu ngân', '2025-12-13 03:41:58', '2025-12-13 03:41:58'),
(57, 14, '2025-12-19', '07:30:00', '11:30:00', 'Dược sĩ', '2025-12-13 03:41:58', '2025-12-13 03:41:58'),
(58, 15, '2025-12-19', '09:00:00', '18:00:00', 'Quản lý phòng khám', '2025-12-13 03:41:58', '2025-12-13 03:41:58'),
(59, 16, '2025-12-19', '09:00:00', '18:00:00', 'Nhân viên IT / Kỹ thuật', '2025-12-13 03:41:58', '2025-12-13 03:41:58'),
(61, 9, '2025-12-20', '17:30:00', '20:30:00', 'Lễ tân', '2025-12-13 03:41:58', '2025-12-13 03:41:58'),
(62, 10, '2025-12-20', '08:00:00', '12:00:00', 'Điều dưỡng trưởng', '2025-12-13 03:41:58', '2025-12-13 03:41:58'),
(63, 13, '2025-12-20', '08:00:00', '12:00:00', 'Thu ngân', '2025-12-13 03:41:58', '2025-12-13 03:41:58'),
(64, 14, '2025-12-20', '07:30:00', '11:30:00', 'Dược sĩ', '2025-12-13 03:41:58', '2025-12-13 03:41:58'),
(67, 8, '2025-12-22', '08:00:00', '17:00:00', 'Nhân viên CSKH', '2025-12-13 03:41:58', '2025-12-13 03:41:58'),
(68, 10, '2025-12-22', '08:00:00', '16:00:00', 'Điều dưỡng trưởng', '2025-12-13 03:41:58', '2025-12-13 03:41:58'),
(69, 11, '2025-12-22', '08:00:00', '16:30:00', 'Nữ hộ sinh', '2025-12-13 03:41:58', '2025-12-13 03:41:58'),
(70, 12, '2025-12-22', '08:00:00', '17:00:00', 'Điều dưỡng đa khoa', '2025-12-13 03:41:58', '2025-12-13 03:41:58'),
(71, 13, '2025-12-22', '08:00:00', '17:00:00', 'Thu ngân', '2025-12-13 03:41:58', '2025-12-13 03:41:58'),
(72, 14, '2025-12-22', '07:30:00', '11:30:00', 'Dược sĩ', '2025-12-13 03:41:58', '2025-12-13 03:41:58'),
(73, 15, '2025-12-22', '09:00:00', '18:00:00', 'Quản lý phòng khám', '2025-12-13 03:41:58', '2025-12-13 03:41:58'),
(74, 16, '2025-12-22', '09:00:00', '18:00:00', 'Nhân viên IT / Kỹ thuật', '2025-12-13 03:41:58', '2025-12-13 03:41:58'),
(77, 8, '2025-12-23', '08:00:00', '17:00:00', 'Nhân viên CSKH', '2025-12-13 03:41:58', '2025-12-13 03:41:58'),
(78, 9, '2025-12-23', '17:30:00', '20:30:00', 'Lễ tân', '2025-12-13 03:41:58', '2025-12-13 03:41:58'),
(79, 10, '2025-12-23', '08:00:00', '16:00:00', 'Điều dưỡng trưởng', '2025-12-13 03:41:58', '2025-12-13 03:41:58'),
(80, 11, '2025-12-23', '08:00:00', '16:30:00', 'Nữ hộ sinh', '2025-12-13 03:41:58', '2025-12-13 03:41:58'),
(81, 12, '2025-12-23', '08:00:00', '17:00:00', 'Điều dưỡng đa khoa', '2025-12-13 03:41:58', '2025-12-13 03:41:58'),
(82, 13, '2025-12-23', '08:00:00', '17:00:00', 'Thu ngân', '2025-12-13 03:41:58', '2025-12-13 03:41:58'),
(83, 14, '2025-12-23', '07:30:00', '11:30:00', 'Dược sĩ', '2025-12-13 03:41:58', '2025-12-13 03:41:58'),
(84, 15, '2025-12-23', '09:00:00', '18:00:00', 'Quản lý phòng khám', '2025-12-13 03:41:58', '2025-12-13 03:41:58'),
(85, 16, '2025-12-23', '09:00:00', '18:00:00', 'Nhân viên IT / Kỹ thuật', '2025-12-13 03:41:58', '2025-12-13 03:41:58'),
(88, 8, '2025-12-24', '08:00:00', '17:00:00', 'Nhân viên CSKH', '2025-12-13 03:41:58', '2025-12-13 03:41:58'),
(89, 9, '2025-12-24', '17:30:00', '20:30:00', 'Lễ tân', '2025-12-13 03:41:58', '2025-12-13 03:41:58'),
(90, 10, '2025-12-24', '08:00:00', '16:00:00', 'Điều dưỡng trưởng', '2025-12-13 03:41:58', '2025-12-13 03:41:58'),
(91, 11, '2025-12-24', '08:00:00', '16:30:00', 'Nữ hộ sinh', '2025-12-13 03:41:58', '2025-12-13 03:41:58'),
(92, 12, '2025-12-24', '08:00:00', '17:00:00', 'Điều dưỡng đa khoa', '2025-12-13 03:41:58', '2025-12-13 03:41:58'),
(93, 13, '2025-12-24', '08:00:00', '17:00:00', 'Thu ngân', '2025-12-13 03:41:58', '2025-12-13 03:41:58'),
(94, 14, '2025-12-24', '07:30:00', '11:30:00', 'Dược sĩ', '2025-12-13 03:41:58', '2025-12-13 03:41:58'),
(95, 15, '2025-12-24', '09:00:00', '18:00:00', 'Quản lý phòng khám', '2025-12-13 03:41:58', '2025-12-13 03:41:58'),
(96, 16, '2025-12-24', '09:00:00', '18:00:00', 'Nhân viên IT / Kỹ thuật', '2025-12-13 03:41:58', '2025-12-13 03:41:58'),
(99, 8, '2025-12-25', '08:00:00', '17:00:00', 'Nhân viên CSKH', '2025-12-13 03:41:58', '2025-12-13 03:41:58'),
(100, 9, '2025-12-25', '17:30:00', '20:30:00', 'Lễ tân', '2025-12-13 03:41:58', '2025-12-13 03:41:58'),
(101, 10, '2025-12-25', '08:00:00', '16:00:00', 'Điều dưỡng trưởng', '2025-12-13 03:41:58', '2025-12-13 03:41:58'),
(102, 11, '2025-12-25', '08:00:00', '16:30:00', 'Nữ hộ sinh', '2025-12-13 03:41:58', '2025-12-13 03:41:58'),
(103, 12, '2025-12-25', '08:00:00', '17:00:00', 'Điều dưỡng đa khoa', '2025-12-13 03:41:58', '2025-12-13 03:41:58'),
(104, 13, '2025-12-25', '08:00:00', '17:00:00', 'Thu ngân', '2025-12-13 03:41:58', '2025-12-13 03:41:58'),
(105, 14, '2025-12-25', '07:30:00', '11:30:00', 'Dược sĩ', '2025-12-13 03:41:58', '2025-12-13 03:41:58'),
(106, 15, '2025-12-25', '09:00:00', '18:00:00', 'Quản lý phòng khám', '2025-12-13 03:41:58', '2025-12-13 03:41:58'),
(107, 16, '2025-12-25', '09:00:00', '18:00:00', 'Nhân viên IT / Kỹ thuật', '2025-12-13 03:41:58', '2025-12-13 03:41:58'),
(110, 8, '2025-12-26', '08:00:00', '17:00:00', 'Nhân viên CSKH', '2025-12-13 03:41:58', '2025-12-13 03:41:58'),
(111, 9, '2025-12-26', '17:30:00', '20:30:00', 'Lễ tân', '2025-12-13 03:41:58', '2025-12-13 03:41:58'),
(112, 10, '2025-12-26', '08:00:00', '16:00:00', 'Điều dưỡng trưởng', '2025-12-13 03:41:58', '2025-12-13 03:41:58'),
(113, 11, '2025-12-26', '08:00:00', '16:30:00', 'Nữ hộ sinh', '2025-12-13 03:41:58', '2025-12-13 03:41:58'),
(114, 12, '2025-12-26', '08:00:00', '17:00:00', 'Điều dưỡng đa khoa', '2025-12-13 03:41:58', '2025-12-13 03:41:58'),
(115, 13, '2025-12-26', '08:00:00', '17:00:00', 'Thu ngân', '2025-12-13 03:41:58', '2025-12-13 03:41:58'),
(116, 14, '2025-12-26', '07:30:00', '11:30:00', 'Dược sĩ', '2025-12-13 03:41:58', '2025-12-13 03:41:58'),
(117, 15, '2025-12-26', '09:00:00', '18:00:00', 'Quản lý phòng khám', '2025-12-13 03:41:58', '2025-12-13 03:41:58'),
(118, 16, '2025-12-26', '09:00:00', '18:00:00', 'Nhân viên IT / Kỹ thuật', '2025-12-13 03:41:58', '2025-12-13 03:41:58'),
(119, 17, '2025-12-13', '07:30:00', '11:30:00', 'Trưởng lễ tân', '2025-12-13 04:18:53', '2025-12-13 04:18:53'),
(120, 17, '2025-12-15', '07:30:00', '11:30:00', 'Trưởng lễ tân', '2025-12-13 04:18:53', '2025-12-13 04:18:53'),
(121, 17, '2025-12-15', '13:30:00', '17:00:00', 'Trưởng lễ tân', '2025-12-13 04:18:53', '2025-12-13 04:18:53'),
(122, 17, '2025-12-16', '07:30:00', '11:30:00', 'Trưởng lễ tân', '2025-12-13 04:18:53', '2025-12-13 04:18:53'),
(123, 17, '2025-12-16', '13:30:00', '17:00:00', 'Trưởng lễ tân', '2025-12-13 04:18:53', '2025-12-13 04:18:53'),
(124, 17, '2025-12-17', '07:30:00', '11:30:00', 'Trưởng lễ tân', '2025-12-13 04:18:53', '2025-12-13 04:18:53'),
(125, 17, '2025-12-17', '13:30:00', '17:00:00', 'Trưởng lễ tân', '2025-12-13 04:18:53', '2025-12-13 04:18:53'),
(126, 17, '2025-12-18', '07:30:00', '11:30:00', 'Trưởng lễ tân', '2025-12-13 04:18:53', '2025-12-13 04:18:53'),
(127, 17, '2025-12-18', '13:30:00', '17:00:00', 'Trưởng lễ tân', '2025-12-13 04:18:53', '2025-12-13 04:18:53'),
(128, 17, '2025-12-19', '07:30:00', '11:30:00', 'Trưởng lễ tân', '2025-12-13 04:18:53', '2025-12-13 04:18:53'),
(129, 17, '2025-12-19', '13:30:00', '17:00:00', 'Trưởng lễ tân', '2025-12-13 04:18:53', '2025-12-13 04:18:53'),
(130, 17, '2025-12-20', '07:30:00', '11:30:00', 'Trưởng lễ tân', '2025-12-13 04:18:53', '2025-12-13 04:18:53'),
(131, 17, '2025-12-22', '07:30:00', '11:30:00', 'Trưởng lễ tân', '2025-12-13 04:18:53', '2025-12-13 04:18:53'),
(132, 17, '2025-12-22', '13:30:00', '17:00:00', 'Trưởng lễ tân', '2025-12-13 04:18:53', '2025-12-13 04:18:53'),
(133, 17, '2025-12-23', '07:30:00', '11:30:00', 'Trưởng lễ tân', '2025-12-13 04:18:54', '2025-12-13 04:18:54'),
(134, 17, '2025-12-23', '13:30:00', '17:00:00', 'Trưởng lễ tân', '2025-12-13 04:18:54', '2025-12-13 04:18:54'),
(135, 17, '2025-12-24', '07:30:00', '11:30:00', 'Trưởng lễ tân', '2025-12-13 04:18:54', '2025-12-13 04:18:54'),
(136, 17, '2025-12-24', '13:30:00', '17:00:00', 'Trưởng lễ tân', '2025-12-13 04:18:54', '2025-12-13 04:18:54'),
(137, 17, '2025-12-25', '07:30:00', '11:30:00', 'Trưởng lễ tân', '2025-12-13 04:18:54', '2025-12-13 04:18:54'),
(138, 17, '2025-12-25', '13:30:00', '17:00:00', 'Trưởng lễ tân', '2025-12-13 04:18:54', '2025-12-13 04:18:54'),
(139, 17, '2025-12-26', '07:30:00', '11:30:00', 'Trưởng lễ tân', '2025-12-13 04:18:54', '2025-12-13 04:18:54'),
(140, 17, '2025-12-26', '13:30:00', '17:00:00', 'Trưởng lễ tân', '2025-12-13 04:18:54', '2025-12-13 04:18:54'),
(141, 17, '2025-12-13', '13:30:00', '17:00:00', 'Trưởng lễ tân', '2025-12-13 07:11:12', '2025-12-13 07:11:12');

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
(15, 'Sản Khoa', 'san-khoa', 'Chuyên sâu về theo dõi thai kỳ, quản lý thai nghén nguy cơ cao (tiền sản giật, đái tháo đường thai kỳ), tư vấn dinh dưỡng và chuẩn bị cho cuộc vượt cạn an toàn.', '2025-12-12 05:38:18', '2025-12-12 05:38:18'),
(16, 'Phụ Khoa', 'phu-khoa', 'Khám và điều trị các bệnh lý viêm nhiễm phụ khoa, lộ tuyến cổ tử cung, u xơ tử cung, u nang buồng trứng, rối loạn kinh nguyệt và sức khỏe tiền mãn kinh.', '2025-12-12 05:38:18', '2025-12-12 05:38:18'),
(17, 'Hiếm muộn & Vô sinh', 'hiem-muon-vo-sinh', 'Tư vấn sức khỏe sinh sản cặp đôi, khám tìm nguyên nhân chậm con. Thực hiện các kỹ thuật hỗ trợ sinh sản như bơm tinh trùng (IUI) và tư vấn thụ tinh ống nghiệm (IVF).', '2025-12-12 05:38:18', '2025-12-12 05:38:18'),
(18, 'Siêu âm & Chẩn đoán hình ảnh', 'sieu-am-chan-doan-hinh-anh', 'Thực hiện các kỹ thuật chẩn đoán hình ảnh hiện đại: Siêu âm thai 4D/5D hình thái học, siêu âm Doppler màu tim thai, siêu âm tuyến vú và đầu dò âm đạo.', '2025-12-12 05:38:18', '2025-12-12 05:38:18'),
(19, 'Sàng lọc trước sinh', 'sang-loc-truoc-sinh', 'Chuyên khoa Di truyền học. Thực hiện và tư vấn các xét nghiệm NIPT, Double Test, Triple Test, chọc ối để phát hiện sớm các dị tật bẩm sinh ở thai nhi.', '2025-12-12 05:38:18', '2025-12-12 05:38:18'),
(20, 'Kế hoạch hóa gia đình', 'ke-hoach-hoa-gia-dinh', 'Tư vấn và thực hiện các biện pháp tránh thai an toàn, hiện đại: Cấy que tránh thai Implanon, đặt vòng nội tiết Mirena, tiêm thuốc tránh thai.', '2025-12-12 05:38:18', '2025-12-12 05:38:18'),
(21, 'Sàn chậu & Thẩm mỹ nữ', 'san-chau-tham-my-nu', 'Điều trị các bệnh lý sa tạng chậu, són tiểu sau sinh. Thực hiện các dịch vụ thẩm mỹ, trẻ hóa vùng kín và phục hồi chức năng sàn chậu cho phụ nữ sau sinh.', '2025-12-12 05:38:18', '2025-12-12 05:38:18'),
(22, 'Xét nghiệm', 'xet-nghiem', 'Trung tâm xét nghiệm thực hiện các chỉ định cận lâm sàng: Huyết học, Sinh hóa, Miễn dịch, Vi sinh và Nội tiết tố phục vụ cho chẩn đoán của bác sĩ lâm sàng.', '2025-12-12 05:38:18', '2025-12-12 05:38:18');

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
(227, 15, 126, NULL, NULL),
(228, 16, 127, NULL, NULL),
(229, 17, 128, NULL, NULL),
(230, 20, 128, NULL, NULL),
(231, 17, 129, NULL, NULL),
(232, 15, 130, NULL, NULL),
(233, 18, 130, NULL, NULL),
(234, 15, 131, NULL, NULL),
(235, 16, 131, NULL, NULL),
(236, 18, 131, NULL, NULL),
(237, 17, 131, NULL, NULL),
(238, 16, 132, NULL, NULL),
(239, 18, 132, NULL, NULL),
(240, 19, 133, NULL, NULL),
(241, 15, 133, NULL, NULL),
(242, 16, 134, NULL, NULL),
(243, 19, 134, NULL, NULL),
(244, 15, 135, NULL, NULL),
(245, 17, 135, NULL, NULL),
(246, 22, 135, NULL, NULL),
(247, 20, 136, NULL, NULL),
(248, 17, 137, NULL, NULL),
(249, 16, 138, NULL, NULL),
(250, 22, 138, NULL, NULL),
(251, 16, 139, NULL, NULL),
(252, 18, 139, NULL, NULL),
(253, 22, 139, NULL, NULL),
(254, 17, 140, NULL, NULL),
(255, 22, 140, NULL, NULL),
(256, 17, 141, NULL, NULL),
(257, 15, 141, NULL, NULL),
(258, 22, 141, NULL, NULL),
(259, 17, 142, NULL, NULL),
(260, 22, 142, NULL, NULL),
(261, 16, 143, NULL, NULL),
(262, 22, 143, NULL, NULL),
(263, 15, 144, NULL, NULL),
(264, 22, 144, NULL, NULL),
(265, 21, 145, NULL, NULL),
(266, 21, 146, NULL, NULL);

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
(3, 'CHAOBANMOI', 'Giảm 10% cho khách mới', 'Giảm 10% cho khách mới', 'phan_tram', 10.00, NULL, 0.00, '2025-11-12', '2026-06-12', NULL, 0, 1, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(4, 'KHAMTHAI', 'Giảm 50k cho gói khám thai', 'Giảm 50k cho gói khám thai', 'tien_mat', 50000.00, NULL, 200000.00, '2025-11-12', '2026-06-12', NULL, 0, 1, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(5, 'TRIAN', 'Ưu đãi cho khách cũ', 'Ưu đãi cho khách cũ', 'phan_tram', 5.00, NULL, 0.00, '2025-11-12', '2026-06-12', NULL, 0, 1, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(6, 'SINHNHAT', 'Quà sinh nhật', 'Quà sinh nhật', 'tien_mat', 100000.00, NULL, 0.00, '2024-12-12', '2027-12-12', NULL, 0, 1, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(7, 'TRONGGIO', 'Giảm 10% giờ vàng', 'Giảm 10% giờ vàng', 'phan_tram', 10.00, NULL, 0.00, '2025-11-12', '2026-03-12', NULL, 0, 1, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(8, 'DICHVU50', 'Giảm 50% dịch vụ chọn lọc', 'Giảm 50% dịch vụ chọn lọc', 'phan_tram', 50.00, NULL, 500000.00, '2025-11-12', '2026-06-12', NULL, 0, 1, '2025-12-12 07:50:15', '2025-12-12 07:50:15');

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
(1, 'Cẩm nang Thai kỳ', 'cam-nang-thai-ky', NULL, NULL, NULL, '2025-12-12 08:30:17', '2025-12-12 08:30:17'),
(2, 'Vô sinh - Hiếm muộn', 'vo-sinh-hiem-muon', NULL, NULL, NULL, '2025-12-12 08:30:17', '2025-12-12 08:30:17'),
(3, 'Tin tức Y khoa', 'tin-tuc-y-khoa', NULL, NULL, NULL, '2025-12-12 08:30:17', '2025-12-12 08:30:17'),
(4, 'Hoạt động Phòng khám', 'hoat-dong-phong-kham', NULL, NULL, NULL, '2025-12-12 08:30:17', '2025-12-12 08:30:17');

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
(126, 'Khám Thai định kỳ & Tư vấn dinh dưỡng', 'Khám lâm sàng, đo bề cao tử cung, vòng bụng, nghe tim thai bằng Doppler. Tư vấn dinh dưỡng và lịch tiêm phòng cho mẹ bầu.', 200000.00, 15, '2025-12-12 06:41:14', '2025-12-12 06:41:14'),
(127, 'Khám Phụ khoa tổng quát', 'Kiểm tra cơ quan sinh dục ngoài và trong, phát hiện sớm các bệnh lý viêm nhiễm, u xơ, u nang. Bao gồm phí dụng cụ dùng một lần.', 250000.00, 20, '2025-12-12 06:41:14', '2025-12-12 06:41:14'),
(128, 'Khám & Tư vấn sức khỏe Tiền hôn nhân', 'Gói khám sức khỏe sinh sản tổng quát cho cả vợ và chồng trước khi cưới. Tư vấn di truyền và chuẩn bị mang thai an toàn.', 800000.00, 30, '2025-12-12 06:41:14', '2025-12-12 06:41:14'),
(129, 'Tư vấn chuyên sâu Vô sinh - Hiếm muộn', 'Bác sĩ chuyên khoa xem hồ sơ cũ, tư vấn phác đồ điều trị và chỉ định các xét nghiệm chuyên sâu cần thiết cho các cặp vợ chồng mong con.', 500000.00, 45, '2025-12-12 06:41:14', '2025-12-12 06:41:14'),
(130, 'Siêu âm thai 5D (Hình thái học)', 'Công nghệ dựng hình 5D sắc nét, khảo sát dị tật thai nhi toàn diện (mặt, tim, chi...). Tặng kèm file video và hình ảnh bé qua Zalo/Email.', 500000.00, 30, '2025-12-12 06:41:14', '2025-12-12 06:41:14'),
(131, 'Siêu âm đầu dò âm đạo (Transvaginal)', 'Kỹ thuật siêu âm qua đường âm đạo giúp quan sát tử cung, buồng trứng rõ nét nhất. Phát hiện thai sớm, u nang buồng trứng, đa nang.', 300000.00, 15, '2025-12-12 06:41:14', '2025-12-12 06:41:14'),
(132, 'Siêu âm Tuyến vú 2 bên', 'Tầm soát nang vú, nhân xơ tuyến vú lành tính/ác tính bằng sóng siêu âm. Không đau, không xâm lấn.', 300000.00, 15, '2025-12-12 06:41:14', '2025-12-12 06:41:14'),
(133, 'Sàng lọc trước sinh không xâm lấn (NIPT - 23 cặp NST)', 'Sàng lọc toàn bộ 23 cặp nhiễm sắc thể từ máu mẹ, phát hiện các bất thường vi mất đoạn với độ chính xác >99%. An toàn tuyệt đối cho thai nhi.', 6500000.00, 10, '2025-12-12 06:41:14', '2025-12-12 06:41:14'),
(134, 'Xét nghiệm tế bào cổ tử cung (Pap Smear - ThinPrep)', 'Tầm soát ung thư cổ tử cung phương pháp mới. Phát hiện sớm các biến đổi tế bào tiền ung thư. Khuyên dùng định kỳ hàng năm.', 400000.00, 10, '2025-12-12 06:41:14', '2025-12-12 06:41:14'),
(135, 'Định lượng Beta-hCG (Máu)', 'Xét nghiệm máu chẩn đoán thai sớm chính xác nhất (có thể phát hiện trước khi chậm kinh). Theo dõi sự phát triển của thai giai đoạn đầu.', 150000.00, 5, '2025-12-12 06:41:14', '2025-12-12 06:41:14'),
(136, 'Cấy que tránh thai Implanon (3 năm)', 'Que cấy tránh thai nội tiết (xuất xứ Mỹ/Châu Âu), hiệu quả ngừa thai lên đến 3 năm. Thủ thuật nhanh, không đau (có gây tê tại chỗ).', 3200000.00, 20, '2025-12-12 06:41:14', '2025-12-12 06:41:14'),
(137, 'Bơm tinh trùng vào buồng tử cung (IUI)', 'Kỹ thuật lọc rửa và bơm tinh trùng vào buồng tử cung. Hỗ trợ sinh sản cho các cặp vợ chồng hiếm muộn nhẹ, tinh trùng yếu.', 3500000.00, 60, '2025-12-12 06:41:14', '2025-12-12 06:41:14'),
(138, 'Gói Tầm soát Ung thư Cổ tử cung (Cơ bản)', 'Bao gồm: Khám phụ khoa, Soi cổ tử cung kỹ thuật số, Xét nghiệm Pap Smear và Test nhanh dịch âm đạo.', 1200000.00, 30, '2025-12-12 06:41:14', '2025-12-12 06:41:14'),
(139, 'Gói Khám Phụ khoa Tổng quát (VIP)', 'Khám lâm sàng, Siêu âm đầu dò, Soi tươi dịch âm đạo, Tầm soát ung thư (HPV + Pap Smear), Siêu âm tuyến vú.', 2500000.00, 45, '2025-12-12 06:41:14', '2025-12-12 06:41:14'),
(140, 'Xét nghiệm dự trữ buồng trứng (AMH)', 'Chỉ số quan trọng nhất để đánh giá khả năng sinh sản của phụ nữ. Bắt buộc thực hiện trước khi làm IVF/IUI.', 800000.00, 10, '2025-12-12 06:41:14', '2025-12-12 06:41:14'),
(141, 'Bộ xét nghiệm Nội tiết tố nữ (6 chỉ số)', 'Kiểm tra 6 chỉ số: FSH, LH, Estradiol, Prolactin, Progesterone, Testosterone. Đánh giá rối loạn kinh nguyệt và rụng trứng.', 1500000.00, 15, '2025-12-12 06:41:14', '2025-12-12 06:41:14'),
(142, 'Tinh dịch đồ', 'Phân tích số lượng, khả năng di động và hình dạng của tinh trùng. Bước đầu tiên khám vô sinh nam.', 350000.00, 60, '2025-12-12 06:41:14', '2025-12-12 06:41:14'),
(143, 'Xét nghiệm Bệnh lây truyền (STD Panel)', 'Xét nghiệm PCR đa mồi phát hiện 9 tác nhân lây qua đường tình dục: Lậu, Giang mai, Chlamydia, Nấm, Trichomonas...', 1800000.00, 20, '2025-12-12 06:41:14', '2025-12-12 06:41:14'),
(144, 'Xét nghiệm Rubella (IgM/IgG)', 'Tầm soát kháng thể Rubella cho phụ nữ chuẩn bị mang thai hoặc đang mang thai 3 tháng đầu.', 300000.00, 10, '2025-12-12 06:41:14', '2025-12-12 06:41:14'),
(145, 'Tập vật lý trị liệu sàn chậu (1 buổi)', 'Bài tập chuyên sâu với máy tập Biofeedback giúp co hồi cơ sàn chậu, chống sa tử cung và són tiểu sau sinh.', 400000.00, 45, '2025-12-12 06:41:14', '2025-12-12 06:41:14'),
(146, 'Làm hồng & Trẻ hóa vùng kín (Laser)', 'Sử dụng công nghệ Laser CO2 Fractional giúp se khít, làm hồng và trẻ hóa vùng kín không phẫu thuật.', 5000000.00, 60, '2025-12-12 06:41:14', '2025-12-12 06:41:14'),
(149, 'Full Combo', NULL, 1800000.00, 30, '2025-12-13 11:35:10', '2025-12-13 11:35:10');

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
(1, 1, 148, 108, 3, 'Test', '2025-12-13 07:58:06', '2025-12-13 07:58:06');

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
(1, 1, 91, 10, '2 lần/ngày', 'Uống trước khi ăn', '2025-12-13 07:58:06', '2025-12-13 07:58:06');

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
-- Cấu trúc bảng cho bảng `family_members`
--

CREATE TABLE `family_members` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `ho_ten` varchar(255) NOT NULL,
  `quan_he` enum('vo','chong','con','cha','me','anh','chi','em','ong','ba','chau','khac') NOT NULL,
  `ngay_sinh` date NOT NULL,
  `gioi_tinh` enum('Nam','Nữ','Khác') NOT NULL,
  `so_dien_thoai` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `dia_chi` text DEFAULT NULL,
  `nhom_mau` varchar(255) DEFAULT NULL,
  `chieu_cao` decimal(5,2) DEFAULT NULL COMMENT 'cm',
  `can_nang` decimal(5,2) DEFAULT NULL COMMENT 'kg',
  `tien_su_benh` text DEFAULT NULL,
  `bhyt_ma_so` varchar(255) DEFAULT NULL,
  `bhyt_ngay_het_han` date DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
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
  `updated_at` timestamp NULL DEFAULT NULL,
  `coupon_id` bigint(20) UNSIGNED DEFAULT NULL,
  `giam_gia` decimal(12,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `hoa_dons`
--

INSERT INTO `hoa_dons` (`id`, `ma_hoa_don`, `lich_hen_id`, `user_id`, `tong_tien`, `so_tien_da_thanh_toan`, `so_tien_con_lai`, `so_tien_da_hoan`, `trang_thai`, `status`, `phuong_thuc`, `ghi_chu`, `created_at`, `updated_at`, `coupon_id`, `giam_gia`) VALUES
(3, 'INV-20251213-00001', 3, 148, 2250000.00, 2250000.00, 0.00, 0.00, 'Đã thanh toán', 'paid', 'Tiền mặt', '', '2025-12-13 04:53:41', '2025-12-13 08:02:48', 3, 250000.00);

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
(1, 'default', '{\"uuid\":\"ddf56bb4-f5cf-4e1b-8845-c8667ac26474\",\"displayName\":\"App\\\\Mail\\\\HoaDonDaThanhToan\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":15:{s:8:\\\"mailable\\\";O:26:\\\"App\\\\Mail\\\\HoaDonDaThanhToan\\\":3:{s:6:\\\"hoaDon\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:17:\\\"App\\\\Models\\\\HoaDon\\\";s:2:\\\"id\\\";i:3;s:9:\\\"relations\\\";a:2:{i:0;s:7:\\\"lichHen\\\";i:1;s:4:\\\"user\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:18:\\\"tn822798@gmail.com\\\";}}s:6:\\\"mailer\\\";s:4:\\\"smtp\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:3:\\\"job\\\";N;}\"}}', 0, NULL, 1765612924, 1765612924);

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

INSERT INTO `lich_hens` (`id`, `user_id`, `bac_si_id`, `dich_vu_id`, `ten_benh_nhan`, `sdt_benh_nhan`, `email_benh_nhan`, `ngay_sinh_benh_nhan`, `tong_tien`, `ngay_hen`, `thoi_gian_hen`, `ghi_chu`, `trang_thai`, `checked_in_at`, `thoi_gian_bat_dau_kham`, `completed_at`, `created_at`, `updated_at`, `payment_status`, `payment_method`, `paid_at`, `cancelled_by`, `cancelled_at`) VALUES
(3, 148, 108, 139, NULL, NULL, NULL, NULL, 2250000.00, '2025-12-13', '14:30:00', 'test', 'Hoàn thành', '2025-12-13 07:38:09', '2025-12-13 07:40:21', '2025-12-13 07:59:47', '2025-12-13 04:53:41', '2025-12-13 08:02:48', 'Đã thanh toán', 'Tiền mặt', '2025-12-13 08:02:48', NULL, NULL);

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
(4, 105, 17, 1, '07:30:00', '11:30:00', '2025-12-13 02:39:30', '2025-12-13 03:14:47'),
(5, 105, 17, 1, '13:30:00', '17:00:00', '2025-12-13 02:39:30', '2025-12-13 03:14:47'),
(6, 105, 17, 2, '07:30:00', '11:30:00', '2025-12-13 02:39:30', '2025-12-13 03:14:47'),
(7, 105, 17, 2, '13:30:00', '17:00:00', '2025-12-13 02:39:30', '2025-12-13 03:14:47'),
(8, 105, 17, 3, '07:30:00', '11:30:00', '2025-12-13 02:39:30', '2025-12-13 03:14:47'),
(9, 105, 17, 3, '13:30:00', '17:00:00', '2025-12-13 02:39:30', '2025-12-13 03:14:47'),
(10, 105, 17, 4, '07:30:00', '11:30:00', '2025-12-13 02:39:30', '2025-12-13 03:14:47'),
(11, 105, 17, 4, '13:30:00', '17:00:00', '2025-12-13 02:39:30', '2025-12-13 03:14:47'),
(12, 105, 17, 5, '07:30:00', '11:30:00', '2025-12-13 02:39:30', '2025-12-13 03:14:47'),
(13, 105, 17, 5, '13:30:00', '17:00:00', '2025-12-13 02:39:30', '2025-12-13 03:14:47'),
(14, 105, 17, 6, '07:30:00', '11:30:00', '2025-12-13 02:39:30', '2025-12-13 03:14:47'),
(15, 107, 20, 1, '07:30:00', '11:30:00', '2025-12-13 02:39:30', '2025-12-13 03:14:47'),
(16, 107, 20, 1, '13:30:00', '17:00:00', '2025-12-13 02:39:30', '2025-12-13 03:14:47'),
(17, 107, 20, 2, '07:30:00', '11:30:00', '2025-12-13 02:39:30', '2025-12-13 03:14:47'),
(18, 107, 20, 2, '13:30:00', '17:00:00', '2025-12-13 02:39:30', '2025-12-13 03:14:47'),
(19, 107, 20, 3, '07:30:00', '11:30:00', '2025-12-13 02:39:30', '2025-12-13 03:14:47'),
(20, 107, 20, 3, '13:30:00', '17:00:00', '2025-12-13 02:39:30', '2025-12-13 03:14:47'),
(21, 107, 20, 4, '07:30:00', '11:30:00', '2025-12-13 02:39:30', '2025-12-13 03:14:47'),
(22, 107, 20, 4, '13:30:00', '17:00:00', '2025-12-13 02:39:30', '2025-12-13 03:14:47'),
(23, 107, 20, 5, '07:30:00', '11:30:00', '2025-12-13 02:39:30', '2025-12-13 03:14:47'),
(24, 107, 20, 5, '13:30:00', '17:00:00', '2025-12-13 02:39:30', '2025-12-13 03:14:47'),
(25, 107, 20, 6, '07:30:00', '11:30:00', '2025-12-13 02:39:30', '2025-12-13 03:14:47'),
(26, 109, 23, 1, '07:30:00', '11:30:00', '2025-12-13 02:39:30', '2025-12-13 03:14:47'),
(27, 109, 23, 1, '13:30:00', '17:00:00', '2025-12-13 02:39:30', '2025-12-13 03:14:47'),
(28, 109, 23, 2, '07:30:00', '11:30:00', '2025-12-13 02:39:30', '2025-12-13 03:14:47'),
(29, 109, 23, 2, '13:30:00', '17:00:00', '2025-12-13 02:39:30', '2025-12-13 03:14:47'),
(30, 109, 23, 3, '07:30:00', '11:30:00', '2025-12-13 02:39:30', '2025-12-13 03:14:47'),
(31, 109, 23, 3, '13:30:00', '17:00:00', '2025-12-13 02:39:30', '2025-12-13 03:14:47'),
(32, 109, 23, 4, '07:30:00', '11:30:00', '2025-12-13 02:39:30', '2025-12-13 03:14:47'),
(33, 109, 23, 4, '13:30:00', '17:00:00', '2025-12-13 02:39:30', '2025-12-13 03:14:47'),
(34, 109, 23, 5, '07:30:00', '11:30:00', '2025-12-13 02:39:30', '2025-12-13 03:14:47'),
(35, 109, 23, 5, '13:30:00', '17:00:00', '2025-12-13 02:39:30', '2025-12-13 03:14:47'),
(36, 109, 23, 6, '07:30:00', '11:30:00', '2025-12-13 02:39:30', '2025-12-13 03:14:47'),
(37, 115, 25, 1, '07:30:00', '11:30:00', '2025-12-13 02:39:30', '2025-12-13 03:14:47'),
(38, 115, 25, 1, '13:30:00', '17:00:00', '2025-12-13 02:39:30', '2025-12-13 03:14:47'),
(39, 115, 25, 2, '07:30:00', '11:30:00', '2025-12-13 02:39:30', '2025-12-13 03:14:47'),
(40, 115, 25, 2, '13:30:00', '17:00:00', '2025-12-13 02:39:30', '2025-12-13 03:14:47'),
(41, 115, 25, 3, '07:30:00', '11:30:00', '2025-12-13 02:39:30', '2025-12-13 03:14:47'),
(42, 115, 25, 3, '13:30:00', '17:00:00', '2025-12-13 02:39:30', '2025-12-13 03:14:47'),
(43, 115, 25, 4, '07:30:00', '11:30:00', '2025-12-13 02:39:30', '2025-12-13 03:14:47'),
(44, 115, 25, 4, '13:30:00', '17:00:00', '2025-12-13 02:39:30', '2025-12-13 03:14:47'),
(45, 115, 25, 5, '07:30:00', '11:30:00', '2025-12-13 02:39:30', '2025-12-13 03:14:47'),
(46, 115, 25, 5, '13:30:00', '17:00:00', '2025-12-13 02:39:30', '2025-12-13 03:14:47'),
(47, 115, 25, 6, '07:30:00', '11:30:00', '2025-12-13 02:39:30', '2025-12-13 03:14:47'),
(48, 106, 18, 1, '07:30:00', '11:30:00', '2025-12-13 02:39:30', '2025-12-13 03:14:47'),
(49, 106, 18, 1, '13:30:00', '17:00:00', '2025-12-13 02:39:30', '2025-12-13 03:14:47'),
(50, 106, 18, 1, '17:30:00', '20:30:00', '2025-12-13 02:39:30', '2025-12-13 03:14:47'),
(51, 106, 18, 3, '07:30:00', '11:30:00', '2025-12-13 02:39:30', '2025-12-13 03:14:47'),
(52, 106, 18, 3, '13:30:00', '17:00:00', '2025-12-13 02:39:30', '2025-12-13 03:14:47'),
(53, 106, 18, 3, '17:30:00', '20:30:00', '2025-12-13 02:39:30', '2025-12-13 03:14:47'),
(54, 106, 18, 5, '07:30:00', '11:30:00', '2025-12-13 02:39:30', '2025-12-13 03:14:47'),
(55, 106, 18, 5, '13:30:00', '17:00:00', '2025-12-13 02:39:30', '2025-12-13 03:14:47'),
(56, 106, 18, 5, '17:30:00', '20:30:00', '2025-12-13 02:39:30', '2025-12-13 03:14:47'),
(57, 106, 18, 2, '07:30:00', '11:30:00', '2025-12-13 02:39:30', '2025-12-13 03:14:47'),
(58, 106, 18, 2, '13:30:00', '17:00:00', '2025-12-13 02:39:30', '2025-12-13 03:14:47'),
(59, 106, 18, 4, '07:30:00', '11:30:00', '2025-12-13 02:39:30', '2025-12-13 03:14:47'),
(60, 106, 18, 4, '13:30:00', '17:00:00', '2025-12-13 02:39:30', '2025-12-13 03:14:47'),
(61, 106, 18, 6, '07:30:00', '11:30:00', '2025-12-13 02:39:30', '2025-12-13 03:14:47'),
(62, 106, 18, 6, '13:30:00', '17:00:00', '2025-12-13 02:39:30', '2025-12-13 03:14:47'),
(63, 108, 20, 1, '07:30:00', '11:30:00', '2025-12-13 02:39:30', '2025-12-13 03:14:47'),
(64, 108, 20, 1, '13:30:00', '17:00:00', '2025-12-13 02:39:30', '2025-12-13 03:14:47'),
(65, 108, 20, 1, '17:30:00', '20:30:00', '2025-12-13 02:39:30', '2025-12-13 03:14:47'),
(66, 108, 20, 3, '07:30:00', '11:30:00', '2025-12-13 02:39:30', '2025-12-13 03:14:47'),
(67, 108, 20, 3, '13:30:00', '17:00:00', '2025-12-13 02:39:30', '2025-12-13 03:14:47'),
(68, 108, 20, 3, '17:30:00', '20:30:00', '2025-12-13 02:39:30', '2025-12-13 03:14:47'),
(69, 108, 20, 5, '07:30:00', '11:30:00', '2025-12-13 02:39:30', '2025-12-13 03:14:47'),
(70, 108, 20, 5, '13:30:00', '17:00:00', '2025-12-13 02:39:30', '2025-12-13 03:14:47'),
(71, 108, 20, 5, '17:30:00', '20:30:00', '2025-12-13 02:39:30', '2025-12-13 03:14:47'),
(72, 108, 20, 2, '07:30:00', '11:30:00', '2025-12-13 02:39:30', '2025-12-13 03:14:47'),
(73, 108, 20, 2, '13:30:00', '17:00:00', '2025-12-13 02:39:30', '2025-12-13 03:14:47'),
(74, 108, 20, 4, '07:30:00', '11:30:00', '2025-12-13 02:39:30', '2025-12-13 03:14:47'),
(75, 108, 20, 4, '13:30:00', '17:00:00', '2025-12-13 02:39:30', '2025-12-13 03:14:47'),
(76, 108, 20, 6, '07:30:00', '11:30:00', '2025-12-13 02:39:30', '2025-12-13 03:14:47'),
(77, 108, 20, 6, '13:30:00', '17:00:00', '2025-12-13 02:39:30', '2025-12-13 03:14:47'),
(78, 110, 24, 1, '07:30:00', '11:30:00', '2025-12-13 02:39:30', '2025-12-13 03:14:47'),
(79, 110, 24, 1, '13:30:00', '17:00:00', '2025-12-13 02:39:30', '2025-12-13 03:14:47'),
(80, 110, 24, 1, '17:30:00', '20:30:00', '2025-12-13 02:39:30', '2025-12-13 03:14:47'),
(81, 110, 24, 3, '07:30:00', '11:30:00', '2025-12-13 02:39:30', '2025-12-13 03:14:47'),
(82, 110, 24, 3, '13:30:00', '17:00:00', '2025-12-13 02:39:30', '2025-12-13 03:14:47'),
(83, 110, 24, 3, '17:30:00', '20:30:00', '2025-12-13 02:39:30', '2025-12-13 03:14:47'),
(84, 110, 24, 5, '07:30:00', '11:30:00', '2025-12-13 02:39:30', '2025-12-13 03:14:47'),
(85, 110, 24, 5, '13:30:00', '17:00:00', '2025-12-13 02:39:30', '2025-12-13 03:14:47'),
(86, 110, 24, 5, '17:30:00', '20:30:00', '2025-12-13 02:39:30', '2025-12-13 03:14:47'),
(87, 110, 24, 2, '07:30:00', '11:30:00', '2025-12-13 02:39:30', '2025-12-13 03:14:47'),
(88, 110, 24, 2, '13:30:00', '17:00:00', '2025-12-13 02:39:30', '2025-12-13 03:14:47'),
(89, 110, 24, 4, '07:30:00', '11:30:00', '2025-12-13 02:39:30', '2025-12-13 03:14:47'),
(90, 110, 24, 4, '13:30:00', '17:00:00', '2025-12-13 02:39:30', '2025-12-13 03:14:47'),
(91, 110, 24, 6, '07:30:00', '11:30:00', '2025-12-13 02:39:30', '2025-12-13 03:14:47'),
(92, 110, 24, 6, '13:30:00', '17:00:00', '2025-12-13 02:39:30', '2025-12-13 03:14:47'),
(93, 111, 19, 1, '07:30:00', '11:30:00', '2025-12-13 02:39:30', '2025-12-13 03:14:47'),
(94, 111, 19, 1, '13:30:00', '17:00:00', '2025-12-13 02:39:30', '2025-12-13 03:14:47'),
(95, 111, 19, 1, '17:30:00', '20:30:00', '2025-12-13 02:39:30', '2025-12-13 03:14:47'),
(96, 111, 19, 3, '07:30:00', '11:30:00', '2025-12-13 02:39:30', '2025-12-13 03:14:47'),
(97, 111, 19, 3, '13:30:00', '17:00:00', '2025-12-13 02:39:30', '2025-12-13 03:14:47'),
(98, 111, 19, 3, '17:30:00', '20:30:00', '2025-12-13 02:39:30', '2025-12-13 03:14:47'),
(99, 111, 19, 5, '07:30:00', '11:30:00', '2025-12-13 02:39:30', '2025-12-13 03:14:47'),
(100, 111, 19, 5, '13:30:00', '17:00:00', '2025-12-13 02:39:30', '2025-12-13 03:14:47'),
(101, 111, 19, 5, '17:30:00', '20:30:00', '2025-12-13 02:39:30', '2025-12-13 03:14:47'),
(102, 111, 19, 2, '07:30:00', '11:30:00', '2025-12-13 02:39:30', '2025-12-13 03:14:47'),
(103, 111, 19, 2, '13:30:00', '17:00:00', '2025-12-13 02:39:30', '2025-12-13 03:14:47'),
(104, 111, 19, 4, '07:30:00', '11:30:00', '2025-12-13 02:39:30', '2025-12-13 03:14:47'),
(105, 111, 19, 4, '13:30:00', '17:00:00', '2025-12-13 02:39:30', '2025-12-13 03:14:47'),
(106, 111, 19, 6, '07:30:00', '11:30:00', '2025-12-13 02:39:30', '2025-12-13 03:14:47'),
(107, 111, 19, 6, '13:30:00', '17:00:00', '2025-12-13 02:39:30', '2025-12-13 03:14:47'),
(108, 114, 18, 2, '07:30:00', '11:30:00', '2025-12-13 02:39:30', '2025-12-13 03:14:47'),
(109, 114, 18, 2, '13:30:00', '17:00:00', '2025-12-13 02:39:30', '2025-12-13 03:14:47'),
(110, 114, 18, 2, '17:30:00', '20:30:00', '2025-12-13 02:39:30', '2025-12-13 03:14:47'),
(111, 114, 18, 4, '07:30:00', '11:30:00', '2025-12-13 02:39:30', '2025-12-13 03:14:47'),
(112, 114, 18, 4, '13:30:00', '17:00:00', '2025-12-13 02:39:30', '2025-12-13 03:14:47'),
(113, 114, 18, 4, '17:30:00', '20:30:00', '2025-12-13 02:39:30', '2025-12-13 03:14:47'),
(114, 114, 18, 6, '07:30:00', '11:30:00', '2025-12-13 02:39:30', '2025-12-13 03:14:47'),
(115, 114, 18, 6, '13:30:00', '17:00:00', '2025-12-13 02:39:30', '2025-12-13 03:14:47'),
(116, 114, 18, 6, '17:30:00', '20:30:00', '2025-12-13 02:39:30', '2025-12-13 03:14:47'),
(117, 114, 18, 1, '13:30:00', '17:00:00', '2025-12-13 02:39:30', '2025-12-13 03:14:47'),
(118, 114, 18, 1, '17:30:00', '20:30:00', '2025-12-13 02:39:30', '2025-12-13 03:14:47'),
(119, 114, 18, 3, '13:30:00', '17:00:00', '2025-12-13 02:39:30', '2025-12-13 03:14:47'),
(120, 114, 18, 3, '17:30:00', '20:30:00', '2025-12-13 02:39:30', '2025-12-13 03:14:47'),
(121, 114, 18, 5, '13:30:00', '17:00:00', '2025-12-13 02:39:30', '2025-12-13 03:14:47'),
(122, 114, 18, 5, '17:30:00', '20:30:00', '2025-12-13 02:39:30', '2025-12-13 03:14:47'),
(123, 114, 18, 0, '07:30:00', '11:30:00', '2025-12-13 02:39:30', '2025-12-13 03:14:47'),
(124, 113, 21, 2, '07:30:00', '11:30:00', '2025-12-13 02:39:30', '2025-12-13 03:14:47'),
(125, 113, 21, 2, '13:30:00', '17:00:00', '2025-12-13 02:39:30', '2025-12-13 03:14:47'),
(126, 113, 21, 2, '17:30:00', '20:30:00', '2025-12-13 02:39:30', '2025-12-13 03:14:47'),
(127, 113, 21, 4, '07:30:00', '11:30:00', '2025-12-13 02:39:30', '2025-12-13 03:14:47'),
(128, 113, 21, 4, '13:30:00', '17:00:00', '2025-12-13 02:39:30', '2025-12-13 03:14:47'),
(129, 113, 21, 4, '17:30:00', '20:30:00', '2025-12-13 02:39:30', '2025-12-13 03:14:47'),
(130, 113, 21, 6, '07:30:00', '11:30:00', '2025-12-13 02:39:30', '2025-12-13 03:14:47'),
(131, 113, 21, 6, '13:30:00', '17:00:00', '2025-12-13 02:39:30', '2025-12-13 03:14:47'),
(132, 113, 21, 6, '17:30:00', '20:30:00', '2025-12-13 02:39:30', '2025-12-13 03:14:47'),
(133, 113, 21, 1, '13:30:00', '17:00:00', '2025-12-13 02:39:30', '2025-12-13 03:14:47'),
(134, 113, 21, 1, '17:30:00', '20:30:00', '2025-12-13 02:39:30', '2025-12-13 03:14:47'),
(135, 113, 21, 3, '13:30:00', '17:00:00', '2025-12-13 02:39:30', '2025-12-13 03:14:47'),
(136, 113, 21, 3, '17:30:00', '20:30:00', '2025-12-13 02:39:30', '2025-12-13 03:14:47'),
(137, 113, 21, 5, '13:30:00', '17:00:00', '2025-12-13 02:39:30', '2025-12-13 03:14:47'),
(138, 113, 21, 5, '17:30:00', '20:30:00', '2025-12-13 02:39:30', '2025-12-13 03:14:47'),
(139, 113, 21, 0, '07:30:00', '11:30:00', '2025-12-13 02:39:30', '2025-12-13 03:14:47'),
(140, 112, 26, 2, '07:30:00', '11:30:00', '2025-12-13 02:39:30', '2025-12-13 03:14:47'),
(141, 112, 26, 2, '13:30:00', '17:00:00', '2025-12-13 02:39:30', '2025-12-13 03:14:47'),
(142, 112, 26, 2, '17:30:00', '20:30:00', '2025-12-13 02:39:30', '2025-12-13 03:14:47'),
(143, 112, 26, 4, '07:30:00', '11:30:00', '2025-12-13 02:39:30', '2025-12-13 03:14:47'),
(144, 112, 26, 4, '13:30:00', '17:00:00', '2025-12-13 02:39:30', '2025-12-13 03:14:47'),
(145, 112, 26, 4, '17:30:00', '20:30:00', '2025-12-13 02:39:30', '2025-12-13 03:14:47'),
(146, 112, 26, 6, '07:30:00', '11:30:00', '2025-12-13 02:39:30', '2025-12-13 03:14:47'),
(147, 112, 26, 6, '13:30:00', '17:00:00', '2025-12-13 02:39:30', '2025-12-13 03:14:47'),
(148, 112, 26, 6, '17:30:00', '20:30:00', '2025-12-13 02:39:30', '2025-12-13 03:14:47'),
(149, 112, 26, 1, '13:30:00', '17:00:00', '2025-12-13 02:39:30', '2025-12-13 03:14:47'),
(150, 112, 26, 1, '17:30:00', '20:30:00', '2025-12-13 02:39:30', '2025-12-13 03:14:47'),
(151, 112, 26, 3, '13:30:00', '17:00:00', '2025-12-13 02:39:30', '2025-12-13 03:14:47'),
(152, 112, 26, 3, '17:30:00', '20:30:00', '2025-12-13 02:39:30', '2025-12-13 03:14:47'),
(153, 112, 26, 5, '13:30:00', '17:00:00', '2025-12-13 02:39:30', '2025-12-13 03:14:47'),
(154, 112, 26, 5, '17:30:00', '20:30:00', '2025-12-13 02:39:30', '2025-12-13 03:14:47'),
(155, 112, 26, 0, '07:30:00', '11:30:00', '2025-12-13 02:39:30', '2025-12-13 03:14:47'),
(156, 116, 22, 2, '07:30:00', '11:30:00', '2025-12-13 02:39:30', '2025-12-13 03:14:47'),
(157, 116, 22, 2, '13:30:00', '17:00:00', '2025-12-13 02:39:30', '2025-12-13 03:14:47'),
(158, 116, 22, 2, '17:30:00', '20:30:00', '2025-12-13 02:39:30', '2025-12-13 03:14:47'),
(159, 116, 22, 4, '07:30:00', '11:30:00', '2025-12-13 02:39:30', '2025-12-13 03:14:47'),
(160, 116, 22, 4, '13:30:00', '17:00:00', '2025-12-13 02:39:30', '2025-12-13 03:14:47'),
(161, 116, 22, 4, '17:30:00', '20:30:00', '2025-12-13 02:39:30', '2025-12-13 03:14:47'),
(162, 116, 22, 6, '07:30:00', '11:30:00', '2025-12-13 02:39:30', '2025-12-13 03:14:47'),
(163, 116, 22, 6, '13:30:00', '17:00:00', '2025-12-13 02:39:30', '2025-12-13 03:14:47'),
(164, 116, 22, 6, '17:30:00', '20:30:00', '2025-12-13 02:39:30', '2025-12-13 03:14:47'),
(165, 116, 22, 1, '13:30:00', '17:00:00', '2025-12-13 02:39:30', '2025-12-13 03:14:47'),
(166, 116, 22, 1, '17:30:00', '20:30:00', '2025-12-13 02:39:30', '2025-12-13 03:14:47'),
(167, 116, 22, 3, '13:30:00', '17:00:00', '2025-12-13 02:39:30', '2025-12-13 03:14:47'),
(168, 116, 22, 3, '17:30:00', '20:30:00', '2025-12-13 02:39:30', '2025-12-13 03:14:47'),
(169, 116, 22, 5, '13:30:00', '17:00:00', '2025-12-13 02:39:30', '2025-12-13 03:14:47'),
(170, 116, 22, 5, '17:30:00', '20:30:00', '2025-12-13 02:39:30', '2025-12-13 03:14:47'),
(171, 116, 22, 0, '07:30:00', '11:30:00', '2025-12-13 02:39:30', '2025-12-13 03:14:47');

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
(1, 106, '2025-12-14', '07:30:00', '11:30:00', 'Hội thảo', '2025-12-13 02:39:30', '2025-12-13 02:39:30'),
(2, 106, '2025-12-14', '13:30:00', '17:00:00', 'Hội thảo', '2025-12-13 02:39:30', '2025-12-13 02:39:30'),
(3, 111, '2025-12-15', '00:00:00', '23:59:59', 'Việc riêng', '2025-12-13 02:39:30', '2025-12-13 02:39:30');

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
(1, NULL, 'Admin@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', 'failed', 'invalid_credentials', '2025-12-12 03:54:41', '2025-12-12 03:54:41'),
(2, NULL, 'Admin@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', 'failed', 'invalid_credentials', '2025-12-12 03:54:45', '2025-12-12 03:54:45'),
(3, NULL, 'Admin@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', 'failed', 'invalid_credentials', '2025-12-12 03:54:59', '2025-12-12 03:54:59'),
(4, NULL, 'henvaemhen@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', 'failed', 'invalid_credentials', '2025-12-12 03:55:59', '2025-12-12 03:55:59'),
(5, NULL, 'Admin@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'success', NULL, '2025-12-12 04:18:08', '2025-12-12 04:18:08'),
(6, 13, 'Admin@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'success', NULL, '2025-12-12 04:24:50', '2025-12-12 04:24:50'),
(7, 13, 'Admin@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', 'success', NULL, '2025-12-12 04:27:14', '2025-12-12 04:27:14'),
(8, 13, 'Admin@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'success', NULL, '2025-12-12 04:29:30', '2025-12-12 04:29:30'),
(9, 13, 'Admin@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'success', NULL, '2025-12-12 04:37:44', '2025-12-12 04:37:44'),
(10, 13, 'Admin@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'success', NULL, '2025-12-12 08:39:46', '2025-12-12 08:39:46'),
(11, 13, 'Admin@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', 'failed', 'invalid_credentials', '2025-12-13 02:13:24', '2025-12-13 02:13:24'),
(12, 13, 'Admin@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', 'success', NULL, '2025-12-13 02:13:34', '2025-12-13 02:13:34'),
(13, 148, 'tn822798@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'success', NULL, '2025-12-13 02:14:29', '2025-12-13 02:14:29'),
(14, NULL, 'henvaemhen@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', 'failed', 'invalid_credentials', '2025-12-13 04:00:35', '2025-12-13 04:00:35'),
(15, 148, 'tn822798@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'success', NULL, '2025-12-13 04:20:32', '2025-12-13 04:20:32'),
(16, 137, 'hunghoang@vietcare.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'failed', 'invalid_credentials', '2025-12-13 04:23:09', '2025-12-13 04:23:09'),
(17, 137, 'hunghoang@vietcare.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'failed', 'invalid_credentials', '2025-12-13 04:24:11', '2025-12-13 04:24:11'),
(18, 149, 'henvaemhen@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', 'success', NULL, '2025-12-13 04:24:34', '2025-12-13 04:24:34'),
(19, 147, 'lan.thammy@vietcare.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'failed', 'invalid_credentials', '2025-12-13 04:27:14', '2025-12-13 04:27:14'),
(20, 137, 'hungpham@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'failed', 'invalid_credentials', '2025-12-13 04:29:17', '2025-12-13 04:29:17'),
(21, 137, 'hungpham@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'failed', 'invalid_credentials', '2025-12-13 04:29:19', '2025-12-13 04:29:19'),
(22, NULL, 'hunghoang@vietcare.comhun', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'failed', 'invalid_credentials', '2025-12-13 04:30:24', '2025-12-13 04:30:24'),
(23, 137, 'hunghoang@vietcare.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'failed', 'invalid_credentials', '2025-12-13 04:32:03', '2025-12-13 04:32:03'),
(24, 137, 'hunghoang@vietcare.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'failed', 'account_locked', '2025-12-13 04:34:51', '2025-12-13 04:34:51'),
(25, 137, 'hunghoang@vietcare.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'success', NULL, '2025-12-13 04:35:22', '2025-12-13 04:35:22'),
(26, 143, 'phuocnguyen@vietcare.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'failed', 'invalid_credentials', '2025-12-13 04:36:56', '2025-12-13 04:36:56'),
(27, 143, 'phuocnguyen@vietcare.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'success', NULL, '2025-12-13 04:37:02', '2025-12-13 04:37:02'),
(28, 137, 'hunghoang@vietcare.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'success', NULL, '2025-12-13 04:38:33', '2025-12-13 04:38:33'),
(29, 137, 'hunghoang@vietcare.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'success', NULL, '2025-12-13 04:40:34', '2025-12-13 04:40:34'),
(30, 141, 'ngocvo@vietcare.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'success', NULL, '2025-12-13 04:43:11', '2025-12-13 04:43:11'),
(31, 142, 'thuypham@vietcare.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'success', NULL, '2025-12-13 04:43:44', '2025-12-13 04:43:44'),
(32, 136, 'lananh@vietcare.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'success', NULL, '2025-12-13 04:44:39', '2025-12-13 04:44:39'),
(33, 138, 'hatran@vietcare.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'success', NULL, '2025-12-13 04:45:17', '2025-12-13 04:45:17'),
(34, 139, 'vannguyen@vietcare.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'success', NULL, '2025-12-13 04:45:51', '2025-12-13 04:45:51'),
(35, 140, 'tuanhoang@vietcare.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'success', NULL, '2025-12-13 04:46:20', '2025-12-13 04:46:20'),
(36, 143, 'phuocnguyen@vietcare.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'success', NULL, '2025-12-13 04:46:51', '2025-12-13 04:46:51'),
(37, 144, 'linhdo@vietcare.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'success', NULL, '2025-12-13 04:47:38', '2025-12-13 04:47:38'),
(38, 145, 'maile@vietcare.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'success', NULL, '2025-12-13 04:48:08', '2025-12-13 04:48:08'),
(39, 146, 'minh.xetnghiem@vietcare.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'success', NULL, '2025-12-13 04:48:32', '2025-12-13 04:48:32'),
(40, 147, 'lan.thammy@vietcare.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'success', NULL, '2025-12-13 04:49:04', '2025-12-13 04:49:04'),
(41, 143, 'phuocnguyen@vietcare.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'success', NULL, '2025-12-13 04:49:32', '2025-12-13 04:49:32'),
(42, 143, 'phuocnguyen@vietcare.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'failed', 'invalid_credentials', '2025-12-13 04:49:46', '2025-12-13 04:49:46'),
(43, 143, 'phuocnguyen@vietcare.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'success', NULL, '2025-12-13 04:49:51', '2025-12-13 04:49:51'),
(44, 149, 'henvaemhen@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'failed', 'invalid_credentials', '2025-12-13 04:54:35', '2025-12-13 04:54:35'),
(45, 149, 'henvaemhen@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'failed', 'invalid_credentials', '2025-12-13 04:54:39', '2025-12-13 04:54:39'),
(46, 149, 'henvaemhen@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'success', NULL, '2025-12-13 04:54:46', '2025-12-13 04:54:46'),
(47, 149, 'henvaemhen@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'failed', 'invalid_credentials', '2025-12-13 04:55:27', '2025-12-13 04:55:27'),
(48, 149, 'henvaemhen@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'success', NULL, '2025-12-13 04:55:48', '2025-12-13 04:55:48'),
(49, 139, 'vannguyen@vietcare.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', 'success', NULL, '2025-12-13 04:56:58', '2025-12-13 04:56:58'),
(50, 148, 'tn822798@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'success', NULL, '2025-12-13 07:06:31', '2025-12-13 07:06:31'),
(51, 139, 'vannguyen@vietcare.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'failed', 'invalid_credentials', '2025-12-13 07:07:09', '2025-12-13 07:07:09'),
(52, 139, 'vannguyen@vietcare.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'success', NULL, '2025-12-13 07:07:13', '2025-12-13 07:07:13'),
(53, 13, 'Admin@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', 'success', NULL, '2025-12-13 07:08:52', '2025-12-13 07:08:52'),
(54, 149, 'henvaemhen@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', 'failed', 'invalid_credentials', '2025-12-13 07:09:18', '2025-12-13 07:09:18'),
(55, 149, 'henvaemhen@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', 'success', NULL, '2025-12-13 07:09:23', '2025-12-13 07:09:23'),
(56, 13, 'Admin@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', 'success', NULL, '2025-12-13 11:06:07', '2025-12-13 11:06:07'),
(57, 148, 'tn822798@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'success', NULL, '2025-12-13 11:07:00', '2025-12-13 11:07:00'),
(58, 149, 'henvaemhen@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', 'failed', 'invalid_credentials', '2025-12-13 11:07:29', '2025-12-13 11:07:29'),
(59, 149, 'henvaemhen@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', 'success', NULL, '2025-12-13 11:07:34', '2025-12-13 11:07:34'),
(60, 139, 'vannguyen@vietcare.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'success', NULL, '2025-12-13 11:08:19', '2025-12-13 11:08:19'),
(61, NULL, 'doctor1@example.test', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'success', NULL, '2025-12-13 11:39:17', '2025-12-13 11:39:17'),
(62, 13, 'Admin@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', 'success', NULL, '2025-12-13 14:07:18', '2025-12-13 14:07:18'),
(63, 149, 'henvaemhen@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', 'success', NULL, '2025-12-13 14:08:24', '2025-12-13 14:08:24'),
(64, 148, 'tn822798@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'success', NULL, '2025-12-13 14:08:56', '2025-12-13 14:08:56'),
(65, 139, 'vannguyen@vietcare.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'success', NULL, '2025-12-13 14:09:29', '2025-12-13 14:09:29');

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
(11, '2025_11_02_235900_create_lich_nghis_table_if_not_exists', 1),
(12, '2025_11_03_000000_create_lich_nghis_table', 1),
(13, '2025_11_03_010000_add_user_id_to_bac_sis', 1),
(14, '2025_11_03_130343_add_user_id_to_bac_sis_table', 1),
(15, '2025_11_04_084258_update_users_table_set_role_defaults', 1),
(16, '2025_11_04_190000_create_benh_ans_table', 1),
(17, '2025_11_04_190100_create_benh_an_files_table', 1),
(18, '2025_11_05_120000_backfill_lich_hens_status_to_vn', 1),
(19, '2025_11_05_130000_create_hoa_dons_table', 1),
(20, '2025_11_05_130100_create_thanh_toans_table', 1),
(21, '2025_11_07_000000_add_payment_columns_to_lich_hens_table', 1),
(22, '2025_11_07_000002_create_hoan_tiens_table', 1),
(23, '2025_11_07_010000_rename_payment_columns_on_lich_hens_table', 1),
(24, '2025_11_10_120000_add_cancel_columns_to_lich_hens', 1),
(25, '2025_11_11_093527_add_missing_columns_to_bac_sis_table', 1),
(26, '2025_11_13_120001_create_thuocs_table', 1),
(27, '2025_11_13_120002_create_don_thuocs_table', 1),
(28, '2025_11_13_120003_create_xet_nghiems_table', 1),
(29, '2025_11_13_170001_create_nhan_viens_table', 1),
(30, '2025_11_13_170002_create_ca_lam_viec_nhan_viens_table', 1),
(31, '2025_11_13_180000_add_unique_user_to_nhan_viens', 1),
(32, '2025_11_14_090000_create_ca_dieu_chinh_bac_sis_table', 1),
(33, '2025_11_14_230000_add_time_columns_to_lich_hens', 1),
(34, '2025_11_17_000001_create_nha_cung_caps_table', 1),
(35, '2025_11_17_000002_create_thuoc_khos_table', 1),
(36, '2025_11_17_000003_create_phieu_nhaps_table', 1),
(37, '2025_11_17_000004_create_phieu_xuats_table', 1),
(38, '2025_11_17_100100_create_chuyen_khoas_and_phongs', 1),
(39, '2025_11_17_100200_add_phong_id_to_lich_lam_viecs', 1),
(40, '2025_11_17_104853_create_jobs_table', 1),
(41, '2025_11_17_104907_create_jobs_table', 1),
(42, '2025_11_17_110000_create_danh_mucs_table', 1),
(43, '2025_11_17_110100_create_tags_table', 1),
(44, '2025_11_17_110200_create_bai_viets_table', 1),
(45, '2025_11_17_110300_create_bai_viet_tag_table', 1),
(46, '2025_11_17_110400_reinforce_lich_hen_slot_uniqueness', 1),
(47, '2025_11_20_054524_create_benh_an_audits_table', 1),
(48, '2025_11_20_055120_add_disk_to_benh_an_files_table', 1),
(49, '2025_11_20_073110_add_disk_to_xet_nghiems_table', 1),
(50, '2025_11_20_100000_create_payment_logs_table', 1),
(51, '2025_11_20_100100_add_idempotency_key_to_thanh_toans_table', 1),
(52, '2025_11_20_110000_add_tong_tien_to_lich_hens_table', 1),
(53, '2025_11_21_000001_create_nhan_vien_audits_table', 1),
(54, '2025_11_21_041434_create_permission_tables', 1),
(55, '2025_11_21_050000_add_account_security_to_users', 1),
(56, '2025_11_21_050100_create_login_audits_table', 1),
(57, '2025_11_30_105600_add_avatar_to_bac_sis_table', 1),
(58, '2025_12_04_140000_add_status_fields_to_hoa_dons_table', 1),
(59, '2025_12_04_145328_add_ton_toi_thieu_to_thuocs_table', 1),
(60, '2025_12_04_150000_add_status_to_phongs_table', 1),
(61, '2025_12_04_150308_create_coupons_table', 1),
(62, '2025_12_04_150320_create_don_hangs_table', 1),
(63, '2025_12_04_150330_create_don_hang_items_table', 1),
(64, '2025_12_04_155210_create_nha_cung_cap_thuoc_table', 1),
(65, '2025_12_04_160000_fix_phieu_nhaps_foreign_key', 1),
(66, '2025_12_04_160100_fix_phieu_nhap_items_foreign_key', 1),
(67, '2025_12_04_160200_fix_phieu_xuat_items_foreign_key', 1),
(68, '2025_12_05_092931_add_deleted_at_to_bai_viets_table', 1),
(69, '2025_12_05_101210_create_patient_profiles_table', 1),
(70, '2025_12_05_101211_create_notification_preferences_table', 1),
(71, '2025_12_05_144344_create_danh_gias_table', 1),
(72, '2025_12_05_150000_create_conversations_table', 1),
(73, '2025_12_05_150001_create_messages_table', 1),
(74, '2025_12_05_201222_create_family_members_table', 1),
(75, '2025_12_05_204006_create_notifications_table', 1),
(76, '2025_12_06_155207_add_sms_appointment_cancelled_to_notification_preferences_table', 1),
(77, '2025_12_07_133150_add_workflow_status_to_lich_hens_table', 1),
(78, '2025_12_07_133230_add_status_to_xet_nghiems_table', 1),
(79, '2025_12_08_174915_add_patient_info_to_users_table', 1),
(80, '2025_12_09_123000_add_started_at_to_lich_hens_table', 1),
(81, '2025_12_09_130926_create_activity_log_table', 1),
(82, '2025_12_09_130927_add_event_column_to_activity_log_table', 1),
(83, '2025_12_09_130928_add_batch_uuid_column_to_activity_log_table', 1),
(84, '2025_12_10_161500_add_avatar_to_users_table', 1),
(85, '2025_12_11_000001_create_chuyen_khoa_dich_vu_table', 1),
(86, '2025_12_12_200000_create_slot_locks_table', 1),
(87, '2025_12_12_201000_add_patient_info_to_lich_hens', 1),
(88, '2025_12_12_202000_add_coupon_to_hoa_dons', 1);

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
(1, 'App\\Models\\User', 13),
(1, 'App\\Models\\User', 150),
(2, 'App\\Models\\User', 13),
(4, 'App\\Models\\User', 136),
(4, 'App\\Models\\User', 137),
(4, 'App\\Models\\User', 138),
(4, 'App\\Models\\User', 139),
(4, 'App\\Models\\User', 140),
(4, 'App\\Models\\User', 141),
(4, 'App\\Models\\User', 142),
(4, 'App\\Models\\User', 143),
(4, 'App\\Models\\User', 144),
(4, 'App\\Models\\User', 145),
(4, 'App\\Models\\User', 146),
(4, 'App\\Models\\User', 147),
(5, 'App\\Models\\User', 25),
(5, 'App\\Models\\User', 26),
(5, 'App\\Models\\User', 27),
(5, 'App\\Models\\User', 28),
(5, 'App\\Models\\User', 29),
(5, 'App\\Models\\User', 51),
(5, 'App\\Models\\User', 52),
(5, 'App\\Models\\User', 53),
(5, 'App\\Models\\User', 54),
(5, 'App\\Models\\User', 55),
(5, 'App\\Models\\User', 115),
(5, 'App\\Models\\User', 116),
(5, 'App\\Models\\User', 117),
(5, 'App\\Models\\User', 118),
(5, 'App\\Models\\User', 119),
(5, 'App\\Models\\User', 120),
(5, 'App\\Models\\User', 121),
(5, 'App\\Models\\User', 122),
(5, 'App\\Models\\User', 123),
(5, 'App\\Models\\User', 149),
(6, 'App\\Models\\User', 148),
(6, 'App\\Models\\User', 150);

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
(8, 115, 'Nguyễn Thị Hồng Hạnh', 'Nhân viên CSKH', '0909222002', 'hanh.nguyen@vietcare.com', '2000-10-20', 'nu', 'nv_avatar/9mZ0E9DC2etpeikNnSxRKryaqtVKcRYDeQHR0YUi.png', 'active', '2025-12-12 06:41:13', '2025-12-12 07:24:50'),
(9, 116, 'Trần Thị Kim Dung', 'Lễ tân', '0909222003', 'dung.tran@vietcare.com', '1999-08-15', 'nu', 'nv_avatar/gTy76mUjFUdwlSE4wuYBiYyjeJl7BKYbcR0AzfP3.png', 'active', '2025-12-12 06:41:13', '2025-12-12 07:23:32'),
(10, 117, 'Phạm Thị Thu Thảo', 'Điều dưỡng trưởng', '0909222004', 'thao.pham@vietcare.com', '1985-02-05', 'nu', 'nv_avatar/J9L8WaUVEUXaH5U0T1yFHsYxH2zb3DuVJTfvUhOD.png', 'active', '2025-12-12 06:41:13', '2025-12-12 07:24:40'),
(11, 118, 'Lê Thị Thanh Trúc', 'Nữ hộ sinh', '0909222005', 'truc.le@vietcare.com', '1995-11-10', 'nu', 'nv_avatar/fbPQYtNRPoz4kUjPK9PLpXALB28b564CiTUAK5ic.png', 'active', '2025-12-12 06:41:13', '2025-12-12 07:22:14'),
(12, 119, 'Nguyễn Ngọc Huyền', 'Điều dưỡng đa khoa', '0909222006', 'huyen.nguyen@vietcare.com', '1997-07-22', 'nu', 'nv_avatar/XP0eYT5HYXhEcRCfTxcPSxSaCTuiLMNtCdGB1IFT.png', 'active', '2025-12-12 06:41:14', '2025-12-12 07:20:05'),
(13, 120, 'Vũ Thị Minh Anh', 'Thu ngân', '0909222007', 'anh.vu@vietcare.com', '1996-04-30', 'nu', 'nv_avatar/abxEAXOG6uldxyMHONI9sU1gwikUptIeDInfsI9n.png', 'active', '2025-12-12 06:41:14', '2025-12-12 07:19:48'),
(14, 121, 'Hoàng Văn Nam', 'Dược sĩ', '0909222008', 'nam.hoang@vietcare.com', '1990-09-14', 'nam', 'nv_avatar/SORI798EIccHzFfo0BTZLYX9IhXiD7kn88jXCOub.png', 'active', '2025-12-12 06:41:14', '2025-12-12 07:21:55'),
(15, 122, 'Nguyễn Thu Phương', 'Quản lý phòng khám', '0909222009', 'phuong.nguyen@vietcare.com', '1980-01-01', 'nu', 'nv_avatar/hcuvgd00doWlGBX75i8WDbJHKfjeuJXe3M0HEyaN.png', 'active', '2025-12-12 06:41:14', '2025-12-12 07:19:29'),
(16, 123, 'Trần Quốc Bảo', 'Nhân viên IT / Kỹ thuật', '0909222010', 'bao.tran@vietcare.com', '1995-12-18', 'nam', 'nv_avatar/CZmBnBIrQbAcDdMl7QI9AiiXVvgXPNIFnSrrWBpb.png', 'active', '2025-12-12 06:41:14', '2025-12-12 07:18:50'),
(17, 149, 'Lê Minh Nhật', 'Trưởng lễ tân', '0789654123', 'henvaemhen@gmail.com', '2004-12-12', 'nam', 'nv_avatar/n53Ti8mAFEeIWZkE7fn214GxlPNQrBCHokZb2fpT.png', 'active', '2025-12-13 04:13:14', '2025-12-13 04:13:14');

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
(11, 8, NULL, 'created', NULL, '{\"email_cong_viec\":\"hanh.nguyen@vietcare.com\",\"user_id\":115,\"ho_ten\":\"Nguy\\u1ec5n Th\\u1ecb H\\u1ed3ng H\\u1ea1nh\",\"chuc_vu\":\"Nh\\u00e2n vi\\u00ean CSKH\",\"so_dien_thoai\":\"0909222002\",\"ngay_sinh\":\"2000-10-20\",\"gioi_tinh\":\"N\\u1eef\",\"trang_thai\":\"\\u0110ang l\\u00e0m\",\"updated_at\":\"2025-12-12 13:41:13\",\"created_at\":\"2025-12-12 13:41:13\",\"id\":8}', '2025-12-12 06:41:13', '2025-12-12 06:41:13'),
(12, 9, NULL, 'created', NULL, '{\"email_cong_viec\":\"dung.tran@vietcare.com\",\"user_id\":116,\"ho_ten\":\"Tr\\u1ea7n Th\\u1ecb Kim Dung\",\"chuc_vu\":\"L\\u1ec5 t\\u00e2n\",\"so_dien_thoai\":\"0909222003\",\"ngay_sinh\":\"1999-08-15\",\"gioi_tinh\":\"N\\u1eef\",\"trang_thai\":\"\\u0110ang l\\u00e0m\",\"updated_at\":\"2025-12-12 13:41:13\",\"created_at\":\"2025-12-12 13:41:13\",\"id\":9}', '2025-12-12 06:41:13', '2025-12-12 06:41:13'),
(13, 10, NULL, 'created', NULL, '{\"email_cong_viec\":\"thao.pham@vietcare.com\",\"user_id\":117,\"ho_ten\":\"Ph\\u1ea1m Th\\u1ecb Thu Th\\u1ea3o\",\"chuc_vu\":\"\\u0110i\\u1ec1u d\\u01b0\\u1ee1ng tr\\u01b0\\u1edfng\",\"so_dien_thoai\":\"0909222004\",\"ngay_sinh\":\"1985-02-05\",\"gioi_tinh\":\"N\\u1eef\",\"trang_thai\":\"\\u0110ang l\\u00e0m\",\"updated_at\":\"2025-12-12 13:41:13\",\"created_at\":\"2025-12-12 13:41:13\",\"id\":10}', '2025-12-12 06:41:13', '2025-12-12 06:41:13'),
(14, 11, NULL, 'created', NULL, '{\"email_cong_viec\":\"truc.le@vietcare.com\",\"user_id\":118,\"ho_ten\":\"L\\u00ea Th\\u1ecb Thanh Tr\\u00fac\",\"chuc_vu\":\"N\\u1eef h\\u1ed9 sinh\",\"so_dien_thoai\":\"0909222005\",\"ngay_sinh\":\"1995-11-10\",\"gioi_tinh\":\"N\\u1eef\",\"trang_thai\":\"\\u0110ang l\\u00e0m\",\"updated_at\":\"2025-12-12 13:41:13\",\"created_at\":\"2025-12-12 13:41:13\",\"id\":11}', '2025-12-12 06:41:13', '2025-12-12 06:41:13'),
(15, 12, NULL, 'created', NULL, '{\"email_cong_viec\":\"huyen.nguyen@vietcare.com\",\"user_id\":119,\"ho_ten\":\"Nguy\\u1ec5n Ng\\u1ecdc Huy\\u1ec1n\",\"chuc_vu\":\"\\u0110i\\u1ec1u d\\u01b0\\u1ee1ng \\u0111a khoa\",\"so_dien_thoai\":\"0909222006\",\"ngay_sinh\":\"1997-07-22\",\"gioi_tinh\":\"N\\u1eef\",\"trang_thai\":\"\\u0110ang l\\u00e0m\",\"updated_at\":\"2025-12-12 13:41:14\",\"created_at\":\"2025-12-12 13:41:14\",\"id\":12}', '2025-12-12 06:41:14', '2025-12-12 06:41:14'),
(16, 13, NULL, 'created', NULL, '{\"email_cong_viec\":\"anh.vu@vietcare.com\",\"user_id\":120,\"ho_ten\":\"V\\u0169 Th\\u1ecb Minh Anh\",\"chuc_vu\":\"Thu ng\\u00e2n\",\"so_dien_thoai\":\"0909222007\",\"ngay_sinh\":\"1996-04-30\",\"gioi_tinh\":\"N\\u1eef\",\"trang_thai\":\"\\u0110ang l\\u00e0m\",\"updated_at\":\"2025-12-12 13:41:14\",\"created_at\":\"2025-12-12 13:41:14\",\"id\":13}', '2025-12-12 06:41:14', '2025-12-12 06:41:14'),
(17, 14, NULL, 'created', NULL, '{\"email_cong_viec\":\"nam.hoang@vietcare.com\",\"user_id\":121,\"ho_ten\":\"Ho\\u00e0ng V\\u0103n Nam\",\"chuc_vu\":\"D\\u01b0\\u1ee3c s\\u0129\",\"so_dien_thoai\":\"0909222008\",\"ngay_sinh\":\"1990-09-14\",\"gioi_tinh\":\"Nam\",\"trang_thai\":\"\\u0110ang l\\u00e0m\",\"updated_at\":\"2025-12-12 13:41:14\",\"created_at\":\"2025-12-12 13:41:14\",\"id\":14}', '2025-12-12 06:41:14', '2025-12-12 06:41:14'),
(18, 15, NULL, 'created', NULL, '{\"email_cong_viec\":\"phuong.nguyen@vietcare.com\",\"user_id\":122,\"ho_ten\":\"Nguy\\u1ec5n Thu Ph\\u01b0\\u01a1ng\",\"chuc_vu\":\"Qu\\u1ea3n l\\u00fd ph\\u00f2ng kh\\u00e1m\",\"so_dien_thoai\":\"0909222009\",\"ngay_sinh\":\"1980-01-01\",\"gioi_tinh\":\"N\\u1eef\",\"trang_thai\":\"\\u0110ang l\\u00e0m\",\"updated_at\":\"2025-12-12 13:41:14\",\"created_at\":\"2025-12-12 13:41:14\",\"id\":15}', '2025-12-12 06:41:14', '2025-12-12 06:41:14'),
(19, 16, NULL, 'created', NULL, '{\"email_cong_viec\":\"bao.tran@vietcare.com\",\"user_id\":123,\"ho_ten\":\"Tr\\u1ea7n Qu\\u1ed1c B\\u1ea3o\",\"chuc_vu\":\"Nh\\u00e2n vi\\u00ean IT \\/ K\\u1ef9 thu\\u1eadt\",\"so_dien_thoai\":\"0909222010\",\"ngay_sinh\":\"1995-12-18\",\"gioi_tinh\":\"Nam\",\"trang_thai\":\"\\u0110ang l\\u00e0m\",\"updated_at\":\"2025-12-12 13:41:14\",\"created_at\":\"2025-12-12 13:41:14\",\"id\":16}', '2025-12-12 06:41:14', '2025-12-12 06:41:14'),
(20, 16, 13, 'updated', '{\"trang_thai\":\"\\u0110ang l\\u00e0m\",\"updated_at\":\"2025-12-12T06:41:14.000000Z\"}', '{\"trang_thai\":\"inactive\",\"updated_at\":\"2025-12-12 13:43:52\"}', '2025-12-12 06:43:52', '2025-12-12 06:43:52'),
(21, 16, 13, 'updated', '{\"trang_thai\":\"inactive\",\"updated_at\":\"2025-12-12T06:43:52.000000Z\"}', '{\"trang_thai\":\"active\",\"updated_at\":\"2025-12-12 13:43:58\"}', '2025-12-12 06:43:58', '2025-12-12 06:43:58'),
(23, 16, NULL, 'updated', '{\"trang_thai\":\"active\",\"updated_at\":\"2025-12-12T06:43:58.000000Z\"}', '{\"trang_thai\":\"\\u0110ang l\\u00e0m\",\"updated_at\":\"2025-12-12 13:47:24\"}', '2025-12-12 06:47:24', '2025-12-12 06:47:24'),
(24, 16, 13, 'updated', '{\"trang_thai\":\"\\u0110ang l\\u00e0m\",\"updated_at\":\"2025-12-12T06:47:24.000000Z\"}', '{\"trang_thai\":\"inactive\",\"updated_at\":\"2025-12-12 14:07:57\"}', '2025-12-12 07:07:57', '2025-12-12 07:07:57'),
(25, 16, 13, 'updated', '{\"trang_thai\":\"inactive\",\"updated_at\":\"2025-12-12T07:07:57.000000Z\"}', '{\"trang_thai\":\"active\",\"updated_at\":\"2025-12-12 14:08:12\"}', '2025-12-12 07:08:12', '2025-12-12 07:08:12'),
(26, 15, 13, 'updated', '{\"trang_thai\":\"\\u0110ang l\\u00e0m\",\"updated_at\":\"2025-12-12T06:41:14.000000Z\"}', '{\"trang_thai\":\"inactive\",\"updated_at\":\"2025-12-12 14:17:04\"}', '2025-12-12 07:17:04', '2025-12-12 07:17:04'),
(27, 14, 13, 'updated', '{\"trang_thai\":\"\\u0110ang l\\u00e0m\",\"updated_at\":\"2025-12-12T06:41:14.000000Z\"}', '{\"trang_thai\":\"inactive\",\"updated_at\":\"2025-12-12 14:17:07\"}', '2025-12-12 07:17:07', '2025-12-12 07:17:07'),
(28, 13, 13, 'updated', '{\"trang_thai\":\"\\u0110ang l\\u00e0m\",\"updated_at\":\"2025-12-12T06:41:14.000000Z\"}', '{\"trang_thai\":\"inactive\",\"updated_at\":\"2025-12-12 14:17:09\"}', '2025-12-12 07:17:09', '2025-12-12 07:17:09'),
(29, 12, 13, 'updated', '{\"trang_thai\":\"\\u0110ang l\\u00e0m\",\"updated_at\":\"2025-12-12T06:41:14.000000Z\"}', '{\"trang_thai\":\"inactive\",\"updated_at\":\"2025-12-12 14:17:11\"}', '2025-12-12 07:17:11', '2025-12-12 07:17:11'),
(31, 8, 13, 'updated', '{\"trang_thai\":\"\\u0110ang l\\u00e0m\",\"updated_at\":\"2025-12-12T06:41:13.000000Z\"}', '{\"trang_thai\":\"inactive\",\"updated_at\":\"2025-12-12 14:17:17\"}', '2025-12-12 07:17:17', '2025-12-12 07:17:17'),
(32, 9, 13, 'updated', '{\"trang_thai\":\"\\u0110ang l\\u00e0m\",\"updated_at\":\"2025-12-12T06:41:13.000000Z\"}', '{\"trang_thai\":\"inactive\",\"updated_at\":\"2025-12-12 14:17:19\"}', '2025-12-12 07:17:19', '2025-12-12 07:17:19'),
(33, 10, 13, 'updated', '{\"trang_thai\":\"\\u0110ang l\\u00e0m\",\"updated_at\":\"2025-12-12T06:41:13.000000Z\"}', '{\"trang_thai\":\"inactive\",\"updated_at\":\"2025-12-12 14:17:22\"}', '2025-12-12 07:17:22', '2025-12-12 07:17:22'),
(34, 11, 13, 'updated', '{\"trang_thai\":\"\\u0110ang l\\u00e0m\",\"updated_at\":\"2025-12-12T06:41:13.000000Z\"}', '{\"trang_thai\":\"inactive\",\"updated_at\":\"2025-12-12 14:17:24\"}', '2025-12-12 07:17:24', '2025-12-12 07:17:24'),
(35, 12, 13, 'updated', '{\"trang_thai\":\"inactive\",\"updated_at\":\"2025-12-12T07:17:11.000000Z\"}', '{\"trang_thai\":\"active\",\"updated_at\":\"2025-12-12 14:17:27\"}', '2025-12-12 07:17:27', '2025-12-12 07:17:27'),
(36, 13, 13, 'updated', '{\"trang_thai\":\"inactive\",\"updated_at\":\"2025-12-12T07:17:09.000000Z\"}', '{\"trang_thai\":\"active\",\"updated_at\":\"2025-12-12 14:17:28\"}', '2025-12-12 07:17:28', '2025-12-12 07:17:28'),
(37, 14, 13, 'updated', '{\"trang_thai\":\"inactive\",\"updated_at\":\"2025-12-12T07:17:07.000000Z\"}', '{\"trang_thai\":\"active\",\"updated_at\":\"2025-12-12 14:17:30\"}', '2025-12-12 07:17:30', '2025-12-12 07:17:30'),
(38, 15, 13, 'updated', '{\"trang_thai\":\"inactive\",\"updated_at\":\"2025-12-12T07:17:04.000000Z\"}', '{\"trang_thai\":\"active\",\"updated_at\":\"2025-12-12 14:17:32\"}', '2025-12-12 07:17:32', '2025-12-12 07:17:32'),
(40, 8, 13, 'updated', '{\"trang_thai\":\"inactive\",\"updated_at\":\"2025-12-12T07:17:17.000000Z\"}', '{\"trang_thai\":\"active\",\"updated_at\":\"2025-12-12 14:17:38\"}', '2025-12-12 07:17:38', '2025-12-12 07:17:38'),
(41, 9, 13, 'updated', '{\"trang_thai\":\"inactive\",\"updated_at\":\"2025-12-12T07:17:19.000000Z\"}', '{\"trang_thai\":\"active\",\"updated_at\":\"2025-12-12 14:17:41\"}', '2025-12-12 07:17:41', '2025-12-12 07:17:41'),
(42, 10, 13, 'updated', '{\"trang_thai\":\"inactive\",\"updated_at\":\"2025-12-12T07:17:22.000000Z\"}', '{\"trang_thai\":\"active\",\"updated_at\":\"2025-12-12 14:17:43\"}', '2025-12-12 07:17:43', '2025-12-12 07:17:43'),
(43, 11, 13, 'updated', '{\"trang_thai\":\"inactive\",\"updated_at\":\"2025-12-12T07:17:24.000000Z\"}', '{\"trang_thai\":\"active\",\"updated_at\":\"2025-12-12 14:17:46\"}', '2025-12-12 07:17:46', '2025-12-12 07:17:46'),
(44, 16, 13, 'updated', '{\"gioi_tinh\":\"Nam\",\"avatar_path\":null,\"updated_at\":\"2025-12-12T07:08:12.000000Z\"}', '{\"gioi_tinh\":\"nam\",\"avatar_path\":\"nv_avatar\\/CZmBnBIrQbAcDdMl7QI9AiiXVvgXPNIFnSrrWBpb.png\",\"updated_at\":\"2025-12-12 14:18:50\"}', '2025-12-12 07:18:50', '2025-12-12 07:18:50'),
(45, 15, 13, 'updated', '{\"gioi_tinh\":\"N\\u1eef\",\"avatar_path\":null,\"updated_at\":\"2025-12-12T07:17:32.000000Z\"}', '{\"gioi_tinh\":\"nu\",\"avatar_path\":\"nv_avatar\\/hcuvgd00doWlGBX75i8WDbJHKfjeuJXe3M0HEyaN.png\",\"updated_at\":\"2025-12-12 14:19:29\"}', '2025-12-12 07:19:29', '2025-12-12 07:19:29'),
(46, 13, 13, 'updated', '{\"gioi_tinh\":\"N\\u1eef\",\"avatar_path\":null,\"updated_at\":\"2025-12-12T07:17:28.000000Z\"}', '{\"gioi_tinh\":\"nu\",\"avatar_path\":\"nv_avatar\\/abxEAXOG6uldxyMHONI9sU1gwikUptIeDInfsI9n.png\",\"updated_at\":\"2025-12-12 14:19:48\"}', '2025-12-12 07:19:48', '2025-12-12 07:19:48'),
(47, 12, 13, 'updated', '{\"gioi_tinh\":\"N\\u1eef\",\"avatar_path\":null,\"updated_at\":\"2025-12-12T07:17:27.000000Z\"}', '{\"gioi_tinh\":\"nu\",\"avatar_path\":\"nv_avatar\\/XP0eYT5HYXhEcRCfTxcPSxSaCTuiLMNtCdGB1IFT.png\",\"updated_at\":\"2025-12-12 14:20:05\"}', '2025-12-12 07:20:05', '2025-12-12 07:20:05'),
(48, 14, 13, 'updated', '{\"gioi_tinh\":\"Nam\",\"avatar_path\":null,\"updated_at\":\"2025-12-12T07:17:30.000000Z\"}', '{\"gioi_tinh\":\"nam\",\"avatar_path\":\"nv_avatar\\/SORI798EIccHzFfo0BTZLYX9IhXiD7kn88jXCOub.png\",\"updated_at\":\"2025-12-12 14:21:55\"}', '2025-12-12 07:21:55', '2025-12-12 07:21:55'),
(49, 11, 13, 'updated', '{\"gioi_tinh\":\"N\\u1eef\",\"avatar_path\":null,\"updated_at\":\"2025-12-12T07:17:46.000000Z\"}', '{\"gioi_tinh\":\"nu\",\"avatar_path\":\"nv_avatar\\/fbPQYtNRPoz4kUjPK9PLpXALB28b564CiTUAK5ic.png\",\"updated_at\":\"2025-12-12 14:22:14\"}', '2025-12-12 07:22:14', '2025-12-12 07:22:14'),
(51, 9, 13, 'updated', '{\"gioi_tinh\":\"N\\u1eef\",\"avatar_path\":null,\"updated_at\":\"2025-12-12T07:17:41.000000Z\"}', '{\"gioi_tinh\":\"nu\",\"avatar_path\":\"nv_avatar\\/gTy76mUjFUdwlSE4wuYBiYyjeJl7BKYbcR0AzfP3.png\",\"updated_at\":\"2025-12-12 14:23:32\"}', '2025-12-12 07:23:32', '2025-12-12 07:23:32'),
(52, 10, 13, 'updated', '{\"gioi_tinh\":\"N\\u1eef\",\"avatar_path\":null,\"updated_at\":\"2025-12-12T07:17:43.000000Z\"}', '{\"gioi_tinh\":\"nu\",\"avatar_path\":\"nv_avatar\\/J9L8WaUVEUXaH5U0T1yFHsYxH2zb3DuVJTfvUhOD.png\",\"updated_at\":\"2025-12-12 14:24:40\"}', '2025-12-12 07:24:40', '2025-12-12 07:24:40'),
(53, 8, 13, 'updated', '{\"gioi_tinh\":\"N\\u1eef\",\"avatar_path\":null,\"updated_at\":\"2025-12-12T07:17:38.000000Z\"}', '{\"gioi_tinh\":\"nu\",\"avatar_path\":\"nv_avatar\\/9mZ0E9DC2etpeikNnSxRKryaqtVKcRYDeQHR0YUi.png\",\"updated_at\":\"2025-12-12 14:24:50\"}', '2025-12-12 07:24:50', '2025-12-12 07:24:50'),
(55, 17, 13, 'created', NULL, '{\"ho_ten\":\"L\\u00ea Minh Nh\\u1eadt\",\"chuc_vu\":\"Tr\\u01b0\\u1edfng l\\u1ec5 t\\u00e2n\",\"so_dien_thoai\":\"0789654123\",\"email_cong_viec\":\"henvaemhen@gmail.com\",\"ngay_sinh\":\"2004-12-12\",\"gioi_tinh\":\"nam\",\"avatar_path\":\"nv_avatar\\/n53Ti8mAFEeIWZkE7fn214GxlPNQrBCHokZb2fpT.png\",\"user_id\":149,\"updated_at\":\"2025-12-13 11:13:14\",\"created_at\":\"2025-12-13 11:13:14\",\"id\":17}', '2025-12-13 04:13:14', '2025-12-13 04:13:14');

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
(1, 'Zuellig Pharma Vietnam', 'TP.HCM', '09091000', 'supplier1@vietcare.com', NULL, '2025-12-12 07:45:55', '2025-12-12 07:45:55'),
(2, 'DKSH Vietnam', 'TP.HCM', '09091001', 'supplier2@vietcare.com', NULL, '2025-12-12 07:45:55', '2025-12-12 07:45:55'),
(3, 'Dược Hậu Giang', 'TP.HCM', '09091002', 'supplier3@vietcare.com', NULL, '2025-12-12 07:45:55', '2025-12-12 07:45:55'),
(4, 'Traphaco', 'TP.HCM', '09091003', 'supplier4@vietcare.com', NULL, '2025-12-12 07:45:55', '2025-12-12 07:45:55'),
(5, 'Vinapharm', 'TP.HCM', '09091004', 'supplier5@vietcare.com', NULL, '2025-12-12 07:45:55', '2025-12-12 07:45:55'),
(6, 'Công ty Vật tư Y tế Sài Gòn', 'TP.HCM', '09091005', 'supplier6@vietcare.com', NULL, '2025-12-12 07:45:55', '2025-12-12 07:45:55'),
(7, 'Công ty Vật tư Y tế Hà Nội', 'TP.HCM', '09091006', 'supplier7@vietcare.com', NULL, '2025-12-12 07:45:55', '2025-12-12 07:45:55'),
(8, 'Công ty Vật tư Y tế Trung Tâm', 'TP.HCM', '09091007', 'supplier8@vietcare.com', NULL, '2025-12-12 07:45:55', '2025-12-12 07:45:55'),
(9, 'Hóa chất Roche Vietnam', 'TP.HCM', '09091008', 'supplier9@vietcare.com', NULL, '2025-12-12 07:45:55', '2025-12-12 07:45:55'),
(10, 'Hóa chất Abbott Vietnam', 'TP.HCM', '09091009', 'supplier10@vietcare.com', NULL, '2025-12-12 07:45:55', '2025-12-12 07:45:55'),
(11, 'Hóa chất Biomed', 'TP.HCM', '09091010', 'supplier11@vietcare.com', NULL, '2025-12-12 07:45:55', '2025-12-12 07:45:55'),
(12, 'Công ty Thiết bị Y tế MedTech', 'TP.HCM', '09091011', 'supplier12@vietcare.com', NULL, '2025-12-12 07:45:55', '2025-12-12 07:45:55'),
(13, 'Công ty Thiết bị Y tế VN', 'TP.HCM', '09091012', 'supplier13@vietcare.com', NULL, '2025-12-12 07:45:55', '2025-12-12 07:45:55'),
(14, 'Nhà thuốc An Bình', 'TP.HCM', '09091013', 'supplier14@vietcare.com', NULL, '2025-12-12 07:45:55', '2025-12-12 07:45:55'),
(15, 'Nhà thuốc Bảo An', 'TP.HCM', '09091014', 'supplier15@vietcare.com', NULL, '2025-12-12 07:45:55', '2025-12-12 07:45:55');

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
(1, 3, 1, 7667.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(2, 13, 1, 7667.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(3, 3, 2, 3200.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(4, 7, 2, 3200.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(5, 8, 3, 6465.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(6, 12, 3, 6465.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(7, 2, 4, 7862.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(8, 3, 4, 7862.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(9, 6, 5, 5528.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(10, 12, 5, 5528.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(11, 2, 6, 3571.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(12, 10, 6, 3571.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(13, 10, 7, 6489.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(14, 13, 7, 6489.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(15, 1, 8, 7117.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(16, 3, 8, 7117.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(17, 13, 9, 6708.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(18, 15, 9, 6708.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(19, 5, 10, 1598.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(20, 15, 10, 1598.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(21, 8, 11, 9072.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(22, 11, 11, 9072.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(23, 1, 12, 9481.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(24, 11, 12, 9481.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(25, 10, 13, 1030.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(26, 14, 13, 1030.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(27, 2, 14, 7461.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(28, 10, 14, 7461.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(29, 6, 15, 6157.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(30, 9, 15, 6157.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(31, 1, 16, 6234.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(32, 7, 16, 6234.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(33, 10, 17, 3962.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(34, 14, 17, 3962.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(35, 2, 18, 6982.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(36, 6, 18, 6982.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(37, 11, 19, 9644.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(38, 12, 19, 9644.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(39, 2, 20, 5052.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(40, 7, 20, 5052.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(41, 4, 21, 5239.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(42, 14, 21, 5239.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(43, 3, 22, 5354.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(44, 9, 22, 5354.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(45, 5, 23, 2578.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(46, 13, 23, 2578.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(47, 3, 24, 3062.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(48, 5, 24, 3062.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(49, 5, 25, 1300.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(50, 13, 25, 1300.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(51, 1, 26, 3924.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(52, 10, 26, 3924.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(53, 4, 27, 8390.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(54, 6, 27, 8390.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(55, 10, 28, 2206.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(56, 13, 28, 2206.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(57, 6, 29, 9768.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(58, 8, 29, 9768.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(59, 12, 30, 9944.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(60, 13, 30, 9944.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(61, 7, 31, 8731.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(62, 9, 31, 8731.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(63, 4, 32, 959.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(64, 13, 32, 959.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(65, 5, 33, 1488.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(66, 8, 33, 1488.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(67, 7, 34, 9179.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(68, 11, 34, 9179.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(69, 1, 35, 4240.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(70, 12, 35, 4240.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(71, 4, 36, 3190.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(72, 7, 36, 3190.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(73, 8, 37, 3196.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(74, 10, 37, 3196.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(75, 2, 38, 6611.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(76, 6, 38, 6611.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(77, 14, 39, 5243.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(78, 15, 39, 5243.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(79, 5, 40, 6217.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(80, 6, 40, 6217.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(81, 12, 41, 7252.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(82, 15, 41, 7252.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(83, 10, 42, 5690.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(84, 14, 42, 5690.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(85, 5, 43, 8403.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(86, 9, 43, 8403.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(87, 1, 44, 2050.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(88, 3, 44, 2050.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(89, 8, 45, 2187.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(90, 12, 45, 2187.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(91, 12, 46, 1050.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(92, 14, 46, 1050.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(93, 1, 47, 6567.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(94, 3, 47, 6567.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(95, 9, 48, 6053.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(96, 11, 48, 6053.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(97, 7, 49, 3148.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(98, 13, 49, 3148.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(99, 2, 50, 6798.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(100, 5, 50, 6798.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(101, 1, 51, 6756.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(102, 6, 51, 6756.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(103, 3, 52, 9295.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(104, 9, 52, 9295.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(105, 5, 53, 4944.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(106, 9, 53, 4944.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(107, 7, 54, 3934.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(108, 12, 54, 3934.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(109, 3, 55, 9585.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(110, 6, 55, 9585.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(111, 2, 56, 5804.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(112, 10, 56, 5804.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(113, 2, 57, 6439.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(114, 12, 57, 6439.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(115, 7, 58, 3038.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(116, 11, 58, 3038.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(117, 2, 59, 5224.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(118, 13, 59, 5224.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(119, 4, 60, 9771.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(120, 5, 60, 9771.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(121, 13, 61, 2949.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(122, 14, 61, 2949.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(123, 3, 62, 7553.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(124, 11, 62, 7553.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(125, 1, 63, 5602.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(126, 7, 63, 5602.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(127, 5, 64, 3184.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(128, 11, 64, 3184.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(129, 12, 65, 8332.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(130, 14, 65, 8332.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(131, 4, 66, 1790.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(132, 15, 66, 1790.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(133, 6, 67, 5299.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(134, 13, 67, 5299.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(135, 3, 68, 2799.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(136, 13, 68, 2799.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(137, 4, 69, 6768.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(138, 6, 69, 6768.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(139, 5, 70, 6674.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(140, 6, 70, 6674.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(141, 2, 71, 6674.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(142, 12, 71, 6674.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(143, 4, 72, 3772.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(144, 11, 72, 3772.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(145, 4, 73, 3514.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(146, 8, 73, 3514.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(147, 1, 74, 5860.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(148, 14, 74, 5860.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(149, 1, 75, 8276.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(150, 14, 75, 8276.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(151, 2, 76, 9989.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(152, 5, 76, 9989.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(153, 8, 77, 5089.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(154, 9, 77, 5089.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(155, 7, 78, 8962.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(156, 10, 78, 8962.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(157, 4, 79, 3629.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(158, 14, 79, 3629.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(159, 1, 80, 851.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(160, 13, 80, 851.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(161, 11, 81, 6564.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(162, 12, 81, 6564.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(163, 2, 82, 6019.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(164, 6, 82, 6019.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(165, 10, 83, 6541.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(166, 13, 83, 6541.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(167, 3, 84, 5297.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(168, 12, 84, 5297.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(169, 6, 85, 2719.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(170, 8, 85, 2719.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(171, 10, 86, 4013.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(172, 15, 86, 4013.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(173, 6, 87, 6564.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(174, 10, 87, 6564.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(175, 4, 88, 1104.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(176, 13, 88, 1104.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(177, 6, 89, 9142.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(178, 13, 89, 9142.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(179, 4, 90, 7835.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(180, 14, 90, 7835.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(181, 4, 91, 3773.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(182, 13, 91, 3773.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(183, 5, 92, 4838.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(184, 11, 92, 4838.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(185, 9, 93, 6906.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(186, 15, 93, 6906.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(187, 3, 94, 9401.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(188, 5, 94, 9401.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(189, 7, 95, 7119.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(190, 9, 95, 7119.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(191, 2, 96, 9637.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(192, 11, 96, 9637.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(193, 6, 97, 2754.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(194, 10, 97, 2754.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(195, 6, 98, 6470.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(196, 10, 98, 6470.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(197, 4, 99, 4746.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(198, 14, 99, 4746.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(199, 2, 100, 3319.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(200, 9, 100, 3319.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(201, 11, 101, 7843.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(202, 14, 101, 7843.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(203, 5, 102, 7796.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(204, 8, 102, 7796.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(205, 8, 103, 1597.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(206, 11, 103, 1597.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(207, 8, 104, 5894.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(208, 13, 104, 5894.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(209, 8, 105, 2441.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(210, 13, 105, 2441.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(211, 5, 106, 6487.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(212, 12, 106, 6487.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(213, 2, 107, 4069.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(214, 15, 107, 4069.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(215, 1, 108, 3780.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(216, 4, 108, 3780.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(217, 7, 109, 9516.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(218, 13, 109, 9516.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(219, 5, 110, 1709.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(220, 8, 110, 1709.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(221, 11, 111, 2557.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(222, 13, 111, 2557.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(223, 3, 112, 8338.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(224, 6, 112, 8338.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(225, 13, 113, 9291.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(226, 14, 113, 9291.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(227, 3, 114, 6872.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(228, 6, 114, 6872.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(229, 4, 115, 2983.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(230, 9, 115, 2983.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(231, 9, 116, 1346.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(232, 11, 116, 1346.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(233, 4, 117, 6841.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(234, 14, 117, 6841.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(235, 3, 118, 2312.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(236, 15, 118, 2312.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(237, 10, 119, 2896.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(238, 14, 119, 2896.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(239, 6, 120, 4603.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(240, 7, 120, 4603.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(241, 6, 121, 1347.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(242, 7, 121, 1347.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(243, 3, 122, 6440.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(244, 13, 122, 6440.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(245, 5, 123, 4290.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(246, 12, 123, 4290.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(247, 7, 124, 8981.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(248, 8, 124, 8981.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(249, 6, 125, 3597.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(250, 7, 125, 3597.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(251, 2, 126, 7060.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(252, 6, 126, 7060.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(253, 1, 127, 1123.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(254, 9, 127, 1123.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(255, 4, 128, 879.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(256, 11, 128, 879.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(257, 6, 129, 8561.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(258, 13, 129, 8561.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(259, 11, 130, 5083.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(260, 14, 130, 5083.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(261, 8, 131, 1200.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(262, 13, 131, 1200.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(263, 4, 132, 9527.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(264, 13, 132, 9527.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(265, 3, 133, 9464.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(266, 13, 133, 9464.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(267, 8, 134, 2136.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(268, 15, 134, 2136.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(269, 6, 135, 4252.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(270, 15, 135, 4252.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(271, 3, 136, 8208.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(272, 14, 136, 8208.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(273, 10, 137, 4857.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(274, 14, 137, 4857.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(275, 4, 138, 9750.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(276, 5, 138, 9750.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(277, 5, 139, 7884.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(278, 9, 139, 7884.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(279, 1, 140, 8061.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(280, 8, 140, 8061.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(281, 8, 141, 1672.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(282, 10, 141, 1672.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(283, 4, 142, 7425.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(284, 10, 142, 7425.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(285, 1, 143, 7891.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(286, 15, 143, 7891.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(287, 10, 144, 7222.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(288, 13, 144, 7222.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(289, 2, 145, 4047.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(290, 11, 145, 4047.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(291, 5, 146, 1563.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(292, 13, 146, 1563.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(293, 10, 147, 7835.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(294, 11, 147, 7835.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(295, 4, 148, 8710.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(296, 14, 148, 8710.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(297, 10, 149, 7338.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(298, 15, 149, 7338.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(299, 5, 150, 6112.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(300, 6, 150, 6112.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(301, 6, 151, 15432.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(302, 8, 151, 15432.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(303, 7, 152, 5359.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(304, 10, 152, 5359.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(305, 1, 153, 21814.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(306, 5, 153, 21814.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(307, 4, 154, 22269.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(308, 8, 154, 22269.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(309, 10, 155, 30009.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(310, 15, 155, 30009.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(311, 4, 156, 2710.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(312, 13, 156, 2710.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(313, 4, 157, 24569.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(314, 8, 157, 24569.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(315, 8, 158, 15917.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(316, 13, 158, 15917.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(317, 3, 159, 45799.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(318, 7, 159, 45799.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(319, 9, 160, 46495.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(320, 15, 160, 46495.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(321, 2, 161, 8485.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(322, 7, 161, 8485.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(323, 1, 162, 49316.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(324, 8, 162, 49316.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(325, 11, 163, 38888.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(326, 14, 163, 38888.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(327, 3, 164, 32901.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(328, 5, 164, 32901.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(329, 13, 165, 2179.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(330, 14, 165, 2179.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(331, 9, 166, 10945.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(332, 12, 166, 10945.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(333, 10, 167, 10571.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(334, 13, 167, 10571.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(335, 4, 168, 9274.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(336, 6, 168, 9274.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(337, 8, 169, 3380.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(338, 11, 169, 3380.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(339, 10, 170, 13880.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(340, 11, 170, 13880.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(341, 3, 171, 22817.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(342, 10, 171, 22817.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(343, 4, 172, 15638.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(344, 14, 172, 15638.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(345, 9, 173, 11919.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(346, 14, 173, 11919.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(347, 1, 174, 18629.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(348, 15, 174, 18629.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(349, 3, 175, 31951.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(350, 10, 175, 31951.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(351, 2, 176, 47634.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(352, 6, 176, 47634.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(353, 4, 177, 13647.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(354, 8, 177, 13647.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(355, 6, 178, 24717.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(356, 11, 178, 24717.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(357, 1, 179, 13303.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(358, 2, 179, 13303.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(359, 4, 180, 39824.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(360, 13, 180, 39824.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(361, 1, 181, 9380.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(362, 9, 181, 9380.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(363, 4, 182, 32782.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(364, 5, 182, 32782.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(365, 5, 183, 42087.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(366, 13, 183, 42087.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(367, 5, 184, 33893.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(368, 7, 184, 33893.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(369, 3, 185, 44973.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(370, 13, 185, 44973.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(371, 5, 186, 10977.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(372, 10, 186, 10977.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(373, 4, 187, 20835.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(374, 8, 187, 20835.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(375, 5, 188, 48519.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(376, 10, 188, 48519.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(377, 11, 189, 46645.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(378, 13, 189, 46645.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(379, 11, 190, 21991.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(380, 13, 190, 21991.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(381, 6, 191, 35119.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(382, 8, 191, 35119.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(383, 6, 192, 36222.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(384, 14, 192, 36222.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(385, 5, 193, 4718.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(386, 11, 193, 4718.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(387, 2, 194, 34879.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(388, 10, 194, 34879.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(389, 9, 195, 24175.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(390, 10, 195, 24175.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(391, 3, 196, 4075.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(392, 6, 196, 4075.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(393, 5, 197, 9151.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(394, 9, 197, 9151.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(395, 5, 198, 21269.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(396, 12, 198, 21269.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(397, 12, 199, 29765.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(398, 13, 199, 29765.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(399, 10, 200, 49482.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(400, 11, 200, 49482.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(401, 8, 1, 6799.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(402, 9, 1, 6799.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(403, 8, 2, 3144.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(404, 1, 3, 8858.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(405, 14, 3, 8858.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(406, 4, 4, 7111.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(407, 11, 4, 7111.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(408, 3, 5, 4240.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(409, 9, 5, 4240.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(410, 5, 6, 6736.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(411, 2, 7, 9986.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(412, 11, 7, 9986.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(413, 6, 8, 1855.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(414, 8, 8, 1855.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(415, 1, 9, 1744.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(416, 11, 9, 1744.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(417, 3, 10, 1585.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(418, 8, 10, 1585.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(419, 1, 11, 4696.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(420, 14, 11, 4696.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(421, 6, 12, 4789.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(422, 12, 12, 4789.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(423, 7, 13, 4943.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(424, 9, 13, 4943.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(425, 3, 14, 5675.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(426, 12, 14, 5675.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(427, 1, 15, 8143.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(428, 14, 15, 8143.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(429, 2, 16, 4177.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(430, 10, 16, 4177.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(431, 8, 18, 9503.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(432, 13, 18, 9503.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(433, 2, 19, 2104.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(434, 9, 20, 1147.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(435, 11, 20, 1147.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(436, 8, 21, 6592.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(437, 12, 22, 3887.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(438, 12, 23, 5633.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(439, 14, 23, 5633.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(440, 12, 24, 7852.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(441, 6, 25, 5875.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(442, 9, 25, 5875.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(443, 15, 26, 3396.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(444, 1, 27, 4819.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(445, 2, 27, 4819.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(446, 9, 28, 3256.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(447, 15, 28, 3256.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(448, 11, 29, 9760.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(449, 1, 30, 9462.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(450, 3, 30, 9462.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(451, 12, 31, 3125.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(452, 15, 31, 3125.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(453, 7, 32, 4606.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(454, 10, 32, 4606.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(455, 3, 33, 5824.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(456, 14, 33, 5824.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(457, 6, 34, 1572.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(458, 8, 34, 1572.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(459, 6, 35, 2198.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(460, 7, 35, 2198.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(461, 6, 36, 7880.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(462, 12, 36, 7880.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(463, 3, 37, 9457.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(464, 9, 37, 9457.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(465, 14, 38, 6978.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(466, 12, 39, 6270.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(467, 13, 39, 6270.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(468, 2, 40, 4843.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(469, 8, 40, 4843.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(470, 6, 41, 6869.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(471, 11, 42, 6823.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(472, 13, 42, 6823.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(473, 1, 43, 9875.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(474, 12, 43, 9875.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(475, 11, 44, 4866.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(476, 12, 44, 4866.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(477, 11, 45, 5327.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(478, 15, 45, 5327.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(479, 1, 46, 9413.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(480, 13, 47, 4455.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(481, 1, 48, 9801.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(482, 15, 48, 9801.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(483, 4, 49, 3257.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(484, 5, 49, 3257.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(485, 7, 50, 1753.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(486, 10, 51, 6168.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(487, 12, 51, 6168.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(488, 10, 52, 1876.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(489, 14, 52, 1876.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(490, 4, 53, 4569.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(491, 13, 53, 4569.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(492, 6, 54, 6654.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(493, 15, 54, 6654.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(494, 12, 55, 5440.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(495, 3, 56, 2294.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(496, 6, 56, 2294.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(497, 5, 57, 4464.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(498, 9, 57, 4464.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(499, 6, 58, 6275.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(500, 7, 59, 4550.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(501, 9, 59, 4550.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(502, 13, 60, 1957.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(503, 15, 60, 1957.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(504, 4, 61, 4697.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(505, 12, 61, 4697.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(506, 10, 62, 7479.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(507, 14, 62, 7479.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(508, 4, 63, 2267.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(509, 11, 63, 2267.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(510, 7, 64, 4399.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(511, 14, 64, 4399.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(512, 3, 65, 2478.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(513, 5, 65, 2478.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(514, 13, 66, 3637.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(515, 2, 67, 9238.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(516, 12, 67, 9238.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(517, 2, 68, 3636.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(518, 8, 68, 3636.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(519, 7, 69, 7124.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(520, 12, 69, 7124.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(521, 8, 70, 1828.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(522, 9, 70, 1828.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(523, 6, 71, 1728.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(524, 10, 71, 1728.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(525, 8, 72, 4795.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(526, 14, 72, 4795.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(527, 9, 73, 6075.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(528, 15, 73, 6075.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(529, 9, 74, 1330.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(530, 11, 74, 1330.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(531, 6, 75, 7633.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(532, 10, 75, 7633.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(533, 4, 76, 7680.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(534, 11, 76, 7680.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(535, 1, 77, 9155.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(536, 7, 77, 9155.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(537, 1, 78, 6667.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(538, 12, 78, 6667.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(539, 13, 79, 8741.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(540, 3, 80, 3405.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(541, 15, 80, 3405.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(542, 5, 81, 4377.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(543, 9, 81, 4377.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(544, 4, 82, 2905.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(545, 11, 82, 2905.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(546, 14, 83, 8396.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(547, 6, 84, 5915.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(548, 2, 85, 3547.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(549, 12, 85, 3547.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(550, 1, 86, 5234.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(551, 12, 87, 9784.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(552, 14, 87, 9784.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(553, 1, 88, 4413.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(554, 6, 88, 4413.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(555, 7, 89, 4449.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(556, 9, 89, 4449.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(557, 2, 90, 3402.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(558, 11, 91, 9584.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(559, 12, 92, 8942.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(560, 15, 92, 8942.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(561, 5, 93, 8442.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(562, 2, 94, 3577.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(563, 3, 95, 5414.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(564, 4, 95, 5414.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(565, 13, 96, 6832.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(566, 12, 97, 8093.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(567, 1, 98, 4636.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(568, 14, 98, 4636.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(569, 2, 99, 4323.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(570, 9, 99, 4323.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(571, 4, 100, 2303.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(572, 6, 100, 2303.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(573, 3, 101, 1866.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(574, 10, 101, 1866.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(575, 3, 102, 4440.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(576, 4, 102, 4440.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(577, 5, 103, 2031.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(578, 7, 103, 2031.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(579, 6, 104, 4324.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(580, 9, 104, 4324.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(581, 6, 105, 8718.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(582, 7, 105, 8718.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(583, 7, 106, 5178.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(584, 14, 106, 5178.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(585, 8, 107, 3106.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(586, 11, 107, 3106.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(587, 5, 108, 9025.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(588, 8, 108, 9025.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(589, 4, 109, 4095.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(590, 15, 109, 4095.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(591, 1, 110, 6250.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(592, 15, 110, 6250.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(593, 12, 111, 9836.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(594, 4, 112, 6877.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(595, 3, 113, 1973.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(596, 10, 113, 1973.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(597, 1, 114, 8095.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(598, 5, 114, 8095.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(599, 2, 115, 7101.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(600, 3, 116, 9795.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(601, 15, 116, 9795.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(602, 7, 117, 6578.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(603, 11, 117, 6578.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(604, 5, 118, 5619.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(605, 6, 118, 5619.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(606, 8, 119, 3645.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(607, 9, 120, 5363.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(608, 13, 120, 5363.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(609, 4, 121, 1845.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(610, 11, 121, 1845.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(611, 10, 122, 3720.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(612, 14, 122, 3720.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(613, 4, 123, 6386.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(614, 8, 123, 6386.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(615, 3, 124, 9755.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(616, 9, 124, 9755.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(617, 1, 125, 3468.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(618, 13, 125, 3468.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(619, 3, 126, 4600.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(620, 7, 126, 4600.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(621, 3, 127, 5918.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(622, 5, 127, 5918.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(623, 1, 128, 9169.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(624, 5, 128, 9169.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(625, 4, 130, 8440.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(626, 7, 130, 8440.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(627, 15, 131, 5887.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(628, 14, 132, 3420.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(629, 15, 132, 3420.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(630, 5, 133, 811.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(631, 7, 133, 811.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(632, 4, 134, 4789.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(633, 10, 134, 4789.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(634, 3, 135, 4221.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(635, 7, 135, 4221.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(636, 6, 136, 6124.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(637, 9, 136, 6124.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(638, 1, 137, 4118.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(639, 9, 137, 4118.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(640, 8, 138, 9498.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(641, 9, 138, 9498.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(642, 6, 139, 6094.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(643, 13, 139, 6094.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(644, 3, 140, 9584.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(645, 12, 140, 9584.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(646, 1, 141, 4397.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(647, 2, 141, 4397.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(648, 14, 142, 4240.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(649, 6, 143, 9831.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(650, 7, 143, 9831.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(651, 5, 144, 1441.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(652, 8, 144, 1441.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(653, 3, 145, 9479.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(654, 8, 145, 9479.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(655, 8, 146, 9128.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(656, 10, 146, 9128.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(657, 13, 147, 3549.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(658, 12, 148, 4537.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(659, 2, 149, 7562.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(660, 14, 149, 7562.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(661, 2, 150, 9952.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(662, 15, 150, 9952.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(663, 4, 151, 13591.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(664, 4, 152, 16683.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(665, 8, 153, 41995.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(666, 14, 153, 41995.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(667, 5, 154, 23842.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(668, 13, 155, 28481.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(669, 2, 156, 3890.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(670, 3, 156, 3890.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(671, 2, 157, 10046.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(672, 13, 157, 10046.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(673, 2, 158, 13507.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(674, 10, 159, 30650.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(675, 7, 160, 7925.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(676, 14, 160, 7925.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(677, 6, 161, 47151.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(678, 9, 161, 47151.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(679, 4, 162, 40101.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(680, 6, 162, 40101.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(681, 2, 163, 18144.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(682, 4, 164, 20553.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(683, 7, 164, 20553.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(684, 10, 165, 6553.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(685, 11, 165, 6553.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(686, 5, 166, 35693.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(687, 6, 167, 31993.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(688, 9, 167, 31993.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(689, 5, 168, 5367.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(690, 15, 168, 5367.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(691, 9, 169, 37178.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(692, 15, 169, 37178.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(693, 6, 170, 11783.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(694, 13, 170, 11783.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(695, 4, 171, 37301.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(696, 11, 171, 37301.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(697, 6, 172, 34936.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(698, 11, 172, 34936.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(699, 10, 173, 14093.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(700, 2, 174, 35695.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(701, 6, 175, 10461.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(702, 14, 175, 10461.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(703, 9, 176, 12544.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(704, 9, 177, 28295.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(705, 15, 177, 28295.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(706, 1, 178, 43893.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(707, 14, 178, 43893.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(708, 6, 179, 8745.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(709, 7, 179, 8745.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(710, 1, 180, 37844.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(711, 2, 180, 37844.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(712, 4, 181, 45831.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(713, 12, 181, 45831.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(714, 8, 182, 49252.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(715, 13, 182, 49252.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(716, 1, 183, 6474.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(717, 10, 183, 6474.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(718, 10, 184, 12473.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(719, 13, 184, 12473.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(720, 6, 185, 7835.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(721, 10, 185, 7835.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(722, 2, 186, 4903.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(723, 9, 186, 4903.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(724, 1, 187, 39002.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(725, 6, 188, 33296.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(726, 15, 188, 33296.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(727, 7, 189, 47332.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(728, 9, 189, 47332.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(729, 6, 190, 10994.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(730, 7, 190, 10994.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(731, 7, 191, 41881.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(732, 13, 191, 41881.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(733, 7, 192, 27750.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(734, 12, 192, 27750.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15');
INSERT INTO `nha_cung_cap_thuoc` (`id`, `nha_cung_cap_id`, `thuoc_id`, `gia_nhap_mac_dinh`, `created_at`, `updated_at`) VALUES
(735, 2, 193, 27779.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(736, 6, 193, 27779.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(737, 7, 194, 45932.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(738, 11, 194, 45932.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(739, 5, 195, 42004.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(740, 6, 195, 42004.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(741, 8, 196, 29372.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(742, 4, 197, 28146.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(743, 12, 197, 28146.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(744, 7, 198, 6496.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(745, 10, 198, 6496.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(746, 1, 199, 37354.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(747, 4, 200, 12190.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15');

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
(1, 3, 'VNPAY', 'request', NULL, NULL, NULL, NULL, '{\"vnp_Version\":\"2.1.0\",\"vnp_TmnCode\":\"I2U8WK7F\",\"vnp_Amount\":225000000,\"vnp_Command\":\"pay\",\"vnp_CreateDate\":\"20251213150105\",\"vnp_CurrCode\":\"VND\",\"vnp_IpAddr\":\"127.0.0.1\",\"vnp_Locale\":\"vn\",\"vnp_OrderInfo\":\"Thanh toan hoa don #3\",\"vnp_OrderType\":\"billpayment\",\"vnp_ReturnUrl\":\"http:\\/\\/127.0.0.1:8000\\/payment\\/vnpay-return\",\"vnp_TxnRef\":\"3\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', '2025-12-13 08:01:05', '2025-12-13 08:01:05'),
(2, 3, 'VNPAY', 'return', NULL, 'VNP15338816', '00', NULL, '{\"vnp_Amount\":\"225000000\",\"vnp_BankCode\":\"NCB\",\"vnp_BankTranNo\":\"VNP15338816\",\"vnp_CardType\":\"ATM\",\"vnp_OrderInfo\":\"Thanh toan hoa don #3\",\"vnp_PayDate\":\"20251213150151\",\"vnp_ResponseCode\":\"00\",\"vnp_TmnCode\":\"I2U8WK7F\",\"vnp_TransactionNo\":\"15338816\",\"vnp_TransactionStatus\":\"00\",\"vnp_TxnRef\":\"3\",\"vnp_SecureHash\":\"69d8889d1916e141bb5589f9d4e4f93a171cd2da47462a1e57304c1f93277db74617b8c1bf387dad2b70e6e65e7415404eb2e888e375d215439f4c41b0f7001f\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', '2025-12-13 08:02:04', '2025-12-13 08:02:04');

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
(1, 'view-users', 'web', '2025-12-12 04:21:30', '2025-12-12 04:21:30'),
(2, 'create-users', 'web', '2025-12-12 04:21:30', '2025-12-12 04:21:30'),
(3, 'edit-users', 'web', '2025-12-12 04:21:30', '2025-12-12 04:21:30'),
(4, 'delete-users', 'web', '2025-12-12 04:21:30', '2025-12-12 04:21:30'),
(5, 'lock-users', 'web', '2025-12-12 04:21:30', '2025-12-12 04:21:30'),
(6, 'unlock-users', 'web', '2025-12-12 04:21:30', '2025-12-12 04:21:30'),
(7, 'assign-roles', 'web', '2025-12-12 04:21:30', '2025-12-12 04:21:30'),
(8, 'view-roles', 'web', '2025-12-12 04:21:30', '2025-12-12 04:21:30'),
(9, 'create-roles', 'web', '2025-12-12 04:21:30', '2025-12-12 04:21:30'),
(10, 'edit-roles', 'web', '2025-12-12 04:21:30', '2025-12-12 04:21:30'),
(11, 'delete-roles', 'web', '2025-12-12 04:21:30', '2025-12-12 04:21:30'),
(12, 'view-permissions', 'web', '2025-12-12 04:21:30', '2025-12-12 04:21:30'),
(13, 'create-permissions', 'web', '2025-12-12 04:21:30', '2025-12-12 04:21:30'),
(14, 'delete-permissions', 'web', '2025-12-12 04:21:30', '2025-12-12 04:21:30'),
(15, 'view-doctors', 'web', '2025-12-12 04:21:30', '2025-12-12 04:21:30'),
(16, 'create-doctors', 'web', '2025-12-12 04:21:30', '2025-12-12 04:21:30'),
(17, 'edit-doctors', 'web', '2025-12-12 04:21:30', '2025-12-12 04:21:30'),
(18, 'delete-doctors', 'web', '2025-12-12 04:21:30', '2025-12-12 04:21:30'),
(19, 'manage-schedules', 'web', '2025-12-12 04:21:30', '2025-12-12 04:21:30'),
(20, 'view-appointments', 'web', '2025-12-12 04:21:30', '2025-12-12 04:21:30'),
(21, 'create-appointments', 'web', '2025-12-12 04:21:30', '2025-12-12 04:21:30'),
(22, 'edit-appointments', 'web', '2025-12-12 04:21:30', '2025-12-12 04:21:30'),
(23, 'cancel-appointments', 'web', '2025-12-12 04:21:30', '2025-12-12 04:21:30'),
(24, 'confirm-appointments', 'web', '2025-12-12 04:21:30', '2025-12-12 04:21:30'),
(25, 'view-medical-records', 'web', '2025-12-12 04:21:30', '2025-12-12 04:21:30'),
(26, 'create-medical-records', 'web', '2025-12-12 04:21:30', '2025-12-12 04:21:30'),
(27, 'edit-medical-records', 'web', '2025-12-12 04:21:30', '2025-12-12 04:21:30'),
(28, 'delete-medical-records', 'web', '2025-12-12 04:21:30', '2025-12-12 04:21:30'),
(29, 'view-services', 'web', '2025-12-12 04:21:30', '2025-12-12 04:21:30'),
(30, 'create-services', 'web', '2025-12-12 04:21:30', '2025-12-12 04:21:30'),
(31, 'edit-services', 'web', '2025-12-12 04:21:30', '2025-12-12 04:21:30'),
(32, 'delete-services', 'web', '2025-12-12 04:21:30', '2025-12-12 04:21:30'),
(33, 'view-medicines', 'web', '2025-12-12 04:21:30', '2025-12-12 04:21:30'),
(34, 'create-medicines', 'web', '2025-12-12 04:21:30', '2025-12-12 04:21:30'),
(35, 'edit-medicines', 'web', '2025-12-12 04:21:30', '2025-12-12 04:21:30'),
(36, 'delete-medicines', 'web', '2025-12-12 04:21:30', '2025-12-12 04:21:30'),
(37, 'manage-inventory', 'web', '2025-12-12 04:21:30', '2025-12-12 04:21:30'),
(38, 'view-inventory-reports', 'web', '2025-12-12 04:21:30', '2025-12-12 04:21:30'),
(39, 'view-staff', 'web', '2025-12-12 04:21:30', '2025-12-12 04:21:30'),
(40, 'create-staff', 'web', '2025-12-12 04:21:30', '2025-12-12 04:21:30'),
(41, 'edit-staff', 'web', '2025-12-12 04:21:30', '2025-12-12 04:21:30'),
(42, 'delete-staff', 'web', '2025-12-12 04:21:30', '2025-12-12 04:21:30'),
(43, 'view-staff-shifts', 'web', '2025-12-12 04:21:30', '2025-12-12 04:21:30'),
(44, 'assign-staff-shifts', 'web', '2025-12-12 04:21:30', '2025-12-12 04:21:30'),
(45, 'view-invoices', 'web', '2025-12-12 04:21:30', '2025-12-12 04:21:30'),
(46, 'create-invoices', 'web', '2025-12-12 04:21:30', '2025-12-12 04:21:30'),
(47, 'edit-invoices', 'web', '2025-12-12 04:21:30', '2025-12-12 04:21:30'),
(48, 'delete-invoices', 'web', '2025-12-12 04:21:30', '2025-12-12 04:21:30'),
(49, 'process-payments', 'web', '2025-12-12 04:21:30', '2025-12-12 04:21:30'),
(50, 'refund-payments', 'web', '2025-12-12 04:21:30', '2025-12-12 04:21:30'),
(51, 'view-payment-logs', 'web', '2025-12-12 04:21:30', '2025-12-12 04:21:30'),
(52, 'view-reports', 'web', '2025-12-12 04:21:30', '2025-12-12 04:21:30'),
(53, 'view-revenue-reports', 'web', '2025-12-12 04:21:30', '2025-12-12 04:21:30'),
(54, 'view-appointment-reports', 'web', '2025-12-12 04:21:30', '2025-12-12 04:21:30'),
(55, 'export-data', 'web', '2025-12-12 04:21:30', '2025-12-12 04:21:30'),
(56, 'view-dashboard', 'web', '2025-12-12 04:35:40', '2025-12-12 04:35:40');

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
(1, 'PN-SEED-1', '2025-11-27', 8, NULL, 8986582.00, 'Seed PN #1', '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(2, 'PN-SEED-2', '2025-10-16', 10, NULL, 21936287.00, 'Seed PN #2', '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(3, 'PN-SEED-3', '2025-10-03', 5, NULL, 7745288.00, 'Seed PN #3', '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(4, 'PN-SEED-4', '2025-10-28', 1, NULL, 10448265.00, 'Seed PN #4', '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(5, 'PN-SEED-5', '2025-10-12', 13, NULL, 7426712.00, 'Seed PN #5', '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(6, 'PN-SEED-6', '2025-12-04', 12, NULL, 7505743.00, 'Seed PN #6', '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(7, 'PN-SEED-7', '2025-09-19', 1, NULL, 27822630.00, 'Seed PN #7', '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(8, 'PN-SEED-8', '2025-11-01', 10, NULL, 18581538.00, 'Seed PN #8', '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(9, 'PN-SEED-9', '2025-10-25', 5, NULL, 17205230.00, 'Seed PN #9', '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(10, 'PN-SEED-10', '2025-11-25', 1, NULL, 11362290.00, 'Seed PN #10', '2025-12-12 07:45:56', '2025-12-12 07:50:15');

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
(1, 1, 89, 'LOT-PN-1-267', '2027-06-12', 411, 1041.00, 427851.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(2, 1, 98, 'LOT-PN-1-855', '2026-08-12', 380, 5895.00, 2240100.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(3, 1, 192, 'LOT-PN-1-262', '2028-09-12', 245, 4791.00, 1173795.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(4, 1, 105, 'LOT-PN-1-617', '2026-12-12', 114, 3785.00, 431490.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(5, 1, 3, 'LOT-PN-1-516', '2027-04-12', 24, 1936.00, 46464.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(6, 1, 191, 'LOT-PN-1-662', '2027-06-12', 210, 7676.00, 1611960.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(7, 1, 157, 'LOT-PN-1-925', '2028-06-12', 460, 3160.00, 1453600.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(8, 1, 174, 'LOT-PN-1-841', '2027-09-12', 54, 1377.00, 74358.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(9, 1, 162, 'LOT-PN-1-85', '2026-08-12', 355, 4482.00, 1591110.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(10, 1, 12, 'LOT-PN-1-320', '2027-04-12', 363, 9030.00, 3277890.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(11, 1, 187, 'LOT-PN-1-866', '2027-07-12', 276, 6914.00, 1908264.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(12, 1, 80, 'LOT-PN-1-877', '2027-10-12', 82, 7346.00, 602372.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(13, 2, 183, 'LOT-PN-2-409', '2028-04-12', 68, 7057.00, 479876.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(14, 2, 117, 'LOT-PN-2-910', '2027-05-12', 65, 9108.00, 592020.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(15, 2, 188, 'LOT-PN-2-204', '2028-10-12', 371, 2811.00, 1042881.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(16, 2, 41, 'LOT-PN-2-526', '2027-08-12', 186, 9117.00, 1695762.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(17, 2, 139, 'LOT-PN-2-856', '2028-09-12', 464, 9437.00, 4378768.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(18, 2, 177, 'LOT-PN-2-84', '2028-09-12', 360, 6127.00, 2205720.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(19, 2, 174, 'LOT-PN-2-956', '2026-06-12', 224, 3813.00, 854112.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(20, 2, 163, 'LOT-PN-2-567', '2026-10-12', 483, 6503.00, 3140949.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(21, 2, 6, 'LOT-PN-2-475', '2028-07-12', 450, 5412.00, 2435400.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(22, 2, 168, 'LOT-PN-2-858', '2027-11-12', 146, 6734.00, 983164.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(23, 2, 110, 'LOT-PN-2-714', '2028-09-12', 224, 1401.00, 313824.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(24, 2, 180, 'LOT-PN-2-806', '2027-11-12', 150, 3055.00, 458250.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(25, 2, 44, 'LOT-PN-2-503', '2026-07-12', 447, 1429.00, 638763.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(26, 3, 35, 'LOT-PN-3-555', '2027-05-12', 150, 1554.00, 233100.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(27, 3, 4, 'LOT-PN-3-972', '2028-05-12', 256, 5425.00, 1388800.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(28, 3, 128, 'LOT-PN-3-850', '2028-12-12', 239, 6622.00, 1582658.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(29, 3, 8, 'LOT-PN-3-500', '2026-09-12', 70, 7531.00, 527170.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(30, 3, 107, 'LOT-PN-3-807', '2028-11-12', 293, 9358.00, 2741894.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(31, 3, 142, 'LOT-PN-3-896', '2027-02-12', 414, 7792.00, 3225888.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(32, 3, 81, 'LOT-PN-3-787', '2028-09-12', 445, 8030.00, 3573350.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(33, 3, 78, 'LOT-PN-3-307', '2027-03-12', 380, 9116.00, 3464080.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(34, 3, 80, 'LOT-PN-3-741', '2026-09-12', 375, 827.00, 310125.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(35, 3, 17, 'LOT-PN-3-812', '2028-01-12', 381, 862.00, 328422.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(36, 3, 73, 'LOT-PN-3-688', '2028-12-12', 117, 8159.00, 954603.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(37, 3, 98, 'LOT-PN-3-911', '2028-07-12', 270, 8328.00, 2248560.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(38, 4, 125, 'LOT-PN-4-484', '2027-11-12', 296, 9504.00, 2813184.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(39, 4, 181, 'LOT-PN-4-971', '2027-07-12', 411, 7429.00, 3053319.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(40, 4, 87, 'LOT-PN-4-398', '2027-03-12', 450, 5620.00, 2529000.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(41, 4, 166, 'LOT-PN-4-209', '2026-12-12', 483, 9326.00, 4504458.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(42, 4, 172, 'LOT-PN-4-544', '2028-06-12', 250, 4102.00, 1025500.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(43, 4, 64, 'LOT-PN-4-307', '2028-11-12', 200, 7964.00, 1592800.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(44, 4, 23, 'LOT-PN-4-97', '2027-05-12', 215, 3359.00, 722185.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(45, 4, 117, 'LOT-PN-4-980', '2028-08-12', 381, 6614.00, 2519934.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(46, 4, 127, 'LOT-PN-4-230', '2027-07-12', 32, 4530.00, 144960.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(47, 4, 183, 'LOT-PN-4-254', '2027-08-12', 270, 9587.00, 2588490.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(48, 4, 108, 'LOT-PN-4-741', '2026-12-12', 341, 5446.00, 1857086.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(49, 4, 40, 'LOT-PN-4-769', '2028-01-12', 185, 2568.00, 475080.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(50, 5, 161, 'LOT-PN-5-970', '2027-01-12', 74, 3359.00, 248566.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(51, 5, 141, 'LOT-PN-5-219', '2026-12-12', 271, 8679.00, 2352009.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(52, 5, 168, 'LOT-PN-5-80', '2027-05-12', 390, 2406.00, 938340.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(53, 5, 95, 'LOT-PN-5-72', '2026-08-12', 439, 2257.00, 990823.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(54, 5, 80, 'LOT-PN-5-873', '2026-09-12', 294, 7192.00, 2114448.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(55, 5, 23, 'LOT-PN-5-6', '2027-12-12', 129, 3361.00, 433569.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(56, 5, 91, 'LOT-PN-5-561', '2028-01-12', 191, 2299.00, 439109.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(57, 5, 3, 'LOT-PN-5-184', '2028-06-12', 305, 3417.00, 1042185.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(58, 5, 63, 'LOT-PN-5-188', '2027-01-12', 265, 7428.00, 1968420.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(59, 5, 166, 'LOT-PN-5-12', '2026-11-12', 166, 4412.00, 732392.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(60, 5, 138, 'LOT-PN-5-557', '2026-07-12', 161, 1139.00, 183379.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(61, 5, 103, 'LOT-PN-5-543', '2026-08-12', 281, 5778.00, 1623618.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(62, 5, 163, 'LOT-PN-5-8', '2026-06-12', 423, 6183.00, 2615409.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(63, 5, 104, 'LOT-PN-5-347', '2028-05-12', 322, 6413.00, 2064986.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(64, 5, 24, 'LOT-PN-5-167', '2028-08-12', 303, 8676.00, 2628828.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(65, 6, 133, 'LOT-PN-6-569', '2028-08-12', 41, 1293.00, 53013.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(66, 6, 18, 'LOT-PN-6-614', '2028-11-12', 57, 5281.00, 301017.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(67, 6, 115, 'LOT-PN-6-373', '2027-05-12', 315, 7642.00, 2407230.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(68, 6, 55, 'LOT-PN-6-49', '2027-01-12', 268, 7660.00, 2052880.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(69, 6, 119, 'LOT-PN-6-295', '2026-07-12', 264, 6228.00, 1644192.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(70, 6, 30, 'LOT-PN-6-854', '2028-11-12', 124, 4526.00, 561224.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(71, 6, 7, 'LOT-PN-6-650', '2027-02-12', 437, 6746.00, 2948002.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(72, 6, 93, 'LOT-PN-6-782', '2027-04-12', 289, 8303.00, 2399567.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(73, 7, 3, 'LOT-PN-7-607', '2028-02-12', 259, 9186.00, 2379174.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(74, 7, 126, 'LOT-PN-7-122', '2028-08-12', 37, 5775.00, 213675.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(75, 7, 43, 'LOT-PN-7-237', '2026-11-12', 149, 5042.00, 751258.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(76, 7, 29, 'LOT-PN-7-574', '2027-08-12', 364, 4800.00, 1747200.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(77, 7, 199, 'LOT-PN-7-474', '2028-02-12', 301, 615.00, 185115.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(78, 7, 4, 'LOT-PN-7-283', '2027-10-12', 471, 4115.00, 1938165.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(79, 8, 154, 'LOT-PN-8-941', '2028-01-12', 348, 7093.00, 2468364.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(80, 8, 41, 'LOT-PN-8-281', '2027-10-12', 454, 3811.00, 1730194.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(81, 8, 131, 'LOT-PN-8-578', '2026-06-12', 499, 6676.00, 3331324.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(82, 8, 172, 'LOT-PN-8-677', '2028-11-12', 65, 7354.00, 478010.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(83, 8, 198, 'LOT-PN-8-928', '2028-08-12', 85, 8710.00, 740350.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(84, 8, 108, 'LOT-PN-8-359', '2028-08-12', 411, 2839.00, 1166829.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(85, 8, 182, 'LOT-PN-8-147', '2026-08-12', 227, 8768.00, 1990336.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(86, 8, 125, 'LOT-PN-8-387', '2027-05-12', 350, 3386.00, 1185100.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(87, 8, 76, 'LOT-PN-8-974', '2027-03-12', 140, 6111.00, 855540.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(88, 9, 175, 'LOT-PN-9-826', '2028-11-12', 89, 7865.00, 699985.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(89, 9, 2, 'LOT-PN-9-388', '2027-12-12', 394, 1382.00, 544508.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(90, 9, 28, 'LOT-PN-9-876', '2028-04-12', 122, 6288.00, 767136.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(91, 9, 69, 'LOT-PN-9-941', '2026-12-12', 266, 8995.00, 2392670.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(92, 9, 186, 'LOT-PN-9-53', '2027-08-12', 36, 6228.00, 224208.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(93, 9, 77, 'LOT-PN-9-272', '2028-03-12', 63, 6716.00, 423108.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(94, 9, 115, 'LOT-PN-9-784', '2026-12-12', 284, 8520.00, 2419680.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(95, 9, 197, 'LOT-PN-9-583', '2027-07-12', 20, 9506.00, 190120.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(96, 10, 127, 'LOT-PN-10-964', '2027-11-12', 64, 3926.00, 251264.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(97, 10, 103, 'LOT-PN-10-820', '2027-08-12', 466, 4010.00, 1868660.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(98, 10, 9, 'LOT-PN-10-141', '2027-10-12', 489, 4746.00, 2320794.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(99, 10, 56, 'LOT-PN-10-625', '2028-01-12', 327, 3685.00, 1204995.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(100, 10, 137, 'LOT-PN-10-502', '2028-03-12', 144, 4278.00, 616032.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(101, 10, 30, 'LOT-PN-10-255', '2028-02-12', 217, 8069.00, 1750973.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(102, 10, 73, 'LOT-PN-10-332', '2027-01-12', 270, 3848.00, 1038960.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(103, 10, 39, 'LOT-PN-10-881', '2028-07-12', 32, 5714.00, 182848.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(104, 1, 103, 'LOT-PN-1-352', '2027-11-12', 275, 3139.00, 863225.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(105, 1, 149, 'LOT-PN-1-257', '2027-09-12', 28, 2029.00, 56812.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(106, 1, 58, 'LOT-PN-1-267', '2026-08-12', 101, 5358.00, 541158.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(107, 1, 108, 'LOT-PN-1-567', '2027-04-12', 141, 4768.00, 672288.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(108, 1, 47, 'LOT-PN-1-223', '2027-08-12', 431, 4026.00, 1735206.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(109, 1, 115, 'LOT-PN-1-373', '2028-10-12', 463, 6612.00, 3061356.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(110, 1, 196, 'LOT-PN-1-32', '2027-12-12', 198, 6119.00, 1211562.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(111, 1, 144, 'LOT-PN-1-221', '2027-11-12', 64, 9025.00, 577600.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(112, 1, 8, 'LOT-PN-1-101', '2028-08-12', 115, 2325.00, 267375.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(113, 2, 161, 'LOT-PN-2-763', '2027-06-12', 122, 3801.00, 463722.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(114, 2, 40, 'LOT-PN-2-264', '2028-01-12', 409, 9887.00, 4043783.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(115, 2, 80, 'LOT-PN-2-482', '2027-05-12', 451, 5703.00, 2572053.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(116, 2, 32, 'LOT-PN-2-370', '2026-07-12', 356, 6376.00, 2269856.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(117, 2, 15, 'LOT-PN-2-709', '2027-07-12', 415, 9543.00, 3960345.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(118, 2, 45, 'LOT-PN-2-81', '2026-10-12', 34, 8571.00, 291414.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(119, 2, 19, 'LOT-PN-2-234', '2027-01-12', 110, 5079.00, 558690.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(120, 2, 14, 'LOT-PN-2-583', '2027-09-12', 45, 3536.00, 159120.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(121, 2, 77, 'LOT-PN-2-570', '2028-04-12', 365, 3055.00, 1115075.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(122, 2, 67, 'LOT-PN-2-25', '2028-07-12', 69, 4490.00, 309810.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(123, 2, 46, 'LOT-PN-2-303', '2028-01-12', 397, 7987.00, 3170839.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(124, 2, 195, 'LOT-PN-2-962', '2027-09-12', 435, 4049.00, 1761315.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(125, 2, 71, 'LOT-PN-2-949', '2028-01-12', 93, 8193.00, 761949.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(126, 2, 192, 'LOT-PN-2-128', '2027-06-12', 74, 6734.00, 498316.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(127, 3, 180, 'LOT-PN-3-375', '2026-07-12', 187, 3716.00, 694892.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(128, 3, 136, 'LOT-PN-3-490', '2026-12-12', 280, 6185.00, 1731800.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(129, 3, 87, 'LOT-PN-3-377', '2028-09-12', 159, 1389.00, 220851.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(130, 3, 181, 'LOT-PN-3-243', '2027-05-12', 448, 3177.00, 1423296.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(131, 3, 158, 'LOT-PN-3-704', '2026-11-12', 341, 9674.00, 3298834.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(132, 3, 83, 'LOT-PN-3-720', '2027-09-12', 85, 4419.00, 375615.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(133, 4, 61, 'LOT-PN-4-959', '2028-01-12', 84, 3875.00, 325500.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(134, 4, 149, 'LOT-PN-4-183', '2027-08-12', 186, 9211.00, 1713246.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(135, 4, 78, 'LOT-PN-4-349', '2028-10-12', 373, 4153.00, 1549069.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(136, 4, 1, 'LOT-PN-4-711', '2028-12-12', 124, 3451.00, 427924.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(137, 4, 29, 'LOT-PN-4-198', '2027-05-12', 249, 6723.00, 1674027.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(138, 4, 164, 'LOT-PN-4-979', '2027-06-12', 245, 6731.00, 1649095.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(139, 4, 42, 'LOT-PN-4-613', '2026-11-12', 342, 1761.00, 602262.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(140, 4, 131, 'LOT-PN-4-736', '2027-06-12', 244, 7153.00, 1745332.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(141, 4, 56, 'LOT-PN-4-372', '2028-12-12', 265, 2402.00, 636530.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(142, 4, 53, 'LOT-PN-4-856', '2026-12-12', 180, 696.00, 125280.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(143, 5, 58, 'LOT-PN-5-49', '2028-03-12', 39, 8195.00, 319605.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(144, 5, 78, 'LOT-PN-5-944', '2026-08-12', 83, 3229.00, 268007.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(145, 5, 41, 'LOT-PN-5-743', '2027-05-12', 37, 1766.00, 65342.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(146, 5, 129, 'LOT-PN-5-381', '2028-03-12', 126, 824.00, 103824.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(147, 5, 127, 'LOT-PN-5-476', '2028-12-12', 31, 1378.00, 42718.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(148, 5, 198, 'LOT-PN-5-945', '2028-07-12', 286, 7328.00, 2095808.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(149, 5, 54, 'LOT-PN-5-486', '2026-11-12', 69, 8518.00, 587742.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(150, 5, 19, 'LOT-PN-5-41', '2028-11-12', 140, 3082.00, 431480.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(151, 5, 43, 'LOT-PN-5-259', '2028-07-12', 139, 5165.00, 717935.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(152, 5, 116, 'LOT-PN-5-467', '2028-10-12', 99, 8065.00, 798435.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(153, 5, 51, 'LOT-PN-5-844', '2027-05-12', 496, 2034.00, 1008864.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(154, 5, 48, 'LOT-PN-5-988', '2028-10-12', 102, 9676.00, 986952.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(155, 6, 99, 'LOT-PN-6-528', '2027-06-12', 189, 7558.00, 1428462.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(156, 6, 123, 'LOT-PN-6-550', '2027-10-12', 309, 4054.00, 1252686.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(157, 6, 142, 'LOT-PN-6-614', '2028-11-12', 160, 9059.00, 1449440.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(158, 6, 20, 'LOT-PN-6-643', '2028-11-12', 37, 1122.00, 41514.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(159, 6, 28, 'LOT-PN-6-763', '2028-07-12', 358, 985.00, 352630.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(160, 6, 179, 'LOT-PN-6-623', '2026-06-12', 77, 3395.00, 261415.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(161, 6, 138, 'LOT-PN-6-792', '2026-08-12', 307, 7821.00, 2401047.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(162, 6, 32, 'LOT-PN-6-22', '2027-10-12', 231, 1379.00, 318549.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(163, 7, 160, 'LOT-PN-7-658', '2027-09-12', 414, 9033.00, 3739662.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(164, 7, 50, 'LOT-PN-7-582', '2028-11-12', 160, 4066.00, 650560.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(165, 7, 13, 'LOT-PN-7-245', '2027-05-12', 258, 3268.00, 843144.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(166, 7, 49, 'LOT-PN-7-879', '2026-11-12', 356, 8377.00, 2982212.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(167, 7, 90, 'LOT-PN-7-60', '2026-09-12', 70, 2692.00, 188440.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(168, 7, 41, 'LOT-PN-7-189', '2026-06-12', 261, 7870.00, 2054070.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(169, 7, 121, 'LOT-PN-7-650', '2027-12-12', 494, 9309.00, 4598646.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(170, 7, 36, 'LOT-PN-7-709', '2028-03-12', 445, 8001.00, 3560445.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(171, 7, 35, 'LOT-PN-7-652', '2026-08-12', 499, 8813.00, 4397687.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(172, 7, 52, 'LOT-PN-7-253', '2026-10-12', 440, 9588.00, 4218720.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(173, 7, 116, 'LOT-PN-7-778', '2027-05-12', 191, 3084.00, 589044.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(174, 8, 29, 'LOT-PN-8-595', '2028-08-12', 335, 8335.00, 2792225.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(175, 8, 167, 'LOT-PN-8-808', '2028-08-12', 315, 5085.00, 1601775.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(176, 8, 141, 'LOT-PN-8-516', '2027-12-12', 78, 6483.00, 505674.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(177, 8, 51, 'LOT-PN-8-804', '2026-06-12', 376, 688.00, 258688.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(178, 8, 84, 'LOT-PN-8-460', '2027-10-12', 198, 3644.00, 721512.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(179, 8, 194, 'LOT-PN-8-109', '2026-06-12', 301, 6826.00, 2054626.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(180, 8, 139, 'LOT-PN-8-780', '2027-09-12', 336, 4861.00, 1633296.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(181, 8, 40, 'LOT-PN-8-145', '2026-11-12', 316, 9249.00, 2922684.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(182, 8, 62, 'LOT-PN-8-840', '2028-04-12', 448, 7776.00, 3483648.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(183, 8, 11, 'LOT-PN-8-266', '2028-01-12', 392, 646.00, 253232.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(184, 8, 97, 'LOT-PN-8-889', '2028-08-12', 232, 9635.00, 2235320.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(185, 8, 30, 'LOT-PN-8-643', '2026-08-12', 67, 1774.00, 118858.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(186, 9, 4, 'LOT-PN-9-68', '2028-10-12', 89, 3302.00, 293878.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(187, 9, 17, 'LOT-PN-9-741', '2027-05-12', 364, 7170.00, 2609880.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(188, 9, 16, 'LOT-PN-9-545', '2027-05-12', 440, 1529.00, 672760.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(189, 9, 195, 'LOT-PN-9-951', '2028-09-12', 231, 8185.00, 1890735.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(190, 9, 189, 'LOT-PN-9-843', '2027-03-12', 339, 1698.00, 575622.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(191, 9, 170, 'LOT-PN-9-359', '2026-07-12', 251, 634.00, 159134.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(192, 9, 11, 'LOT-PN-9-94', '2026-06-12', 284, 9357.00, 2657388.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(193, 9, 7, 'LOT-PN-9-892', '2028-01-12', 39, 6046.00, 235794.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(194, 9, 180, 'LOT-PN-9-622', '2027-06-12', 69, 9409.00, 649221.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(195, 9, 89, 'LOT-PN-9-194', '2027-02-12', 246, 9939.00, 2444994.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(196, 9, 108, 'LOT-PN-9-378', '2027-08-12', 209, 1140.00, 238260.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(197, 9, 164, 'LOT-PN-9-270', '2027-01-12', 268, 1823.00, 488564.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(198, 9, 88, 'LOT-PN-9-11', '2026-08-12', 328, 9406.00, 3085168.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(199, 9, 3, 'LOT-PN-9-1', '2027-04-12', 166, 7252.00, 1203832.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(200, 10, 108, 'LOT-PN-10-118', '2027-04-12', 419, 7556.00, 3165964.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(201, 10, 81, 'LOT-PN-10-545', '2027-07-12', 101, 1470.00, 148470.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(202, 10, 115, 'LOT-PN-10-89', '2026-09-12', 457, 7434.00, 3397338.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(203, 10, 136, 'LOT-PN-10-207', '2027-12-12', 310, 8796.00, 2726760.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(204, 10, 65, 'LOT-PN-10-145', '2027-01-12', 283, 2269.00, 642127.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(205, 10, 76, 'LOT-PN-10-58', '2027-01-12', 128, 5891.00, 754048.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(206, 10, 193, 'LOT-PN-10-172', '2027-03-12', 291, 1813.00, 527583.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15');

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
(1, 'PX-SEED-1', '2025-11-16', NULL, 'Khach le', 2493677.00, 'Seed PX #1', '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(2, 'PX-SEED-2', '2025-11-18', NULL, 'Khach le', 1873278.00, 'Seed PX #2', '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(3, 'PX-SEED-3', '2025-12-07', NULL, 'Khach le', 1604830.00, 'Seed PX #3', '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(4, 'PX-SEED-4', '2025-12-03', NULL, 'Khach le', 1807743.00, 'Seed PX #4', '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(5, 'PX-SEED-5', '2025-11-17', NULL, 'Khach le', 2490167.00, 'Seed PX #5', '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(6, 'PX-SEED-6', '2025-11-24', NULL, 'Khach le', 1084688.00, 'Seed PX #6', '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(7, 'PX-SEED-7', '2025-11-14', NULL, 'Khach le', 1948362.00, 'Seed PX #7', '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(8, 'PX-SEED-8', '2025-11-17', NULL, 'Khach le', 589346.00, 'Seed PX #8', '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(9, 'PX-SEED-9', '2025-11-25', NULL, 'Khach le', 887430.00, 'Seed PX #9', '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(10, 'PX-SEED-10', '2025-12-04', NULL, 'Khach le', 2046615.00, 'Seed PX #10', '2025-12-12 07:45:56', '2025-12-12 07:50:15');

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
(1, 1, 98, 6, 1610.00, 9660.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(2, 1, 68, 90, 1970.00, 177300.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(3, 1, 87, 81, 6650.00, 538650.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(4, 1, 63, 79, 3490.00, 275710.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(5, 1, 95, 92, 9805.00, 902060.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(6, 1, 144, 53, 10549.00, 559097.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(7, 1, 196, 9, 2903.00, 26127.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(8, 2, 3, 10, 7082.00, 70820.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(9, 2, 82, 33, 10513.00, 346929.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(10, 2, 135, 27, 9473.00, 255771.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(11, 3, 23, 19, 1076.00, 20444.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(12, 3, 50, 42, 11632.00, 488544.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(13, 3, 123, 53, 2447.00, 129691.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(14, 3, 118, 65, 2107.00, 136955.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(15, 3, 106, 37, 6032.00, 223184.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(16, 3, 104, 14, 11587.00, 162218.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(17, 3, 1, 62, 2196.00, 136152.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(18, 3, 197, 5, 10827.00, 54135.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(19, 4, 170, 62, 2391.00, 148242.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(20, 4, 44, 85, 7105.00, 603925.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(21, 4, 181, 74, 9263.00, 685462.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(22, 4, 118, 35, 11244.00, 393540.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(23, 4, 98, 84, 11159.00, 937356.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(24, 4, 112, 39, 9824.00, 383136.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(25, 5, 23, 2, 10834.00, 21668.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(26, 5, 84, 49, 5029.00, 246421.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(27, 5, 31, 93, 7843.00, 729399.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(28, 5, 42, 20, 8179.00, 163580.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(29, 5, 44, 87, 4381.00, 381147.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(30, 5, 166, 8, 4913.00, 39304.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(31, 6, 186, 9, 6657.00, 59913.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(32, 6, 26, 67, 2466.00, 165222.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(33, 6, 25, 7, 5867.00, 41069.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(34, 6, 66, 43, 8618.00, 370574.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(35, 6, 84, 48, 6830.00, 327840.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(36, 6, 171, 48, 5884.00, 282432.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(37, 6, 172, 17, 6960.00, 118320.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(38, 6, 7, 19, 10321.00, 196099.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(39, 7, 3, 71, 4499.00, 319429.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(40, 7, 160, 69, 3812.00, 263028.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(41, 7, 157, 62, 8831.00, 547522.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(42, 7, 140, 38, 2889.00, 109782.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(43, 7, 10, 1, 9266.00, 9266.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(44, 8, 114, 36, 4482.00, 161352.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(45, 8, 84, 8, 6934.00, 55472.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(46, 8, 30, 84, 2143.00, 180012.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(47, 8, 80, 70, 8116.00, 568120.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(48, 8, 70, 18, 2492.00, 44856.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(49, 8, 108, 25, 10487.00, 262175.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(50, 8, 165, 50, 10864.00, 543200.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(51, 9, 9, 20, 1055.00, 21100.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(52, 9, 39, 32, 1719.00, 55008.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(53, 9, 39, 37, 1719.00, 63603.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(54, 9, 135, 36, 11333.00, 407988.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(55, 9, 32, 18, 4041.00, 72738.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(56, 9, 153, 47, 7062.00, 331914.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(57, 9, 108, 44, 5304.00, 233376.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(58, 9, 145, 11, 6523.00, 71753.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(59, 9, 21, 33, 4397.00, 145101.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(60, 10, 67, 22, 6673.00, 146806.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(61, 10, 37, 80, 3567.00, 285360.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(62, 10, 138, 30, 4915.00, 147450.00, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(63, 1, 68, 81, 10365.00, 839565.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(64, 1, 3, 82, 1839.00, 150798.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(65, 1, 73, 36, 9947.00, 358092.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(66, 1, 23, 4, 9534.00, 38136.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(67, 1, 4, 27, 11538.00, 311526.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(68, 1, 31, 38, 9754.00, 370652.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(69, 1, 19, 40, 5829.00, 233160.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(70, 1, 148, 58, 3306.00, 191748.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(71, 2, 31, 4, 3242.00, 12968.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(72, 2, 199, 42, 6466.00, 271572.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(73, 2, 40, 98, 5934.00, 581532.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(74, 2, 139, 94, 1507.00, 141658.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(75, 2, 5, 11, 10169.00, 111859.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(76, 2, 127, 64, 8182.00, 523648.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(77, 2, 127, 21, 8182.00, 171822.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(78, 2, 142, 7, 8317.00, 58219.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(79, 3, 197, 15, 8020.00, 120300.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(80, 3, 197, 71, 8020.00, 569420.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(81, 3, 153, 87, 4870.00, 423690.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(82, 3, 40, 75, 4100.00, 307500.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(83, 3, 73, 44, 4180.00, 183920.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(84, 4, 150, 93, 10749.00, 999657.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(85, 4, 75, 35, 2061.00, 72135.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(86, 4, 64, 61, 10151.00, 619211.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(87, 4, 200, 52, 2245.00, 116740.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(88, 5, 118, 73, 5814.00, 424422.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(89, 5, 129, 21, 1126.00, 23646.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(90, 5, 166, 95, 10903.00, 1035785.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(91, 5, 165, 38, 11611.00, 441218.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(92, 5, 124, 44, 5202.00, 228888.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(93, 5, 33, 32, 4989.00, 159648.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(94, 5, 16, 92, 1098.00, 101016.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(95, 5, 122, 8, 9443.00, 75544.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(96, 6, 38, 15, 10229.00, 153435.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(97, 6, 138, 74, 5007.00, 370518.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(98, 6, 10, 25, 10021.00, 250525.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(99, 6, 92, 42, 4583.00, 192486.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(100, 6, 61, 19, 6196.00, 117724.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(101, 7, 23, 46, 9152.00, 420992.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(102, 7, 100, 30, 10651.00, 319530.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(103, 7, 13, 79, 1402.00, 110758.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(104, 7, 108, 2, 9828.00, 19656.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(105, 7, 39, 50, 3003.00, 150150.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(106, 7, 180, 3, 1510.00, 4530.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(107, 7, 75, 93, 9922.00, 922746.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(108, 8, 187, 54, 6498.00, 350892.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(109, 8, 125, 19, 11426.00, 217094.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(110, 8, 44, 10, 2136.00, 21360.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(111, 9, 118, 12, 4118.00, 49416.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(112, 9, 95, 10, 6543.00, 65430.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(113, 9, 136, 14, 9511.00, 133154.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(114, 9, 13, 55, 11626.00, 639430.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(115, 10, 62, 96, 11372.00, 1091712.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(116, 10, 153, 54, 4789.00, 258606.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(117, 10, 200, 71, 9807.00, 696297.00, '2025-12-12 07:50:15', '2025-12-12 07:50:15');

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
(17, 'Phòng khám Sản 01 (VIP)', 'phong_kham', 'Phòng khám tiêu chuẩn VIP, không gian riêng tư, trang bị ghế khám thông minh.', 'Sẵn sàng', 'Tầng 1, Khu A (Cạnh lễ tân)', 25.00, 3, '2025-12-12 07:01:59', '2025-12-13 03:14:26'),
(18, 'Phòng khám Sản 02', 'phong_kham', 'Chuyên khám thai định kỳ và tư vấn dinh dưỡng.', 'Sẵn sàng', 'Tầng 1, Khu A', 20.00, 3, '2025-12-12 07:01:59', '2025-12-13 03:14:26'),
(19, 'Phòng Siêu âm Chẩn đoán hình ảnh', 'phong_xet_nghiem', 'Trang bị máy siêu âm Voluson E10 hiện đại nhất, màn hình LED lớn cho gia đình cùng xem.', 'Sẵn sàng', 'Tầng 1, Khu B (Đối diện phòng khám Sản)', 30.00, 5, '2025-12-12 07:02:00', '2025-12-13 03:14:26'),
(20, 'Phòng khám Phụ khoa 01', 'phong_kham', 'Phòng khám phụ khoa tổng quát, có khu vực thay đồ kín đáo cho khách hàng.', 'Sẵn sàng', 'Tầng 2, Khu A', 20.00, 3, '2025-12-12 07:02:00', '2025-12-13 03:14:26'),
(21, 'Phòng Thủ thuật Kế hoạch hóa GĐ', 'phong_thu_thuat', 'Chuyên thực hiện cấy que, đặt vòng, hút thai an toàn. Đảm bảo tiêu chuẩn vô khuẩn tuyệt đối.', 'Sẵn sàng', 'Tầng 2, Khu B (Khu vực vô trùng)', 35.00, 4, '2025-12-12 07:02:00', '2025-12-13 03:14:26'),
(22, 'Phòng Laser Thẩm mỹ & Sàn chậu', 'phong_chuc_nang', 'Trang bị máy Laser CO2 và máy tập sàn chậu Biofeedback. Không gian Spa thư giãn.', 'Sẵn sàng', 'Tầng 2, Khu C (Khu vực yên tĩnh)', 25.00, 2, '2025-12-12 07:02:00', '2025-12-13 03:14:26'),
(23, 'Phòng Tư vấn Vô sinh - Hiếm muộn', 'phong_kham', 'Không gian tư vấn riêng tư, kín đáo cho các cặp đôi.', 'Sẵn sàng', 'Tầng 3, Khu A', 25.00, 4, '2025-12-12 07:02:00', '2025-12-13 03:14:26'),
(24, 'Phòng Lab IUI & Lọc rửa tinh trùng', 'phong_thu_thuat', 'Phòng Lab đạt chuẩn ISO, kiểm soát nhiệt độ và độ ẩm nghiêm ngặt để nuôi cấy phôi/tinh trùng.', 'Sẵn sàng', 'Tầng 3, Khu B (Cách ly đặc biệt)', 40.00, 5, '2025-12-12 07:02:00', '2025-12-13 03:14:26'),
(25, 'Trung tâm Xét nghiệm (Labo)', 'phong_xet_nghiem', 'Hệ thống máy xét nghiệm tự động hoàn toàn (Roche/Abbott).', 'Sẵn sàng', 'Tầng 3, Khu C', 50.00, 10, '2025-12-12 07:02:00', '2025-12-13 03:14:26'),
(26, 'Phòng Tư vấn Sàng lọc Trước sinh', 'phong_kham', 'Tư vấn chuyên sâu về kết quả NIPT và chọc ối.', 'Sẵn sàng', 'Tầng 1, Khu A (Gần khu lấy máu)', 15.00, 3, '2025-12-12 07:02:00', '2025-12-13 03:14:26');

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
(1, 'super-admin', 'web', '2025-12-12 04:21:48', '2025-12-12 04:21:48'),
(2, 'admin', 'web', '2025-12-12 04:21:48', '2025-12-12 04:21:48'),
(3, 'manager', 'web', '2025-12-12 04:21:48', '2025-12-12 04:21:48'),
(4, 'doctor', 'web', '2025-12-12 04:21:48', '2025-12-12 04:21:48'),
(5, 'staff', 'web', '2025-12-12 04:21:48', '2025-12-12 04:21:48'),
(6, 'patient', 'web', '2025-12-12 04:21:48', '2025-12-12 04:21:48'),
(7, 'accountant', 'web', '2025-12-12 04:21:48', '2025-12-12 04:21:48'),
(8, 'pharmacist', 'web', '2025-12-12 04:21:48', '2025-12-12 04:21:48');

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
(56, 5);

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
(1, 'Dinh dưỡng bà bầu', 'dinh-duong-ba-bau', '2025-12-12 08:30:17', '2025-12-12 08:30:17'),
(2, 'Lịch khám thai', 'lich-kham-thai', '2025-12-12 08:30:17', '2025-12-12 08:30:17'),
(3, 'Thụ tinh ống nghiệm (IVF)', 'thu-tinh-ong-nghiem-ivf', '2025-12-12 08:30:17', '2025-12-12 08:30:17'),
(4, 'Sàng lọc trước sinh', 'sang-loc-truoc-sinh', '2025-12-12 08:30:17', '2025-12-12 08:30:17'),
(5, 'Ưu đãi', 'uu-dai', '2025-12-12 08:30:17', '2025-12-12 08:30:17'),
(6, 'Sale', 'sale', '2025-12-12 08:30:17', '2025-12-12 08:30:17');

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
(1, 3, 'VNPAY', 2250000.00, 'VND', 'Thành công', 'VNP15338816', '3_VNP15338816', '2025-12-13 08:02:04', NULL, '2025-12-13 08:02:04', '2025-12-13 08:02:04'),
(2, 3, 'cash', 2250000.00, 'VND', 'succeeded', 'CASH-20251213150248', NULL, '2025-12-13 08:02:48', '{\"note\":null}', '2025-12-13 08:02:48', '2025-12-13 08:02:48');

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
(1, 'Paracetamol 500mg', 'Paracetamol', '500mg', 'viên', 6799.00, 126, '2025-12-12 07:45:55', '2025-12-12 07:50:15'),
(2, 'Ibuprofen 200mg - Lot02', 'Ibuprofen', '200mg', 'viên', 3144.00, 134, '2025-12-12 07:45:55', '2025-12-12 07:50:15'),
(3, 'Amoxicillin 500mg - Lot03', 'Amoxicillin', '500mg', 'viên', 8858.00, 177, '2025-12-12 07:45:55', '2025-12-12 07:50:15'),
(4, 'Azithromycin 250mg - Lot04', 'Azithromycin', '250mg', 'viên', 7111.00, 20, '2025-12-12 07:45:55', '2025-12-12 07:50:15'),
(5, 'Doxycycline 100mg - Lot05', 'Doxycycline', '100mg', 'viên', 4240.00, 179, '2025-12-12 07:45:55', '2025-12-12 07:50:15'),
(6, 'Metronidazole 500mg - Lot06', 'Metronidazole', '500mg', 'viên', 6736.00, 38, '2025-12-12 07:45:55', '2025-12-12 07:50:15'),
(7, 'Cefuroxime 250mg - Lot07', 'Cefuroxime', '250mg', 'viên', 9986.00, 121, '2025-12-12 07:45:55', '2025-12-12 07:50:15'),
(8, 'Cefixime 400mg - Lot08', 'Cefixime', '400mg', 'viên', 1855.00, 145, '2025-12-12 07:45:55', '2025-12-12 07:50:15'),
(9, 'Cefadroxil 500mg - Lot09', 'Cefadroxil', '500mg', 'viên', 1744.00, 39, '2025-12-12 07:45:55', '2025-12-12 07:50:15'),
(10, 'Ceftriaxone 1g - Lot10', 'Ceftriaxone', '1g', 'ống', 1585.00, 189, '2025-12-12 07:45:55', '2025-12-12 07:50:15'),
(11, 'Omeprazole 20mg - Lot11', 'Omeprazole', '20mg', 'viên', 4696.00, 120, '2025-12-12 07:45:55', '2025-12-12 07:50:15'),
(12, 'Pantoprazole 40mg - Lot12', 'Pantoprazole', '40mg', 'viên', 4789.00, 199, '2025-12-12 07:45:55', '2025-12-12 07:50:15'),
(13, 'Ranitidine 150mg - Lot13', 'Ranitidine', '150mg', 'viên', 4943.00, 108, '2025-12-12 07:45:55', '2025-12-12 07:50:15'),
(14, 'Tranexamic 500mg - Lot14', 'Tranexamic', '500mg', 'viên', 5675.00, 113, '2025-12-12 07:45:55', '2025-12-12 07:50:15'),
(15, 'Ferrous sulfate 325mg - Lot15', 'Ferrous', '325mg', 'viên', 8143.00, 67, '2025-12-12 07:45:55', '2025-12-12 07:50:15'),
(16, 'Vitamin C 500mg - Lot16', 'Vitamin', '500mg', 'viên', 4177.00, 130, '2025-12-12 07:45:55', '2025-12-12 07:50:15'),
(17, 'Vitamin D3 2000IU - Lot17', 'Vitamin', '2000IU', 'ống', 5469.00, 35, '2025-12-12 07:45:55', '2025-12-12 07:50:15'),
(18, 'Calcium 600mg - Lot18', 'Calcium', '600mg', 'viên', 9503.00, 199, '2025-12-12 07:45:55', '2025-12-12 07:50:15'),
(19, 'Utrogestan 200mg - Lot19', 'Utrogestan', '200mg', 'viên', 2104.00, 81, '2025-12-12 07:45:55', '2025-12-12 07:50:15'),
(20, 'Duphaston 10mg - Lot20', 'Duphaston', '10mg', 'viên', 1147.00, 44, '2025-12-12 07:45:55', '2025-12-12 07:50:15'),
(21, 'Levothyroxine 50mcg - Lot21', 'Levothyroxine', NULL, 'ống', 6592.00, 103, '2025-12-12 07:45:55', '2025-12-12 07:50:15'),
(22, 'Progesterone 100mg - Lot22', 'Progesterone', '100mg', 'viên', 3887.00, 116, '2025-12-12 07:45:55', '2025-12-12 07:50:15'),
(23, 'Nitrofurantoin 100mg - Lot23', 'Nitrofurantoin', '100mg', 'viên', 5633.00, 146, '2025-12-12 07:45:55', '2025-12-12 07:50:15'),
(24, 'Fluconazole 150mg - Lot24', 'Fluconazole', '150mg', 'viên', 7852.00, 78, '2025-12-12 07:45:55', '2025-12-12 07:50:15'),
(25, 'Ketoconazole 200mg - Lot25', 'Ketoconazole', '200mg', 'viên', 5875.00, 85, '2025-12-12 07:45:55', '2025-12-12 07:50:15'),
(26, 'Paracetamol 500mg - Lot26', 'Paracetamol', '500mg', 'viên', 3396.00, 127, '2025-12-12 07:45:55', '2025-12-12 07:50:15'),
(27, 'Ibuprofen 200mg - Lot27', 'Ibuprofen', '200mg', 'viên', 4819.00, 69, '2025-12-12 07:45:55', '2025-12-12 07:50:15'),
(28, 'Amoxicillin 500mg - Lot28', 'Amoxicillin', '500mg', 'viên', 3256.00, 192, '2025-12-12 07:45:55', '2025-12-12 07:50:15'),
(29, 'Azithromycin 250mg - Lot29', 'Azithromycin', '250mg', 'viên', 9760.00, 116, '2025-12-12 07:45:55', '2025-12-12 07:50:15'),
(30, 'Doxycycline 100mg - Lot30', 'Doxycycline', '100mg', 'viên', 9462.00, 22, '2025-12-12 07:45:55', '2025-12-12 07:50:15'),
(31, 'Metronidazole 500mg - Lot31', 'Metronidazole', '500mg', 'viên', 3125.00, 174, '2025-12-12 07:45:55', '2025-12-12 07:50:15'),
(32, 'Cefuroxime 250mg - Lot32', 'Cefuroxime', '250mg', 'viên', 4606.00, 75, '2025-12-12 07:45:55', '2025-12-12 07:50:15'),
(33, 'Cefixime 400mg - Lot33', 'Cefixime', '400mg', 'viên', 5824.00, 59, '2025-12-12 07:45:55', '2025-12-12 07:50:15'),
(34, 'Cefadroxil 500mg - Lot34', 'Cefadroxil', '500mg', 'viên', 1572.00, 101, '2025-12-12 07:45:55', '2025-12-12 07:50:15'),
(35, 'Ceftriaxone 1g - Lot35', 'Ceftriaxone', '1g', 'ống', 2198.00, 48, '2025-12-12 07:45:55', '2025-12-12 07:50:15'),
(36, 'Omeprazole 20mg - Lot36', 'Omeprazole', '20mg', 'viên', 7880.00, 166, '2025-12-12 07:45:55', '2025-12-12 07:50:15'),
(37, 'Pantoprazole 40mg - Lot37', 'Pantoprazole', '40mg', 'viên', 9457.00, 54, '2025-12-12 07:45:55', '2025-12-12 07:50:15'),
(38, 'Ranitidine 150mg - Lot38', 'Ranitidine', '150mg', 'viên', 6978.00, 102, '2025-12-12 07:45:55', '2025-12-12 07:50:15'),
(39, 'Tranexamic 500mg - Lot39', 'Tranexamic', '500mg', 'viên', 6270.00, 64, '2025-12-12 07:45:55', '2025-12-12 07:50:15'),
(40, 'Ferrous sulfate 325mg - Lot40', 'Ferrous', '325mg', 'viên', 4843.00, 190, '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(41, 'Vitamin C 500mg - Lot41', 'Vitamin', '500mg', 'viên', 6869.00, 200, '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(42, 'Vitamin D3 2000IU - Lot42', 'Vitamin', '2000IU', 'ống', 6823.00, 103, '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(43, 'Calcium 600mg - Lot43', 'Calcium', '600mg', 'viên', 9875.00, 183, '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(44, 'Utrogestan 200mg - Lot44', 'Utrogestan', '200mg', 'viên', 4866.00, 87, '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(45, 'Duphaston 10mg - Lot45', 'Duphaston', '10mg', 'viên', 5327.00, 148, '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(46, 'Levothyroxine 50mcg - Lot46', 'Levothyroxine', NULL, 'ống', 9413.00, 139, '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(47, 'Progesterone 100mg - Lot47', 'Progesterone', '100mg', 'viên', 4455.00, 125, '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(48, 'Nitrofurantoin 100mg - Lot48', 'Nitrofurantoin', '100mg', 'viên', 9801.00, 88, '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(49, 'Fluconazole 150mg - Lot49', 'Fluconazole', '150mg', 'viên', 3257.00, 147, '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(50, 'Ketoconazole 200mg - Lot50', 'Ketoconazole', '200mg', 'viên', 1753.00, 142, '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(51, 'Paracetamol 500mg - Lot51', 'Paracetamol', '500mg', 'viên', 6168.00, 103, '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(52, 'Ibuprofen 200mg - Lot52', 'Ibuprofen', '200mg', 'viên', 1876.00, 159, '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(53, 'Amoxicillin 500mg - Lot53', 'Amoxicillin', '500mg', 'viên', 4569.00, 21, '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(54, 'Azithromycin 250mg - Lot54', 'Azithromycin', '250mg', 'viên', 6654.00, 13, '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(55, 'Doxycycline 100mg - Lot55', 'Doxycycline', '100mg', 'viên', 5440.00, 143, '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(56, 'Metronidazole 500mg - Lot56', 'Metronidazole', '500mg', 'viên', 2294.00, 28, '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(57, 'Cefuroxime 250mg - Lot57', 'Cefuroxime', '250mg', 'viên', 4464.00, 200, '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(58, 'Cefixime 400mg - Lot58', 'Cefixime', '400mg', 'viên', 6275.00, 17, '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(59, 'Cefadroxil 500mg - Lot59', 'Cefadroxil', '500mg', 'viên', 4550.00, 128, '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(60, 'Ceftriaxone 1g - Lot60', 'Ceftriaxone', '1g', 'ống', 1957.00, 83, '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(61, 'Omeprazole 20mg - Lot61', 'Omeprazole', '20mg', 'viên', 4697.00, 60, '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(62, 'Pantoprazole 40mg - Lot62', 'Pantoprazole', '40mg', 'viên', 7479.00, 110, '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(63, 'Ranitidine 150mg - Lot63', 'Ranitidine', '150mg', 'viên', 2267.00, 178, '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(64, 'Tranexamic 500mg - Lot64', 'Tranexamic', '500mg', 'viên', 4399.00, 107, '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(65, 'Ferrous sulfate 325mg - Lot65', 'Ferrous', '325mg', 'viên', 2478.00, 135, '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(66, 'Vitamin C 500mg - Lot66', 'Vitamin', '500mg', 'viên', 3637.00, 114, '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(67, 'Vitamin D3 2000IU - Lot67', 'Vitamin', '2000IU', 'ống', 9238.00, 199, '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(68, 'Calcium 600mg - Lot68', 'Calcium', '600mg', 'viên', 3636.00, 46, '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(69, 'Utrogestan 200mg - Lot69', 'Utrogestan', '200mg', 'viên', 7124.00, 11, '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(70, 'Duphaston 10mg - Lot70', 'Duphaston', '10mg', 'viên', 1828.00, 74, '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(71, 'Levothyroxine 50mcg - Lot71', 'Levothyroxine', NULL, 'ống', 1728.00, 158, '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(72, 'Progesterone 100mg - Lot72', 'Progesterone', '100mg', 'viên', 4795.00, 56, '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(73, 'Nitrofurantoin 100mg - Lot73', 'Nitrofurantoin', '100mg', 'viên', 6075.00, 45, '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(74, 'Fluconazole 150mg - Lot74', 'Fluconazole', '150mg', 'viên', 1330.00, 85, '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(75, 'Ketoconazole 200mg - Lot75', 'Ketoconazole', '200mg', 'viên', 7633.00, 182, '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(76, 'Paracetamol 500mg - Lot76', 'Paracetamol', '500mg', 'viên', 7680.00, 51, '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(77, 'Ibuprofen 200mg - Lot77', 'Ibuprofen', '200mg', 'viên', 9155.00, 138, '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(78, 'Amoxicillin 500mg - Lot78', 'Amoxicillin', '500mg', 'viên', 6667.00, 26, '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(79, 'Azithromycin 250mg - Lot79', 'Azithromycin', '250mg', 'viên', 8741.00, 173, '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(80, 'Doxycycline 100mg - Lot80', 'Doxycycline', '100mg', 'viên', 3405.00, 196, '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(81, 'Metronidazole 500mg - Lot81', 'Metronidazole', '500mg', 'viên', 4377.00, 131, '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(82, 'Cefuroxime 250mg - Lot82', 'Cefuroxime', '250mg', 'viên', 2905.00, 151, '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(83, 'Cefixime 400mg - Lot83', 'Cefixime', '400mg', 'viên', 8396.00, 149, '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(84, 'Cefadroxil 500mg - Lot84', 'Cefadroxil', '500mg', 'viên', 5915.00, 48, '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(85, 'Ceftriaxone 1g - Lot85', 'Ceftriaxone', '1g', 'ống', 3547.00, 48, '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(86, 'Omeprazole 20mg - Lot86', 'Omeprazole', '20mg', 'viên', 5234.00, 36, '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(87, 'Pantoprazole 40mg - Lot87', 'Pantoprazole', '40mg', 'viên', 9784.00, 16, '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(88, 'Ranitidine 150mg - Lot88', 'Ranitidine', '150mg', 'viên', 4413.00, 71, '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(89, 'Tranexamic 500mg - Lot89', 'Tranexamic', '500mg', 'viên', 4449.00, 18, '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(90, 'Ferrous sulfate 325mg - Lot90', 'Ferrous', '325mg', 'viên', 3402.00, 101, '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(91, 'Vitamin C 500mg - Lot91', 'Vitamin', '500mg', 'viên', 9584.00, 155, '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(92, 'Vitamin D3 2000IU - Lot92', 'Vitamin', '2000IU', 'ống', 8942.00, 40, '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(93, 'Calcium 600mg - Lot93', 'Calcium', '600mg', 'viên', 8442.00, 182, '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(94, 'Utrogestan 200mg - Lot94', 'Utrogestan', '200mg', 'viên', 3577.00, 73, '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(95, 'Duphaston 10mg - Lot95', 'Duphaston', '10mg', 'viên', 5414.00, 75, '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(96, 'Levothyroxine 50mcg - Lot96', 'Levothyroxine', NULL, 'ống', 6832.00, 29, '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(97, 'Progesterone 100mg - Lot97', 'Progesterone', '100mg', 'viên', 8093.00, 148, '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(98, 'Nitrofurantoin 100mg - Lot98', 'Nitrofurantoin', '100mg', 'viên', 4636.00, 119, '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(99, 'Fluconazole 150mg - Lot99', 'Fluconazole', '150mg', 'viên', 4323.00, 81, '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(100, 'Ketoconazole 200mg - Lot100', 'Ketoconazole', '200mg', 'viên', 2303.00, 172, '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(101, 'Paracetamol 500mg - Lot101', 'Paracetamol', '500mg', 'viên', 1866.00, 112, '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(102, 'Ibuprofen 200mg - Lot102', 'Ibuprofen', '200mg', 'viên', 4440.00, 26, '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(103, 'Amoxicillin 500mg - Lot103', 'Amoxicillin', '500mg', 'viên', 2031.00, 10, '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(104, 'Azithromycin 250mg - Lot104', 'Azithromycin', '250mg', 'viên', 4324.00, 141, '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(105, 'Doxycycline 100mg - Lot105', 'Doxycycline', '100mg', 'viên', 8718.00, 134, '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(106, 'Metronidazole 500mg - Lot106', 'Metronidazole', '500mg', 'viên', 5178.00, 74, '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(107, 'Cefuroxime 250mg - Lot107', 'Cefuroxime', '250mg', 'viên', 3106.00, 43, '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(108, 'Cefixime 400mg - Lot108', 'Cefixime', '400mg', 'viên', 9025.00, 24, '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(109, 'Cefadroxil 500mg - Lot109', 'Cefadroxil', '500mg', 'viên', 4095.00, 18, '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(110, 'Ceftriaxone 1g - Lot110', 'Ceftriaxone', '1g', 'ống', 6250.00, 86, '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(111, 'Omeprazole 20mg - Lot111', 'Omeprazole', '20mg', 'viên', 9836.00, 188, '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(112, 'Pantoprazole 40mg - Lot112', 'Pantoprazole', '40mg', 'viên', 6877.00, 151, '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(113, 'Ranitidine 150mg - Lot113', 'Ranitidine', '150mg', 'viên', 1973.00, 59, '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(114, 'Tranexamic 500mg - Lot114', 'Tranexamic', '500mg', 'viên', 8095.00, 56, '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(115, 'Ferrous sulfate 325mg - Lot115', 'Ferrous', '325mg', 'viên', 7101.00, 83, '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(116, 'Vitamin C 500mg - Lot116', 'Vitamin', '500mg', 'viên', 9795.00, 177, '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(117, 'Vitamin D3 2000IU - Lot117', 'Vitamin', '2000IU', 'ống', 6578.00, 63, '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(118, 'Calcium 600mg - Lot118', 'Calcium', '600mg', 'viên', 5619.00, 31, '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(119, 'Utrogestan 200mg - Lot119', 'Utrogestan', '200mg', 'viên', 3645.00, 121, '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(120, 'Duphaston 10mg - Lot120', 'Duphaston', '10mg', 'viên', 5363.00, 93, '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(121, 'Levothyroxine 50mcg - Lot121', 'Levothyroxine', NULL, 'ống', 1845.00, 96, '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(122, 'Progesterone 100mg - Lot122', 'Progesterone', '100mg', 'viên', 3720.00, 132, '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(123, 'Nitrofurantoin 100mg - Lot123', 'Nitrofurantoin', '100mg', 'viên', 6386.00, 146, '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(124, 'Fluconazole 150mg - Lot124', 'Fluconazole', '150mg', 'viên', 9755.00, 56, '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(125, 'Ketoconazole 200mg - Lot125', 'Ketoconazole', '200mg', 'viên', 3468.00, 19, '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(126, 'Paracetamol 500mg - Lot126', 'Paracetamol', '500mg', 'viên', 4600.00, 166, '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(127, 'Ibuprofen 200mg - Lot127', 'Ibuprofen', '200mg', 'viên', 5918.00, 70, '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(128, 'Amoxicillin 500mg - Lot128', 'Amoxicillin', '500mg', 'viên', 9169.00, 116, '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(129, 'Azithromycin 250mg - Lot129', 'Azithromycin', '250mg', 'viên', 1199.00, 156, '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(130, 'Doxycycline 100mg - Lot130', 'Doxycycline', '100mg', 'viên', 8440.00, 85, '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(131, 'Metronidazole 500mg - Lot131', 'Metronidazole', '500mg', 'viên', 5887.00, 20, '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(132, 'Cefuroxime 250mg - Lot132', 'Cefuroxime', '250mg', 'viên', 3420.00, 107, '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(133, 'Cefixime 400mg - Lot133', 'Cefixime', '400mg', 'viên', 811.00, 66, '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(134, 'Cefadroxil 500mg - Lot134', 'Cefadroxil', '500mg', 'viên', 4789.00, 186, '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(135, 'Ceftriaxone 1g - Lot135', 'Ceftriaxone', '1g', 'ống', 4221.00, 65, '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(136, 'Omeprazole 20mg - Lot136', 'Omeprazole', '20mg', 'viên', 6124.00, 50, '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(137, 'Pantoprazole 40mg - Lot137', 'Pantoprazole', '40mg', 'viên', 4118.00, 70, '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(138, 'Ranitidine 150mg - Lot138', 'Ranitidine', '150mg', 'viên', 9498.00, 38, '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(139, 'Tranexamic 500mg - Lot139', 'Tranexamic', '500mg', 'viên', 6094.00, 73, '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(140, 'Ferrous sulfate 325mg - Lot140', 'Ferrous', '325mg', 'viên', 9584.00, 79, '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(141, 'Vitamin C 500mg - Lot141', 'Vitamin', '500mg', 'viên', 4397.00, 94, '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(142, 'Vitamin D3 2000IU - Lot142', 'Vitamin', '2000IU', 'ống', 4240.00, 77, '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(143, 'Calcium 600mg - Lot143', 'Calcium', '600mg', 'viên', 9831.00, 27, '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(144, 'Utrogestan 200mg - Lot144', 'Utrogestan', '200mg', 'viên', 1441.00, 154, '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(145, 'Duphaston 10mg - Lot145', 'Duphaston', '10mg', 'viên', 9479.00, 89, '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(146, 'Levothyroxine 50mcg - Lot146', 'Levothyroxine', NULL, 'ống', 9128.00, 154, '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(147, 'Progesterone 100mg - Lot147', 'Progesterone', '100mg', 'viên', 3549.00, 89, '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(148, 'Nitrofurantoin 100mg - Lot148', 'Nitrofurantoin', '100mg', 'viên', 4537.00, 152, '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(149, 'Fluconazole 150mg - Lot149', 'Fluconazole', '150mg', 'viên', 7562.00, 175, '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(150, 'Ketoconazole 200mg - Lot150', 'Ketoconazole', '200mg', 'viên', 9952.00, 44, '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(151, 'Găng tay y tế S', NULL, NULL, 'cái', 13591.00, 7, '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(152, 'Găng tay y tế M - Pack 2', NULL, NULL, 'cái', 16683.00, 36, '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(153, 'Găng tay y tế L - Pack 3', NULL, NULL, 'cái', 41995.00, 49, '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(154, 'Bơm kim tiêm 1ml - Pack 4', NULL, NULL, 'cái', 23842.00, 48, '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(155, 'Bơm kim tiêm 5ml - Pack 5', NULL, NULL, 'cái', 28481.00, 24, '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(156, 'Bơm kim tiêm 10ml - Pack 6', NULL, NULL, 'cái', 3890.00, 35, '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(157, 'Bông gòn 50g - Pack 7', NULL, NULL, 'cái', 10046.00, 9, '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(158, 'Cồn 70% - Pack 8', NULL, NULL, 'cái', 13507.00, 17, '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(159, 'Gel siêu âm 5L - Pack 9', NULL, NULL, 'cái', 30650.00, 22, '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(160, 'Que thử thai - Pack 10', NULL, NULL, 'cái', 7925.00, 16, '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(161, 'Que thử rụng trứng - Pack 11', NULL, NULL, 'cái', 47151.00, 39, '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(162, 'Kim tiêm 21G - Pack 12', NULL, NULL, 'cái', 40101.00, 6, '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(163, 'Kim tiêm 23G - Pack 13', NULL, NULL, 'cái', 18144.00, 36, '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(164, 'Khẩu trang N95 - Pack 14', NULL, NULL, 'cái', 20553.00, 15, '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(165, 'Khẩu trang y tế 3 lớp - Pack 15', NULL, NULL, 'cái', 6553.00, 35, '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(166, 'Gạc vô trùng 10x10 - Pack 16', NULL, NULL, 'cái', 35693.00, 34, '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(167, 'Túi đựng máu - Pack 17', NULL, NULL, 'cái', 31993.00, 7, '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(168, 'Hộp đựng dụng cụ tiểu phẫu - Pack 18', NULL, NULL, 'cái', 5367.00, 42, '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(169, 'Dao mổ 10 - Pack 19', NULL, NULL, 'cái', 37178.00, 23, '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(170, 'Dụng cụ lấy máu - Pack 20', NULL, NULL, 'cái', 11783.00, 42, '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(171, 'Bồn đựng mẫu - Pack 21', NULL, NULL, 'cái', 37301.00, 46, '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(172, 'Găng tay y tế S - Pack 22', NULL, NULL, 'cái', 34936.00, 9, '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(173, 'Găng tay y tế M - Pack 23', NULL, NULL, 'cái', 14093.00, 7, '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(174, 'Găng tay y tế L - Pack 24', NULL, NULL, 'cái', 35695.00, 17, '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(175, 'Bơm kim tiêm 1ml - Pack 25', NULL, NULL, 'cái', 10461.00, 23, '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(176, 'Bơm kim tiêm 5ml - Pack 26', NULL, NULL, 'cái', 12544.00, 21, '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(177, 'Bơm kim tiêm 10ml - Pack 27', NULL, NULL, 'cái', 28295.00, 14, '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(178, 'Bông gòn 50g - Pack 28', NULL, NULL, 'cái', 43893.00, 6, '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(179, 'Cồn 70% - Pack 29', NULL, NULL, 'cái', 8745.00, 49, '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(180, 'Gel siêu âm 5L - Pack 30', NULL, NULL, 'cái', 37844.00, 9, '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(181, 'Que thử thai - Pack 31', NULL, NULL, 'cái', 45831.00, 33, '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(182, 'Que thử rụng trứng - Pack 32', NULL, NULL, 'cái', 49252.00, 16, '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(183, 'Kim tiêm 21G - Pack 33', NULL, NULL, 'cái', 6474.00, 12, '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(184, 'Kim tiêm 23G - Pack 34', NULL, NULL, 'cái', 12473.00, 34, '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(185, 'Khẩu trang N95 - Pack 35', NULL, NULL, 'cái', 7835.00, 23, '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(186, 'Khẩu trang y tế 3 lớp - Pack 36', NULL, NULL, 'cái', 4903.00, 48, '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(187, 'Gạc vô trùng 10x10 - Pack 37', NULL, NULL, 'cái', 39002.00, 29, '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(188, 'Túi đựng máu - Pack 38', NULL, NULL, 'cái', 33296.00, 18, '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(189, 'Hộp đựng dụng cụ tiểu phẫu - Pack 39', NULL, NULL, 'cái', 47332.00, 20, '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(190, 'Dao mổ 10 - Pack 40', NULL, NULL, 'cái', 10994.00, 39, '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(191, 'Dụng cụ lấy máu - Pack 41', NULL, NULL, 'cái', 41881.00, 9, '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(192, 'Bồn đựng mẫu - Pack 42', NULL, NULL, 'cái', 27750.00, 13, '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(193, 'Găng tay y tế S - Pack 43', NULL, NULL, 'cái', 27779.00, 41, '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(194, 'Găng tay y tế M - Pack 44', NULL, NULL, 'cái', 45932.00, 27, '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(195, 'Găng tay y tế L - Pack 45', NULL, NULL, 'cái', 42004.00, 49, '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(196, 'Bơm kim tiêm 1ml - Pack 46', NULL, NULL, 'cái', 29372.00, 31, '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(197, 'Bơm kim tiêm 5ml - Pack 47', NULL, NULL, 'cái', 28146.00, 49, '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(198, 'Bơm kim tiêm 10ml - Pack 48', NULL, NULL, 'cái', 6496.00, 13, '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(199, 'Bông gòn 50g - Pack 49', NULL, NULL, 'cái', 37354.00, 36, '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(200, 'Cồn 70% - Pack 50', NULL, NULL, 'cái', 12190.00, 28, '2025-12-12 07:45:56', '2025-12-12 07:50:15');

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
(1, 89, 'LOT-PN-1-689', '2028-06-12', 411, 1041.00, 1457.40, 8, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(2, 98, 'LOT-PN-1-902', '2028-11-12', 380, 5895.00, 8253.00, 8, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(3, 192, 'LOT-PN-1-127', '2026-12-12', 245, 4791.00, 6707.40, 8, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(4, 105, 'LOT-PN-1-103', '2028-10-12', 114, 3785.00, 5299.00, 8, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(5, 3, 'LOT-PN-1-630', '2028-06-12', 24, 1936.00, 2710.40, 8, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(6, 191, 'LOT-PN-1-148', '2027-10-12', 210, 7676.00, 10746.40, 8, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(7, 157, 'LOT-PN-1-7', '2027-07-12', 398, 3160.00, 4424.00, 8, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(8, 174, 'LOT-PN-1-774', '2027-04-12', 54, 1377.00, 1927.80, 8, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(9, 162, 'LOT-PN-1-16', '2027-01-12', 355, 4482.00, 6274.80, 8, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(10, 12, 'LOT-PN-1-268', '2027-03-12', 363, 9030.00, 12642.00, 8, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(11, 187, 'LOT-PN-1-631', '2028-01-12', 222, 6914.00, 9679.60, 8, '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(12, 80, 'LOT-PN-1-298', '2028-01-12', 82, 7346.00, 10284.40, 8, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(13, 183, 'LOT-PN-2-856', '2026-12-12', 68, 7057.00, 9879.80, 10, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(14, 117, 'LOT-PN-2-690', '2028-05-12', 65, 9108.00, 12751.20, 10, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(15, 188, 'LOT-PN-2-659', '2028-09-12', 371, 2811.00, 3935.40, 10, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(16, 41, 'LOT-PN-2-284', '2028-12-12', 186, 9117.00, 12763.80, 10, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(17, 139, 'LOT-PN-2-285', '2028-10-12', 464, 9437.00, 13211.80, 10, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(18, 177, 'LOT-PN-2-449', '2027-12-12', 360, 6127.00, 8577.80, 10, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(19, 174, 'LOT-PN-2-415', '2028-04-12', 224, 3813.00, 5338.20, 10, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(20, 163, 'LOT-PN-2-676', '2027-04-12', 483, 6503.00, 9104.20, 10, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(21, 6, 'LOT-PN-2-864', '2028-09-12', 450, 5412.00, 7576.80, 10, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(22, 168, 'LOT-PN-2-200', '2028-08-12', 146, 6734.00, 9427.60, 10, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(23, 110, 'LOT-PN-2-351', '2027-02-12', 224, 1401.00, 1961.40, 10, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(24, 180, 'LOT-PN-2-883', '2026-08-12', 147, 3055.00, 4277.00, 10, '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(25, 44, 'LOT-PN-2-463', '2027-06-12', 265, 1429.00, 2000.60, 10, '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(26, 35, 'LOT-PN-3-54', '2027-01-12', 150, 1554.00, 2175.60, 5, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(27, 4, 'LOT-PN-3-560', '2027-02-12', 256, 5425.00, 7595.00, 5, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(28, 128, 'LOT-PN-3-472', '2028-12-12', 239, 6622.00, 9270.80, 5, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(29, 8, 'LOT-PN-3-700', '2026-12-12', 70, 7531.00, 10543.40, 5, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(30, 107, 'LOT-PN-3-556', '2028-01-12', 293, 9358.00, 13101.20, 5, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(31, 142, 'LOT-PN-3-441', '2028-01-12', 414, 7792.00, 10908.80, 5, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(32, 81, 'LOT-PN-3-785', '2028-07-12', 445, 8030.00, 11242.00, 5, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(33, 78, 'LOT-PN-3-878', '2026-07-12', 380, 9116.00, 12762.40, 5, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(34, 80, 'LOT-PN-3-554', '2027-01-12', 305, 827.00, 1157.80, 5, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(35, 17, 'LOT-PN-3-923', '2028-05-12', 381, 862.00, 1206.80, 5, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(36, 73, 'LOT-PN-3-474', '2026-12-12', 37, 8159.00, 11422.60, 5, '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(37, 98, 'LOT-PN-3-366', '2027-08-12', 180, 8328.00, 11659.20, 5, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(38, 125, 'LOT-PN-4-362', '2027-02-12', 277, 9504.00, 13305.60, 1, '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(39, 181, 'LOT-PN-4-859', '2027-03-12', 337, 7429.00, 10400.60, 1, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(40, 87, 'LOT-PN-4-399', '2028-03-12', 369, 5620.00, 7868.00, 1, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(41, 166, 'LOT-PN-4-879', '2028-11-12', 483, 9326.00, 13056.40, 1, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(42, 172, 'LOT-PN-4-291', '2027-01-12', 233, 4102.00, 5742.80, 1, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(43, 64, 'LOT-PN-4-690', '2027-11-12', 139, 7964.00, 11149.60, 1, '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(44, 23, 'LOT-PN-4-812', '2028-02-12', 144, 3359.00, 4702.60, 1, '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(45, 117, 'LOT-PN-4-864', '2026-09-12', 381, 6614.00, 9259.60, 1, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(46, 127, 'LOT-PN-4-68', '2028-05-12', 11, 4530.00, 6342.00, 1, '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(47, 183, 'LOT-PN-4-488', '2027-05-12', 270, 9587.00, 13421.80, 1, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(48, 108, 'LOT-PN-4-548', '2026-10-12', 270, 5446.00, 7624.40, 1, '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(49, 40, 'LOT-PN-4-551', '2028-06-12', 185, 2568.00, 3595.20, 1, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(50, 161, 'LOT-PN-5-91', '2028-11-12', 74, 3359.00, 4702.60, 13, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(51, 141, 'LOT-PN-5-354', '2027-06-12', 271, 8679.00, 12150.60, 13, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(52, 168, 'LOT-PN-5-58', '2027-05-12', 390, 2406.00, 3368.40, 13, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(53, 95, 'LOT-PN-5-19', '2027-10-12', 337, 2257.00, 3159.80, 13, '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(54, 80, 'LOT-PN-5-913', '2027-05-12', 294, 7192.00, 10068.80, 13, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(55, 23, 'LOT-PN-5-788', '2028-12-12', 129, 3361.00, 4705.40, 13, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(56, 91, 'LOT-PN-5-952', '2027-07-12', 191, 2299.00, 3218.60, 13, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(57, 3, 'LOT-PN-5-165', '2027-12-12', 142, 3417.00, 4783.80, 13, '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(58, 63, 'LOT-PN-5-538', '2027-07-12', 186, 7428.00, 10399.20, 13, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(59, 166, 'LOT-PN-5-20', '2028-08-12', 63, 4412.00, 6176.80, 13, '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(60, 138, 'LOT-PN-5-688', '2027-05-12', 57, 1139.00, 1594.60, 13, '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(61, 103, 'LOT-PN-5-342', '2028-12-12', 281, 5778.00, 8089.20, 13, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(62, 163, 'LOT-PN-5-135', '2027-06-12', 423, 6183.00, 8656.20, 13, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(63, 104, 'LOT-PN-5-217', '2026-06-12', 308, 6413.00, 8978.20, 13, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(64, 24, 'LOT-PN-5-66', '2026-09-12', 303, 8676.00, 12146.40, 13, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(65, 133, 'LOT-PN-6-672', '2028-04-12', 41, 1293.00, 1810.20, 12, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(66, 18, 'LOT-PN-6-676', '2027-02-12', 57, 5281.00, 7393.40, 12, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(67, 115, 'LOT-PN-6-151', '2026-07-12', 315, 7642.00, 10698.80, 12, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(68, 55, 'LOT-PN-6-383', '2026-09-12', 268, 7660.00, 10724.00, 12, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(69, 119, 'LOT-PN-6-128', '2027-10-12', 264, 6228.00, 8719.20, 12, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(70, 30, 'LOT-PN-6-964', '2027-05-12', 40, 4526.00, 6336.40, 12, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(71, 7, 'LOT-PN-6-593', '2028-03-12', 418, 6746.00, 9444.40, 12, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(72, 93, 'LOT-PN-6-486', '2027-05-12', 289, 8303.00, 11624.20, 12, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(73, 3, 'LOT-PN-7-461', '2027-12-12', 259, 9186.00, 12860.40, 1, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(74, 126, 'LOT-PN-7-13', '2028-07-12', 37, 5775.00, 8085.00, 1, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(75, 43, 'LOT-PN-7-38', '2027-06-12', 149, 5042.00, 7058.80, 1, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(76, 29, 'LOT-PN-7-642', '2027-04-12', 364, 4800.00, 6720.00, 1, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(77, 199, 'LOT-PN-7-686', '2028-11-12', 259, 615.00, 861.00, 1, '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(78, 4, 'LOT-PN-7-405', '2028-04-12', 471, 4115.00, 5761.00, 1, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(79, 154, 'LOT-PN-8-414', '2026-09-12', 348, 7093.00, 9930.20, 10, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(80, 41, 'LOT-PN-8-609', '2027-03-12', 454, 3811.00, 5335.40, 10, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(81, 131, 'LOT-PN-8-398', '2027-11-12', 499, 6676.00, 9346.40, 10, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(82, 172, 'LOT-PN-8-123', '2028-07-12', 65, 7354.00, 10295.60, 10, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(83, 198, 'LOT-PN-8-280', '2026-06-12', 85, 8710.00, 12194.00, 10, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(84, 108, 'LOT-PN-8-231', '2028-01-12', 411, 2839.00, 3974.60, 10, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(85, 182, 'LOT-PN-8-201', '2026-10-12', 227, 8768.00, 12275.20, 10, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(86, 125, 'LOT-PN-8-307', '2028-08-12', 350, 3386.00, 4740.40, 10, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(87, 76, 'LOT-PN-8-426', '2027-11-12', 140, 6111.00, 8555.40, 10, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(88, 175, 'LOT-PN-9-446', '2027-03-12', 89, 7865.00, 11011.00, 5, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(89, 2, 'LOT-PN-9-380', '2027-07-12', 394, 1382.00, 1934.80, 5, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(90, 28, 'LOT-PN-9-15', '2026-12-12', 122, 6288.00, 8803.20, 5, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(91, 69, 'LOT-PN-9-216', '2027-07-12', 266, 8995.00, 12593.00, 5, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(92, 186, 'LOT-PN-9-13', '2027-06-12', 27, 6228.00, 8719.20, 5, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(93, 77, 'LOT-PN-9-191', '2028-05-12', 63, 6716.00, 9402.40, 5, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(94, 115, 'LOT-PN-9-513', '2027-02-12', 284, 8520.00, 11928.00, 5, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(95, 197, 'LOT-PN-9-533', '2026-08-12', 0, 9506.00, 13308.40, 5, '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(96, 127, 'LOT-PN-10-897', '2027-01-12', 0, 3926.00, 5496.40, 1, '2025-12-12 07:45:56', '2025-12-12 07:50:15'),
(97, 103, 'LOT-PN-10-433', '2028-09-12', 466, 4010.00, 5614.00, 1, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(98, 9, 'LOT-PN-10-245', '2026-06-12', 469, 4746.00, 6644.40, 1, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(99, 56, 'LOT-PN-10-52', '2028-09-12', 327, 3685.00, 5159.00, 1, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(100, 137, 'LOT-PN-10-313', '2028-11-12', 144, 4278.00, 5989.20, 1, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(101, 30, 'LOT-PN-10-581', '2028-10-12', 217, 8069.00, 11296.60, 1, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(102, 73, 'LOT-PN-10-265', '2028-01-12', 270, 3848.00, 5387.20, 1, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(103, 39, 'LOT-PN-10-945', '2028-10-12', 0, 5714.00, 7999.60, 1, '2025-12-12 07:45:56', '2025-12-12 07:45:56'),
(104, 103, 'LOT-PN-1-352', '2028-07-12', 275, 3139.00, 4394.60, 4, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(105, 149, 'LOT-PN-1-257', '2028-02-12', 28, 2029.00, 2840.60, 4, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(106, 58, 'LOT-PN-1-267', '2026-12-12', 101, 5358.00, 7501.20, 4, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(107, 108, 'LOT-PN-1-567', '2027-07-12', 141, 4768.00, 6675.20, 4, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(108, 47, 'LOT-PN-1-223', '2026-11-12', 431, 4026.00, 5636.40, 4, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(109, 115, 'LOT-PN-1-373', '2028-05-12', 463, 6612.00, 9256.80, 4, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(110, 196, 'LOT-PN-1-32', '2028-03-12', 198, 6119.00, 8566.60, 4, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(111, 144, 'LOT-PN-1-221', '2027-06-12', 64, 9025.00, 12635.00, 4, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(112, 8, 'LOT-PN-1-101', '2028-12-12', 115, 2325.00, 3255.00, 4, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(113, 161, 'LOT-PN-2-763', '2026-11-12', 122, 3801.00, 5321.40, 3, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(114, 40, 'LOT-PN-2-264', '2027-09-12', 409, 9887.00, 13841.80, 3, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(115, 80, 'LOT-PN-2-482', '2028-12-12', 451, 5703.00, 7984.20, 3, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(116, 32, 'LOT-PN-2-370', '2026-12-12', 356, 6376.00, 8926.40, 3, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(117, 15, 'LOT-PN-2-709', '2026-06-12', 415, 9543.00, 13360.20, 3, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(118, 45, 'LOT-PN-2-81', '2027-06-12', 34, 8571.00, 11999.40, 3, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(119, 19, 'LOT-PN-2-234', '2028-01-12', 70, 5079.00, 7110.60, 3, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(120, 14, 'LOT-PN-2-583', '2026-06-12', 45, 3536.00, 4950.40, 3, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(121, 77, 'LOT-PN-2-570', '2028-07-12', 365, 3055.00, 4277.00, 3, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(122, 67, 'LOT-PN-2-25', '2028-06-12', 69, 4490.00, 6286.00, 3, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(123, 46, 'LOT-PN-2-303', '2028-02-12', 397, 7987.00, 11181.80, 3, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(124, 195, 'LOT-PN-2-962', '2028-12-12', 435, 4049.00, 5668.60, 3, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(125, 71, 'LOT-PN-2-949', '2026-12-12', 93, 8193.00, 11470.20, 3, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(126, 192, 'LOT-PN-2-128', '2026-07-12', 74, 6734.00, 9427.60, 3, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(127, 180, 'LOT-PN-3-375', '2028-03-12', 187, 3716.00, 5202.40, 13, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(128, 136, 'LOT-PN-3-490', '2028-07-12', 280, 6185.00, 8659.00, 13, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(129, 87, 'LOT-PN-3-377', '2027-08-12', 159, 1389.00, 1944.60, 13, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(130, 181, 'LOT-PN-3-243', '2026-10-12', 448, 3177.00, 4447.80, 13, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(131, 158, 'LOT-PN-3-704', '2028-09-12', 341, 9674.00, 13543.60, 13, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(132, 83, 'LOT-PN-3-720', '2028-10-12', 85, 4419.00, 6186.60, 13, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(133, 61, 'LOT-PN-4-959', '2027-11-12', 65, 3875.00, 5425.00, 7, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(134, 149, 'LOT-PN-4-183', '2028-12-12', 186, 9211.00, 12895.40, 7, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(135, 78, 'LOT-PN-4-349', '2027-09-12', 373, 4153.00, 5814.20, 7, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(136, 1, 'LOT-PN-4-711', '2028-09-12', 124, 3451.00, 4831.40, 7, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(137, 29, 'LOT-PN-4-198', '2027-12-12', 249, 6723.00, 9412.20, 7, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(138, 164, 'LOT-PN-4-979', '2027-12-12', 245, 6731.00, 9423.40, 7, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(139, 42, 'LOT-PN-4-613', '2027-10-12', 342, 1761.00, 2465.40, 7, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(140, 131, 'LOT-PN-4-736', '2028-11-12', 244, 7153.00, 10014.20, 7, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(141, 56, 'LOT-PN-4-372', '2028-07-12', 265, 2402.00, 3362.80, 7, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(142, 53, 'LOT-PN-4-856', '2027-09-12', 180, 696.00, 974.40, 7, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(143, 58, 'LOT-PN-5-49', '2027-06-12', 39, 8195.00, 11473.00, 7, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(144, 78, 'LOT-PN-5-944', '2026-09-12', 83, 3229.00, 4520.60, 7, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(145, 41, 'LOT-PN-5-743', '2026-12-12', 37, 1766.00, 2472.40, 7, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(146, 129, 'LOT-PN-5-381', '2028-04-12', 105, 824.00, 1153.60, 7, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(147, 127, 'LOT-PN-5-476', '2028-09-12', 31, 1378.00, 1929.20, 7, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(148, 198, 'LOT-PN-5-945', '2028-11-12', 286, 7328.00, 10259.20, 7, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(149, 54, 'LOT-PN-5-486', '2028-04-12', 69, 8518.00, 11925.20, 7, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(150, 19, 'LOT-PN-5-41', '2028-04-12', 140, 3082.00, 4314.80, 7, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(151, 43, 'LOT-PN-5-259', '2026-11-12', 139, 5165.00, 7231.00, 7, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(152, 116, 'LOT-PN-5-467', '2027-02-12', 99, 8065.00, 11291.00, 7, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(153, 51, 'LOT-PN-5-844', '2026-07-12', 496, 2034.00, 2847.60, 7, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(154, 48, 'LOT-PN-5-988', '2026-08-12', 102, 9676.00, 13546.40, 7, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(155, 99, 'LOT-PN-6-528', '2028-02-12', 189, 7558.00, 10581.20, 12, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(156, 123, 'LOT-PN-6-550', '2027-02-12', 309, 4054.00, 5675.60, 12, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(157, 142, 'LOT-PN-6-614', '2027-03-12', 153, 9059.00, 12682.60, 12, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(158, 20, 'LOT-PN-6-643', '2026-10-12', 37, 1122.00, 1570.80, 12, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(159, 28, 'LOT-PN-6-763', '2027-08-12', 358, 985.00, 1379.00, 12, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(160, 179, 'LOT-PN-6-623', '2028-02-12', 77, 3395.00, 4753.00, 12, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(161, 138, 'LOT-PN-6-792', '2028-05-12', 307, 7821.00, 10949.40, 12, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(162, 32, 'LOT-PN-6-22', '2028-08-12', 231, 1379.00, 1930.60, 12, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(163, 160, 'LOT-PN-7-658', '2027-02-12', 414, 9033.00, 12646.20, 14, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(164, 50, 'LOT-PN-7-582', '2026-10-12', 160, 4066.00, 5692.40, 14, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(165, 13, 'LOT-PN-7-245', '2026-12-12', 124, 3268.00, 4575.20, 14, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(166, 49, 'LOT-PN-7-879', '2028-02-12', 356, 8377.00, 11727.80, 14, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(167, 90, 'LOT-PN-7-60', '2028-04-12', 70, 2692.00, 3768.80, 14, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(168, 41, 'LOT-PN-7-189', '2028-02-12', 261, 7870.00, 11018.00, 14, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(169, 121, 'LOT-PN-7-650', '2027-07-12', 494, 9309.00, 13032.60, 14, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(170, 36, 'LOT-PN-7-709', '2027-01-12', 445, 8001.00, 11201.40, 14, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(171, 35, 'LOT-PN-7-652', '2026-09-12', 499, 8813.00, 12338.20, 14, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(172, 52, 'LOT-PN-7-253', '2027-06-12', 440, 9588.00, 13423.20, 14, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(173, 116, 'LOT-PN-7-778', '2027-02-12', 191, 3084.00, 4317.60, 14, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(174, 29, 'LOT-PN-8-595', '2028-03-12', 335, 8335.00, 11669.00, 4, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(175, 167, 'LOT-PN-8-808', '2027-03-12', 315, 5085.00, 7119.00, 4, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(176, 141, 'LOT-PN-8-516', '2027-07-12', 78, 6483.00, 9076.20, 4, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(177, 51, 'LOT-PN-8-804', '2028-12-12', 376, 688.00, 963.20, 4, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(178, 84, 'LOT-PN-8-460', '2028-04-12', 198, 3644.00, 5101.60, 4, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(179, 194, 'LOT-PN-8-109', '2027-12-12', 301, 6826.00, 9556.40, 4, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(180, 139, 'LOT-PN-8-780', '2027-02-12', 242, 4861.00, 6805.40, 4, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(181, 40, 'LOT-PN-8-145', '2026-10-12', 143, 9249.00, 12948.60, 4, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(182, 62, 'LOT-PN-8-840', '2028-10-12', 352, 7776.00, 10886.40, 4, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(183, 11, 'LOT-PN-8-266', '2028-07-12', 392, 646.00, 904.40, 4, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(184, 97, 'LOT-PN-8-889', '2027-03-12', 232, 9635.00, 13489.00, 4, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(185, 30, 'LOT-PN-8-643', '2027-02-12', 67, 1774.00, 2483.60, 4, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(186, 4, 'LOT-PN-9-68', '2027-01-12', 62, 3302.00, 4622.80, 13, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(187, 17, 'LOT-PN-9-741', '2028-12-12', 364, 7170.00, 10038.00, 13, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(188, 16, 'LOT-PN-9-545', '2027-10-12', 348, 1529.00, 2140.60, 13, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(189, 195, 'LOT-PN-9-951', '2026-10-12', 231, 8185.00, 11459.00, 13, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(190, 189, 'LOT-PN-9-843', '2027-07-12', 339, 1698.00, 2377.20, 13, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(191, 170, 'LOT-PN-9-359', '2026-08-12', 251, 634.00, 887.60, 13, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(192, 11, 'LOT-PN-9-94', '2028-08-12', 284, 9357.00, 13099.80, 13, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(193, 7, 'LOT-PN-9-892', '2027-12-12', 39, 6046.00, 8464.40, 13, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(194, 180, 'LOT-PN-9-622', '2028-03-12', 69, 9409.00, 13172.60, 13, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(195, 89, 'LOT-PN-9-194', '2027-10-12', 246, 9939.00, 13914.60, 13, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(196, 108, 'LOT-PN-9-378', '2027-04-12', 209, 1140.00, 1596.00, 13, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(197, 164, 'LOT-PN-9-270', '2027-03-12', 268, 1823.00, 2552.20, 13, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(198, 88, 'LOT-PN-9-11', '2027-05-12', 328, 9406.00, 13168.40, 13, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(199, 3, 'LOT-PN-9-1', '2028-03-12', 166, 7252.00, 10152.80, 13, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(200, 108, 'LOT-PN-10-118', '2027-11-12', 419, 7556.00, 10578.40, 15, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(201, 81, 'LOT-PN-10-545', '2028-09-12', 101, 1470.00, 2058.00, 15, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(202, 115, 'LOT-PN-10-89', '2026-07-12', 457, 7434.00, 10407.60, 15, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(203, 136, 'LOT-PN-10-207', '2026-11-12', 296, 8796.00, 12314.40, 15, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(204, 65, 'LOT-PN-10-145', '2028-06-12', 283, 2269.00, 3176.60, 15, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(205, 76, 'LOT-PN-10-58', '2026-08-12', 128, 5891.00, 8247.40, 15, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(206, 193, 'LOT-PN-10-172', '2027-10-12', 291, 1813.00, 2538.20, 15, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(207, 83, 'LOT-NEAR-5202', '2025-12-20', 8, 8396.00, 10914.80, 6, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(208, 39, 'LOT-NEAR-7726', '2025-12-25', 8, 6270.00, 8151.00, 9, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(209, 80, 'LOT-NEAR-6288', '2025-12-17', 6, 3405.00, 4426.50, 9, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(210, 52, 'LOT-NEAR-6570', '2025-12-19', 6, 1876.00, 2438.80, 2, '2025-12-12 07:50:15', '2025-12-12 07:50:15'),
(211, 153, 'LOT-NEAR-7854', '2025-12-23', 6, 41995.00, 54593.50, 9, '2025-12-12 07:50:15', '2025-12-12 07:50:15');

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
(13, 'Admin', 'Admin@gmail.com', NULL, NULL, NULL, NULL, NULL, '$2y$10$NfHct5bDMquy9sXw7Ja1UO.x3IaJsaZbJEUJKbWgUN3UZlniSiWx6', NULL, NULL, NULL, 0, '2025-12-13 14:07:18', 0, '127.0.0.1', '2025-12-12 04:24:09', '2025-12-13 14:07:18', 'admin'),
(115, 'Nguyễn Thị Hồng Hạnh', 'hanh.nguyen@vietcare.com', NULL, '0909222002', '2000-10-20', 'Nữ', '2025-12-12 06:47:23', '$2y$10$0bTwsv5YVaRWfHHa9SO4YeO5IxvYBGtl3EFrx8.UlYUYXC4o5TB6.', NULL, NULL, NULL, 0, NULL, 0, NULL, '2025-12-12 06:41:13', '2025-12-12 06:47:23', 'staff'),
(116, 'Trần Thị Kim Dung', 'dung.tran@vietcare.com', NULL, '0909222003', '1999-08-15', 'Nữ', '2025-12-12 06:47:23', '$2y$10$pnnjtuzIGJ8KI0w4CUNwQO1f5Whl04xNgESdzn52nIGwxDzeEG7ne', NULL, NULL, NULL, 0, NULL, 0, NULL, '2025-12-12 06:41:13', '2025-12-12 06:47:23', 'staff'),
(117, 'Phạm Thị Thu Thảo', 'thao.pham@vietcare.com', NULL, '0909222004', '1985-02-05', 'Nữ', '2025-12-12 06:47:24', '$2y$10$2pQLqCpww3anC3eTP5gMBu2nEduMNMMMJB3nnmn7.4kjBz8VD88la', NULL, NULL, NULL, 0, NULL, 0, NULL, '2025-12-12 06:41:13', '2025-12-12 06:47:24', 'staff'),
(118, 'Lê Thị Thanh Trúc', 'truc.le@vietcare.com', NULL, '0909222005', '1995-11-10', 'Nữ', '2025-12-12 06:47:24', '$2y$10$6yAjv0SgSucUjTBdrBf9eOvPTbjVBKJlTm0SrCjYv/kbp69zga/l2', NULL, NULL, NULL, 0, NULL, 0, NULL, '2025-12-12 06:41:13', '2025-12-12 06:47:24', 'staff'),
(119, 'Nguyễn Ngọc Huyền', 'huyen.nguyen@vietcare.com', NULL, '0909222006', '1997-07-22', 'Nữ', '2025-12-12 06:47:24', '$2y$10$Oy5Rs07j.BdaHI3olyot/eP/uuSYri/IgAg2xTGuFT52JEe1KYCJe', NULL, NULL, NULL, 0, NULL, 0, NULL, '2025-12-12 06:41:13', '2025-12-12 06:47:24', 'staff'),
(120, 'Vũ Thị Minh Anh', 'anh.vu@vietcare.com', NULL, '0909222007', '1996-04-30', 'Nữ', '2025-12-12 06:47:24', '$2y$10$SaJ574BFnjJXE2Zfa2rTXeKLFSYAiXukn5VQ1024wTO1RemgQ7wIu', NULL, NULL, NULL, 0, NULL, 0, NULL, '2025-12-12 06:41:14', '2025-12-12 06:47:24', 'staff'),
(121, 'Hoàng Văn Nam', 'nam.hoang@vietcare.com', NULL, '0909222008', '1990-09-14', 'Nam', '2025-12-12 06:47:24', '$2y$10$0ames9TDSX5.k5I4WhEgJeh2V/illnPcH2tdvJ8BVX23HhSlwOIYK', NULL, NULL, NULL, 0, NULL, 0, NULL, '2025-12-12 06:41:14', '2025-12-12 06:47:24', 'staff'),
(122, 'Nguyễn Thu Phương', 'phuong.nguyen@vietcare.com', NULL, '0909222009', '1980-01-01', 'Nữ', '2025-12-12 06:47:24', '$2y$10$sYa/q9dfXYLnDia283gKK.OHr.kmPB0ns8hFcEFTdkJ5EF65rkRwq', NULL, NULL, NULL, 0, NULL, 0, NULL, '2025-12-12 06:41:14', '2025-12-12 06:47:24', 'staff'),
(123, 'Trần Quốc Bảo', 'bao.tran@vietcare.com', NULL, '0909222010', '1995-12-18', 'Nam', '2025-12-12 06:47:24', '$2y$10$Nq1Fl0NEseVCuKc166AAkuxmJwnFI5m46s67eomOkKuN55bPM/PYe', NULL, NULL, NULL, 0, NULL, 0, NULL, '2025-12-12 06:41:14', '2025-12-12 06:47:24', 'staff'),
(136, 'TS.BS Nguyễn Thị Lan Anh', 'lananh@vietcare.com', 'avatars/NgwECmcOF5OsDRW5m7U47jEgpIwpJNGv9yBECZpz.png', '0909111001', NULL, NULL, '2025-12-12 07:01:42', '$2y$10$8pfnJ6dIyePif5beHwu5lOCc6NaUKdwBT0/0nUFckm./daq46SLkK', NULL, NULL, NULL, 0, '2025-12-13 04:44:39', 0, '127.0.0.1', '2025-12-12 07:01:42', '2025-12-13 04:45:00', 'doctor'),
(137, 'ThS.BS Phạm Văn Hùng', 'hunghoang@vietcare.com', 'avatars/Dc4rhJ9iMhQbGivllt2KH4SC3wcev2SoV5thMf9W.png', '0909111002', NULL, NULL, '2025-12-12 07:01:42', '$2y$10$Nj4x5jCYFIJ92xVxO5V5R.dg8suQFx.n1hdBxzFbnTyRn7CnhoBQC', NULL, NULL, NULL, 0, '2025-12-13 04:40:34', 0, '127.0.0.1', '2025-12-12 07:01:42', '2025-12-13 04:40:34', 'doctor'),
(138, 'BSCKII Trần Thu Hà', 'hatran@vietcare.com', 'avatars/msxfncVI0Y4IfkPa2P3Akg9wViu4eg8BkeeFI1kB.png', '0909111003', NULL, NULL, '2025-12-12 07:01:42', '$2y$10$QxndpifIYQP54mLB76BR3ebmq.immmidNdR0QDbL26TKpePzOt96S', NULL, NULL, NULL, 0, '2025-12-13 04:45:17', 0, '127.0.0.1', '2025-12-12 07:01:42', '2025-12-13 04:45:33', 'doctor'),
(139, 'BS.CKI Nguyễn Thanh Vân', 'vannguyen@vietcare.com', 'avatars/Kn1lryOqsrl2hnJ2oZxSUZR41NdgYBACo1Bn2A2t.png', '0909111004', NULL, NULL, '2025-12-12 07:01:42', '$2y$10$WHqtKBUR7cHm9Zwkb4vQNurFIzJZ63QU7jPXdFiNYyLkxbTYiBR3i', NULL, NULL, NULL, 0, '2025-12-13 14:09:29', 0, '127.0.0.1', '2025-12-12 07:01:42', '2025-12-13 14:09:29', 'doctor'),
(140, 'TS.BS Hoàng Minh Tuấn', 'tuanhoang@vietcare.com', 'avatars/tFhDIqyGiJGubaeaPIwtHzZhVQKWUQl9ckcKskDv.png', '0909111005', NULL, NULL, '2025-12-12 07:01:42', '$2y$10$QanY1YS97ZhkvemCFYHgB.NvF6R2.k.XeQ470m5S2EVCwssbe8t8a', NULL, NULL, NULL, 0, '2025-12-13 04:46:20', 0, '127.0.0.1', '2025-12-12 07:01:42', '2025-12-13 04:46:35', 'doctor'),
(141, 'ThS.BS Võ Thị Ngọc', 'ngocvo@vietcare.com', 'avatars/javPWxwi07wRO58pqLeC79LKbHTmU1zEmb5CkZA4.png', '0909111006', NULL, NULL, '2025-12-12 07:01:42', '$2y$10$hmx2XrhGvrLBQziqJLlRquBLA2uFMb1DGKdNq8Kc/WG7paVxw.FDC', NULL, NULL, NULL, 0, '2025-12-13 04:43:11', 0, '127.0.0.1', '2025-12-12 07:01:42', '2025-12-13 04:43:30', 'doctor'),
(142, 'BS.CKI Phạm Thanh Thúy', 'thuypham@vietcare.com', 'avatars/7Am7ipu0EktY6wXSN9cWfcRtVNtBNWiFqcAToFY6.png', '0909111007', NULL, NULL, '2025-12-12 07:01:42', '$2y$10$8FVpMIeapC0rxEe/zMPIpeE/B8bdZEFK1QVDmEErDSCWMpNvAShTC', NULL, NULL, NULL, 0, '2025-12-13 04:43:44', 0, '127.0.0.1', '2025-12-12 07:01:42', '2025-12-13 04:43:57', 'doctor'),
(143, 'ThS.BS Nguyễn Hữu Phước', 'phuocnguyen@vietcare.com', 'avatars/RgY8tGvX7ZN7Q6Uar9Z5JF7hhcyxWgsgHpzMkK2m.png', '0909111008', NULL, NULL, '2025-12-12 07:01:43', '$2y$10$rUeQPUJR1UBLTOV7pYVGnON/fpUbm/VAe203gjQdMkDY4V54nqHlu', NULL, NULL, NULL, 0, '2025-12-13 04:49:51', 0, '127.0.0.1', '2025-12-12 07:01:43', '2025-12-13 04:49:51', 'doctor'),
(144, 'BS.CKI Đỗ Mỹ Linh', 'linhdo@vietcare.com', 'avatars/rSIA5L9IxPl7e3ckaiw8iGmiuiajr6Cc8e6GZLYo.png', '0909111009', NULL, NULL, '2025-12-12 07:01:43', '$2y$10$srWKZNsaJPNvYzLwD9Smh.1I5GILdRRYrUqGCIfiz6xvRhBtdd8am', NULL, NULL, NULL, 0, '2025-12-13 04:47:38', 0, '127.0.0.1', '2025-12-12 07:01:43', '2025-12-13 04:47:49', 'doctor'),
(145, 'ThS.BS Lê Thị Mai', 'maile@vietcare.com', 'avatars/vF1nqgNi2AZSwjxA7ii2rz5xqH1KPzDjDqeHD8nE.png', '0909111010', NULL, NULL, '2025-12-12 07:01:43', '$2y$10$.5xZRHSCsi2P2K4iI8kT5OTXkZWIYa/hxtfvMPslpwvbIz5u5hxpK', NULL, NULL, NULL, 0, '2025-12-13 04:48:08', 0, '127.0.0.1', '2025-12-12 07:01:43', '2025-12-13 04:48:19', 'doctor'),
(146, 'BS.CKI Trần Văn Minh', 'minh.xetnghiem@vietcare.com', 'avatars/WkE01UOSwyyKFNjyZVn0lMBkR8lkTfFBCKc0UE4B.png', '0909111011', NULL, NULL, '2025-12-12 07:01:43', '$2y$10$SXhcWBt41QYSMe1TxfadIOvVYxYGRO0TL.SlYex45PHQpzB2B.uPC', NULL, NULL, NULL, 0, '2025-12-13 04:48:32', 0, '127.0.0.1', '2025-12-12 07:01:43', '2025-12-13 04:48:46', 'doctor'),
(147, 'ThS.BS Nguyễn Ngọc Lan', 'lan.thammy@vietcare.com', 'avatars/wlRgm2tm7BDT0t0gOOmYwIBEvVFpYVTRkxpq4Vaf.png', '0909111012', NULL, NULL, '2025-12-12 07:01:43', '$2y$10$d7gui7/U19F8BC.imDqrh.63JAb77EgMRah8VO1MtrNQdsQMnqCRm', NULL, NULL, NULL, 0, '2025-12-13 04:49:04', 0, '127.0.0.1', '2025-12-12 07:01:43', '2025-12-13 04:49:17', 'doctor'),
(148, 'Nguyễn Chí Thanh', 'tn822798@gmail.com', NULL, NULL, NULL, NULL, NULL, '$2y$10$GGgNSGZW72uejUeDxFsv/eaD0bJkyfVS9DpBO2AaH2jVqcSFCA90W', NULL, NULL, NULL, 0, '2025-12-13 14:08:56', 0, '127.0.0.1', '2025-12-12 08:39:23', '2025-12-13 14:08:56', 'patient'),
(149, 'Lê Minh Nhật', 'henvaemhen@gmail.com', NULL, NULL, NULL, NULL, '2025-12-13 04:32:47', '$2y$10$2zaIsqKua20VhsyfMioL3OeatZNmM5d5GYAllSyMDQwUx8juNVeli', '1DoL7fJP5uqtiouM5a0MekQKrHzhmE3wHxYxpddngtXfAo2vi8BQMDb4Dr5R', NULL, NULL, 0, '2025-12-13 14:08:24', 0, '127.0.0.1', '2025-12-13 04:13:11', '2025-12-13 14:08:24', 'staff');

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
  `trang_thai` varchar(255) NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `xet_nghiems`
--

INSERT INTO `xet_nghiems` (`id`, `benh_an_id`, `user_id`, `bac_si_id`, `loai`, `file_path`, `disk`, `mo_ta`, `trang_thai`, `created_at`, `updated_at`) VALUES
(1, 1, 148, 108, 'Xét nghiệm máu', '', 'public', 'Test', 'pending', '2025-12-13 07:58:37', '2025-12-13 07:58:37'),
(2, 1, 148, 108, 'Testt', 'xet_nghiem/JIeBG21s60GHGm8WEVPOnIH3Dr3U0NFrI7CHtqEB.png', 'benh_an_private', NULL, 'pending', '2025-12-13 08:04:38', '2025-12-13 08:04:38');

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
-- Chỉ mục cho bảng `family_members`
--
ALTER TABLE `family_members`
  ADD PRIMARY KEY (`id`),
  ADD KEY `family_members_user_id_foreign` (`user_id`);

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
  ADD KEY `lich_hens_ngay_gio_idx` (`ngay_hen`,`thoi_gian_hen`);

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
-- AUTO_INCREMENT cho bảng `activity_log`
--
ALTER TABLE `activity_log`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `bac_sis`
--
ALTER TABLE `bac_sis`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=118;

--
-- AUTO_INCREMENT cho bảng `bai_viets`
--
ALTER TABLE `bai_viets`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `benh_ans`
--
ALTER TABLE `benh_ans`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT cho bảng `benh_an_audits`
--
ALTER TABLE `benh_an_audits`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=142;

--
-- AUTO_INCREMENT cho bảng `chuyen_khoas`
--
ALTER TABLE `chuyen_khoas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT cho bảng `chuyen_khoa_dich_vu`
--
ALTER TABLE `chuyen_khoa_dich_vu`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=267;

--
-- AUTO_INCREMENT cho bảng `conversations`
--
ALTER TABLE `conversations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `coupons`
--
ALTER TABLE `coupons`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT cho bảng `danh_gias`
--
ALTER TABLE `danh_gias`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `danh_mucs`
--
ALTER TABLE `danh_mucs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `dich_vus`
--
ALTER TABLE `dich_vus`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=150;

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
-- AUTO_INCREMENT cho bảng `family_members`
--
ALTER TABLE `family_members`
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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT cho bảng `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `lich_hens`
--
ALTER TABLE `lich_hens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT cho bảng `lich_lam_viecs`
--
ALTER TABLE `lich_lam_viecs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=172;

--
-- AUTO_INCREMENT cho bảng `lich_nghis`
--
ALTER TABLE `lich_nghis`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `login_audits`
--
ALTER TABLE `login_audits`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;

--
-- AUTO_INCREMENT cho bảng `messages`
--
ALTER TABLE `messages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=89;

--
-- AUTO_INCREMENT cho bảng `nhan_viens`
--
ALTER TABLE `nhan_viens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT cho bảng `nhan_vien_audits`
--
ALTER TABLE `nhan_vien_audits`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT cho bảng `nha_cung_caps`
--
ALTER TABLE `nha_cung_caps`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT cho bảng `nha_cung_cap_thuoc`
--
ALTER TABLE `nha_cung_cap_thuoc`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=748;

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=207;

--
-- AUTO_INCREMENT cho bảng `phieu_xuats`
--
ALTER TABLE `phieu_xuats`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT cho bảng `phieu_xuat_items`
--
ALTER TABLE `phieu_xuat_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=118;

--
-- AUTO_INCREMENT cho bảng `phongs`
--
ALTER TABLE `phongs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT cho bảng `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT cho bảng `slot_locks`
--
ALTER TABLE `slot_locks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT cho bảng `tags`
--
ALTER TABLE `tags`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT cho bảng `thanh_toans`
--
ALTER TABLE `thanh_toans`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `thuocs`
--
ALTER TABLE `thuocs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=201;

--
-- AUTO_INCREMENT cho bảng `thuoc_khos`
--
ALTER TABLE `thuoc_khos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=212;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=152;

--
-- AUTO_INCREMENT cho bảng `xet_nghiems`
--
ALTER TABLE `xet_nghiems`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

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
  ADD CONSTRAINT `don_thuocs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Các ràng buộc cho bảng `don_thuoc_items`
--
ALTER TABLE `don_thuoc_items`
  ADD CONSTRAINT `don_thuoc_items_don_thuoc_id_foreign` FOREIGN KEY (`don_thuoc_id`) REFERENCES `don_thuocs` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `don_thuoc_items_thuoc_id_foreign` FOREIGN KEY (`thuoc_id`) REFERENCES `thuocs` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `family_members`
--
ALTER TABLE `family_members`
  ADD CONSTRAINT `family_members_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

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
-- Các ràng buộc cho bảng `slot_locks`
--
ALTER TABLE `slot_locks`
  ADD CONSTRAINT `slot_locks_bac_si_id_foreign` FOREIGN KEY (`bac_si_id`) REFERENCES `bac_sis` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `slot_locks_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

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
