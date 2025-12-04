# 🎯 TÓM TẮT: Hệ thống gửi Mail Nhắc Lịch Hẹn

## ✅ ĐÃ FIX VÀ HOẠT ĐỘNG

### Vấn đề ban đầu:
- Mail không được gửi vì `AppointmentReminder` implement `ShouldQueue` nhưng queue không chạy
- Không có cách test đơn giản

### Giải pháp đã áp dụng:
1. ✅ Thêm tham số `$sync` vào `AppointmentReminder` để có thể gửi ngay lập tức
2. ✅ Tạo command `php artisan test:send-reminder` để test
3. ✅ Tạo UI test mail tại `/admin/tools/test-mail`
4. ✅ Sửa các controller để gửi mail sync khi cần

---

## 🚀 CÁCH SỬ DỤNG

### 1️⃣ Test gửi mail ngay (Khuyến nghị)

#### Qua Command Line:
```bash
php artisan test:send-reminder
```

#### Qua Web:
1. Đăng nhập admin
2. Vào: http://127.0.0.1:8000/admin/tools/test-mail
3. Click "Gửi Mail" cho lịch hẹn muốn test

### 2️⃣ Gửi thủ công từ Dashboard

Vào `/admin/dashboard` và click:
- **"Gửi nhắc lịch ngày mai"** - Gửi cho tất cả lịch hẹn ngày mai
- **"Gửi nhắc lịch 3 giờ tới"** - Gửi cho lịch hẹn trong 3 giờ tới

### 3️⃣ Gửi TỰ ĐỘNG (Cần thiết lập thêm)

#### Thiết lập Windows Task Scheduler:
1. Mở **Task Scheduler** (Win + R → `taskschd.msc`)
2. **Create Basic Task** → Tên: `Laravel Scheduler`
3. Trigger: **Daily** at `00:00`
4. Action: **Start a program** → `C:\Users\Admin\DACN\scheduler.bat`
5. Edit Trigger: **Repeat every 1 minute**, **Indefinitely**

---

## 📧 CẤU HÌNH MAIL (ĐÃ ĐÚNG)

File `.env`:
```
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=tn822798@gmail.com
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=tn822798@gmail.com
```

✅ **Đã test thành công!**

---

## 📋 LỊCH TRÌNH GỬI TỰ ĐỘNG

Theo `app/Console/Kernel.php`:

| Loại mail | Thời điểm gửi | Tần suất kiểm tra | Điều kiện |
|-----------|--------------|-------------------|-----------|
| **T-24h** | Trước 24 giờ | Mỗi 15 phút | Lịch hẹn ngày mai |
| **T-3h**  | Trước 3 giờ  | Mỗi 10 phút | Lịch hẹn trong 3h tới |

**Lưu ý:** Mỗi lịch hẹn chỉ nhận 1 mail (dùng Cache để tránh spam)

---

## 🔍 KIỂM TRA LOG

```bash
# Log Laravel
type storage\logs\laravel.log

# Log Scheduler (sau khi thiết lập Task Scheduler)
type storage\logs\scheduler.log
```

---

## 🛠️ TROUBLESHOOTING

### Mail không gửi được?
```bash
# Test mail đơn giản
php artisan tinker
Mail::raw('Test', function($m){$m->to('email@test.com')->subject('Test');});
exit
```

### Kiểm tra lịch hẹn có email không?
```bash
php artisan tinker --execute="dd(App\Models\LichHen::with('user')->get()->pluck('user.email'));"
```

### Test scheduler thủ công:
```bash
php artisan schedule:run
```

---

## 📁 FILES QUAN TRỌNG

| File | Mục đích |
|------|----------|
| `app/Notifications/AppointmentReminder.php` | Template email |
| `app/Console/Kernel.php` | Lịch trình tự động |
| `app/Console/Commands/TestSendReminder.php` | Command test |
| `app/Http/Controllers/Admin/TestMailController.php` | UI test mail |
| `scheduler.bat` | Script cho Windows Task Scheduler |
| `.env` | Cấu hình mail |

---

## ⚡ QUICK START

**Để test ngay:**
```bash
php artisan test:send-reminder
```

**Hoặc truy cập:**
```
http://127.0.0.1:8000/admin/tools/test-mail
```

✅ **Mail đã sẵn sàng gửi!**

---

Xem hướng dẫn chi tiết trong file `HUONG_DAN_GUI_MAIL.md`
