@extends('layouts.staff')

@section('title', 'Nội soi chờ thực hiện')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1 fw-bold text-dark">
                <i class="fas fa-stethoscope me-2 text-primary"></i>
                Nội soi chờ thực hiện
            </h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('staff.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Nội soi chờ</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ route('staff.noisoi.completed') }}" class="btn btn-outline-success">
                <i class="fas fa-check-circle me-2"></i>Đã hoàn thành
            </a>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm bg-warning bg-opacity-10">
                <div class="card-body">
                    <p class="text-warning fw-bold mb-1 small">CHỜ THỰC HIỆN</p>
                    <h3 class="mb-0 fw-bold">{{ number_format($stats['pending']) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm bg-info bg-opacity-10">
                <div class="card-body">
                    <p class="text-info fw-bold mb-1 small">ĐANG XỬ LÝ</p>
                    <h3 class="mb-0 fw-bold">{{ number_format($stats['processing']) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm bg-success bg-opacity-10">
                <div class="card-body">
                    <p class="text-success fw-bold mb-1 small">HOÀN THÀNH HÔM NAY</p>
                    <h3 class="mb-0 fw-bold">{{ number_format($stats['completed_today']) }}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th width="80">Mã</th>
                            <th>Ngày chỉ định</th>
                            <th>Bệnh nhân</th>
                            <th>Bác sĩ</th>
                            <th>Loại</th>
                            <th width="120">Trạng thái</th>
                            <th width="150" class="text-center">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($noiSois as $ns)
                            <tr>
                                <td><strong class="text-primary">#{{ $ns->id }}</strong></td>
                                <td><small>{{ $ns->created_at->format('d/m/Y H:i') }}</small></td>
                                <td>
                                    <div class="fw-medium">{{ $ns->benhAn->user->name ?? 'N/A' }}</div>
                                    <small class="text-muted">{{ $ns->benhAn->user->so_dien_thoai ?? '' }}</small>
                                </td>
                                <td><small>{{ $ns->bacSiChiDinh->user->name ?? 'N/A' }}</small></td>
                                <td><span class="badge bg-light text-dark">{{ $ns->loaiNoiSoi?->ten ?? $ns->loai }}</span></td>
                                <td>
                                    @if($ns->trang_thai === 'pending')
                                        <span class="badge bg-warning"><i class="fas fa-hourglass-start me-1"></i>Chờ</span>
                                    @else
                                        <span class="badge bg-info"><i class="fas fa-spinner me-1"></i>Đang xử lý</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($ns->trang_thai === 'pending')
                                        <form action="{{ route('staff.noisoi.processing', $ns) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-info" title="Bắt đầu xử lý">
                                                <i class="fas fa-play"></i>
                                            </button>
                                        </form>
                                    @endif
                                    <a href="{{ route('staff.noisoi.upload.form', $ns) }}" class="btn btn-sm btn-primary" title="Upload kết quả">
                                        <i class="fas fa-upload"></i>
                                    </a>
                                    <a href="{{ route('staff.noisoi.show', $ns) }}" class="btn btn-sm btn-outline-secondary" title="Xem chi tiết">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">Không có Nội soi chờ thực hiện</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $noiSois->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
