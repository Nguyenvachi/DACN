# Hướng dẫn thiết lập gửi Mail Nhắc Lịch Hẹn tự động

## 1. Tổng quan

Hệ thống gửi mail nhắc lịch hẹn có 3 cách hoạt động:

### A. Gửi thủ công từ Admin Dashboard
- Truy cập: `/admin/dashboard`
- Click nút **"Gửi nhắc lịch ngày mai"** hoặc **"Gửi nhắc lịch 3 giờ tới"**

### B. Test gửi mail cho từng lịch hẹn
- Truy cập: `/admin/tools/test-mail`
- Chọn lịch hẹn muốn test
- Click nút **"Gửi Mail"**

### C. Tự động gửi theo lịch (Schedule)
- Sử dụng Laravel Scheduler (cần cài đặt thêm)

---

## 2. Kiểm tra cấu hình Mail hiện tại

File `.env` đang dùng Gmail SMTP:
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=tn822798@gmail.com
MAIL_PASSWORD=usjrjqxpfbeetixd
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=tn822798@gmail.com
```

**✓ Cấu hình này đã đúng và hoạt động!**

---

## 3. Test gửi mail

### Cách 1: Qua Terminal (Command Line)
```bash
php artisan test:send-reminder
```

Hoặc chỉ định ID lịch hẹn cụ thể:
```bash
php artisan test:send-reminder 11
```

### Cách 2: Qua Web Interface
1. Đăng nhập với tài khoản admin
2. Truy cập: `http://127.0.0.1:8000/admin/tools/test-mail`
3. Chọn lịch hẹn và click "Gửi Mail"

---

## 4. Thiết lập gửi mail TỰ ĐỘNG

### Option 1: Windows Task Scheduler (Khuyến nghị cho Windows)

#### Bước 1: Tạo file batch script
Tạo file `scheduler.bat` trong thư mục gốc project:

```batch
@echo off
cd /d "C:\Users\Admin\DACN"
php artisan schedule:run >> storage\logs\scheduler.log 2>&1
```

#### Bước 2: Thiết lập Windows Task Scheduler
1. Mở **Task Scheduler** (Win + R → `taskschd.msc`)
2. Click **Create Basic Task**
3. Điền thông tin:
   - Name: `Laravel Scheduler`
   - Trigger: **Daily**
   - Start time: `00:00:00`
   - Action: **Start a program**
   - Program: `C:\Users\Admin\DACN\scheduler.bat`
4. Sau khi tạo, mở Properties:
   - Tab **Triggers**: Click **Edit**
   - Chọn **Repeat task every: 1 minute**
   - Duration: **Indefinitely**
   - Click **OK**

### Option 2: Chạy Queue Worker (Nếu dùng Queue)

Nếu muốn dùng queue để gửi mail bất đồng bộ:

1. Sửa `.env`:
```env
QUEUE_CONNECTION=database
```

2. Tạo bảng jobs:
```bash
php artisan queue:table
php artisan migrate
```

3. Chạy queue worker:
```bash
php artisan queue:work
```

**Lưu ý:** Với cấu hình hiện tại (`QUEUE_CONNECTION=sync`), mail vẫn gửi được nhưng đồng bộ (không qua queue).

---

## 5. Kiểm tra log

### Log Laravel
```bash
type storage\logs\laravel.log
```

### Log Scheduler (nếu dùng Windows Task Scheduler)
```bash
type storage\logs\scheduler.log
```

---

## 6. Lịch trình gửi mail tự động

Theo cấu hình trong `app/Console/Kernel.php`:

### Mail nhắc T-24h (trước 24 giờ)
- **Thời gian chạy:** Mỗi 15 phút
- **Điều kiện:** Lịch hẹn của ngày mai (cửa sổ 1420-1440 phút = ~24h)
- **Chỉ gửi 1 lần** cho mỗi lịch hẹn (dùng Cache key)

### Mail nhắc T-3h (trước 3 giờ)
- **Thời gian chạy:** Mỗi 10 phút
- **Điều kiện:** Lịch hẹn trong 3 giờ tới (cửa sổ 160-180 phút = ~3h)
- **Chỉ gửi 1 lần** cho mỗi lịch hẹn (dùng Cache key)

---

## 7. Troubleshooting

### Mail không gửi được?

#### Kiểm tra 1: Xem log
```bash
type storage\logs\laravel.log
```

#### Kiểm tra 2: Test kết nối SMTP
```bash
php artisan tinker
```
Trong tinker:
```php
Mail::raw('Test email', function($msg) {
    $msg->to('your-email@gmail.com')->subject('Test');
});
exit
```

#### Kiểm tra 3: Kiểm tra App Password của Gmail
- Gmail cần bật 2-Step Verification
- Tạo App Password tại: https://myaccount.google.com/apppasswords
- Cập nhật `MAIL_PASSWORD` trong `.env`

#### Kiểm tra 4: Xem user có email không
```bash
php artisan tinker --execute="$lh = App\Models\LichHen::find(11); dd($lh->user->email);"
```

### Scheduler không chạy?

#### Kiểm tra Task Scheduler (Windows)
1. Mở Task Scheduler
2. Tìm task `Laravel Scheduler`
3. Click phải → **Run** để test
4. Xem tab **History** để debug

#### Test schedule thủ công
```bash
php artisan schedule:run
```

---

## 8. Giải pháp tạm thời (không cần Scheduler)

Nếu chưa thiết lập được Scheduler, có thể:

1. **Gửi thủ công từ Dashboard** mỗi ngày
2. **Dùng route test để gửi cho từng lịch hẹn**
3. **Chạy command artisan khi cần:**
   ```bash
   php artisan test:send-reminder
   ```

---

## 9. Files quan trọng

- `app/Notifications/AppointmentReminder.php` - Template email
- `app/Console/Kernel.php` - Lịch trình tự động
- `app/Console/Commands/TestSendReminder.php` - Command test
- `app/Http/Controllers/Admin/ReminderController.php` - Controller gửi thủ công
- `app/Http/Controllers/Admin/TestMailController.php` - Controller test UI
- `.env` - Cấu hình mail

---

## 10. Kết luận

✅ **Mail đã hoạt động!** Test bằng:
```bash
php artisan test:send-reminder
```

✅ **Để gửi tự động:** Thiết lập Windows Task Scheduler (xem mục 4)

✅ **Để test từng lịch:** Truy cập `/admin/tools/test-mail`
