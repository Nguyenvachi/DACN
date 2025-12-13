@extends('layouts.doctor')

@section('title', 'Xem kết quả nội soi')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1 fw-bold">
                <i class="bi bi-camera-video me-2 text-info"></i>
                Kết quả nội soi
            </h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('doctor.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('doctor.benhan.show', $noiSoi->benh_an_id) }}">Bệnh án #{{ $noiSoi->benh_an_id }}</a></li>
                    <li class="breadcrumb-item active">Kết quả nội soi</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('doctor.noi-soi.edit', $noiSoi->id) }}" class="btn btn-warning">
                <i class="bi bi-pencil me-2"></i>Chỉnh sửa
            </a>
            <a href="{{ route('doctor.benhan.show', $noiSoi->benh_an_id) }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-2"></i>Quay lại
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">Thông tin nội soi</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p class="mb-2"><strong>Loại nội soi:</strong> {{ $noiSoi->loai_noi_soi }}</p>
                            <p class="mb-2"><strong>Ngày chỉ định:</strong> {{ $noiSoi->ngay_chi_dinh->format('d/m/Y') }}</p>
                            <p class="mb-2"><strong>Ngày thực hiện:</strong> {{ $noiSoi->ngay_thuc_hien ? $noiSoi->ngay_thuc_hien->format('d/m/Y') : '-' }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-2"><strong>Bác sĩ chỉ định:</strong> {{ $noiSoi->bacSiChiDinh->ho_ten ?? 'N/A' }}</p>
                            <p class="mb-2"><strong>Bác sĩ thực hiện:</strong> {{ $noiSoi->bacSiThucHien->ho_ten ?? 'N/A' }}</p>
                            <p class="mb-2"><strong>Trạng thái:</strong> 
                                <span class="badge bg-{{ $noiSoi->trang_thai_badge }}">{{ $noiSoi->trang_thai_text }}</span>
                            </p>
                        </div>
                    </div>

                    @if($noiSoi->chi_dinh)
                    <div class="mb-3">
                        <strong>Lý do chỉ định:</strong>
                        <p class="mb-0">{{ $noiSoi->chi_dinh }}</p>
                    </div>
                    @endif

                    @if($noiSoi->mo_ta_hinh_anh)
                    <div class="mb-3">
                        <strong>Mô tả hình ảnh:</strong>
                        <p class="mb-0">{{ $noiSoi->mo_ta_hinh_anh }}</p>
                    </div>
                    @endif

                    @if($noiSoi->ton_thuong)
                    <div class="mb-3">
                        <strong>Tổn thương:</strong>
                        <p class="mb-0 text-danger">{{ $noiSoi->ton_thuong }}</p>
                    </div>
                    @endif

                    @if($noiSoi->chan_doan)
                    <div class="mb-3">
                        <strong>Chẩn đoán:</strong>
                        <p class="mb-0">{{ $noiSoi->chan_doan }}</p>
                    </div>
                    @endif

                    @if($noiSoi->sinh_thiet)
                    <div class="mb-3">
                        <strong>Sinh thiết:</strong>
                        <p class="mb-0">{{ $noiSoi->sinh_thiet }}</p>
                    </div>
                    @endif

                    @if($noiSoi->xu_tri)
                    <div class="mb-3">
                        <strong>Xử trí:</strong>
                        <p class="mb-0">{{ $noiSoi->xu_tri }}</p>
                    </div>
                    @endif

                    @if($noiSoi->bien_chung)
                    <div class="mb-3">
                        <strong>Biến chứng:</strong>
                        <p class="mb-0 text-warning">{{ $noiSoi->bien_chung }}</p>
                    </div>
                    @endif

                    @if($noiSoi->ket_luan)
                    <div class="mb-3">
                        <strong>Kết luận:</strong>
                        <p class="mb-0">{{ $noiSoi->ket_luan }}</p>
                    </div>
                    @endif

                    @if($noiSoi->de_nghi)
                    <div class="mb-3">
                        <strong>Đề nghị:</strong>
                        <p class="mb-0">{{ $noiSoi->de_nghi }}</p>
                    </div>
                    @endif

                    @if($noiSoi->ghi_chu)
                    <div class="mb-3">
                        <strong>Ghi chú:</strong>
                        <p class="mb-0">{{ $noiSoi->ghi_chu }}</p>
                    </div>
                    @endif

                    @if($noiSoi->hinh_anh && count($noiSoi->hinh_anh) > 0)
                    <div class="mb-3">
                        <strong>Hình ảnh nội soi:</strong>
                        <div class="d-flex flex-wrap gap-2 mt-2">
                            @foreach($noiSoi->hinh_anh as $img)
                            <a href="{{ asset('storage/' . $img) }}" target="_blank">
                                <img src="{{ asset('storage/' . $img) }}" class="img-thumbnail" style="width: 150px; height: 150px; object-fit: cover;">
                            </a>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm border-primary">
                <div class="card-header bg-light">
                    <h6 class="mb-0"><i class="bi bi-person me-2"></i>Thông tin bệnh nhân</h6>
                </div>
                <div class="card-body">
                    <p class="mb-2"><strong>Họ tên:</strong> {{ $noiSoi->benhAn->benhNhan->name }}</p>
                    <p class="mb-2"><strong>Mã BN:</strong> #{{ $noiSoi->benhAn->benhNhan->id }}</p>
                    <p class="mb-2"><strong>Ngày sinh:</strong> {{ $noiSoi->benhAn->benhNhan->ngay_sinh ? \Carbon\Carbon::parse($noiSoi->benhAn->benhNhan->ngay_sinh)->format('d/m/Y') : 'N/A' }}</p>
                    <p class="mb-0"><strong>Số điện thoại:</strong> {{ $noiSoi->benhAn->benhNhan->sdt ?? 'N/A' }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
