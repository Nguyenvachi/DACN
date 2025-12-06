@php
    $isPatient = auth()->check() && auth()->user()->role === 'patient';
@endphp

@if($isPatient)
    @extends('layouts.patient-modern')

    @section('title', 'Đặt Lịch Hẹn')
    @section('page-title', 'Đặt Lịch Hẹn')
    @section('page-subtitle', 'Tạo lịch hẹn khám bệnh mới')

    @section('content')
@else
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Đặt Lịch Hẹn') }}
        </h2>
    </x-slot>
@endif

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                {{-- ✅ HEADER CARD --}}
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-8 text-white">
                    <div class="flex items-center space-x-4">
                        <div class="bg-white rounded-full p-3">
                            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold">{{ $bacSi->ho_ten }}</h2>
                            <p class="text-blue-100">{{ $bacSi->chuyen_khoa }}</p>
                        </div>
                    </div>
                </div>

                {{-- ✅ FORM BODY --}}
                <div class="p-8">
                    @if ($errors->any())
                    <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded">
                        <div class="flex items-center mb-2">
                            <svg class="w-5 h-5 text-red-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                            </svg>
                            <p class="font-semibold text-red-800">Có lỗi xảy ra:</p>
                        </div>
                        <ul class="list-disc list-inside text-red-700 text-sm space-y-1">
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <form id="bookingForm" method="POST" action="{{ route('lichhen.store') }}" class="space-y-6">
                        @csrf
                        <input type="hidden" name="bac_si_id" value="{{ $bacSi->id }}">

                        {{-- ✅ CHUYÊN KHOA (Optional - nếu muốn cho user đổi bác sĩ) --}}
                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path>
                                    <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"></path>
                                </svg>
                                Chuyên khoa
                            </label>
                            <select id="chuyen-khoa-select" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                                <option value="">Chọn chuyên khoa</option>
                            </select>
                        </div>

                        {{-- ✅ BÁC SĨ --}}
                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"></path>
                                </svg>
                                Bác sĩ
                            </label>
                            <select id="bac-si-select" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                                <option value="{{ $bacSi->id }}" selected>{{ $bacSi->ho_ten }}</option>
                            </select>
                        </div>

                        {{-- ✅ DỊCH VỤ --}}
                        <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
                            <label for="dich_vu_id" class="block text-sm font-semibold text-gray-700 mb-2">
                                <svg class="w-4 h-4 inline mr-1 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                                </svg>
                                Chọn dịch vụ <span class="text-red-500">*</span>
                            </label>
                            <select id="dich_vu_id" name="dich_vu_id" required class="w-full px-4 py-2.5 border border-blue-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                                <option value="">-- Chọn dịch vụ --</option>
                                @foreach($danhSachDichVu as $dv)
                                <option value="{{ $dv->id }}" {{ old('dich_vu_id') == $dv->id ? 'selected' : '' }}>
                                    {{ $dv->ten_dich_vu }} ({{ number_format($dv->gia ?? 0) }} VND) - {{ $dv->thoi_gian_uoc_tinh ?? 30 }} phút
                                </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- ✅ NGÀY HẸN --}}
                        <div class="bg-green-50 p-4 rounded-lg border border-green-200">
                            <label for="ngay_hen" class="block text-sm font-semibold text-gray-700 mb-2">
                                <svg class="w-4 h-4 inline mr-1 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                                </svg>
                                Chọn ngày hẹn <span class="text-red-500">*</span>
                            </label>
                            <input id="ngay_hen" name="ngay_hen" type="date" required
                                value="{{ old('ngay_hen', date('Y-m-d')) }}"
                                min="{{ date('Y-m-d') }}"
                                class="w-full px-4 py-2.5 border border-green-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition">
                        </div>

                        {{-- ✅ KHUNG GIỜ TRỐNG --}}
                        <div class="bg-yellow-50 p-4 rounded-lg border border-yellow-200">
                            <label class="block text-sm font-semibold text-gray-700 mb-3">
                                <svg class="w-4 h-4 inline mr-1 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                </svg>
                                Chọn khung giờ <span class="text-red-500">*</span>
                            </label>
                            <div id="slotsContainer" class="text-sm text-gray-600">
                                <div class="flex items-center space-x-2 text-gray-500">
                                    <svg class="animate-spin h-5 w-5" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    <span>Vui lòng chọn dịch vụ và ngày hẹn để xem khung giờ trống...</span>
                                </div>
                            </div>
                        </div>

                        {{-- ✅ GHI CHÚ --}}
                        <div>
                            <label for="ghi_chu" class="block text-sm font-semibold text-gray-700 mb-2">
                                <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path>
                                </svg>
                                Ghi chú (tùy chọn)
                            </label>
                            <textarea id="ghi_chu" name="ghi_chu" rows="3"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                                placeholder="Nhập ghi chú nếu có...">{{ old('ghi_chu') }}</textarea>
                        </div>

                        {{-- ✅ BUTTONS --}}
                        <div class="flex items-center justify-end space-x-3 pt-4 border-t">
                            <a href="{{ route('public.bacsi.index') }}"
                                class="px-6 py-2.5 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition font-medium">
                                Hủy
                            </a>
                            <button type="submit" id="submitBtn"
                                class="px-6 py-2.5 bg-gradient-to-r from-blue-500 to-blue-600 text-black rounded-lg hover:from-blue-600 hover:to-blue-700 transition font-medium shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                                <svg class="w-5 h-5 inline mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                Xác nhận Đặt Lịch
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- ✅ STYLES --}}
    <style>
        .slot-btn {
            cursor: pointer;
            border: 2px solid #e5e7eb;
            padding: 10px 16px;
            margin: 6px;
            border-radius: 8px;
            background: #ffffff;
            transition: all 0.2s ease;
            font-weight: 500;
            display: inline-block;
            min-width: 120px;
            text-align: center;
        }

        .slot-btn:hover {
            border-color: #3b82f6;
            background: #eff6ff;
            transform: translateY(-2px);
            box-shadow: 0 4px 6px rgba(59, 130, 246, 0.1);
        }

        .slot-btn.selected {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: #ffffff;
            border-color: #2563eb;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
        }

        .slot-btn.disabled {
            opacity: 0.4;
            cursor: not-allowed;
            background: #f3f4f6;
        }

        .error-msg {
            color: #dc2626;
            font-weight: 500;
        }

        .success-msg {
            color: #16a34a;
            font-weight: 500;
        }
    </style>

    {{-- ✅ JAVASCRIPT --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('bookingForm');
            const dichVuSelect = document.getElementById('dich_vu_id');
            const dateInput = document.getElementById('ngay_hen');
            const slotsContainer = document.getElementById('slotsContainer');
            const bacSiId = "{{ $bacSi->id }}";
            const submitBtn = document.getElementById('submitBtn');

            function showMessage(msg, type = 'info') {
                const icons = {
                    error: '<svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>',
                    success: '<svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>',
                    info: '<svg class="w-5 h-5 text-blue-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>'
                };
                slotsContainer.innerHTML = `
                    <div class="flex items-center space-x-2 ${type === 'error' ? 'error-msg' : type === 'success' ? 'success-msg' : ''}">
                        ${icons[type] || icons.info}
                        <span>${msg}</span>
                    </div>
                `;
            }

            async function fetchSlots() {
                const dichVuId = dichVuSelect.value;
                const ngayHen = dateInput.value;

                if (!dichVuId || !ngayHen) {
                    showMessage('Vui lòng chọn dịch vụ và ngày hẹn để xem khung giờ trống.', 'info');
                    return;
                }

                showMessage('Đang tải khung giờ trống...', 'info');

                try {
                    const url = `/api/bac-si/${bacSiId}/thoi-gian-trong/${ngayHen}?dich_vu_id=${dichVuId}`;
                    console.log('🔄 Fetching:', url);

                    const response = await fetch(url);

                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }

                    const data = await response.json();
                    console.log('✅ Data received:', data);

                    if (!data.slots || data.slots.length === 0) {
                        showMessage('Không có khung giờ trống trong ngày này. Vui lòng chọn ngày khác.', 'error');
                        return;
                    }

                    // Hiển thị slots
                    let html = '<div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">';
                    data.slots.forEach(slot => {
                        html += `
                            <button type="button" class="slot-btn" data-time="${slot.time}">
                                <div class="text-sm font-semibold">${slot.time}</div>
                                <div class="text-xs opacity-75">${slot.label}</div>
                            </button>
                        `;
                    });
                    html += '</div>';

                    slotsContainer.innerHTML = html;

                    // Add click handlers
                    document.querySelectorAll('.slot-btn').forEach(btn => {
                        btn.addEventListener('click', function() {
                            document.querySelectorAll('.slot-btn').forEach(b => b.classList.remove('selected'));
                            this.classList.add('selected');

                            let hiddenInput = form.querySelector('input[name="thoi_gian_hen"]');
                            if (!hiddenInput) {
                                hiddenInput = document.createElement('input');
                                hiddenInput.type = 'hidden';
                                hiddenInput.name = 'thoi_gian_hen';
                                form.appendChild(hiddenInput);
                            }
                            hiddenInput.value = this.dataset.time;
                            console.log('✅ Đã chọn:', this.dataset.time);
                        });
                    });

                } catch (error) {
                    console.error('❌ Slots fetch error:', error);
                    showMessage('Có lỗi xảy ra khi tải khung giờ. Vui lòng thử lại.', 'error');
                }
            }

            // Validate before submit
            form.addEventListener('submit', function(e) {
                const chosen = form.querySelector('input[name="thoi_gian_hen"]');
                if (!chosen || !chosen.value) {
                    e.preventDefault();
                    showMessage('Vui lòng chọn khung giờ trước khi gửi.', 'error');
                    slotsContainer.scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });
                }
            });

            dichVuSelect.addEventListener('change', fetchSlots);
            dateInput.addEventListener('change', fetchSlots);

            // Auto load nếu có sẵn giá trị
            if (dichVuSelect.value && dateInput.value) {
                fetchSlots();
            }
        });
    </script>

    {{-- ✅ AJAX CHUYÊN KHOA & BÁC SĨ --}}
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ckSel = document.getElementById('chuyen-khoa-select');
            const bsSel = document.getElementById('bac-si-select');

            // ✅ BƯỚC 1: Định nghĩa ID bác sĩ đang xem
            // Dùng ' ' để đảm bảo nó là chuỗi, so sánh an toàn với this.value
            const currentBacSiId = '{{ $bacSi->id }}';

            // Tải danh sách chuyên khoa
            fetch('{{ route("ajax.chuyenkhoa") }}')
                .then(r => r.json())
                .then(list => {
                    list.forEach(ck => {
                        const opt = document.createElement('option');
                        opt.value = ck;
                        opt.textContent = ck;
                        ckSel.appendChild(opt);
                    });
                    ckSel.value = '{{ $bacSi->chuyen_khoa }}';
                });

            // Khi chọn chuyên khoa -> tải bác sĩ
            ckSel.addEventListener('change', () => {
                const ck = ckSel.value;
                bsSel.innerHTML = '<option value="">-- Chọn bác sĩ --</option>';
                if (!ck) return;

                const url = new URL('{{ route("ajax.bacsi.byChuyenKhoa") }}', window.location.origin);
                url.searchParams.set('ck', ck);

                fetch(url)
                    .then(r => r.json())
                    .then(doctors => {
                        doctors.forEach(d => {
                            const opt = document.createElement('option');
                            opt.value = d.id;
                            opt.textContent = d.ho_ten;

                            // ✅ BƯỚC 2: Dùng biến JS để so sánh
                            if (d.id == currentBacSiId) {
                                opt.selected = true;
                            }

                            bsSel.appendChild(opt);
                        });
                    });
            });

            // Khi đổi bác sĩ -> chuyển trang
            bsSel.addEventListener('change', function() {
                // ✅ BƯỚC 3: Dùng biến JS để so sánh
                if (this.value && this.value != currentBacSiId) {
                    window.location.href = `/dat-lich/${this.value}`;
                }
        });
    });
</script>
@endpush

@if(!$isPatient ?? false)
</x-app-layout>
@endif
@endsection
