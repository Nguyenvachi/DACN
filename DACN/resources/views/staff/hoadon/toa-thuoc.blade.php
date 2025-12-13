@extends('layouts.staff')

@section('title', 'Toa Thuốc - Bệnh Án #' . $benhAn->id)

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0">
            <i class="bi bi-prescription2 text-primary me-2"></i>
            Toa Thuốc - Bệnh Án #{{ $benhAn->id }}
        </h2>
        <div>
            <a href="{{ route('staff.dashboard') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Quay lại
            </a>
            <a href="{{ route('staff.hoadon.create-from-benh-an', $benhAn) }}" class="btn btn-success">
                <i class="bi bi-receipt"></i> Tạo Hóa Đơn
            </a>
        </div>
    </div>

    {{-- Thông tin bệnh nhân --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="bi bi-person-badge"></i> Thông Tin Bệnh Nhân</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Họ tên:</strong> {{ $benhAn->user->name ?? 'N/A' }}</p>
                    <p><strong>Ngày sinh:</strong> {{ $benhAn->user->ngay_sinh ? $benhAn->user->ngay_sinh->format('d/m/Y') : 'N/A' }}</p>
                    <p><strong>Giới tính:</strong> {{ $benhAn->user->gioi_tinh ?? 'N/A' }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Số điện thoại:</strong> {{ $benhAn->user->so_dien_thoai ?? 'N/A' }}</p>
                    <p><strong>Bác sĩ khám:</strong> {{ $benhAn->bacSi->hoten ?? 'N/A' }}</p>
                    <p><strong>Ngày khám:</strong> {{ $benhAn->created_at->format('d/m/Y H:i') }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Chẩn đoán --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-info text-white">
            <h5 class="mb-0"><i class="bi bi-clipboard2-pulse"></i> Chẩn Đoán</h5>
        </div>
        <div class="card-body">
            <p><strong>Triệu chứng:</strong> {{ $benhAn->trieu_chung ?? 'N/A' }}</p>
            <p><strong>Chẩn đoán:</strong> {{ $benhAn->chan_doan ?? 'N/A' }}</p>
            @if($benhAn->ghi_chu)
            <p><strong>Ghi chú:</strong> {{ $benhAn->ghi_chu }}</p>
            @endif
        </div>
    </div>

    {{-- Toa thuốc --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0"><i class="bi bi-capsule"></i> Toa Thuốc</h5>
        </div>
        <div class="card-body">
            @if($benhAn->donThuoc && $benhAn->donThuoc->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th width="5%">#</th>
                                <th width="30%">Tên Thuốc</th>
                                <th width="15%">Số Lượng</th>
                                <th width="15%">Đơn Giá</th>
                                <th width="15%">Thành Tiền</th>
                                <th width="20%">Cách Dùng</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $tongTienThuoc = 0; @endphp
                            @foreach($benhAn->donThuoc as $index => $dt)
                                @php
                                    $thanhTien = $dt->thuoc ? ($dt->thuoc->gia_ban * $dt->so_luong) : 0;
                                    $tongTienThuoc += $thanhTien;
                                @endphp
                                <tr>
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    <td>
                                        <strong>{{ $dt->thuoc->ten_thuoc ?? 'N/A' }}</strong><br>
                                        <small class="text-muted">{{ $dt->thuoc->hoat_chat ?? '' }}</small>
                                    </td>
                                    <td class="text-center">{{ $dt->so_luong }}</td>
                                    <td class="text-end">{{ number_format($dt->thuoc->gia_ban ?? 0) }} đ</td>
                                    <td class="text-end"><strong>{{ number_format($thanhTien) }} đ</strong></td>
                                    <td>
                                        <div><strong>Liều:</strong> {{ $dt->lieu_dung ?? 'N/A' }}</div>
                                        <div><strong>Cách dùng:</strong> {{ $dt->cach_dung ?? 'N/A' }}</div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="table-light">
                            <tr>
                                <th colspan="4" class="text-end">Tổng tiền thuốc:</th>
                                <th class="text-end text-primary">{{ number_format($tongTienThuoc) }} đ</th>
                                <th></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            @else
                <div class="alert alert-info">
                    <i class="bi bi-info-circle"></i> Bác sĩ chưa kê toa thuốc cho bệnh án này.
                </div>
            @endif
        </div>
    </div>

    {{-- Dịch vụ đã chỉ định --}}
    <div class="card shadow-sm">
        <div class="card-header bg-warning">
            <h5 class="mb-0"><i class="bi bi-clipboard-check"></i> Dịch Vụ Đã Chỉ Định</h5>
        </div>
        <div class="card-body">
            <div class="row">
                {{-- Nội soi --}}
                @if($benhAn->noiSoi && $benhAn->noiSoi->count() > 0)
                <div class="col-md-6 mb-3">
                    <h6 class="text-primary"><i class="bi bi-activity"></i> Nội Soi</h6>
                    <ul class="list-group">
                        @foreach($benhAn->noiSoi as $ns)
                            <li class="list-group-item d-flex justify-content-between">
                                <span>{{ $ns->loai_noi_soi }}</span>
                                <span class="badge bg-info">{{ $ns->trang_thai }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
                @endif

                {{-- X-quang --}}
                @if($benhAn->xQuang && $benhAn->xQuang->count() > 0)
                <div class="col-md-6 mb-3">
                    <h6 class="text-primary"><i class="bi bi-x-ray"></i> X-Quang</h6>
                    <ul class="list-group">
                        @foreach($benhAn->xQuang as $xq)
                            <li class="list-group-item d-flex justify-content-between">
                                <span>{{ $xq->loai_x_quang }}</span>
                                <span class="badge bg-info">{{ $xq->trang_thai }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
                @endif

                {{-- Xét nghiệm --}}
                @if($benhAn->xetNghiem && $benhAn->xetNghiem->count() > 0)
                <div class="col-md-6 mb-3">
                    <h6 class="text-primary"><i class="bi bi-droplet"></i> Xét Nghiệm</h6>
                    <ul class="list-group">
                        @foreach($benhAn->xetNghiem as $xn)
                            <li class="list-group-item d-flex justify-content-between">
                                <span>{{ $xn->loai_xet_nghiem }} - {{ $xn->ten_xet_nghiem }}</span>
                                <span class="badge bg-info">{{ $xn->trang_thai }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
                @endif

                {{-- Thủ thuật --}}
                @if($benhAn->thuThuat && $benhAn->thuThuat->count() > 0)
                <div class="col-md-6 mb-3">
                    <h6 class="text-primary"><i class="bi bi-hand-index"></i> Thủ Thuật</h6>
                    <ul class="list-group">
                        @foreach($benhAn->thuThuat as $tt)
                            <li class="list-group-item d-flex justify-content-between">
                                <span>{{ $tt->loai_thu_thuat }}</span>
                                <span class="badge bg-info">{{ $tt->trang_thai }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
                @endif
            </div>

            @if((!$benhAn->noiSoi || $benhAn->noiSoi->count() == 0) && 
                (!$benhAn->xQuang || $benhAn->xQuang->count() == 0) &&
                (!$benhAn->xetNghiem || $benhAn->xetNghiem->count() == 0) &&
                (!$benhAn->thuThuat || $benhAn->thuThuat->count() == 0))
                <div class="alert alert-info">
                    <i class="bi bi-info-circle"></i> Chưa có dịch vụ nào được chỉ định.
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
