{{-- Helper blade component để hiển thị badge trạng thái lịch hẹn --}}
@props(['status'])

@php
    // Map status tiếng Việt (database) với config hiển thị
    $statusConfig = [
        \App\Models\LichHen::STATUS_PENDING_VN => [
            'label' => \App\Models\LichHen::STATUS_PENDING_VN,
            'color' => 'warning',
            'icon' => 'clock'
        ],
        \App\Models\LichHen::STATUS_CONFIRMED_VN => [
            'label' => \App\Models\LichHen::STATUS_CONFIRMED_VN,
            'color' => 'info',
            'icon' => 'check-circle'
        ],
        \App\Models\LichHen::STATUS_CHECKED_IN_VN => [
            'label' => \App\Models\LichHen::STATUS_CHECKED_IN_VN,
            'color' => 'primary',
            'icon' => 'user-check'
        ],
        \App\Models\LichHen::STATUS_IN_PROGRESS_VN => [
            'label' => \App\Models\LichHen::STATUS_IN_PROGRESS_VN,
            'color' => 'secondary',
            'icon' => 'stethoscope'
        ],
        \App\Models\LichHen::STATUS_COMPLETED_VN => [
            'label' => \App\Models\LichHen::STATUS_COMPLETED_VN,
            'color' => 'success',
            'icon' => 'check-double'
        ],
        \App\Models\LichHen::STATUS_CANCELLED_VN => [
            'label' => \App\Models\LichHen::STATUS_CANCELLED_VN,
            'color' => 'danger',
            'icon' => 'times-circle'
        ],
    ];

    $config = $statusConfig[$status] ?? [
        'label' => $status,
        'color' => 'secondary',
        'icon' => 'circle'
    ];
@endphp

<span {{ $attributes->merge(['class' => "badge bg-{$config['color']} d-inline-flex align-items-center gap-1"]) }}>
    <i class="fas fa-{{$config['icon']}}"></i>
    {{ $config['label'] }}
</span>
