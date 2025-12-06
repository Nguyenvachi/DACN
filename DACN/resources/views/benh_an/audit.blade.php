@php
    $role = auth()->user()->role ?? 'patient';
    $layout = match ($role) {
        'admin' => 'layouts.admin',
        'doctor' => 'layouts.doctor',
        'staff' => 'layouts.staff',
        'patient' => 'layouts.patient-modern',
        default => 'layouts.app',
    };
@endphp

@extends($layout)

@section('content')
    <div class="container-fluid py-4">

        {{-- HEADER --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold mb-0">
                <i class="bi bi-clock-history text-primary"></i>
                Lịch sử thay đổi Bệnh án
            </h3>

            <a href="{{ route($role . '.benhan.show', $benhAn) }}" class="btn btn-secondary d-flex align-items-center gap-2">
                <i class="bi bi-arrow-left"></i> Quay lại
            </a>
        </div>

        {{-- =============================
        THÔNG TIN BỆNH ÁN
    ============================== --}}
        <div class="card shadow-sm border-start border-primary border-3 mb-4">
            <div class="card-body">

                <h5 class="fw-semibold mb-3">
                    <i class="bi bi-file-medical"></i> Thông tin bệnh án
                </h5>

                <div class="row g-3 text-sm">
                    <div class="col-md-4"><strong>Mã BA:</strong> #{{ $benhAn->id }}</div>
                    <div class="col-md-4"><strong>Tiêu đề:</strong> {{ $benhAn->tieu_de }}</div>
                    <div class="col-md-4"><strong>Ngày khám:</strong> {{ $benhAn->ngay_kham->format('d/m/Y') }}</div>
                    <div class="col-md-4"><strong>Bệnh nhân:</strong> {{ $benhAn->benhNhan->name ?? 'N/A' }}</div>
                    <div class="col-md-4"><strong>Bác sĩ:</strong> {{ $benhAn->bacSi->ho_ten ?? 'N/A' }}</div>
                </div>

            </div>
        </div>


        {{-- =============================
        AUDIT LOGS
    ============================== --}}
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-light">
                <h5 class="fw-semibold mb-0 d-flex align-items-center gap-2">
                    <i class="bi bi-list-ul"></i>
                    Lịch sử thay đổi ({{ $audits->total() }} bản ghi)
                </h5>
            </div>

            @if ($audits->count() > 0)
                <div class="table-responsive">
                    <table id="benhAnAuditTable" class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Thời gian</th>
                                <th>Hành động</th>
                                <th>Người thực hiện</th>
                                <th>Chi tiết thay đổi</th>
                                <th>IP / User Agent</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($audits as $audit)
                                <tr>
                                    {{-- TIME --}}
                                    <td class="text-nowrap">
                                        {{ $audit->created_at->format('d/m/Y H:i:s') }}
                                    </td>

                                    {{-- ACTION --}}
                                    <td>
                                        <span
                                            class="badge
                                    @if ($audit->action === 'created') bg-success
                                    @elseif($audit->action === 'updated') bg-primary
                                    @else bg-danger @endif
                                ">
                                            {{ $audit->action_label }}
                                        </span>
                                    </td>

                                    {{-- USER --}}
                                    <td>
                                        @if ($audit->user)
                                            <div class="fw-semibold">{{ $audit->user->name }}</div>
                                            <div class="text-muted small">{{ $audit->user->email }}</div>
                                        @else
                                            <span class="text-muted fst-italic">System</span>
                                        @endif
                                    </td>

                                    {{-- CHANGE DETAILS --}}
                                    <td style="max-width: 450px;">
                                        @if ($audit->action === 'created' && $audit->new_values)
                                            <details>
                                                <summary class="text-primary cursor-pointer">Xem dữ liệu tạo mới</summary>
                                                <pre class="bg-light rounded p-2 mt-2 small border overflow-auto" style="max-height: 200px;">
{{ json_encode($audit->new_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}
                                        </pre>
                                            </details>
                                        @elseif($audit->action === 'updated' && $audit->old_values && $audit->new_values)
                                            <details>
                                                <summary class="text-primary cursor-pointer">Xem trường thay đổi</summary>
                                                <div class="mt-2 small">

                                                    @foreach ($audit->new_values as $key => $newVal)
                                                        @if (isset($audit->old_values[$key]) && $audit->old_values[$key] != $newVal)
                                                            <div class="p-2 bg-light rounded border mb-2">
                                                                <div class="fw-semibold">{{ $key }}:</div>
                                                                <div class="text-danger">- Cũ:
                                                                    {{ $audit->old_values[$key] ?? 'null' }}</div>
                                                                <div class="text-success">+ Mới: {{ $newVal ?? 'null' }}
                                                                </div>
                                                            </div>
                                                        @endif
                                                    @endforeach

                                                </div>
                                            </details>
                                        @elseif($audit->action === 'deleted' && $audit->old_values)
                                            <details>
                                                <summary class="text-primary cursor-pointer">Xem dữ liệu đã xoá</summary>
                                                <pre class="bg-light rounded p-2 mt-2 small border overflow-auto" style="max-height: 200px;">
{{ json_encode($audit->old_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}
                                        </pre>
                                            </details>
                                        @elseif(isset($audit->new_values['description']))
                                            <div class="text-muted">
                                                <i class="bi bi-info-circle text-primary"></i>
                                                {{ $audit->new_values['description'] }}
                                            </div>
                                        @else
                                            <span class="text-muted fst-italic">Không có dữ liệu</span>
                                        @endif
                                    </td>

                                    {{-- IP / USER AGENT --}}
                                    <td class="small text-muted">
                                        <div>{{ $audit->ip_address ?? 'N/A' }}</div>
                                        <div title="{{ $audit->user_agent }}">
                                            {{ Str::limit($audit->user_agent, 35) }}
                                        </div>
                                    </td>

                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- PAGINATION --}}
                <div class="card-footer bg-light">
                    {{ $audits->links() }}
                </div>
            @else
                <div class="p-5 text-center text-muted">
                    <i class="bi bi-inbox display-6"></i>
                    <p class="mt-2">Chưa có lịch sử thay đổi nào</p>
                </div>
            @endif

        </div>

    </div>
@endsection

{{-- DataTables Script --}}
<x-datatable-script tableId="benhAnAuditTable" />
