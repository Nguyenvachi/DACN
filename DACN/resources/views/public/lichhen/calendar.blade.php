@php
    $isPatient = auth()->check() && auth()->user()->isPatient();
    $layout = $isPatient ? 'layouts.patient-modern' : 'layouts.app';
@endphp

@extends($layout)

@push('meta')
    <meta name="description" content="Chọn bác sĩ, dịch vụ và ngày để xem khung giờ trống">
@endpush

@section('content')
<div class="container mt-4">
    <h1 class="mb-3">Lịch khám – Tìm khung giờ trống</h1>

    <div class="row g-3 align-items-end">
        <div class="col-md-4">
            <label class="form-label">Bác sĩ</label>
            <select id="bacSi" class="form-select">
                <option value="">-- Chọn bác sĩ --</option>
                @foreach($bacSis as $bs)
                    <option value="{{ $bs->id }}">{{ $bs->ho_ten }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-4">
            <label class="form-label">Dịch vụ</label>
            <select id="dichVu" class="form-select">
                <option value="">-- Chọn dịch vụ --</option>
                @foreach($dichVus as $dv)
                    <option value="{{ $dv->id }}" data-duration="{{ (int)($dv->thoi_gian_uoc_tinh ?? 30) }}">{{ $dv->ten }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-4">
            <label class="form-label">Ngày khám</label>
            <input type="date" id="ngay" class="form-control" value="{{ now()->toDateString() }}">
        </div>
    </div>

    <div class="mt-3">
        <button id="btnLoad" class="btn btn-primary">Xem khung giờ trống</button>
        <a href="{{ route('lichhen.my') }}" class="btn btn-outline-secondary ms-2">Lịch hẹn của tôi</a>
    </div>

    <div id="result" class="mt-4">
        <div id="shiftInfo" class="text-muted mb-2"></div>
        <div id="slots" class="row g-2"></div>
    </div>
</div>

@push('scripts')
<script>
(function(){
    const btn = document.getElementById('btnLoad');
    const bacSi = document.getElementById('bacSi');
    const dichVu = document.getElementById('dichVu');
    const ngay = document.getElementById('ngay');
    const slotsWrap = document.getElementById('slots');
    const shiftInfo = document.getElementById('shiftInfo');

    btn.addEventListener('click', async function(){
        const bs = bacSi.value; const dv = dichVu.value; const day = ngay.value;
        slotsWrap.innerHTML = '';
        shiftInfo.textContent = '';
        if(!bs || !dv || !day){
            slotsWrap.innerHTML = '<div class="col-12 text-danger">Vui lòng chọn đủ bác sĩ, dịch vụ và ngày.</div>';
            return;
        }
        const url = `{{ url('/api/bac-si') }}/${bs}/thoi-gian-trong/${day}?dich_vu_id=${dv}`;
        try{
            const res = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' }});
            const json = await res.json();
            if(json.shift){
                shiftInfo.textContent = `Giờ làm việc: ${json.shift.start} - ${json.shift.end}`;
            }
            if(Array.isArray(json.slots) && json.slots.length){
                json.slots.forEach(s => {
                    const col = document.createElement('div');
                    col.className = 'col-auto';
                    const a = document.createElement('a');
                    a.className = 'btn btn-outline-primary btn-sm';
                    a.textContent = s.label;
                    a.href = '#';
                    a.addEventListener('click', (e)=>{
                        e.preventDefault();
                        alert(`Bạn chọn khung giờ ${s.label}. Vui lòng quay lại trang đặt lịch để xác nhận.`);
                    });
                    col.appendChild(a);
                    slotsWrap.appendChild(col);
                });
            } else {
                slotsWrap.innerHTML = '<div class="col-12">Không có khung giờ trống phù hợp.</div>';
            }
        }catch(e){
            console.error(e);
            slotsWrap.innerHTML = '<div class="col-12 text-danger">Không tải được khung giờ. Vui lòng thử lại.</div>';
        }
    });
})();
</script>
@endpush
@endsection
