

<?php $__env->startSection('title', 'Tin nhắn bệnh nhân'); ?>

<?php $__env->startSection('content'); ?>
    <div class="container-fluid py-4">
        <h2 class="mb-4">
            <i class="bi bi-chat-dots"></i> Tin nhắn bệnh nhân
        </h2>

        <div class="row">
            <?php $__empty_1 = true; $__currentLoopData = $conversations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $conversation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="col-md-6 mb-3">
                    <div class="card border-0 shadow-sm h-100 hover-shadow" style="cursor: pointer;"
                        onclick="window.location='<?php echo e(route('doctor.chat.show', $conversation)); ?>'">
                        <div class="card-body">
                            <div class="d-flex align-items-start">
                                <div class="me-3">
                                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center"
                                        style="width: 50px; height: 50px;">
                                        <i class="bi bi-person fs-4"></i>
                                    </div>
                                </div>

                                <div class="flex-grow-1">
                                    <h5 class="mb-1"><?php echo e($conversation->benhNhan->name); ?></h5>
                                    <p class="text-muted small mb-2"><?php echo e($conversation->benhNhan->email); ?></p>

                                    <?php if($conversation->latestMessage): ?>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <p class="mb-0 text-truncate me-2" style="max-width: 300px;">
                                                <?php if($conversation->latestMessage->user_id == auth()->id()): ?>
                                                    <strong>Bạn:</strong>
                                                <?php endif; ?>
                                                <?php echo e(Str::limit($conversation->latestMessage->noi_dung, 40)); ?>

                                            </p>
                                            <small class="text-muted">
                                                <?php echo e($conversation->last_message_at->diffForHumans()); ?>

                                            </small>
                                        </div>
                                    <?php else: ?>
                                        <p class="text-muted small mb-0">Bắt đầu cuộc trò chuyện</p>
                                    <?php endif; ?>

                                    <?php
                                        $unreadCount = $conversation->getUnreadCountForUser(auth()->id());
                                    ?>
                                    <?php if($unreadCount > 0): ?>
                                        <span class="badge bg-danger rounded-pill mt-2">
                                            <?php echo e($unreadCount); ?> tin nhắn mới
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center py-5">
                            <i class="bi bi-chat-dots fs-1 text-muted d-block mb-3"></i>
                            <h5>Chưa có cuộc trò chuyện nào</h5>
                            <p class="text-muted">Bệnh nhân sẽ có thể chat với bạn sau khi có lịch hẹn</p>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <style>
        .hover-shadow {
            transition: all 0.3s ease;
        }

        .hover-shadow:hover {
            transform: translateY(-2px);
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
        }
    </style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.doctor', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH F:\WORKING\DACN\DACN\resources\views/doctor/chat/index.blade.php ENDPATH**/ ?>