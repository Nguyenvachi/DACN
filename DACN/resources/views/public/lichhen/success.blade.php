@php
    $isPatient = auth()->check() && auth()->user()->isPatient();
@endphp

@if($isPatient)
    @extends('layouts.patient-modern')

    @section('title', 'Đặt lịch thành công')
    @section('page-title', 'Đặt Lịch Thành Công')
    @section('page-subtitle', 'Lịch hẹn của bạn đã được tạo')

    @section('content')
@else
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Đặt Lịch Thành Công') }}
        </h2>
    </x-slot>
@endif

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                        <p class="font-bold">✓ Đặt lịch hẹn thành công!</p>
                        <p class="text-sm">Chúng tôi đã ghi nhận lịch hẹn của bạn. Vui lòng thanh toán trong vòng 15 phút, nếu không lịch hẹn sẽ tự động hủy.</p>
                    </div>

                    <div class="flex gap-4">
                        <a href="{{ route('lichhen.my') }}"
                            class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-black uppercase tracking-widest hover:bg-blue-700">
                            Xem lịch hẹn của tôi
                        </a>

                        <a href="{{ route('public.bacsi.index') }}"
                            class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-black uppercase tracking-widest hover:bg-gray-700">
                            Đặt thêm lịch khác
                        </a>

                        <a href="{{ route('dashboard') }}"
                            class="inline-flex items-center px-4 py-2 bg-gray-200 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300">
                            Về trang chủ
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
