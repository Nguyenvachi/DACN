@php
    $role = $role ?? auth()->user()->role ?? 'patient';
    $layout = match ($role) {
        'admin' => 'layouts.admin',
        'doctor' => 'layouts.doctor',
        'staff' => 'layouts.staff',
        'patient' => 'layouts.patient-modern',
        default => 'layouts.app',
    };
@endphp

@extends($layout)

@section('content')
    <div class="container-fluid py-4">

        {{-- TIÊU ĐỀ --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold mb-0">
                <i class="bi bi-capsule text-primary"></i>
                Kê đơn thuốc cho bệnh án #{{ $benhAn->id }}
            </h3>

            <a href="{{ route($role . '.benhan.show', $benhAn) }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Quay lại bệnh án
            </a>
        </div>

        {{-- CARD FORM --}}
        <div class="card shadow-sm border-left-primary mb-4">
            <div class="card-body">

                <form method="POST" action="{{ route('benhan.donthuoc.store', $benhAn) }}">
                    @csrf

                    {{-- GHI CHÚ --}}
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Ghi chú đơn thuốc</label>
                        <input name="ghi_chu" class="form-control" placeholder="Ghi chú (tuỳ chọn)">
                    </div>

                    {{-- ITEM THUỐC --}}
                    <h5 class="fw-semibold mb-2">
                        <i class="bi bi-prescription2"></i> Danh sách thuốc
                    </h5>

                    <div id="items">

                        <div class="card mb-3 item-row border-start border-primary border-3">
                            <div class="card-body row g-3 align-items-end">

                                {{-- THUỐC --}}
                                <div class="col-md-5">
                                    <label class="form-label fw-semibold">Thuốc</label>
                                    <select class="form-select" name="items[0][thuoc_id]" required>
                                        <option value="">-- Chọn thuốc --</option>
                                        @foreach ($thuocs as $t)
                                            <option value="{{ $t->id }}">
                                                {{ $t->ten }}
                                                {{ $t->ham_luong ? '(' . $t->ham_luong . ')' : '' }}
                                                - {{ $t->don_vi }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- SỐ LƯỢNG --}}
                                <div class="col-md-2">
                                    <label class="form-label fw-semibold">Số lượng</label>
                                    <input type="number" class="form-control" name="items[0][so_luong]" value="1"
                                        min="1" required>
                                </div>

                                {{-- LIỀU DÙNG --}}
                                <div class="col-md-3">
                                    <label class="form-label fw-semibold">Liều dùng</label>
                                    <input class="form-control" name="items[0][lieu_dung]"
                                        placeholder="1 viên x 2 lần/ngày">
                                </div>

                                {{-- CÁCH DÙNG --}}
                                <div class="col-md-2">
                                    <label class="form-label fw-semibold">Cách dùng</label>
                                    <input class="form-control" name="items[0][cach_dung]" placeholder="Sau ăn">
                                </div>

                            </div>
                        </div>

                    </div>

                    {{-- BUTTON THÊM --}}
                    <div class="mb-3">
                        <button type="button" id="btn-add-row"
                            class="btn btn-outline-primary d-flex align-items-center gap-2">
                            <i class="bi bi-plus-circle"></i> Thêm thuốc
                        </button>
                    </div>

                    {{-- BUTTON LƯU --}}
                    <div class="d-flex gap-2 mt-4">
                        <button class="btn btn-success px-4">
                            <i class="bi bi-check-circle"></i> Lưu đơn thuốc
                        </button>

                        <a class="btn btn-secondary px-4" href="{{ route($role . '.benhan.show', $benhAn) }}">
                            Hủy
                        </a>
                    </div>

                </form>

            </div>
        </div>

    </div>
@endsection


@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const items = document.getElementById('items');
            const btn = document.getElementById('btn-add-row');
            let idx = 1;

            btn.addEventListener('click', function() {
                const tpl = document.querySelector('.item-row').cloneNode(true);

                tpl.querySelectorAll('select, input').forEach(el => {
                    el.name = el.name.replace('[0]', '[' + idx + ']');

                    if (el.tagName === 'INPUT') {
                        el.value = (el.type === 'number') ? 1 : '';
                    }
                    if (el.tagName === 'SELECT') {
                        el.selectedIndex = 0;
                    }
                });

                items.appendChild(tpl);
                idx++;
            });
        });
    </script>
@endpush

@php($role = $role ?? (request()->routeIs('admin.*') ? 'admin' : (request()->routeIs('doctor.*') ? 'doctor' : 'patient')))
