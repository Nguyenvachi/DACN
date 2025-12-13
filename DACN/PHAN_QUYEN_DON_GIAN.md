# HỆ THỐNG PHÂN QUYỀN ĐƠN GIẢN

## 4 VAI TRÒ CHÍNH

### 1. **ADMIN** (Quản trị viên)

**Quyền hạn đầy đủ:**

-   ✅ Quản lý toàn bộ hệ thống
-   ✅ Quản lý Bác sĩ (thêm/sửa/xóa, lịch làm việc)
-   ✅ Quản lý Nhân viên
-   ✅ Quản lý Dịch vụ & Chuyên khoa
-   ✅ Quản lý Thuốc & Kho
-   ✅ Quản lý Nhà cung cấp
-   ✅ Quản lý Lịch hẹn
-   ✅ Xem & quản lý Bệnh án
-   ✅ Quản lý Hóa đơn & Thanh toán
-   ✅ Quản lý CMS (Bài viết, Danh mục, Tag, Media)
-   ✅ Phân quyền Users, Roles, Permissions
-   ✅ Xem Dashboard & Báo cáo
-   ✅ Chat tư vấn & Đánh giá

**Route prefix:** `/admin`

---

### 2. **STAFF** (Nhân viên lễ tân)

**Quyền hạn:**

-   ✅ Check-in bệnh nhân
-   ✅ Quản lý hàng đợi
-   ✅ Xem & xác nhận Lịch hẹn
-   ✅ Xem Bệnh án (chỉ đọc)
-   ✅ Tạo Hóa đơn từ bệnh án
-   ✅ Thu tiền & Thanh toán
-   ✅ Chat tư vấn & Đánh giá
-   ✅ Xem Dashboard nhân viên

**Không có quyền:**

-   ❌ Quản lý Bác sĩ, Nhân viên
-   ❌ Quản lý Dịch vụ, Chuyên khoa
-   ❌ Quản lý Thuốc, Kho, NCC
-   ❌ Quản lý CMS
-   ❌ Phân quyền

**Route prefix:** `/staff`, `/admin` (chỉ một số routes)

---

### 3. **DOCTOR** (Bác sĩ)

**Quyền hạn:**

-   ✅ Xem lịch làm việc của mình
-   ✅ Xem lịch hẹn của mình
-   ✅ Tạo & cập nhật Bệnh án (của bệnh nhân mình khám)
-   ✅ Kê đơn thuốc
-   ✅ Yêu cầu dịch vụ (Nội soi, X-quang, Xét nghiệm, Thủ thuật)
-   ✅ Upload kết quả xét nghiệm
-   ✅ Hoàn thành khám
-   ✅ Xem Dashboard bác sĩ

**Không có quyền:**

-   ❌ Xem bệnh án của bác sĩ khác
-   ❌ Quản lý hệ thống
-   ❌ Tạo hóa đơn

**Route prefix:** `/doctor`

---

### 4. **PATIENT** (Bệnh nhân)

**Quyền hạn:**

-   ✅ Đặt lịch hẹn
-   ✅ Hủy lịch hẹn
-   ✅ Xem lịch hẹn của mình
-   ✅ Xem bệnh án của mình
-   ✅ Xem đơn thuốc
-   ✅ Xem hóa đơn & thanh toán online
-   ✅ Chat tư vấn
-   ✅ Đánh giá dịch vụ
-   ✅ Xem hồ sơ cá nhân

**Không có quyền:**

-   ❌ Xem thông tin của bệnh nhân khác
-   ❌ Truy cập admin panel

**Route prefix:** `/patient`

---

## CẤU TRÚC MIDDLEWARE

### 1. Middleware Custom Role

**File:** `app/Http/Middleware/RoleMiddleware.php`

```php
// Cho phép nhiều role
Route::middleware(['custom_role:admin,staff'])->group(function () {
    // Admin và Staff đều truy cập được
});
```

### 2. Middleware đã loại bỏ

-   ❌ `permission:*` - Không còn sử dụng
-   ❌ `role_or_permission:*` - Không còn sử dụng
-   ✅ Chỉ sử dụng `custom_role:role1,role2,...`

---

## MA TRẬN PHÂN QUYỀN

| Chức năng               | Admin | Staff | Doctor        | Patient       |
| ----------------------- | ----- | ----- | ------------- | ------------- |
| **Dashboard**           | ✅    | ✅    | ✅            | ✅            |
| **Quản lý Bác sĩ**      | ✅    | ❌    | ❌            | ❌            |
| **Quản lý Nhân viên**   | ✅    | ❌    | ❌            | ❌            |
| **Quản lý Dịch vụ**     | ✅    | ❌    | ❌            | ❌            |
| **Quản lý Thuốc & Kho** | ✅    | ❌    | ❌            | ❌            |
| **Check-in**            | ✅    | ✅    | ❌            | ❌            |
| **Lịch hẹn (xem)**      | ✅    | ✅    | ✅            | ✅            |
| **Lịch hẹn (đặt)**      | ✅    | ✅    | ❌            | ✅            |
| **Bệnh án (tạo)**       | ✅    | ❌    | ✅            | ❌            |
| **Bệnh án (xem)**       | ✅    | ✅    | ✅ (của mình) | ✅ (của mình) |
| **Kê đơn thuốc**        | ✅    | ❌    | ✅            | ❌            |
| **Tạo hóa đơn**         | ✅    | ✅    | ❌            | ❌            |
| **Thanh toán**          | ✅    | ✅    | ❌            | ✅            |
| **Chat tư vấn**         | ✅    | ✅    | ✅            | ✅            |
| **CMS**                 | ✅    | ❌    | ❌            | ❌            |
| **Phân quyền**          | ✅    | ❌    | ❌            | ❌            |

---

## CÁCH SỬ DỤNG TRONG CODE

### 1. Trong Routes

```php
// Chỉ admin
Route::middleware(['custom_role:admin'])->group(function () {
    Route::resource('bac-si', AdminBacSiController::class);
});

// Admin và Staff
Route::middleware(['custom_role:admin,staff'])->group(function () {
    Route::get('/hoa-don', [HoaDonController::class, 'index']);
});

// Admin, Staff và Doctor
Route::middleware(['custom_role:admin,staff,doctor'])->group(function () {
    Route::get('/benh-an', [BenhAnController::class, 'index']);
});
```

### 2. Trong Controller

```php
// Kiểm tra role trong controller
if (auth()->user()->role === 'admin') {
    // Logic cho admin
}

// Hoặc dùng gate/policy
$this->authorize('update', $benhAn);
```

### 3. Trong View

```blade
@if(auth()->user()->role === 'admin')
    <a href="{{ route('admin.users.index') }}">Quản lý Users</a>
@endif

@if(in_array(auth()->user()->role, ['admin', 'staff']))
    <a href="{{ route('admin.hoadon.index') }}">Quản lý Hóa đơn</a>
@endif
```

---

## LƯU Ý QUAN TRỌNG

1. **Không còn sử dụng Spatie Permission package** cho phân quyền phức tạp
2. **Chỉ dựa vào 4 role đơn giản:** admin, staff, doctor, patient
3. **Authorization trong Controller** vẫn sử dụng Policy để kiểm tra quyền chi tiết
4. **Sidebar động** theo role trong `layouts/admin.blade.php`
5. **Dashboard riêng** cho từng role

---

## MIGRATION & CLEANUP

### Các bước đã thực hiện:

1. ✅ Loại bỏ tất cả middleware `permission:*`
2. ✅ Thay thế bằng `custom_role:role1,role2`
3. ✅ Đăng ký middleware `custom_role` trong Kernel
4. ✅ Cập nhật layout admin để hiển thị menu theo role
5. ✅ Cập nhật dashboard staff với thống kê phù hợp

### Có thể xóa (optional):

-   Các migration của Spatie Permission (nếu không dùng)
-   Routes quản lý roles/permissions (nếu không cần UI)
-   File seeder permissions (nếu không cần)

---

## KẾT LUẬN

Hệ thống phân quyền đã được đơn giản hóa, dễ hiểu và dễ bảo trì. Chỉ cần nhớ 4 vai trò chính và sử dụng middleware `custom_role` cho mọi routes cần bảo vệ.
