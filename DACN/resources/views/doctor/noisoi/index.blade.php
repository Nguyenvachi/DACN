@extends('layouts.doctor')

@section('title', 'Nội soi')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1 fw-bold text-dark">
                <i class="fas fa-stethoscope me-2 text-primary"></i>
                Nội soi
            </h4>
            <div class="text-muted">Danh sách nội soi do bạn chỉ định</div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="text-muted small">TỔNG</div>
                    <div class="h4 mb-0 fw-bold">{{ number_format($stats['total'] ?? 0) }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm bg-warning bg-opacity-10">
                <div class="card-body">
                    <div class="text-warning small fw-bold">CHỜ</div>
                    <div class="h4 mb-0 fw-bold">{{ number_format($stats['pending'] ?? 0) }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm bg-info bg-opacity-10">
                <div class="card-body">
                    <div class="text-info small fw-bold">ĐANG XỬ LÝ</div>
                    <div class="h4 mb-0 fw-bold">{{ number_format($stats['processing'] ?? 0) }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm bg-success bg-opacity-10">
                <div class="card-body">
                    <div class="text-success small fw-bold">ĐÃ CÓ KQ</div>
                    <div class="h4 mb-0 fw-bold">{{ number_format($stats['completed'] ?? 0) }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form class="row g-2 mb-3" method="GET">
                <div class="col-md-3">
                    <input type="text" name="search" class="form-control" placeholder="Tìm theo bệnh nhân" value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-select">
                        <option value="">-- Trạng thái --</option>
                        <option value="pending" @selected(request('status')==='pending')>Chờ</option>
                        <option value="processing" @selected(request('status')==='processing')>Đang xử lý</option>
                        <option value="completed" @selected(request('status')==='completed')>Đã có KQ</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="loai" class="form-select">
                        <option value="">-- Loại --</option>
                        @foreach($loaiNoiSois as $loai)
                            <option value="{{ $loai }}" @selected(request('loai')===$loai)>{{ $loai }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="date" name="from_date" class="form-control" value="{{ request('from_date') }}">
                </div>
                <div class="col-md-2">
                    <input type="date" name="to_date" class="form-control" value="{{ request('to_date') }}">
                </div>
                <div class="col-md-1 d-grid">
                    <button class="btn btn-primary">Lọc</button>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th width="90">Mã</th>
                            <th>Ngày chỉ định</th>
                            <th>Bệnh nhân</th>
                            <th>Loại</th>
                            <th width="140">Trạng thái</th>
                            <th width="140" class="text-end">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($noiSois as $ns)
                            <tr>
                                <td><strong class="text-primary">#{{ $ns->id }}</strong></td>
                                <td><small>{{ $ns->ngay_chi_dinh?->format('d/m/Y H:i') ?? $ns->created_at?->format('d/m/Y H:i') }}</small></td>
                                <td>
                                    <div class="fw-medium">{{ $ns->benhAn->user->name ?? ($ns->benhAn->user->ho_ten ?? 'N/A') }}</div>
                                    <small class="text-muted">{{ $ns->benhAn->user->so_dien_thoai ?? '' }}</small>
                                </td>
                                <td><span class="badge bg-light text-dark">{{ $ns->loaiNoiSoi?->ten ?? $ns->loai }}</span></td>
                                <td><span class="badge bg-secondary">{{ $ns->trang_thai_text }}</span></td>
                                <td class="text-end">
                                    <a href="{{ route('doctor.noisoi.show', $ns) }}" class="btn btn-sm btn-outline-secondary">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @if($ns->hasResult())
                                        <a href="{{ $ns->getDownloadUrl() }}" class="btn btn-sm btn-success" target="_blank">
                                            <i class="fas fa-download"></i>
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">Chưa có nội soi</td>
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
