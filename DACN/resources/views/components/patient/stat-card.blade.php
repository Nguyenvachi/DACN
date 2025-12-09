@props([
    'title' => '',
    'value' => 0,
    'icon' => 'fa-chart-line',
    'color' => 'primary', // primary, success, warning, danger, info
    'route' => null
])

@php
    // ENHANCED: Modern gradient configs with VietCare design (Parent: components/patient/stat-card.blade.php)
    $colorConfig = [
        'primary' => [
            'gradient' => 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)',
            'shadow' => 'rgba(102, 126, 234, 0.4)'
        ],
        'success' => [
            'gradient' => 'linear-gradient(135deg, #10b981 0%, #059669 100%)',
            'shadow' => 'rgba(16, 185, 129, 0.4)'
        ],
        'warning' => [
            'gradient' => 'linear-gradient(135deg, #f59e0b 0%, #d97706 100%)',
            'shadow' => 'rgba(245, 158, 11, 0.4)'
        ],
        'danger' => [
            'gradient' => 'linear-gradient(135deg, #ef4444 0%, #dc2626 100%)',
            'shadow' => 'rgba(239, 68, 68, 0.4)'
        ],
        'info' => [
            'gradient' => 'linear-gradient(135deg, #3b82f6 0%, #2563eb 100%)',
            'shadow' => 'rgba(59, 130, 246, 0.4)'
        ],
    ];

    $config = $colorConfig[$color] ?? $colorConfig['primary'];
@endphp

<div class="stat-card-modern card border-0 h-100"
     style="background: {{ $config['gradient'] }}; box-shadow: 0 4px 15px {{ $config['shadow'] }};">
    <div class="card-body text-white p-4">
        <div class="d-flex align-items-center">
            <div class="flex-shrink-0">
                <div class="p-3 rounded-circle bg-white bg-opacity-25 backdrop-blur">
                    <i class="fas {{ $icon }} fa-2x text-white"></i>
                </div>
            </div>
            <div class="flex-grow-1 ms-3">
                <p class="text-white text-opacity-75 mb-1 small fw-medium">{{ $title }}</p>
                <h3 class="mb-0 fw-bold text-white">{{ $value }}</h3>
            </div>
        </div>

        @if($route)
        <a href="{{ $route }}" class="stretched-link text-decoration-none"></a>
        @endif
    </div>
</div>

<style>
.stat-card-modern {
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.stat-card-modern:hover {
    transform: translateY(-5px);
}
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
