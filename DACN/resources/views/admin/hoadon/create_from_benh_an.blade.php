@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0">
            <i class="bi bi-receipt"></i>
            Tạo hóa đơn từ bệnh án #{{ $benhAn->id }}
        </h2>
        <a href="{{ route('admin.benhan.show', $benhAn) }}" class="btn btn-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Quay lại
        </a>
    </div>

    @if($existingHoaDon)
        <div class="alert alert-info">
            <i class="bi bi-info-circle"></i>
            Bệnh án này đã có hóa đơn <strong>#{{ $existingHoaDon->ma_hoa_don }}</strong>.
            <a href="{{ route('admin.hoadon.show', $existingHoaDon) }}" class="alert-link">Xem hóa đơn</a>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}
            <button class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="bi bi-person-badge"></i> Thông tin bệnh nhân</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Họ tên:</strong> {{ $benhAn->benhNhan->name ?? 'N/A' }}</p>
                    <p><strong>Email:</strong> {{ $benhAn->benhNhan->email ?? 'N/A' }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Bác sĩ:</strong> {{ $benhAn->bacSi->ten ?? 'N/A' }}</p>
                    <p><strong>Ngày khám:</strong> {{ $benhAn->ngay_kham }}</p>
                </div>
            </div>
        </div>
    </div>

    <form method="POST" action="{{ route('admin.hoadon.store-from-benh-an', $benhAn) }}" id="hoaDonForm">
        @csrf
        
        <div class="card shadow-sm mt-4">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="bi bi-list-check"></i> Danh sách dịch vụ đã thực hiện</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th><input type="checkbox" id="selectAll" checked></th>
                                <th>Loại dịch vụ</th>
                                <th>Tên dịch vụ</th>
                                <th>Số lượng</th>
                                <th>Đơn giá (VNĐ)</th>
                                <th>Thành tiền (VNĐ)</th>
                            </tr>
                        </thead>
                        <tbody id="dichVuTable">
                            {{-- Nội soi --}}
                            @foreach($benhAn->noiSois as $ns)
                            <tr>
                                <td>
                                    <input type="checkbox" class="dv-checkbox" checked
                                        data-loai="noi_soi"
                                        data-id="{{ $ns->id }}"
                                        data-ten="Nội soi - {{ $ns->loai_noi_soi }}"
                                        data-soluong="1"
                                        data-dongia="500000">
                                </td>
                                <td><span class="badge bg-info">Nội soi</span></td>
                                <td>{{ $ns->loai_noi_soi }}</td>
                                <td><input type="number" class="form-control form-control-sm sl-input" value="1" min="1" style="width: 80px;"></td>
                                <td><input type="number" class="form-control form-control-sm dg-input" value="500000" min="0" step="1000" style="width: 150px;"></td>
                                <td class="tt-cell fw-bold">500,000</td>
                            </tr>
                            @endforeach

                            {{-- X-quang --}}
                            @foreach($benhAn->xQuangs as $xq)
                            <tr>
                                <td>
                                    <input type="checkbox" class="dv-checkbox" checked
                                        data-loai="x_quang"
                                        data-id="{{ $xq->id }}"
                                        data-ten="X-quang - {{ $xq->loai_x_quang }}"
                                        data-soluong="1"
                                        data-dongia="300000">
                                </td>
                                <td><span class="badge bg-warning">X-quang</span></td>
                                <td>{{ $xq->loai_x_quang }}</td>
                                <td><input type="number" class="form-control form-control-sm sl-input" value="1" min="1" style="width: 80px;"></td>
                                <td><input type="number" class="form-control form-control-sm dg-input" value="300000" min="0" step="1000" style="width: 150px;"></td>
                                <td class="tt-cell fw-bold">300,000</td>
                            </tr>
                            @endforeach

                            {{-- Xét nghiệm --}}
                            @foreach($benhAn->xetNghiems as $xn)
                            <tr>
                                <td>
                                    <input type="checkbox" class="dv-checkbox" checked
                                        data-loai="xet_nghiem"
                                        data-id="{{ $xn->id }}"
                                        data-ten="Xét nghiệm - {{ $xn->loai }}"
                                        data-soluong="1"
                                        data-dongia="200000">
                                </td>
                                <td><span class="badge bg-primary">Xét nghiệm</span></td>
                                <td>{{ $xn->loai }}</td>
                                <td><input type="number" class="form-control form-control-sm sl-input" value="1" min="1" style="width: 80px;"></td>
                                <td><input type="number" class="form-control form-control-sm dg-input" value="200000" min="0" step="1000" style="width: 150px;"></td>
                                <td class="tt-cell fw-bold">200,000</td>
                            </tr>
                            @endforeach

                            {{-- Thủ thuật --}}
                            @foreach($benhAn->thuThuats as $tt)
                                @php
                                    $giaThuThuat = (float) ($tt->gia_tien ?? 0);
                                    if ($giaThuThuat <= 0 && isset($thuThuatPriceMap)) {
                                        $giaThuThuat = (float) ($thuThuatPriceMap[$tt->ten_thu_thuat] ?? 0);
                                    }
                                    if ($giaThuThuat <= 0) {
                                        $giaThuThuat = 1000000;
                                    }
                                @endphp
                            <tr>
                                <td>
                                    <input type="checkbox" class="dv-checkbox" checked
                                        data-loai="thu_thuat"
                                        data-id="{{ $tt->id }}"
                                        data-ten="Thủ thuật - {{ $tt->ten_thu_thuat }}"
                                        data-soluong="1"
                                        data-dongia="{{ $giaThuThuat }}">
                                </td>
                                <td><span class="badge bg-danger">Thủ thuật</span></td>
                                <td>{{ $tt->ten_thu_thuat }}</td>
                                <td><input type="number" class="form-control form-control-sm sl-input" value="1" min="1" style="width: 80px;"></td>
                                <td><input type="number" class="form-control form-control-sm dg-input" value="{{ $giaThuThuat }}" min="0" step="1000" style="width: 150px;"></td>
                                <td class="tt-cell fw-bold">{{ number_format($giaThuThuat, 0, ',', ',') }}</td>
                            </tr>
                            @endforeach

                            {{-- Thuốc --}}
                            @foreach($benhAn->donThuocs as $don)
                                @foreach($don->items as $item)
                                <tr>
                                    <td>
                                        <input type="checkbox" class="dv-checkbox" checked
                                            data-loai="thuoc"
                                            data-id="{{ $item->thuoc_id }}"
                                            data-ten="Thuốc - {{ $item->thuoc->ten ?? 'N/A' }}"
                                            data-soluong="{{ $item->so_luong }}"
                                            data-dongia="{{ $item->thuoc->gia_ban ?? 0 }}">
                                    </td>
                                    <td><span class="badge bg-secondary">Thuốc</span></td>
                                    <td>{{ $item->thuoc->ten ?? 'N/A' }} <small class="text-muted">({{ $item->lieu_dung }})</small></td>
                                    <td><input type="number" class="form-control form-control-sm sl-input" value="{{ $item->so_luong }}" min="1" style="width: 80px;"></td>
                                    <td><input type="number" class="form-control form-control-sm dg-input" value="{{ $item->thuoc->gia_ban ?? 0 }}" min="0" step="100" style="width: 150px;"></td>
                                    <td class="tt-cell fw-bold">{{ number_format(($item->thuoc->gia_ban ?? 0) * $item->so_luong, 0, ',', ',') }}</td>
                                </tr>
                                @endforeach
                            @endforeach

                            @if($benhAn->noiSois->isEmpty() && $benhAn->xQuangs->isEmpty() && $benhAn->xetNghiems->isEmpty() && $benhAn->thuThuats->isEmpty() && $benhAn->donThuocs->isEmpty())
                            <tr>
                                <td colspan="6" class="text-center text-muted">Không có dịch vụ nào</td>
                            </tr>
                            @endif
                        </tbody>
                        <tfoot class="table-light">
                            <tr>
                                <th colspan="5" class="text-end">TỔNG CỘNG:</th>
                                <th id="tongTien" class="text-danger fs-5">0 VNĐ</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <div class="mb-3 mt-3">
                    <label class="form-label">Ghi chú</label>
                    <textarea name="ghi_chu" class="form-control" rows="2" placeholder="Ghi chú thêm (nếu có)"></textarea>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('admin.benhan.show', $benhAn) }}" class="btn btn-secondary">
                        <i class="bi bi-x-circle"></i> Hủy
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle"></i> Tạo hóa đơn
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectAllCheckbox = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.dv-checkbox');
    const form = document.getElementById('hoaDonForm');

    // Tính tổng tiền
    function calculateTotal() {
        let total = 0;
        document.querySelectorAll('tbody tr').forEach(row => {
            const checkbox = row.querySelector('.dv-checkbox');
            if (checkbox && checkbox.checked) {
                const soLuong = parseInt(row.querySelector('.sl-input').value) || 0;
                const donGia = parseInt(row.querySelector('.dg-input').value) || 0;
                const thanhTien = soLuong * donGia;
                row.querySelector('.tt-cell').textContent = thanhTien.toLocaleString('vi-VN');
                total += thanhTien;
            }
        });
        document.getElementById('tongTien').textContent = total.toLocaleString('vi-VN') + ' VNĐ';
    }

    // Select/Deselect all
    selectAllCheckbox.addEventListener('change', function() {
        checkboxes.forEach(cb => cb.checked = this.checked);
        calculateTotal();
    });

    // Checkbox change
    checkboxes.forEach(cb => {
        cb.addEventListener('change', calculateTotal);
    });

    // Input change
    document.querySelectorAll('.sl-input, .dg-input').forEach(input => {
        input.addEventListener('input', calculateTotal);
    });

    // Submit form
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const dichVu = [];
        document.querySelectorAll('tbody tr').forEach(row => {
            const checkbox = row.querySelector('.dv-checkbox');
            if (checkbox && checkbox.checked) {
                const soLuong = parseInt(row.querySelector('.sl-input').value);
                const donGia = parseInt(row.querySelector('.dg-input').value);
                
                dichVu.push({
                    loai: checkbox.dataset.loai,
                    id: checkbox.dataset.id,
                    ten: checkbox.dataset.ten,
                    so_luong: soLuong,
                    don_gia: donGia
                });
            }
        });

        if (dichVu.length === 0) {
            alert('Vui lòng chọn ít nhất một dịch vụ!');
            return;
        }

        // Add hidden inputs
        dichVu.forEach((dv, index) => {
            Object.keys(dv).forEach(key => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = `dich_vu[${index}][${key}]`;
                input.value = dv[key];
                form.appendChild(input);
            });
        });

        form.submit();
    });

    // Initial calculation
    calculateTotal();
});
</script>
@endsection
