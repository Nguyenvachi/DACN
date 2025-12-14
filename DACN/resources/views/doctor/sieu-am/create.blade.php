@extends('layouts.doctor')

@section('title', 'Chỉ định siêu âm')

@section('content')
<div class="container-fluid py-4">
    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1 fw-bold" style="color: #1f2937;">
                <i class="fas fa-baby me-2" style="color: #ec4899;"></i>
                Chỉ định siêu âm thai
            </h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('doctor.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('doctor.benhan.edit', $benhAn->id) }}">Bệnh án #{{ $benhAn->id }}</a></li>
                    <li class="breadcrumb-item active">Chỉ định siêu âm</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('doctor.benhan.edit', $benhAn->id) }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Quay lại
        </a>
    </div>

    <div class="row">
        <div class="col-lg-8">
            {{-- Thông tin bệnh nhân --}}
            <div class="card vc-card mb-4">
                <div class="card-body">
                    <h5 class="card-title mb-3">
                        <i class="fas fa-user-injured me-2" style="color: #ec4899;"></i>
                        Thông tin bệnh nhân
                    </h5>
                    <div class="row">
                        <div class="col-md-6">
                            <p class="mb-2"><strong>Họ tên:</strong> {{ $benhAn->user->name }}</p>
                            <p class="mb-2"><strong>Mã BN:</strong> #{{ $benhAn->user->id }}</p>
                            <p class="mb-2"><strong>Ngày khám:</strong> {{ \Carbon\Carbon::parse($benhAn->ngay_kham)->format('d/m/Y H:i') }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-2"><strong>Chẩn đoán:</strong> {{ $benhAn->chuan_doan ?? 'Chưa có' }}</p>
                            <p class="mb-2"><strong>Dịch vụ:</strong> {{ $benhAn->lichHen->dichVu->ten_dich_vu ?? 'N/A' }}</p>
                            <p class="mb-2"><strong>Trạng thái:</strong>
                                <span class="vc-badge vc-badge-{{ $benhAn->lichHen->trang_thai === 'Hoàn thành' ? 'success' : 'warning' }}">
                                    {{ $benhAn->lichHen->trang_thai }}
                                </span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Form chỉ định --}}
            <div class="card vc-card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-stethoscope me-2" style="color: #10b981;"></i>
                        Chỉ định siêu âm
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('doctor.sieu-am.store', $benhAn->id) }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Loại siêu âm <span class="text-danger">*</span></label>
                            <select name="loai_sieu_am_id" class="form-select" required>
                                <option value="">-- Chọn loại siêu âm --</option>
                                @foreach($loaiSieuAms as $loai)
                                <option value="{{ $loai->id }}" 
                                        data-gia="{{ number_format($loai->gia_tien, 0, ',', '.') }}"
                                        {{ old('loai_sieu_am_id') == $loai->id ? 'selected' : '' }}>
                                    {{ $loai->ten }} - {{ number_format($loai->gia_tien, 0, ',', '.') }} VNĐ
                                </option>
                                @endforeach
                            </select>
                            @error('loai_sieu_am_id')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Lý do chỉ định</label>
                            <textarea name="ly_do_chi_dinh" class="form-control" rows="3"
                                      placeholder="VD: Theo dõi sự phát triển của thai nhi, kiểm tra dị tật...">{{ old('ly_do_chi_dinh') }}</textarea>
                            <small class="text-muted">Ghi rõ mục đích siêu âm để kỹ thuật viên có thể tập trung quan sát</small>
                            @error('ly_do_chi_dinh')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Ghi chú</label>
                            <textarea name="ghi_chu" class="form-control" rows="2"
                                      placeholder="Ghi chú thêm (nếu có)...">{{ old('ghi_chu') }}</textarea>
                            @error('ghi_chu')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex gap-2 justify-content-end">
                            <a href="{{ route('doctor.benhan.edit', $benhAn->id) }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-2"></i>Hủy
                            </a>
                            <button type="submit" class="btn vc-btn-primary">
                                <i class="fas fa-check me-2"></i>Chỉ định siêu âm
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            {{-- Danh sách siêu âm đã chỉ định --}}
            <div class="card vc-card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-list-alt me-2" style="color: #6366f1;"></i>
                        Siêu âm đã chỉ định
                    </h5>
                </div>
                <div class="card-body">
                    @if($existingSieuAm->isEmpty())
                    <div class="text-center py-4">
                        <i class="fas fa-baby fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Chưa có siêu âm nào</p>
                    </div>
                    @else
                    <div class="list-group list-group-flush">
                        @foreach($existingSieuAm as $sieuAm)
                        <div class="list-group-item px-0">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h6 class="mb-0">{{ $sieuAm->loai_sieu_am }}</h6>
                                @if($sieuAm->trang_thai === 'Đã có kết quả')
                                <span class="badge bg-success">
                                    <i class="fas fa-check me-1"></i>Đã có KQ
                                </span>
                                @elseif($sieuAm->trang_thai === 'Đang thực hiện')
                                <span class="badge bg-warning">
                                    <i class="fas fa-clock me-1"></i>Đang thực hiện
                                </span>
                                @else
                                <span class="badge bg-secondary">
                                    <i class="fas fa-hourglass-start me-1"></i>Chờ thực hiện
                                </span>
                                @endif
                            </div>

                            @if($sieuAm->ly_do_chi_dinh)
                            <p class="mb-2 small text-muted">{{ Str::limit($sieuAm->ly_do_chi_dinh, 80) }}</p>
                            @endif

                            <small class="text-muted">
                                <i class="far fa-calendar me-1"></i>
                                {{ \Carbon\Carbon::parse($sieuAm->ngay_chi_dinh)->format('d/m/Y H:i') }}
                            </small>

                            @if($sieuAm->gia_tien)
                            <div class="mt-2">
                                <span class="badge bg-info">
                                    <i class="fas fa-money-bill-wave me-1"></i>
                                    {{ number_format($sieuAm->gia_tien, 0, ',', '.') }} VNĐ
                                </span>
                                @if($sieuAm->trang_thai_thanh_toan === 'Đã thanh toán')
                                <span class="badge bg-success">
                                    <i class="fas fa-check-circle me-1"></i>Đã thanh toán
                                </span>
                                @else
                                <span class="badge bg-warning">
                                    <i class="fas fa-clock me-1"></i>Chưa thanh toán
                                </span>
                                @endif
                            </div>
                            @endif

                            @if($sieuAm->trang_thai === 'Đã có kết quả')
                            <div class="mt-2">
                                <a href="{{ route('doctor.sieu-am.edit', $sieuAm->id) }}" 
                                   class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye me-1"></i>Xem kết quả
                                </a>
                            </div>
                            @endif
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>

            {{-- Hướng dẫn --}}
            <div class="card vc-card mt-3">
                <div class="card-body">
                    <h6 class="card-title">
                        <i class="fas fa-info-circle me-2" style="color: #10b981;"></i>
                        Lưu ý
                    </h6>
                    <ul class="mb-0 small">
                        <li>Chọn loại siêu âm phù hợp với tuổi thai và mục đích khám</li>
                        <li>Ghi rõ lý do chỉ định để kỹ thuật viên có thể tập trung quan sát</li>
                        <li>Kết quả siêu âm sẽ được cập nhật bởi kỹ thuật viên siêu âm</li>
                        <li>Chi phí siêu âm sẽ được tính vào tổng chi phí điều trị</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
