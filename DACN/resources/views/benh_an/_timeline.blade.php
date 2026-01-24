@php
    // build timeline steps and active class based on lịch hẹn trạng thái
    $current = $benhAn->lichHen->trang_thai ?? null;
    $steps = [
        \App\Models\LichHen::STATUS_PENDING_VN,
        \App\Models\LichHen::STATUS_CONFIRMED_VN,
        \App\Models\LichHen::STATUS_CHECKED_IN_VN,
        \App\Models\LichHen::STATUS_IN_PROGRESS_VN,
        \App\Models\LichHen::STATUS_COMPLETED_VN,
    ];
@endphp

<div class="card shadow-sm mb-4">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h6 class="fw-semibold mb-0">Luồng khám</h6>
            @if(isset($actionButtons) && $actionButtons)
                <div>{!! $actionButtons !!}</div>
            @endif
        </div>
        <div class="d-flex gap-3 align-items-center justify-content-between mt-3 timeline">
            @foreach($steps as $s)
                @php $active = ($current === $s) || (in_array($current, $steps) && array_search($current, $steps) > array_search($s, $steps)); @endphp
                <div class="text-center flex-fill">
                    <div class="mb-1">
                        <span class="badge {{ $active ? 'bg-primary' : 'bg-light text-muted' }}">{{ $s }}</span>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
