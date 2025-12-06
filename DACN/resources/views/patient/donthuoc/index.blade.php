@extends('layouts.patient-modern')

@section('title', 'Đơn thuốc của tôi')
@section('page-title', 'Đơn thuốc của tôi')
@section('page-subtitle', 'Xem và quản lý đơn thuốc được kê bởi bác sĩ')

@section('content')
<div class="row g-4">
    <div class="col-12">
        @forelse($donThuocs as $donThuoc)
            <div class="card mb-4">
                <div class="card-header bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-1">Đơn thuốc #{{ $donThuoc->id }}</h5>
                            <p class="text-muted mb-0">
                                <i class="fas fa-calendar me-2"></i>{{ $donThuoc->created_at?->format('d/m/Y') }}
                            </p>
                        </div>
                        <div>
                            @if($donThuoc->trang_thai === 'da_cap_thuoc')
                                <span class="badge badge-success">Đã cấp thuốc</span>
                            @else
                                <span class="badge badge-warning">Chưa cấp thuốc</span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    {{-- Thông tin bệnh án --}}
                    @if($donThuoc->benhAn)
                        <div class="alert alert-info mb-4">
                            <div class="row">
                                <div class="col-md-6">
                                    <p class="mb-1"><strong><i class="fas fa-user-md me-2"></i>Bác sĩ:</strong> {{ $donThuoc->benhAn->bacSi->ten_bac_si ?? 'N/A' }}</p>
                                    <p class="mb-0"><strong><i class="fas fa-stethoscope me-2"></i>Chẩn đoán:</strong> {{ $donThuoc->benhAn->chan_doan ?? 'N/A' }}</p>
                                </div>
                                <div class="col-md-6">
                                    <p class="mb-1"><strong><i class="fas fa-calendar-check me-2"></i>Ngày khám:</strong> {{ $donThuoc->benhAn->ngay_kham?->format('d/m/Y') ?? 'N/A' }}</p>
                                    <p class="mb-0"><strong><i class="fas fa-file-medical me-2"></i>Mã bệnh án:</strong> #{{ $donThuoc->benh_an_id }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Danh sách thuốc --}}
                    <h6 class="mb-3"><i class="fas fa-pills me-2"></i>Danh sách thuốc:</h6>
                    <div class="table-responsive">
                        <table class="table">
                            <thead class="bg-light">
                                <tr>
                                    <th>Tên thuốc</th>
                                    <th>Liều lượng</th>
                                    <th>Số lượng</th>
                                    <th>Cách dùng</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($donThuoc->items as $item)
                                    <tr>
                                        <td>
                                            <strong>{{ $item->ten_thuoc }}</strong>
                                            @if($item->thuoc)
                                                <br><small class="text-muted">{{ $item->thuoc->hoat_chat ?? '' }}</small>
                                            @endif
                                        </td>
                                        <td>{{ $item->lieu_luong }}</td>
                                        <td><span class="badge bg-primary">{{ $item->so_luong }}</span></td>
                                        <td>{{ $item->cach_dung }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Lời dặn --}}
                    @if($donThuoc->loi_dan)
                        <div class="alert alert-warning mt-3">
                            <h6 class="alert-heading"><i class="fas fa-exclamation-triangle me-2"></i>Lời dặn của bác sĩ:</h6>
                            <p class="mb-0">{{ $donThuoc->loi_dan }}</p>
                        </div>
                    @endif
                </div>
                <div class="card-footer bg-white d-flex justify-content-between align-items-center">
                    <div>
                        @if($donThuoc->trang_thai === 'chua_cap_thuoc')
                            <span class="text-muted"><i class="fas fa-info-circle me-2"></i>Vui lòng đến phòng khám để nhận thuốc</span>
                        @else
                            <span class="text-success"><i class="fas fa-check-circle me-2"></i>Đã nhận thuốc ngày {{ $donThuoc->ngay_cap_thuoc?->format('d/m/Y') }}</span>
                        @endif
                    </div>
                    <div>
                        @if($donThuoc->benhAn)
                            <a href="{{ route('patient.benhan.show', $donThuoc->benhAn) }}" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-file-medical me-1"></i>Xem bệnh án
                            </a>
                        @endif
                        <button onclick="window.print()" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-print me-1"></i>In đơn
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="fas fa-prescription fs-1 text-muted d-block mb-3"></i>
                    <h5 class="text-muted">Chưa có đơn thuốc nào</h5>
                    <p class="text-muted">Các đơn thuốc được kê bởi bác sĩ sẽ hiển thị tại đây</p>
                </div>
            </div>
        @endforelse

        {{-- Pagination --}}
        @if($donThuocs->hasPages())
            <div class="mt-4">
                {{ $donThuocs->links() }}
            </div>
        @endif
    </div>
</div>

@push('styles')
<style>
    @media print {
        .sidebar, .top-nav, .card-footer, footer, .btn {
            display: none !important;
        }
        .main-content {
            margin-left: 0 !important;
        }
        .card {
            box-shadow: none !important;
            border: 1px solid #dee2e6 !important;
        }
    }
</style>
@endpush
@endsection
