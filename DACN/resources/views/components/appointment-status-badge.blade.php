{{-- Helper blade component để hiển thị badge trạng thái lịch hẹn --}}
@props(['status'])

@php
    // Map status tiếng Việt (database) với config hiển thị
    $statusConfig = [
        'Chờ xác nhận' => [
            'label' => 'Chờ xác nhận',
            'color' => 'warning',
            'icon' => 'clock'
        ],
        'Đã xác nhận' => [
            'label' => 'Đã xác nhận',
            'color' => 'info',
            'icon' => 'check-circle'
        ],
        'Đã check-in' => [
            'label' => 'Đã check-in',
            'color' => 'primary',
            'icon' => 'user-check'
        ],
        'Đang khám' => [
            'label' => 'Đang khám',
            'color' => 'secondary',
            'icon' => 'stethoscope'
        ],
        'Hoàn thành' => [
            'label' => 'Hoàn thành',
            'color' => 'success',
            'icon' => 'check-double'
        ],
        'Đã hủy' => [
            'label' => 'Đã hủy',
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
