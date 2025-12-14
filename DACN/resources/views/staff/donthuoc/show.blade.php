@extends('layouts.staff')

@section('title', 'Chi Tiết Đơn Thuốc #' . $donThuoc->id)

@section('content')
<div class="container-fluid py-4">
    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">
                <i class="bi bi-prescription2 text-primary me-2"></i>
                Đơn Thuốc #{{ str_pad($donThuoc->id, 4, '0', STR_PAD_LEFT) }}
            </h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('staff.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('staff.donthuoc.index') }}">Đơn thuốc</a></li>
                    <li class="breadcrumb-item active">Chi tiết</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ route('staff.donthuoc.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-1"></i>Quay lại
            </a>
            <button onclick="window.print()" class="btn btn-info">
                <i class="bi bi-printer me-1"></i>In đơn
            </button>
        </div>
    </div>

    <div class="row g-4">
        {{-- Left Column --}}
        <div class="col-lg-8">
            {{-- Thông tin bệnh nhân --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-person-badge me-2"></i>Thông Tin Bệnh Nhân</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p class="mb-2"><strong><i class="bi bi-person me-2"></i>Họ tên:</strong> {{ $donThuoc->benhAn->user->name ?? 'N/A' }}</p>
                            <p class="mb-2"><strong><i class="bi bi-calendar3 me-2"></i>Ngày sinh:</strong> 
                                {{ $donThuoc->benhAn->user->ngay_sinh ? $donThuoc->benhAn->user->ngay_sinh->format('d/m/Y') : 'N/A' }}
                            </p>
                            <p class="mb-2"><strong><i class="bi bi-gender-ambiguous me-2"></i>Giới tính:</strong> 
                                {{ $donThuoc->benhAn->user->gioi_tinh ?? 'N/A' }}
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-2"><strong><i class="bi bi-telephone me-2"></i>Số điện thoại:</strong> 
                                {{ $donThuoc->benhAn->user->so_dien_thoai ?? 'N/A' }}
                            </p>
                            <p class="mb-2"><strong><i class="bi bi-envelope me-2"></i>Email:</strong> 
                                {{ $donThuoc->benhAn->user->email ?? 'N/A' }}
                            </p>
                            <p class="mb-2"><strong><i class="bi bi-geo-alt me-2"></i>Địa chỉ:</strong> 
                                {{ $donThuoc->benhAn->user->dia_chi ?? 'N/A' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Thông tin khám bệnh --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="bi bi-clipboard2-pulse me-2"></i>Thông Tin Khám Bệnh</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p class="mb-2"><strong>Bác sĩ khám:</strong> BS. {{ $donThuoc->benhAn->bacSi->ho_ten ?? 'N/A' }}</p>
                            <p class="mb-2"><strong>Ngày khám:</strong> {{ $donThuoc->benhAn->created_at->format('d/m/Y H:i') }}</p>
                            <p class="mb-2"><strong>Mã bệnh án:</strong> #{{ $donThuoc->benh_an_id }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-2"><strong>Triệu chứng:</strong> 
                                @if(empty($donThuoc->benhAn->trieu_chung))
                                    <span class="text-muted">(Chưa có thông tin)</span>
                                @else
                                    {{ $donThuoc->benhAn->trieu_chung }}
                                @endif
                            </p>
                            <p class="mb-2"><strong>Chẩn đoán:</strong> 
                                @if(empty($donThuoc->benhAn->chuan_doan))
                                    <span class="text-muted">(Chưa có thông tin)</span>
                                @else
                                    {{ $donThuoc->benhAn->chuan_doan }}
                                @endif
                            </p>
                        </div>
                    </div>
                    @if($donThuoc->benhAn->ghi_chu)
                    <hr>
                    <p class="mb-0"><strong>Ghi chú bác sĩ:</strong> {{ $donThuoc->benhAn->ghi_chu }}</p>
                    @endif
                </div>
            </div>

            {{-- Danh sách thuốc --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="bi bi-capsule me-2"></i>Danh Sách Thuốc</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th width="5%">#</th>
                                    <th width="30%">Tên Thuốc</th>
                                    <th width="15%">Hoạt chất</th>
                                    <th width="10%">SL</th>
                                    <th width="20%">Liều dùng</th>
                                    <th width="20%">Cách dùng</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($donThuoc->items as $index => $item)
                                <tr>
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    <td>
                                        <strong>{{ $item->thuoc->ten ?? 'N/A' }}</strong>
                                        @if($item->thuoc)
                                        <br><small class="text-muted">{{ $item->thuoc->ham_luong }}</small>
                                        @endif
                                    </td>
                                    <td>{{ $item->thuoc->hoat_chat ?? 'N/A' }}</td>
                                    <td class="text-center">
                                        <span class="badge bg-primary">{{ $item->so_luong }} {{ $item->thuoc->don_vi ?? '' }}</span>
                                    </td>
                                    <td>{{ $item->lieu_dung ?? 'N/A' }}</td>
                                    <td>{{ $item->cach_dung ?? '-' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @if($donThuoc->ghi_chu)
                    <div class="alert alert-info mt-3 mb-0">
                        <strong><i class="bi bi-info-circle me-2"></i>Ghi chú đơn thuốc:</strong><br>
                        {{ $donThuoc->ghi_chu }}
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Right Column --}}
        <div class="col-lg-4">
            {{-- Trạng thái --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-flag me-2"></i>Trạng Thái</h5>
                </div>
                <div class="card-body">
                    @if($donThuoc->ngay_cap_thuoc)
                    <div class="alert alert-success mb-3">
                        <h6 class="alert-heading"><i class="bi bi-check-circle me-2"></i>Đã cấp thuốc</h6>
                        <hr>
                        <p class="mb-1"><strong>Ngày cấp:</strong> {{ $donThuoc->ngay_cap_thuoc->format('d/m/Y H:i') }}</p>
                        <p class="mb-1"><strong>Người cấp:</strong> {{ $donThuoc->nguoiCapThuoc->name ?? 'N/A' }}</p>
                        @if($donThuoc->ghi_chu_cap_thuoc)
                        <p class="mb-0"><strong>Ghi chú:</strong> {{ $donThuoc->ghi_chu_cap_thuoc }}</p>
                        @endif
                    </div>
                    @else
                    <div class="alert alert-warning mb-3">
                        <h6 class="alert-heading"><i class="bi bi-hourglass-split me-2"></i>Chưa cấp thuốc</h6>
                        <p class="mb-0">Đơn thuốc đang chờ cấp cho bệnh nhân.</p>
                    </div>

                    <form method="POST" action="{{ route('staff.donthuoc.cap-thuoc', $donThuoc) }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Ghi chú cấp thuốc (tùy chọn)</label>
                            <textarea name="ghi_chu_cap_thuoc" class="form-control" rows="3" 
                                      placeholder="Nhập ghi chú nếu có..."></textarea>
                        </div>
                        <button type="submit" class="btn btn-success w-100">
                            <i class="bi bi-check-circle me-1"></i>Xác nhận đã cấp thuốc
                        </button>
                    </form>
                    @endif
                </div>
            </div>

            {{-- Thông tin đơn thuốc --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-info-circle me-2"></i>Thông Tin</h5>
                </div>
                <div class="card-body">
                    <p class="mb-2"><strong>Mã đơn thuốc:</strong> #{{ str_pad($donThuoc->id, 4, '0', STR_PAD_LEFT) }}</p>
                    <p class="mb-2"><strong>Ngày kê đơn:</strong> {{ $donThuoc->created_at->format('d/m/Y H:i') }}</p>
                    <p class="mb-2"><strong>Số loại thuốc:</strong> {{ $donThuoc->items->count() }}</p>
                    <p class="mb-0"><strong>Tổng số lượng:</strong> {{ $donThuoc->items->sum('so_luong') }}</p>
                </div>
            </div>

            {{-- Actions --}}
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-gear me-2"></i>Thao Tác</h5>
                </div>
                <div class="card-body">
                    <a href="{{ route('staff.hoadon.create-from-benh-an', $donThuoc->benh_an_id) }}" 
                       class="btn btn-primary w-100 mb-2">
                        <i class="bi bi-receipt me-1"></i>Tạo hóa đơn
                    </a>
                    <button onclick="window.print()" class="btn btn-info w-100 mb-2">
                        <i class="bi bi-printer me-1"></i>In đơn thuốc
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    @media print {
        .btn, .breadcrumb, nav, .card-header, .alert-dismissible .btn-close {
            display: none !important;
        }
        .card {
            border: 1px solid #ddd !important;
            box-shadow: none !important;
        }
    }
</style>
@endpush
@endsection
