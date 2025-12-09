{{-- File: resources/views/components/empty-state.blade.php --}}
{{-- Parent: resources/views/components/ --}}

@props([
    'icon' => 'bi-inbox',
    'title' => 'Không có dữ liệu',
    'description' => null,
    'actionText' => null,
    'actionUrl' => null,
    'actionIcon' => 'bi-plus-lg'
])

<div {{ $attributes->merge(['class' => 'text-center py-5']) }}>

    {{-- Icon --}}
    <div class="mb-4">
        <i class="bi {{ $icon }} text-muted" style="font-size: 4rem; opacity: 0.3;"></i>
    </div>

    {{-- Title --}}
    <h5 class="text-muted fw-semibold mb-2">{{ $title }}</h5>

    {{-- Description --}}
    @if($description)
        <p class="text-muted mb-4">{{ $description }}</p>
    @endif

    {{-- Slot for custom content --}}
    {{ $slot }}

    {{-- Action Button --}}
    @if($actionText && $actionUrl)
        <div class="mt-4">
            <a href="{{ $actionUrl }}" class="btn btn-primary">
                <i class="bi {{ $actionIcon }} me-2"></i>{{ $actionText }}
            </a>
        </div>
    @endif

</div>

<style>
.empty-state-icon {
    animation: float 3s ease-in-out infinite;
}

@keyframes float {
    0%, 100% {
        transform: translateY(0);
    }
    50% {
        transform: translateY(-10px);
    }
}
</style>
