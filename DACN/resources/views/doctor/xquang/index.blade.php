{{-- Parent file: resources/views/layouts/doctor.blade.php --}}
@extends('layouts.doctor')

@section('title', 'Danh sách X-Quang')

@section('content')
<div class="container-fluid py-4">
    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1 fw-bold" style="color: #1f2937;">
                <i class="fas fa-x-ray me-2" style="color: #3b82f6;"></i>
                Danh sách X-Quang
            </h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('doctor.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">X-Quang</li>
                </ol>
            </nav>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="vc-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted mb-1 small">Tổng X-Quang</p>
                            <h3 class="fw-bold mb-0" style="color: #3b82f6;">{{ $stats['total'] }}</h3>
                        </div>
                        <div class="bg-primary bg-opacity-10 rounded p-3">
                            <i class="fas fa-x-ray fa-2x text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="vc-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted mb-1 small">Chờ xử lý</p>
                            <h3 class="fw-bold mb-0" style="color: #6b7280;">{{ $stats['pending'] }}</h3>
                        </div>
                        <div class="bg-secondary bg-opacity-10 rounded p-3">
                            <i class="fas fa-clock fa-2x text-secondary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="vc-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted mb-1 small">Đang xử lý</p>
                            <h3 class="fw-bold mb-0" style="color: #f59e0b;">{{ $stats['processing'] }}</h3>
                        </div>
                        <div class="bg-warning bg-opacity-10 rounded p-3">
                            <i class="fas fa-spinner fa-2x text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="vc-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted mb-1 small">Đã hoàn thành</p>
                            <h3 class="fw-bold mb-0" style="color: #10b981;">{{ $stats['completed'] }}</h3>
                        </div>
                        <div class="bg-success bg-opacity-10 rounded p-3">
                            <i class="fas fa-check-circle fa-2x text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Filter Form --}}
    <div class="card vc-card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('doctor.xquang.index') }}" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label small">Tìm kiếm bệnh nhân</label>
                    <input type="text" name="search" class="form-control" placeholder="Tên, SĐT..." value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label small">Trạng thái</label>
                    <select name="status" class="form-select">
                        <option value="">-- Tất cả --</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Chờ xử lý</option>
                        <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Đang xử lý</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Đã hoàn thành</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small">Loại</label>
                    <select name="loai" class="form-select">
                        <option value="">-- Tất cả --</option>
                        @foreach($loaiXQuangs as $loai)
                            <option value="{{ $loai }}" {{ request('loai') == $loai ? 'selected' : '' }}>
                                {{ $loai }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small">Từ ngày</label>
                    <input type="date" name="from_date" class="form-control" value="{{ request('from_date') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label small">Đến ngày</label>
                    <input type="date" name="to_date" class="form-control" value="{{ request('to_date') }}">
                </div>
                <div class="col-md-1 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-primary flex-fill">
                        <i class="fas fa-filter"></i>
                    </button>
                    <a href="{{ route('doctor.xquang.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-redo"></i>
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- Danh sách X-Quang --}}
    <div class="card vc-card">
        <div class="card-body">
            @if($xQuangs->isEmpty())
                <div class="text-center py-5">
                    <i class="fas fa-x-ray fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Chưa có X-Quang nào được chỉ định</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Mã</th>
                                <th>Ngày chỉ định</th>
                                <th>Bệnh nhân</th>
                                <th>Loại</th>
                                <th>Trạng thái</th>
                                <th class="text-center">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($xQuangs as $xq)
                            <tr>
                                <td><strong class="text-primary">#XQ{{ $xq->id }}</strong></td>
                                <td><small>{{ optional($xq->ngay_chi_dinh ?? $xq->created_at)->format('d/m/Y H:i') }}</small></td>
                                <td>
                                    <div>
                                        <strong>{{ $xq->benhAn->user->name ?? 'N/A' }}</strong><br>
                                        <small class="text-muted">BA #{{ $xq->benh_an_id }}</small>
                                    </div>
                                </td>
                                <td>{{ $xq->loai }}</td>
                                <td>
                                    @if($xq->trang_thai === 'pending')
                                        <span class="badge bg-warning"><i class="fas fa-hourglass-half me-1"></i>Chờ thực hiện</span>
                                    @elseif($xq->trang_thai === 'processing')
                                        <span class="badge bg-info"><i class="fas fa-spinner me-1"></i>Đang xử lý</span>
                                    @elseif($xq->trang_thai === 'completed')
                                        <span class="badge bg-success"><i class="fas fa-check-circle me-1"></i>Đã có kết quả</span>
                                    @else
                                        <span class="badge bg-secondary">{{ $xq->trang_thai_text }}</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('doctor.xquang.show', $xq->id) }}" class="btn btn-outline-primary" title="Xem chi tiết">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if($xq->trang_thai === 'completed' && $xq->file_path)
                                        <a href="{{ route('doctor.xquang.download', $xq->id) }}" class="btn btn-outline-success" title="Tải kết quả" target="_blank">
                                            <i class="fas fa-download"></i>
                                        </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-end mt-3">
                    {{ $xQuangs->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
