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

    <div class="space-y-10">

        <!-- HEADER -->
        <div>
            <h1 class="text-3xl font-bold text-slate-800">
                Reports & Analytics
            </h1>
            <p class="text-sm text-gray-500">
                Overview of your asset data and system insights
            </p>
        </div>

        <!-- STATS -->
        <div class="grid grid-cols-1 gap-6 md:grid-cols-3">

            <div class="p-6 bg-white shadow rounded-2xl">
                <p class="text-sm text-gray-500">Total Assets</p>
                <h2 class="text-2xl font-bold text-slate-800">
                    <?php echo e(\App\Models\Item::count()); ?>

                </h2>
            </div>

            <div class="p-6 bg-white shadow rounded-2xl">
                <p class="text-sm text-gray-500">Total Brands</p>
                <h2 class="text-2xl font-bold text-slate-800">
                    <?php echo e(\App\Models\Item::distinct('brand')->count('brand')); ?>

                </h2>
            </div>

            <div class="p-6 bg-white shadow rounded-2xl">
                <p class="text-sm text-gray-500">Users</p>
                <h2 class="text-2xl font-bold text-slate-800">
                    <?php echo e(\App\Models\User::count()); ?>

                </h2>
            </div>

        </div>

        <!-- RECENT ACTIVITY -->
        <div class="p-6 bg-white shadow rounded-2xl">

            <h3 class="mb-4 font-semibold text-gray-700">
                Recent Assets Activity
            </h3>

            <div class="space-y-3">

                <?php $__currentLoopData = \App\Models\Item::latest()->take(5)->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="flex items-center justify-between p-3 bg-slate-50 rounded-lg">

                        <div>
                            <p class="font-medium text-gray-800">
                                <?php echo e($item->part_name); ?>

                            </p>
                            <p class="text-xs text-gray-500">
                                <?php echo e($item->brand); ?>

                            </p>
                        </div>

                        <span class="text-xs text-gray-400">
                            <?php echo e($item->created_at->diffForHumans()); ?>

                        </span>

                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            </div>

        </div>

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
<?php endif; ?><?php /**PATH C:\Users\akalisik\Project\backend\resources\views/reports.blade.php ENDPATH**/ ?>