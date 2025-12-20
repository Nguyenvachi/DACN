@extends('layouts.staff')

@section('title', 'Nội soi đã hoàn thành')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1 fw-bold text-dark">
                <i class="fas fa-check-circle me-2 text-success"></i>
                Nội soi đã hoàn thành
            </h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('staff.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('staff.noisoi.pending') }}">Nội soi</a></li>
                    <li class="breadcrumb-item active">Đã hoàn thành</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ route('staff.noisoi.pending') }}" class="btn btn-outline-primary">
                <i class="fas fa-hourglass-start me-2"></i>Chờ xử lý
            </a>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form class="row g-2" method="GET">
                <div class="col-md-3">
                    <input type="text" name="search" class="form-control" placeholder="Tìm kiếm" value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <select name="bac_si_id" class="form-select">
                        <option value="">-- Bác sĩ --</option>
                        @foreach($bacSis as $bs)
                            <option value="{{ $bs->id }}" @selected((string)request('bac_si_id')===(string)$bs->id)>
                                {{ $bs->user->name ?? $bs->ho_ten ?? ('#'.$bs->id) }}
                            </option>
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
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th width="80">Mã</th>
                            <th>Bệnh nhân</th>
                            <th>Bác sĩ</th>
                            <th>Loại</th>
                            <th>Hoàn thành</th>
                            <th width="140" class="text-end">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($noiSois as $ns)
                            <tr>
                                <td><strong class="text-primary">#{{ $ns->id }}</strong></td>
                                <td>
                                    <div class="fw-medium">{{ $ns->benhAn->user->name ?? 'N/A' }}</div>
                                    <small class="text-muted">{{ $ns->benhAn->user->so_dien_thoai ?? '' }}</small>
                                </td>
                                <td><small>{{ $ns->bacSiChiDinh->user->name ?? 'N/A' }}</small></td>
                                <td><span class="badge bg-light text-dark">{{ $ns->loaiNoiSoi?->ten ?? $ns->loai }}</span></td>
                                <td><small>{{ $ns->updated_at?->format('d/m/Y H:i') }}</small></td>
                                <td class="text-end">
                                    <a href="{{ route('staff.noisoi.show', $ns) }}" class="btn btn-sm btn-outline-secondary">
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
                                <td colspan="6" class="text-center py-5 text-muted">Chưa có nội soi hoàn thành</td>
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
