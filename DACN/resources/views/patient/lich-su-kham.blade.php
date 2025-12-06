@extends('layouts.patient-modern')

@section('title', 'Lịch sử khám bệnh')
@section('page-title', 'Lịch sử khám bệnh')
@section('page-subtitle', 'Timeline lịch sử khám bệnh và điều trị của bạn')

@section('content')
<div class="row g-4">
    {{-- Thống kê tổng quan --}}
    <div class="col-12">
        <div class="row g-4">
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body text-center">
                        <i class="fas fa-calendar-check text-primary fs-1 mb-3"></i>
                        <h3 class="mb-1">{{ $statistics['total_appointments'] }}</h3>
                        <p class="text-muted mb-0">Tổng lịch hẹn</p>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card">
                    <div class="card-body text-center">
                        <i class="fas fa-file-medical text-success fs-1 mb-3"></i>
                        <h3 class="mb-1">{{ $statistics['total_medical_records'] }}</h3>
                        <p class="text-muted mb-0">Bệnh án</p>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card">
                    <div class="card-body text-center">
                        <i class="fas fa-prescription text-info fs-1 mb-3"></i>
                        <h3 class="mb-1">{{ $statistics['total_prescriptions'] }}</h3>
                        <p class="text-muted mb-0">Đơn thuốc</p>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card">
                    <div class="card-body text-center">
                        <i class="fas fa-flask text-warning fs-1 mb-3"></i>
                        <h3 class="mb-1">{{ $statistics['total_tests'] }}</h3>
                        <p class="text-muted mb-0">Xét nghiệm</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Timeline --}}
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="fas fa-history me-2"></i>Lịch sử khám bệnh</h5>
            </div>
            <div class="card-body">
                @forelse($timeline as $year => $months)
                    <h5 class="text-primary mb-4">
                        <i class="fas fa-calendar me-2"></i>Năm {{ $year }}
                    </h5>

                    @foreach($months as $month => $items)
                        <h6 class="text-muted mb-3 ms-4">Tháng {{ $month }}</h6>

                        <div class="timeline ms-5 mb-5">
                            @foreach($items as $item)
                                <div class="timeline-item mb-4 position-relative">
                                    <div class="timeline-marker position-absolute" style="left: -30px; top: 5px;">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center"
                                             style="width: 40px; height: 40px; background: {{ $item['color'] }};">
                                            <i class="fas fa-{{ $item['icon'] }} text-white"></i>
                                        </div>
                                    </div>

                                    <div class="card border-start border-4" style="border-color: {{ $item['color'] }} !important;">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-start mb-2">
                                                <div>
                                                    <h6 class="mb-1">{{ $item['title'] }}</h6>
                                                    <p class="text-muted mb-0">
                                                        <i class="fas fa-clock me-1"></i>{{ $item['date'] }}
                                                    </p>
                                                </div>
                                                <span class="badge" style="background: {{ $item['color'] }};">{{ $item['type'] }}</span>
                                            </div>

                                            @if(isset($item['doctor']))
                                                <p class="mb-1">
                                                    <i class="fas fa-user-md me-2 text-primary"></i>
                                                    <strong>Bác sĩ:</strong> {{ $item['doctor'] }}
                                                </p>
                                            @endif

                                            @if(isset($item['diagnosis']))
                                                <p class="mb-1">
                                                    <i class="fas fa-stethoscope me-2 text-success"></i>
                                                    <strong>Chẩn đoán:</strong> {{ $item['diagnosis'] }}
                                                </p>
                                            @endif

                                            @if(isset($item['description']))
                                                <p class="mb-0 text-muted">{{ $item['description'] }}</p>
                                            @endif

                                            @if(isset($item['action_url']))
                                                <div class="mt-3">
                                                    <a href="{{ $item['action_url'] }}" class="btn btn-sm btn-outline-primary">
                                                        <i class="fas fa-eye me-1"></i>Xem chi tiết
                                                    </a>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                @empty
                    <div class="text-center py-5">
                        <i class="fas fa-history fs-1 text-muted d-block mb-3"></i>
                        <h5 class="text-muted">Chưa có lịch sử khám bệnh</h5>
                        <p class="text-muted">Lịch sử khám bệnh và điều trị của bạn sẽ được hiển thị tại đây</p>
                        <a href="{{ route('public.bacsi.index') }}" class="btn btn-primary mt-3">
                            <i class="fas fa-calendar-plus me-2"></i>Đặt lịch khám ngay
                        </a>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .timeline {
        border-left: 3px solid #e5e7eb;
        padding-left: 40px;
    }

    .timeline-item {
        padding-left: 10px;
    }

    .timeline-marker {
        z-index: 1;
    }
</style>
@endpush
@endsection
