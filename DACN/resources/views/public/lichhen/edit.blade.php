@php
    $isPatient = auth()->check() && auth()->user()->role === 'patient';
    $layout = $isPatient ? 'layouts.patient-modern' : 'layouts.app';
@endphp

@extends($layout)

@section('content')
<div class="container">
    <h4>Sửa lịch hẹn LH-{{ str_pad($lichHen->id, 4, '0', STR_PAD_LEFT) }}</h4>
    <form method="POST" action="{{ route('lichhen.update', $lichHen) }}">
        @csrf
        @method('PATCH')
        <div class="mb-3">
            <label class="form-label">Ngày hẹn</label>
            <input type="date" name="ngay_hen" class="form-control" value="{{ old('ngay_hen', $lichHen->ngay_hen) }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Giờ hẹn</label>
            <input type="time" name="thoi_gian_hen" class="form-control" value="{{ old('thoi_gian_hen', $lichHen->thoi_gian_hen) }}" required>
        </div>
        <button class="btn btn-primary">Lưu thay đổi</button>
        <a class="btn btn-secondary" href="{{ route('lichhen.mine') }}">Hủy</a>
    </form>
</div>
@endsection
