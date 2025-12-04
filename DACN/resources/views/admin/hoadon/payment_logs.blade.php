@extends('layouts.admin')

@section('content')
    <div class="container-fluid py-4">

        {{-- ============================
         🔥 HEADER
    ============================= --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold mb-0">
                <i class="bi bi-clock-history me-2"></i>
                Payment Logs – Hóa đơn #{{ $hoaDon->id }}
            </h2>

            <a href="{{ route('admin.hoadon.show', $hoaDon) }}" class="btn btn-secondary btn-sm">
                <i class="bi bi-arrow-left me-1"></i> Quay lại Hóa đơn
            </a>
        </div>


        {{-- ============================
         🔥 CARD LOG TABLE
    ============================= --}}
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white">
                <h5 class="mb-0 fw-semibold">
                    <i class="bi bi-list-check me-2"></i>
                    Lịch sử giao dịch thanh toán
                </h5>
                <small class="text-muted">
                    Toàn bộ request/response từ VNPay & MoMo
                </small>
            </div>

            <div class="card-body">

                {{-- Không có log --}}
                @if ($logs->isEmpty())
                    <div class="alert alert-info d-flex align-items-center">
                        <i class="bi bi-info-circle me-2 fs-5"></i>
                        Chưa có log thanh toán nào cho hóa đơn này.
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th width="60">ID</th>
                                    <th width="100">Provider</th>
                                    <th width="110">Event</th>
                                    <th width="160">Transaction Ref</th>
                                    <th width="110">Result Code</th>
                                    <th>Kết quả</th>
                                    <th width="130">IP</th>
                                    <th width="170">Thời gian</th>
                                    <th width="110">Payload</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($logs as $log)
                                    <tr>
                                        <td>{{ $log->id }}</td>

                                        {{-- Provider --}}
                                        <td>
                                            <span class="badge bg-{{ $log->provider == 'VNPAY' ? 'primary' : 'success' }}">
                                                {{ strtoupper($log->provider) }}
                                            </span>
                                        </td>

                                        {{-- Event --}}
                                        <td>
                                            @if ($log->event_type == 'request')
                                                <span class="badge bg-info">Request</span>
                                            @elseif($log->event_type == 'return')
                                                <span class="badge bg-warning">Return</span>
                                            @else
                                                <span class="badge bg-dark">IPN</span>
                                            @endif
                                        </td>

                                        {{-- Transaction Ref --}}
                                        <td><code>{{ $log->transaction_ref ?? '-' }}</code></td>

                                        {{-- Result Code --}}
                                        <td>
                                            @if ($log->result_code)
                                                <span
                                                    class="badge bg-{{ in_array($log->result_code, ['00', '0']) ? 'success' : 'danger' }}">
                                                    {{ $log->result_code }}
                                                </span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>

                                        {{-- Message --}}
                                        <td><small>{{ $log->result_message ?? '-' }}</small></td>

                                        {{-- IP --}}
                                        <td><code>{{ $log->ip_address ?? '-' }}</code></td>

                                        {{-- Timestamp --}}
                                        <td>
                                            <small>{{ $log->created_at->format('d/m/Y H:i:s') }}</small>
                                        </td>

                                        {{-- Action: View JSON payload --}}
                                        <td>
                                            <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal"
                                                data-bs-target="#payloadModal{{ $log->id }}">
                                                <i class="bi bi-code-square"></i> Xem
                                            </button>
                                        </td>
                                    </tr>


                                    {{-- ============================
                                 🔥 MODAL: JSON PAYLOAD
                            ============================= --}}
                                    <div class="modal fade" id="payloadModal{{ $log->id }}" tabindex="-1">
                                        <div class="modal-dialog modal-lg modal-dialog-scrollable">
                                            <div class="modal-content">

                                                <div class="modal-header">
                                                    <h5 class="modal-title">
                                                        Payload – Log #{{ $log->id }}
                                                        ({{ strtoupper($log->event_type) }})
                                                    </h5>
                                                    <button class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>

                                                <div class="modal-body">
                                                    <pre class="bg-light p-3 rounded" style="max-height: 500px;">
<code>{{ json_encode($log->payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</code>
                                            </pre>
                                                </div>

                                                <div class="modal-footer">
                                                    <button class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        {{ $logs->links() }}
                    </div>
                @endif
            </div>
        </div>

    </div>
@endsection
