@extends('layouts.doctor')

@section('title', 'Đơn thuốc DT-' . str_pad($donThuoc->id, 4, '0', STR_PAD_LEFT))

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            {{-- Header --}}
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="mb-0 fw-bold" style="color: #1f2937;">
                    <i class="fas fa-prescription-bottle-alt me-2" style="color: #10b981;"></i>
                    Đơn thuốc DT-{{ str_pad($donThuoc->id, 4, '0', STR_PAD_LEFT) }}
                </h4>
                <div class="d-flex gap-2">
                    <button onclick="window.print()" class="btn btn-outline-primary">
                        <i class="fas fa-print me-2"></i>In đơn
                    </button>
                    <a href="{{ route('doctor.benhan.edit', $donThuoc->benh_an_id) }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Quay lại
                    </a>
                </div>
            </div>

            {{-- Prescription Card --}}
            <div class="card vc-card" id="printableArea">
                <div class="card-body p-4">
                    {{-- Header phòng khám --}}
                    <div class="text-center mb-4 pb-3 border-bottom">
                        <h3 class="fw-bold mb-1" style="color: #10b981;">PHÒNG KHÁM VietCare</h3>
                        <p class="mb-1">Địa chỉ: 123 Đường ABC, Quận XYZ, TP.HCM</p>
                        <p class="mb-1">Hotline: 1900-xxxx | Email: contact@vietcare.vn</p>
                    </div>

                    <h4 class="text-center fw-bold mb-4" style="color: #1f2937;">
                        ĐơN THUỐC
                    </h4>

                    {{-- Thông tin --}}
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <p class="mb-2"><strong>Bệnh nhân:</strong> {{ $donThuoc->benhAn->user->name }}</p>
                            <p class="mb-2"><strong>Năm sinh:</strong>
                                {{ \Carbon\Carbon::parse($donThuoc->benhAn->user->ngay_sinh ?? now())->format('Y') }}
                            </p>
                            <p class="mb-2"><strong>Mã BN:</strong> #{{ $donThuoc->user_id }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-2"><strong>Ngày khám:</strong>
                                {{ \Carbon\Carbon::parse($donThuoc->benhAn->ngay_kham)->format('d/m/Y H:i') }}
                            </p>
                            <p class="mb-2"><strong>Bác sĩ:</strong>
                                BS. {{ $donThuoc->benhAn->bacSi->user->name ?? 'N/A' }}
                            </p>
                            <p class="mb-2"><strong>Chẩn đoán:</strong>
                                {{ $donThuoc->benhAn->chuan_doan ?? 'Chưa có chẩn đoán' }}
                            </p>
                        </div>
                    </div>

                    {{-- Danh sách thuốc --}}
                    <h5 class="fw-bold mb-3" style="color: #10b981;">DANH SÁCH THUỐC:</h5>
                    <div class="table-responsive mb-4">
                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 5%;">STT</th>
                                    <th style="width: 30%;">Tên thuốc</th>
                                    <th style="width: 20%;">Hoạt chất - Hàm lượng</th>
                                    <th style="width: 10%;">Số lượng</th>
                                    <th style="width: 20%;">Liều dùng</th>
                                    <th style="width: 15%;">Cách dùng</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($donThuoc->items as $index => $item)
                                <tr>
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    <td>
                                        <strong>{{ $item->thuoc->ten }}</strong>
                                    </td>
                                    <td>
                                        {{ $item->thuoc->hoat_chat }}<br>
                                        <small class="text-muted">{{ $item->thuoc->ham_luong }}</small>
                                    </td>
                                    <td class="text-center">
                                        <strong>{{ $item->so_luong }}</strong> {{ $item->thuoc->don_vi }}
                                    </td>
                                    <td>{{ $item->lieu_dung }}</td>
                                    <td>{{ $item->cach_dung ?? '-' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Ghi chú --}}
                    @if($donThuoc->ghi_chu)
                    <div class="alert alert-info mb-4">
                        <strong><i class="fas fa-info-circle me-2"></i>Lưu ý:</strong><br>
                        {{ $donThuoc->ghi_chu }}
                    </div>
                    @endif

                    {{-- Lời dặn chung --}}
                    <div class="mb-4 p-3 bg-light rounded">
                        <h6 class="fw-bold mb-2">Lời dặn:</h6>
                        <ul class="mb-0">
                            <li>Uống thuốc đúng liều lượng và thời gian theo chỉ định</li>
                            <li>Không tự ý ngừng thuốc khi chưa hết đợt điều trị</li>
                            <li>Nếu có phản ứng phụ, liên hệ ngay với bác sĩ</li>
                            <li>Bảo quản thuốc nơi khô ráo, tránh ánh nắng trực tiếp</li>
                            <li>Tái khám theo lịch hẹn hoặc khi có triệu chứng bất thường</li>
                        </ul>
                    </div>

                    {{-- Chữ ký --}}
                    <div class="row mt-5 pt-4">
                        <div class="col-6 text-center">
                            <p class="mb-1"><em>Ngày {{ \Carbon\Carbon::parse($donThuoc->created_at)->format('d') }} tháng {{ \Carbon\Carbon::parse($donThuoc->created_at)->format('m') }} năm {{ \Carbon\Carbon::parse($donThuoc->created_at)->format('Y') }}</em></p>
                        </div>
                        <div class="col-6 text-center">
                            <p class="mb-5"><strong>BÁC SĨ ĐIỀU TRỊ</strong></p>
                            <p class="mt-5 pt-3">
                                <strong>BS. {{ $donThuoc->benhAn->bacSi->user->name ?? 'N/A' }}</strong>
                            </p>
                        </div>
                    </div>

                    {{-- Footer --}}
                    <div class="text-center mt-4 pt-3 border-top">
                        <small class="text-muted">
                            Đơn thuốc này chỉ có giá trị trong 30 ngày kể từ ngày kê đơn
                        </small>
                    </div>
                </div>
            </div>

            {{-- Actions không in --}}
            <div class="d-flex gap-2 justify-content-end mt-3 no-print">
                <a href="{{ route('doctor.donthuoc.create', $donThuoc->benh_an_id) }}" class="btn btn-outline-warning">
                    <i class="fas fa-edit me-2"></i>Sửa đơn
                </a>
                <form action="{{ route('doctor.donthuoc.destroy', $donThuoc->id) }}"
                      method="POST"
                      class="d-inline"
                      onsubmit="return confirm('Bạn có chắc muốn xóa đơn thuốc này?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger">
                        <i class="fas fa-trash me-2"></i>Xóa đơn
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
@media print {
    body * {
        visibility: hidden;
    }
    #printableArea, #printableArea * {
        visibility: visible;
    }
    #printableArea {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
    }
    .no-print {
        display: none !important;
    }
    .btn, nav, .breadcrumb {
        display: none !important;
    }
}
</style>
@endpush
@endsection
