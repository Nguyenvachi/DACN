@php
    $isPatient = auth()->check() && auth()->user()->isPatient();
@endphp

@if ($isPatient)
    @extends('layouts.patient-modern')
    @section('title', 'Đặt Lịch Hẹn')
    @section('content')
    @else
        <!DOCTYPE html>
        <html lang="vi">

        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Đặt Lịch Hẹn - VietCare</title>
            {{-- Bootstrap & Icons --}}
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
            {{-- Tailwind (Giả lập qua CDN nếu project bạn chưa build CSS, nếu đã có @vite thì giữ nguyên @vite) --}}
            <script src="https://cdn.tailwindcss.com"></script>
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        </head>

        <body class="bg-gray-50">
    @endif

    <div class="min-h-screen py-10 bg-gray-50">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- ✅ HEADER CARD: THÔNG TIN BÁC SĨ --}}
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden mb-6 border border-gray-100 card">
                <div class="bg-gradient-to-r from-blue-600 to-indigo-700 px-8 py-8 text-white relative overflow-hidden">
                    {{-- Decorative circles --}}
                    <div class="absolute top-0 right-0 -mr-10 -mt-10 w-32 h-32 rounded-full bg-white opacity-10"></div>
                    <div class="absolute bottom-0 left-0 -ml-10 -mb-10 w-24 h-24 rounded-full bg-white opacity-10"></div>

                    <div
                        class="flex flex-col md:flex-row items-center md:items-start space-y-4 md:space-y-0 md:space-x-6 relative z-10">
                        <div class="flex-shrink-0">
                            @php
                                // Build an avatar image URL using the model accessor if available, else fallback to inline SVG.
                                $initials = '';
                                if (!empty($bacSi->ho_ten)) {
                                    $parts = preg_split('/\s+/', trim($bacSi->ho_ten));
                                    $initials = strtoupper(
                                        substr($parts[0] ?? '', 0, 1) .
                                            (isset($parts[count($parts) - 1])
                                                ? substr($parts[count($parts) - 1], 0, 1)
                                                : ''),
                                    );
                                }
                                // Use model attribute avatar_url if present (accessor defined in model). Otherwise, try Storage path of possible fields.
                                if (!empty($bacSi->avatar_url)) {
                                    $avatarUrl = $bacSi->avatar_url;
                                } elseif (!empty($bacSi->hinh_anh)) {
                                    $avatarUrl = Storage::url($bacSi->hinh_anh);
                                } else {
                                    $bg = '10b981';
                                    $text = urlencode($initials ?: 'B');
                                    $svg = "<svg xmlns='http://www.w3.org/2000/svg' width='160' height='160'><rect width='100%' height='100%' fill='transparent' /><circle cx='80' cy='80' r='80' fill='#{$bg}' /><text x='50%' y='50%' dy='.06em' text-anchor='middle' font-family='Inter, Arial, Helvetica, sans-serif' font-size='56' fill='white' font-weight='700'>{$initials}</text></svg>";
                                    $avatarUrl = 'data:image/svg+xml;base64,' . base64_encode($svg);
                                }
                            @endphp
                            <img src="{{ $avatarUrl }}" alt="{{ $bacSi->ho_ten }}"
                                class="w-24 h-24 rounded-full object-cover border-4 border-white/30 shadow-lg">
                        </div>
                        <div class="text-center md:text-left">
                            <h1 class="text-3xl font-bold mb-1">{{ $bacSi->ho_ten }}</h1>
                            <div class="flex items-center justify-center md:justify-start space-x-4 text-blue-100 text-sm">
                                <span class="bg-white/20 px-3 py-1 rounded-full"><i class="fas fa-stethoscope mr-1"></i>
                                    {{ $bacSi->chuyen_khoa }}</span>
                                <span><i class="fas fa-star text-yellow-400 mr-1"></i> 15 năm kinh nghiệm</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ✅ MAIN FORM BODY --}}
                <div class="p-8">

                    {{-- STEP WIZARD --}}
                    <div class="mb-10">
                        <div class="flex items-center justify-between relative">
                            <div class="absolute top-1/2 left-0 right-0 h-1 bg-gray-100 -translate-y-1/2 rounded-full z-0">
                            </div>
                            <div class="absolute top-1/2 left-0 h-1 bg-blue-600 -translate-y-1/2 rounded-full z-0 transition-all duration-500"
                                id="progressBar" style="width: 0%"></div>

                            {{-- Steps --}}
                            @foreach ([['icon' => 'fa-notes-medical', 'label' => 'Dịch Vụ'], ['icon' => 'fa-calendar-day', 'label' => 'Ngày Khám'], ['icon' => 'fa-clock', 'label' => 'Giờ Khám'], ['icon' => 'fa-check', 'label' => 'Xác Nhận']] as $index => $step)
                                <div class="step-item relative z-10 flex flex-col items-center group {{ $index == 0 ? 'active' : '' }}"
                                    data-step="{{ $index + 1 }}">
                                    <div
                                        class="step-circle w-10 h-10 rounded-full bg-white border-2 border-gray-300 text-gray-400 flex items-center justify-center text-sm font-bold transition-all duration-300 group-[.active]:border-blue-600 group-[.active]:bg-blue-600 group-[.active]:text-white group-[.completed]:bg-green-500 group-[.completed]:border-green-500 group-[.completed]:text-white shadow-sm">
                                        <i class="fas {{ $step['icon'] }}"></i>
                                    </div>
                                    <span
                                        class="text-xs font-semibold mt-2 text-gray-400 group-[.active]:text-blue-600 group-[.completed]:text-green-600 transition-colors">{{ $step['label'] }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- FORM START --}}
                    <form action="{{ route('lichhen.store') }}" method="POST" id="bookingForm" class="space-y-8">
                        @csrf
                        <input type="hidden" name="bac_si_id" value="{{ $bacSi->id }}">
                        <input type="hidden" name="thoi_gian_hen" id="input_thoi_gian_hen" required>
                        <input type="hidden" name="coupon_code" id="hidden_coupon" value="">
                        <input type="hidden" name="reminder" id="hidden_reminder" value="none">

                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

                            {{-- LEFT COLUMN --}}
                            <div class="space-y-6">
                                {{-- 1. Dịch Vụ --}}
                                <div class="form-group transition-all hover:translate-y-[-2px]">
                                    <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wider">
                                        1. Chọn Dịch Vụ <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <select id="dich_vu_id" name="dich_vu_id" required
                                            class="w-full pl-4 pr-10 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all appearance-none bg-white shadow-sm">
                                            <option value="">-- Vui lòng chọn dịch vụ --</option>
                                            @foreach ($danhSachDichVu as $dv)
                                                <option value="{{ $dv->id }}" data-price="{{ $dv->gia ?? 0 }}"
                                                    data-duration="{{ $dv->thoi_gian_uoc_tinh ?? 30 }}"
                                                    data-desc="{{ e($dv->mo_ta) }}"
                                                    {{ old('dich_vu_id') == $dv->id ? 'selected' : '' }}>
                                                    {{ $dv->ten_dich_vu }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <div
                                            class="absolute inset-y-0 right-0 flex items-center px-3 pointer-events-none text-gray-500">
                                            <i class="fas fa-chevron-down"></i>
                                        </div>
                                    </div>

                                    {{-- Service Summary Box --}}
                                    @include('public.lichhen._summary')
                                </div>
                                {{-- Appointment Type + Coupon & Reminder (Additions, non-destructive) --}}
                                <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-3 items-center">
                                    <div class="flex items-center gap-3">
                                        <label class="text-sm text-gray-600">Loại hẹn</label>
                                        <select id="loai_hen" name="loai_hen" class="px-3 py-2 border rounded-lg">
                                            <option value="in_person">Khám trực tiếp</option>
                                            <option value="online">Khám trực tuyến</option>
                                        </select>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <input type="text" id="coupon_code" name="coupon_code"
                                            placeholder="Mã khuyến mãi (nếu có)" class="px-3 py-2 border rounded-lg w-full">
                                        <button type="button" id="applyCouponBtn"
                                            class="px-3 py-2 bg-white border rounded-lg hover:bg-blue-50">Áp dụng</button>
                                    </div>
                                </div>
                                <div class="mt-3 flex items-center justify-between gap-3">
                                    <div class="flex items-center gap-2 text-sm text-gray-600">
                                        <i class="fas fa-bell mr-1"></i>
                                        <select id="reminder_select" class="px-3 py-2 border rounded-lg">
                                            <option value="none">Không nhắc</option>
                                            <option value="sms_1h">SMS trước 1 giờ</option>
                                            <option value="email_24h">Email trước 24 giờ</option>
                                        </select>
                                    </div>
                                    <div class="text-sm text-gray-500">Ước chờ: <span id="estWait" role="status"
                                            aria-live="polite">-</span></div>
                                </div>

                                {{-- 2. Ngày Hẹn --}}
                                <div class="form-group">
                                    <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wider">
                                        2. Chọn Ngày <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <input id="ngay_hen" name="ngay_hen" type="date" required
                                            value="{{ old('ngay_hen', date('Y-m-d')) }}" min="{{ date('Y-m-d') }}"
                                            class="w-full pl-4 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all shadow-sm">
                                    </div>
                                </div>
                            </div>

                            {{-- RIGHT COLUMN (Time Slots) --}}
                            <div class="bg-gray-50 rounded-2xl p-6 border border-gray-200">
                                <label
                                    class="block text-sm font-bold text-gray-700 mb-4 uppercase tracking-wider flex justify-between items-center">
                                    <span>3. Chọn Giờ Khám <span class="text-red-500">*</span></span>
                                    <div class="flex items-center gap-2">
                                        <span
                                            class="text-xs font-normal text-gray-500 bg-white px-2 py-1 rounded border">Còn
                                            trống</span>
                                        <span id="slotsCount"
                                            class="badge badge-success text-xs font-semibold text-white bg-green-500 px-2 py-1 rounded">-</span>
                                    </div>
                                </label>

                                <!-- Filters + Sticky Summary (Desktop) -->
                                <div class="flex items-center justify-between mb-3 gap-3">
                                    <div class="flex items-center gap-2">
                                        <button type="button" role="button" tabindex="0"
                                            class="filter-btn px-3 py-1 rounded border text-sm bg-white" data-filter="all"
                                            aria-pressed="true">Tất cả</button>
                                        <button type="button" role="button" tabindex="0"
                                            class="filter-btn px-3 py-1 rounded border text-sm bg-white" data-filter="am"
                                            aria-pressed="false">Sáng</button>
                                        <button type="button" role="button" tabindex="0"
                                            class="filter-btn px-3 py-1 rounded border text-sm bg-white" data-filter="pm"
                                            aria-pressed="false">Chiều</button>
                                    </div>
                                    <div id="summaryCard"
                                        class="hidden md:block w-56 bg-white shadow rounded-lg p-3 border border-gray-100">
                                        <div class="text-xs text-gray-500">Tóm tắt</div>
                                        <div class="mt-2 text-sm text-gray-700">
                                            <div class="flex justify-between"><span>Dịch vụ</span><span
                                                    id="sumSvc">-</span></div>
                                            <div class="flex justify-between"><span>Ngày</span><span
                                                    id="sumDate">-</span></div>
                                            <div class="flex justify-between"><span>Giờ</span><span
                                                    id="sumTime">-</span></div>
                                            <div class="flex justify-between"><span>Giá</span><span
                                                    id="sumPrice">-</span></div>
                                        </div>
                                    </div>
                                </div>

                                <div id="slotsContainer" class="min-h-[200px] flex items-center justify-center"
                                    role="listbox" aria-label="Danh sách khung giờ" aria-live="polite">
                                    {{-- Placeholder State --}}
                                    <div class="text-center text-gray-400">
                                        <i class="fas fa-hand-pointer text-4xl mb-3 opacity-50 animate-bounce"></i>
                                        <p class="text-sm">Vui lòng chọn <strong>Dịch vụ</strong> và
                                            <strong>Ngày</strong><br>để xem lịch trống.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- GUEST INFO SECTION (Only if not logged in) --}}
                        @guest
                            <div class="border-t pt-8 mt-4">
                                <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                                    <span
                                        class="w-8 h-8 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center mr-2 text-sm"><i
                                            class="fas fa-user"></i></span>
                                    Thông Tin Bệnh Nhân
                                </h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Họ và Tên <span
                                                class="text-red-500">*</span></label>
                                        <input type="text" name="ten_benh_nhan" aria-required="true" aria-label="Họ và Tên"
                                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 transition"
                                            placeholder="Nguyễn Văn A" required value="{{ old('ten_benh_nhan', auth()->check() ? auth()->user()->name : '') }}">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Số Điện Thoại <span
                                                class="text-red-500">*</span></label>
                                        <input type="tel" name="sdt_benh_nhan" aria-required="true" aria-label="Số Điện Thoại"
                                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 transition"
                                            placeholder="09xxxxxxx" required value="{{ old('sdt_benh_nhan', auth()->check() ? auth()->user()->so_dien_thoai : '') }}">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Email (để nhận
                                            lịch)</label>
                                        <input type="email" name="email_benh_nhan" aria-label="Email"
                                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 transition"
                                            placeholder="email@example.com" value="{{ old('email_benh_nhan', auth()->check() ? auth()->user()->email : '') }}">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Ngày Sinh</label>
                                        <input type="date" name="ngay_sinh_benh_nhan" aria-label="Ngày sinh"
                                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 transition">
                                            value="{{ old('ngay_sinh_benh_nhan', auth()->check() && auth()->user()->ngay_sinh ? auth()->user()->ngay_sinh->format('Y-m-d') : '') }}">
                                    </div>
                                </div>
                            </div>
                        @endguest

                        {{-- NOTE SECTION --}}
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wider">Ghi chú /
                                Triệu chứng</label>
                            <textarea id="ghi_chu" name="ghi_chu" rows="3"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all shadow-sm"
                                placeholder="Ví dụ: Đau bụng dưới, đã khám cách đây 2 tuần...">{{ old('ghi_chu') }}</textarea>
                        </div>

                        {{-- ACTIONS --}}
                        <div class="flex items-center justify-end pt-6 border-t border-gray-100">
                            <a href="{{ route('public.bacsi.index') }}"
                                class="mr-4 px-6 py-3 rounded-xl text-gray-600 hover:bg-gray-100 font-medium transition">
                                Hủy bỏ
                            </a>
                            <button type="submit" id="submitBtn"
                                class="px-8 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-xl font-bold shadow-lg hover:shadow-blue-500/30 hover:-translate-y-0.5 transition-all duration-200 flex items-center">
                                <span>Xác Nhận Đặt Lịch</span>
                                <i class="fas fa-arrow-right ml-2"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile sticky summary bar: shows selected service, time, price, and CTA -->
    <div id="mobileSummaryBar" class="mobile-summary">
        <div class="summary-left flex items-center gap-3">
            <div>
                <div class="text-xs text-gray-400">Chọn</div>
                <div id="mbSvc" class="font-semibold text-sm">-</div>
            </div>
            <div>
                <div class="text-xs text-gray-400">Giờ</div>
                <div id="mbTime" class="font-semibold text-sm">-</div>
            </div>
        </div>
        <div class="summary-right flex items-center gap-3">
            <div class="text-right">
                <div class="text-xs text-gray-400">Giá</div>
                <div id="mbPrice" class="font-semibold text-blue-600">-</div>
            </div>
            <button id="mbBookBtn" class="px-4 py-2 btn-primary rounded-xl font-medium">Đặt lịch</button>
        </div>
    </div>

    {{-- ✅ MODAL CONFIRMATION --}}
    <div class="modal fade" id="confirmModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-2xl rounded-2xl">
                <div
                    class="modal-header bg-gradient-to-r from-blue-600 to-indigo-600 text-white border-0 rounded-t-2xl p-5">
                    <h5 class="modal-title font-bold text-lg"><i class="fas fa-clipboard-check mr-2"></i> Xác Nhận Thông
                        Tin</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body p-6">
                    <div class="bg-blue-50 p-4 rounded-xl border border-blue-100 mb-4">
                        <p class="text-sm text-blue-800 mb-1">Bác sĩ phụ trách</p>
                        <p class="font-bold text-blue-900 text-lg">{{ $bacSi->ho_ten }}</p>
                    </div>
                    <div class="space-y-3 text-sm text-gray-600">
                        <div class="flex justify-between border-b border-gray-100 pb-2">
                            <span>Dịch vụ:</span>
                            <span class="font-bold text-gray-900 text-right" id="modalService">...</span>
                        </div>
                        <div class="flex justify-between border-b border-gray-100 pb-2">
                            <span>Thời gian:</span>
                            <span class="font-bold text-gray-900" id="modalDateTime">...</span>
                        </div>
                        <div class="flex justify-between border-b border-gray-100 pb-2">
                            <span>Chi phí (dự kiến):</span>
                            <span class="font-bold text-blue-600 text-lg" id="modalPrice">...</span>
                        </div>
                        <div class="flex justify-between border-b border-gray-100 pb-2">
                            <span>Loại hẹn:</span>
                            <span class="font-bold" id="modalType">-</span>
                        </div>
                        <div class="flex justify-between border-b border-gray-100 pb-2">
                            <span>Nhắc nhở:</span>
                            <span class="font-bold" id="modalReminder">-</span>
                        </div>
                        <div class="flex justify-between border-b border-gray-100 pb-2">
                            <span>Mã KM:</span>
                            <span class="font-bold" id="modalCoupon">-</span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 p-5 bg-gray-50 rounded-b-2xl">
                    <button type="button" class="btn btn-light text-gray-500 font-medium" data-bs-dismiss="modal">Quay
                        lại</button>
                    <button type="button" id="confirmYes"
                        class="btn bg-blue-600 text-white font-bold px-6 py-2 rounded-lg hover:bg-blue-700 shadow-md">
                        Đồng ý đặt lịch
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- ✅ CUSTOM CSS --}}
    <style>
        /* Sync theme variables with layouts.patient-modern */
        :root {
            --ps-primary: #10b981;
            /* green */
            --ps-primary-dark: #059669;
            --ps-secondary: #3b82f6;
            /* fallback */
            --ps-text: #1f2937;
            --ps-border: #e5e7eb;
        }

        /* Scoped sync for when this page is inside patient-modern/layout */
        .main-content .bg-gradient-to-r.from-blue-600.to-indigo-700 {
            background: linear-gradient(90deg, var(--ps-primary), var(--ps-primary-dark)) !important;
        }

        /* Button: use site btn-primary gradient to match design */
        #submitBtn {
            background: linear-gradient(135deg, var(--ps-primary), var(--ps-primary-dark)) !important;
            box-shadow: 0 6px 12px rgba(5, 150, 105, 0.12) !important;
        }

        /* Card alignment to layout's card style */
        .main-content .bg-white.rounded-2xl {
            border-radius: 1rem !important;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.06) !important;
        }

        /* Sync slot-pill accent to primary color */
        .slot-pill.selected {
            background: linear-gradient(135deg, var(--ps-primary), var(--ps-primary-dark)) !important;
        }

        .slot-btn {
            @apply w-full py-3 px-2 rounded-lg border border-gray-200 bg-white text-gray-700 font-medium transition-all duration-200 hover:border-blue-400 hover:text-blue-600 hover:shadow-md;
        }

        .slot-btn.selected {
            @apply bg-blue-600 border-blue-600 text-white shadow-lg ring-2 ring-blue-200 ring-offset-1 transform -translate-y-0.5;
        }

        .animate-fade-in {
            animation: fadeIn 0.3s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(5px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Filters */
        .filter-btn {
            @apply px-3 py-1 rounded border;
        }

        .filter-btn.active {
            @apply bg-blue-600 text-white border-transparent;
        }

        /* Slot focus & accessibility */
        .slot-btn:focus {
            outline: 3px solid rgba(37, 99, 235, 0.15);
            outline-offset: 2px;
        }

        /* Summary card override on smaller screens */
        @media (max-width: 900px) {
            #summaryCard {
                display: none !important;
            }
        }

        /* Pill slot style */
        .slot-pill {
            border-radius: 12px;
            padding: 12px 14px;
        }

        .slot-pill .text-xs {
            color: rgba(0, 0, 0, 0.6);
        }

        .slot-pill:disabled,
        .slot-pill.disabled {
            opacity: .6;
            cursor: not-allowed;
        }

        .slot-pill.locked-by-me {
            outline: 2px dashed rgba(59,130,246,0.5);
            box-shadow: 0 2px 8px rgba(59,130,246,0.12);
        }
        .slot-pill.locked-by-other {
            border: 1px dashed #ccc;
            background: linear-gradient(180deg, rgba(255,255,255,0.9), rgba(250,250,250,0.95));
            opacity: 0.75;
            cursor: not-allowed;
        }

        /* Mobile sticky footer summary */
        #mobileSummaryBar {
            display: none;
        }

        @media (max-width: 900px) {
            #mobileSummaryBar {
                display: flex;
                position: fixed;
                left: 0;
                right: 0;
                bottom: 0;
                z-index: 60;
                gap: 10px;
                padding: 12px;
                background: white;
                border-top: 1px solid #e5e7eb;
                box-shadow: 0 -6px 16px rgba(0, 0, 0, 0.06);
                justify-content: space-between;
                align-items: center;
            }

            #mobileSummaryBar .summary-left {
                display: flex;
                gap: 12px;
                align-items: center;
            }

            #mobileSummaryBar .summary-right {
                display: flex;
                gap: 10px;
                align-items: center;
            }
        }
    </style>

    {{-- ✅ JAVASCRIPT --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('bookingForm');
            const dichVuSelect = document.getElementById('dich_vu_id');
            const dateInput = document.getElementById('ngay_hen');
            const slotsContainer = document.getElementById('slotsContainer');
            const timeInput = document.getElementById('input_thoi_gian_hen');
            const submitBtn = document.getElementById('submitBtn');
            const bacSiId = "{{ $bacSi->id }}";
            const userId = "{{ auth()->id() ?? '' }}";

            // Summary Elements
            const summaryBox = document.getElementById('serviceSummary');
            const summaryPrice = document.getElementById('summaryPrice');
            const summaryDuration = document.getElementById('summaryDuration');
            const progressBar = document.getElementById('progressBar');
            // New UI elements
            const loaiHen = document.getElementById('loai_hen');
            const couponInput = document.getElementById('coupon_code');
            const applyCouponBtn = document.getElementById('applyCouponBtn');
            const reminderSelect = document.getElementById('reminder_select');
            const filterBtns = document.querySelectorAll('.filter-btn');
            const sumSvc = document.getElementById('sumSvc');
            const sumDate = document.getElementById('sumDate');
            const sumTime = document.getElementById('sumTime');
            const sumPrice = document.getElementById('sumPrice');
            const estWaitEl = document.getElementById('estWait');

            // Formatter
            const formatter = new Intl.NumberFormat('vi-VN', {
                style: 'currency',
                currency: 'VND'
            });

            // Locking state for slots held by the current user
            window.heldSlot = null; // { bacSiId, date, time, until }
            let lockTicker = null;

            async function tryLockSlot(bacSiId, ngay, gio, duration = 30) {
                const tokenEl = document.querySelector('meta[name="csrf-token"]');
                const token = tokenEl ? tokenEl.getAttribute('content') : '';
                try {
                    const r = await fetch('/api/slot-lock', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': token,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ bac_si_id: bacSiId, ngay: ngay, gio: gio, duration: duration })
                    });
                    if (!r.ok) {
                        const j = await r.json().catch(() => ({}));
                        return { success: false, message: j.message || 'Không thể giữ chỗ' };
                    }
                    const j = await r.json();
                    // Expect success true
                    if (j.success) return { success: true, locked_until: j.locked_until || null };
                    return { success: false, message: j.message || 'Không thể giữ chỗ' };
                } catch (e) {
                    return { success: false, message: 'Lỗi mạng khi giữ chỗ' };
                }
            }

            async function releaseHeldLock() {
                if (!window.heldSlot) return true;
                const tokenEl = document.querySelector('meta[name="csrf-token"]');
                const token = tokenEl ? tokenEl.getAttribute('content') : '';
                try {
                    const r = await fetch('/api/slot-unlock', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': token,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ bac_si_id: window.heldSlot.bacSiId, ngay: window.heldSlot.date, gio: window.heldSlot.time })
                    });
                    window.heldSlot = null;
                    if (lockTicker) { clearInterval(lockTicker); lockTicker = null; }
                    // remove any UI indicators
                    document.querySelectorAll('.slot-btn').forEach(b => b.classList.remove('locked-by-me'));
                    return true;
                } catch (e) {
                    return false;
                }
            }

            function updateLockUI() {
                if (!window.heldSlot) return;
                // add visual indicator to the locked button
                document.querySelectorAll('.slot-btn').forEach(b => {
                    if (b.dataset.time === window.heldSlot.time) {
                        b.classList.add('locked-by-me');
                    } else {
                        b.classList.remove('locked-by-me');
                    }
                });
                // Start ticker to clear expired locks client-side
                if (!lockTicker) {
                    lockTicker = setInterval(() => {
                        if (!window.heldSlot || !window.heldSlot.until) { clearInterval(lockTicker); lockTicker = null; return; }
                        const now = new Date();
                        const until = new Date(window.heldSlot.until);
                        if (now >= until) {
                            // expired
                            releaseHeldLock();
                        }
                    }, 1000);
                }
                // update textual info
                const lockInfoEl = document.getElementById('lockInfo');
                if (lockInfoEl && window.heldSlot && window.heldSlot.until) {
                    const until = new Date(window.heldSlot.until);
                    const now = new Date();
                    const secLeft = Math.max(0, Math.round((until - now) / 1000));
                    const minutes = Math.floor(secLeft / 60);
                    const seconds = secLeft % 60;
                    lockInfoEl.textContent = `Đang giữ chỗ — hết hạn trong ${minutes}m ${seconds}s`;
                }
            }

            function updateProgress(step) {
                const percent = ((step - 1) / 3) * 100;
                progressBar.style.width = `${percent}%`;

                document.querySelectorAll('.step-item').forEach(item => {
                    const s = parseInt(item.dataset.step);
                    if (s < step) {
                        item.classList.add('completed');
                        item.classList.remove('active');
                    } else if (s === step) {
                        item.classList.add('active');
                        item.classList.remove('completed');
                    } else {
                        item.classList.remove('active', 'completed');
                    }
                });
            }

            // 1. Service Change
            dichVuSelect.addEventListener('change', function() {
                const opt = this.selectedOptions[0];
                if (this.value) {
                    summaryBox.classList.remove('hidden');
                    summaryPrice.textContent = formatter.format(opt.dataset.price);
                    summaryDuration.innerHTML =
                        `<i class="far fa-clock mr-1"></i> ${opt.dataset.duration} phút`;
                    updateProgress(2);
                    if (dateInput.value) fetchSlots();
                    // update summary card
                    sumSvc && (sumSvc.textContent = opt.textContent.trim());
                    sumPrice && (sumPrice.textContent = formatter.format(opt.dataset.price));
                } else {
                    summaryBox.classList.add('hidden');
                    updateProgress(1);
                }
            });

            // 2. Date Change
            dateInput.addEventListener('change', function() {
                if (this.value && dichVuSelect.value) {
                    fetchSlots();
                    sumDate && (sumDate.textContent = this.value.split('-').reverse().join('/'));
                } else if (this.value) {
                    updateProgress(2);
                }
            });

            // 3. Fetch Slots
            async function fetchSlots() {
                const dv = dichVuSelect.value;
                const date = dateInput.value;
                if (!dv || !date) return;

                // Loading State
                slotsContainer.innerHTML = `
            <div class="text-center py-10">
                <div class="inline-block animate-spin rounded-full h-8 w-8 border-4 border-blue-500 border-t-transparent"></div>
                <p class="text-gray-500 mt-2 text-sm">Đang tìm lịch trống...</p>
            </div>
        `;
                updateProgress(3);

                try {
                    const res = await fetch(`/api/bac-si/${bacSiId}/thoi-gian-trong/${date}?dich_vu_id=${dv}`);
                    const data = await res.json();

                    if (!data.slots || data.slots.length === 0) {
                        slotsContainer.innerHTML = `
                    <div class="text-center py-8 bg-red-50 rounded-xl border border-red-100 text-red-500 w-full">
                        <i class="far fa-calendar-times text-3xl mb-2"></i>
                        <p class="font-medium">Hết lịch trống ngày này</p>
                        <p class="text-xs opacity-75">Vui lòng chọn ngày khác</p>
                    </div>
                `;
                        const slotCountEl = document.getElementById('slotsCount');
                        if (slotCountEl) {
                            slotCountEl.textContent = 0;
                            slotCountEl.classList.remove('bg-green-500');
                            slotCountEl.classList.add('bg-red-500');
                        }
                        return;
                    }

                    // Render Grid
                    let html =
                        '<div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3 w-full animate-fade-in">';
                    data.slots.forEach((slot, idx) => {
                        const lockedByOther = slot.locked_by && String(slot.locked_by) !== String(userId);
                        const disabledAttr = (slot.available === 0 || slot.available === '0' || lockedByOther) ?
                            'disabled aria-disabled="true" tabindex="-1"' : 'tabindex="0"';
                        const disabledClass = (slot.available === 0 || slot.available === '0') ?
                            ' disabled opacity-60 cursor-not-allowed' : '';
                        const lockedClass = lockedByOther ? ' locked-by-other' : '';
                        html += `
                    <button type="button" class="slot-btn group slot-pill${disabledClass}${lockedClass}" ${disabledAttr} data-time="${slot.time}" data-index="${idx}" data-available="${slot.available ?? ''}" data-locked-by="${slot.locked_by ?? ''}" data-locked-until="${slot.locked_until ?? ''}" role="option" aria-pressed="false" aria-label="Khung giờ ${slot.time} ${slot.available ? '(' + slot.available + ' trống)' : ''}">
                        <div class="flex items-center justify-between w-full">
                            <span class="block text-lg font-bold group-hover:scale-105 transition-transform">${slot.time}</span>
                            ${slot.available ? `<span class="inline-block text-xs bg-white/10 px-2 py-1 rounded text-gray-800">${slot.available}</span>` : ''}
                        </div>
                        ${slot.available ? `<div class="text-xs text-gray-500 mt-1">Còn ${slot.available}</div>` : ''}${lockedByOther ? `<div class="text-xs text-red-500 mt-1">Đang giữ</div>` : ''}
                    </button>
                `;
                    });
                    html += '</div>';
                    slotsContainer.innerHTML = html;
                    // Update count badge with color
                    const slotCountEl = document.getElementById('slotsCount');
                    if (slotCountEl) {
                        slotCountEl.textContent = data.slots.length;
                        slotCountEl.classList.remove('bg-green-500', 'bg-red-500');
                        if (data.slots.length > 0) slotCountEl.classList.add('bg-green-500');
                        else slotCountEl.classList.add('bg-red-500');
                    }

                            // Click Event
                    document.querySelectorAll('.slot-btn').forEach(btn => {
                        btn.addEventListener('click', async function() {
                            document.querySelectorAll('.slot-btn').forEach(b => b.classList
                                .remove('selected'));
                            this.classList.add('selected');
                            timeInput.value = this.dataset.time;
                            updateProgress(4);
                            // Update summary time
                            sumTime && (sumTime.textContent = this.dataset.time);
                            // Update estimated wait time
                            const idx = Number(this.dataset.index || 0);
                            const duration = Number(dichVuSelect.selectedOptions[0]?.dataset
                                ?.duration || 30);
                            const wait = idx * (duration + 5);
                            estWaitEl && (estWaitEl.textContent = wait === 0 ? 'Ngay lập tức' :
                                `~ ${wait} phút`);
                            // Set aria pressed
                            document.querySelectorAll('.slot-btn').forEach(b => b.setAttribute(
                                'aria-pressed', 'false'));
                            this.setAttribute('aria-pressed', 'true');
                            // Update mobile summary info
                                                        // ===== SLOT LOCK: attempt to reserve this slot via API
                                                        try {
                                                            // release any previously held lock
                                                            if (window.heldSlot && (window.heldSlot.time !== this.dataset.time)) {
                                                                await releaseHeldLock();
                                                            }

                                                            const resp = await tryLockSlot(bacSiId, dateInput.value, this.dataset.time, Number(dichVuSelect.selectedOptions[0]?.dataset?.duration || 30));
                                                            if (!resp.success) {
                                                                alert(resp.message || 'Không thể giữ chỗ: ' + (resp.message || 'Đã có người khác giữ'));
                                                                // Mark this button as disabled
                                                                this.classList.add('disabled');
                                                                this.disabled = true;
                                                                return;
                                                            }
                                                            // Successfully locked
                                                            window.heldSlot = { bacSiId, date: dateInput.value, time: this.dataset.time, until: resp.locked_until || null };
                                                            updateLockUI();
                                                        } catch (err) {
                                                            console.error('Lock error', err);
                                                        }

                            const mbBar = document.getElementById('mobileSummaryBar');
                            const mbSvcEl = document.getElementById('mbSvc');
                            const mbTimeEl = document.getElementById('mbTime');
                            const mbPriceEl = document.getElementById('mbPrice');
                            if (mbBar) {
                                mbBar.style.display = 'flex';
                                mbSvcEl && (mbSvcEl.textContent = dichVuSelect.selectedOptions[
                                    0]?.textContent.trim() || '-');
                                mbTimeEl && (mbTimeEl.textContent = this.dataset.time || '-');
                                mbPriceEl && (mbPriceEl.textContent = summaryPrice
                                    .textContent || '-');
                            }
                        });

                        // Keyboard nav: arrows and enter/space to select
                        btn.addEventListener('keydown', function(e) {
                            const all = Array.from(document.querySelectorAll('.slot-btn'));
                            const i = all.indexOf(this);
                            if (e.key === 'ArrowRight' || e.key === 'ArrowDown') {
                                e.preventDefault();
                                (all[i + 1] || all[0]).focus();
                            } else if (e.key === 'ArrowLeft' || e.key === 'ArrowUp') {
                                e.preventDefault();
                                (all[i - 1] || all[all.length - 1]).focus();
                            } else if (e.key === 'Enter' || e.key === ' ') {
                                e.preventDefault();
                                this.click();
                            }
                        });
                    });

                } catch (e) {
                    console.error(e);
                    slotsContainer.innerHTML = `<p class="text-red-500">Lỗi tải lịch. Vui lòng thử lại.</p>`;
                }
            }

            // 4. Modal Confirm logic
            const confirmModal = new bootstrap.Modal(document.getElementById('confirmModal'));

            submitBtn.addEventListener('click', function(e) {
                e.preventDefault();

                // Basic Validation
                if (!dichVuSelect.value || !dateInput.value || !timeInput.value) {
                    alert('Vui lòng hoàn tất chọn Dịch vụ, Ngày và Giờ khám!');
                    return;
                }

                // Fill Modal Data
                document.getElementById('modalService').textContent = dichVuSelect.selectedOptions[0].text;
                document.getElementById('modalDateTime').textContent =
                    `${timeInput.value} - ${dateInput.value.split('-').reverse().join('/')}`;
                document.getElementById('modalPrice').textContent = document.getElementById('summaryPrice')
                    .textContent;
                // Additional info
                const lt = loaiHen ? loaiHen.options[loaiHen.selectedIndex].text : '-';
                const rm = reminderSelect ? reminderSelect.options[reminderSelect.selectedIndex].text :
                    'Không nhắc';
                const hc = document.getElementById('hidden_coupon') ? document.getElementById(
                    'hidden_coupon').value : '';
                document.getElementById('modalType').textContent = lt;
                document.getElementById('modalReminder').textContent = rm;
                document.getElementById('modalCoupon').textContent = hc || '-';

                confirmModal.show();
            });

            document.getElementById('confirmYes').addEventListener('click', function() {
                form.submit();
            });

            // Ensure we release any held lock on page unload or when leaving
            window.addEventListener('beforeunload', function() {
                if (window.heldSlot) {
                    // best-effort release with keepalive
                    const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
                    try {
                        fetch('/api/slot-unlock', { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': token }, body: JSON.stringify({ bac_si_id: window.heldSlot.bacSiId, ngay: window.heldSlot.date, gio: window.heldSlot.time }), keepalive: true });
                    } catch (e) { /* ignore */ }
                }
            });

            // Also unlock when we submit form (after confirm)
            form.addEventListener('submit', function() {
                if (window.heldSlot) {
                    const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
                    try {
                        fetch('/api/slot-unlock', { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': token }, body: JSON.stringify({ bac_si_id: window.heldSlot.bacSiId, ngay: window.heldSlot.date, gio: window.heldSlot.time }), keepalive: true });
                    } catch (e) { /* ignore */ }
                }
            });

            // Set summary initial values and auto load logic (if validation fail redirect)
            if (dichVuSelect.value) {
                sumSvc && (sumSvc.textContent = dichVuSelect.selectedOptions[0].textContent.trim());
                summaryPrice && (summaryPrice.textContent = formatter.format(Number(dichVuSelect.selectedOptions[0]
                    ?.dataset?.price || 0)));
            }
            if (dateInput.value) {
                sumDate && (sumDate.textContent = dateInput.value.split('-').reverse().join('/'));
            }
            if (dichVuSelect.value && dateInput.value) fetchSlots();

            // Coupon logic - use server-side validation endpoint
            applyCouponBtn && applyCouponBtn.addEventListener('click', async function() {
                if (!dichVuSelect.value) return alert('Vui lòng chọn dịch vụ trước khi áp dụng mã');
                const code = (couponInput.value || '').trim();
                if (!code) return alert('Vui lòng nhập mã khuyến mãi');
                const rawPrice = Number(dichVuSelect.selectedOptions[0]?.dataset?.price || 0);
                const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
                try {
                    const r = await fetch('/api/coupons/validate', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': token, 'Accept': 'application/json' },
                        body: JSON.stringify({ ma: code, tong_tien: rawPrice })
                    });
                    const j = await r.json();
                    if (!r.ok || !j.success) {
                        alert(j.message || 'Mã không hợp lệ');
                        return;
                    }
                    // Apply discount preview
                    const finalPrice = rawPrice - (j.coupon ? Number(j.coupon.giam_gia || 0) : 0);
                    summaryPrice.textContent = formatter.format(finalPrice);
                    const hc = document.getElementById('hidden_coupon');
                    if (hc) hc.value = code;
                    sumPrice && (sumPrice.textContent = formatter.format(finalPrice));
                    alert('Áp dụng mã: ' + code);
                } catch (e) {
                    alert('Lỗi khi kiểm tra mã giảm giá');
                }
            });

            // Press Enter on coupon input to apply
            couponInput && couponInput.addEventListener('keydown', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    applyCouponBtn && applyCouponBtn.click();
                }
            });

            // Filters
            filterBtns.forEach(b => {
                b.addEventListener('click', function() {
                    const f = this.dataset.filter;
                    filterBtns.forEach(bb => bb.classList.remove('active'));
                    this.classList.add('active');
                    filterBtns.forEach(bb => bb.setAttribute('aria-pressed', 'false'));
                    this.setAttribute('aria-pressed', 'true');
                    // client-side filter on existing buttons
                    document.querySelectorAll('.slot-btn').forEach(btn => {
                        const t = btn.dataset.time; // '08:00'
                        const hour = Number(t.split(':')[0]);
                        if (f === 'am' && hour >= 12) btn.style.display = 'none';
                        else if (f === 'pm' && hour < 12) btn.style.display = 'none';
                        else btn.style.display = '';
                    });
                });
            });

            // Keyboard support for filter buttons
            filterBtns.forEach(b => {
                b.addEventListener('keydown', function(e) {
                    if (e.key === 'Enter' || e.key === ' ') {
                        e.preventDefault();
                        b.click();
                    }
                });
            });

            // Apply default filter active
            const defFilter = Array.from(filterBtns).find(b => b.dataset.filter === 'all');
            defFilter && defFilter.classList.add('active');

            // Reminder select binding
            reminderSelect && reminderSelect.addEventListener('change', function() {
                const hr = document.getElementById('hidden_reminder');
                if (hr) hr.value = this.value;
            });

            // Mobile summary CTA binds to the same confirm flow
            const mbBookBtn = document.getElementById('mbBookBtn');
            mbBookBtn && mbBookBtn.addEventListener('click', function() {
                submitBtn && submitBtn.click();
            });
        });
    </script>

    @if ($isPatient)
    @endsection
@else
    </body>

    </html>
@endif
