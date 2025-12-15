@php
    $lh = $benhAn->lichHen;
@endphp
<div class="card shadow-sm mb-4 sticky-top" style="top: 20px;">
    <div class="card-body">
        <div class="d-grid gap-2">
            @can('update', $benhAn)
                <a href="{{ route(auth()->user()->isAdmin() ? 'admin.benhan.edit' : 'doctor.benhan.edit', $benhAn) }}" class="btn btn-primary">Sửa bệnh án</a>
            @endcan

            @if($lh)
                @can('startExam', $lh)
                    @if($lh->canStartExam())
                        <form method="POST" action="{{ route('doctor.queue.start', $lh->id) }}" class="ajax-action start-form" data-success-redirect="{{ route('doctor.benhan.edit', $benhAn->id) }}">
                            @csrf
                            <button type="submit" class="btn btn-success">Bắt đầu khám</button>
                        </form>
                    @endif
                @endcan

                @can('completeExam', $lh)
                    @if($lh->canCompleteExam())
                        <form method="POST" action="{{ route('doctor.lichhen.complete', $lh->id) }}" class="ajax-action complete-form">
                            @csrf
                            <button type="submit" class="btn btn-danger">Hoàn tất khám</button>
                        </form>
                    @endif
                @endcan

                @can('view', $benhAn)
                    <a href="{{ route((auth()->user()->isAdmin() ? 'admin' : 'doctor') . '.benhan.exportPdf', $benhAn) }}" class="btn btn-outline-secondary">Xuất PDF</a>
                @endcan
            @endif

            @can('update', $benhAn)
                <a href="{{ route('benhan.donthuoc.create', $benhAn) }}" class="btn btn-outline-warning">Kê đơn</a>
            @endcan

        </div>
    </div>
</div>
