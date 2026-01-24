{{-- Parent file: resources/views/layouts/patient-modern.blade.php --}}
@extends('layouts.patient-modern')

@section('title', 'Kết quả X-Quang')
@section('page-title', 'Hồ sơ X-Quang')
@section('page-subtitle', 'Theo dõi lịch sử và tải về kết quả X-Quang của bạn')

@section('content')
<div class="row g-4">
    {{-- Stats --}}
    <div class="col-12">
        <div class="row g-4">
            <div class="col-md-3">
                <div class="card shadow-sm border-0 h-100 bg-primary bg-opacity-10">
                    <div class="card-body d-flex align-items-center justify-content-between p-4">
                        <div>
                            <p class="text-primary fw-bold mb-1 text-uppercase small">Tổng số</p>
                            <h2 class="mb-0 fw-bold text-dark">{{ $stats['total'] }}</h2>
                        </div>
                        <div class="bg-white rounded-circle p-3 shadow-sm text-primary">
                            <i class="fas fa-x-ray fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm border-0 h-100 bg-secondary bg-opacity-10">
                    <div class="card-body d-flex align-items-center justify-content-between p-4">
                        <div>
                            <p class="text-secondary fw-bold mb-1 text-uppercase small">Chờ xử lý</p>
                            <h2 class="mb-0 fw-bold text-dark">{{ $stats['pending'] }}</h2>
                        </div>
                        <div class="bg-white rounded-circle p-3 shadow-sm text-secondary">
                            <i class="fas fa-clock fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm border-0 h-100 bg-warning bg-opacity-10">
                    <div class="card-body d-flex align-items-center justify-content-between p-4">
                        <div>
                            <p class="text-warning-dark fw-bold mb-1 text-uppercase small">Đang xử lý</p>
                            <h2 class="mb-0 fw-bold text-dark">{{ $stats['processing'] }}</h2>
                        </div>
                        <div class="bg-white rounded-circle p-3 shadow-sm text-warning">
                            <i class="fas fa-spinner fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm border-0 h-100 bg-success bg-opacity-10">
                    <div class="card-body d-flex align-items-center justify-content-between p-4">
                        <div>
                            <p class="text-success fw-bold mb-1 text-uppercase small">Đã hoàn thành</p>
                            <h2 class="mb-0 fw-bold text-dark">{{ $stats['completed'] }}</h2>
                        </div>
                        <div class="bg-white rounded-circle p-3 shadow-sm text-success">
                            <i class="fas fa-check-circle fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Filters --}}
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <form method="GET" action="{{ route('patient.xquang.index') }}" class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label small fw-semibold">Tìm bác sĩ</label>
                        <input type="text" name="search" class="form-control" placeholder="Tên bác sĩ..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small fw-semibold">Trạng thái</label>
                        <select name="status" class="form-select">
                            <option value="">-- Tất cả --</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Chờ xử lý</option>
                            <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Đang xử lý</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Đã hoàn thành</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small fw-semibold">Loại</label>
                        <select name="loai" class="form-select">
                            <option value="">-- Tất cả --</option>
                            @foreach($loaiXQuangs as $loai)
                                <option value="{{ $loai }}" {{ request('loai') == $loai ? 'selected' : '' }}>{{ $loai }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small fw-semibold">Từ ngày</label>
                        <input type="date" name="from_date" class="form-control" value="{{ request('from_date') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small fw-semibold">Đến ngày</label>
                        <input type="date" name="to_date" class="form-control" value="{{ request('to_date') }}">
                    </div>
                    <div class="col-md-1 d-flex align-items-end gap-2">
                        <button type="submit" class="btn btn-primary flex-fill"><i class="fas fa-filter"></i></button>
                        <a href="{{ route('patient.xquang.index') }}" class="btn btn-outline-secondary"><i class="fas fa-redo"></i></a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- List --}}
    <div class="col-12">
        @forelse($xQuangs as $xq)
            <div class="card shadow-sm mb-4 border-hover-primary transition-all">
                <div class="card-header bg-white py-3 border-bottom">
                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                        <div class="d-flex align-items-center">
                            <div class="bg-light rounded-circle p-2 me-3 text-info">
                                <i class="fas fa-x-ray fa-lg"></i>
                            </div>
                            <div>
                                <h6 class="mb-0 fw-bold">
                                    <a href="{{ route('patient.xquang.show', $xq) }}" class="text-dark text-decoration-none stretched-link">
                                        {{ $xq->loai ?? 'X-Quang' }}
                                    </a>
                                </h6>
                                <small class="text-muted"><i class="far fa-clock me-1"></i>{{ $xq->created_at?->format('d/m/Y H:i') }}</small>
                            </div>
                        </div>

                        <div>
                            @if ($xq->trang_thai === 'completed')
                                <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill border border-success border-opacity-25">
                                    <i class="fas fa-check-circle me-1"></i>Đã có kết quả
                                </span>
                            @elseif($xq->trang_thai === 'processing')
                                <span class="badge bg-warning bg-opacity-10 text-warning px-3 py-2 rounded-pill border border-warning border-opacity-25">
                                    <i class="fas fa-spinner fa-spin me-1"></i>Đang xử lý
                                </span>
                            @else
                                <span class="badge bg-secondary bg-opacity-10 text-secondary px-3 py-2 rounded-pill border border-secondary border-opacity-25">
                                    <i class="fas fa-hourglass-start me-1"></i>Chờ thực hiện
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row g-4">
                        <div class="col-md-7 border-end-md">
                            @if ($xq->benhAn)
                                <div class="mb-3">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="fas fa-user-md text-primary me-2" style="width: 20px;"></i>
                                        <span class="text-muted small me-2">Bác sĩ chỉ định:</span>
                                        <span class="fw-semibold">{{ $xq->benhAn->bacSi->ho_ten ?? ($xq->benhAn->bacSi->user->name ?? 'N/A') }}</span>
                                    </div>
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="fas fa-notes-medical text-danger me-2" style="width: 20px;"></i>
                                        <span class="text-muted small me-2">Chẩn đoán sơ bộ:</span>
                                        <span class="fw-semibold">{{ $xq->benhAn->chan_doan ?? '---' }}</span>
                                    </div>
                                </div>
                            @endif

                            @if ($xq->mo_ta)
                                <div class="bg-light p-3 rounded mb-3">
                                    <h6 class="text-muted small fw-bold text-uppercase mb-2">Mô tả / Yêu cầu:</h6>
                                    <p class="mb-0 text-dark small">{{ $xq->mo_ta }}</p>
                                </div>
                            @endif
                        </div>

                        <div class="col-md-5">
                            @if ($xq->trang_thai === 'completed' && $xq->file_path)
                                <div class="d-flex flex-column h-100 justify-content-between">
                                    <div class="alert alert-light border shadow-sm">
                                        <h6 class="alert-heading fw-bold text-primary mb-2">
                                            <i class="fas fa-file-medical me-2"></i>Kết quả đã sẵn sàng
                                        </h6>
                                        <p class="mb-0 text-muted small">Bạn có thể xem chi tiết hoặc tải file kết quả.</p>
                                    </div>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('patient.xquang.show', $xq) }}" class="btn btn-outline-primary flex-fill">
                                            <i class="fas fa-eye me-1"></i>Xem
                                        </a>
                                        <a href="{{ $xq->getDownloadUrl() }}" class="btn btn-success flex-fill" target="_blank">
                                            <i class="fas fa-download me-1"></i>Tải
                                        </a>
                                    </div>
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <i class="fas fa-hourglass-half fa-2x text-warning mb-2"></i>
                                    <p class="text-muted mb-0">Chưa có kết quả</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-5">
                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                <p class="text-muted">Chưa có kết quả X-Quang nào</p>
            </div>
        @endforelse

        <div class="d-flex justify-content-end">
            {{ $xQuangs->links() }}
        </div>
    </div>
</div>
@endsection
