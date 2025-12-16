

<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag; ?>
<?php foreach($attributes->onlyProps(['tableId' => 'dataTable', 'config' => '{}']) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $attributes = $attributes->exceptProps(['tableId' => 'dataTable', 'config' => '{}']); ?>
<?php foreach (array_filter((['tableId' => 'dataTable', 'config' => '{}']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $__defined_vars = get_defined_vars(); ?>
<?php foreach ($attributes as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
} ?>
<?php unset($__defined_vars); ?>

<?php $__env->startPush('styles'); ?>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<?php if (! $__env->hasRenderedOnce('78b6202e-a248-4fc1-aacb-26c0f3aae007')): $__env->markAsRenderedOnce('78b6202e-a248-4fc1-aacb-26c0f3aae007'); ?>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<?php endif; ?>
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

    const customConfig = <?php echo $config; ?>;
    const finalConfig = { ...defaultConfig, ...customConfig };

    if (!$.fn.DataTable.isDataTable('#<?php echo e($tableId); ?>')) {
        $('#<?php echo e($tableId); ?>').DataTable(finalConfig);
    }
});
</script>
<?php $__env->stopPush(); ?>
<?php /**PATH F:\WORKING\DACN\DACN\resources\views/components/datatable-script.blade.php ENDPATH**/ ?>