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

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="mb-0">Phiếu xuất kho</h3>
            <a href="{{ route('admin.kho.index') }}" class="btn btn-secondary btn-sm">Quay lại</a>
        </div>

        <form method="post" action="{{ route('admin.kho.xuat.store') }}">
            @csrf

            <div class="card-custom">

                {{-- THÔNG TIN CHUNG --}}
                <div class="row g-3 mb-4">
                    <div class="col-sm-3">
                        <label class="form-label fw-bold">Ngày xuất</label>
                        <input type="date" name="ngay_xuat" class="form-control" value="{{ date('Y-m-d') }}">
                    </div>

                    <div class="col-sm-5">
                        <label class="form-label fw-bold">Đối tượng / Khách hàng</label>
                        <input type="text" name="doi_tuong" class="form-control" placeholder="Tên khách hàng / Ghi chú">
                    </div>
                </div>

                {{-- BẢNG THUỐC --}}
                <div class="table-responsive mb-3">
                    <table class="table table-bordered align-middle" id="items">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 45%">Thuốc</th>
                                <th style="width: 12%">SL</th>
                                <th style="width: 18%">Đơn giá xuất (₫)</th>
                                <th style="width: 5%"></th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>

                    <button type="button" class="btn btn-outline-primary btn-sm" id="add-row">
                        + Thêm dòng
                    </button>
                </div>

                <div class="d-flex justify-content-end mt-4">
                    <a href="{{ route('admin.kho.index') }}" class="btn btn-light me-2">Hủy</a>
                    <button class="btn btn-primary">Lưu phiếu xuất</button>
                </div>

            </div>
        </form>
    </div>

    @php
        $drugOptions = $thuocs
            ->map(function ($t) {
                return ['id' => $t->id, 'ten' => $t->ten];
            })
            ->values()
            ->toArray();
    @endphp

    <script type="application/json" id="drugs-data">
    @json($drugOptions)
</script>

    <script>
        const drugs = JSON.parse(document.getElementById('drugs-data').textContent);

        function row(idx) {
            const options = drugs
                .map(d => `<option value="${d.id}">${d.ten}</option>`)
                .join('');

            return `
        <tr>
            <td>
                <select name="items[${idx}][thuoc_id]" class="form-select">
                    ${options}
                </select>
            </td>

            <td>
                <input type="number" min="1" class="form-control"
                       name="items[${idx}][so_luong]" value="1">
            </td>

            <td>
                <input type="number" step="0.01" min="0"
                       class="form-control"
                       name="items[${idx}][don_gia]" value="0">
            </td>

            <td class="text-center">
                <button type="button"
                        class="btn btn-outline-danger btn-del del">
                    <i class="bi bi-dash-lg"></i>
                </button>
            </td>
        </tr>`;
        }

        let i = 0;
        const tbody = document.querySelector('#items tbody');

        document.getElementById('add-row').addEventListener('click', () => {
            tbody.insertAdjacentHTML('beforeend', row(i++));
        });

        tbody.addEventListener('click', e => {
            if (e.target.closest('.del'))
                e.target.closest('tr').remove();
        });

        // Khởi tạo 1 dòng mặc định
        tbody.insertAdjacentHTML('beforeend', row(i++));
    </script>
@endsection
