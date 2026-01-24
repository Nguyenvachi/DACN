@php
    use Illuminate\Support\Str;
@endphp

<div class="mb-3">
    <div class="alert alert-info mb-2">Số hồ sơ bệnh án: <strong>{{ isset($benhAns) ? $benhAns->count() : 0 }}</strong></div>
</div>

@if (isset($benhAns) && $benhAns->count())
    <div class="card border-0 shadow-sm mt-4">
        <div class="card-header bg-white border-0 pt-4">
            <h5 class="fw-bold mb-0"><i class="fas fa-notes-medical me-2 text-primary"></i>Hồ sơ bệnh án</h5>
        </div>
        <div class="card-body p-4">
            <h6 class="mb-3">Danh sách hồ sơ</h6>
            <ul class="list-group">
                @foreach ($benhAns as $ba)
                    <li class="list-group-item d-flex justify-content-between align-items-start">
                        <div>
                            <div class="fw-bold">
                                {{ \Carbon\Carbon::parse($ba->ngay_kham)->format('d/m/Y H:i') ?? 'N/A' }} -
                                {{ $ba->ten_benh ?? ($ba->chuan_doan ?? 'Hồ sơ') }}</div>
                            <small
                                class="text-muted">{{ \Illuminate\Support\Str::limit($ba->chuan_doan ?? ($ba->ghi_chu ?? ''), 120) }}</small>
                        </div>
                        <div>
                            @if (Route::has('patient.benhan.show'))
                                <a href="{{ route('patient.benhan.show', $ba->id) }}"
                                    class="btn btn-sm btn-outline-primary">Chi tiết</a>
                            @endif
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
@else
    <div class="card border-0 shadow-sm mt-4">
        <div class="card-body p-4">
            <p class="mb-0 text-muted">Bạn chưa có hồ sơ y tế nào.</p>
            <a href="{{ route('public.bacsi.index') }}" class="btn btn-sm btn-primary mt-2">Đặt lịch khám</a>
        </div>
    </div>
@endif
