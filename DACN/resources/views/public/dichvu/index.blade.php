@php
    $isPatient = auth()->check() && auth()->user()->role === 'patient';
    $layout = $isPatient ? 'layouts.patient-modern' : 'layouts.app';
@endphp

@extends($layout)
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Danh Sách Dịch Vụ') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <h1 class="text-2xl font-bold mb-4">Danh sách Dịch vụ của chúng tôi</h1>

                    <div class="overflow-x-auto">
                        <table id="publicDichvuTable" class="min-w-full divide-y divide-gray-200 border">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                        Tên Dịch Vụ
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                        Mô Tả
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                        Giá (VNĐ)
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                        Thời gian (phút)
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($dsDichVu as $dichVu)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap font-medium">
                                        {{ $dichVu->ten_dich_vu }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ $dichVu->mo_ta }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ number_format($dichVu->gia ?? 0, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ $dichVu->thoi_gian_uoc_tinh ?? 30 }}
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                                        Không có dịch vụ nào.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-6">
                        <a href="{{ route('public.bacsi.index') }}"
                            class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-black uppercase tracking-widest hover:bg-blue-700">
                            Đặt lịch khám →
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>

@if($isPatient ?? false)
    @endsection

    @push('scripts')
    {{-- DataTables Script --}}
    <x-datatable-script tableId="publicDichvuTable" />
    @endpush
@else
</x-app-layout>

{{-- DataTables Script --}}
<x-datatable-script tableId="publicDichvuTable" />
@endif
