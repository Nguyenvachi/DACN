@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0">
            <i class="bi bi-star-fill text-warning me-2"></i>
            Chi tiết Đánh giá
        </h2>
        <a href="{{ route('admin.danhgia.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Quay lại
        </a>
    </div>

    <div class="row g-4">
        {{-- Thông tin đánh giá --}}
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">Nội dung đánh giá</h5>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="me-3">
                                @for ($i = 1; $i <= 5; $i++)
                                    @if ($i <= $danhGia->rating)
                                        <i class="bi bi-star-fill text-warning fs-3"></i>
                                    @else
                                        <i class="bi bi-star text-muted fs-3"></i>
                                    @endif
                                @endfor
                            </div>
                            <h3 class="mb-0">{{ $danhGia->rating }}/5</h3>
                        </div>

                        <div class="bg-light p-3 rounded">
                            <p class="mb-0">{{ $danhGia->noi_dung }}</p>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-md-6">
                            <strong>Trạng thái:</strong>
                            @if ($danhGia->trang_thai === 'approved')
                                <span class="badge bg-success">Đã duyệt</span>
                            @elseif ($danhGia->trang_thai === 'pending')
                                <span class="badge bg-warning text-dark">Chờ duyệt</span>
                            @else
                                <span class="badge bg-danger">Từ chối</span>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <strong>Ngày đánh giá:</strong> {{ $danhGia->created_at->format('d/m/Y H:i') }}
                        </div>
                    </div>
                </div>
            </div>

            {{-- Thông tin lịch hẹn --}}
            @if ($danhGia->lichHen)
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0">Thông tin lịch hẹn</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <strong>Mã lịch hẹn:</strong> #{{ $danhGia->lichHen->id }}
                            </div>
                            <div class="col-md-6">
                                <strong>Dịch vụ:</strong> {{ $danhGia->lichHen->dichVu->ten_dich_vu ?? 'N/A' }}
                            </div>
                            <div class="col-md-6">
                                <strong>Ngày hẹn:</strong> {{ \Carbon\Carbon::parse($danhGia->lichHen->ngay_hen)->format('d/m/Y') }}
                            </div>
                            <div class="col-md-6">
                                <strong>Thời gian:</strong> {{ $danhGia->lichHen->thoi_gian_hen }}
                            </div>
                            <div class="col-md-12">
                                <strong>Trạng thái:</strong>
                                <span class="badge bg-info">{{ $danhGia->lichHen->trang_thai }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        {{-- Thông tin bệnh nhân và bác sĩ --}}
        <div class="col-lg-4">
            {{-- Bệnh nhân --}}
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">Bệnh nhân</h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <i class="bi bi-person-circle fs-1 text-primary"></i>
                    </div>
                    <div>
                        <strong>{{ $danhGia->user->name }}</strong><br>
                        <small class="text-muted">
                            <i class="bi bi-envelope"></i> {{ $danhGia->user->email }}
                        </small>
                    </div>
                </div>
            </div>

            {{-- Bác sĩ --}}
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">Bác sĩ</h5>
                </div>
                <div class="card-body">
                    @if ($danhGia->bacSi->avatar_url)
                        <div class="text-center mb-3">
                            <img src="{{ $danhGia->bacSi->avatar_url }}"
                                 alt="{{ $danhGia->bacSi->ho_ten }}"
                                 class="rounded-circle"
                                 style="width: 80px; height: 80px; object-fit: cover;">
                        </div>
                    @else
                        <div class="text-center mb-3">
                            <i class="bi bi-person-circle fs-1 text-info"></i>
                        </div>
                    @endif
                    <div>
                        <strong>{{ $danhGia->bacSi->ho_ten }}</strong><br>
                        <small class="text-muted">
                            <i class="bi bi-envelope"></i> {{ $danhGia->bacSi->email }}
                        </small><br>
                        <small class="text-muted">
                            <i class="bi bi-telephone"></i> {{ $danhGia->bacSi->so_dien_thoai }}
                        </small>
                    </div>
                    <div class="mt-3">
                        <a href="{{ route('admin.bac-si.show', $danhGia->bacSi->id) }}"
                           class="btn btn-sm btn-outline-primary w-100">
                            Xem hồ sơ bác sĩ
                        </a>
                    </div>
                </div>
            </div>

            {{-- Hành động --}}
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">Hành động</h5>
                </div>
                <div class="card-body">
                    @if ($danhGia->trang_thai !== 'approved')
                        <form action="{{ route('admin.danhgia.approve', $danhGia->id) }}"
                              method="POST" class="mb-2">
                            @csrf
                            @method('PATCH')
                            <button class="btn btn-success w-100" type="submit">
                                <i class="bi bi-check-circle"></i> Duyệt đánh giá
                            </button>
                        </form>
                    @endif

                    @if ($danhGia->trang_thai !== 'rejected')
                        <form action="{{ route('admin.danhgia.reject', $danhGia->id) }}"
                              method="POST" class="mb-2">
                            @csrf
                            @method('PATCH')
                            <button class="btn btn-warning w-100" type="submit">
                                <i class="bi bi-x-circle"></i> Từ chối
                            </button>
                        </form>
                    @endif

                    <form action="{{ route('admin.danhgia.destroy', $danhGia->id) }}"
                          method="POST"
                          onsubmit="return confirm('Bạn có chắc muốn xóa đánh giá này?')">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger w-100" type="submit">
                            <i class="bi bi-trash"></i> Xóa đánh giá
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
