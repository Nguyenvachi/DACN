@extends('layouts.patient-modern')

@section('title', 'Kết quả xét nghiệm')
@section('page-title', 'Kết quả xét nghiệm')
@section('page-subtitle', 'Xem và tải về kết quả xét nghiệm của bạn')

@section('content')
<div class="row g-4">
    {{-- Thống kê --}}
    <div class="col-12">
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <p class="text-muted mb-1">Tổng xét nghiệm</p>
                                <h3 class="mb-0">{{ $xetNghiems->total() }}</h3>
                            </div>
                            <div class="bg-primary bg-opacity-10 rounded-circle p-3">
                                <i class="fas fa-flask text-primary fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <p class="text-muted mb-1">Tháng này</p>
                                <h3 class="mb-0">{{ $xetNghiems->where('created_at', '>=', now()->startOfMonth())->count() }}</h3>
                            </div>
                            <div class="bg-success bg-opacity-10 rounded-circle p-3">
                                <i class="fas fa-calendar-check text-success fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <p class="text-muted mb-1">Chờ kết quả</p>
                                <h3 class="mb-0">{{ $xetNghiems->whereNull('ket_qua')->count() }}</h3>
                            </div>
                            <div class="bg-warning bg-opacity-10 rounded-circle p-3">
                                <i class="fas fa-clock text-warning fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Danh sách xét nghiệm --}}
    <div class="col-12">
        @forelse($xetNghiems as $xetNghiem)
            <div class="card mb-3">
                <div class="card-header bg-white">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <div>
                            <h6 class="mb-1">
                                <i class="fas fa-microscope me-2 text-primary"></i>
                                {{ $xetNghiem->loai ?? 'Xét nghiệm' }}
                            </h6>
                            <p class="text-muted mb-0">
                                <small>
                                    <i class="fas fa-calendar me-1"></i>{{ $xetNghiem->created_at?->format('d/m/Y H:i') }}
                                </small>
                            </p>
                        </div>
                        <div>
                            @if($xetNghiem->ket_qua)
                                @if($xetNghiem->trang_thai === 'binh_thuong')
                                    <span class="badge badge-success">Bình thường</span>
                                @elseif($xetNghiem->trang_thai === 'bat_thuong')
                                    <span class="badge badge-danger">Bất thường</span>
                                @else
                                    <span class="badge badge-info">Đã có kết quả</span>
                                @endif
                            @else
                                <span class="badge badge-warning">Chờ kết quả</span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            @if($xetNghiem->benhAn)
                                <div class="mb-3">
                                    <p class="mb-1"><strong><i class="fas fa-user-md me-2"></i>Bác sĩ chỉ định:</strong> {{ $xetNghiem->bacSiChiDinh->ho_ten ?? ($xetNghiem->benhAn->bacSi->ho_ten ?? 'N/A') }}</p>
                                    <p class="mb-1"><strong><i class="fas fa-file-medical me-2"></i>Bệnh án:</strong> #{{ $xetNghiem->benh_an_id }}</p>
                                    <p class="mb-0"><strong><i class="fas fa-stethoscope me-2"></i>Chẩn đoán:</strong> {{ $xetNghiem->benhAn->chan_doan ?? 'N/A' }}</p>
                                </div>
                            @endif

                            @if($xetNghiem->mo_ta)
                                <div class="alert alert-info mb-0">
                                    <h6 class="alert-heading"><i class="fas fa-info-circle me-2"></i>Mô tả:</h6>
                                    <p class="mb-0">{{ $xetNghiem->mo_ta }}</p>
                                </div>
                            @endif

                            @if($xetNghiem->ket_qua)
                                <div class="alert alert-light mt-3 mb-0">
                                    <h6 class="alert-heading"><i class="fas fa-clipboard-check me-2"></i>Kết quả:</h6>
                                    <p class="mb-0">{{ $xetNghiem->ket_qua }}</p>
                                </div>
                            @endif

                            @if($xetNghiem->nhan_xet)
                                <div class="alert alert-warning mt-3 mb-0">
                                    <h6 class="alert-heading"><i class="fas fa-comment-medical me-2"></i>Nhận xét của bác sĩ:</h6>
                                    <p class="mb-0">{{ $xetNghiem->nhan_xet }}</p>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="card-title"><i class="fas fa-info-circle me-2"></i>Thông tin xét nghiệm</h6>
                                    <hr>
                                    <p class="mb-2"><strong>Mã XN:</strong> #{{ $xetNghiem->id }}</p>
                                    <p class="mb-2"><strong>Ngày XN:</strong> {{ $xetNghiem->created_at?->format('d/m/Y') }}</p>
                                    @if($xetNghiem->file_ket_qua)
                                        <p class="mb-2"><strong>File đính kèm:</strong></p>
                                        <a href="{{ route('patient.benhan.xetnghiem.download', $xetNghiem) }}" class="btn btn-sm btn-success w-100 mb-2">
                                            <i class="fas fa-download me-1"></i>Tải file kết quả
                                        </a>
                                    @endif
                                    @if($xetNghiem->benhAn)
                                        <a href="{{ route('patient.benhan.show', $xetNghiem->benhAn) }}" class="btn btn-sm btn-outline-primary w-100">
                                            <i class="fas fa-file-medical me-1"></i>Xem bệnh án
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-white">
                    <div class="d-flex justify-content-end gap-2">
                        <button onclick="window.print()" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-print me-1"></i>In kết quả
                        </button>
                        @if($xetNghiem->file_ket_qua)
                            <a href="{{ route('patient.benhan.xetnghiem.download', $xetNghiem) }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-download me-1"></i>Tải PDF
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="fas fa-flask fs-1 text-muted d-block mb-3"></i>
                    <h5 class="text-muted">Chưa có kết quả xét nghiệm</h5>
                    <p class="text-muted">Các kết quả xét nghiệm sẽ được hiển thị tại đây khi có</p>
                </div>
            </div>
        @endforelse

        {{-- Pagination --}}
        @if($xetNghiems->hasPages())
            <div class="mt-4">
                {{ $xetNghiems->links() }}
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
            page-break-inside: avoid;
        }
    }
</style>
@endpush
@endsection
