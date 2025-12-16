@extends('layouts.doctor')

@section('title', 'Chỉnh sửa bệnh án #' . $record->id)

@section('content')
<div class="container-fluid py-4">
    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1 fw-bold" style="color: #1f2937;">
                <i class="fas fa-file-medical me-2" style="color: #10b981;"></i>
                Bệnh án #{{ $record->id }}
            </h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('doctor.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('doctor.benhan.index') }}">Bệnh án</a></li>
                    <li class="breadcrumb-item active">Chỉnh sửa #{{ $record->id }}</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2">
            @if($record->trang_thai === 'Đang khám')
            <button type="button" class="btn btn-success" id="completeExamBtn">
                <i class="fas fa-check-circle me-2"></i>Hoàn thành khám
            </button>
            @endif
            <a href="{{ route('doctor.benhan.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Quay lại
            </a>
        </div>
    </div>

    {{-- Thông tin bệnh nhân --}}
    <div class="card vc-card mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <h5 class="mb-3">
                        <i class="fas fa-user-injured me-2" style="color: #3b82f6;"></i>
                        {{ $record->user->name ?? 'N/A' }}
                    </h5>
                    <div class="row">
                        <div class="col-md-4">
                            <p class="mb-2"><strong>Mã BN:</strong> #{{ $record->user_id }}</p>
                            <p class="mb-2"><strong>Giới tính:</strong> {{ $record->user->gioi_tinh ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-4">
                            <p class="mb-2"><strong>Năm sinh:</strong> {{ \Carbon\Carbon::parse($record->user->ngay_sinh ?? now())->format('Y') }}</p>
                            <p class="mb-2"><strong>SĐT:</strong> {{ $record->user->so_dien_thoai ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-4">
                            <p class="mb-2"><strong>Ngày khám:</strong> {{ \Carbon\Carbon::parse($record->ngay_kham)->format('d/m/Y H:i') }}</p>
                            @if($record->lichHen)
                            <p class="mb-2"><strong>Trạng thái:</strong>
                                <span class="vc-badge vc-badge-{{ $record->lichHen->trang_thai === 'Hoàn thành' ? 'success' : 'warning' }}">
                                    {{ $record->lichHen->trang_thai }}
                                </span>
                            </p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            {{-- Form chỉnh sửa bệnh án --}}
            <div class="card vc-card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-edit me-2" style="color: #10b981;"></i>
                        Thông tin bệnh án
                    </h5>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                    <div class="alert alert-danger mb-4">
                        <strong>Vui lòng kiểm tra lại:</strong>
                        <ul class="mb-0 mt-2">
                            @foreach ($errors->all() as $e)
                            <li>{{ $e }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <form method="POST" enctype="multipart/form-data" action="{{ route('doctor.benhan.update', $record) }}" id="benhAnForm">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Tiêu đề</label>
                            <input type="text" name="tieu_de" class="form-control bg-light" 
                                   value="{{ $record->lichHen && $record->lichHen->dichVu ? $record->lichHen->dichVu->ten_dich_vu : old('tieu_de', $record->tieu_de) }}" 
                                   readonly required>
                            <small class="text-muted">Tiêu đề tự động lấy từ dịch vụ đã chọn</small>
                        </div>

                        {{-- Chỉ số sinh tồn --}}
                        <div class="mb-4">
                            @php
                                $isAssignedDoctor = auth()->user()->bacSi && auth()->user()->bacSi->id == $record->bac_si_id;
                            @endphp
                            <h5 class="mb-3">
                                <i class="bi bi-heart-pulse text-danger me-2"></i>Chỉ số sinh tồn
                                @if(!$isAssignedDoctor)
                                    <span class="badge bg-secondary">Chỉ xem</span>
                                @endif
                            </h5>
                            @if(!$isAssignedDoctor)
                                <div class="alert alert-warning mb-3">
                                    <i class="bi bi-lock me-2"></i>Chỉ bác sĩ khám ({{ $record->bacSi->ho_ten ?? 'N/A' }}) mới có thể nhập chỉ số sinh tồn.
                                </div>
                            @endif
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <label class="form-label fw-semibold">Huyết áp</label>
                                    <input type="text" name="huyet_ap" class="form-control{{ !$isAssignedDoctor ? ' bg-light' : '' }}" 
                                           placeholder="VD: 120/80" 
                                           value="{{ old('huyet_ap', $record->huyet_ap) }}"
                                           {{ !$isAssignedDoctor ? 'readonly' : '' }}>
                                    <small class="text-muted">mmHg</small>
                                </div>
                                
                                <div class="col-md-3">
                                    <label class="form-label fw-semibold">Nhịp tim</label>
                                    <input type="number" name="nhip_tim" class="form-control{{ !$isAssignedDoctor ? ' bg-light' : '' }}" 
                                           placeholder="VD: 75" 
                                           value="{{ old('nhip_tim', $record->nhip_tim) }}"
                                           {{ !$isAssignedDoctor ? 'readonly' : '' }}>
                                    <small class="text-muted">lần/phút</small>
                                </div>
                                
                                <div class="col-md-3">
                                    <label class="form-label fw-semibold">Nhiệt độ</label>
                                    <input type="number" name="nhiet_do" class="form-control{{ !$isAssignedDoctor ? ' bg-light' : '' }}" 
                                           step="0.1" 
                                           placeholder="VD: 36.5" 
                                           value="{{ old('nhiet_do', $record->nhiet_do) }}"
                                           {{ !$isAssignedDoctor ? 'readonly' : '' }}>
                                    <small class="text-muted">°C</small>
                                </div>
                                
                                <div class="col-md-3">
                                    <label class="form-label fw-semibold">Nhịp thở</label>
                                    <input type="number" name="nhip_tho" class="form-control{{ !$isAssignedDoctor ? ' bg-light' : '' }}" 
                                           placeholder="VD: 18" 
                                           value="{{ old('nhip_tho', $record->nhip_tho) }}"
                                           {{ !$isAssignedDoctor ? 'readonly' : '' }}>
                                    <small class="text-muted">lần/phút</small>
                                </div>
                                
                                <div class="col-md-3">
                                    <label class="form-label fw-semibold">Cân nặng</label>
                                    <input type="number" name="can_nang" class="form-control{{ !$isAssignedDoctor ? ' bg-light' : '' }}" 
                                           step="0.01" 
                                           placeholder="VD: 65.5" 
                                           value="{{ old('can_nang', $record->can_nang) }}"
                                           id="can_nang_doctor"
                                           {{ !$isAssignedDoctor ? 'readonly' : '' }}>
                                    <small class="text-muted">kg</small>
                                </div>
                                
                                <div class="col-md-3">
                                    <label class="form-label fw-semibold">Chiều cao</label>
                                    <input type="number" name="chieu_cao" class="form-control{{ !$isAssignedDoctor ? ' bg-light' : '' }}" 
                                           step="0.01" 
                                           placeholder="VD: 170" 
                                           value="{{ old('chieu_cao', $record->chieu_cao) }}"
                                           id="chieu_cao_doctor"
                                           {{ !$isAssignedDoctor ? 'readonly' : '' }}>
                                    <small class="text-muted">cm</small>
                                </div>
                                
                                <div class="col-md-3">
                                    <label class="form-label fw-semibold">BMI</label>
                                    <input type="number" name="bmi" class="form-control" 
                                           step="0.1" 
                                           placeholder="Tự động tính" 
                                           value="{{ old('bmi', $record->bmi) }}"
                                           id="bmi_doctor"
                                           readonly>
                                    <small class="text-muted">kg/m²</small>
                                </div>
                                
                                <div class="col-md-3">
                                    <label class="form-label fw-semibold">SpO2</label>
                                    <input type="number" name="spo2" class="form-control{{ !$isAssignedDoctor ? ' bg-light' : '' }}" 
                                           placeholder="VD: 98" 
                                           value="{{ old('spo2', $record->spo2) }}"
                                           {{ !$isAssignedDoctor ? 'readonly' : '' }}>
                                    <small class="text-muted">%</small>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Triệu chứng</label>
                            <textarea name="trieu_chung" class="form-control" rows="4"
                                      placeholder="Mô tả triệu chứng của bệnh nhân...">{{ old('trieu_chung', $record->trieu_chung) }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold" >Chẩn đoán</label>
                            <textarea name="chuan_doan" class="form-control" rows="4"
                                      placeholder="Chẩn đoán bệnh...">{{ old('chuan_doan', $record->chuan_doan) }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Hướng điều trị</label>
                            <textarea name="dieu_tri" class="form-control" rows="4"
                                      placeholder="Phương pháp điều trị, lời khuyên...">{{ old('dieu_tri', $record->dieu_tri) }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Ghi chú</label>
                            <textarea name="ghi_chu" class="form-control" rows="3"
                                      placeholder="Ghi chú khác...">{{ old('ghi_chu', $record->ghi_chu) }}</textarea>
                        </div>

                        {{-- Lịch hẹn tái khám --}}
                        <div class="card border-primary mb-3">
                            <div class="card-header bg-primary text-white">
                                <h6 class="mb-0"><i class="fas fa-calendar-check me-2"></i>Lịch hẹn tái khám</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Ngày hẹn tái khám</label>
                                        <input type="date" name="ngay_hen_tai_kham" class="form-control" 
                                               value="{{ old('ngay_hen_tai_kham', $record->ngay_hen_tai_kham ? $record->ngay_hen_tai_kham->format('Y-m-d') : '') }}"
                                               min="{{ date('Y-m-d') }}">
                                        <small class="text-muted">Để trống nếu không cần tái khám</small>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Lý do tái khám</label>
                                        <input type="text" name="ly_do_tai_kham" class="form-control" 
                                               value="{{ old('ly_do_tai_kham', $record->ly_do_tai_kham) }}"
                                               placeholder="VD: Kiểm tra lại, xem kết quả XN...">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Tệp đính kèm mới</label>
                            <input type="file" name="files[]" multiple class="form-control">
                            <small class="text-muted">Chọn nhiều file nếu cần (ảnh, PDF...)</small>
                        </div>

                        {{-- Hidden fields --}}
                        <input type="hidden" name="user_id" value="{{ $record->user_id }}">
                        <input type="hidden" name="bac_si_id" value="{{ $record->bac_si_id }}">
                        <input type="hidden" name="lich_hen_id" value="{{ $record->lich_hen_id }}">
                        <input type="hidden" name="ngay_kham" value="{{ \Carbon\Carbon::parse($record->ngay_kham)->format('Y-m-d') }}">

                        <div class="d-flex gap-2 justify-content-end mt-4">
                            <a href="{{ route('doctor.benhan.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-2"></i>Hủy
                            </a>
                            <button type="submit" class="btn vc-btn-primary">
                                <i class="fas fa-save me-2"></i>Lưu thay đổi
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Files đã upload --}}
            @if($record->files && $record->files->count() > 0)
            <div class="card vc-card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-paperclip me-2" style="color: #6366f1;"></i>
                        Files đã tải lên ({{ $record->files->count() }})
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        @foreach($record->files as $file)
                        <div class="col-md-4">
                            <div class="card h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <i class="fas fa-file fa-2x" style="color: #3b82f6;"></i>
                                        <form action="{{ route('doctor.benhan.files.destroy', [$record, $file]) }}"
                                              method="POST"
                                              onsubmit="return confirm('Xóa file này?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                    <h6 class="card-title small">{{ Str::limit($file->file_name, 25) }}</h6>
                                    <p class="card-text small text-muted mb-2">
                                        {{ number_format($file->file_size / 1024, 2) }} KB
                                    </p>
                                    <a href="{{ route('doctor.benhan.files.download', $file) }}"
                                       class="btn btn-sm btn-outline-primary w-100">
                                        <i class="fas fa-download me-1"></i>Tải về
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        </div>

        <div class="col-lg-4">
            {{-- Đơn thuốc --}}
            <div class="card vc-card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-prescription-bottle-alt me-2" style="color: #10b981;"></i>
                        Đơn thuốc
                    </h5>
                    <a href="{{ route('doctor.donthuoc.create', $record->id) }}" class="btn btn-sm vc-btn-primary">
                        <i class="fas fa-plus"></i>
                    </a>
                </div>
                <div class="card-body">
                    @php
                        $donThuoc = App\Models\DonThuoc::where('benh_an_id', $record->id)->with('items.thuoc')->first();
                    @endphp

                    @if($donThuoc)
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle me-2"></i>
                        <strong>Đã kê {{ $donThuoc->items->count() }} loại thuốc</strong>
                    </div>
                    <ul class="list-unstyled mb-3">
                        @foreach($donThuoc->items as $item)
                        <li class="mb-2">
                            <i class="fas fa-pills me-2" style="color: #10b981;"></i>
                            <strong>{{ $item->thuoc->ten }}</strong><br>
                            <small class="text-muted ms-4">
                                SL: {{ $item->so_luong }} | {{ $item->lieu_dung }}
                            </small>
                        </li>
                        @endforeach
                    </ul>
                    <div class="d-grid gap-2">
                        <a href="{{ route('doctor.donthuoc.show', $donThuoc->id) }}"
                           class="btn btn-sm btn-outline-primary" target="_blank">
                            <i class="fas fa-eye me-2"></i>Xem đơn đầy đủ
                        </a>
                        <a href="{{ route('doctor.donthuoc.create', $record->id) }}"
                           class="btn btn-sm btn-outline-warning">
                            <i class="fas fa-edit me-2"></i>Sửa đơn
                        </a>
                    </div>
                    @else
                    <div class="text-center py-4">
                        <i class="fas fa-prescription-bottle-alt fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Chưa kê đơn thuốc</p>
                        <a href="{{ route('doctor.donthuoc.create', $record->id) }}" class="btn btn-sm vc-btn-primary">
                            <i class="fas fa-plus me-2"></i>Kê đơn ngay
                        </a>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Xét nghiệm --}}
            {{-- Xét nghiệm --}}
            <div class="card vc-card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-flask me-2" style="color: #3b82f6;"></i>
                        Xét nghiệm
                    </h5>
                    <a href="{{ route('doctor.xet-nghiem.create', ['benh_an_id' => $record->id]) }}" class="btn btn-sm vc-btn-primary">
                        <i class="fas fa-plus"></i>
                    </a>
                </div>
                <div class="card-body">
                    @php
                        $xetNghiems = App\Models\XetNghiem::where('benh_an_id', $record->id)->get();
                    @endphp

                    @if($xetNghiems->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($xetNghiems as $xn)
                        <div class="list-group-item px-0">
                            <div class="d-flex justify-content-between align-items-start mb-1">
                                <h6 class="mb-0 small">{{ $xn->loai ?? $xn->loai_xet_nghiem ?? 'Xét nghiệm' }}</h6>
                                <div>
                                    @if(in_array($xn->trang_thai, ['Có kết quả', 'Đã có kết quả', 'completed']) || $xn->trang_thai_xn === 'Đã có kết quả' || $xn->chi_so)
                                    <span class="badge bg-success"><i class="fas fa-check me-1"></i>Hoàn thành</span>
                                    @elseif($xn->trang_thai_xn === 'Đang xử lý' || $xn->trang_thai === 'processing' || $xn->trang_thai === 'Đang xét nghiệm')
                                    <span class="badge bg-warning"><i class="fas fa-clock me-1"></i>Đang xử lý</span>
                                    @else
                                    <span class="badge bg-secondary"><i class="fas fa-hourglass-start me-1"></i>Chờ thực hiện</span>
                                    @endif
                                    @if($xn->gia_tien)
                                    <span class="badge bg-info ms-1">{{ number_format($xn->gia_tien, 0, ',', '.') }} đ</span>
                                    @endif
                                </div>
                            </div>
                            @if($xn->mo_ta)
                            <p class="mb-2 small text-muted">{{ Str::limit($xn->mo_ta, 60) }}</p>
                            @endif
                            <div class="d-flex gap-2">
                                @if(in_array($xn->trang_thai, ['Có kết quả', 'Đã có kết quả', 'completed']) || $xn->trang_thai_xn === 'Đã có kết quả' || $xn->chi_so)
                                <a href="{{ route('doctor.xet-nghiem.show', $xn->id) }}" class="btn btn-sm btn-success">
                                    <i class="fas fa-eye me-1"></i>Xem chi tiết
                                </a>
                                @else
                                <a href="{{ route('doctor.xet-nghiem.edit', $xn->id) }}" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit me-1"></i>Nhập kết quả
                                </a>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="text-center py-4">
                        <i class="fas fa-flask fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Chưa chỉ định XN</p>
                        <a href="{{ route('doctor.xet-nghiem.create', ['benh_an_id' => $record->id]) }}" class="btn btn-sm vc-btn-primary">
                            <i class="fas fa-plus me-2"></i>Chỉ định ngay
                        </a>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Siêu âm --}}
            <div class="card vc-card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-baby me-2" style="color: #ec4899;"></i>
                        Siêu âm thai
                    </h5>
                    <a href="{{ route('doctor.sieu-am.create', $record->id) }}" class="btn btn-sm vc-btn-primary">
                        <i class="fas fa-plus"></i>
                    </a>
                </div>
                <div class="card-body">
                    @php
                        $sieuAms = $record->sieuAms ?? collect();
                    @endphp

                    @if($sieuAms->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($sieuAms as $sa)
                        <div class="list-group-item px-0">
                            <div class="d-flex justify-content-between align-items-start mb-1">
                                <h6 class="mb-0 small">{{ $sa->loai_sieu_am }}</h6>
                                <div>
                                    @if($sa->trang_thai === 'Đã có kết quả' || $sa->trang_thai === 'Hoàn thành' || $sa->ket_qua)
                                    <span class="badge bg-success"><i class="fas fa-check me-1"></i>Hoàn thành</span>
                                    @elseif($sa->trang_thai === 'Đang thực hiện')
                                    <span class="badge bg-warning"><i class="fas fa-clock me-1"></i>Đang thực hiện</span>
                                    @else
                                    <span class="badge bg-secondary"><i class="fas fa-hourglass-start me-1"></i>Chờ thực hiện</span>
                                    @endif
                                    @if($sa->gia_tien)
                                    <span class="badge bg-info ms-1">{{ number_format($sa->gia_tien, 0, ',', '.') }} đ</span>
                                    @endif
                                </div>
                            </div>
                            @if($sa->ly_do_chi_dinh)
                            <p class="mb-2 small text-muted">{{ Str::limit($sa->ly_do_chi_dinh, 60) }}</p>
                            @endif
                            @if($sa->tuoi_thai_tuan)
                            <small class="text-muted">
                                <i class="fas fa-calendar-alt me-1"></i>Tuổi thai: {{ $sa->tuoi_thai_tuan }} tuần 
                                @if($sa->tuoi_thai_ngay) {{ $sa->tuoi_thai_ngay }} ngày @endif
                            </small>
                            @endif
                            <div class="mt-2 d-flex gap-2">
                                @if($sa->trang_thai === 'Đã có kết quả' || $sa->trang_thai === 'Hoàn thành' || $sa->ket_qua)
                                <a href="{{ route('doctor.sieu-am.show', $sa->id) }}" class="btn btn-sm btn-success">
                                    <i class="fas fa-eye me-1"></i>Xem chi tiết
                                </a>
                                @else
                                <a href="{{ route('doctor.sieu-am.edit', $sa->id) }}" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit me-1"></i>Nhập kết quả
                                </a>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="text-center py-4">
                        <i class="fas fa-baby fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Chưa chỉ định siêu âm</p>
                        <a href="{{ route('doctor.sieu-am.create', $record->id) }}" class="btn btn-sm vc-btn-primary">
                            <i class="fas fa-plus me-2"></i>Chỉ định ngay
                        </a>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Theo dõi thai kỳ --}}
            <div class="card vc-card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-heartbeat me-2" style="color: #ec4899;"></i>
                        Theo dõi thai kỳ
                    </h5>
                    <a href="{{ route('doctor.theo-doi-thai-ky.create', ['benh_an_id' => $record->id]) }}" class="btn btn-sm vc-btn-primary">
                        <i class="fas fa-plus"></i>
                    </a>
                </div>
                <div class="card-body">
                    @php
                        $theoDoiThaiKys = $record->theoDoiThaiKy ?? collect();
                    @endphp

                    @if($theoDoiThaiKys->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($theoDoiThaiKys as $tdtk)
                        <div class="list-group-item px-0">
                            <div class="d-flex justify-content-between align-items-start mb-1">
                                <h6 class="mb-0 small">Ngày khám: {{ \Carbon\Carbon::parse($tdtk->ngay_kham)->format('d/m/Y') }}</h6>
                                <div>
                                    @if($tdtk->trang_thai_thanh_toan === 'Đã thanh toán')
                                    <span class="badge bg-success"><i class="fas fa-check-circle me-1"></i>Đã thanh toán</span>
                                    @else
                                    <span class="badge bg-warning"><i class="fas fa-clock me-1"></i>Chưa thanh toán</span>
                                    @endif
                                    @if($tdtk->goi_dich_vu)
                                    <span class="badge bg-info ms-1">{{ $tdtk->goi_dich_vu }}</span>
                                    @endif
                                    @if($tdtk->gia_tien)
                                    <span class="badge bg-primary ms-1">{{ number_format($tdtk->gia_tien, 0, ',', '.') }} đ</span>
                                    @endif
                                </div>
                            </div>
                            @if($tdtk->tuoi_thai)
                            <p class="mb-1 small text-muted">
                                <i class="fas fa-calendar-alt me-1"></i>Tuổi thai: {{ $tdtk->tuoi_thai }} tuần
                            </p>
                            @endif
                            @if($tdtk->can_nang)
                            <p class="mb-1 small text-muted">
                                <i class="fas fa-weight me-1"></i>Cân nặng: {{ $tdtk->can_nang }} kg
                            </p>
                            @endif
                            @if($tdtk->huyet_ap)
                            <p class="mb-1 small text-muted">
                                <i class="fas fa-heartbeat me-1"></i>Huyết áp: {{ $tdtk->huyet_ap }}
                            </p>
                            @endif
                            @if($tdtk->tim_thai)
                            <p class="mb-2 small text-muted">
                                <i class="fas fa-heart me-1"></i>Tim thai: {{ $tdtk->tim_thai }} nhịp/phút
                            </p>
                            @endif
                            <div class="mt-2 d-flex gap-2">
                                <a href="{{ route('doctor.theo-doi-thai-ky.show', $tdtk->id) }}" class="btn btn-sm btn-success">
                                    <i class="fas fa-eye me-1"></i>Xem chi tiết
                                </a>
                                <a href="{{ route('doctor.theo-doi-thai-ky.edit', $tdtk->id) }}" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit me-1"></i>Cập nhật
                                </a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="text-center py-4">
                        <i class="fas fa-heartbeat fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Chưa có theo dõi thai kỳ</p>
                        <a href="{{ route('doctor.theo-doi-thai-ky.create', ['benh_an_id' => $record->id]) }}" class="btn btn-sm vc-btn-primary">
                            <i class="fas fa-plus me-2"></i>Thêm theo dõi ngay
                        </a>
                    </div>
                    @endif
                </div>
            </div>

            {{-- X-quang --}}
            <div class="card vc-card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-x-ray me-2" style="color: #f59e0b;"></i>
                        X-quang
                    </h5>
                    <a href="{{ route('doctor.x-quang.create', $record->id) }}" class="btn btn-sm vc-btn-primary">
                        <i class="fas fa-plus"></i>
                    </a>
                </div>
                <div class="card-body">
                    @php
                        $xQuangs = $record->xQuangs ?? collect();
                    @endphp

                    @if($xQuangs->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($xQuangs as $xq)
                        <div class="list-group-item px-0">
                            <div class="d-flex justify-content-between align-items-start mb-1">
                                <h6 class="mb-0 small">{{ $xq->loai_x_quang }}</h6>
                                <div>
                                    @if($xq->trang_thai === 'Đã có kết quả' || $xq->trang_thai === 'Hoàn thành' || $xq->ket_qua)
                                    <span class="badge bg-success"><i class="fas fa-check me-1"></i>Hoàn thành</span>
                                    @elseif($xq->trang_thai === 'Đang thực hiện' || $xq->trang_thai === 'Đang chụp')
                                    <span class="badge bg-warning"><i class="fas fa-clock me-1"></i>Đang thực hiện</span>
                                    @else
                                    <span class="badge bg-secondary"><i class="fas fa-hourglass-start me-1"></i>Chờ chụp</span>
                                    @endif
                                    @if($xq->gia_tien)
                                    <span class="badge bg-info ms-1">{{ number_format($xq->gia_tien, 0, ',', '.') }} đ</span>
                                    @endif
                                </div>
                            </div>
                            @if($xq->vi_tri)
                            <p class="mb-2 small text-muted">Vị trí: {{ $xq->vi_tri }}</p>
                            @endif
                            @if($xq->chi_dinh)
                            <p class="mb-2 small text-muted">{{ Str::limit($xq->chi_dinh, 60) }}</p>
                            @endif
                            <div class="mt-2 d-flex gap-2">
                                @if($xq->trang_thai === 'Đã có kết quả' || $xq->trang_thai === 'Hoàn thành' || $xq->ket_qua)
                                <a href="{{ route('doctor.x-quang.show', $xq->id) }}" class="btn btn-sm btn-success">
                                    <i class="fas fa-eye me-1"></i>Xem chi tiết
                                </a>
                                @else
                                <a href="{{ route('doctor.x-quang.edit', $xq->id) }}" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit me-1"></i>Nhập kết quả
                                </a>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="text-center py-4">
                        <i class="fas fa-x-ray fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Chưa chỉ định X-quang</p>
                        <a href="{{ route('doctor.x-quang.create', $record->id) }}" class="btn btn-sm vc-btn-primary">
                            <i class="fas fa-plus me-2"></i>Chỉ định ngay
                        </a>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Nội soi --}}
            <div class="card vc-card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-microscope me-2" style="color: #10b981;"></i>
                        Nội soi
                    </h5>
                    <a href="{{ route('doctor.noi-soi.create', $record->id) }}" class="btn btn-sm vc-btn-primary">
                        <i class="fas fa-plus"></i>
                    </a>
                </div>
                <div class="card-body">
                    @php
                        $noiSois = $record->noiSois ?? collect();
                    @endphp

                    @if($noiSois->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($noiSois as $ns)
                        <div class="list-group-item px-0">
                            <div class="d-flex justify-content-between align-items-start mb-1">
                                <h6 class="mb-0 small">{{ $ns->loai_noi_soi }}</h6>
                                <div>
                                    @if($ns->trang_thai === 'Đã có kết quả' || $ns->trang_thai === 'Hoàn thành' || $ns->ket_qua)
                                    <span class="badge bg-success"><i class="fas fa-check me-1"></i>Hoàn thành</span>
                                    @elseif($ns->trang_thai === 'Đang thực hiện')
                                    <span class="badge bg-warning"><i class="fas fa-clock me-1"></i>Đang thực hiện</span>
                                    @else
                                    <span class="badge bg-secondary"><i class="fas fa-hourglass-start me-1"></i>Chờ thực hiện</span>
                                    @endif
                                    @if($ns->gia_tien)
                                    <span class="badge bg-info ms-1">{{ number_format($ns->gia_tien, 0, ',', '.') }} đ</span>
                                    @endif
                                </div>
                            </div>
                            @if($ns->chi_dinh)
                            <p class="mb-2 small text-muted">{{ Str::limit($ns->chi_dinh, 60) }}</p>
                            @endif
                            @if($ns->chuan_bi)
                            <small class="text-muted"><i class="fas fa-info-circle me-1"></i>Chuẩn bị: {{ Str::limit($ns->chuan_bi, 40) }}</small>
                            @endif
                            <div class="mt-2 d-flex gap-2">
                                @if($ns->trang_thai === 'Đã có kết quả' || $ns->trang_thai === 'Hoàn thành' || $ns->ket_qua)
                                <a href="{{ route('doctor.noi-soi.show', $ns->id) }}" class="btn btn-sm btn-success">
                                    <i class="fas fa-eye me-1"></i>Xem chi tiết
                                </a>
                                @else
                                <a href="{{ route('doctor.noi-soi.edit', $ns->id) }}" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit me-1"></i>Nhập kết quả
                                </a>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="text-center py-4">
                        <i class="fas fa-microscope fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Chưa chỉ định nội soi</p>
                        <a href="{{ route('doctor.noi-soi.create', $record->id) }}" class="btn btn-sm vc-btn-primary">
                            <i class="fas fa-plus me-2"></i>Chỉ định ngay
                        </a>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Thủ thuật --}}
            <div class="card vc-card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-procedures me-2" style="color: #f59e0b;"></i>
                        Thủ thuật
                    </h5>
                    <a href="{{ route('doctor.thu-thuat.create', $record->id) }}" class="btn btn-sm vc-btn-primary">
                        <i class="fas fa-plus"></i>
                    </a>
                </div>
                <div class="card-body">
                    @php
                        $thuThuats = $record->thuThuats ?? collect();
                    @endphp

                    @if($thuThuats->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($thuThuats as $tt)
                        <div class="list-group-item px-0">
                            <div class="d-flex justify-content-between align-items-start mb-1">
                                <h6 class="mb-0 small">{{ $tt->ten_thu_thuat }}</h6>
                                <div>
                                    @if($tt->trang_thai === 'Đã hoàn thành' || $tt->trang_thai === 'Hoàn thành' || $tt->ket_qua)
                                    <span class="badge bg-success"><i class="fas fa-check me-1"></i>Hoàn thành</span>
                                    @elseif($tt->trang_thai === 'Đang thực hiện')
                                    <span class="badge bg-warning"><i class="fas fa-clock me-1"></i>Đang thực hiện</span>
                                    @else
                                    <span class="badge bg-secondary"><i class="fas fa-hourglass-start me-1"></i>Chờ thực hiện</span>
                                    @endif
                                    @if($tt->gia_tien)
                                    <span class="badge bg-info ms-1">{{ number_format($tt->gia_tien, 0, ',', '.') }} đ</span>
                                    @endif
                                </div>
                            </div>
                            @if($tt->chi_tiet_truoc_thu_thuat)
                            <p class="mb-2 small text-muted">{{ Str::limit($tt->chi_tiet_truoc_thu_thuat, 60) }}</p>
                            @endif
                            <div class="mt-2 d-flex gap-2">
                                @if($tt->trang_thai === 'Đã hoàn thành' || $tt->trang_thai === 'Hoàn thành' || $tt->ket_qua)
                                <a href="{{ route('doctor.thu-thuat.show', $tt->id) }}" class="btn btn-sm btn-success">
                                    <i class="fas fa-eye me-1"></i>Xem chi tiết
                                </a>
                                @else
                                <a href="{{ route('doctor.thu-thuat.edit', $tt->id) }}" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit me-1"></i>Nhập kết quả
                                </a>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="text-center py-4">
                        <i class="fas fa-procedures fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Chưa chỉ định thủ thuật</p>
                        <a href="{{ route('doctor.thu-thuat.create', $record->id) }}" class="btn btn-sm vc-btn-primary">
                            <i class="fas fa-plus me-2"></i>Chỉ định ngay
                        </a>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Lịch sử --}}
            <div class="card vc-card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-history me-2" style="color: #6366f1;"></i>
                        Lịch sử
                    </h5>
                </div>
                <div class="card-body">
                    <ul class="timeline">
                        <li>
                            <strong>Tạo bệnh án</strong><br>
                            <small class="text-muted">{{ \Carbon\Carbon::parse($record->created_at)->format('d/m/Y H:i') }}</small>
                        </li>
                        @if($record->updated_at != $record->created_at)
                        <li>
                            <strong>Cập nhật gần nhất</strong><br>
                            <small class="text-muted">{{ \Carbon\Carbon::parse($record->updated_at)->diffForHumans() }}</small>
                        </li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal hoàn thành khám --}}
@if($record->lichHen && $record->lichHen->trang_thai === 'Đang khám')
<div class="modal fade" id="completeModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Hoàn thành khám bệnh</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('doctor.lichhen.complete', $record->lich_hen_id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <p>Xác nhận hoàn thành khám bệnh cho <strong>{{ $record->user->name ?? 'bệnh nhân' }}</strong>?</p>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        Sau khi hoàn thành, hệ thống sẽ tự động tạo hóa đơn và gửi thông báo cho bệnh nhân.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check me-2"></i>Hoàn thành
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

@push('scripts')
<script>
$(document).ready(function() {
    // Open complete modal
    $('#completeExamBtn').click(function() {
        $('#completeModal').modal('show');
    });
    
    // Auto calculate BMI
    const canNang = document.getElementById('can_nang_doctor');
    const chieuCao = document.getElementById('chieu_cao_doctor');
    const bmi = document.getElementById('bmi_doctor');
    
    function calculateBMI() {
        const weight = parseFloat(canNang.value);
        const height = parseFloat(chieuCao.value) / 100; // convert cm to m
        
        if (weight && height && height > 0) {
            const bmiValue = weight / (height * height);
            bmi.value = bmiValue.toFixed(1);
        } else {
            bmi.value = '';
        }
    }
    
    if (canNang && chieuCao && bmi) {
        canNang.addEventListener('input', calculateBMI);
        chieuCao.addEventListener('input', calculateBMI);
    }
});

// Cập nhật trạng thái dịch vụ nâng cao
function updateStatus(dichVuId, trangThai) {
    if (!confirm('Cập nhật trạng thái: ' + trangThai + '?')) {
        return;
    }

    // Create form and submit
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '{{ url("doctor/dich-vu-nang-cao") }}/' + dichVuId;

    // CSRF token
    const csrfInput = document.createElement('input');
    csrfInput.type = 'hidden';
    csrfInput.name = '_token';
    csrfInput.value = '{{ csrf_token() }}';
    form.appendChild(csrfInput);

    // Method PUT
    const methodInput = document.createElement('input');
    methodInput.type = 'hidden';
    methodInput.name = '_method';
    methodInput.value = 'PUT';
    form.appendChild(methodInput);

    // Trang thai
    const statusInput = document.createElement('input');
    statusInput.type = 'hidden';
    statusInput.name = 'trang_thai';
    statusInput.value = trangThai;
    form.appendChild(statusInput);

    document.body.appendChild(form);
    form.submit();
}
</script>
@endpush

@push('styles')
<style>
.timeline {
    list-style: none;
    padding-left: 0;
}
.timeline li {
    padding-left: 20px;
    position: relative;
    padding-bottom: 15px;
}
.timeline li:before {
    content: '';
    position: absolute;
    left: 0;
    top: 5px;
    width: 10px;
    height: 10px;
    border-radius: 50%;
    background: #10b981;
}
.timeline li:not(:last-child):after {
    content: '';
    position: absolute;
    left: 4px;
    top: 15px;
    width: 2px;
    height: calc(100% - 10px);
    background: #e5e7eb;
}
</style>
@endpush
@endsection
