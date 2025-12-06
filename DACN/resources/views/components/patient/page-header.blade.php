@props([
    'title' => '',
    'subtitle' => '',
    'icon' => 'fa-home',
    'actionRoute' => null,
    'actionText' => null,
    'actionIcon' => 'fa-plus'
])

<div class="page-header mb-4">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h2 class="page-title mb-1">
                @if($icon)
                <i class="fas {{ $icon }} me-2"></i>
                @endif
                {{ $title }}
            </h2>
            @if($subtitle)
            <p class="page-subtitle text-muted mb-0">{{ $subtitle }}</p>
            @endif
        </div>

        @if($actionRoute && $actionText)
        <a href="{{ $actionRoute }}" class="btn btn-primary">
            <i class="fas {{ $actionIcon }} me-2"></i>{{ $actionText }}
        </a>
        @endif
    </div>
</div>

<style>
.page-header {
    animation: fadeInDown 0.5s ease;
}

.page-title {
    font-size: 1.75rem;
    font-weight: 700;
    color: var(--text-dark);
}

.page-subtitle {
    font-size: 0.95rem;
    color: var(--text-light);
}

@keyframes fadeInDown {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>
