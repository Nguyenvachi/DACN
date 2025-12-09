{{-- File: resources/views/components/alert.blade.php --}}
{{-- Parent: resources/views/components/ --}}

@props([
    'type' => 'info', // success, danger, warning, info, primary
    'icon' => null,
    'dismissible' => true,
    'title' => null
])

@php
    $icons = [
        'success' => 'bi-check-circle-fill',
        'danger' => 'bi-exclamation-triangle-fill',
        'warning' => 'bi-exclamation-circle-fill',
        'info' => 'bi-info-circle-fill',
        'primary' => 'bi-bell-fill'
    ];

    $defaultIcon = $icons[$type] ?? 'bi-info-circle-fill';
@endphp

<div {{ $attributes->merge(['class' => "alert alert-{$type} d-flex align-items-start" . ($dismissible ? ' alert-dismissible fade show' : '')]) }} role="alert">

    {{-- Icon --}}
    <div class="flex-shrink-0 me-3">
        <i class="bi {{ $icon ?? $defaultIcon }} fs-4"></i>
    </div>

    {{-- Content --}}
    <div class="flex-grow-1">
        @if($title)
            <h5 class="alert-heading fw-bold mb-2">{{ $title }}</h5>
        @endif
        <div>{{ $slot }}</div>
    </div>

    {{-- Close button --}}
    @if($dismissible)
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    @endif

</div>

<style>
.alert {
    border: none;
    border-radius: 0.75rem;
    padding: 1.25rem;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
}
.alert-success {
    background: linear-gradient(135deg, rgba(16, 185, 129, 0.15) 0%, rgba(5, 150, 105, 0.1) 100%);
    color: #059669;
}
.alert-danger {
    background: linear-gradient(135deg, rgba(239, 68, 68, 0.15) 0%, rgba(220, 38, 38, 0.1) 100%);
    color: #dc2626;
}
.alert-warning {
    background: linear-gradient(135deg, rgba(245, 158, 11, 0.15) 0%, rgba(217, 119, 6, 0.1) 100%);
    color: #d97706;
}
.alert-info {
    background: linear-gradient(135deg, rgba(59, 130, 246, 0.15) 0%, rgba(37, 99, 235, 0.1) 100%);
    color: #2563eb;
}
.alert-primary {
    background: linear-gradient(135deg, rgba(102, 126, 234, 0.15) 0%, rgba(118, 75, 162, 0.1) 100%);
    color: #764ba2;
}
</style>
