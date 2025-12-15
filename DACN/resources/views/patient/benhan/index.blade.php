@extends('layouts.patient-modern')

@section('title', 'Hồ Sơ Bệnh Án')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0"><i class="fas fa-file-medical me-2"></i>Hồ Sơ Bệnh Án</h4>
    </div>

    @if($benhAns->count() > 0)
        <div class="row g-4">
            @foreach($benhAns as $benhAn)
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm hover-lift">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div>
                                    <h5 class="card-title mb-1">
                                        <i class="fas fa-calendar-alt text-primary me-2"></i>
                                        {{ \Carbon\Carbon::parse($benhAn->ngay_kham)->format('d/m/Y') }}
                                    </h5>
                                    <p class="text-muted small mb-0">
                                        Mã HS: HS-{{ str_pad($benhAn->id, 4, '0', STR_PAD_LEFT) }}
                                    </p>
                                </div>
                                <span class="badge bg-primary">{{ $benhAn->dichVu->ten ?? 'N/A' }}</span>
                            </div>

                            <div class="mb-3">
                                <p class="mb-2">
                                    <i class="fas fa-user-md text-success me-2"></i>
                                    <strong>Bác sĩ:</strong> {{ $benhAn->bacSi->user->name ?? 'N/A' }}
                                </p>
                                @if($benhAn->chan_doan)
                                    <p class="mb-2">
                                        <i class="fas fa-stethoscope text-info me-2"></i>
                                        <strong>Chẩn đoán:</strong> {{ Str::limit($benhAn->chan_doan, 50) }}
                                    </p>
                                @endif
                            </div>

                            <div class="d-flex gap-2">
                                <a href="{{ route('patient.benhan.show', $benhAn) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye me-1"></i>Xem chi tiết
                                </a>
                                <a href="{{ route('patient.benhan.exportPdf', $benhAn) }}" class="btn btn-sm btn-outline-success">
                                    <i class="fas fa-download me-1"></i>Tải PDF
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-4">
            {{ $benhAns->links() }}
        </div>
    @else
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center py-5">
                <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">Chưa có hồ sơ bệnh án</h5>
                <p class="text-muted">Hồ sơ bệnh án của bạn sẽ được lưu tại đây sau mỗi lần khám</p>
                <a href="{{ route('public.bacsi.index') }}" class="btn btn-primary mt-3">
                    <i class="fas fa-calendar-plus me-2"></i>Đặt lịch khám
                </a>
            </div>
        </div>
    @endif
</div>

@push('styles')
<style>
.hover-lift {
    transition: transform 0.3s, box-shadow 0.3s;
}
.hover-lift:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.15) !important;
}
</style>
@endpush
@endsection
