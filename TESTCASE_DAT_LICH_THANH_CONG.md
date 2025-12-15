# TESTCASE: Luồng Đặt Lịch Thành Công (Appointment Booking Flow)

Mục tiêu: Kiểm thử toàn bộ luồng "Đặt lịch → Xác nhận → Check-in → Khám → Hoàn thành → Thanh toán" trên giao diện theo thực tế dự án.

Parent file / tham chiếu:
- File mẹ (số liệu luồng tổng quát): [TESTCASE_LUONG_KHAM_THANH_CONG.md](TESTCASE_LUONG_KHAM_THANH_CONG.md)
- Route/Controllers/Models liên quan: [routes/web.php](routes/web.php), [`App\Models\LichHen`](app/Models/LichHen.php), [`App\Models\User`](app/Models/User.php), [`App\Models\BacSi`](app/Models/BacSi.php), [`App\Models\BenhAn`](app/Models/BenhAn.php), [`App\Models\HoaDon`](app/Models/HoaDon.php), [`App\Http\Controllers\Public\LichHenController`](app/Http/Controllers/Public/LichHenController.php), [`App\Http\Controllers\Doctor\LichHenController`](app/Http/Controllers/Doctor/LichHenController.php), [`App\Http\Controllers\Staff\CheckinController`](app/Http/Controllers/Staff/CheckinController.php), [`App\Http\Controllers\Staff\QueueController`](app/Http/Controllers/Staff/QueueController.php), [`App\Http\Controllers\Doctor\BenhAnController`](app/Http/Controllers/Doctor/BenhAnController.php), [`App\Http\Controllers\Admin\HoaDonController`](app/Http/Controllers/Admin/HoaDonController.php), automation script: [`scripts/run_workflow.php`](scripts/run_workflow.php).

Prerequisites
- Database seeded with doctors, patients and roles:
  - Run seeders: `php artisan db:seed --class=NhanVienSeeder`, `php artisan db:seed --class=BacSiSeeder`, `php artisan db:seed` (or run all).
  - Kiểm tra danh sách bác sĩ bằng view: [resources/views/public/bacsi/index.blade.php](resources/views/public/bacsi/index.blade.php)
- Server dev: `php artisan serve`
- Test accounts (suggested): patient `tn822798@gmail.com` (used in existing `TESTCASE_LUONG_KHAM_THANH_CONG.md`) or run `database/seeders/PatientSeeder` if exists.

Checklist (high-level)
1. Patient đặt lịch hẹn trên UI (Public)
2. Doctor xác nhận lịch (Doctor UI)
3. Staff check-in & gọi khám (Staff UI)
4. Doctor khám, tạo bệnh án (Doctor UI)
5. Doctor hoàn thành khám (Doctor UI)
6. Admin tạo hóa đơn (Admin UI)
7. Patient thanh toán (VNPay/MoMo) => xác nhận hóa đơn & trạng thái

Chi tiết bước kiểm thử (UI + DB checks)

BƯỚC 1 — Bệnh nhân đặt lịch
- UI:
  1. Login bệnh nhân: `/login` (route: `login`) — dùng test account.
  2. Lướt đến trang bác sĩ: `/bacsi` or route `public.bacsi.index` ([resources/views/public/bacsi/index.blade.php](resources/views/public/bacsi/index.blade.php)).
  3. Chọn bác sĩ, click "Đặt lịch" (route `lichhen.create`): `GET /lich-hen/create?bac_si_id=[id]` hoặc theo route file [routes/web.php](routes/web.php).
  4. Chọn Dịch vụ (ví dụ: Full Combo), chọn ngày/h giờ hiển thị, click "Đặt lịch".
- DB (Eloquent/Tinker):
  - Xác thực LichHen record vừa tạo:
    ```php
    $ap = \App\Models\LichHen::where('user_id', $patientId)->orderByDesc('created_at')->first();
    $ap->trang_thai; // => should be 'Chờ xác nhận' or LichHen::STATUS_PENDING_VN
    $ap->bac_si_id; $ap->dich_vu_id; $ap->ngay_hen; $ap->thoi_gian_hen;
    ```
- Expected UI:
  - Success message: "Đặt lịch thành công"
  - Status in patient dashboard `patient/lichhen/index` shows "Chờ xác nhận"

BƯỚC 2 — Bác sĩ xác nhận lịch
- UI:
  1. Logout patient → Login bác sĩ (seeded).
  2. Vào `GET /doctor/lich-hen` (route: `doctor.lichhen.index`) xem danh sách chờ.
  3. Xác nhận bằng nút "Xác nhận" (controller: [`App\Http\Controllers\Doctor\LichHenController`](app/Http/Controllers/Doctor/LichHenController.php)).
- DB:
  ```php
  $ap = \App\Models\LichHen::find($id);
  $ap->trang_thai; // => 'Đã xác nhận' (LichHen::STATUS_CONFIRMED_VN)
  ```
- Expected UI:
  - Patient receives email (if mail configured), which you can test via [admin/tools/test-mail.blade.php](resources/views/admin/tools/test-mail.blade.php).
  - Doctor sees updated appointment in 'sắp tới'.

BƯỚC 3 — Nhân viên check-in & gọi khám
- UI (Staff):
  1. Login Staff → `GET /staff/checkin` (route: `staff.checkin.index`) → Tìm lịch trình.
  2. Thực hiện Check-in (button) → `trang_thai` chuyển thành `Đã check-in` (`LichHen::STATUS_CHECKED_IN_VN`), `checked_in_at` được cập nhật.
  3. Gọi khám (Call next) `POST /staff/queue/call-next/{id}` → `trang_thai` chuyển thành `Đang khám` (`LichHen::STATUS_IN_PROGRESS_VN`).
- DB:
  ```php
  $ap->refresh();
  $ap->trang_thai; // => 'Đã check-in' rồi 'Đang khám'
  $ap->checked_in_at; $ap->started_at; // timestamps set
  ```
- Expected UI:
  - Queue page shows patient in "Đang khám" list.
  - Doctor queue list shows "Đang khám".

BƯỚC 4 — Bác sĩ khám & tạo bệnh án
- UI:
  1. Doctor vào `GET /doctor/queue` hoặc click nút "Khám" (Doctor UI): route for create benh an: `GET /doctor/benh-an/create?lich_hen_id=[id]`.
  2. Lưu bệnh án: `POST /doctor/benh-an` handled by [`App\Http\Controllers\Doctor\BenhAnController`](app/Http/Controllers/Doctor/BenhAnController.php).
- DB:
  ```php
  $benhAn = \App\Models\BenhAn::firstWhere('lich_hen_id', $ap->id);
  $benhAn->chuan_doan; $benhAn->trieu_chung; // ensuring created
  ```
- Expected UI:
  - Redirect to benh an detail page (`doctor.benhan.show`), status/feedback success shown.

BƯỚC 5 — Hoàn thành khám
- UI:
  1. Doctor click "Hoàn thành" (button route: `POST /doctor/lich-hen/{id}/complete`).
- DB:
  ```php
  $ap->refresh();
  $ap->trang_thai; // => 'Hoàn thành' (LichHen::STATUS_COMPLETED_VN)
  $ap->completed_at != null;
  ```
- Expected UI:
  - Appointment shows "Hoàn thành" in all dashboards.

BƯỚC 6 — Admin tạo hóa đơn
- UI:
  1. Login Admin → `GET /admin/hoadon` (route: `admin.hoadon.index`).
  2. Tạo hóa đơn cho lịch hẹn đã hoàn thành: `POST /admin/hoadon`.
- DB:
  ```php
  $hoaDon = \App\Models\HoaDon::firstWhere('lich_hen_id', $ap->id);
  $hoaDon->trang_thai; // 'Chưa thanh toán' (or status)
  $hoaDon->tong_tien; // match service price + thuốc
  ```
- Expected UI:
  - Invoice appears in admin list; patient sees invoice in `patient.payments`.

BƯỚC 7 — Bệnh nhân thanh toán (VNPay sandbox)
- UI:
  1. Login patient → vào `patient.payments` → click Pay (VNPay) — route: `payment/vnpay-create` & `payment/vnpay-return` in flows.
  2. Hoàn tất sandbox payment (test numbers are in `TESTCASE_LUONG_KHAM_THANH_CONG.md`).
- DB:
  ```php
  $hoaDon->refresh();
  $hoaDon->trang_thai; // => 'Đã thanh toán' (paid)
  $hoaDon->paid_at != null;
  ```
- Expected UI:
  - Confirmation email (if configured), payment status updated.
  - Patient dashboard shows invoice paid.

Quick verification queries (tinker or DB)
- Find the appointment:
  ```php
  $ap = \App\Models\LichHen::where('user_id', $patientId)->latest()->first();
  dd($ap->only(['id','ngay_hen','thoi_gian_hen','trang_thai','checked_in_at','completed_at']));
  ```
- Check BenhAn:
  ```php
  \App\Models\BenhAn::where('lich_hen_id', $ap->id)->exists();
  ```
- Check invoice:
  ```php
  \App\Models\HoaDon::where('lich_hen_id', $ap->id)->first()->only(['id','tong_tien','trang_thai','paid_at']);
  ```
- Confirm BenhAn files/XetNghiem if any: view in `doctor.xetnghiem` pages.

Automation (optional)
- Use script: `php scripts/run_workflow.php` to simulate the full flow automatically. See file: [`scripts/run_workflow.php`](scripts/run_workflow.php).

Edge cases & extra checks
- Test with different roles (Doctor/Staff/Admin) and verify RBAC (route permissions).
- Test email notifications: [`App\Http\Controllers\Admin\TestMailController`](app/Http/Controllers/Admin/TestMailController.php) + [`resources/views/admin/tools/test-mail.blade.php`](resources/views/admin/tools/test-mail.blade.php).
- Test VNPay sandbox behavior: Route names are `payment/vnpay-create` and `payment/vnpay-return` as referenced in testcases.

Notes & References
- Routes & route names are in: [routes/web.php](routes/web.php)
- Appointment model & constants: [`App\Models\LichHen`](app/Models/LichHen.php)
- Doctor queue: [`App\Http\Controllers\Staff\QueueController`](app/Http/Controllers/Staff/QueueController.php) & view `resources/views/doctor/queue/index.blade.php` ([view] resources/views/doctor/queue/index.blade.php)
- Patient booking view: [resources/views/public/lichhen/create.blade.php](resources/views/public/lichhen/create.blade.php)
- BenhAn controller: [`App\Http\Controllers\BenhAnController`](app/Http/Controllers/BenhAnController.php)
- Medical workflow service: [`App\Services\MedicalWorkflowService`](app/Services/MedicalWorkflowService.php)

---

Ghi chú: Luồng test này khớp với UI & DB trong repository; nếu bạn muốn, mình sẽ:
- Tạo file `TESTCASE_DAT_LICH_THANH_CONG.md` trong root (đã làm),
- Thêm checklist verification scripts (artisan tinker snippets),
- Hoặc tự chạy `scripts/run_workflow.php` và show sample output.

Bạn muốn mình tiếp tục tự động hóa test run (tạo script hoặc tests PHP/Pest) hay chỉ cần file mô tả này?