@extends('layouts.doctor')

@section('title', 'Chi tiết X-Quang #XQ' . $xQuang->id)

@section('content')
<div class="container-fluid py-4">
    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1 fw-bold" style="color: #1f2937;">
                <i class="fas fa-x-ray me-2" style="color: #3b82f6;"></i>
                X-Quang #XQ{{ $xQuang->id }}
            </h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('doctor.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('doctor.xquang.index') }}">X-Quang</a></li>
                    <li class="breadcrumb-item active">Chi tiết #XQ{{ $xQuang->id }}</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2">
            @if($xQuang->trang_thai === 'completed' && $xQuang->file_path)
                <a href="{{ route('doctor.xquang.download', $xQuang->id) }}" class="btn btn-success" target="_blank">
                    <i class="fas fa-download me-2"></i>Tải kết quả
                </a>
            @endif
            <a href="{{ route('doctor.xquang.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Quay lại
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            {{-- Thông tin X-Quang --}}
            <div class="card vc-card mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-info-circle me-2" style="color: #3b82f6;"></i>
                        Thông tin X-Quang
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p class="mb-2"><strong>Mã:</strong> #XQ{{ $xQuang->id }}</p>
                            <p class="mb-2"><strong>Loại:</strong> {{ $xQuang->loai }}</p>
                            <p class="mb-2"><strong>Ngày chỉ định:</strong> {{ optional($xQuang->ngay_chi_dinh ?? $xQuang->created_at)->format('d/m/Y H:i') }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-2"><strong>Trạng thái:</strong>
                                @if($xQuang->trang_thai === 'pending')
                                    <span class="badge bg-warning"><i class="fas fa-hourglass-half me-1"></i>Chờ thực hiện</span>
                                @elseif($xQuang->trang_thai === 'processing')
                                    <span class="badge bg-info"><i class="fas fa-spinner me-1"></i>Đang xử lý</span>
                                @elseif($xQuang->trang_thai === 'completed')
                                    <span class="badge bg-success"><i class="fas fa-check-circle me-1"></i>Đã có kết quả</span>
                                @endif
                            </p>
                            <p class="mb-2"><strong>Bệnh án:</strong>
                                <a href="{{ route('doctor.benhan.show', $xQuang->benh_an_id) }}" class="text-primary">
                                    #BA{{ $xQuang->benh_an_id }}
                                </a>
                            </p>
                        </div>
                    </div>

                    @if($xQuang->mo_ta)
                        <div class="alert alert-info">
                            <strong><i class="fas fa-notes-medical me-2"></i>Chỉ định chi tiết:</strong>
                            <p class="mb-0 mt-2">{{ $xQuang->mo_ta }}</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Kết quả X-Quang --}}
            @if($xQuang->trang_thai === 'completed' && $xQuang->file_path)
                <div class="card vc-card mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">
                            <i class="fas fa-file-medical me-2" style="color: #10b981;"></i>
                            Kết quả
                        </h5>
                    </div>
                    <div class="card-body">
                        @if($xQuang->ket_qua)
                            <div class="mb-3">
                                <strong>Kết quả:</strong>
                                <div class="mt-2" style="white-space: pre-line;">{{ $xQuang->ket_qua }}</div>
                            </div>
                        @endif

                        @if($xQuang->nhan_xet)
                            <div class="mb-3">
                                <strong>Nhận xét kỹ thuật viên:</strong>
                                <div class="mt-2" style="white-space: pre-line;">{{ $xQuang->nhan_xet }}</div>
                            </div>
                        @endif

                        <div class="text-center py-3">
                            <a href="{{ route('doctor.xquang.download', $xQuang->id) }}" class="btn btn-lg btn-success" target="_blank">
                                <i class="fas fa-download me-2"></i>Tải file kết quả
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Nhận xét bác sĩ --}}
                <div class="card vc-card">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">
                            <i class="fas fa-comment-medical me-2" style="color: #6366f1;"></i>
                            Nhận xét bác sĩ
                        </h5>
                    </div>
                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif
                        @if (session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif

                        <form action="{{ route('doctor.xquang.comment', $xQuang->id) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <textarea name="nhan_xet_bac_si" class="form-control" rows="4" placeholder="Nhập nhận xét của bác sĩ...">{{ old('nhan_xet_bac_si') }}</textarea>
                                @error('nhan_xet_bac_si')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>Lưu nhận xét
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            @else
                <div class="card vc-card">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-hourglass-half fa-3x text-warning mb-3"></i>
                        <p class="text-muted">Đang chờ kỹ thuật viên thực hiện và upload kết quả</p>
                    </div>
                </div>
            @endif
        </div>

        <div class="col-lg-4">
            {{-- Thông tin bệnh nhân --}}
            <div class="card vc-card">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-user-injured me-2" style="color: #3b82f6;"></i>
                        Thông tin bệnh nhân
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Họ tên:</strong>
                        <p class="mb-0">{{ $xQuang->benhAn->user->name ?? 'N/A' }}</p>
                    </div>
                    <div class="mb-3">
                        <strong>Mã BN:</strong>
                        <p class="mb-0">#{{ $xQuang->user_id }}</p>
                    </div>
                    <div class="mb-3">
                        <strong>SĐT:</strong>
                        <p class="mb-0">{{ $xQuang->benhAn->user->so_dien_thoai ?? 'N/A' }}</p>
                    </div>

                    @if($xQuang->benhAn->lichHen)
                        <hr>
                        <div class="mb-3">
                            <strong>Lịch hẹn:</strong>
                            <p class="mb-0">{{ optional($xQuang->benhAn->lichHen->ngay_hen)->format('d/m/Y') }}</p>
                        </div>
                        <div class="mb-3">
                            <strong>Dịch vụ:</strong>
                            <p class="mb-0">{{ $xQuang->benhAn->lichHen->dichVu->ten_dich_vu ?? 'N/A' }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .alert-info {
        background-color: #e0f2fe;
        border-color: #0ea5e9;
        color: #0c4a6e;
    }
</style>
@endpush
