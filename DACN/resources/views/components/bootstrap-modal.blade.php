{{-- File: resources/views/components/bootstrap-modal.blade.php --}}
{{-- Parent: resources/views/components/ --}}

@props([
    'id' => 'modal',
    'title' => 'Modal Title',
    'size' => 'md', // sm, md, lg, xl
    'centered' => false,
    'backdrop' => 'true', // true, false, static
    'keyboard' => 'true',
    'showFooter' => true
])

<div class="modal fade" id="{{ $id }}" tabindex="-1"
     data-bs-backdrop="{{ $backdrop }}"
     data-bs-keyboard="{{ $keyboard }}"
     aria-labelledby="{{ $id }}Label"
     aria-hidden="true">
    <div class="modal-dialog modal-{{ $size }} {{ $centered ? 'modal-dialog-centered' : '' }} modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg">

            {{-- Header --}}
            <div class="modal-header border-0 bg-gradient-primary text-white">
                <h5 class="modal-title fw-bold" id="{{ $id }}Label">
                    {{ $title }}
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            {{-- Body --}}
            <div class="modal-body">
                {{ $slot }}
            </div>

            {{-- Footer --}}
            @if($showFooter)
                <div class="modal-footer border-0 bg-light">
                    @isset($footer)
                        {{ $footer }}
                    @else
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    @endisset
                </div>
            @endif

        </div>
    </div>
</div>

<style>
.bg-gradient-primary {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
}
.modal-content {
    border-radius: 1rem;
    overflow: hidden;
}
.modal-header {
    padding: 1.5rem;
}
.modal-body {
    padding: 2rem;
}
.modal-footer {
    padding: 1rem 2rem;
}
</style>
