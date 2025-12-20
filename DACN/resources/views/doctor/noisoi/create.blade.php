@extends('layouts.doctor')

@section('title', 'Chỉ định Nội soi')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1 fw-bold text-dark">
                <i class="fas fa-stethoscope me-2 text-primary"></i>
                Chỉ định Nội soi
            </h4>
            <div class="text-muted">Bệnh án #{{ $benhAn->id }} - {{ $benhAn->user->name ?? 'N/A' }}</div>
        </div>
        <a href="{{ route('doctor.benhan.edit', $benhAn->id) }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Quay lại bệnh án
        </a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Vui lòng kiểm tra lại:</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row g-4">
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h6 class="mb-0 fw-bold">Thông tin chỉ định</h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('doctor.noisoi.store', $benhAn->id) }}">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Loại nội soi</label>
                            @if(isset($loaiNoiSois) && $loaiNoiSois->count() > 0)
                                <select name="loai_noi_soi_id" class="form-select" required id="loaiNoiSoi">
                                    <option value="">-- Chọn loại Nội soi --</option>
                                    @foreach($loaiNoiSois as $it)
                                        <option value="{{ $it->id }}"
                                                data-gia="{{ $it->gia }}"
                                                data-time="{{ $it->thoi_gian_uoc_tinh }}"
                                                {{ (string) old('loai_noi_soi_id') === (string) $it->id ? 'selected' : '' }}>
                                            {{ $it->ten }} - {{ number_format($it->gia ?? 0, 0, ',', '.') }}đ
                                        </option>
                                    @endforeach
                                </select>
                                <small class="text-muted" id="nsInfo"></small>
                                @error('loai_noi_soi_id')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror

                                <script>
                                    (function () {
                                        const sel = document.getElementById('loaiNoiSoi');
                                        const info = document.getElementById('nsInfo');
                                        if (!sel || !info) return;

                                        const render = () => {
                                            const opt = sel.options[sel.selectedIndex];
                                            if (!opt || !opt.dataset) {
                                                info.textContent = '';
                                                return;
                                            }
                                            const gia = opt.dataset.gia;
                                            const time = opt.dataset.time;
                                            if (gia && time) {
                                                info.textContent = `Chi phí: ${parseInt(gia, 10).toLocaleString('vi-VN')}đ | Thời gian: ${time} phút`;
                                            } else {
                                                info.textContent = '';
                                            }
                                        };

                                        sel.addEventListener('change', render);
                                        render();
                                    })();
                                </script>
                            @else
                                <input type="text" name="loai" class="form-control" value="{{ old('loai') }}" placeholder="Ví dụ: Nội soi cổ tử cung" required>
                                @error('loai')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            @endif
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Mô tả / Yêu cầu</label>
                            <textarea name="mo_ta" class="form-control" rows="3" placeholder="Ghi chú yêu cầu, lý do...">{{ old('mo_ta') }}</textarea>
                        </div>

                        @if(!isset($loaiNoiSois) || $loaiNoiSois->count() === 0)
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Giá (VNĐ)</label>
                                <input type="number" name="gia" class="form-control" value="{{ old('gia', 0) }}" min="0" step="1000">
                                <small class="text-muted">Nếu để 0 thì hóa đơn không cộng thêm.</small>
                            </div>
                        @endif

                        <button class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Lưu chỉ định
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-bold">Lịch sử nội soi</h6>
                </div>
                <div class="card-body">
                    @if($existing->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($existing as $ns)
                                <div class="list-group-item px-0">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <div class="fw-semibold">#{{ $ns->id }} - {{ $ns->loaiNoiSoi?->ten ?? $ns->loai }}</div>
                                            <small class="text-muted">{{ $ns->created_at?->format('d/m/Y H:i') }}</small>
                                        </div>
                                        <span class="badge bg-secondary">{{ $ns->trang_thai_text }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-muted">Chưa có nội soi nào.</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
