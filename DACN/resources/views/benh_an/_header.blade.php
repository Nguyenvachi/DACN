<div class="card shadow-sm mb-4 border-start border-primary border-3">
    <div class="card-body d-flex justify-content-between align-items-center">
        <div>
            <h5 class="fw-semibold mb-0">Bệnh án #{{ $benhAn->id ?? $record->id }} - {{ $benhAn->tieu_de ?? ($record->tieu_de ?? '—') }}</h5>
            <div class="text-muted small">Bệnh nhân: {{ $benhAn->benhNhan->name ?? $record->benhNhan->name ?? '—' }}</div>
        </div>
        <div class="d-flex gap-2">
            {{-- Action buttons injected by parent view --}}
            @if(isset($actionButtons))
                {!! $actionButtons !!}
            @endif
        </div>
    </div>
</div>
