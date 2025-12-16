

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-6">
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Quản lý hoàn tiền</h1>
            <p class="text-gray-600 mt-1">Danh sách tất cả yêu cầu hoàn tiền</p>
        </div>
    </div>

    <?php if(session('success')): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <?php echo e(session('error')); ?>

        </div>
    <?php endif; ?>

    <!-- Bộ lọc -->
    <div class="bg-white rounded-lg shadow-md p-4 mb-6">
        <form method="GET" class="flex gap-4 items-end">
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700 mb-2">Trạng thái</label>
                <select name="trang_thai" class="w-full border-gray-300 rounded-lg">
                    <option value="">Tất cả</option>
                    <option value="Đang xử lý" <?php echo e(request('trang_thai') === 'Đang xử lý' ? 'selected' : ''); ?>>Đang xử lý</option>
                    <option value="Hoàn thành" <?php echo e(request('trang_thai') === 'Hoàn thành' ? 'selected' : ''); ?>>Hoàn thành</option>
                    <option value="Từ chối" <?php echo e(request('trang_thai') === 'Từ chối' ? 'selected' : ''); ?>>Từ chối</option>
                </select>
            </div>
            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg">
                Lọc
            </button>
        </form>
    </div>

    <!-- Danh sách yêu cầu hoàn tiền -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mã HĐ</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bệnh nhân</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Số tiền</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lý do</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Trạng thái</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ngày yêu cầu</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Thao tác</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php $__empty_1 = true; $__currentLoopData = $hoanTiens; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $hoanTien): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <a href="<?php echo e(route('admin.hoadon.show', $hoanTien->hoaDon)); ?>" class="text-blue-600 hover:text-blue-800 font-medium">
                                #<?php echo e($hoanTien->hoaDon->ma_hoa_don ?? $hoanTien->hoa_don_id); ?>

                            </a>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm">
                                <div class="font-medium text-gray-900"><?php echo e($hoanTien->hoaDon->user->name ?? 'N/A'); ?></div>
                                <div class="text-gray-500"><?php echo e($hoanTien->hoaDon->user->email ?? ''); ?></div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-lg font-semibold text-red-600">
                                <?php echo e(number_format($hoanTien->so_tien, 0, ',', '.')); ?> VNĐ
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900 max-w-xs"><?php echo e($hoanTien->ly_do); ?></div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <?php if($hoanTien->trang_thai === 'Đang xử lý'): ?>
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    Đang xử lý
                                </span>
                            <?php elseif($hoanTien->trang_thai === 'Hoàn thành'): ?>
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Hoàn thành
                                </span>
                            <?php else: ?>
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                    Từ chối
                                </span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <?php echo e($hoanTien->created_at->format('d/m/Y H:i')); ?>

                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <?php if($hoanTien->trang_thai === 'Đang xử lý'): ?>
                                <div class="flex gap-2">
                                    <form method="POST" action="<?php echo e(route('admin.hoadon.refund.approve', $hoanTien)); ?>" class="inline">
                                        <?php echo csrf_field(); ?>
                                        <button type="submit" 
                                                onclick="return confirm('Xác nhận phê duyệt hoàn tiền <?php echo e(number_format($hoanTien->so_tien, 0, ',', '.')); ?> VNĐ?')"
                                                class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-xs font-medium">
                                            Phê duyệt
                                        </button>
                                    </form>
                                    <button type="button" 
                                            onclick="showRejectModal(<?php echo e($hoanTien->id); ?>)"
                                            class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-xs font-medium">
                                        Từ chối
                                    </button>
                                </div>
                            <?php else: ?>
                                <span class="text-gray-400 text-xs">Đã xử lý</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <p class="mt-2 text-sm">Không có yêu cầu hoàn tiền nào</p>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <?php if($hoanTiens->hasPages()): ?>
        <div class="mt-6">
            <?php echo e($hoanTiens->links()); ?>

        </div>
    <?php endif; ?>
</div>

<!-- Modal từ chối -->
<div id="rejectModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">Từ chối yêu cầu hoàn tiền</h3>
            <form id="rejectForm" method="POST">
                <?php echo csrf_field(); ?>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Lý do từ chối <span class="text-red-500">*</span></label>
                    <textarea name="ly_do_tu_choi" 
                              rows="4" 
                              required
                              class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                              placeholder="Nhập lý do từ chối yêu cầu hoàn tiền..."></textarea>
                </div>
                <div class="flex gap-3">
                    <button type="submit" class="flex-1 bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg">
                        Xác nhận từ chối
                    </button>
                    <button type="button" 
                            onclick="hideRejectModal()"
                            class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg">
                        Hủy
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function showRejectModal(hoanTienId) {
    const form = document.getElementById('rejectForm');
    form.action = `/admin/hoan-tien/${hoanTienId}/tu-choi`;
    document.getElementById('rejectModal').classList.remove('hidden');
}

function hideRejectModal() {
    document.getElementById('rejectModal').classList.add('hidden');
}

// Đóng modal khi click bên ngoài
document.getElementById('rejectModal').addEventListener('click', function(e) {
    if (e.target === this) {
        hideRejectModal();
    }
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH F:\WORKING\DACN\DACN\resources\views/admin/hoadon/all_refunds.blade.php ENDPATH**/ ?>