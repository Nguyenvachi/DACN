{{-- filepath: resources/views/admin/sieuam/show.blade.php --}}
{{-- Parent file: app/Http/Controllers/Admin/SieuAmController.php --}}

@extends('layouts.admin')

@section('title', 'Chi tiết Siêu âm #' . $sieuAm->id)

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        {{-- Main Content --}}
        <div class="col-lg-8">
            {{-- Header --}}
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="mb-0"><i class="fas fa-file-medical me-2"></i>Chi tiết Siêu âm #{{ $sieuAm->id }}</h2>
                <span class="badge {{ $sieuAm->trang_thai_badge_class }} fs-6">
                    {{ $sieuAm->trang_thai_text }}
                </span>
            </div>

            {{-- Thông tin siêu âm --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">Thông tin chi tiết</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th width="200">Loại siêu âm:</th>
                            <td><strong>{{ $sieuAm->loaiSieuAm->ten ?? 'N/A' }}</strong></td>
                        </tr>
                        <tr>
                            <th>Mã loại:</th>
                            <td>{{ $sieuAm->loaiSieuAm->ma ?? 'N/A' }}</td>
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
                            <td>{{ $sieuAm->created_at->format('d/m/Y H:i:s') }}</td>
                        </tr>
                        <tr>
                            <th>Cập nhật cuối:</th>
                            <td>{{ $sieuAm->updated_at->format('d/m/Y H:i:s') }}</td>
                        </tr>
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
                            <a href="{{ URL::temporarySignedRoute('admin.benhan.sieuam.download', now()->addMinutes(10), ['sieuAm' => $sieuAm->id]) }}"
                               class="btn btn-success">
                                <i class="fas fa-download me-2"></i>Tải xuống file kết quả
                            </a>
                            <span class="text-muted ms-3">Disk: {{ $sieuAm->disk }}</span>
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
            @endif

            {{-- Actions --}}
            <div class="mb-3">
                <a href="{{ route('admin.sieuam.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Quay lại danh sách
                </a>

                <form action="{{ route('admin.sieuam.destroy', $sieuAm) }}"
                      method="POST"
                      class="d-inline"
                      onsubmit="return confirm('Bạn có chắc muốn xóa siêu âm này? File kết quả cũng sẽ bị xóa.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash me-2"></i>Xóa siêu âm
                    </button>
                </form>
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="col-lg-4">
            {{-- Thông tin Bệnh nhân --}}
            @if($sieuAm->benhAn && $sieuAm->benhAn->user)
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0"><i class="fas fa-user me-2"></i>Bệnh nhân</h6>
                </div>
                <div class="card-body">
                    <p class="mb-2"><strong>Họ tên:</strong> {{ $sieuAm->benhAn->user->ho_ten }}</p>
                    <p class="mb-2"><strong>Email:</strong> {{ $sieuAm->benhAn->user->email }}</p>
                    @if($sieuAm->benhAn->user->phone)
                    <p class="mb-2"><strong>SĐT:</strong> {{ $sieuAm->benhAn->user->phone }}</p>
                    @endif
                    @if($sieuAm->benhAn->user->date_of_birth)
                    <p class="mb-0"><strong>Ngày sinh:</strong> {{ \Carbon\Carbon::parse($sieuAm->benhAn->user->date_of_birth)->format('d/m/Y') }}</p>
                    @endif
                </div>
            </div>
            @endif

            {{-- Thông tin Bác sĩ --}}
            @if($sieuAm->bacSi)
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0"><i class="fas fa-user-md me-2"></i>Bác sĩ chỉ định</h6>
                </div>
                <div class="card-body">
                    <p class="mb-2"><strong>Họ tên:</strong> {{ $sieuAm->bacSi->user->ho_ten }}</p>
                    @if($sieuAm->bacSi->chuyenKhoa)
                    <p class="mb-2"><strong>Chuyên khoa:</strong> {{ $sieuAm->bacSi->chuyenKhoa->ten_chuyen_khoa }}</p>
                    @endif
                    <p class="mb-0"><strong>Email:</strong> {{ $sieuAm->bacSi->user->email }}</p>
                </div>
            </div>
            @endif

            {{-- Thông tin Bệnh án --}}
            @if($sieuAm->benhAn)
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0"><i class="fas fa-notes-medical me-2"></i>Bệnh án</h6>
                </div>
                <div class="card-body">
                    <p class="mb-2"><strong>Mã:</strong> #{{ $sieuAm->benhAn->id }}</p>
                    <p class="mb-2"><strong>Ngày tạo:</strong> {{ $sieuAm->benhAn->created_at->format('d/m/Y H:i') }}</p>
                    @if($sieuAm->benhAn->chan_doan)
                    <p class="mb-0"><strong>Chẩn đoán:</strong><br>{{ $sieuAm->benhAn->chan_doan }}</p>
                    @endif
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
