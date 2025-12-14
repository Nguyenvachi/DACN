@extends('layouts.patient-modern')

@section('title', 'Kết Quả Siêu Âm')

@section('content')
<div class="container-fluid px-4 py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">
                <i class="fas fa-heartbeat text-primary me-2"></i>Kết quả siêu âm
            </h2>
            <p class="text-muted mb-0">Xem các kết quả siêu âm của bạn</p>
        </div>
    </div>

    @if($sieuAms->count() > 0)
        <div class="row g-4">
            @foreach($sieuAms as $sieuAm)
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div>
                                    <h5 class="card-title mb-1">
                                        {{ $sieuAm->loaiSieuAm->ten ?? $sieuAm->loai_sieu_am ?? 'Siêu âm' }}
                                    </h5>
                                    <p class="text-muted small mb-0">
                                        <i class="fas fa-calendar me-1"></i>
                                        {{ \Carbon\Carbon::parse($sieuAm->ngay_thuc_hien)->format('d/m/Y') }}
                                    </p>
                                </div>
                                <span class="badge bg-{{ $sieuAm->trang_thai === 'Đã có kết quả' ? 'success' : 'warning' }}">
                                    {{ $sieuAm->trang_thai }}
                                </span>
                            </div>

                            <div class="mb-3">
                                <p class="mb-2">
                                    <i class="fas fa-user-md text-success me-2"></i>
                                    <strong>Bác sĩ:</strong> {{ $sieuAm->bacSiThucHien->ho_ten ?? 'N/A' }}
                                </p>
                                @if($sieuAm->ket_luan)
                                    <p class="mb-0">
                                        <i class="fas fa-clipboard-check text-info me-2"></i>
                                        <strong>Kết luận:</strong> {{ Str::limit($sieuAm->ket_luan, 80) }}
                                    </p>
                                @endif
                            </div>

                            <a href="{{ route('patient.sieuam.show', $sieuAm) }}" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-eye me-1"></i>Xem chi tiết
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-4">
            {{ $sieuAms->links() }}
        </div>
    @else
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center py-5">
                <i class="fas fa-heartbeat fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">Chưa có kết quả siêu âm</h5>
                <p class="text-muted">Kết quả siêu âm của bạn sẽ được lưu tại đây sau khi thực hiện</p>
            </div>
        </div>
    @endif
</div>
@endsection
