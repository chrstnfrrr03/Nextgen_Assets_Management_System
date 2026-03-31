<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54 = $attributes; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\AppLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>

    <div class="max-w-2xl mx-auto space-y-6">

        <h1 class="text-2xl font-bold text-gray-800">Add New Asset</h1>

        <?php if($errors->any()): ?>
            <div class="p-4 text-red-700 bg-red-100 rounded-lg">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div>• <?php echo e($error); ?></div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="<?php echo e(route('assets.store')); ?>" class="space-y-4">
            <?php echo csrf_field(); ?>

            <input name="part_no" placeholder="Part Number" class="w-full px-4 py-2 border rounded-lg" required>

            <input name="brand" placeholder="Brand" class="w-full px-4 py-2 border rounded-lg" required>

            <input name="part_name" placeholder="Name" class="w-full px-4 py-2 border rounded-lg" required>

            <input name="description" placeholder="Description" class="w-full px-4 py-2 border rounded-lg" required>

            <button class="w-full py-2 text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                Save Asset
            </button>
        </form>

    </div>

 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?><?php /**PATH C:\Users\akalisik\Project\backend\resources\views/items-create.blade.php ENDPATH**/ ?>