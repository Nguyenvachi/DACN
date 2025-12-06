@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0">
            <i class="bi bi-star-fill text-warning me-2"></i>
            Quản lý Đánh giá
        </h2>
    </div>

    {{-- FLASH MESSAGE --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle"></i> {{ session('success') }}
            <button class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- STATISTICS --}}
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Tổng đánh giá</h6>
                            <h3 class="fw-bold mb-0">{{ $danhGias->count() }}</h3>
                        </div>
                        <div class="bg-primary bg-opacity-10 p-3 rounded">
                            <i class="bi bi-star-fill text-primary fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Đã duyệt</h6>
                            <h3 class="fw-bold mb-0 text-success">
                                {{ \App\Models\DanhGia::approved()->count() }}
                            </h3>
                        </div>
                        <div class="bg-success bg-opacity-10 p-3 rounded">
                            <i class="bi bi-check-circle-fill text-success fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Chờ duyệt</h6>
                            <h3 class="fw-bold mb-0 text-warning">
                                {{ \App\Models\DanhGia::pending()->count() }}
                            </h3>
                        </div>
                        <div class="bg-warning bg-opacity-10 p-3 rounded">
                            <i class="bi bi-hourglass-split text-warning fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Đã từ chối</h6>
                            <h3 class="fw-bold mb-0 text-danger">
                                {{ \App\Models\DanhGia::where('trang_thai', 'rejected')->count() }}
                            </h3>
                        </div>
                        <div class="bg-danger bg-opacity-10 p-3 rounded">
                            <i class="bi bi-x-circle-fill text-danger fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- FILTER PANEL --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.danhgia.index') }}" class="row g-3">

                {{-- Search --}}
                <div class="col-md-3">
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                        placeholder="Tìm kiếm...">
                </div>

                {{-- Bác sĩ --}}
                <div class="col-md-3">
                    <select name="bac_si_id" class="form-select">
                        <option value="">Tất cả bác sĩ</option>
                        @foreach ($bacSis as $bacSi)
                            <option value="{{ $bacSi->id }}" {{ request('bac_si_id') == $bacSi->id ? 'selected' : '' }}>
                                {{ $bacSi->ho_ten }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Rating --}}
                <div class="col-md-2">
                    <select name="rating" class="form-select">
                        <option value="">Tất cả sao</option>
                        @for ($i = 5; $i >= 1; $i--)
                            <option value="{{ $i }}" {{ request('rating') == $i ? 'selected' : '' }}>
                                {{ $i }} sao
                            </option>
                        @endfor
                    </select>
                </div>

                {{-- Trạng thái --}}
                <div class="col-md-2">
                    <select name="trang_thai" class="form-select">
                        <option value="">Tất cả trạng thái</option>
                        <option value="approved" {{ request('trang_thai') === 'approved' ? 'selected' : '' }}>Đã duyệt</option>
                        <option value="pending" {{ request('trang_thai') === 'pending' ? 'selected' : '' }}>Chờ duyệt</option>
                        <option value="rejected" {{ request('trang_thai') === 'rejected' ? 'selected' : '' }}>Từ chối</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <button class="btn btn-primary w-100">
                        <i class="bi bi-funnel"></i> Lọc
                    </button>
                </div>

            </form>
        </div>
    </div>

    {{-- MAIN TABLE --}}
    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table id="danhGiaTable" class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Bệnh nhân</th>
                            <th>Bác sĩ</th>
                            <th>Đánh giá</th>
                            <th>Nội dung</th>
                            <th>Trạng thái</th>
                            <th>Ngày tạo</th>
                            <th class="text-center">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($danhGias as $danhGia)
                            <tr>
                                <td>
                                    <div>
                                        <strong>{{ $danhGia->user->name }}</strong><br>
                                        <small class="text-muted">{{ $danhGia->user->email }}</small>
                                    </div>
                                </td>
                                <td>
                                    <strong>{{ $danhGia->bacSi->ho_ten }}</strong>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @for ($i = 1; $i <= 5; $i++)
                                            @if ($i <= $danhGia->rating)
                                                <i class="bi bi-star-fill text-warning"></i>
                                            @else
                                                <i class="bi bi-star text-muted"></i>
                                            @endif
                                        @endfor
                                        <span class="ms-2 fw-bold">{{ $danhGia->rating }}/5</span>
                                    </div>
                                </td>
                                <td>
                                    <div style="max-width: 300px;">
                                        {{ Str::limit($danhGia->noi_dung, 100) }}
                                    </div>
                                </td>
                                <td>
                                    @if ($danhGia->trang_thai === 'approved')
                                        <span class="badge bg-success">Đã duyệt</span>
                                    @elseif ($danhGia->trang_thai === 'pending')
                                        <span class="badge bg-warning text-dark">Chờ duyệt</span>
                                    @else
                                        <span class="badge bg-danger">Từ chối</span>
                                    @endif
                                </td>
                                <td>
                                    <small>{{ $danhGia->created_at->format('d/m/Y H:i') }}</small>
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('admin.danhgia.show', $danhGia->id) }}"
                                       class="btn btn-sm btn-outline-secondary me-1" title="Xem">
                                        <i class="bi bi-eye"></i>
                                    </a>

                                    @if ($danhGia->trang_thai !== 'approved')
                                        <form action="{{ route('admin.danhgia.approve', $danhGia->id) }}"
                                              method="POST" style="display:inline-block;" class="me-1">
                                            @csrf
                                            @method('PATCH')
                                            <button class="btn btn-sm btn-outline-success" type="submit" title="Duyệt">
                                                <i class="bi bi-check-circle"></i>
                                            </button>
                                        </form>
                                    @endif

                                    @if ($danhGia->trang_thai !== 'rejected')
                                        <form action="{{ route('admin.danhgia.reject', $danhGia->id) }}"
                                              method="POST" style="display:inline-block;" class="me-1">
                                            @csrf
                                            @method('PATCH')
                                            <button class="btn btn-sm btn-outline-warning" type="submit" title="Từ chối">
                                                <i class="bi bi-x-circle"></i>
                                            </button>
                                        </form>
                                    @endif

                                    <form action="{{ route('admin.danhgia.destroy', $danhGia->id) }}"
                                          method="POST" style="display:inline-block;"
                                          onsubmit="return confirm('Bạn có chắc muốn xóa đánh giá này?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger" type="submit" title="Xóa">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <i class="bi bi-inbox fs-1 text-muted"></i>
                                    <p class="text-muted mt-2">Chưa có đánh giá nào</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<x-datatable-script tableId="danhGiaTable" />
@endsection
