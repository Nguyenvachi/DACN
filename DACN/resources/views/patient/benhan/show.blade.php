@extends('layouts.patient-modern')

@section('title', 'Chi Tiết Bệnh Án')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <a href="{{ route('patient.benhan.index') }}" class="btn btn-sm btn-outline-secondary mb-2">
                <i class="fas fa-arrow-left me-1"></i>Quay lại
            </a>
            <h4 class="mb-0">
                <i class="fas fa-file-medical me-2"></i>Bệnh Án #{{ str_pad($benhAn->id, 5, '0', STR_PAD_LEFT) }}
            </h4>
        </div>
        <div>
            <a href="{{ route('patient.benhan.exportPdf', $benhAn) }}" class="btn btn-success">
                <i class="fas fa-download me-2"></i>Tải PDF
            </a>
        </div>
    </div>

    <div class="row g-4">
        <!-- Thông tin chung -->
        <div class="col-md-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Thông Tin Khám</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <p class="text-muted small mb-1">Ngày khám</p>
                            <p class="fw-bold mb-0">
                                <i class="fas fa-calendar me-2 text-primary"></i>
                                {{ \Carbon\Carbon::parse($benhAn->ngay_kham)->format('d/m/Y') }}
                            </p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <p class="text-muted small mb-1">Bác sĩ điều trị</p>
                            <p class="fw-bold mb-0">
                                <i class="fas fa-user-md me-2 text-success"></i>
                                {{ $benhAn->bacSi->user->name ?? 'N/A' }}
                            </p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <p class="text-muted small mb-1">Dịch vụ</p>
                            <p class="fw-bold mb-0">
                                <i class="fas fa-hospital me-2 text-info"></i>
                                {{ $benhAn->dichVu->ten ?? 'N/A' }}
                            </p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <p class="text-muted small mb-1">Trạng thái</p>
                            <span class="badge bg-success">Đã hoàn thành</span>
                        </div>
                    </div>

                    @if($benhAn->ly_do_kham)
                        <div class="mt-3">
                            <p class="text-muted small mb-1">Lý do khám</p>
                            <p class="mb-0">{{ $benhAn->ly_do_kham }}</p>
                        </div>
                    @endif

                    @if($benhAn->chan_doan)
                        <div class="mt-3">
                            <p class="text-muted small mb-1"><i class="fas fa-stethoscope me-1"></i>Chẩn đoán</p>
                            <div class="alert alert-info mb-0">
                                {{ $benhAn->chan_doan }}
                            </div>
                        </div>
                    @endif

                    @if($benhAn->phuong_phap_dieu_tri)
                        <div class="mt-3">
                            <p class="text-muted small mb-1"><i class="fas fa-procedures me-1"></i>Phương pháp điều trị</p>
                            <p class="mb-0">{{ $benhAn->phuong_phap_dieu_tri }}</p>
                        </div>
                    @endif

                    @if($benhAn->ghi_chu)
                        <div class="mt-3">
                            <p class="text-muted small mb-1"><i class="fas fa-notes-medical me-1"></i>Ghi chú</p>
                            <p class="mb-0">{{ $benhAn->ghi_chu }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Đơn thuốc -->
            @if($benhAn->donThuoc)
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="fas fa-prescription me-2"></i>Đơn Thuốc</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Tên thuốc</th>
                                        <th>Liều lượng</th>
                                        <th>Số lượng</th>
                                        <th>Cách dùng</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($benhAn->donThuoc->items as $item)
                                        <tr>
                                            <td>{{ $item->thuoc->ten_thuoc }}</td>
                                            <td>{{ $item->lieu_luong }}</td>
                                            <td>{{ $item->so_luong }}</td>
                                            <td>{{ $item->cach_dung }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <a href="{{ route('patient.donthuoc.show', $benhAn->donThuoc) }}" class="btn btn-sm btn-outline-success">
                            <i class="fas fa-eye me-1"></i>Xem chi tiết đơn thuốc
                        </a>
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="col-md-4">
            <!-- Files đính kèm -->
            @if($benhAn->files && $benhAn->files->count() > 0)
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0"><i class="fas fa-paperclip me-2"></i>File Đính Kèm</h5>
                    </div>
                    <div class="card-body">
                        @foreach($benhAn->files as $file)
                            <div class="d-flex justify-content-between align-items-center mb-2 p-2 border-bottom">
                                <div>
                                    <i class="fas fa-file me-2 text-primary"></i>
                                    <small>{{ $file->ten_file }}</small>
                                </div>
                                <a href="{{ route('patient.benhan.files.download', $file) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-download"></i>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Xét nghiệm -->
            @if($benhAn->xetNghiem && $benhAn->xetNghiem->count() > 0)
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-warning text-white">
                        <h5 class="mb-0"><i class="fas fa-flask me-2"></i>Kết Quả Xét Nghiệm</h5>
                    </div>
                    <div class="card-body">
                        @foreach($benhAn->xetNghiem as $xn)
                            <div class="mb-3 pb-3 border-bottom">
                                <h6>{{ $xn->loai_xet_nghiem }}</h6>
                                <p class="small mb-2">{{ $xn->mo_ta }}</p>
                                <a href="{{ route('patient.benhan.xetnghiem.download', $xn) }}" class="btn btn-sm btn-outline-warning">
                                    <i class="fas fa-download me-1"></i>Tải kết quả
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
