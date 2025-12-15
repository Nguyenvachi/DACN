@csrf

<div class="row g-3">

    {{-- Bệnh nhân --}}
    <div class="col-md-6">
        <label class="form-label fw-semibold">Bệnh nhân</label>
        <select name="user_id" class="form-select" required>
            @foreach ($patients as $p)
                <option value="{{ $p->id }}" @selected(old('user_id', $record->user_id ?? '') == $p->id)>
                    {{ $p->name }}
                </option>
            @endforeach
        </select>
    </div>

    {{-- Bác sĩ --}}
    <div class="col-md-6">
        <label class="form-label fw-semibold">Bác sĩ</label>
        <input type="number" name="bac_si_id" class="form-control"
            value="{{ old('bac_si_id', $doctorId ?? ($record->bac_si_id ?? '')) }}" required>
        <small class="text-muted">Nhập ID bác sĩ (hoặc tự điền nếu bạn là bác sĩ)</small>
    </div>

    {{-- Lịch hẹn --}}
    <div class="col-md-6">
        <label class="form-label fw-semibold">Lịch hẹn (tuỳ chọn)</label>
        <select name="lich_hen_id" class="form-select">
            <option value="">-- Không chọn --</option>
            @foreach ($appointments as $a)
                <option value="{{ $a->id }}" @selected(old('lich_hen_id', $record->lich_hen_id ?? '') == $a->id)>
                    #{{ $a->id }} — {{ $a->ngay_hen }} {{ $a->thoi_gian_hen }}
                </option>
            @endforeach
        </select>
    </div>

    {{-- Ngày khám --}}
    <div class="col-md-6">
        <label class="form-label fw-semibold">Ngày khám</label>
        <input type="date" name="ngay_kham" class="form-control"
            value="{{ old('ngay_kham', optional($record->ngay_kham ?? now())->format('Y-m-d')) }}" required>
    </div>

</div>

{{-- Tiêu đề --}}
<div class="mt-4">
    <label class="form-label fw-semibold">Tiêu đề</label>
    <input type="text" name="tieu_de" class="form-control" value="{{ old('tieu_de', $record->tieu_de ?? '') }}"
        required>
</div>

{{-- Chỉ số sinh tồn --}}
<div class="mt-4">
    <h5 class="mb-3"><i class="bi bi-heart-pulse text-danger me-2"></i>Chỉ số sinh tồn <span class="badge bg-secondary">Chỉ xem</span></h5>
    <div class="alert alert-info mb-3">
        <i class="bi bi-info-circle me-2"></i>Chỉ bác sĩ khám mới có thể nhập chỉ số sinh tồn. Admin/Staff chỉ được xem.
    </div>
    <div class="row g-3">
        <div class="col-md-3">
            <label class="form-label fw-semibold">Huyết áp</label>
            <input type="text" class="form-control bg-light" 
                   value="{{ old('huyet_ap', $record->huyet_ap ?? '--') }}"
                   readonly>
            <small class="text-muted">mmHg</small>
        </div>
        
        <div class="col-md-3">
            <label class="form-label fw-semibold">Nhịp tim</label>
            <input type="text" class="form-control bg-light" 
                   value="{{ old('nhip_tim', $record->nhip_tim ?? '--') }}"
                   readonly>
            <small class="text-muted">lần/phút</small>
        </div>
        
        <div class="col-md-3">
            <label class="form-label fw-semibold">Nhiệt độ</label>
            <input type="text" class="form-control bg-light" 
                   value="{{ old('nhiet_do', $record->nhiet_do ?? '--') }}"
                   readonly>
            <small class="text-muted">°C</small>
        </div>
        
        <div class="col-md-3">
            <label class="form-label fw-semibold">Nhịp thở</label>
            <input type="text" class="form-control bg-light" 
                   value="{{ old('nhip_tho', $record->nhip_tho ?? '--') }}"
                   readonly>
            <small class="text-muted">lần/phút</small>
        </div>
        
        <div class="col-md-3">
            <label class="form-label fw-semibold">Cân nặng</label>
            <input type="text" class="form-control bg-light" 
                   value="{{ old('can_nang', $record->can_nang ?? '--') }}"
                   readonly>
            <small class="text-muted">kg</small>
        </div>
        
        <div class="col-md-3">
            <label class="form-label fw-semibold">Chiều cao</label>
            <input type="text" class="form-control bg-light" 
                   value="{{ old('chieu_cao', $record->chieu_cao ?? '--') }}"
                   readonly>
            <small class="text-muted">cm</small>
        </div>
        
        <div class="col-md-3">
            <label class="form-label fw-semibold">BMI</label>
            <input type="text" class="form-control bg-light" 
                   value="{{ old('bmi', $record->bmi ?? '--') }}"
                   readonly>
            <small class="text-muted">kg/m²</small>
        </div>
        
        <div class="col-md-3">
            <label class="form-label fw-semibold">SpO2</label>
            <input type="text" class="form-control bg-light" 
                   value="{{ old('spo2', $record->spo2 ?? '--') }}"
                   readonly>
            <small class="text-muted">%</small>
        </div>
    </div>
</div>

<script>
    // Auto calculate BMI
    document.addEventListener('DOMContentLoaded', function() {
        const canNang = document.getElementById('can_nang');
        const chieuCao = document.getElementById('chieu_cao');
        const bmi = document.getElementById('bmi');
        
        function calculateBMI() {
            const weight = parseFloat(canNang.value);
            const height = parseFloat(chieuCao.value) / 100; // convert cm to m
            
            if (weight && height && height > 0) {
                const bmiValue = weight / (height * height);
                bmi.value = bmiValue.toFixed(1);
            } else {
                bmi.value = '';
            }
        }
        
        canNang.addEventListener('input', calculateBMI);
        chieuCao.addEventListener('input', calculateBMI);
    });
</script>

{{-- 3 cột nội dung --}}
<div class="row mt-4 g-3">

    <div class="col-md-4">
        <label class="form-label fw-semibold">Triệu chứng</label>
        <textarea name="trieu_chung" class="form-control" rows="5">{{ old('trieu_chung', $record->trieu_chung ?? '') }}</textarea>
    </div>

    <div class="col-md-4">
        <label class="form-label fw-semibold">Chẩn đoán</label>
        <textarea name="chuan_doan" class="form-control" rows="5">{{ old('chuan_doan', $record->chuan_doan ?? '') }}</textarea>
    </div>

    <div class="col-md-4">
        <label class="form-label fw-semibold">Điều trị</label>
        <textarea name="dieu_tri" class="form-control" rows="5">{{ old('dieu_tri', $record->dieu_tri ?? '') }}</textarea>
    </div>

</div>

{{-- Ghi chú --}}
<div class="mt-4">
    <label class="form-label fw-semibold">Ghi chú</label>
    <textarea name="ghi_chu" class="form-control" rows="3">{{ old('ghi_chu', $record->ghi_chu ?? '') }}</textarea>
</div>


