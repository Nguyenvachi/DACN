@props([
    'status' => '',
    'type' => 'default', // success, warning, danger, info, default
    'icon' => null
])

@php
    $typeConfig = [
        'success' => ['class' => 'badge-success', 'icon' => 'fa-check-circle'],
        'warning' => ['class' => 'badge-warning', 'icon' => 'fa-clock'],
        'danger' => ['class' => 'badge-danger', 'icon' => 'fa-times-circle'],
        'info' => ['class' => 'badge-info', 'icon' => 'fa-info-circle'],
        'default' => ['class' => 'badge-secondary', 'icon' => 'fa-circle'],
    ];

    $config = $typeConfig[$type] ?? $typeConfig['default'];
    $badgeIcon = $icon ?? $config['icon'];
@endphp

<span class="badge status-badge {{ $config['class'] }} px-3 py-2">
    <i class="fas {{ $badgeIcon }} me-1"></i>{{ $status }}
</span>

<style>
.status-badge {
    font-weight: 600;
    font-size: 0.875rem;
    border-radius: 50px;
    display: inline-flex;
    align-items: center;
}

.badge-success {
    background: linear-gradient(135deg, #10b981, #059669);
    color: white;
}

.badge-warning {
    background: linear-gradient(135deg, #f59e0b, #d97706);
    color: white;
}

.badge-danger {
    background: linear-gradient(135deg, #ef4444, #dc2626);
    color: white;
}

.badge-info {
    background: linear-gradient(135deg, #3b82f6, #2563eb);
    color: white;
}

.badge-secondary {
    background: linear-gradient(135deg, #6b7280, #4b5563);
    color: white;
}
</style>
