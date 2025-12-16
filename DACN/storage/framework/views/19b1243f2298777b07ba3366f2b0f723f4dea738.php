<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo e(config('app.name', 'Laravel')); ?></title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <?php echo $__env->yieldPushContent('meta'); ?>
    <?php echo $__env->yieldPushContent('styles'); ?>

    <!-- ADDED: Unified design system -->
    <link rel="stylesheet" href="<?php echo e(asset('css/design-system-unified.css')); ?>">
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100">

        
        <?php echo $__env->make('layouts.navigation', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

        
        <?php if(View::hasSection('header')): ?>
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
                    <?php echo $__env->yieldContent('header'); ?>
                </div>
            </header>
        <?php elseif(isset($header)): ?>
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
                    <?php echo e($header); ?>

                </div>
            </header>
        <?php endif; ?>

        
        <main class="py-2">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <?php if (! empty(trim($__env->yieldContent('content')))): ?>
                    <?php echo $__env->yieldContent('content'); ?>
                <?php elseif(isset($slot)): ?>
                    <?php echo e($slot); ?>

                <?php endif; ?>
            </div>
        </main>

    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>

</html>
<?php /**PATH F:\WORKING\DACN\DACN\resources\views/layouts/app.blade.php ENDPATH**/ ?>