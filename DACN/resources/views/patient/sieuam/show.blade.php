{{-- filepath: resources/views/patient/sieuam/show.blade.php --}}
{{-- Parent file: app/Http/Controllers/Patient/SieuAmController.php --}}

@extends('layouts.patient-modern')

@section('title', 'Chi tiết Siêu âm')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        {{-- Main Content --}}
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="fas fa-file-medical me-2"></i>Thông tin Siêu âm</h5>
                        <span class="badge {{ $sieuAm->trang_thai_badge_class }} fs-6">
                            {{ $sieuAm->trang_thai_text }}
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th width="200">Loại siêu âm:</th>
                            <td><strong>{{ $sieuAm->loaiSieuAm->ten ?? 'N/A' }}</strong></td>
                        </tr>
                        <tr>
                            <th>Mô tả:</th>
                            <td>{{ $sieuAm->mo_ta ?: 'Không có' }}</td>
                        </tr>
                        <tr>
                            <th>Giá:</th>
                            <td><strong class="text-primary">{{ number_format($sieuAm->gia, 0, ',', '.') }} VNĐ</strong></td>
                        </tr>
                        <tr>
                            <th>Ngày tạo:</th>
                            <td>{{ $sieuAm->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                        @if($sieuAm->updated_at && $sieuAm->updated_at != $sieuAm->created_at)
                        <tr>
                            <th>Cập nhật lần cuối:</th>
                            <td>{{ $sieuAm->updated_at->format('d/m/Y H:i') }}</td>
                        </tr>
                        @endif
                    </table>
                </div>
            </div>

            {{-- Kết quả siêu âm --}}
            @if($sieuAm->trang_thai === 'completed')
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-success bg-opacity-10 py-3">
                    <h5 class="mb-0 text-success">
                        <i class="fas fa-check-circle me-2"></i>Kết quả Siêu âm
                    </h5>
                </div>
                <div class="card-body">
                    @if($sieuAm->ket_qua)
                    <div class="mb-3">
                        <label class="form-label fw-bold">Kết quả:</label>
                        <div class="alert alert-light border">
                            {!! nl2br(e($sieuAm->ket_qua)) !!}
                        </div>
                    </div>
                    @endif

                    @if($sieuAm->file_path)
                    <div class="mb-3">
                        <label class="form-label fw-bold">File đính kèm:</label>
                        <div>
                            <a href="{{ URL::temporarySignedRoute('patient.benhan.sieuam.download', now()->addMinutes(10), ['sieuAm' => $sieuAm->id]) }}"
                               class="btn btn-success">
                                <i class="fas fa-download me-2"></i>Tải xuống file kết quả
                            </a>
                        </div>
                    </div>
                    @endif

                    @if($sieuAm->nhan_xet)
                    <div class="mb-3">
                        <label class="form-label fw-bold">Nhận xét của Bác sĩ:</label>
                        <div class="alert alert-info border">
                            {!! nl2br(e($sieuAm->nhan_xet)) !!}
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            @else
            <div class="alert alert-warning">
                <i class="fas fa-info-circle me-2"></i>
                Siêu âm đang trong quá trình thực hiện. Vui lòng quay lại sau để xem kết quả.
            </div>
            @endif

            {{-- Back Button --}}
            <div class="mb-3">
                <a href="{{ route('patient.sieuam.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Quay lại danh sách
                </a>
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="col-lg-4">
            {{-- Thông tin Bác sĩ --}}
            @if($sieuAm->bacSi)
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0"><i class="fas fa-user-md me-2"></i>Bác sĩ chỉ định</h6>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        @if($sieuAm->bacSi->user->avatar)
                        <img src="{{ asset('storage/' . $sieuAm->bacSi->user->avatar) }}"
                             class="rounded-circle"
                             width="80" height="80"
                             alt="Avatar">
                        @else
                        <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center"
                             style="width: 80px; height: 80px;">
                            <i class="fas fa-user-md fa-2x text-primary"></i>
                        </div>
                        @endif
                    </div>
                    <h6 class="text-center mb-2">{{ $sieuAm->bacSi->user->ho_ten }}</h6>
                    @if($sieuAm->bacSi->chuyenKhoa)
                    <p class="text-center text-muted small mb-2">{{ $sieuAm->bacSi->chuyenKhoa->ten_chuyen_khoa }}</p>
                    @endif
                    @if($sieuAm->bacSi->user->email)
                    <p class="text-center small mb-0">
                        <i class="fas fa-envelope me-1"></i>{{ $sieuAm->bacSi->user->email }}
                    </p>
                    @endif
                </div>
            </div>
            @endif

            {{-- Thông tin Bệnh án --}}
            @if($sieuAm->benhAn)
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0"><i class="fas fa-notes-medical me-2"></i>Bệnh án liên quan</h6>
                </div>
                <div class="card-body">
                    <p class="mb-2"><strong>Mã bệnh án:</strong> #{{ $sieuAm->benhAn->id }}</p>
                    <p class="mb-2"><strong>Ngày khám:</strong> {{ $sieuAm->benhAn->created_at->format('d/m/Y') }}</p>
                    @if($sieuAm->benhAn->chan_doan)
                    <p class="mb-0"><strong>Chẩn đoán:</strong> {{ Str::limit($sieuAm->benhAn->chan_doan, 100) }}</p>
                    @endif
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
