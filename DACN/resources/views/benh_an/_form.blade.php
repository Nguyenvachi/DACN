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

{{-- File upload --}}
<div class="mt-4">
    <label class="form-label fw-semibold">Tệp đính kèm</label>
    <input type="file" name="files[]" multiple class="form-control">
    <small class="text-muted">Bạn có thể chọn nhiều tệp</small>
</div>


