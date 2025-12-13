@extends('layouts.doctor')

@section('title', 'Kết Quả Xét Nghiệm')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-lg-9">
            <div class="card shadow-sm">
                <div class="card-header bg-danger text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="bi bi-file-earmark-medical"></i> Kết Quả Xét Nghiệm
                    </h5>
                    <div>
                        @if(in_array($xetNghiem->trang_thai, ['Chờ lấy mẫu', 'Đã lấy mẫu', 'Đang xét nghiệm']))
                        <a href="{{ route('doctor.xet-nghiem.edit', $xetNghiem->id) }}" class="btn btn-sm btn-light me-2">
                            <i class="bi bi-pencil"></i> Nhập Kết Quả
                        </a>
                        @endif
                        <a href="{{ route('doctor.benh-an.show', $xetNghiem->benh_an_id) }}" class="btn btn-sm btn-light">
                            <i class="bi bi-arrow-left"></i> Quay lại
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Thông tin chỉ định -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="text-muted mb-3">Thông Tin Chỉ Định</h6>
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <td width="40%" class="text-muted">Loại xét nghiệm:</td>
                                    <td><span class="badge bg-secondary">{{ $xetNghiem->loai_xet_nghiem }}</span></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Tên xét nghiệm:</td>
                                    <td><strong>{{ $xetNghiem->ten_xet_nghiem }}</strong></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Ngày chỉ định:</td>
                                    <td>{{ $xetNghiem->ngay_chi_dinh->format('d/m/Y') }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Bác sĩ chỉ định:</td>
                                    <td>{{ $xetNghiem->bacSiChiDinh->ho_ten ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Dịch vụ:</td>
                                    <td>{{ $xetNghiem->dichVu->ten_dich_vu ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Trạng thái:</td>
                                    <td>{!! $xetNghiem->trang_thai_badge !!}</td>
                                </tr>
                            </table>
                        </div>

                        <div class="col-md-6">
                            <h6 class="text-muted mb-3">Thời Gian Thực Hiện</h6>
                            <table class="table table-sm table-borderless">
                                @if($xetNghiem->ngay_lay_mau)
                                <tr>
                                    <td width="40%" class="text-muted">Ngày lấy mẫu:</td>
                                    <td>{{ $xetNghiem->ngay_lay_mau->format('d/m/Y') }}</td>
                                </tr>
                                @endif
                                @if($xetNghiem->ngay_tra_ket_qua)
                                <tr>
                                    <td class="text-muted">Ngày trả kết quả:</td>
                                    <td>{{ $xetNghiem->ngay_tra_ket_qua->format('d/m/Y') }}</td>
                                </tr>
                                @endif
                                @if($xetNghiem->can_nhin_an)
                                <tr>
                                    <td colspan="2">
                                        <span class="badge bg-warning text-dark">
                                            <i class="bi bi-exclamation-triangle"></i> Yêu cầu nhịn ăn
                                        </span>
                                    </td>
                                </tr>
                                @endif
                                @if($xetNghiem->chuan_bi)
                                <tr>
                                    <td class="text-muted">Chuẩn bị:</td>
                                    <td>{{ $xetNghiem->chuan_bi }}</td>
                                </tr>
                                @endif
                            </table>
                        </div>
                    </div>

                    @if(!empty($xetNghiem->chi_so) && count($xetNghiem->chi_so) > 0)
                    <hr class="my-4">
                    <h6 class="text-muted mb-3">Kết Quả Xét Nghiệm</h6>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th width="30%">Chỉ Số</th>
                                    <th width="20%" class="text-center">Kết Quả</th>
                                    <th width="15%" class="text-center">Đơn Vị</th>
                                    <th width="25%" class="text-center">Giá Trị Bình Thường</th>
                                    <th width="10%" class="text-center">Đánh Giá</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($xetNghiem->chi_so as $chiSo)
                                @php
                                    // Simple comparison logic - can be enhanced
                                    $status = '';
                                    if (!empty($chiSo['ket_qua']) && !empty($chiSo['gia_tri_bt'])) {
                                        $ketQua = (float)$chiSo['ket_qua'];
                                        $giaTri = $chiSo['gia_tri_bt'];
                                        
                                        if (preg_match('/(\d+\.?\d*)\s*-\s*(\d+\.?\d*)/', $giaTri, $matches)) {
                                            $min = (float)$matches[1];
                                            $max = (float)$matches[2];
                                            if ($ketQua < $min) {
                                                $status = '<span class="badge bg-primary">Thấp</span>';
                                            } elseif ($ketQua > $max) {
                                                $status = '<span class="badge bg-danger">Cao</span>';
                                            } else {
                                                $status = '<span class="badge bg-success">BT</span>';
                                            }
                                        }
                                    }
                                @endphp
                                <tr>
                                    <td>{{ $chiSo['ten'] ?? '' }}</td>
                                    <td class="text-center"><strong>{{ $chiSo['ket_qua'] ?? '' }}</strong></td>
                                    <td class="text-center">{{ $chiSo['don_vi'] ?? '' }}</td>
                                    <td class="text-center">{{ $chiSo['gia_tri_bt'] ?? '' }}</td>
                                    <td class="text-center">{!! $status !!}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endif

                    @if($xetNghiem->nhan_xet || $xetNghiem->ket_luan || $xetNghiem->de_xuat || $xetNghiem->ghi_chu)
                    <hr class="my-4">
                    <h6 class="text-muted mb-3">Nhận Xét & Kết Luận</h6>
                    
                    @if($xetNghiem->nhan_xet)
                    <div class="mb-3">
                        <strong class="text-muted">Nhận xét:</strong>
                        <p class="mb-0 ms-3">{{ $xetNghiem->nhan_xet }}</p>
                    </div>
                    @endif

                    @if($xetNghiem->ket_luan)
                    <div class="mb-3">
                        <strong class="text-muted">Kết luận:</strong>
                        <p class="mb-0 ms-3">{{ $xetNghiem->ket_luan }}</p>
                    </div>
                    @endif

                    @if($xetNghiem->de_xuat)
                    <div class="mb-3">
                        <strong class="text-muted">Đề xuất:</strong>
                        <p class="mb-0 ms-3">{{ $xetNghiem->de_xuat }}</p>
                    </div>
                    @endif

                    @if($xetNghiem->ghi_chu)
                    <div class="mb-3">
                        <strong class="text-muted">Ghi chú:</strong>
                        <p class="mb-0 ms-3">{{ $xetNghiem->ghi_chu }}</p>
                    </div>
                    @endif
                    @endif

                    @if(!empty($xetNghiem->file_ket_qua) && count($xetNghiem->file_ket_qua) > 0)
                    <hr class="my-4">
                    <h6 class="text-muted mb-3">File Kết Quả</h6>
                    <div class="list-group">
                        @foreach($xetNghiem->file_ket_qua as $file)
                        @php
                            $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                            $isPdf = $extension === 'pdf';
                            $isImage = in_array($extension, ['jpg', 'jpeg', 'png', 'gif']);
                        @endphp
                        <a href="{{ Storage::url($file) }}" target="_blank" class="list-group-item list-group-item-action d-flex align-items-center">
                            @if($isPdf)
                                <i class="bi bi-file-earmark-pdf text-danger fs-4 me-3"></i>
                            @elseif($isImage)
                                <i class="bi bi-file-earmark-image text-primary fs-4 me-3"></i>
                            @else
                                <i class="bi bi-file-earmark text-secondary fs-4 me-3"></i>
                            @endif
                            <div>
                                <div>{{ basename($file) }}</div>
                                <small class="text-muted">Nhấn để xem/tải xuống</small>
                            </div>
                        </a>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-3">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h6 class="mb-0"><i class="bi bi-person"></i> Thông Tin Bệnh Nhân</h6>
                </div>
                <div class="card-body">
                    <p class="mb-2"><strong>Họ tên:</strong><br>{{ $xetNghiem->benhAn->benh_nhan_ten }}</p>
                    <p class="mb-2"><strong>Ngày sinh:</strong><br>{{ \Carbon\Carbon::parse($xetNghiem->benhAn->benh_nhan_ngay_sinh)->format('d/m/Y') }}</p>
                    <p class="mb-2"><strong>Tuổi:</strong> {{ \Carbon\Carbon::parse($xetNghiem->benhAn->benh_nhan_ngay_sinh)->age }} tuổi</p>
                    <p class="mb-2"><strong>Giới tính:</strong> {{ $xetNghiem->benhAn->benh_nhan_gioi_tinh }}</p>
                    <p class="mb-0"><strong>Số điện thoại:</strong><br>{{ $xetNghiem->benhAn->benh_nhan_sdt }}</p>
                </div>
            </div>

            @if($xetNghiem->benhAn->chan_doan_so_bo)
            <div class="card shadow-sm mt-3">
                <div class="card-header bg-light">
                    <h6 class="mb-0"><i class="bi bi-clipboard-pulse"></i> Chẩn Đoán</h6>
                </div>
                <div class="card-body">
                    <p class="mb-0">{{ $xetNghiem->benhAn->chan_doan_so_bo }}</p>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
