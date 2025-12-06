{{--
    Component DataTable Script
    Sử dụng: Thêm vào cuối mỗi view có table cần filter

    Props:
    - tableId: ID của table (mặc định: 'dataTable')
    - config: JSON config tùy chỉnh (optional)

    Ví dụ:
    <x-datatable-script tableId="myTable" />
--}}

@props(['tableId' => 'dataTable', 'config' => '{}'])

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
@endpush

@push('scripts')
@once
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
@endonce
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>

<script>
$(document).ready(function() {
    const defaultConfig = {
        language: {
            "sProcessing": "Đang xử lý...",
            "sLengthMenu": "Hiển thị _MENU_ dòng",
            "sZeroRecords": "Không tìm thấy dữ liệu",
            "sInfo": "Hiển thị _START_ đến _END_ trong tổng số _TOTAL_ dòng",
            "sInfoEmpty": "Hiển thị 0 đến 0 trong tổng số 0 dòng",
            "sInfoFiltered": "(lọc từ _MAX_ dòng)",
            "sSearch": "Tìm kiếm:",
            "oPaginate": {
                "sFirst": "Đầu",
                "sPrevious": "Trước",
                "sNext": "Tiếp",
                "sLast": "Cuối"
            }
        },
        responsive: true,
        pageLength: 25,
        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Tất cả"]],
        order: [[0, 'desc']],
        columnDefs: [
            { orderable: false, targets: -1 }
        ]
    };

    const customConfig = {!! $config !!};
    const finalConfig = { ...defaultConfig, ...customConfig };

    if (!$.fn.DataTable.isDataTable('#{{ $tableId }}')) {
        $('#{{ $tableId }}').DataTable(finalConfig);
    }
});
</script>
@endpush
