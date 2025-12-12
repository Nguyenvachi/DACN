@php
    $isPatient = auth()->check() && auth()->user()->role === 'patient';
    $layout = $isPatient ? 'layouts.patient-modern' : 'layouts.app';
@endphp

@extends($layout)

@section('content')
    <style>
        .schedule-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem;
            border-radius: 10px;
            margin-bottom: 2rem;
        }

        .week-navigation {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        .schedule-table {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .day-column {
            border: 1px solid #e0e0e0;
            padding: 1rem;
            min-height: 200px;
        }

        .day-header {
            font-weight: bold;
            color: #667eea;
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid #667eea;
        }

        .day-header.weekend {
            color: #dc3545;
            border-bottom-color: #dc3545;
        }

        .slot-item {
            padding: 0.5rem;
            margin-bottom: 0.5rem;
            border-radius: 5px;
            font-size: 0.9rem;
            transition: all 0.2s;
        }

        .slot-item.slot-available {
            background: #e3f2fd;
            border-left: 4px solid #2196f3;
            cursor: pointer;
        }

        .slot-item.slot-available:hover {
            background: #2196f3;
            color: white;
            transform: translateX(5px);
        }

        .slot-item.slot-partial {
            background: #fff3e0;
            border-left: 4px solid #ff9800;
            cursor: pointer;
        }

        .slot-item.slot-partial:hover {
            background: #ff9800;
            color: white;
            transform: translateX(5px);
        }

        .slot-item.slot-my-booking {
            background: #fff9c4 !important;
            border-left: 5px solid #fbc02d !important;
            cursor: not-allowed;
            font-weight: bold;
            color: #f57f17 !important;
        }

        .slot-item.slot-full {
            background: #ffebee;
            border-left: 3px solid #ef5350;
            cursor: not-allowed;
            opacity: 0.7;
            color: #c62828;
        }

        .no-slots {
            color: #999;
            font-style: italic;
            text-align: center;
            padding: 1rem;
        }
    </style>

    <div class="container py-4">
        <!-- Header -->
        <div class="schedule-header">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h2 class="mb-2">
                        <i class="fas fa-calendar-week"></i>
                        Lịch Rảnh - {{ $bacSi->ten }}
                    </h2>
                    <p class="mb-0">
                        <i class="fas fa-stethoscope"></i>
                        {{ $bacSi->chuyenKhoa->ten ?? 'Đa khoa' }}
                    </p>
                </div>
                <div class="col-md-4 text-md-end">
                    <a href="{{ route('public.bacsi.index') }}" class="btn btn-light">
                        <i class="fas fa-arrow-left"></i> Quay lại danh sách
                    </a>
                </div>
            </div>
        </div>

        <!-- Week Navigation -->
        <div class="week-navigation">
            @php
                $currentWeekStart = now()->startOfWeek();
                $isCurrentWeek = $weekStart->format('Y-m-d') === $currentWeekStart->format('Y-m-d');
            @endphp
            
            @if(!$isCurrentWeek)
                <a href="{{ route('public.bacsi.schedule', ['bacSi' => $bacSi->id, 'week_start' => $weekStart->copy()->subWeek()->format('Y-m-d')]) }}"
                    class="btn btn-outline-primary">
                    <i class="fas fa-chevron-left"></i> Tuần trước
                </a>
            @else
                <div style="width: 150px;"></div>
            @endif
            
            <h4 class="mb-0">
                {{ $weekStart->format('d/m/Y') }} - {{ $weekEnd->format('d/m/Y') }}
                @if($isCurrentWeek)
                    <span class="badge bg-primary ms-2">Tuần này</span>
                @endif
            </h4>
            <a href="{{ route('public.bacsi.schedule', ['bacSi' => $bacSi->id, 'week_start' => $weekStart->copy()->addWeek()->format('Y-m-d')]) }}"
                class="btn btn-outline-primary">
                Tuần sau <i class="fas fa-chevron-right"></i>
            </a>
        </div>

        <!-- Chú thích màu sắc -->
        <div class="alert alert-info mb-3">
            <div class="row text-center">
                <div class="col-md-3">
                    <span class="badge" style="background: #2196f3; padding: 8px 12px;">
                        <i class="far fa-clock"></i> Còn trống (0/2)
                    </span>
                </div>
                <div class="col-md-3">
                    <span class="badge" style="background: #ff9800; padding: 8px 12px;">
                        <i class="fas fa-user-plus"></i> Đã có 1 người (1/2)
                    </span>
                </div>
                <div class="col-md-3">
                    <span class="badge" style="background: #fbc02d; color: #f57f17; padding: 8px 12px;">
                        <i class="fas fa-check-circle"></i> Bạn đã đặt
                    </span>
                </div>
                <div class="col-md-3">
                    <span class="badge" style="background: #ef5350; padding: 8px 12px;">
                        <i class="fas fa-user-clock"></i> Đã đầy (2/2)
                    </span>
                </div>
            </div>
        </div>

        <!-- Schedule Table -->
        <div class="schedule-table">
            <div class="row g-0">
                @php
                    $daysOfWeek = ['Thứ 2', 'Thứ 3', 'Thứ 4', 'Thứ 5', 'Thứ 6', 'Thứ 7', 'Chủ nhật'];
                    $currentDay = $weekStart->copy();
                    $today = now()->startOfDay();
                @endphp

                @for ($i = 0; $i < 7; $i++)
                    @php
                        $dateStr = $currentDay->format('Y-m-d');
                        $daySlots = $slotsByDate->get($dateStr, collect());
                        $isWeekend = $i >= 5;
                        $isPastDay = $currentDay->lt($today); // Kiểm tra ngày đã qua
                    @endphp

                    @if(!$isPastDay)
                        <div class="col-md-12 col-lg-{{ 12 / 7 }} day-column">
                            <div class="day-header {{ $isWeekend ? 'weekend' : '' }}">
                                {{ $daysOfWeek[$i] }}<br>
                                <small>{{ $currentDay->format('d/m') }}</small>
                                @if($currentDay->isToday())
                                    <span class="badge bg-success ms-1" style="font-size: 0.7rem;">Hôm nay</span>
                                @endif
                            </div>

                            @if ($daySlots->isNotEmpty())
                                @foreach ($daySlots as $slot)
                                    @php
                                        // Kiểm tra bệnh nhân này đã đặt slot này chưa
                                        $userId = auth()->id();
                                        $userPhone = auth()->check() && auth()->user()->benh_nhan 
                                            ? auth()->user()->benh_nhan->so_dien_thoai 
                                            : null;
                                        
                                        $hasBooked = false;
                                        
                                        // Chuẩn hóa format thời gian để so sánh
                                        $slotTime = \Carbon\Carbon::createFromFormat('H:i', $slot['start'])->format('H:i:s');
                                        
                                        if ($userId || $userPhone) {
                                            $query = \App\Models\LichHen::where('bac_si_id', $bacSi->id)
                                                ->where('ngay_hen', $dateStr)
                                                ->where('thoi_gian_hen', $slotTime)
                                                ->whereNotIn('trang_thai', [\App\Models\LichHen::STATUS_CANCELLED_VN]);
                                            
                                            if ($userId) {
                                                $query->where('user_id', $userId);
                                            } elseif ($userPhone) {
                                                $query->where('so_dien_thoai_benh_nhan', $userPhone);
                                            }
                                            
                                            $hasBooked = $query->exists();
                                        }
                                        
                                        // Lấy thông tin từ slot (đã có sẵn từ service)
                                        $bookedCount = $slot['booked_count'] ?? 0;
                                        $isFull = $slot['is_full'] ?? false;
                                        $isAvailable = $slot['is_available'] ?? true;
                                    @endphp
                                    
                                    @if($hasBooked)
                                        {{-- Đã đặt - màu vàng, không click được --}}
                                        <div class="slot-item slot-my-booking">
                                            <i class="fas fa-check-circle"></i>
                                            {{ $slot['start'] }} - {{ $slot['end'] }}
                                            <span class="badge bg-warning text-dark ms-1" style="font-size: 0.65rem;">Đã đặt</span>
                                        </div>
                                    @elseif($isFull)
                                        {{-- Đầy (2/2) - màu đỏ nhạt --}}
                                        <div class="slot-item slot-full">
                                            <i class="fas fa-user-clock"></i>
                                            {{ $slot['start'] }} - {{ $slot['end'] }}
                                            <span class="badge bg-danger ms-1" style="font-size: 0.65rem;">{{ $bookedCount }}/2</span>
                                        </div>
                                    @elseif($bookedCount > 0)
                                        {{-- Đã có người đặt (1/2) - màu cam, vẫn click được --}}
                                        <div class="slot-item slot-partial" 
                                             onclick="bookSlot('{{ $dateStr }}', '{{ $slot['start'] }}')">
                                            <i class="fas fa-user-plus"></i>
                                            {{ $slot['start'] }} - {{ $slot['end'] }}
                                            <span class="badge bg-warning text-dark ms-1" style="font-size: 0.65rem;">{{ $bookedCount }}/2</span>
                                        </div>
                                    @else
                                        {{-- Còn trống (0/2) - màu xanh dương, click được --}}
                                        <div class="slot-item slot-available" 
                                             onclick="bookSlot('{{ $dateStr }}', '{{ $slot['start'] }}')">
                                            <i class="far fa-clock"></i>
                                            {{ $slot['start'] }} - {{ $slot['end'] }}
                                        </div>
                                    @endif
                                @endforeach
                            @else
                                <div class="no-slots">
                                    Không có lịch
                                </div>
                            @endif
                        </div>
                    @endif

                    @php
                        $currentDay->addDay();
                    @endphp
                @endfor
            </div>
        </div>

        <!-- Quick Info -->
        <div class="alert alert-info mt-4">
            <i class="fas fa-info-circle"></i>
            <strong>Lưu ý:</strong> 
            <ul class="mb-0 mt-2">
                <li><span class="badge bg-primary">Xanh dương</span> - Khung giờ còn trống, click để đặt lịch</li>
                <li><span class="badge bg-success">Xanh lá</span> - Bạn đã đặt lịch khung giờ này</li>
                <li><span class="badge bg-secondary">Xám</span> - Khung giờ đã đủ 2 người đặt</li>
            </ul>
        </div>

        <!-- Booking Modal -->
        <div class="modal fade" id="bookingModal" tabindex="-1" aria-labelledby="bookingModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-gradient-to-r from-blue-500 to-blue-600 text-white">
                        <h5 class="modal-title" id="bookingModalLabel">
                            <i class="fas fa-calendar-check"></i> Đặt Lịch Khám
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="bookingForm" action="{{ route('lichhen.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="bac_si_id" value="{{ $bacSi->id }}">
                        <input type="hidden" name="ngay_hen" id="modal_ngay_hen">
                        <input type="hidden" name="thoi_gian_hen" id="modal_thoi_gian_hen">
                        <input type="hidden" name="payment_gateway" id="payment_gateway" value="">

                        <div class="modal-body">
                            <!-- Thông tin bác sĩ -->
                            <div class="card bg-light mb-3">
                                <div class="card-body">
                                    <h6 class="card-title mb-2"><i class="fas fa-user-md text-primary"></i> Thông tin bác sĩ</h6>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <strong>Bác sĩ:</strong> {{ $bacSi->ho_ten }}
                                        </div>
                                        <div class="col-md-6">
                                            <strong>Chuyên khoa:</strong> {{ $bacSi->chuyen_khoa }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Thời gian đã chọn -->
                            <div class="card bg-light mb-3">
                                <div class="card-body">
                                    <h6 class="card-title mb-2"><i class="fas fa-clock text-success"></i> Thời gian khám</h6>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <strong>Ngày:</strong> <span id="display_ngay_hen"></span>
                                        </div>
                                        <div class="col-md-6">
                                            <strong>Giờ:</strong> <span id="display_thoi_gian_hen"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Chọn dịch vụ -->
                            <div class="mb-3">
                                <label class="form-label fw-bold">
                                    <i class="fas fa-stethoscope text-primary"></i> Chọn dịch vụ <span class="text-danger">*</span>
                                </label>
                                <select name="dich_vu_id" id="dich_vu_select" class="form-select" required>
                                    <option value="">-- Chọn dịch vụ --</option>
                                    @php
                                        $dichVus = \App\Models\DichVu::where('loai', 'Cơ bản')
                                            ->where('hoat_dong', true)
                                            ->orderBy('ten_dich_vu')
                                            ->get();
                                    @endphp
                                    @foreach($dichVus as $dv)
                                        <option value="{{ $dv->id }}" 
                                                data-price="{{ $dv->gia_tien }}" 
                                                data-duration="{{ $dv->thoi_gian }}">
                                            {{ $dv->ten_dich_vu }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Hiển thị giá tiền -->
                            <div id="service_info" class="card border-primary mb-3" style="display: none;">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-money-bill-wave text-success me-2 fs-4"></i>
                                                <div>
                                                    <small class="text-muted">Chi phí dự kiến</small>
                                                    <div class="fw-bold text-success fs-5" id="service_price">0 ₫</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-clock text-info me-2 fs-4"></i>
                                                <div>
                                                    <small class="text-muted">Thời gian khám</small>
                                                    <div class="fw-bold text-info fs-5" id="service_duration">0 phút</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @auth
                                <!-- Thông tin bệnh nhân (đã đăng nhập) -->
                                <div class="card bg-light mb-3">
                                    <div class="card-body">
                                        <h6 class="card-title mb-2"><i class="fas fa-user text-primary"></i> Thông tin bệnh nhân</h6>
                                        <div class="row">
                                            <div class="col-md-6 mb-2">
                                                <strong>Họ tên:</strong> {{ auth()->user()->name }}
                                            </div>
                                            <div class="col-md-6 mb-2">
                                                <strong>Email:</strong> {{ auth()->user()->email }}
                                            </div>
                                            @if(auth()->user()->benh_nhan)
                                                <div class="col-md-6">
                                                    <strong>SĐT:</strong> {{ auth()->user()->benh_nhan->so_dien_thoai ?? 'Chưa cập nhật' }}
                                                </div>
                                                <div class="col-md-6">
                                                    <strong>Ngày sinh:</strong> {{ auth()->user()->benh_nhan->ngay_sinh ? \Carbon\Carbon::parse(auth()->user()->benh_nhan->ngay_sinh)->format('d/m/Y') : 'Chưa cập nhật' }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <!-- Hidden inputs cho user đã đăng nhập -->
                                <input type="hidden" name="ho_ten" value="{{ auth()->user()->name }}">
                                <input type="hidden" name="so_dien_thoai" value="{{ auth()->user()->benh_nhan->so_dien_thoai ?? '' }}">
                            @else
                                <!-- Form thông tin bệnh nhân (chưa đăng nhập) -->
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">Họ tên <span class="text-danger">*</span></label>
                                        <input type="text" name="ho_ten" class="form-control" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">Số điện thoại <span class="text-danger">*</span></label>
                                        <input type="text" name="so_dien_thoai" class="form-control" required>
                                    </div>
                                </div>
                            @endauth

                            <!-- Ghi chú -->
                            <div class="mb-3">
                                <label class="form-label fw-bold">Ghi chú (tùy chọn)</label>
                                <textarea name="ghi_chu" class="form-control" rows="3" placeholder="Mô tả triệu chứng hoặc lý do khám..."></textarea>
                            </div>

                            <!-- Hình thức thanh toán -->
                            <div class="mb-3">
                                <label class="form-label fw-bold">
                                    <i class="fas fa-credit-card text-warning"></i> Hình thức thanh toán <span class="text-danger">*</span>
                                </label>
                                <div class="row g-2">
                                    <div class="col-md-6">
                                        <div class="form-check card p-3 h-100">
                                            <input class="form-check-input" type="radio" name="payment_method" id="payment_tien_mat" value="tien_mat" checked>
                                            <label class="form-check-label w-100" for="payment_tien_mat">
                                                <i class="fas fa-money-bill-wave text-success"></i> Tiền mặt
                                                <small class="d-block text-muted">Thanh toán tại phòng khám</small>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check card p-3 h-100">
                                            <input class="form-check-input" type="radio" name="payment_method" id="payment_chuyen_khoan" value="chuyen_khoan">
                                            <label class="form-check-label w-100" for="payment_chuyen_khoan">
                                                <i class="fas fa-university text-primary"></i> Chuyển khoản
                                                <small class="d-block text-muted">Thanh toán qua VNPay/Momo</small>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                <i class="fas fa-times"></i> Hủy
                            </button>
                            <button type="button" class="btn btn-primary" id="submit_booking">
                                <i class="fas fa-check"></i> Xác nhận đặt lịch
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Payment Gateway Modal -->
        <div class="modal fade" id="paymentGatewayModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title">
                            <i class="fas fa-credit-card"></i> Chọn phương thức thanh toán
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-4">
                        <p class="text-center mb-3">Vui lòng chọn cổng thanh toán:</p>
                        <div class="row g-3">
                            <div class="col-6">
                                <button type="button" class="btn btn-outline-primary w-100 h-100 p-4 payment-gateway-btn" data-gateway="vnpay">
                                    <img src="https://vnpay.vn/s1/statics.vnpay.vn/2023/9/06ncktiwd6dc1694418196384.png" 
                                         alt="VNPay" style="max-height: 40px;" class="mb-2">
                                    <div class="fw-bold">VNPay</div>
                                    <small class="text-muted">Thanh toán qua VNPay</small>
                                </button>
                            </div>
                            <div class="col-6">
                                <button type="button" class="btn btn-outline-danger w-100 h-100 p-4 payment-gateway-btn" data-gateway="momo">
                                    <img src="https://developers.momo.vn/v3/img/logo.png" 
                                         alt="Momo" style="max-height: 40px;" class="mb-2">
                                    <div class="fw-bold">Momo</div>
                                    <small class="text-muted">Thanh toán qua Momo</small>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Reviews Section -->
        @php
            $avgRating = \App\Models\DanhGia::getAverageRating($bacSi->id);
            $totalReviews = \App\Models\DanhGia::getTotalReviews($bacSi->id);
            $reviews = \App\Models\DanhGia::where('bac_si_id', $bacSi->id)
                ->approved()
                ->with('user')
                ->latest()
                ->take(5)
                ->get();
        @endphp

        @if ($totalReviews > 0)
            <div class="card mt-4 shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">
                        <i class="bi bi-star-fill text-warning"></i>
                        Đánh giá từ bệnh nhân
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-3 text-center border-end">
                            <div class="display-4 fw-bold text-warning">{{ number_format($avgRating, 1) }}</div>
                            <div class="mb-2">
                                @for ($i = 1; $i <= 5; $i++)
                                    @if ($i <= round($avgRating))
                                        <i class="bi bi-star-fill text-warning"></i>
                                    @else
                                        <i class="bi bi-star text-muted"></i>
                                    @endif
                                @endfor
                            </div>
                            <div class="text-muted">{{ $totalReviews }} đánh giá</div>
                        </div>
                        <div class="col-md-9">
                            @php
                                $distribution = \App\Models\DanhGia::getRatingDistribution($bacSi->id);
                            @endphp
                            @for ($i = 5; $i >= 1; $i--)
                                @php
                                    $count = $distribution[$i] ?? 0;
                                    $percentage = $totalReviews > 0 ? ($count / $totalReviews) * 100 : 0;
                                @endphp
                                <div class="d-flex align-items-center mb-2">
                                    <span class="me-2" style="width: 60px;">{{ $i }} sao</span>
                                    <div class="progress flex-grow-1" style="height: 20px;">
                                        <div class="progress-bar bg-warning" role="progressbar"
                                             style="width: {{ $percentage }}%"
                                             aria-valuenow="{{ $percentage }}" aria-valuemin="0" aria-valuemax="100">
                                        </div>
                                    </div>
                                    <span class="ms-2 text-muted" style="width: 50px;">{{ $count }}</span>
                                </div>
                            @endfor
                        </div>
                    </div>

                    <hr>

                    <h6 class="fw-bold mb-3">Đánh giá gần đây</h6>
                    @foreach ($reviews as $review)
                        <div class="mb-3 pb-3 border-bottom">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <strong>{{ $review->user->name }}</strong>
                                    <div class="text-warning">
                                        @for ($i = 1; $i <= 5; $i++)
                                            @if ($i <= $review->rating)
                                                <i class="bi bi-star-fill"></i>
                                            @else
                                                <i class="bi bi-star"></i>
                                            @endif
                                        @endfor
                                    </div>
                                </div>
                                <small class="text-muted">{{ $review->created_at->diffForHumans() }}</small>
                            </div>
                            <p class="mb-0">{{ $review->noi_dung }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    <script>
        function bookSlot(date, time) {
            // Điền thông tin vào modal
            document.getElementById('modal_ngay_hen').value = date;
            document.getElementById('modal_thoi_gian_hen').value = time;
            
            // Format hiển thị
            const dateObj = new Date(date);
            const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            document.getElementById('display_ngay_hen').textContent = dateObj.toLocaleDateString('vi-VN', options);
            document.getElementById('display_thoi_gian_hen').textContent = time;
            
            // Reset form
            document.getElementById('bookingForm').reset();
            document.getElementById('modal_ngay_hen').value = date;
            document.getElementById('modal_thoi_gian_hen').value = time;
            document.getElementById('service_info').style.display = 'none';
            document.getElementById('payment_gateway').value = '';
            
            // Hiển thị modal
            const modal = new bootstrap.Modal(document.getElementById('bookingModal'));
            modal.show();
        }

        // Xử lý khi chọn dịch vụ
        document.addEventListener('DOMContentLoaded', function() {
            const dichVuSelect = document.getElementById('dich_vu_select');
            const serviceInfo = document.getElementById('service_info');
            const servicePrice = document.getElementById('service_price');
            const serviceDuration = document.getElementById('service_duration');
            const bookingForm = document.getElementById('bookingForm');
            const submitBtn = document.getElementById('submit_booking');
            let paymentGatewayModal = null;

            // Khởi tạo modal sau khi DOM load xong
            const paymentGatewayModalEl = document.getElementById('paymentGatewayModal');
            if (paymentGatewayModalEl) {
                paymentGatewayModal = new bootstrap.Modal(paymentGatewayModalEl);
            }

            // Hiển thị thông tin dịch vụ
            dichVuSelect.addEventListener('change', function() {
                const selected = this.options[this.selectedIndex];
                if (this.value) {
                    const price = selected.getAttribute('data-price');
                    const duration = selected.getAttribute('data-duration');
                    
                    servicePrice.textContent = new Intl.NumberFormat('vi-VN', {
                        style: 'currency',
                        currency: 'VND'
                    }).format(price);
                    serviceDuration.textContent = duration + ' phút';
                    serviceInfo.style.display = 'block';
                } else {
                    serviceInfo.style.display = 'none';
                }
            });

            // Xử lý nút xác nhận
            submitBtn.addEventListener('click', function(e) {
                e.preventDefault();
                
                const dichVuId = dichVuSelect.value;
                if (!dichVuId) {
                    alert('Vui lòng chọn dịch vụ!');
                    return;
                }

                const paymentMethodEl = document.querySelector('input[name="payment_method"]:checked');
                if (!paymentMethodEl) {
                    alert('Vui lòng chọn phương thức thanh toán!');
                    return;
                }
                
                const paymentMethod = paymentMethodEl.value;
                
                // Nếu chọn chuyển khoản, hiển thị popup chọn cổng
                if (paymentMethod === 'chuyen_khoan') {
                    if (paymentGatewayModal) {
                        paymentGatewayModal.show();
                    } else {
                        alert('Không thể hiển thị popup thanh toán. Vui lòng thử lại!');
                    }
                } else {
                    // Thanh toán tiền mặt, submit trực tiếp
                    submitForm();
                }
            });

            // Xử lý chọn cổng thanh toán
            document.querySelectorAll('.payment-gateway-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const gateway = this.getAttribute('data-gateway');
                    const gatewayInput = document.getElementById('payment_gateway');
                    
                    if (gatewayInput) {
                        gatewayInput.value = gateway;
                        
                        if (paymentGatewayModal) {
                            paymentGatewayModal.hide();
                        }
                        
                        // Submit form sau khi chọn cổng
                        setTimeout(() => submitForm(), 300); // Đợi modal đóng xong
                    } else {
                        alert('Lỗi: Không tìm thấy trường payment_gateway!');
                    }
                });
            });

            function submitForm() {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang xử lý...';
                
                const formData = new FormData(bookingForm);
                
                // Debug: log form data
                console.log('Submitting form with data:');
                for (let [key, value] of formData.entries()) {
                    console.log(key + ': ' + value);
                }
                
                fetch(bookingForm.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(response => {
                    console.log('Response status:', response.status);
                    return response.json();
                })
                .then(data => {
                    console.log('Response data:', data);
                    
                    if (data.success) {
                        // Nếu có payment_gateway (vnpay/momo), tạo form POST đến gateway
                        if (data.payment_gateway && data.hoa_don_id) {
                            console.log('Creating payment form for gateway:', data.payment_gateway);
                            
                            const gatewayRoute = data.payment_gateway === 'vnpay' 
                                ? '{{ route("vnpay.create") }}' 
                                : '{{ route("momo.create") }}';
                            
                            // Tạo form POST động
                            const paymentForm = document.createElement('form');
                            paymentForm.method = 'POST';
                            paymentForm.action = gatewayRoute;
                            
                            // CSRF token
                            const csrfInput = document.createElement('input');
                            csrfInput.type = 'hidden';
                            csrfInput.name = '_token';
                            csrfInput.value = '{{ csrf_token() }}';
                            paymentForm.appendChild(csrfInput);
                            
                            // hoa_don_id
                            const hoaDonInput = document.createElement('input');
                            hoaDonInput.type = 'hidden';
                            hoaDonInput.name = 'hoa_don_id';
                            hoaDonInput.value = data.hoa_don_id;
                            paymentForm.appendChild(hoaDonInput);
                            
                            // amount
                            const amountInput = document.createElement('input');
                            amountInput.type = 'hidden';
                            amountInput.name = 'amount';
                            amountInput.value = data.amount;
                            paymentForm.appendChild(amountInput);
                            
                            // Submit form
                            document.body.appendChild(paymentForm);
                            console.log('Submitting payment form to:', gatewayRoute);
                            paymentForm.submit();
                        } else if (data.payment_url) {
                            // Chưa chọn gateway, chuyển đến trang payment để chọn
                            console.log('Redirecting to payment selection page:', data.payment_url);
                            window.location.href = data.payment_url;
                        } else if (data.redirect_url) {
                            // Thanh toán tiền mặt, reload trang để hiển thị slot đã đặt với màu mới
                            console.log('Booking successful, reloading page');
                            
                            // Đóng modal trước
                            const bookingModalEl = document.getElementById('bookingModal');
                            const bookingModalInstance = bootstrap.Modal.getInstance(bookingModalEl);
                            if (bookingModalInstance) {
                                bookingModalInstance.hide();
                            }
                            
                            // Hiển thị thông báo thành công
                            setTimeout(() => {
                                alert(data.message || 'Đặt lịch thành công!');
                                window.location.reload();
                            }, 300);
                        } else {
                            // Thành công nhưng không có redirect
                            alert(data.message || 'Đặt lịch thành công!');
                            window.location.reload();
                        }
                    } else {
                        alert(data.message || 'Có lỗi xảy ra, vui lòng thử lại!');
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = '<i class="fas fa-check"></i> Xác nhận đặt lịch';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Có lỗi xảy ra, vui lòng thử lại!');
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="fas fa-check"></i> Xác nhận đặt lịch';
                });
            }
        });
    </script>
@endsection
