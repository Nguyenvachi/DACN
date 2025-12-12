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
            @if($record->lichHen && $record->lichHen->trang_thai === 'Đang khám')
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
                <div class="col-md-8">
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
                <div class="col-md-4 text-end">
                    {{-- Quick Actions --}}
                    <div class="d-grid gap-2">
                        <a href="{{ route('doctor.donthuoc.create', $record->id) }}" class="btn btn-outline-primary">
                            <i class="fas fa-prescription-bottle-alt me-2"></i>Kê đơn thuốc
                        </a>
                        <a href="{{ route('doctor.xetnghiem.create', $record->id) }}" class="btn btn-outline-info">
                            <i class="fas fa-flask me-2"></i>Chỉ định xét nghiệm
                        </a>
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
                            <input type="text" name="tieu_de" class="form-control"
                                   value="{{ old('tieu_de', $record->tieu_de) }}" required>
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
                    <a href="{{ route('doctor.xetnghiem.create', $record->id) }}" class="btn btn-sm vc-btn-primary">
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
                                    @if($xn->trang_thai_xn === 'Đã có kết quả' || $xn->trang_thai === 'completed')
                                    <span class="badge bg-success"><i class="fas fa-check me-1"></i>Hoàn thành</span>
                                    @elseif($xn->trang_thai_xn === 'Đang xử lý' || $xn->trang_thai === 'processing')
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
                            @if(($xn->trang_thai_xn === 'Đã có kết quả' || $xn->trang_thai === 'completed') && $xn->file_path)
                            <a href="{{ route('doctor.benhan.xetnghiem.download', $xn->id) }}"
                               class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-download me-1"></i>Tải KQ
                            </a>
                            @endif
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="text-center py-4">
                        <i class="fas fa-flask fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Chưa chỉ định XN</p>
                        <a href="{{ route('doctor.xetnghiem.create', $record->id) }}" class="btn btn-sm vc-btn-primary">
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
                                    @if($sa->trang_thai === 'Đã có kết quả')
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
                            @if($sa->trang_thai === 'Đã có kết quả')
                            <div class="mt-2">
                                <a href="{{ route('doctor.sieu-am.edit', $sa->id) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye me-1"></i>Xem KQ
                                </a>
                            </div>
                            @endif
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
                                    @if($tt->trang_thai === 'Đã hoàn thành')
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
                            @if($tt->trang_thai !== 'Chờ thực hiện')
                            <div class="mt-2">
                                <a href="{{ route('doctor.thu-thuat.edit', $tt->id) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye me-1"></i>Xem chi tiết
                                </a>
                            </div>
                            @endif
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

            {{-- Dịch vụ nâng cao --}}
            <div class="card vc-card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-stethoscope me-2" style="color: #f59e0b;"></i>
                        Dịch vụ nâng cao
                    </h5>
                    <button type="button" class="btn btn-sm vc-btn-primary" data-bs-toggle="modal" data-bs-target="#dichVuNangCaoModal">
                        <i class="fas fa-plus"></i>
                    </button>
                </div>
                <div class="card-body">
                    @php
                        $dichVuNangCao = $record->dichVuNangCao;
                    @endphp

                    @if($dichVuNangCao->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($dichVuNangCao as $dv)
                        <div class="list-group-item px-0">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">{{ $dv->dichVu->ten_dich_vu }}</h6>
                                    <p class="mb-1 small text-muted">
                                        <i class="fas fa-tag me-1"></i>
                                        {{ number_format($dv->gia_tai_thoi_diem, 0, ',', '.') }} VNĐ
                                    </p>
                                    @if($dv->ghi_chu)
                                    <p class="mb-1 small text-muted">
                                        <i class="fas fa-comment me-1"></i>
                                        {{ $dv->ghi_chu }}
                                    </p>
                                    @endif
                                </div>
                                <div class="ms-2">
                                    @if($dv->trang_thai === 'Hoàn thành')
                                    <span class="badge bg-success">Hoàn thành</span>
                                    @elseif($dv->trang_thai === 'Đang thực hiện')
                                    <span class="badge bg-info">Đang thực hiện</span>
                                    @elseif($dv->trang_thai === 'Đã hủy')
                                    <span class="badge bg-secondary">Đã hủy</span>
                                    @else
                                    <span class="badge bg-warning">Chờ thực hiện</span>
                                    @endif
                                </div>
                            </div>
                            
                            @if($dv->trang_thai !== 'Đã hủy' && $dv->trang_thai !== 'Hoàn thành')
                            <div class="d-flex gap-2 mt-2">
                                <button type="button" class="btn btn-sm btn-outline-info" 
                                        onclick="updateStatus({{ $dv->id }}, 'Đang thực hiện')">
                                    <i class="fas fa-play me-1"></i>Bắt đầu
                                </button>
                                @if($dv->trang_thai === 'Đang thực hiện')
                                <button type="button" class="btn btn-sm btn-outline-success" 
                                        data-bs-toggle="modal" data-bs-target="#ketQuaModal{{ $dv->id }}">
                                    <i class="fas fa-check me-1"></i>Hoàn thành
                                </button>
                                @endif
                                <form action="{{ route('doctor.benhan.dichvunangcao.destroy', $dv) }}" 
                                      method="POST" class="d-inline"
                                      onsubmit="return confirm('Hủy dịch vụ này?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </form>
                            </div>
                            @endif

                            @if($dv->ket_qua)
                            <div class="mt-2 p-2 bg-light rounded">
                                <strong class="small">Kết quả:</strong>
                                <p class="mb-0 small">{{ $dv->ket_qua }}</p>
                            </div>
                            @endif
                        </div>

                        {{-- Modal kết quả --}}
                        <div class="modal fade" id="ketQuaModal{{ $dv->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Nhập kết quả - {{ $dv->dichVu->ten_dich_vu }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form action="{{ route('doctor.benhan.dichvunangcao.update', $dv) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="trang_thai" value="Hoàn thành">
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label">Kết quả</label>
                                                <textarea name="ket_qua" class="form-control" rows="4" 
                                                          placeholder="Nhập kết quả thực hiện dịch vụ..." required></textarea>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Ghi chú bổ sung</label>
                                                <textarea name="ghi_chu" class="form-control" rows="2" 
                                                          placeholder="Ghi chú thêm (nếu có)">{{ $dv->ghi_chu }}</textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                                            <button type="submit" class="btn btn-success">
                                                <i class="fas fa-check me-2"></i>Xác nhận hoàn thành
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="text-center py-4">
                        <i class="fas fa-stethoscope fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Chưa chỉ định dịch vụ nâng cao</p>
                        <button type="button" class="btn btn-sm vc-btn-primary" data-bs-toggle="modal" data-bs-target="#dichVuNangCaoModal">
                            <i class="fas fa-plus me-2"></i>Chỉ định ngay
                        </button>
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

{{-- Modal chỉ định dịch vụ nâng cao --}}
<div class="modal fade" id="dichVuNangCaoModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-stethoscope me-2"></i>
                    Chỉ định dịch vụ nâng cao
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('doctor.benhan.dichvunangcao.create', $record) }}" method="POST">
                @csrf
                <div class="modal-body">
                    @php
                        $dichVuNangCaoList = \App\Models\DichVu::where('loai', 'Nâng cao')
                            ->where('hoat_dong', true)
                            ->orderBy('ten_dich_vu')
                            ->get();
                        $daChiDinh = $record->dichVuNangCao->pluck('dich_vu_id')->toArray();
                    @endphp

                    @if($dichVuNangCaoList->count() > 0)
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Chọn dịch vụ cần chỉ định</label>
                        <div class="row">
                            @foreach($dichVuNangCaoList as $dv)
                            <div class="col-md-6 mb-3">
                                <div class="form-check card h-100 {{ in_array($dv->id, $daChiDinh) ? 'border-secondary bg-light' : '' }}">
                                    <div class="card-body">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" 
                                                   name="dich_vu_ids[]" 
                                                   value="{{ $dv->id }}" 
                                                   id="dv{{ $dv->id }}"
                                                   {{ in_array($dv->id, $daChiDinh) ? 'disabled' : '' }}>
                                            <label class="form-check-label" for="dv{{ $dv->id }}">
                                                <strong>{{ $dv->ten_dich_vu }}</strong>
                                                @if(in_array($dv->id, $daChiDinh))
                                                <span class="badge bg-secondary ms-2">Đã chỉ định</span>
                                                @endif
                                                <br>
                                                <small class="text-muted">{{ $dv->mo_ta }}</small>
                                                <br>
                                                <span class="text-primary fw-bold">
                                                    <i class="fas fa-tag me-1"></i>
                                                    {{ number_format($dv->gia, 0, ',', '.') }} VNĐ
                                                </span>
                                                <small class="text-muted ms-2">
                                                    <i class="fas fa-clock me-1"></i>
                                                    ~{{ $dv->thoi_gian_uoc_tinh }} phút
                                                </small>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Ghi chú chỉ định</label>
                        <textarea name="ghi_chu" class="form-control" rows="3" 
                                  placeholder="Ghi chú cho các dịch vụ được chỉ định (nếu có)..."></textarea>
                    </div>
                    @else
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Chưa có dịch vụ nâng cao nào trong hệ thống.
                    </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    @if($dichVuNangCaoList->count() > 0)
                    <button type="submit" class="btn vc-btn-primary">
                        <i class="fas fa-check me-2"></i>Chỉ định
                    </button>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // Open complete modal
    $('#completeExamBtn').click(function() {
        $('#completeModal').modal('show');
    });
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
