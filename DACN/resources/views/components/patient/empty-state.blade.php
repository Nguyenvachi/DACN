@props([
    'icon' => 'fa-inbox',
    'title' => 'Không có dữ liệu',
    'description' => '',
    'actionRoute' => null,
    'actionText' => null,
    'actionIcon' => 'fa-plus'
])

<div class="empty-state text-center py-5">
    <div class="empty-icon mb-4">
        <i class="fas {{ $icon }} fa-4x text-muted opacity-50"></i>
    </div>
    <h4 class="empty-title text-muted mb-3">{{ $title }}</h4>

    @if($description)
    <p class="empty-description text-muted mb-4">{{ $description }}</p>
    @endif

    @if($actionRoute && $actionText)
    <a href="{{ $actionRoute }}" class="btn btn-primary btn-lg">
        <i class="fas {{ $actionIcon }} me-2"></i>{{ $actionText }}
    </a>
    @endif

    {{ $slot }}
</div>

<style>
.empty-state {
    animation: fadeIn 0.5s ease;
}

.empty-icon {
    animation: pulse 2s ease-in-out infinite;
}

.empty-title {
    font-size: 1.25rem;
    font-weight: 600;
}

.empty-description {
    font-size: 1rem;
    max-width: 500px;
    margin-left: auto;
    margin-right: auto;
}

@keyframes fadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}

@keyframes pulse {
    0%, 100% {
        transform: scale(1);
    }
    50% {
        transform: scale(1.05);
    }
}
</style>
