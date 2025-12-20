@extends('layouts.doctor')

@section('title', 'Chi tiết siêu âm #SA' . $sieuAm->id)

@section('content')
<div class="container-fluid py-4">
    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1 fw-bold" style="color: #1f2937;">
                <i class="fas fa-ultrasound me-2" style="color: #10b981;"></i>
                Siêu âm #SA{{ $sieuAm->id }}
            </h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('doctor.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('doctor.sieuam.index') }}">Siêu âm</a></li>
                    <li class="breadcrumb-item active">SA{{ $sieuAm->id }}</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ route('doctor.sieuam.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i> Quay lại
            </a>
            @if($sieuAm->file_path)
                <a href="{{ URL::temporarySignedRoute('doctor.benhan.sieuam.download', now()->addMinutes(10), ['sieuAm' => $sieuAm->id]) }}" class="btn btn-primary">
                    <i class="fas fa-download me-1"></i> Tải kết quả
                </a>
            @endif
        </div>
    </div>

    <div class="row">
        {{-- Thông tin chính --}}
        <div class="col-lg-8">
            {{-- Thông tin siêu âm --}}
            <div class="card vc-card mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-info-circle me-2" style="color: #10b981;"></i>
                        Thông tin siêu âm
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <small class="text-muted d-block mb-1">Mã siêu âm</small>
                            <span class="badge bg-primary fs-6">SA-{{ $sieuAm->id }}</span>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block mb-1">Trạng thái</small>
                            <span class="badge {{ $sieuAm->trang_thai_badge_class }} fs-6">
                                {{ $sieuAm->trang_thai_text }}
                            </span>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block mb-1">Loại siêu âm</small>
                            <strong>{{ $sieuAm->loai }}</strong>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block mb-1">Giá dịch vụ</small>
                            <strong class="text-primary">{{ number_format($sieuAm->gia, 0, ',', '.') }}đ</strong>
                        </div>
                        <div class="col-12">
                            <small class="text-muted d-block mb-1">Mô tả yêu cầu</small>
                            <p class="mb-0">{{ $sieuAm->mo_ta ?? 'Không có mô tả' }}</p>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block mb-1">Ngày chỉ định</small>
                            {{ $sieuAm->created_at->format('d/m/Y H:i') }}
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block mb-1">Cập nhật lần cuối</small>
                            {{ $sieuAm->updated_at->format('d/m/Y H:i') }}
                        </div>
                    </div>
                </div>
            </div>

            {{-- Kết quả siêu âm --}}
            @if($sieuAm->trang_thai === 'completed')
                <div class="card vc-card mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">
                            <i class="fas fa-check-circle me-2" style="color: #10b981;"></i>
                            Kết quả siêu âm
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Kết quả từ kỹ thuật viên</label>
                            <div class="p-3 bg-light rounded">
                                {{ $sieuAm->ket_qua }}
                            </div>
                        </div>

                        @if($sieuAm->file_path)
                            <div class="mb-4">
                                <label class="form-label fw-semibold">File đính kèm</label>
                                <div class="d-flex align-items-center gap-2 p-3 border rounded">
                                    <i class="fas fa-file-pdf fa-2x text-danger"></i>
                                    <div class="flex-grow-1">
                                        <div class="fw-semibold">{{ basename($sieuAm->file_path) }}</div>
                                        <small class="text-muted">Uploaded: {{ $sieuAm->updated_at->format('d/m/Y H:i') }}</small>
                                    </div>
                                    <a href="{{ URL::temporarySignedRoute('doctor.benhan.sieuam.download', now()->addMinutes(10), ['sieuAm' => $sieuAm->id]) }}"
                                       class="btn btn-primary btn-sm">
                                        <i class="fas fa-download me-1"></i> Tải về
                                    </a>
                                </div>
                            </div>
                        @endif

                        {{-- Form nhận xét của bác sĩ --}}
                        <div>
                            <label class="form-label fw-semibold">Nhận xét của bác sĩ</label>
                            <form action="{{ route('doctor.sieuam.update-review', $sieuAm->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <textarea name="nhan_xet" rows="4" class="form-control mb-2"
                                          placeholder="Nhập nhận xét của bạn về kết quả siêu âm...">{{ old('nhan_xet', $sieuAm->nhan_xet) }}</textarea>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i> Lưu nhận xét
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @else
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    Siêu âm đang chờ kỹ thuật viên thực hiện và upload kết quả.
                </div>
            @endif
        </div>

        {{-- Sidebar --}}
        <div class="col-lg-4">
            {{-- Thông tin bệnh nhân --}}
            <div class="card vc-card mb-4">
                <div class="card-header bg-light py-3">
                    <h6 class="mb-0"><i class="fas fa-user-injured me-2"></i>Bệnh nhân</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <small class="text-muted d-block mb-1">Họ tên</small>
                        <strong>{{ $sieuAm->benhAn->user->name ?? 'N/A' }}</strong>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted d-block mb-1">Email</small>
                        {{ $sieuAm->benhAn->user->email ?? 'N/A' }}
                    </div>
                    <div class="mb-3">
                        <small class="text-muted d-block mb-1">Mã bệnh án</small>
                        <a href="{{ route('doctor.benhan.edit', $sieuAm->benh_an_id) }}"
                           class="badge bg-primary text-decoration-none">
                            BA-{{ $sieuAm->benh_an_id }}
                        </a>
                    </div>
                    <div>
                        <small class="text-muted d-block mb-1">Chẩn đoán</small>
                        {{ $sieuAm->benhAn->chuan_doan ?? 'Chưa có' }}
                    </div>
                </div>
            </div>

            {{-- Bác sĩ chỉ định --}}
            <div class="card vc-card">
                <div class="card-header bg-light py-3">
                    <h6 class="mb-0"><i class="fas fa-user-md me-2"></i>Bác sĩ chỉ định</h6>
                </div>
                <div class="card-body">
                    <div class="mb-2">
                        <strong>{{ $sieuAm->bacSi->ho_ten ?? 'N/A' }}</strong>
                    </div>
                    <div class="small text-muted">
                        {{ $sieuAm->bacSi->chuyen_khoa ?? '' }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
