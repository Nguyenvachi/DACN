<div class="card shadow-sm mb-4">
    <div class="card-body">
        <h6 class="fw-semibold mb-3">Tệp đính kèm</h6>
        <ul class="list-unstyled">
            @forelse($benhAn->files as $f)
                <li class="mb-2 d-flex align-items-center gap-2">
                    @php $disk = $f->disk ?? $f->disk_name ?? 'public'; @endphp
                    @if($disk === 'benh_an_private')
                        <a href="{{ URL::temporarySignedRoute((auth()->user()->isDoctor() ? 'doctor' : auth()->user()->roleKey()) . '.benhan.files.download', now()->addMinutes(10), ['file' => $f->id]) }}">{{ $f->ten_file ?? basename($f->path) }}</a>
                        @else
                        <a href="{{ url(Storage::url($f->path)) }}" target="_blank">{{ $f->ten_file ?? basename($f->path) }}</a>
                    @endif

                    <small class="ms-auto text-muted">{{ $f->created_at?->format('d/m/Y H:i') }}</small>
                </li>
            @empty
                <li class="text-muted">Chưa có tệp đính kèm</li>
            @endforelse
        </ul>
    </div>
</div>
