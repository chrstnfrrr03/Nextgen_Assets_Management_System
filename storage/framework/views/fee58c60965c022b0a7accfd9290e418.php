<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e(config('app.name')); ?></title>

    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
</head>

<body class="flex items-center justify-center min-h-screen bg-slate-100">

    <div class="w-full max-w-md p-6 bg-white border shadow rounded-2xl">
        <?php echo $__env->yieldContent('content'); ?>
    </div>

</body>

</html><?php /**PATH C:\Users\akalisik\Project\backend\resources\views/layouts/guest.blade.php ENDPATH**/ ?>