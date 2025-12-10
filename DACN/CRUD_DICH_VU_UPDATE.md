# Cập nhật CRUD Dịch vụ - Tóm tắt

## Các thay đổi đã thực hiện

### 1. Controller - `DichVuController.php`

✅ **Cập nhật các methods:**

-   `index()`: Thêm filter theo loại, trạng thái, tìm kiếm
-   `store()`: Xử lý loại dịch vụ và trạng thái hoạt động
-   `update()`: Xử lý loại dịch vụ và trạng thái hoạt động
-   `publicIndex()`: Chỉ hiển thị dịch vụ Cơ bản và đang hoạt động

### 2. LichHenController - `LichHenController.php`

✅ **Cập nhật method create():**

-   Chỉ lấy dịch vụ **Cơ bản** và **đang hoạt động**
-   Bệnh nhân đặt lịch chỉ thấy dịch vụ Cơ bản

### 3. Views Admin

#### a. `admin/dichvu/index.blade.php`

✅ Thêm:

-   Bộ lọc: Tìm kiếm, Loại dịch vụ, Trạng thái
-   Hiển thị badge loại (Cơ bản/Nâng cao)
-   Hiển thị badge trạng thái (Hoạt động/Tạm dừng)
-   Pagination thay vì DataTables

#### b. `admin/dichvu/create.blade.php`

✅ Thêm:

-   Dropdown **Loại dịch vụ** (Cơ bản/Nâng cao)
-   Checkbox **Trạng thái hoạt động**
-   Ghi chú giải thích về từng loại

#### c. `admin/dichvu/edit.blade.php`

✅ Thêm:

-   Dropdown **Loại dịch vụ** (Cơ bản/Nâng cao)
-   Checkbox **Trạng thái hoạt động**
-   Ghi chú giải thích về từng loại

## Phân loại dịch vụ

### 🔵 Dịch vụ Cơ bản

-   Hiển thị khi **bệnh nhân đặt lịch**
-   Ví dụ: Khám thai định kỳ, Tư vấn tiền sản, Khám tổng quát,...
-   Bắt buộc phải đang **hoạt động** mới hiển thị

### 🟡 Dịch vụ Nâng cao (Chuyên sâu)

-   **Không hiển thị** khi đặt lịch
-   Chỉ **bác sĩ chỉ định** sau khám lâm sàng
-   Ví dụ: Đo tim thai, Chọc ối, Siêu âm 4D,...

## Quy trình sử dụng

### 1. Admin quản lý dịch vụ

```
Dashboard → Quản lý dịch vụ →
Thêm/Sửa → Chọn loại (Cơ bản/Nâng cao) → Lưu
```

### 2. Bệnh nhân đặt lịch

```
Đặt lịch → Chọn dịch vụ (CHỈ THẤY CƠ BẢN) →
Chọn ngày giờ → Xác nhận
```

### 3. Bác sĩ chỉ định dịch vụ nâng cao

```
Khám lâm sàng → Chỉnh sửa bệnh án →
Phần "Dịch vụ nâng cao" → Chọn dịch vụ → Chỉ định
```

## Validation

### Thêm/Sửa dịch vụ

-   ✅ Tên dịch vụ: Bắt buộc, max 255 ký tự
-   ✅ Loại: Bắt buộc, chỉ "Cơ bản" hoặc "Nâng cao"
-   ✅ Giá: Bắt buộc, số dương, step 1000
-   ✅ Thời gian: Bắt buộc, số nguyên dương (phút)
-   ✅ Mô tả: Tùy chọn
-   ✅ Hoạt động: Checkbox (mặc định true)

## Filter & Search

### Trang danh sách dịch vụ

1. **Tìm kiếm**: Theo tên dịch vụ
2. **Loại**: Tất cả / Cơ bản / Nâng cao
3. **Trạng thái**: Tất cả / Đang hoạt động / Tạm dừng

## API Endpoints

### Admin Routes

```
GET    /admin/dich-vu              -> index (có filter)
GET    /admin/dich-vu/create       -> create
POST   /admin/dich-vu              -> store
GET    /admin/dich-vu/{id}/edit    -> edit
PUT    /admin/dich-vu/{id}         -> update
DELETE /admin/dich-vu/{id}         -> destroy
```

## Database Schema

### Bảng `dich_vus`

```sql
- id
- ten_dich_vu (string, 255)
- loai (enum: 'Cơ bản', 'Nâng cao')
- mo_ta (text, nullable)
- gia (decimal, 10, 2)
- thoi_gian_uoc_tinh (integer)
- hoat_dong (boolean, default: true)
- created_at
- updated_at
```

## Testing Checklist

-   [x] Admin có thể thêm dịch vụ với loại
-   [x] Admin có thể sửa loại dịch vụ
-   [x] Admin có thể tắt/bật dịch vụ
-   [x] Filter theo loại và trạng thái hoạt động
-   [x] Bệnh nhân đặt lịch chỉ thấy dịch vụ Cơ bản
-   [x] Dịch vụ tạm dừng không hiển thị
-   [x] Bác sĩ chỉ định dịch vụ nâng cao

## Lưu ý quan trọng

⚠️ **Dữ liệu cũ**: Các dịch vụ đã tồn tại sẽ tự động được set `loai = 'Cơ bản'` khi chạy seeder.

⚠️ **Validation**: Khi cập nhật dịch vụ, phải chọn loại (không được để trống).

⚠️ **Đặt lịch**: Chỉ dịch vụ Cơ bản + Hoạt động mới hiển thị cho bệnh nhân.

## Mở rộng tương lai

-   [ ] Thống kê dịch vụ theo loại
-   [ ] Giá dịch vụ theo thời gian
-   [ ] Combo dịch vụ
-   [ ] Voucher/coupon theo loại dịch vụ

---

Ngày cập nhật: 10/12/2025
