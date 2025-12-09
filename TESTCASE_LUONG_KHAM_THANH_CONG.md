# TESTCASE: LUỒNG KHÁM BỆNH THÀNH CÔNG

## 📋 Tổng quan
**Mục tiêu**: Kiểm tra toàn bộ quy trình khám bệnh từ khi bệnh nhân đặt lịch đến khi hoàn thành và thanh toán.

**Thời gian test ước tính**: 15-20 phút

**Vai trò tham gia**:
1. **Bệnh nhân** (Patient) - Nguyễn Thích
2. **Nhân viên** (Staff) - Tiếp tân
3. **Bác sĩ** (Doctor) - BS chỉ định
4. **Admin** - Quản trị hệ thống

---

## 🔄 LUỒNG HOẠT ĐỘNG CHI TIẾT

### **GIAI ĐOẠN 1: BỆNH NHÂN ĐẶT LỊCH HẸN** 👤

#### **Bước 1.1: Đăng nhập với tài khoản Bệnh nhân**
- URL: `http://127.0.0.1:8000/login`
- Tài khoản: `tn822798@gmail.com` / Password của bạn
- ✅ **Kết quả mong đợi**: Redirect về Dashboard Bệnh nhân

#### **Bước 1.2: Xem danh sách bác sĩ**
- Click menu "Bác sĩ" hoặc vào: `http://127.0.0.1:8000/bacsi`
- ✅ **Kết quả mong đợi**: Hiển thị danh sách bác sĩ với avatar, chuyên khoa, đánh giá

#### **Bước 1.3: Chọn bác sĩ và xem lịch trống**
- Click "Đặt lịch" ở một bác sĩ
- Chọn dịch vụ: "Full Combo" (1,800,000đ)
- Chọn ngày: **Ngày mai hoặc hôm nay**
- ✅ **Kết quả mong đợi**: Hiển thị các khung giờ trống (14:00, 15:00, 16:00...)

#### **Bước 1.4: Đặt lịch hẹn**
- Chọn giờ: **14:00**
- Nhập ghi chú: "Khám tổng quát định kỳ"
- Click "Đặt lịch ngay"
- ✅ **Kết quả mong đợi**: 
  - Thông báo "Đặt lịch thành công"
  - Lịch hẹn có trạng thái **"Chờ xác nhận"**
  - Có mã lịch hẹn (ví dụ: LH-20251208-001)

#### **Bước 1.5: Kiểm tra lịch hẹn trong Dashboard**
- Vào `http://127.0.0.1:8000/patient/dashboard`
- ✅ **Kết quả mong đợi**: 
  - Hiển thị lịch hẹn vừa đặt
  - Trạng thái: "Chờ xác nhận"
  - Có thông tin bác sĩ, dịch vụ, ngày giờ

---

### **GIAI ĐOẠN 2: BÁC SĨ XÁC NHẬN LỊCH HẸN** 👨‍⚕️

#### **Bước 2.1: Đăng xuất và đăng nhập lại với tài khoản Bác sĩ**
- Logout bệnh nhân
- Login với tài khoản Bác sĩ (bác sĩ được chọn ở bước 1.3)
- ✅ **Kết quả mong đợi**: Redirect về Dashboard Bác sĩ

#### **Bước 2.2: Xem lịch hẹn chờ xác nhận**
- Vào menu "Lịch hẹn" → "Chờ xác nhận"
- Hoặc: `http://127.0.0.1:8000/doctor/lich-hen`
- ✅ **Kết quả mong đợi**: 
  - Hiển thị lịch hẹn vừa đặt với trạng thái "Chờ xác nhận"
  - Có nút "Xác nhận" và "Từ chối"

#### **Bước 2.3: Xác nhận lịch hẹn**
- Click nút "Xác nhận" ở lịch hẹn
- ✅ **Kết quả mong đợi**: 
  - Thông báo "Xác nhận lịch hẹn thành công"
  - Trạng thái chuyển từ "Chờ xác nhận" → **"Đã xác nhận"**
  - Email thông báo gửi cho bệnh nhân (nếu cấu hình mail)

#### **Bước 2.4: Kiểm tra Dashboard Bác sĩ**
- Vào `http://127.0.0.1:8000/doctor/dashboard`
- ✅ **Kết quả mong đợi**: 
  - Số lịch hẹn hôm nay tăng lên
  - Lịch hẹn hiển thị trong "Lịch hẹn sắp tới"

---

### **GIAI ĐOẠN 3: NHÂN VIÊN CHECK-IN BỆNH NHÂN** 🏥

#### **Bước 3.1: Đăng nhập với tài khoản Nhân viên**
- Logout bác sĩ
- Login với tài khoản Staff/Nhân viên
- ✅ **Kết quả mong đợi**: Redirect về Dashboard Nhân viên với sidebar màu xanh

#### **Bước 3.2: Vào trang Check-in**
- Click menu "Tiếp tân" → "Check-in bệnh nhân"
- Hoặc: `http://127.0.0.1:8000/staff/checkin`
- ✅ **Kết quả mong đợi**: 
  - Hiển thị 4 thẻ thống kê với gradient (Tổng lịch hẹn, Đã check-in, Chờ check-in, Đang khám)
  - Danh sách lịch hẹn hôm nay có trạng thái "Đã xác nhận"

#### **Bước 3.3: Check-in bệnh nhân**
- Tìm lịch hẹn vừa tạo (tìm theo tên "Nguyễn Thích" hoặc mã lịch hẹn)
- Click nút "Check-in" màu xanh
- ✅ **Kết quả mong đợi**: 
  - Thông báo "Đã check-in thành công cho bệnh nhân Nguyễn Thích"
  - Trạng thái chuyển từ "Đã xác nhận" → **"Đã check-in"**
  - Cột `checked_in_at` được ghi nhận thời gian hiện tại

#### **Bước 3.4: Kiểm tra trong Quản lý hàng đợi**
- Click menu "Tiếp tân" → "Quản lý hàng đợi"
- Hoặc: `http://127.0.0.1:8000/staff/queue`
- ✅ **Kết quả mong đợi**: 
  - Bệnh nhân xuất hiện trong danh sách "Đang chờ khám"
  - Có số thứ tự (STT: 1, 2, 3...)
  - Hiển thị thời gian chờ (tính từ lúc check-in)

#### **Bước 3.5: Gọi bệnh nhân vào khám**
- Click nút "Gọi vào khám" ở bệnh nhân
- ✅ **Kết quả mong đợi**: 
  - Thông báo "Đã gọi bệnh nhân Nguyễn Thích vào khám với BS. [Tên bác sĩ]"
  - Trạng thái chuyển từ "Đã check-in" → **"Đang khám"**
  - Bệnh nhân di chuyển từ "Đang chờ khám" sang "Đang khám"

---

### **GIAI ĐOẠN 4: BÁC SĨ KHÁM VÀ LẬP BỆNH ÁN** 👨‍⚕️

#### **Bước 4.1: Đăng nhập lại với Bác sĩ**
- Logout nhân viên
- Login lại với tài khoản Bác sĩ
- ✅ **Kết quả mong đợi**: Vào Dashboard Bác sĩ

#### **Bước 4.2: Xem hàng đợi khám bệnh**
- Vào menu "Hàng đợi khám"
- Hoặc: `http://127.0.0.1:8000/doctor/queue`
- ✅ **Kết quả mong đợi**: 
  - Hiển thị bệnh nhân "Nguyễn Thích" trong danh sách "Đang khám"
  - Có nút "Bắt đầu khám"

#### **Bước 4.3: Bắt đầu khám và tạo bệnh án**
- Click "Bắt đầu khám"
- Hoặc vào: `http://127.0.0.1:8000/doctor/benh-an/create?lich_hen_id=[ID]`
- Nhập thông tin bệnh án:
  - **Lý do khám**: "Khám tổng quát định kỳ"
  - **Triệu chứng**: "Không có triệu chứng bất thường"
  - **Chẩn đoán**: "Sức khỏe tốt"
  - **Chiều cao**: 170 cm
  - **Cân nặng**: 65 kg
  - **Huyết áp**: 120/80 mmHg
  - **Nhịp tim**: 75 bpm
  - **Nhiệt độ**: 36.5°C
- Click "Lưu bệnh án"
- ✅ **Kết quả mong đợi**: 
  - Thông báo "Tạo bệnh án thành công"
  - Redirect về trang chi tiết bệnh án

#### **Bước 4.4: Kê đơn thuốc (tùy chọn)**
- Trong trang chi tiết bệnh án, click "Kê đơn thuốc"
- Hoặc: `http://127.0.0.1:8000/doctor/don-thuoc/create?benh_an_id=[ID]`
- Thêm thuốc:
  - Tìm thuốc "Paracetamol 500mg"
  - Số lượng: 20 viên
  - Liều dùng: "1 viên x 3 lần/ngày, sau ăn"
- Click "Lưu đơn thuốc"
- ✅ **Kết quả mong đợi**: 
  - Thông báo "Kê đơn thuốc thành công"
  - Đơn thuốc hiển thị trong bệnh án

#### **Bước 4.5: Hoàn thành khám**
- Trong trang bệnh án, click "Hoàn thành khám"
- Hoặc vào: `http://127.0.0.1:8000/doctor/lich-hen/[ID]/complete`
- ✅ **Kết quả mong đợi**: 
  - Thông báo "Hoàn thành khám thành công"
  - Trạng thái lịch hẹn chuyển từ "Đang khám" → **"Hoàn thành"**
  - Cột `completed_at` được ghi nhận thời gian

---

### **GIAI ĐOẠN 5: ADMIN TẠO HÓA ĐƠN VÀ BỆNH NHÂN THANH TOÁN** 💰

#### **Bước 5.1: Đăng nhập với Admin**
- Logout bác sĩ
- Login với tài khoản Admin
- ✅ **Kết quả mong đợi**: Vào Dashboard Admin

#### **Bước 5.2: Tạo hóa đơn cho lịch hẹn**
- Vào menu "Quản lý" → "Hóa đơn"
- Hoặc: `http://127.0.0.1:8000/admin/hoadon`
- Click "Tạo hóa đơn mới"
- Chọn lịch hẹn vừa hoàn thành
- Kiểm tra thông tin:
  - Dịch vụ: Full Combo - 1,800,000đ
  - Thuốc: Paracetamol (nếu có) - tự động tính
  - Tổng tiền: Tự động tính
- Click "Tạo hóa đơn"
- ✅ **Kết quả mong đợi**: 
  - Thông báo "Tạo hóa đơn thành công"
  - Hóa đơn có trạng thái "Chưa thanh toán"

#### **Bước 5.3: Đăng nhập lại với Bệnh nhân**
- Logout admin
- Login lại với tài khoản Bệnh nhân (Nguyễn Thích)
- ✅ **Kết quả mong đợi**: Vào Dashboard Bệnh nhân

#### **Bước 5.4: Xem hóa đơn và thanh toán**
- Vào menu "Hóa đơn" hoặc Dashboard
- Hoặc: `http://127.0.0.1:8000/patient/payments`
- Tìm hóa đơn chưa thanh toán
- Click "Thanh toán"
- Chọn phương thức:
  - **VNPay** (khuyến nghị để test)
  - Hoặc **MoMo**
- Click "Thanh toán qua VNPay"
- ✅ **Kết quả mong đợi**: 
  - Redirect sang trang sandbox VNPay
  - Hiển thị thông tin thanh toán

#### **Bước 5.5: Thanh toán trên VNPay Sandbox**
- Nhập thông tin test của VNPay:
  - Số thẻ: `9704198526191432198`
  - Tên chủ thẻ: `NGUYEN VAN A`
  - Ngày phát hành: `07/15`
  - Mật khẩu OTP: `123456`
- Click "Thanh toán"
- ✅ **Kết quả mong đợi**: 
  - VNPay xử lý thanh toán thành công
  - Redirect về trang kết quả

#### **Bước 5.6: Kiểm tra kết quả thanh toán**
- Sau khi redirect về: `http://127.0.0.1:8000/payment/vnpay-return`
- ✅ **Kết quả mong đợi**: 
  - Thông báo "Thanh toán thành công"
  - Hóa đơn chuyển sang trạng thái **"Đã thanh toán"**
  - Cột `paid_at` được ghi nhận thời gian
  - Email xác nhận thanh toán gửi cho bệnh nhân

---

### **GIAI ĐOẠN 6: KIỂM TRA KẾT QUẢ CUỐI CÙNG** ✅

#### **Bước 6.1: Kiểm tra Dashboard Bệnh nhân**
- Vào `http://127.0.0.1:8000/patient/dashboard`
- ✅ **Kết quả mong đợi**: 
  - Lịch hẹn hiển thị với trạng thái "Hoàn thành"
  - Hóa đơn hiển thị "Đã thanh toán"
  - Có thể tải xuống phiếu khám bệnh (PDF)

#### **Bước 6.2: Kiểm tra Dashboard Bác sĩ**
- Login với tài khoản Bác sĩ
- Vào `http://127.0.0.1:8000/doctor/dashboard`
- ✅ **Kết quả mong đợi**: 
  - Số lịch hẹn hoàn thành trong ngày tăng lên
  - Thống kê doanh thu cập nhật

#### **Bước 6.3: Kiểm tra Dashboard Nhân viên**
- Login với tài khoản Nhân viên
- Vào `http://127.0.0.1:8000/staff/queue`
- ✅ **Kết quả mong đợi**: 
  - Bệnh nhân hiển thị trong danh sách "Đã hoàn thành"
  - Thời gian chờ trung bình được cập nhật

#### **Bước 6.4: Kiểm tra Dashboard Admin**
- Login với tài khoản Admin
- Vào `http://127.0.0.1:8000/admin/dashboard`
- ✅ **Kết quả mong đợi**: 
  - Doanh thu hôm nay tăng 1,800,000đ
  - Số lịch hẹn hoàn thành tăng lên
  - Biểu đồ cập nhật

#### **Bước 6.5: Kiểm tra báo cáo**
- Vào menu "Báo cáo" → "Báo cáo doanh thu"
- Hoặc: `http://127.0.0.1:8000/admin/reports`
- ✅ **Kết quả mong đợi**: 
  - Hóa đơn vừa thanh toán hiển thị trong báo cáo
  - Tổng doanh thu cập nhật chính xác

---

## 📊 BẢNG TRẠNG THÁI LUỒNG

| Giai đoạn | Người thực hiện | Trạng thái lịch hẹn | Cột database |
|-----------|----------------|---------------------|--------------|
| 1. Đặt lịch | Bệnh nhân | **Chờ xác nhận** | `trang_thai = 'Chờ xác nhận'` |
| 2. Xác nhận | Bác sĩ | **Đã xác nhận** | `trang_thai = 'Đã xác nhận'` |
| 3. Check-in | Nhân viên | **Đã check-in** | `trang_thai = 'Đã check-in'`, `checked_in_at = [timestamp]` |
| 4. Gọi khám | Nhân viên | **Đang khám** | `trang_thai = 'Đang khám'` |
| 5. Hoàn thành | Bác sĩ | **Hoàn thành** | `trang_thai = 'Hoàn thành'`, `completed_at = [timestamp]` |
| 6. Thanh toán | Bệnh nhân + Admin | *(không đổi)* | `payment_status = 'paid'`, `paid_at = [timestamp]` |

---

## 🎯 DANH SÁCH ROUTES ĐƯỢC TEST

### Routes Bệnh nhân:
- ✅ `GET /login` - Đăng nhập
- ✅ `GET /bacsi` - Danh sách bác sĩ
- ✅ `GET /lichhen/create` - Tạo lịch hẹn
- ✅ `POST /lichhen` - Lưu lịch hẹn
- ✅ `GET /patient/dashboard` - Dashboard bệnh nhân
- ✅ `GET /patient/payments` - Hóa đơn
- ✅ `POST /payment/vnpay-create` - Thanh toán VNPay

### Routes Bác sĩ:
- ✅ `GET /doctor/dashboard` - Dashboard bác sĩ
- ✅ `GET /doctor/lich-hen` - Quản lý lịch hẹn
- ✅ `POST /doctor/lich-hen/{id}/confirm` - Xác nhận lịch hẹn
- ✅ `GET /doctor/queue` - Hàng đợi khám
- ✅ `GET /doctor/benh-an/create` - Tạo bệnh án
- ✅ `POST /doctor/benh-an` - Lưu bệnh án
- ✅ `GET /doctor/don-thuoc/create` - Kê đơn thuốc
- ✅ `POST /doctor/lich-hen/{id}/complete` - Hoàn thành khám

### Routes Nhân viên:
- ✅ `GET /staff/dashboard` - Dashboard nhân viên
- ✅ `GET /staff/checkin` - Check-in bệnh nhân
- ✅ `POST /staff/checkin/checkin/{lichhen}` - Thực hiện check-in
- ✅ `GET /staff/queue` - Quản lý hàng đợi
- ✅ `POST /staff/queue/call-next/{lichhen}` - Gọi vào khám
- ✅ `GET /staff/queue/realtime-data` - Dữ liệu real-time

### Routes Admin:
- ✅ `GET /admin/dashboard` - Dashboard admin
- ✅ `GET /admin/hoadon` - Quản lý hóa đơn
- ✅ `POST /admin/hoadon` - Tạo hóa đơn
- ✅ `GET /admin/reports` - Báo cáo

---

## 🐛 CHECKLIST LỖI CẦN KIỂM TRA

### Database:
- [ ] Tất cả cột được sử dụng phải tồn tại (✅ Đã fix: `checked_in_at`, `completed_at`)
- [ ] Foreign keys hợp lệ (user_id, bac_si_id, dich_vu_id)
- [ ] Trạng thái dùng đúng (Tiếng Việt, KHÔNG phải tiếng Anh)

### Routes:
- [ ] Tất cả routes trong menu đều hoạt động
- [ ] Middleware kiểm tra role đúng
- [ ] Redirect sau khi thành công

### UI/UX:
- [ ] Gradient cards hiển thị đẹp
- [ ] Thông báo success/error rõ ràng
- [ ] Responsive trên mobile
- [ ] Loading states khi submit form

### Email (nếu cấu hình):
- [ ] Email xác nhận lịch hẹn
- [ ] Email hóa đơn đã thanh toán
- [ ] Email nhắc nhở trước giờ khám

---

## ⚡ TESTCASE NHANH (5 PHÚT)

Nếu không có thời gian, test nhanh theo các bước sau:

1. **Login Bệnh nhân** → Đặt lịch hẹn (2 phút)
2. **Login Bác sĩ** → Xác nhận lịch hẹn (30 giây)
3. **Login Nhân viên** → Check-in → Gọi vào khám (1 phút)
4. **Login Bác sĩ** → Tạo bệnh án → Hoàn thành (1.5 phút)
5. **Kiểm tra Dashboard các role** (30 giây)

**Tổng**: ~5 phút

---

## 📝 GHI CHÚ

### Thông tin test tài khoản:
- **Bệnh nhân**: `tn822798@gmail.com`
- **Bác sĩ**: (tùy bác sĩ được chọn trong hệ thống)
- **Nhân viên**: (tài khoản staff của bạn)
- **Admin**: (tài khoản admin của bạn)

### VNPay Sandbox:
- **Số thẻ test**: `9704198526191432198`
- **Tên chủ thẻ**: `NGUYEN VAN A`
- **Ngày phát hành**: `07/15`
- **Mật khẩu OTP**: `123456`

### MoMo Test:
- **Số điện thoại**: `0999999999`
- **OTP**: `123456`

---

## ✅ KẾT LUẬN

Luồng khám bệnh hoàn chỉnh bao gồm **6 giai đoạn** với **4 vai trò** tham gia:

1. ✅ Bệnh nhân đặt lịch
2. ✅ Bác sĩ xác nhận
3. ✅ Nhân viên check-in và quản lý hàng đợi
4. ✅ Bác sĩ khám và lập bệnh án
5. ✅ Admin tạo hóa đơn, Bệnh nhân thanh toán
6. ✅ Kiểm tra kết quả trên tất cả Dashboard

**Tổng số routes được test**: 24 routes

**Thời gian test đầy đủ**: 15-20 phút

**Thời gian test nhanh**: 5 phút
