# Hướng dẫn sử dụng tính năng Dịch vụ nâng cao

## Tổng quan

Tính năng dịch vụ nâng cao cho phép bác sĩ chỉ định thêm các dịch vụ khám bệnh chuyên sâu sau khi khám lâm sàng xong, như:

-   Đo tim thai
-   Chọc ối
-   Siêu âm 4D
-   Xét nghiệm máu thai nhi
-   Test sàng lọc trước sinh
-   Đo độ mờ da gáy
-   Sinh thiết nhau thai
-   Đo co bóp tử cung
-   Xét nghiệm NIPT
-   Siêu âm Doppler

## Cấu trúc dữ liệu

### Bảng `dich_vus`

Đã được cập nhật với các cột:

-   `loai`: Enum ('Cơ bản', 'Nâng cao') - Phân loại dịch vụ
-   `hoat_dong`: Boolean - Trạng thái hoạt động của dịch vụ

### Bảng `benh_an_dich_vu_nang_cao`

Bảng trung gian lưu các dịch vụ nâng cao được chỉ định:

-   `benh_an_id`: ID bệnh án
-   `dich_vu_id`: ID dịch vụ
-   `gia_tai_thoi_diem`: Giá dịch vụ tại thời điểm chỉ định
-   `trang_thai`: Enum ('Chờ thực hiện', 'Đang thực hiện', 'Hoàn thành', 'Đã hủy')
-   `ghi_chu`: Ghi chú của bác sĩ khi chỉ định
-   `ket_qua`: Kết quả sau khi thực hiện
-   `thoi_gian_thuc_hien`: Thời gian bắt đầu thực hiện
-   `nguoi_thuc_hien_id`: ID người thực hiện dịch vụ

## API Endpoints

### 1. Chỉ định dịch vụ nâng cao

**POST** `/doctor/benh-an/{benhAn}/dich-vu-nang-cao`

**Parameters:**

```json
{
    "dich_vu_ids": [1, 2, 3],
    "ghi_chu": "Cần làm siêu âm để kiểm tra chi tiết"
}
```

**Response:** Redirect với thông báo thành công

### 2. Cập nhật trạng thái dịch vụ

**PUT** `/doctor/dich-vu-nang-cao/{dichVuNangCao}`

**Parameters:**

```json
{
    "trang_thai": "Hoàn thành",
    "ket_qua": "Kết quả siêu âm bình thường",
    "ghi_chu": "Không phát hiện bất thường"
}
```

### 3. Hủy dịch vụ

**DELETE** `/doctor/dich-vu-nang-cao/{dichVuNangCao}`

## Quy trình sử dụng

### Bước 1: Khám lâm sàng

1. Bác sĩ tiếp nhận bệnh nhân
2. Thực hiện khám lâm sàng thông thường
3. Ghi nhận triệu chứng, chẩn đoán trong bệnh án

### Bước 2: Chỉ định dịch vụ nâng cao

1. Trong trang chỉnh sửa bệnh án, tìm đến phần **"Dịch vụ nâng cao"**
2. Click nút **"+"** để mở modal chỉ định
3. Chọn các dịch vụ cần chỉ định (có thể chọn nhiều)
4. Nhập ghi chú nếu cần
5. Click **"Chỉ định"**

### Bước 3: Theo dõi và cập nhật trạng thái

Các trạng thái của dịch vụ:

#### a. Chờ thực hiện (mặc định)

-   Dịch vụ vừa được chỉ định
-   Chờ bệnh nhân hoặc nhân viên thực hiện

#### b. Đang thực hiện

-   Click nút **"Bắt đầu"** khi bắt đầu thực hiện dịch vụ
-   Hệ thống tự động ghi nhận thời gian và người thực hiện

#### c. Hoàn thành

-   Click nút **"Hoàn thành"** sau khi thực hiện xong
-   Nhập kết quả thực hiện
-   Có thể cập nhật ghi chú bổ sung

#### d. Đã hủy

-   Click nút **"X"** để hủy dịch vụ
-   Dịch vụ đã hủy không thể khôi phục

## Quản lý dịch vụ (Admin)

### Thêm dịch vụ nâng cao mới

```php
use App\Models\DichVu;

DichVu::create([
    'ten_dich_vu' => 'Tên dịch vụ',
    'loai' => 'Nâng cao',
    'mo_ta' => 'Mô tả chi tiết',
    'gia' => 500000,
    'thoi_gian_uoc_tinh' => 30, // phút
    'hoat_dong' => true,
]);
```

### Tắt dịch vụ

```php
$dichVu = DichVu::find($id);
$dichVu->update(['hoat_dong' => false]);
```

## Migration và Seeder

### Chạy migration

```bash
php artisan migrate
```

### Chạy seeder để thêm dữ liệu mẫu

```bash
php artisan db:seed --class=DichVuNangCaoSeeder
```

Seeder này sẽ tự động:

-   Thêm 10 dịch vụ nâng cao mẫu
-   Cập nhật các dịch vụ cũ thành loại "Cơ bản"

## Models và Relationships

### BenhAn Model

```php
// Quan hệ với dịch vụ nâng cao
public function dichVuNangCao()
{
    return $this->hasMany(BenhAnDichVuNangCao::class);
}
```

### DichVu Model

```php
// Quan hệ với bệnh án dịch vụ nâng cao
public function benhAnDichVuNangCao()
{
    return $this->hasMany(BenhAnDichVuNangCao::class);
}
```

### BenhAnDichVuNangCao Model

```php
// Relationships
public function benhAn()
{
    return $this->belongsTo(BenhAn::class);
}

public function dichVu()
{
    return $this->belongsTo(DichVu::class);
}

public function nguoiThucHien()
{
    return $this->belongsTo(User::class, 'nguoi_thuc_hien_id');
}
```

## Audit Log

Hệ thống tự động ghi nhận các hoạt động:

-   `advanced_services_ordered`: Khi chỉ định dịch vụ nâng cao
-   `advanced_service_updated`: Khi cập nhật trạng thái dịch vụ
-   `advanced_service_cancelled`: Khi hủy dịch vụ

## Lưu ý quan trọng

1. **Giá dịch vụ**: Giá được lưu tại thời điểm chỉ định (`gia_tai_thoi_diem`) để đảm bảo tính nhất quán, ngay cả khi giá dịch vụ thay đổi sau này.

2. **Quyền truy cập**: Chỉ bác sĩ được phép chỉ định và cập nhật dịch vụ nâng cao.

3. **Validation**:

    - Không thể chỉ định trùng dịch vụ đang có trạng thái khác "Đã hủy"
    - Phải chọn ít nhất 1 dịch vụ khi chỉ định

4. **Tích hợp với hóa đơn**: Các dịch vụ nâng cao sẽ được tính vào hóa đơn tổng của bệnh nhân (cần tích hợp thêm).

## Mở rộng tương lai

-   [ ] Tích hợp với module thanh toán
-   [ ] Báo cáo thống kê dịch vụ nâng cao
-   [ ] Lịch hẹn riêng cho dịch vụ nâng cao
-   [ ] Upload file kết quả dịch vụ
-   [ ] Thông báo cho bệnh nhân khi có kết quả

## Hỗ trợ

Nếu gặp vấn đề, vui lòng liên hệ team phát triển hoặc tạo issue trên repository.
