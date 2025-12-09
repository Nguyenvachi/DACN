@extends('layouts.doctor')

@section('title', 'Lịch hẹn đã xác nhận')

@section('content')
<div class="container-fluid py-4">
    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1 fw-bold" style="color: #1f2937;">
                <i class="fas fa-check-circle me-2" style="color: #10b981;"></i>
                Lịch hẹn đã xác nhận
            </h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('doctor.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Lịch đã xác nhận</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ route('doctor.lichhen.pending') }}" class="btn btn-outline-warning">
                <i class="fas fa-clock me-2"></i>Lịch chờ xác nhận
            </a>
        </div>
    </div>

    {{-- Filters --}}
    <div class="card vc-card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('doctor.lichhen.confirmed') }}" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Từ ngày</label>
                    <input type="date" name="from_date" class="form-control" value="{{ request('from_date') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Đến ngày</label>
                    <input type="date" name="to_date" class="form-control" value="{{ request('to_date') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Tìm kiếm</label>
                    <input type="text" name="search" class="form-control" placeholder="Tên bệnh nhân..." value="{{ request('search') }}">
                </div>
                <div class="col-md-3 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search me-2"></i>Lọc
                    </button>
                    <a href="{{ route('doctor.lichhen.confirmed') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-redo"></i>
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- Statistics Cards --}}
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="vc-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted mb-1 small">Tổng lịch đã xác nhận</p>
                            <h3 class="fw-bold mb-0" style="color: #10b981;">{{ $appointments->total() }}</h3>
                        </div>
                        <div class="rounded p-3" style="background: linear-gradient(135deg, #10b981, #059669);">
                            <i class="fas fa-check-circle fa-2x text-white"></i>
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
                            <p class="text-muted mb-1 small">Hôm nay</p>
                            <h3 class="fw-bold mb-0" style="color: #3b82f6;">
                                {{ $appointments->where('ngay_hen', today())->count() }}
                            </h3>
                        </div>
                        <div class="bg-info bg-opacity-10 rounded p-3">
                            <i class="fas fa-calendar-day fa-2x text-info"></i>
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
                            <p class="text-muted mb-1 small">Tuần này</p>
                            <h3 class="fw-bold mb-0" style="color: #8b5cf6;">
                                {{ $appointments->whereBetween('ngay_hen', [now()->startOfWeek(), now()->endOfWeek()])->count() }}
                            </h3>
                        </div>
                        <div class="rounded p-3" style="background: linear-gradient(135deg, #8b5cf6, #7c3aed);">
                            <i class="fas fa-calendar-week fa-2x text-white"></i>
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
                            <p class="text-muted mb-1 small">Đã check-in</p>
                            <h3 class="fw-bold mb-0" style="color: #f59e0b;">
                                {{ $appointments->whereIn('trang_thai', ['Đã check-in', 'Đang khám'])->count() }}
                            </h3>
                        </div>
                        <div class="bg-warning bg-opacity-10 rounded p-3">
                            <i class="fas fa-user-check fa-2x text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Danh sách lịch hẹn --}}
    <div class="card vc-card">
        <div class="card-body">
            @if($appointments->isEmpty())
                <div class="text-center py-5">
                    <i class="fas fa-calendar-check fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Chưa có lịch hẹn nào đã xác nhận</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Mã LH</th>
                                <th>Ngày & Giờ</th>
                                <th>Bệnh nhân</th>
                                <th>Dịch vụ</th>
                                <th>Trạng thái</th>
                                <th>Thanh toán</th>
                                <th class="text-center">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($appointments as $lh)
                            <tr>
                                <td>
                                    <strong class="text-primary">#{{ $lh->id }}</strong>
                                </td>
                                <td>
                                    <div>
                                        <i class="fas fa-calendar text-success me-1"></i>
                                        {{ \Carbon\Carbon::parse($lh->ngay_hen)->format('d/m/Y') }}
                                    </div>
                                    <small class="text-muted">
                                        <i class="fas fa-clock me-1"></i>
                                        {{ \Carbon\Carbon::parse($lh->thoi_gian_hen)->format('H:i') }}
                                    </small>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-circle me-2" style="width: 34px; height: 34px; border-radius: 50%; background: linear-gradient(135deg, #10b981, #059669); color: white; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 14px;">
                                            {{ strtoupper(substr($lh->user->name ?? 'N', 0, 1)) }}
                                        </div>
                                        <div>
                                            <strong>{{ $lh->user->name ?? 'N/A' }}</strong>
                                            <br>
                                            <small class="text-muted">{{ $lh->user->so_dien_thoai ?? '' }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border">
                                        {{ $lh->dichVu->ten_dich_vu ?? 'N/A' }}
                                    </span>
                                </td>
                                <td>
                                    @if($lh->trang_thai === 'Đã xác nhận')
                                        <span class="badge bg-success">
                                            <i class="fas fa-check-circle me-1"></i>{{ $lh->trang_thai }}
                                        </span>
                                    @elseif($lh->trang_thai === 'Đã check-in')
                                        <span class="badge bg-info">
                                            <i class="fas fa-user-check me-1"></i>{{ $lh->trang_thai }}
                                        </span>
                                    @elseif($lh->trang_thai === 'Đang khám')
                                        <span class="badge bg-primary">
                                            <i class="fas fa-stethoscope me-1"></i>{{ $lh->trang_thai }}
                                        </span>
                                    @else
                                        <span class="badge bg-secondary">{{ $lh->trang_thai }}</span>
                                    @endif
                                </td>
                                <td>
                                    @if($lh->payment_status === 'paid')
                                        <span class="badge bg-success"><i class="fas fa-check"></i> Đã thanh toán</span>
                                    @else
                                        <span class="badge bg-warning"><i class="fas fa-clock"></i> Chưa thanh toán</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('doctor.lichhen.show', $lh->id) }}"
                                           class="btn btn-outline-info"
                                           title="Xem chi tiết">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if($lh->trang_thai === 'Đã xác nhận')
                                        <a href="{{ route('doctor.benhan.create', ['lich_hen_id' => $lh->id, 'user_id' => $lh->user_id]) }}"
                                           class="btn btn-outline-primary"
                                           title="Tạo bệnh án">
                                            <i class="fas fa-file-medical"></i>
                                        </a>
                                        @endif
                                        @if($lh->conversation)
                                        <a href="{{ route('doctor.chat.show', $lh->conversation->id) }}"
                                           class="btn btn-outline-success"
                                           title="Chat">
                                            <i class="fas fa-comments"></i>
                                        </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="d-flex justify-content-end mt-3">
                    {{ $appointments->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .table th {
        font-weight: 600;
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #6b7280;
    }
    .avatar-circle {
        font-size: 14px;
    }
</style>
@endpush
