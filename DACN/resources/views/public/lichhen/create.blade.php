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
    <!DOCTYPE html>
    <html lang="vi">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Đặt Lịch Hẹn - VietCare</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body>
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
                    {{-- ENHANCED: Step Indicator (Parent: public/lichhen/create.blade.php) --}}
                    <div class="mb-8">
                        <div class="flex items-center justify-between relative">
                            <div class="absolute top-5 left-0 right-0 h-1 bg-gray-200" style="z-index: 0;">
                                <div id="progressBar" class="h-full bg-gradient-to-r from-blue-500 to-blue-600 transition-all duration-500" style="width: 0%;"></div>
                            </div>

                            <div class="flex justify-between w-full relative" style="z-index: 1;">
                                <div class="flex flex-col items-center step-item active" data-step="1">
                                    <div class="w-10 h-10 rounded-full bg-blue-600 text-white flex items-center justify-center font-bold shadow-lg step-circle">
                                        <i class="fas fa-stethoscope"></i>
                                    </div>
                                    <span class="text-xs mt-2 font-semibold text-blue-600">Chọn Dịch Vụ</span>
                                </div>

                                <div class="flex flex-col items-center step-item" data-step="2">
                                    <div class="w-10 h-10 rounded-full bg-gray-300 text-gray-600 flex items-center justify-center font-bold shadow step-circle">
                                        <i class="fas fa-calendar-alt"></i>
                                    </div>
                                    <span class="text-xs mt-2 font-medium text-gray-500">Chọn Ngày</span>
                                </div>

                                <div class="flex flex-col items-center step-item" data-step="3">
                                    <div class="w-10 h-10 rounded-full bg-gray-300 text-gray-600 flex items-center justify-center font-bold shadow step-circle">
                                        <i class="fas fa-clock"></i>
                                    </div>
                                    <span class="text-xs mt-2 font-medium text-gray-500">Chọn Giờ</span>
                                </div>

                                <div class="flex flex-col items-center step-item" data-step="4">
                                    <div class="w-10 h-10 rounded-full bg-gray-300 text-gray-600 flex items-center justify-center font-bold shadow step-circle">
                                        <i class="fas fa-check"></i>
                                    </div>
                                    <span class="text-xs mt-2 font-medium text-gray-500">Xác Nhận</span>
                                </div>
                            </div>
                        </div>
                    </div>

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

                        {{-- ✅ THÔNG TIN BÁC SĨ ĐÃ CHỌN --}}
                        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 p-5 rounded-xl border-2 border-blue-200 shadow-sm">
                            <div class="flex items-center space-x-4">
                                <div class="flex-shrink-0">
                                    @if($bacSi->hinh_anh)
                                        <img src="{{ asset('storage/' . $bacSi->hinh_anh) }}"
                                             alt="{{ $bacSi->ho_ten }}"
                                             class="w-20 h-20 rounded-full object-cover border-4 border-white shadow-md">
                                    @else
                                        <div class="w-20 h-20 rounded-full bg-blue-500 flex items-center justify-center text-white text-2xl font-bold border-4 border-white shadow-md">
                                            {{ strtoupper(substr($bacSi->ho_ten, 0, 1)) }}
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center space-x-2 mb-1">
                                        <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"></path>
                                        </svg>
                                        <h3 class="text-xl font-bold text-gray-800">{{ $bacSi->ho_ten }}</h3>
                                    </div>
                                    @if($bacSi->chuyenKhoas && $bacSi->chuyenKhoas->isNotEmpty())
                                        <p class="text-sm text-gray-600 mb-2">
                                            <svg class="w-4 h-4 inline mr-1 text-indigo-500" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path>
                                                <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"></path>
                                            </svg>
                                            {{ $bacSi->chuyenKhoas->pluck('ten_chuyen_khoa')->join(', ') }}
                                        </p>
                                    @endif
                                    <div class="flex items-center space-x-4 text-sm">
                                        @php
                                            $avgRating = $bacSi->danhGias()->avg('rating') ?? 0;
                                            $totalReviews = $bacSi->danhGias()->count();
                                        @endphp
                                        @if($totalReviews > 0)
                                            <div class="flex items-center text-yellow-500">
                                                <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                </svg>
                                                <span class="ml-1 font-semibold">{{ number_format($avgRating, 1) }}</span>
                                                <span class="ml-1 text-gray-500">({{ $totalReviews }} đánh giá)</span>
                                            </div>
                                        @endif
                                        @if($bacSi->kinh_nghiem)
                                            <span class="text-gray-600">
                                                <svg class="w-4 h-4 inline text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                                                </svg>
                                                {{ $bacSi->kinh_nghiem }} năm kinh nghiệm
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- ✅ DỊCH VỤ --}}
                        <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
                            <label for="dich_vu_id" class="block text-sm font-semibold text-gray-700 mb-2">
                                <svg class="w-4 h-4 inline mr-1 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                                </svg>
                                Chọn dịch vụ <span class="text-red-500">*</span>
                            </label>
                            <select id="dich_vu_id" name="dich_vu_id" required 
                                    class="w-full px-4 py-2.5 border border-blue-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                                <option value="">-- Chọn dịch vụ --</option>
                                @foreach($danhSachDichVu as $dv)
                                <option value="{{ $dv->id }}" 
                                        data-price="{{ $dv->gia_tien ?? 0 }}"
                                        data-duration="{{ $dv->thoi_gian ?? 30 }}"
                                        {{ old('dich_vu_id') == $dv->id ? 'selected' : '' }}>
                                    {{ $dv->ten_dich_vu }}
                                </option>
                                @endforeach
                            </select>

                            {{-- Thông tin dịch vụ đã chọn --}}
                            <div id="serviceInfo" class="hidden mt-4 p-4 bg-white rounded-lg border-2 border-blue-300 shadow-sm animate-fade-in">
                                <div class="flex items-center justify-between mb-3">
                                    <h4 class="text-sm font-bold text-gray-700 flex items-center">
                                        <svg class="w-5 h-5 mr-2 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path>
                                            <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"></path>
                                        </svg>
                                        Thông tin dịch vụ
                                    </h4>
                                </div>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                    {{-- Giá tiền --}}
                                    <div class="bg-gradient-to-br from-green-50 to-emerald-50 p-3 rounded-lg border border-green-200">
                                        <div class="flex items-center space-x-2 mb-1">
                                            <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"></path>
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd"></path>
                                            </svg>
                                            <span class="text-xs font-semibold text-gray-600">Chi phí dự kiến</span>
                                        </div>
                                        <p id="servicePrice" class="text-2xl font-bold text-green-700">0 ₫</p>
                                    </div>

                                    {{-- Thời gian --}}
                                    <div class="bg-gradient-to-br from-purple-50 to-indigo-50 p-3 rounded-lg border border-purple-200">
                                        <div class="flex items-center space-x-2 mb-1">
                                            <svg class="w-5 h-5 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                            </svg>
                                            <span class="text-xs font-semibold text-gray-600">Thời gian khám</span>
                                        </div>
                                        <p id="serviceDuration" class="text-2xl font-bold text-purple-700">0 phút</p>
                                    </div>
                                </div>

                                <div class="mt-3 flex items-start space-x-2 text-xs text-gray-600 bg-blue-50 p-2 rounded">
                                    <svg class="w-4 h-4 text-blue-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span>Thời gian có thể thay đổi tùy theo tình trạng sức khỏe thực tế</span>
                                </div>
                            </div>
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
                        {{-- ENHANCED: Visual improvements with loading and animations (Parent: public/lichhen/create.blade.php) --}}
                        <div class="bg-yellow-50 p-4 rounded-lg border border-yellow-200">
                            <label class="block text-sm font-semibold text-gray-700 mb-3">
                                <svg class="w-4 h-4 inline mr-1 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                </svg>
                                Chọn khung giờ <span class="text-red-500">*</span>
                            </label>
                            <div id="slotsContainer" class="text-sm text-gray-600">
                                <div class="flex items-center justify-center space-x-2 text-gray-500 py-8">
                                    <svg class="animate-pulse h-5 w-5 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
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
    {{-- ENHANCED: Modern styles with gradients and animations (Parent: public/lichhen/create.blade.php) --}}
    <style>
        /* Fade-in animation */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in {
            animation: fadeIn 0.5s ease-out forwards;
        }

        /* Step Indicator Styles */
        .step-item.active .step-circle {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4);
            animation: pulse 2s infinite;
        }

        .step-item.completed .step-circle {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.4);
        }

        .step-item.active span {
            color: #2563eb;
            font-weight: 600;
        }

        .step-item.completed span {
            color: #059669;
        }

        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.05);
            }
        }

        /* Slot Button Styles */
        .slot-btn {
            cursor: pointer;
            border: 2px solid #e5e7eb;
            padding: 12px 20px;
            margin: 6px;
            border-radius: 12px;
            background: #ffffff;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            font-weight: 500;
            display: inline-block;
            min-width: 130px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .slot-btn::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(59, 130, 246, 0.1);
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }

        .slot-btn:hover::before {
            width: 300px;
            height: 300px;
        }

        .slot-btn:hover {
            border-color: #3b82f6;
            background: #eff6ff;
            transform: translateY(-3px);
            box-shadow: 0 8px 16px rgba(59, 130, 246, 0.2);
        }

        .slot-btn.selected {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: #ffffff;
            border-color: #2563eb;
            box-shadow: 0 8px 20px rgba(37, 99, 235, 0.4);
            transform: translateY(-3px) scale(1.05);
        }

        .slot-btn.selected::after {
            content: '✓';
            position: absolute;
            top: -8px;
            right: -8px;
            width: 24px;
            height: 24px;
            background: #10b981;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: bold;
            box-shadow: 0 2px 8px rgba(16, 185, 129, 0.4);
        }

        .slot-btn.disabled {
            opacity: 0.4;
            cursor: not-allowed;
            background: #f3f4f6;
        }

        .slot-btn.disabled:hover {
            transform: none;
            box-shadow: none;
        }

        /* Loading Animation */
        @keyframes shimmer {
            0% {
                background-position: -468px 0;
            }
            100% {
                background-position: 468px 0;
            }
        }

        .loading-slots {
            animation: shimmer 1.5s infinite;
            background: linear-gradient(to right, #f6f7f8 0%, #edeef1 20%, #f6f7f8 40%, #f6f7f8 100%);
            background-size: 800px 104px;
        }

        /* Message Styles */
        .error-msg {
            color: #dc2626;
            font-weight: 500;
            animation: shake 0.5s;
        }

        .success-msg {
            color: #16a34a;
            font-weight: 500;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-10px); }
            75% { transform: translateX(10px); }
        }

        /* Smooth transitions */
        * {
            transition: all 0.2s ease;
        }
    </style>

    {{-- ✅ JAVASCRIPT --}}
    {{-- ENHANCED: Step tracking and better UX (Parent: public/lichhen/create.blade.php) --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('bookingForm');
            const dichVuSelect = document.getElementById('dich_vu_id');
            const dateInput = document.getElementById('ngay_hen');
            const slotsContainer = document.getElementById('slotsContainer');
            const serviceInfo = document.getElementById('serviceInfo');
            const servicePrice = document.getElementById('servicePrice');
            const serviceDuration = document.getElementById('serviceDuration');
            const bacSiId = "{{ $bacSi->id }}";
            const submitBtn = document.getElementById('submitBtn');
            let currentStep = 1;

            // Format currency
            function formatCurrency(amount) {
                return new Intl.NumberFormat('vi-VN', {
                    style: 'currency',
                    currency: 'VND'
                }).format(amount);
            }

            // Handle service selection
            dichVuSelect.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                
                if (this.value) {
                    const price = selectedOption.getAttribute('data-price') || 0;
                    const duration = selectedOption.getAttribute('data-duration') || 30;
                    
                    // Show service info with animation
                    serviceInfo.classList.remove('hidden');
                    servicePrice.textContent = formatCurrency(price);
                    serviceDuration.textContent = duration + ' phút';
                    
                    updateStep(2);
                    
                    // Auto fetch slots if date is already selected
                    if (dateInput.value) {
                        fetchSlots();
                    }
                } else {
                    serviceInfo.classList.add('hidden');
                    updateStep(1);
                    showMessage('Vui lòng chọn dịch vụ và ngày hẹn để xem khung giờ trống.', 'info');
                }
            });

            // Trigger on page load if service is pre-selected
            if (dichVuSelect.value) {
                dichVuSelect.dispatchEvent(new Event('change'));
            }

            // Step management
            function updateStep(step) {
                currentStep = step;
                const progressBar = document.getElementById('progressBar');
                const progressPercent = ((step - 1) / 3) * 100;
                progressBar.style.width = progressPercent + '%';

                document.querySelectorAll('.step-item').forEach((item, index) => {
                    const itemStep = index + 1;
                    item.classList.remove('active', 'completed');

                    if (itemStep < step) {
                        item.classList.add('completed');
                        item.querySelector('.step-circle').style.background = 'linear-gradient(135deg, #10b981 0%, #059669 100%)';
                        item.querySelector('.step-circle').innerHTML = '<i class="fas fa-check"></i>';
                    } else if (itemStep === step) {
                        item.classList.add('active');
                    } else {
                        item.querySelector('.step-circle').style.background = '#d1d5db';
                        const icons = ['fa-stethoscope', 'fa-calendar-alt', 'fa-clock', 'fa-check'];
                        item.querySelector('.step-circle').innerHTML = `<i class="fas ${icons[itemStep - 1]}"></i>`;
                    }
                });
            }

            function showMessage(msg, type = 'info') {
                const icons = {
                    error: '<svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>',
                    success: '<svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>',
                    info: '<svg class="w-5 h-5 text-blue-500 animate-pulse" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>',
                    loading: '<svg class="animate-spin h-5 w-5 text-blue-500" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>'
                };
                slotsContainer.innerHTML = `
                    <div class="flex items-center justify-center space-x-3 py-8 ${type === 'error' ? 'error-msg' : type === 'success' ? 'success-msg' : ''}">
                        ${icons[type] || icons.info}
                        <span class="text-base">${msg}</span>
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

                showMessage('Đang tải khung giờ trống...', 'loading');
                updateStep(3);

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
                        showMessage('❌ Không có khung giờ trống trong ngày này. Vui lòng chọn ngày khác.', 'error');
                        updateStep(2);
                        return;
                    }

                    // Hiển thị slots với animation
                    let html = '<div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3 animate-fade-in">';
                    data.slots.forEach((slot, index) => {
                        html += `
                            <button type="button" class="slot-btn" data-time="${slot.time}" style="animation-delay: ${index * 0.05}s;">
                                <div class="text-base font-bold">${slot.time}</div>
                                <div class="text-xs opacity-75 mt-1">${slot.label}</div>
                            </button>
                        `;
                    });
                    html += '</div>';
                    html += '<p class="text-center text-sm text-gray-500 mt-4"><i class="fas fa-info-circle mr-1"></i>Có ' + data.slots.length + ' khung giờ khả dụng</p>';

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
                            updateStep(4);
                        });
                    });

                } catch (error) {
                    console.error('❌ Slots fetch error:', error);
                    showMessage('❌ Có lỗi xảy ra khi tải khung giờ. Vui lòng thử lại.', 'error');
                    updateStep(2);
                }
            }

            // Validate before submit
            form.addEventListener('submit', function(e) {
                const chosen = form.querySelector('input[name="thoi_gian_hen"]');
                if (!chosen || !chosen.value) {
                    e.preventDefault();
                    showMessage('⚠️ Vui lòng chọn khung giờ trước khi gửi.', 'error');
                    slotsContainer.scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });
                    return false;
                }

                // Show loading state
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<svg class="animate-spin h-5 w-5 inline mr-2" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Đang xử lý...';
            });

            // Event listeners with step tracking
            dateInput.addEventListener('change', function() {
                if (this.value && dichVuSelect.value) {
                    fetchSlots();
                }
            });

            // Auto load nếu có sẵn giá trị
            if (dichVuSelect.value && dateInput.value) {
                updateStep(2);
                fetchSlots();
            }
        });
    </script>

@if($isPatient)
    @endsection
@else
    </body>
    </html>
@endif
