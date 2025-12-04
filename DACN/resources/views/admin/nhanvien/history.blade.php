@extends('layouts.admin')

@section('content')

    <style>
        .card-custom {
            border-radius: 12px;
            border: none;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.06);
        }

        .change-item {
            padding: 4px 0;
            border-bottom: 1px dashed #eee;
        }

        .change-item:last-child {
            border-bottom: none;
        }
    </style>

    <div class="container-fluid py-4">

        {{-- HEADER --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-0">
                    <i class="bi bi-clock-history me-2 text-primary"></i>
                    Lịch sử thay đổi
                </h2>
                <small class="text-muted">{{ $nhanvien->ho_ten }}</small>
            </div>

            <a href="{{ route('admin.nhanvien.show', $nhanvien) }}" class="btn btn-secondary btn-sm">
                <i class="bi bi-arrow-left"></i> Quay lại
            </a>
        </div>

        {{-- CARD --}}
        <div class="card card-custom">
            <div class="card-body">

                @if ($audits->isEmpty())
                    <div class="alert alert-info mb-0">
                        <i class="bi bi-info-circle me-1"></i>
                        Chưa có thay đổi nào được ghi nhận.
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th style="width:15%">Thời gian</th>
                                    <th style="width:18%">Người thực hiện</th>
                                    <th style="width:12%">Hành động</th>
                                    <th>Chi tiết thay đổi</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($audits as $audit)
                                    <tr>
                                        {{-- TIME --}}
                                        <td class="text-muted">
                                            {{ $audit->created_at->format('d/m/Y H:i:s') }}
                                        </td>

                                        {{-- USER --}}
                                        <td>
                                            {{ $audit->user?->name ?? 'Hệ thống' }}
                                        </td>

                                        {{-- ACTION --}}
                                        <td>
                                            @if ($audit->action === 'created')
                                                <span class="badge bg-success">Tạo mới</span>
                                            @elseif($audit->action === 'updated')
                                                <span class="badge bg-warning text-dark">Cập nhật</span>
                                            @elseif($audit->action === 'deleted')
                                                <span class="badge bg-danger">Xóa</span>
                                            @endif
                                        </td>

                                        {{-- CHANGES --}}
                                        <td>
                                            @if ($audit->action === 'created')
                                                <span class="text-muted">Tạo nhân viên mới</span>
                                            @elseif($audit->action === 'deleted')
                                                <span class="text-muted">Nhân viên đã bị xóa</span>
                                            @elseif($audit->action === 'updated' && $audit->old_data && $audit->new_data)
                                                <div class="small">
                                                    @foreach ($audit->new_data as $key => $newValue)
                                                        @php
                                                            $oldValue = $audit->old_data[$key] ?? null;
                                                            $label = ucfirst(str_replace('_', ' ', $key));
                                                        @endphp

                                                        @if ($oldValue !== $newValue)
                                                            <div class="change-item">
                                                                <strong>{{ $label }}:</strong>
                                                                <span class="text-danger text-decoration-line-through">
                                                                    {{ $oldValue ?: '(trống)' }}
                                                                </span>
                                                                →
                                                                <span class="text-success">
                                                                    {{ $newValue ?: '(trống)' }}
                                                                </span>
                                                            </div>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>

                        </table>
                    </div>

                    {{-- PAGINATION --}}
                    <div class="mt-3">
                        {{ $audits->links() }}
                    </div>
                @endif

            </div>
        </div>

    </div>

@endsection
