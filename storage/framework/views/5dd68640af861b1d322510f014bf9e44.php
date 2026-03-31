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

    <div class="space-y-8">

        <!-- HEADER -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-slate-800">Asset Management</h1>
                <p class="text-sm text-gray-500">Manage and track all company assets</p>
            </div>

            <!-- RIGHT ACTIONS -->
            <div class="flex items-center gap-2">

                <!--  ADD BUTTON (NEW) -->
                <a href="<?php echo e(route('assets.create')); ?>"
                   class="px-5 py-2 text-sm font-medium text-white bg-green-600 shadow rounded-xl hover:bg-green-700">
                    + Add Asset
                </a>

                <!-- EXPORT BUTTON -->
                <a href="<?php echo e(route('assets.export')); ?>"
                   class="px-5 py-2 text-sm font-medium text-white bg-blue-600 shadow rounded-xl hover:bg-blue-700">
                    Export CSV
                </a>

            </div>
        </div>

        <!-- TABLE CARD -->
        <div class="overflow-hidden bg-white border shadow-lg rounded-2xl">

            <div class="flex items-center justify-between px-6 py-4 border-b bg-slate-50">
                <h3 class="font-semibold text-gray-700">All Assets</h3>
                <span class="text-sm text-gray-400">Total: <?php echo e($items->total()); ?></span>
            </div>

            <table class="w-full text-sm">
                <thead class="text-xs text-gray-500 uppercase bg-slate-50">
                    <tr>
                        <th class="px-6 py-4 text-left">Code</th>
                        <th class="px-6 py-4 text-left">Brand</th>
                        <th class="px-6 py-4 text-left">Name</th>
                        <th class="px-6 py-4 text-left">Assigned</th>
                        <th class="px-6 py-4 text-left">Status</th>
                        <th class="px-6 py-4 text-left">Actions</th>
                    </tr>
                </thead>

                <tbody class="divide-y">

                    <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr class="transition hover:bg-slate-50">

                            <td class="px-6 py-4 font-medium"><?php echo e($item->part_no); ?></td>
                            <td class="px-6 py-4"><?php echo e($item->brand); ?></td>
                            <td class="px-6 py-4"><?php echo e($item->part_name); ?></td>

                            <td class="px-6 py-4 text-gray-500">
                                <?php echo e($item->user->name ?? '-'); ?>

                            </td>

                            <!-- STATUS -->
                            <td class="px-6 py-4">
                                <?php $status = $item->status ?? 'available'; ?>

                                <span class="px-3 py-1 text-xs font-semibold rounded-full
                                <?php echo e($status == 'available' ? 'bg-green-100 text-green-700' : ''); ?>

                                <?php echo e($status == 'assigned' ? 'bg-yellow-100 text-yellow-700' : ''); ?>

                                <?php echo e($status == 'maintenance' ? 'bg-red-100 text-red-700' : ''); ?>">
                                    <?php echo e(ucfirst($status)); ?>

                                </span>
                            </td>

                            <!-- ACTIONS -->
                            <td class="px-6 py-4">

                                <form method="POST" action="<?php echo e(route('assets.update', $item->id)); ?>"
                                    class="flex items-center gap-2">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('PUT'); ?>

                                    <!-- USER -->
                                    <select name="assigned_to"
                                        class="px-2 py-1 text-xs border rounded-lg focus:ring-2 focus:ring-blue-500">
                                        <option value="">Assign</option>
                                        <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($user->id); ?>" <?php echo e($item->assigned_to == $user->id ? 'selected' : ''); ?>>
                                                <?php echo e($user->name); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>

                                    <!-- STATUS -->
                                    <select name="status"
                                        class="px-2 py-1 text-xs border rounded-lg focus:ring-2 focus:ring-blue-500">
                                        <option value="available" <?php echo e($item->status == 'available' ? 'selected' : ''); ?>>
                                            Available</option>
                                        <option value="assigned" <?php echo e($item->status == 'assigned' ? 'selected' : ''); ?>>
                                            Assigned</option>
                                        <option value="maintenance" <?php echo e($item->status == 'maintenance' ? 'selected' : ''); ?>>
                                            Maintenance</option>
                                    </select>

                                    <button class="px-3 py-1 text-xs font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                                        Save
                                    </button>
                                </form>

                            </td>

                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                </tbody>
            </table>

            <!-- PAGINATION -->
            <div class="p-4">
                <?php echo e($items->links()); ?>

            </div>

        </div>

        <!-- ACTIVITY LOG -->
        <div class="p-6 bg-white border shadow-lg rounded-2xl">

            <div class="flex items-center justify-between mb-4">
                <h3 class="font-semibold text-gray-700">Activity Log</h3>
                <span class="text-xs text-gray-400">Latest actions</span>
            </div>

            <div class="space-y-3 text-sm">

                <?php $__empty_1 = true; $__currentLoopData = $logs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="flex items-center justify-between p-3 transition rounded-lg bg-slate-50 hover:bg-slate-100">

                        <div class="flex items-center gap-2">

                            <span class="px-2 py-1 text-xs font-medium rounded-full
                                <?php echo e($log->action == 'created' ? 'bg-green-100 text-green-700' : ''); ?>

                                <?php echo e($log->action == 'updated' ? 'bg-blue-100 text-blue-700' : ''); ?>

                                <?php echo e($log->action == 'deleted' ? 'bg-red-100 text-red-700' : ''); ?>">
                                <?php echo e(ucfirst($log->action)); ?>

                            </span>

                            <span class="text-gray-700">
                                <?php echo e(optional($log->item)->part_name ?? 'Asset'); ?>

                            </span>

                            <span class="text-gray-500">
                                by <?php echo e(optional($log->user)->name ?? 'System'); ?>

                            </span>

                        </div>

                        <span class="text-xs text-gray-400">
                            <?php echo e($log->created_at?->diffForHumans()); ?>

                        </span>

                    </div>

                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="py-6 text-center text-gray-400">
                        No activity yet
                    </div>
                <?php endif; ?>

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
<?php endif; ?><?php /**PATH C:\Users\akalisik\Project\backend\resources\views/items.blade.php ENDPATH**/ ?>