<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * App\Models\BacSi
 *
 * @property int $id
 * @property int|null $user_id
 * @property string $ho_ten
 * @property string|null $email
 * @property string $chuyen_khoa
 * @property int $kinh_nghiem Số năm kinh nghiệm
 * @property string|null $mo_ta
 * @property string $trang_thai
 * @property string|null $so_dien_thoai
 * @property string|null $dia_chi
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CaDieuChinhBacSi> $caDieuChinhs
 * @property-read int|null $ca_dieu_chinhs_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ChuyenKhoa> $chuyenKhoas
 * @property-read int|null $chuyen_khoas_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\LichHen> $lichHens
 * @property-read int|null $lich_hens_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\LichLamViec> $lichLamViecs
 * @property-read int|null $lich_lam_viecs_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\LichNghi> $lichNghis
 * @property-read int|null $lich_nghis_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Phong> $phongs
 * @property-read int|null $phongs_count
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|BacSi newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BacSi newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BacSi query()
 * @method static \Illuminate\Database\Eloquent\Builder|BacSi whereChuyenKhoa($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BacSi whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BacSi whereDiaChi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BacSi whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BacSi whereHoTen($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BacSi whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BacSi whereKinhNghiem($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BacSi whereMoTa($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BacSi whereSoDienThoai($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BacSi whereTrangThai($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BacSi whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BacSi whereUserId($value)
 */
	class BacSi extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\BaiViet
 *
 * @property int $id
 * @property int $user_id
 * @property int|null $danh_muc_id
 * @property string $title
 * @property string $slug
 * @property string|null $excerpt
 * @property string $content
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $published_at
 * @property string|null $meta_title
 * @property string|null $meta_description
 * @property string|null $thumbnail
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $author
 * @property-read \App\Models\DanhMuc|null $danhMuc
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Tag> $tags
 * @property-read int|null $tags_count
 * @method static \Illuminate\Database\Eloquent\Builder|BaiViet newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BaiViet newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BaiViet published()
 * @method static \Illuminate\Database\Eloquent\Builder|BaiViet query()
 * @method static \Illuminate\Database\Eloquent\Builder|BaiViet whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BaiViet whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BaiViet whereDanhMucId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BaiViet whereExcerpt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BaiViet whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BaiViet whereMetaDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BaiViet whereMetaTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BaiViet wherePublishedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BaiViet whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BaiViet whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BaiViet whereThumbnail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BaiViet whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BaiViet whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BaiViet whereUserId($value)
 */
	class BaiViet extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\BenhAn
 *
 * @property int $id
 * @property int $user_id
 * @property int $bac_si_id
 * @property int|null $lich_hen_id
 * @property \Illuminate\Support\Carbon $ngay_kham
 * @property string $tieu_de
 * @property string|null $trieu_chung
 * @property string|null $chuan_doan
 * @property string|null $dieu_tri
 * @property string|null $ghi_chu
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\BenhAnAudit> $audits
 * @property-read int|null $audits_count
 * @property-read \App\Models\BacSi $bacSi
 * @property-read \App\Models\User $benhNhan
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\DonThuoc> $donThuocs
 * @property-read int|null $don_thuocs_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\BenhAnFile> $files
 * @property-read int|null $files_count
 * @property-read \App\Models\LichHen|null $lichHen
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\XetNghiem> $xetNghiems
 * @property-read int|null $xet_nghiems_count
 * @method static \Illuminate\Database\Eloquent\Builder|BenhAn newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BenhAn newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BenhAn query()
 * @method static \Illuminate\Database\Eloquent\Builder|BenhAn whereBacSiId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BenhAn whereChuanDoan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BenhAn whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BenhAn whereDieuTri($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BenhAn whereGhiChu($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BenhAn whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BenhAn whereLichHenId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BenhAn whereNgayKham($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BenhAn whereTieuDe($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BenhAn whereTrieuChung($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BenhAn whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BenhAn whereUserId($value)
 */
	class BenhAn extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\BenhAnAudit
 *
 * @property int $id
 * @property int $benh_an_id
 * @property int|null $user_id
 * @property string $action
 * @property array|null $old_values
 * @property array|null $new_values
 * @property string|null $ip_address
 * @property string|null $user_agent
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\BenhAn $benhAn
 * @property-read string $action_label
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|BenhAnAudit newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BenhAnAudit newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BenhAnAudit query()
 * @method static \Illuminate\Database\Eloquent\Builder|BenhAnAudit whereAction($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BenhAnAudit whereBenhAnId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BenhAnAudit whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BenhAnAudit whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BenhAnAudit whereIpAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BenhAnAudit whereNewValues($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BenhAnAudit whereOldValues($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BenhAnAudit whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BenhAnAudit whereUserAgent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BenhAnAudit whereUserId($value)
 */
	class BenhAnAudit extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\BenhAnFile
 *
 * @property int $id
 * @property int $benh_an_id
 * @property string $ten_file
 * @property string $path
 * @property string $disk
 * @property string|null $loai_mime
 * @property int|null $size_bytes
 * @property int|null $uploaded_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\BenhAn $benhAn
 * @property-read string $disk_name
 * @property-read string $url
 * @property-read \App\Models\User|null $uploader
 * @method static \Illuminate\Database\Eloquent\Builder|BenhAnFile newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BenhAnFile newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BenhAnFile query()
 * @method static \Illuminate\Database\Eloquent\Builder|BenhAnFile whereBenhAnId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BenhAnFile whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BenhAnFile whereDisk($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BenhAnFile whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BenhAnFile whereLoaiMime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BenhAnFile wherePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BenhAnFile whereSizeBytes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BenhAnFile whereTenFile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BenhAnFile whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BenhAnFile whereUploadedBy($value)
 */
	class BenhAnFile extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\CaDieuChinhBacSi
 *
 * @property int $id
 * @property int $bac_si_id
 * @property \Illuminate\Support\Carbon $ngay
 * @property string $gio_bat_dau
 * @property string $gio_ket_thuc
 * @property string $hanh_dong
 * @property string|null $ly_do
 * @property array|null $meta
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\BacSi $bacSi
 * @method static \Illuminate\Database\Eloquent\Builder|CaDieuChinhBacSi newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CaDieuChinhBacSi newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CaDieuChinhBacSi query()
 * @method static \Illuminate\Database\Eloquent\Builder|CaDieuChinhBacSi whereBacSiId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CaDieuChinhBacSi whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CaDieuChinhBacSi whereGioBatDau($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CaDieuChinhBacSi whereGioKetThuc($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CaDieuChinhBacSi whereHanhDong($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CaDieuChinhBacSi whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CaDieuChinhBacSi whereLyDo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CaDieuChinhBacSi whereMeta($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CaDieuChinhBacSi whereNgay($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CaDieuChinhBacSi whereUpdatedAt($value)
 */
	class CaDieuChinhBacSi extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\CaLamViecNhanVien
 *
 * @property int $id
 * @property int $nhan_vien_id
 * @property string $ngay
 * @property string $bat_dau
 * @property string $ket_thuc
 * @property string|null $ghi_chu
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\NhanVien $nhanVien
 * @method static \Illuminate\Database\Eloquent\Builder|CaLamViecNhanVien newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CaLamViecNhanVien newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CaLamViecNhanVien query()
 * @method static \Illuminate\Database\Eloquent\Builder|CaLamViecNhanVien whereBatDau($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CaLamViecNhanVien whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CaLamViecNhanVien whereGhiChu($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CaLamViecNhanVien whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CaLamViecNhanVien whereKetThuc($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CaLamViecNhanVien whereNgay($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CaLamViecNhanVien whereNhanVienId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CaLamViecNhanVien whereUpdatedAt($value)
 */
	class CaLamViecNhanVien extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\ChuyenKhoa
 *
 * @property int $id
 * @property string $ten
 * @property string|null $slug
 * @property string|null $mo_ta
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\BacSi> $bacSis
 * @property-read int|null $bac_sis_count
 * @method static \Illuminate\Database\Eloquent\Builder|ChuyenKhoa newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ChuyenKhoa newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ChuyenKhoa query()
 * @method static \Illuminate\Database\Eloquent\Builder|ChuyenKhoa whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ChuyenKhoa whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ChuyenKhoa whereMoTa($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ChuyenKhoa whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ChuyenKhoa whereTen($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ChuyenKhoa whereUpdatedAt($value)
 */
	class ChuyenKhoa extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\DanhMuc
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string|null $meta_title
 * @property string|null $meta_description
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\BaiViet> $baiViets
 * @property-read int|null $bai_viets_count
 * @method static \Illuminate\Database\Eloquent\Builder|DanhMuc newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DanhMuc newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DanhMuc query()
 * @method static \Illuminate\Database\Eloquent\Builder|DanhMuc whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DanhMuc whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DanhMuc whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DanhMuc whereMetaDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DanhMuc whereMetaTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DanhMuc whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DanhMuc whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DanhMuc whereUpdatedAt($value)
 */
	class DanhMuc extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\DichVu
 *
 * @property int $id
 * @property string $ten_dich_vu
 * @property string|null $mo_ta
 * @property string $gia
 * @property int $thoi_gian_uoc_tinh
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\LichHen> $lichHens
 * @property-read int|null $lich_hens_count
 * @method static \Illuminate\Database\Eloquent\Builder|DichVu newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DichVu newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DichVu query()
 * @method static \Illuminate\Database\Eloquent\Builder|DichVu whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DichVu whereGia($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DichVu whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DichVu whereMoTa($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DichVu whereTenDichVu($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DichVu whereThoiGianUocTinh($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DichVu whereUpdatedAt($value)
 */
	class DichVu extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\DonThuoc
 *
 * @property int $id
 * @property int $benh_an_id
 * @property int|null $user_id
 * @property int|null $bac_si_id
 * @property int|null $lich_hen_id
 * @property string|null $ghi_chu
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\BenhAn $benhAn
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\DonThuocItem> $items
 * @property-read int|null $items_count
 * @method static \Illuminate\Database\Eloquent\Builder|DonThuoc newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DonThuoc newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DonThuoc query()
 * @method static \Illuminate\Database\Eloquent\Builder|DonThuoc whereBacSiId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DonThuoc whereBenhAnId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DonThuoc whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DonThuoc whereGhiChu($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DonThuoc whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DonThuoc whereLichHenId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DonThuoc whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DonThuoc whereUserId($value)
 */
	class DonThuoc extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\DonThuocItem
 *
 * @property int $id
 * @property int $don_thuoc_id
 * @property int $thuoc_id
 * @property int $so_luong
 * @property string|null $lieu_dung
 * @property string|null $cach_dung
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\DonThuoc $donThuoc
 * @property-read \App\Models\Thuoc $thuoc
 * @method static \Illuminate\Database\Eloquent\Builder|DonThuocItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DonThuocItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DonThuocItem query()
 * @method static \Illuminate\Database\Eloquent\Builder|DonThuocItem whereCachDung($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DonThuocItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DonThuocItem whereDonThuocId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DonThuocItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DonThuocItem whereLieuDung($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DonThuocItem whereSoLuong($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DonThuocItem whereThuocId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DonThuocItem whereUpdatedAt($value)
 */
	class DonThuocItem extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\HoaDon
 *
 * @property int $id
 * @property int $lich_hen_id
 * @property int $user_id
 * @property string $tong_tien
 * @property string $trang_thai
 * @property string|null $phuong_thuc
 * @property string|null $ghi_chu
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\HoanTien> $hoanTiens
 * @property-read int|null $hoan_tiens_count
 * @property-read \App\Models\LichHen $lichHen
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PaymentLog> $paymentLogs
 * @property-read int|null $payment_logs_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ThanhToan> $thanhToans
 * @property-read int|null $thanh_toans_count
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|HoaDon newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|HoaDon newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|HoaDon query()
 * @method static \Illuminate\Database\Eloquent\Builder|HoaDon whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HoaDon whereGhiChu($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HoaDon whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HoaDon whereLichHenId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HoaDon wherePhuongThuc($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HoaDon whereTongTien($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HoaDon whereTrangThai($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HoaDon whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HoaDon whereUserId($value)
 */
	class HoaDon extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\HoanTien
 *
 * @property int $id
 * @property int $hoa_don_id
 * @property string $so_tien
 * @property string|null $ly_do
 * @property string $trang_thai
 * @property string|null $provider
 * @property string|null $provider_ref
 * @property array|null $payload
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\HoaDon $hoaDon
 * @method static \Illuminate\Database\Eloquent\Builder|HoanTien newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|HoanTien newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|HoanTien query()
 * @method static \Illuminate\Database\Eloquent\Builder|HoanTien whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HoanTien whereHoaDonId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HoanTien whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HoanTien whereLyDo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HoanTien wherePayload($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HoanTien whereProvider($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HoanTien whereProviderRef($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HoanTien whereSoTien($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HoanTien whereTrangThai($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HoanTien whereUpdatedAt($value)
 */
	class HoanTien extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\LichHen
 *
 * @property int $id
 * @property int $user_id
 * @property int $bac_si_id
 * @property int $dich_vu_id
 * @property string $tong_tien
 * @property string $ngay_hen
 * @property string $thoi_gian_hen
 * @property string|null $ghi_chu
 * @property string $trang_thai
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $payment_status
 * @property string|null $payment_method
 * @property \Illuminate\Support\Carbon|null $paid_at
 * @property int|null $cancelled_by
 * @property string|null $cancelled_at
 * @property-read \App\Models\BacSi $bacSi
 * @property-read \App\Models\DichVu $dichVu
 * @property-read bool $is_paid
 * @property-read string $payment_label
 * @property-read mixed $thanh_toan_trang_thai
 * @property-read mixed $tong_tien_de_xuat
 * @property-read \App\Models\HoaDon|null $hoaDon
 * @property-write mixed $status
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|LichHen newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LichHen newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LichHen query()
 * @method static \Illuminate\Database\Eloquent\Builder|LichHen whereBacSiId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LichHen whereCancelledAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LichHen whereCancelledBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LichHen whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LichHen whereDichVuId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LichHen whereGhiChu($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LichHen whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LichHen whereNgayHen($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LichHen wherePaidAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LichHen wherePaymentMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LichHen wherePaymentStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LichHen whereThoiGianHen($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LichHen whereTongTien($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LichHen whereTrangThai($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LichHen whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LichHen whereUserId($value)
 */
	class LichHen extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\LichLamViec
 *
 * @property int $id
 * @property int $bac_si_id
 * @property int|null $phong_id
 * @property int $ngay_trong_tuan
 * @property string $thoi_gian_bat_dau
 * @property string $thoi_gian_ket_thuc
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\BacSi $bacSi
 * @property-read \App\Models\Phong|null $phong
 * @method static \Illuminate\Database\Eloquent\Builder|LichLamViec newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LichLamViec newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LichLamViec query()
 * @method static \Illuminate\Database\Eloquent\Builder|LichLamViec whereBacSiId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LichLamViec whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LichLamViec whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LichLamViec whereNgayTrongTuan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LichLamViec wherePhongId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LichLamViec whereThoiGianBatDau($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LichLamViec whereThoiGianKetThuc($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LichLamViec whereUpdatedAt($value)
 */
	class LichLamViec extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\LichNghi
 *
 * @property int $id
 * @property int $bac_si_id
 * @property \Illuminate\Support\Carbon $ngay
 * @property string $bat_dau
 * @property string $ket_thuc
 * @property string|null $ly_do
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\BacSi $bacSi
 * @method static \Illuminate\Database\Eloquent\Builder|LichNghi newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LichNghi newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LichNghi query()
 * @method static \Illuminate\Database\Eloquent\Builder|LichNghi whereBacSiId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LichNghi whereBatDau($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LichNghi whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LichNghi whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LichNghi whereKetThuc($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LichNghi whereLyDo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LichNghi whereNgay($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LichNghi whereUpdatedAt($value)
 */
	class LichNghi extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\LoginAudit
 *
 * @property int $id
 * @property int|null $user_id
 * @property string|null $email
 * @property string $ip
 * @property string|null $user_agent
 * @property string $status
 * @property string|null $reason
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|LoginAudit newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LoginAudit newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LoginAudit query()
 * @method static \Illuminate\Database\Eloquent\Builder|LoginAudit whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoginAudit whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoginAudit whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoginAudit whereIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoginAudit whereReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoginAudit whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoginAudit whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoginAudit whereUserAgent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoginAudit whereUserId($value)
 */
	class LoginAudit extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\NhaCungCap
 *
 * @property int $id
 * @property string $ten
 * @property string|null $dia_chi
 * @property string|null $so_dien_thoai
 * @property string|null $email
 * @property string|null $ghi_chu
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|NhaCungCap newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|NhaCungCap newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|NhaCungCap query()
 * @method static \Illuminate\Database\Eloquent\Builder|NhaCungCap whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NhaCungCap whereDiaChi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NhaCungCap whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NhaCungCap whereGhiChu($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NhaCungCap whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NhaCungCap whereSoDienThoai($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NhaCungCap whereTen($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NhaCungCap whereUpdatedAt($value)
 */
	class NhaCungCap extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\NhanVien
 *
 * @property int $id
 * @property int|null $user_id
 * @property string $ho_ten
 * @property string|null $chuc_vu
 * @property string|null $so_dien_thoai
 * @property string|null $email_cong_viec
 * @property string|null $ngay_sinh
 * @property string|null $gioi_tinh
 * @property string|null $avatar_path
 * @property string $trang_thai
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\NhanVienAudit> $audits
 * @property-read int|null $audits_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CaLamViecNhanVien> $caLamViecs
 * @property-read int|null $ca_lam_viecs_count
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|NhanVien newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|NhanVien newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|NhanVien query()
 * @method static \Illuminate\Database\Eloquent\Builder|NhanVien whereAvatarPath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NhanVien whereChucVu($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NhanVien whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NhanVien whereEmailCongViec($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NhanVien whereGioiTinh($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NhanVien whereHoTen($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NhanVien whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NhanVien whereNgaySinh($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NhanVien whereSoDienThoai($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NhanVien whereTrangThai($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NhanVien whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NhanVien whereUserId($value)
 */
	class NhanVien extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\NhanVienAudit
 *
 * @property int $id
 * @property int $nhan_vien_id
 * @property int|null $user_id
 * @property string $action
 * @property array|null $old_data
 * @property array|null $new_data
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\NhanVien $nhanVien
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|NhanVienAudit newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|NhanVienAudit newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|NhanVienAudit query()
 * @method static \Illuminate\Database\Eloquent\Builder|NhanVienAudit whereAction($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NhanVienAudit whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NhanVienAudit whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NhanVienAudit whereNewData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NhanVienAudit whereNhanVienId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NhanVienAudit whereOldData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NhanVienAudit whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NhanVienAudit whereUserId($value)
 */
	class NhanVienAudit extends \Eloquent {}
}

namespace App\Models{
/**
 * Parent: app/Models/
 * Child: PaymentLog.php
 * Purpose: Model để ghi audit log cho tất cả request/response thanh toán
 *
 * @property int $id
 * @property int|null $hoa_don_id
 * @property string $provider
 * @property string $event_type
 * @property string|null $idempotency_key
 * @property string|null $transaction_ref
 * @property string|null $result_code
 * @property string|null $result_message
 * @property array $payload
 * @property string|null $ip_address
 * @property string|null $user_agent
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\HoaDon|null $hoaDon
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentLog query()
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentLog whereEventType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentLog whereHoaDonId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentLog whereIdempotencyKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentLog whereIpAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentLog wherePayload($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentLog whereProvider($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentLog whereResultCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentLog whereResultMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentLog whereTransactionRef($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentLog whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentLog whereUserAgent($value)
 */
	class PaymentLog extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\PhieuNhap
 *
 * @property int $id
 * @property string $ma_phieu
 * @property string $ngay_nhap
 * @property int|null $nha_cung_cap_id
 * @property int|null $user_id
 * @property string $tong_tien
 * @property string|null $ghi_chu
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|PhieuNhap newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PhieuNhap newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PhieuNhap query()
 * @method static \Illuminate\Database\Eloquent\Builder|PhieuNhap whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhieuNhap whereGhiChu($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhieuNhap whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhieuNhap whereMaPhieu($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhieuNhap whereNgayNhap($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhieuNhap whereNhaCungCapId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhieuNhap whereTongTien($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhieuNhap whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhieuNhap whereUserId($value)
 */
	class PhieuNhap extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\PhieuNhapItem
 *
 * @property int $id
 * @property int $phieu_nhap_id
 * @property int $thuoc_id
 * @property string|null $ma_lo
 * @property string|null $han_su_dung
 * @property int $so_luong
 * @property string $don_gia
 * @property string $thanh_tien
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|PhieuNhapItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PhieuNhapItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PhieuNhapItem query()
 * @method static \Illuminate\Database\Eloquent\Builder|PhieuNhapItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhieuNhapItem whereDonGia($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhieuNhapItem whereHanSuDung($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhieuNhapItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhieuNhapItem whereMaLo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhieuNhapItem wherePhieuNhapId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhieuNhapItem whereSoLuong($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhieuNhapItem whereThanhTien($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhieuNhapItem whereThuocId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhieuNhapItem whereUpdatedAt($value)
 */
	class PhieuNhapItem extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\PhieuXuat
 *
 * @property int $id
 * @property string $ma_phieu
 * @property string $ngay_xuat
 * @property int|null $user_id
 * @property string|null $doi_tuong
 * @property string $tong_tien
 * @property string|null $ghi_chu
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|PhieuXuat newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PhieuXuat newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PhieuXuat query()
 * @method static \Illuminate\Database\Eloquent\Builder|PhieuXuat whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhieuXuat whereDoiTuong($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhieuXuat whereGhiChu($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhieuXuat whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhieuXuat whereMaPhieu($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhieuXuat whereNgayXuat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhieuXuat whereTongTien($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhieuXuat whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhieuXuat whereUserId($value)
 */
	class PhieuXuat extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\PhieuXuatItem
 *
 * @property int $id
 * @property int $phieu_xuat_id
 * @property int $thuoc_id
 * @property int $so_luong
 * @property string $don_gia
 * @property string $thanh_tien
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|PhieuXuatItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PhieuXuatItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PhieuXuatItem query()
 * @method static \Illuminate\Database\Eloquent\Builder|PhieuXuatItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhieuXuatItem whereDonGia($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhieuXuatItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhieuXuatItem wherePhieuXuatId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhieuXuatItem whereSoLuong($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhieuXuatItem whereThanhTien($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhieuXuatItem whereThuocId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhieuXuatItem whereUpdatedAt($value)
 */
	class PhieuXuatItem extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Phong
 *
 * @property int $id
 * @property string $ten
 * @property string|null $loai
 * @property string|null $mo_ta
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\BacSi> $bacSis
 * @property-read int|null $bac_sis_count
 * @method static \Illuminate\Database\Eloquent\Builder|Phong newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Phong newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Phong query()
 * @method static \Illuminate\Database\Eloquent\Builder|Phong whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Phong whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Phong whereLoai($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Phong whereMoTa($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Phong whereTen($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Phong whereUpdatedAt($value)
 */
	class Phong extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Tag
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\BaiViet> $baiViets
 * @property-read int|null $bai_viets_count
 * @method static \Illuminate\Database\Eloquent\Builder|Tag newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Tag newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Tag query()
 * @method static \Illuminate\Database\Eloquent\Builder|Tag whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tag whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tag whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tag whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tag whereUpdatedAt($value)
 */
	class Tag extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\ThanhToan
 *
 * @property int $id
 * @property int $hoa_don_id
 * @property string $provider
 * @property string $so_tien
 * @property string $tien_te
 * @property string $trang_thai
 * @property string|null $transaction_ref
 * @property string|null $idempotency_key
 * @property \Illuminate\Support\Carbon|null $paid_at
 * @property array|null $payload
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\HoaDon $hoaDon
 * @method static \Illuminate\Database\Eloquent\Builder|ThanhToan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ThanhToan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ThanhToan query()
 * @method static \Illuminate\Database\Eloquent\Builder|ThanhToan whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ThanhToan whereHoaDonId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ThanhToan whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ThanhToan whereIdempotencyKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ThanhToan wherePaidAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ThanhToan wherePayload($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ThanhToan whereProvider($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ThanhToan whereSoTien($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ThanhToan whereTienTe($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ThanhToan whereTrangThai($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ThanhToan whereTransactionRef($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ThanhToan whereUpdatedAt($value)
 */
	class ThanhToan extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Thuoc
 *
 * @property int $id
 * @property string $ten
 * @property string|null $hoat_chat
 * @property string|null $ham_luong
 * @property string $don_vi
 * @property string|null $gia_tham_khao
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ThuocKho> $kho
 * @property-read int|null $kho_count
 * @method static \Illuminate\Database\Eloquent\Builder|Thuoc newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Thuoc newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Thuoc query()
 * @method static \Illuminate\Database\Eloquent\Builder|Thuoc whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Thuoc whereDonVi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Thuoc whereGiaThamKhao($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Thuoc whereHamLuong($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Thuoc whereHoatChat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Thuoc whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Thuoc whereTen($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Thuoc whereUpdatedAt($value)
 */
	class Thuoc extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\ThuocKho
 *
 * @property int $id
 * @property int $thuoc_id
 * @property string|null $ma_lo
 * @property string|null $han_su_dung
 * @property int $so_luong
 * @property string $gia_nhap
 * @property string $gia_xuat
 * @property int|null $nha_cung_cap_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\NhaCungCap|null $nhaCungCap
 * @property-read \App\Models\Thuoc $thuoc
 * @method static \Illuminate\Database\Eloquent\Builder|ThuocKho newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ThuocKho newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ThuocKho query()
 * @method static \Illuminate\Database\Eloquent\Builder|ThuocKho whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ThuocKho whereGiaNhap($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ThuocKho whereGiaXuat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ThuocKho whereHanSuDung($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ThuocKho whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ThuocKho whereMaLo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ThuocKho whereNhaCungCapId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ThuocKho whereSoLuong($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ThuocKho whereThuocId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ThuocKho whereUpdatedAt($value)
 */
	class ThuocKho extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\User
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $locked_at
 * @property \Illuminate\Support\Carbon|null $locked_until
 * @property bool $must_change_password
 * @property \Illuminate\Support\Carbon|null $last_login_at
 * @property int $login_attempts
 * @property string|null $last_login_ip
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $role
 * @property-read \App\Models\BacSi|null $bacSi
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\LoginAudit> $loginAudits
 * @property-read int|null $login_audits_count
 * @property-read \App\Models\NhanVien|null $nhanVien
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Permission> $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Role> $roles
 * @property-read int|null $roles_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User permission($permissions, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User role($roles, $guard = null, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereLastLoginAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereLastLoginIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereLockedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereLockedUntil($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereLoginAttempts($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereMustChangePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User withoutPermission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder|User withoutRole($roles, $guard = null)
 */
	class User extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\XetNghiem
 *
 * @property int $id
 * @property int $benh_an_id
 * @property int|null $user_id
 * @property int|null $bac_si_id
 * @property string $loai
 * @property string $file_path
 * @property string $disk
 * @property string|null $mo_ta
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\BenhAn $benhAn
 * @property-read mixed $disk_name
 * @method static \Illuminate\Database\Eloquent\Builder|XetNghiem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|XetNghiem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|XetNghiem query()
 * @method static \Illuminate\Database\Eloquent\Builder|XetNghiem whereBacSiId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|XetNghiem whereBenhAnId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|XetNghiem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|XetNghiem whereDisk($value)
 * @method static \Illuminate\Database\Eloquent\Builder|XetNghiem whereFilePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|XetNghiem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|XetNghiem whereLoai($value)
 * @method static \Illuminate\Database\Eloquent\Builder|XetNghiem whereMoTa($value)
 * @method static \Illuminate\Database\Eloquent\Builder|XetNghiem whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|XetNghiem whereUserId($value)
 */
	class XetNghiem extends \Eloquent {}
}

