@extends('layouts.doctor')

@section('title', 'Xem kết quả X-quang')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1 fw-bold">
                <i class="bi bi-file-medical me-2 text-warning"></i>
                Kết quả X-quang
            </h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('doctor.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('doctor.benhan.show', $xQuang->benh_an_id) }}">Bệnh án #{{ $xQuang->benh_an_id }}</a></li>
                    <li class="breadcrumb-item active">Kết quả X-quang</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('doctor.x-quang.edit', $xQuang->id) }}" class="btn btn-warning">
                <i class="bi bi-pencil me-2"></i>Chỉnh sửa
            </a>
            <a href="{{ route('doctor.benhan.show', $xQuang->benh_an_id) }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-2"></i>Quay lại
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0">Thông tin X-quang</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p class="mb-2"><strong>Loại X-quang:</strong> {{ $xQuang->loai_x_quang }}</p>
                            <p class="mb-2"><strong>Vị trí:</strong> {{ $xQuang->vi_tri }}</p>
                            <p class="mb-2"><strong>Ngày chỉ định:</strong> {{ $xQuang->ngay_chi_dinh->format('d/m/Y') }}</p>
                            <p class="mb-2"><strong>Ngày chụp:</strong> {{ $xQuang->ngay_chup ? $xQuang->ngay_chup->format('d/m/Y') : '-' }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-2"><strong>Bác sĩ chỉ định:</strong> {{ $xQuang->bacSiChiDinh->ho_ten ?? 'N/A' }}</p>
                            <p class="mb-2"><strong>Bác sĩ đọc kết quả:</strong> {{ $xQuang->bacSiDocKetQua->ho_ten ?? 'N/A' }}</p>
                            <p class="mb-2"><strong>Kỹ thuật:</strong> {{ $xQuang->ky_thuat ?? '-' }}</p>
                            <p class="mb-2"><strong>Trạng thái:</strong> 
                                <span class="badge bg-{{ $xQuang->trang_thai_badge }}">{{ $xQuang->trang_thai_text }}</span>
                            </p>
                        </div>
                    </div>

                    @if($xQuang->chi_dinh)
                    <div class="mb-3">
                        <strong>Lý do chỉ định:</strong>
                        <p class="mb-0">{{ $xQuang->chi_dinh }}</p>
                    </div>
                    @endif

                    @if($xQuang->mo_ta_hinh_anh)
                    <div class="mb-3">
                        <strong>Mô tả hình ảnh:</strong>
                        <p class="mb-0">{{ $xQuang->mo_ta_hinh_anh }}</p>
                    </div>
                    @endif

                    @if($xQuang->tim_mach)
                    <div class="mb-3">
                        <strong>Tim mạch:</strong>
                        <p class="mb-0">{{ $xQuang->tim_mach }}</p>
                    </div>
                    @endif

                    @if($xQuang->phoi)
                    <div class="mb-3">
                        <strong>Phổi:</strong>
                        <p class="mb-0">{{ $xQuang->phoi }}</p>
                    </div>
                    @endif

                    @if($xQuang->xuong_khop)
                    <div class="mb-3">
                        <strong>Xương khớp:</strong>
                        <p class="mb-0">{{ $xQuang->xuong_khop }}</p>
                    </div>
                    @endif

                    @if($xQuang->co_quan_khac)
                    <div class="mb-3">
                        <strong>Cơ quan khác:</strong>
                        <p class="mb-0">{{ $xQuang->co_quan_khac }}</p>
                    </div>
                    @endif

                    @if($xQuang->chan_doan)
                    <div class="mb-3">
                        <strong>Chẩn đoán:</strong>
                        <p class="mb-0 text-primary fw-bold">{{ $xQuang->chan_doan }}</p>
                    </div>
                    @endif

                    @if($xQuang->ket_luan)
                    <div class="mb-3">
                        <strong>Kết luận:</strong>
                        <p class="mb-0">{{ $xQuang->ket_luan }}</p>
                    </div>
                    @endif

                    @if($xQuang->de_nghi)
                    <div class="mb-3">
                        <strong>Đề nghị:</strong>
                        <p class="mb-0">{{ $xQuang->de_nghi }}</p>
                    </div>
                    @endif

                    @if($xQuang->ghi_chu)
                    <div class="mb-3">
                        <strong>Ghi chú:</strong>
                        <p class="mb-0">{{ $xQuang->ghi_chu }}</p>
                    </div>
                    @endif

                    @if($xQuang->hinh_anh && count($xQuang->hinh_anh) > 0)
                    <div class="mb-3">
                        <strong>Hình ảnh X-quang:</strong>
                        <div class="d-flex flex-wrap gap-2 mt-2">
                            @foreach($xQuang->hinh_anh as $img)
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
                    <p class="mb-2"><strong>Họ tên:</strong> {{ $xQuang->benhAn->benhNhan->name }}</p>
                    <p class="mb-2"><strong>Mã BN:</strong> #{{ $xQuang->benhAn->benhNhan->id }}</p>
                    <p class="mb-2"><strong>Ngày sinh:</strong> {{ $xQuang->benhAn->benhNhan->ngay_sinh ? \Carbon\Carbon::parse($xQuang->benhAn->benhNhan->ngay_sinh)->format('d/m/Y') : 'N/A' }}</p>
                    <p class="mb-0"><strong>Số điện thoại:</strong> {{ $xQuang->benhAn->benhNhan->sdt ?? 'N/A' }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
