@extends('layouts.patient-modern')

@section('title', 'Kết Quả Thủ Thuật')

@section('content')
<div class="container-fluid px-4 py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">
                <i class="fas fa-procedures text-primary me-2"></i>Kết quả thủ thuật
            </h2>
            <p class="text-muted mb-0">Xem các kết quả thủ thuật của bạn</p>
        </div>
    </div>

    @if($thuThuats->count() > 0)
        <div class="row g-4">
            @foreach($thuThuats as $thuThuat)
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div>
                                    <h5 class="card-title mb-1">
                                        {{ $thuThuat->loaiThuThuat->ten ?? $thuThuat->ten_thu_thuat ?? 'Thủ thuật' }}
                                    </h5>
                                    <p class="text-muted small mb-0">
                                        <i class="fas fa-calendar me-1"></i>
                                        {{ \Carbon\Carbon::parse($thuThuat->ngay_thuc_hien)->format('d/m/Y') }}
                                    </p>
                                </div>
                                <span class="badge bg-{{ $thuThuat->trang_thai === 'Đã hoàn thành' ? 'success' : 'warning' }}">
                                    {{ $thuThuat->trang_thai }}
                                </span>
                            </div>

                            <div class="mb-3">
                                <p class="mb-2">
                                    <i class="fas fa-user-md text-success me-2"></i>
                                    <strong>Bác sĩ:</strong> {{ $thuThuat->bacSiThucHien->ho_ten ?? 'N/A' }}
                                </p>
                                @if($thuThuat->ket_luan)
                                    <p class="mb-0">
                                        <i class="fas fa-clipboard-check text-info me-2"></i>
                                        <strong>Kết luận:</strong> {{ Str::limit($thuThuat->ket_luan, 80) }}
                                    </p>
                                @endif
                            </div>

                            <a href="{{ route('patient.thuthuat.show', $thuThuat) }}" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-eye me-1"></i>Xem chi tiết
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-4">
            {{ $thuThuats->links() }}
        </div>
    @else
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center py-5">
                <i class="fas fa-procedures fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">Chưa có kết quả thủ thuật</h5>
                <p class="text-muted">Kết quả thủ thuật của bạn sẽ được lưu tại đây sau khi thực hiện</p>
            </div>
        </div>
    @endif
</div>
@endsection
