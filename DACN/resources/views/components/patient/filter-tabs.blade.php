@props([
    'tabs' => [] // Array of ['id' => 'all', 'label' => 'Tất cả', 'icon' => 'fa-list']
])

<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <ul class="nav nav-pills filter-tabs mb-0" role="tablist">
            @foreach($tabs as $index => $tab)
            <li class="nav-item" role="presentation">
                <button class="nav-link {{ $index === 0 ? 'active' : '' }}"
                        id="{{ $tab['id'] }}-tab"
                        data-bs-toggle="pill"
                        data-bs-target="#{{ $tab['id'] }}"
                        type="button">
                    @if(isset($tab['icon']))
                    <i class="fas {{ $tab['icon'] }} me-2"></i>
                    @endif
                    {{ $tab['label'] }}
                    @if(isset($tab['count']))
                    <span class="badge bg-white text-dark ms-2">{{ $tab['count'] }}</span>
                    @endif
                </button>
            </li>
            @endforeach
        </ul>
    </div>
</div>

<style>
.filter-tabs .nav-link {
    border-radius: 50px;
    padding: 0.5rem 1.5rem;
    color: #6b7280;
    transition: all 0.3s;
    font-weight: 500;
    border: 2px solid transparent;
}

.filter-tabs .nav-link:hover {
    background-color: #f3f4f6;
    color: var(--primary-color);
}

.filter-tabs .nav-link.active {
    background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
    color: white;
    box-shadow: 0 4px 8px rgba(16, 185, 129, 0.3);
}

.filter-tabs .nav-link .badge {
    font-size: 0.75rem;
    padding: 0.25rem 0.5rem;
}
</style>
