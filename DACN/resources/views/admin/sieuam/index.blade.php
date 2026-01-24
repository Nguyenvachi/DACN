{{-- filepath: resources/views/admin/sieuam/index.blade.php --}}
{{-- Parent file: app/Http/Controllers/Admin/SieuAmController.php --}}

@extends('layouts.admin')

@section('title', 'Quản lý Siêu âm')

@section('content')
    <div class="container-fluid py-4">
        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-0"><i class="fas fa-file-medical me-2"></i>Quản lý Siêu âm</h2>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.sieuam.statistics') }}" class="btn btn-primary">
                    <i class="fas fa-chart-line me-2"></i>Thống kê
                </a>
                <a href="{{ route('admin.sieuam.report') }}" class="btn btn-info">
                    <i class="fas fa-chart-bar me-2"></i>Báo cáo thống kê
                </a>
            </div>
        </div>

        {{-- Stats Cards --}}
        <div class="row mb-4">
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card border-0 shadow-sm h-100"
                    style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <div class="card-body text-white">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h6 class="text-white-50 mb-1">Tổng số</h6>
                                <h3 class="mb-0 fw-bold">{{ $stats['total'] }}</h3>
                            </div>
                            <i class="fas fa-clipboard-list fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card border-0 shadow-sm h-100"
                    style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                    <div class="card-body text-white">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h6 class="text-white-50 mb-1">Chờ thực hiện</h6>
                                <h3 class="mb-0 fw-bold">{{ $stats['pending'] }}</h3>
                            </div>
                            <i class="fas fa-hourglass-half fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card border-0 shadow-sm h-100"
                    style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                    <div class="card-body text-white">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h6 class="text-white-50 mb-1">Đang thực hiện</h6>
                                <h3 class="mb-0 fw-bold">{{ $stats['processing'] }}</h3>
                            </div>
                            <i class="fas fa-spinner fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card border-0 shadow-sm h-100"
                    style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
                    <div class="card-body text-white">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h6 class="text-white-50 mb-1">Đã hoàn thành</h6>
                                <h3 class="mb-0 fw-bold">{{ $stats['completed'] }}</h3>
                            </div>
                            <i class="fas fa-check-circle fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Filters --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('admin.sieuam.index') }}" class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Trạng thái</label>
                        <select name="trang_thai" class="form-select">
                            <option value="">Tất cả</option>
                            <option value="pending" {{ request('trang_thai') == 'pending' ? 'selected' : '' }}>Chờ thực hiện
                            </option>
                            <option value="processing" {{ request('trang_thai') == 'processing' ? 'selected' : '' }}>Đang
                                thực hiện</option>
                            <option value="completed" {{ request('trang_thai') == 'completed' ? 'selected' : '' }}>Đã hoàn
                                thành</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Loại siêu âm</label>
                        <select name="loai_sieu_am_id" class="form-select">
                            <option value="">Tất cả</option>
                            @foreach (\App\Models\LoaiSieuAm::all() as $loai)
                                <option value="{{ $loai->id }}"
                                    {{ request('loai_sieu_am_id') == $loai->id ? 'selected' : '' }}>
                                    {{ $loai->ten }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Bác sĩ</label>
                        <select name="bac_si_id" class="form-select">
                            <option value="">Tất cả</option>
                            @foreach (\App\Models\BacSi::with('user')->get() as $bs)
                                <option value="{{ $bs->id }}"
                                    {{ request('bac_si_id') == $bs->id ? 'selected' : '' }}>
                                    {{ $bs->user->ho_ten }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Tìm kiếm</label>
                        <input type="text" name="search" class="form-control" placeholder="Tên bệnh nhân..."
                            value="{{ request('search') }}">
                    </div>

                    <div class="col-12">
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="fas fa-filter me-1"></i>Lọc
                        </button>
                        <a href="{{ route('admin.sieuam.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-redo me-1"></i>Reset
                        </a>
                    </div>
                </form>
            </div>
        </div>

        {{-- Data Table --}}
        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                @if ($sieuAms->count() > 0)
                    <div class="table-responsive">
                        <table id="sieuAmTable" class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Bệnh nhân</th>
                                    <th>Loại siêu âm</th>
                                    <th>Bác sĩ</th>
                                    <th>Ngày tạo</th>
                                    <th>Giá</th>
                                    <th>Trạng thái</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($sieuAms as $sieuAm)
                                    <tr>
                                        <td>#{{ $sieuAm->id }}</td>
                                        <td>
                                            @if ($sieuAm->benhAn && $sieuAm->benhAn->user)
                                                <strong>{{ $sieuAm->benhAn->user->ho_ten }}</strong>
                                                <br><small class="text-muted">{{ $sieuAm->benhAn->user->email }}</small>
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </td>
                                        <td>{{ $sieuAm->loaiSieuAm->ten ?? 'N/A' }}</td>
                                        <td>
                                            @if ($sieuAm->bacSi)
                                                {{ $sieuAm->bacSi->user->ho_ten }}
                                            @else
                                                <span class="text-muted">Chưa chỉ định</span>
                                            @endif
                                        </td>
                                        <td>{{ $sieuAm->created_at->format('d/m/Y H:i') }}</td>
                                        <td>{{ number_format($sieuAm->gia, 0, ',', '.') }} VNĐ</td>
                                        <td>
                                            <span class="badge {{ $sieuAm->trang_thai_badge_class }}">
                                                {{ $sieuAm->trang_thai_text }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.sieuam.show', $sieuAm) }}"
                                                class="btn btn-sm btn-outline-primary" title="Xem chi tiết">
                                                <i class="fas fa-eye"></i>
                                            </a>

                                            @if ($sieuAm->file_path)
                                                <a href="{{ URL::temporarySignedRoute('admin.benhan.sieuam.download', now()->addMinutes(10), ['sieuAm' => $sieuAm->id]) }}"
                                                    class="btn btn-sm btn-outline-success" title="Tải file">
                                                    <i class="fas fa-download"></i>
                                                </a>
                                            @endif

                                            <form action="{{ route('admin.sieuam.destroy', $sieuAm) }}" method="POST"
                                                class="d-inline"
                                                onsubmit="return confirm('Bạn có chắc muốn xóa siêu âm này?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger"
                                                    title="Xóa">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="p-3">
                        {{ $sieuAms->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Không tìm thấy kết quả nào</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

<x-datatable-script
    tableId="sieuAmTable"
    config='{"paging": false, "info": false, "searching": false, "lengthChange": false}'
/>
