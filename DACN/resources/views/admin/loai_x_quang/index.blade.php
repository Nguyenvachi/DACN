@extends('layouts.admin')

@section('content')
    <div class="container-fluid py-4">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold mb-0">
                <i class="fas fa-x-ray me-2"></i>Quản lý Loại X-Quang
            </h2>

            <a href="{{ route('admin.loai-x-quang.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i> Thêm loại X-Quang
            </a>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                {{ session('error') }}
                <button class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body">
                <form method="GET" class="row g-2 mb-3" action="{{ route('admin.loai-x-quang.index') }}">
                    <div class="col-md-6">
                        <input type="text" name="q" class="form-control" value="{{ $q ?? '' }}" placeholder="Tìm theo tên hoặc mã...">
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-outline-secondary w-100" type="submit">
                            <i class="fas fa-search me-1"></i> Tìm
                        </button>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Tên</th>
                                <th>Mã</th>
                                <th>Giá</th>
                                <th>Thời gian</th>
                                <th>Phòng</th>
                                <th>Chuyên khoa</th>
                                <th>Trạng thái</th>
                                <th class="text-center">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($items as $it)
                                <tr>
                                    <td class="fw-semibold">{{ $it->ten }}</td>
                                    <td><code>{{ $it->ma ?? '—' }}</code></td>
                                    <td>{{ number_format($it->gia, 0, ',', '.') }} đ</td>
                                    <td>{{ (int) $it->thoi_gian_uoc_tinh }} phút</td>
                                    <td>{{ optional($it->phong)->ten ?? '—' }}</td>
                                    <td>
                                        @if($it->chuyenKhoas && $it->chuyenKhoas->count() > 0)
                                            @foreach($it->chuyenKhoas as $ck)
                                                <span class="badge bg-info text-dark me-1">{{ $ck->ten }}</span>
                                            @endforeach
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($it->active)
                                            <span class="badge bg-success">Bật</span>
                                        @else
                                            <span class="badge bg-secondary">Tắt</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('admin.loai-x-quang.edit', $it) }}" class="btn btn-sm btn-outline-warning">
                                            <i class="fas fa-edit"></i>
                                        </a>

                                        <form action="{{ route('admin.loai-x-quang.destroy', $it) }}" method="POST" class="d-inline"
                                              onsubmit="return confirm('Xóa loại X-Quang này? (Chỉ xóa được nếu chưa phát sinh X-Quang)')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4 text-muted">Chưa có loại X-Quang nào.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $items->links() }}
                </div>
            </div>
        </div>

    </div>
@endsection
