@extends('layouts.admin')

@section('content')
    <style>
        .card-custom {
            background: #fff;
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.08);
        }

        table.table input,
        table.table select {
            height: 38px !important;
        }

        .btn-del {
            width: 34px;
            height: 34px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
        }
    </style>

    <div class="container-fluid py-4">

        {{-- HEADER --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold mb-0">
                <i class="bi bi-download me-2"></i> Phiếu nhập kho
            </h2>
            <a href="{{ route('admin.kho.index') }}" class="btn btn-secondary btn-sm">
                <i class="bi bi-arrow-left"></i> Quay lại
            </a>
        </div>

        <div class="card-custom">

            <form method="post" action="{{ route('admin.kho.nhap.store') }}">
                @csrf

                {{-- THÔNG TIN --}}
                <div class="row g-3 mb-4">
                    <div class="col-sm-3">
                        <label class="form-label fw-semibold">Ngày nhập</label>
                        <input type="date" name="ngay_nhap" class="form-control" value="{{ date('Y-m-d') }}">
                    </div>

                    <div class="col-sm-5">
                        <label class="form-label fw-semibold">Nhà cung cấp</label>
                        <select name="nha_cung_cap_id" id="ncc-select" class="form-select">
                            <option value="">-- Chọn --</option>
                            @foreach ($nccs as $n)
                                <option value="{{ $n->id }}">{{ $n->ten }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- BẢNG NHẬP KHO --}}
                <div class="table-responsive mb-3">
                    <table class="table table-bordered align-middle" id="items">
                        <thead class="table-light">
                            <tr>
                                <th style="width:28%">Thuốc</th>
                                <th style="width:12%">Lô</th>
                                <th style="width:15%">Hạn sử dụng</th>
                                <th style="width:10%">SL</th>
                                <th style="width:15%">Đơn giá (₫)</th>
                                <th style="width:5%"></th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>

                    <button type="button" class="btn btn-outline-primary btn-sm" id="add-row">
                        <i class="bi bi-plus-lg"></i> Thêm dòng
                    </button>
                </div>

                {{-- BUTTONS --}}
                <div class="mt-4 d-flex justify-content-end">
                    <a href="{{ route('admin.kho.index') }}" class="btn btn-light me-2">Hủy</a>
                    <button class="btn btn-primary px-4">
                        <i class="bi bi-save me-1"></i> Lưu phiếu nhập
                    </button>
                </div>

            </form>
        </div>
    </div>

    {{-- DRUG JSON --}}
    @php
        $drugOptions = $thuocs
            ->map(
                fn($t) => [
                    'id' => $t->id,
                    'ten' => $t->ten,
                ],
            )
            ->values()
            ->toArray();
    @endphp

    <script type="application/json" id="drugs-data">
@json($drugOptions)
</script>

    {{-- SCRIPT --}}
    @push('scripts')
        <script>
            (function() {
                const DRUGS = JSON.parse(document.getElementById('drugs-data').textContent);
                let currentDrugs = DRUGS; // Danh sách thuốc hiện tại (scope cục bộ)

                let i = 0;
                const tbody = document.querySelector('#items tbody');

                function row(idx) {
                    const options = (currentDrugs || []).map(d =>
                        `<option value="${d.id}">${d.ten}${d.ham_luong ? ' - ' + d.ham_luong : ''}</option>`
                    ).join('');

                    return `
            <tr>

                <td>
                    <select name="items[${idx}][thuoc_id]"
                            class="form-select drug-select">
                        <option value="">-- Chọn thuốc --</option>
                        ${options}
                    </select>
                </td>

                <td>
                    <input class="form-control"
                           name="items[${idx}][ma_lo]"
                           placeholder="VD: L001">
                </td>

                <td>
                    <input type="date"
                           class="form-control"
                           name="items[${idx}][han_su_dung]">
                </td>

                <td>
                    <input type="number" min="1"
                           class="form-control"
                           value="1"
                           name="items[${idx}][so_luong]">
                </td>

                <td>
                    <input type="number" min="0" step="0.01"
                           class="form-control drug-price"
                           value="0"
                           name="items[${idx}][don_gia]"
                           placeholder="Giá nhập">
                </td>

                <td class="text-center">
                    <button type="button"
                            class="btn btn-outline-danger btn-del del">
                        <i class="bi bi-dash-lg"></i>
                    </button>
                </td>

            </tr>`;
                }

                // Lắng nghe sự kiện thay đổi NCC
                document.getElementById('ncc-select').addEventListener('change', async function() {
                    const nccId = this.value;

                    if (!nccId) {
                        // Không chọn NCC → Hiển thị tất cả thuốc
                        currentDrugs = DRUGS;
                        alert('⚠️ Chưa chọn nhà cung cấp. Hiển thị tất cả thuốc.');
                        return;
                    }

                    // Gọi API load thuốc theo NCC
                    try {
                        const response = await fetch(`{{ route('admin.kho.api.thuocs_by_ncc') }}?ncc_id=${nccId}`);
                        const result = await response.json();

                        if (result.success) {
                            currentDrugs = result.data;

                            if (!Array.isArray(currentDrugs) || currentDrugs.length === 0) {
                                alert('⚠️ Nhà cung cấp này chưa có thuốc nào. Vui lòng thêm thuốc vào danh mục NCC trước.');
                                currentDrugs = DRUGS; // Fallback về tất cả
                            }

                            // Xóa tất cả dòng hiện tại và reset chỉ số
                            tbody.innerHTML = '';
                            i = 0;
                            tbody.insertAdjacentHTML('beforeend', row(i++));
                        }
                    } catch (error) {
                        console.error('Lỗi load thuốc:', error);
                        alert('Lỗi khi tải danh sách thuốc!');
                    }
                });

                document.getElementById('add-row').addEventListener('click', () => {
                    if (!document.getElementById('ncc-select').value) {
                        alert('⚠️ Vui lòng chọn nhà cung cấp trước!');
                        return;
                    }
                    tbody.insertAdjacentHTML('beforeend', row(i++));
                });

                tbody.addEventListener('click', e => {
                    if (e.target.closest('.del'))
                        e.target.closest('tr').remove();
                });

                // Tự động điền giá nhập mặc định khi chọn thuốc
                tbody.addEventListener('change', e => {
                    if (e.target.classList && e.target.classList.contains('drug-select')) {
                        const thuocId = e.target.value;
                        const drug = (currentDrugs || []).find(d => d.id == thuocId);
                        if (drug && drug.gia_nhap_mac_dinh) {
                            const priceInput = e.target.closest('tr').querySelector('.drug-price');
                            if (priceInput) priceInput.value = drug.gia_nhap_mac_dinh;
                        }
                    }
                });

                // Khởi tạo 1 dòng mặc định
                tbody.insertAdjacentHTML('beforeend', row(i++));
            })();
        </script>
    @endpush
@endsection
