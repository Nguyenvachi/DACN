@extends('layouts.patient-modern')

@section('title', 'Chi Tiết Đơn Thuốc')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <a href="{{ route('patient.donthuoc.index') }}" class="btn btn-sm btn-outline-secondary mb-2">
                <i class="fas fa-arrow-left me-1"></i>Quay lại
            </a>
            <h4 class="mb-0">
                <i class="fas fa-prescription me-2"></i>Đơn Thuốc DT-{{ str_pad($donThuoc->id, 4, '0', STR_PAD_LEFT) }}
            </h4>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Thông Tin Đơn Thuốc</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <p class="text-muted small mb-1">Ngày kê đơn</p>
                            <p class="fw-bold">
                                <i class="fas fa-calendar me-2 text-primary"></i>
                                {{ \Carbon\Carbon::parse($donThuoc->created_at)->format('d/m/Y') }}
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p class="text-muted small mb-1">Bác sĩ kê đơn</p>
                            <p class="fw-bold">
                                <i class="fas fa-user-md me-2 text-success"></i>
                                {{ $donThuoc->bacSi->ho_ten ?? ($donThuoc->benhAn->bacSi->ho_ten ?? 'N/A') }}
                            </p>
                        </div>
                    </div>

                    @if($donThuoc->loi_dan)
                        <div class="alert alert-info">
                            <strong><i class="fas fa-notes-medical me-2"></i>Lời dặn:</strong>
                            <p class="mb-0 mt-2">{{ $donThuoc->loi_dan }}</p>
                        </div>
                    @endif

                    <h5 class="mb-3"><i class="fas fa-pills me-2"></i>Danh Sách Thuốc</h5>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Tên thuốc</th>
                                    <th>Liều lượng</th>
                                    <th>Số lượng</th>
                                    <th>Cách dùng</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($donThuoc->items as $index => $item)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <strong>{{ $item->thuoc->ten_thuoc ?? 'N/A' }}</strong>
                                            @if($item->thuoc && $item->thuoc->hoat_chat)
                                                <br><small class="text-muted">{{ $item->thuoc->hoat_chat }}</small>
                                            @endif
                                        </td>
                                        <td>{{ $item->lieu_dung ?? 'Á Ả' }}</td>
                                        <td>{{ $item->so_luong }}</td>
                                        <td>{{ $item->cach_dung ?? 'Á Ả' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @if($donThuoc->benhAn)
                        <div class="mt-4 p-3 bg-light rounded">
                            <p class="mb-2"><strong>Liên quan đến hồ sơ:</strong></p>
                            <a href="{{ route('patient.benhan.show', $donThuoc->benhAn) }}" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-file-medical me-1"></i>Xem hồ sơ
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-lightbulb me-2"></i>Lưu Ý</h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled">
                        <li class="mb-3">
                            <i class="fas fa-check-circle text-success me-2"></i>
                            Uống đúng liều lượng bác sĩ kê đơn
                        </li>
                        <li class="mb-3">
                            <i class="fas fa-check-circle text-success me-2"></i>
                            Tuân thủ thời gian uống thuốc
                        </li>
                        <li class="mb-3">
                            <i class="fas fa-check-circle text-success me-2"></i>
                            Bảo quản thuốc đúng cách
                        </li>
                        <li class="mb-3">
                            <i class="fas fa-exclamation-triangle text-warning me-2"></i>
                            Ngưng thuốc nếu có phản ứng phụ
                        </li>
                        <li>
                            <i class="fas fa-phone text-info me-2"></i>
                            Liên hệ bác sĩ nếu có thắc mắc
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
