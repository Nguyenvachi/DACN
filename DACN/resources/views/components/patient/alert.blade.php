@props([
    'type' => 'success', // success, error, warning, info
    'dismissible' => true,
    'icon' => null
])

@php
    $typeConfig = [
        'success' => ['class' => 'alert-success', 'icon' => 'fa-check-circle'],
        'error' => ['class' => 'alert-danger', 'icon' => 'fa-exclamation-circle'],
        'warning' => ['class' => 'alert-warning', 'icon' => 'fa-exclamation-triangle'],
        'info' => ['class' => 'alert-info', 'icon' => 'fa-info-circle'],
    ];

    $config = $typeConfig[$type] ?? $typeConfig['info'];
    $alertIcon = $icon ?? $config['icon'];
@endphp

<div class="alert {{ $config['class'] }} alert-modern {{ $dismissible ? 'alert-dismissible fade show' : '' }}" role="alert">
    <i class="fas {{ $alertIcon }} me-2"></i>
    {{ $slot }}
    @if($dismissible)
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    @endif
</div>

<style>
.alert-modern {
    border: none;
    border-radius: 0.75rem;
    padding: 1rem 1.25rem;
    font-weight: 500;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    animation: slideInRight 0.3s ease;
}

@keyframes slideInRight {
    from {
        opacity: 0;
        transform: translateX(20px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}
</style>
