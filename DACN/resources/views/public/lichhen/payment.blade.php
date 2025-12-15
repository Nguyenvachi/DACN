{{--
    Parent: resources/views/public/lichhen/
    Child: payment.blade.php
    Purpose: Trang chọn phương thức thanh toán cho bệnh nhân
--}}
@php
    $isPatient = auth()->check() && auth()->user()->isPatient();
@endphp

@if($isPatient)
    @extends('layouts.patient-modern')

    @section('title', 'Thanh toán lịch hẹn')
    @section('page-title', 'Thanh toán lịch hẹn #' . $lichHen->id)
    @section('page-subtitle', 'Chọn phương thức thanh toán')

    @section('content')
@else
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Thanh toán lịch hẹn #{{ $lichHen->id }}
        </h2>
    </x-slot>
@endif

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">

                    @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                        {{ session('success') }}
                    </div>
                    @endif

                    {{-- Thông tin lịch hẹn --}}
                    <div class="bg-gray-50 p-4 rounded-lg mb-6">
                        <h3 class="font-bold text-lg mb-3">Thông tin lịch hẹn</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm">
                            <div>
                                <span class="font-semibold">Bác sĩ:</span>
                                {{ $lichHen->bacSi->ho_ten ?? '-' }}
                            </div>
                            <div>
                                <span class="font-semibold">Dịch vụ:</span>
                                {{ $lichHen->dichVu->ten_dich_vu ?? '-' }}
                            </div>
                            <div>
                                <span class="font-semibold">Ngày hẹn:</span>
                                {{ \Carbon\Carbon::parse($lichHen->ngay_hen)->format('d/m/Y') }}
                            </div>
                            <div>
                                <span class="font-semibold">Giờ hẹn:</span>
                                {{ $lichHen->thoi_gian_hen }}
                            </div>
                            <div class="col-span-2">
                                <span class="font-semibold">Tổng tiền:</span>
                                <span class="text-red-600 font-bold text-xl">
                                    {{ number_format($lichHen->tong_tien, 0, ',', '.') }} VNĐ
                                </span>
                            </div>
                        </div>
                    </div>

                    {{-- Chọn phương thức thanh toán --}}
                    <div class="space-y-4">
                        <h3 class="font-bold text-lg mb-4">Chọn phương thức thanh toán</h3>

                        {{-- VNPay --}}
                        <form method="POST" action="{{ route('vnpay.create') }}" class="border border-blue-300 rounded-lg p-4 hover:shadow-md transition">
                            @csrf
                            <input type="hidden" name="hoa_don_id" value="{{ $hoaDon->id }}">
                            <input type="hidden" name="amount" value="{{ $hoaDon->tong_tien }}">

                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-4">
                                    <img src="data:image/png;base64,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"
                                        alt="VNPay"
                                        class="h-12">
                                    <div>
                                        <h4 class="font-semibold">VNPay</h4>
                                        <p class="text-sm text-gray-600">Thanh toán qua ví điện tử, thẻ ATM, thẻ tín dụng</p>
                                    </div>
                                </div>
                                <button type="submit"
                                    class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-black font-semibold rounded-lg transition">
                                    Thanh toán
                                </button>
                            </div>
                        </form>

                        {{-- MoMo --}}
                        <form method="POST" action="{{ route('momo.create') }}" class="border border-pink-300 rounded-lg p-4 hover:shadow-md transition">
                            @csrf
                            <input type="hidden" name="hoa_don_id" value="{{ $hoaDon->id }}">
                            <input type="hidden" name="amount" value="{{ $hoaDon->tong_tien }}">

                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-4">
                                    <img src="https://developers.momo.vn/v3/img/logo.svg"
                                        alt="MoMo"
                                        class="h-12">
                                    <div>
                                        <h4 class="font-semibold">MoMo</h4>
                                        <p class="text-sm text-gray-600">Thanh toán qua ví MoMo</p>
                                    </div>
                                </div>
                                <button type="submit"
                                    class="px-6 py-2 bg-pink-600 hover:bg-pink-700 text-black font-semibold rounded-lg transition">
                                    Thanh toán
                                </button>
                            </div>
                        </form>

                        {{-- Thanh toán sau --}}
                        <form method="POST" action="{{ route('patient.lichhen.payment.skip', $lichHen) }}"
                            class="border border-red-300 rounded-lg p-4"
                            onsubmit="return confirm('⚠️ CẢNH BÁO: Nếu bỏ qua thanh toán, lịch hẹn của bạn sẽ BỊ HỦY ngay lập tức!\n\nBạn có chắc muốn hủy lịch hẹn không?');">
                            @csrf
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-4">
                                    <i class="bi bi-x-circle text-4xl text-red-600"></i>
                                    <div>
                                        <h4 class="font-semibold text-red-600">Hủy lịch hẹn</h4>
                                        <p class="text-sm text-red-600">⚠️ Lịch hẹn sẽ bị hủy nếu không thanh toán</p>
                                    </div>
                                </div>
                                <button type="submit"
                                    class="px-6 py-2 bg-red-500 hover:bg-red-600 text-white font-semibold rounded-lg transition">
                                    Hủy lịch
                                </button>
                            </div>
                        </form>
                    </div>

                    {{-- THÊM: Thông báo timeout --}}
                    <div class="mt-4 p-4 bg-yellow-50 border border-yellow-300 rounded-lg">
                        <p class="text-sm text-yellow-800">
                            <i class="bi bi-clock-history me-1"></i>
                            <strong>Lưu ý:</strong> Lịch hẹn sẽ tự động bị hủy sau <strong>15 phút</strong> nếu chưa hoàn tất thanh toán.
                        </p>
                    </div>

                    {{-- Actions --}}
                    <div class="mt-6 pt-4 border-t flex gap-3">
                        <a href="{{ route('lichhen.my') }}"
                            class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg transition">
                            <i class="bi bi-arrow-left me-1"></i> Quay lại lịch hẹn
                        </a>
                    </div>

            </div>
        </div>
    </div>
</div>

@if(!$isPatient ?? false)
</x-app-layout>
@endif
@endsection
