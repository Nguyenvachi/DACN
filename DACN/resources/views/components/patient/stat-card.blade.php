@props([
    'title' => '',
    'value' => 0,
    'icon' => 'fa-chart-line',
    'color' => 'primary', // primary, success, warning, danger, info
    'route' => null
])

@php
    $colorConfig = [
        'primary' => ['bg' => 'bg-primary', 'text' => 'text-primary'],
        'success' => ['bg' => 'bg-success', 'text' => 'text-success'],
        'warning' => ['bg' => 'bg-warning', 'text' => 'text-warning'],
        'danger' => ['bg' => 'bg-danger', 'text' => 'text-danger'],
        'info' => ['bg' => 'bg-info', 'text' => 'text-info'],
    ];

    $config = $colorConfig[$color] ?? $colorConfig['primary'];
@endphp

<div class="stat-card card border-0 shadow-sm h-100">
    <div class="card-body">
        <div class="d-flex align-items-center">
            <div class="flex-shrink-0">
                <div class="stat-icon rounded-circle {{ $config['bg'] }} bg-opacity-10 p-3">
                    <i class="fas {{ $icon }} fa-2x {{ $config['text'] }}"></i>
                </div>
            </div>
            <div class="flex-grow-1 ms-3">
                <h6 class="stat-title text-muted mb-1">{{ $title }}</h6>
                <h3 class="stat-value mb-0">{{ $value }}</h3>
            </div>
        </div>

        @if($route)
        <a href="{{ $route }}" class="stretched-link"></a>
        @endif
    </div>
</div>

<style>
.stat-card {
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.stat-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12) !important;
}

.stat-icon {
    width: 60px;
    height: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.stat-title {
    font-size: 0.875rem;
    font-weight: 500;
}

.stat-value {
    font-size: 1.75rem;
    font-weight: 700;
    color: var(--text-dark);
}

.stat-card .stretched-link::after {
    position: absolute;
    top: 0;
    right: 0;
    bottom: 0;
    left: 0;
    z-index: 1;
    content: "";
}
</style>
