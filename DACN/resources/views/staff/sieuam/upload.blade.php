@extends('layouts.staff')

@section('title', 'Upload kết quả siêu âm')

@section('content')
<div class="container-fluid py-4">
    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1 fw-bold">
                <i class="fas fa-cloud-upload-alt me-2 text-primary"></i>
                Upload kết quả siêu âm #SA{{ $sieuAm->id }}
            </h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('staff.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('staff.sieuam.pending') }}">Siêu âm chờ</a></li>
                    <li class="breadcrumb-item active">Upload</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('staff.sieuam.pending') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Quay lại
        </a>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row">
        {{-- Form upload --}}
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-gradient-success text-white py-3">
                    <h5 class="mb-0"><i class="fas fa-file-upload me-2"></i>Upload kết quả</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('staff.sieuam.upload', $sieuAm->id) }}"
                          method="POST" enctype="multipart/form-data">
                        @csrf

                        {{-- File upload --}}
                        <div class="mb-4">
                            <label class="form-label fw-semibold">
                                File kết quả siêu âm <span class="text-danger">*</span>
                            </label>
                            <input type="file" name="file" class="form-control form-control-lg"
                                   accept=".pdf,.jpg,.jpeg,.png,.dcm" required>
                            <div class="form-text">
                                Định dạng: PDF, JPG, PNG, DICOM | Kích thước tối đa: 20MB
                            </div>
                        </div>

                        {{-- Kết quả --}}
                        <div class="mb-4">
                            <label class="form-label fw-semibold">
                                Kết quả siêu âm <span class="text-danger">*</span>
                            </label>
                            <textarea name="ket_qua" rows="8" class="form-control" required
                                      placeholder="Gợi ý mẫu Sản–Phụ khoa (bạn có thể chỉnh theo từng ca):

I. THÔNG TIN CHUNG
- Chỉ định: ...
- Kỹ thuật: (Đầu dò bụng/đầu dò âm đạo/Doppler...) | Tư thế: ...

II. TỬ CUNG / NỘI MẠC (phần phụ khoa)
- Tử cung: kích thước..., tư thế..., cấu trúc cơ tử cung...
- Nội mạc: dày ... mm, đều/không đều, hình ảnh...

III. PHẦN PHỤ
- Buồng trứng P/T: kích thước..., nang/noãn..., khối bất thường...
- Túi cùng Douglas: dịch ...

IV. THAI KỲ (nếu có)
- Số thai: ... | Vị trí thai: ...
- CRL/BPD/HC/AC/FL: ...
- Tim thai: ... | Nhịp: ... bpm
- Nước ối (AFI): ... | Nhau thai: vị trí..., độ..., dấu hiệu bất thường...
- CTC: chiều dài ... mm | đóng/mở...

V. KẾT LUẬN
- ...

VI. ĐỀ NGHỊ
- ...">{{ old('ket_qua') }}</textarea>
                            <div class="form-text">
                                Mô tả chi tiết các phát hiện trong quá trình siêu âm
                            </div>
                        </div>

                        {{-- Buttons --}}
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="fas fa-upload me-2"></i>Upload kết quả
                            </button>
                            <a href="{{ route('staff.sieuam.pending') }}" class="btn btn-secondary btn-lg">
                                <i class="fas fa-times me-2"></i>Hủy
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Sidebar - Thông tin siêu âm --}}
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-light py-3">
                    <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i>Thông tin siêu âm</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <small class="text-muted d-block mb-1">Mã SA</small>
                        <span class="badge bg-gradient-primary">SA-{{ $sieuAm->id }}</span>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted d-block mb-1">Bệnh nhân</small>
                        <strong>{{ $sieuAm->benhAn->user->name ?? 'N/A' }}</strong>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted d-block mb-1">Loại siêu âm</small>
                        {{ $sieuAm->loai }}
                    </div>
                    <div class="mb-3">
                        <small class="text-muted d-block mb-1">Bác sĩ chỉ định</small>
                        {{ $sieuAm->bacSi->ho_ten ?? 'N/A' }}
                    </div>
                    @if($sieuAm->mo_ta)
                    <div>
                        <small class="text-muted d-block mb-1">Yêu cầu từ bác sĩ</small>
                        <p class="small mb-0">{{ $sieuAm->mo_ta }}</p>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Hướng dẫn --}}
            <div class="card shadow-sm border-0 bg-light">
                <div class="card-body">
                    <h6 class="mb-3"><i class="fas fa-info-circle text-primary me-2"></i>Lưu ý</h6>
                    <ul class="small mb-0 ps-3">
                        <li>File phải rõ ràng, dễ đọc</li>
                        <li>Kết quả phải chi tiết, chính xác</li>
                        <li>Sau khi upload, bác sĩ sẽ được thông báo</li>
                        <li>Không thể sửa sau khi đã upload</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
