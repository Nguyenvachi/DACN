@extends('layouts.doctor')

@section('title', 'Danh sách siêu âm')

@section('content')
<div class="container-fluid py-4">
    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1 fw-bold" style="color: #1f2937;">
                <i class="fas fa-ultrasound me-2" style="color: #10b981;"></i>
                Danh sách siêu âm
            </h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('doctor.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Siêu âm</li>
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
                            <p class="text-muted mb-1 small">Tổng siêu âm</p>
                            <h3 class="fw-bold mb-0" style="color: #10b981;">{{ $stats['total'] }}</h3>
                        </div>
                        <div class="rounded p-3" style="background: linear-gradient(135deg, #10b981, #059669);">
                            <i class="fas fa-ultrasound fa-2x text-white"></i>
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
                            <p class="text-muted mb-1 small">Chờ thực hiện</p>
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

    {{-- Filter & Search --}}
    <div class="card vc-card mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label small text-muted">Tìm kiếm bệnh nhân</label>
                    <input type="text" name="search" class="form-control"
                           placeholder="Tên, email..." value="{{ request('search') }}">
                </div>

                <div class="col-md-3">
                    <label class="form-label small text-muted">Trạng thái</label>
                    <select name="trang_thai" class="form-select">
                        <option value="">Tất cả</option>
                        <option value="pending" {{ request('trang_thai') === 'pending' ? 'selected' : '' }}>Chờ thực hiện</option>
                        <option value="processing" {{ request('trang_thai') === 'processing' ? 'selected' : '' }}>Đang xử lý</option>
                        <option value="completed" {{ request('trang_thai') === 'completed' ? 'selected' : '' }}>Đã có kết quả</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label small text-muted">Loại siêu âm</label>
                    <select name="loai_sieu_am_id" class="form-select">
                        <option value="">Tất cả</option>
                        @foreach($loaiSieuAms as $loai)
                            <option value="{{ $loai->id }}" {{ request('loai_sieu_am_id') == $loai->id ? 'selected' : '' }}>
                                {{ $loai->ten }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search me-1"></i> Tìm kiếm
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Table --}}
    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="px-4">Mã SA</th>
                            <th>Bệnh nhân</th>
                            <th>Loại siêu âm</th>
                            <th>Trạng thái</th>
                            <th>Ngày chỉ định</th>
                            <th>Giá</th>
                            <th class="text-center">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sieuAms as $sa)
                            <tr>
                                <td class="px-4">
                                    <span class="badge bg-primary">SA-{{ $sa->id }}</span>
                                </td>
                                <td>
                                    <div>
                                        <strong>{{ $sa->benhAn->user->name ?? 'N/A' }}</strong>
                                        <div class="small text-muted">BA-{{ $sa->benh_an_id }}</div>
                                    </div>
                                </td>
                                <td>{{ $sa->loai }}</td>
                                <td>
                                    <span class="badge {{ $sa->trang_thai_badge_class }}">
                                        {{ $sa->trang_thai_text }}
                                    </span>
                                </td>
                                <td>
                                    <small>{{ $sa->created_at->format('d/m/Y H:i') }}</small>
                                </td>
                                <td>
                                    <span class="text-primary fw-semibold">{{ number_format($sa->gia, 0, ',', '.') }}đ</span>
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('doctor.sieuam.show', $sa->id) }}"
                                       class="btn btn-sm btn-outline-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">
                                    <i class="fas fa-inbox fa-3x mb-3 d-block opacity-25"></i>
                                    Chưa có siêu âm nào
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($sieuAms->hasPages())
            <div class="card-footer bg-white">
                {{ $sieuAms->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
