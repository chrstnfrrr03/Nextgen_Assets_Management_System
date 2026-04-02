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

            <div class="flex items-center gap-2">
                <a href="<?php echo e(route('assets.create')); ?>"
                    class="px-5 py-2 text-sm font-medium text-white bg-green-600 shadow rounded-xl hover:bg-green-700">
                    + Add Asset
                </a>

                <a href="<?php echo e(route('assets.export')); ?>"
                    class="px-5 py-2 text-sm font-medium text-white bg-blue-600 shadow rounded-xl hover:bg-blue-700">
                    Export CSV
                </a>
            </div>
        </div>

        <!-- TABLE -->
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
                        <tr class="hover:bg-slate-50">

                            <td class="px-6 py-4 font-medium"><?php echo e($item->part_no); ?></td>
                            <td class="px-6 py-4"><?php echo e($item->brand); ?></td>
                            <td class="px-6 py-4"><?php echo e($item->part_name); ?></td>

                            <!-- ASSIGNED -->
                            <td class="px-6 py-4 text-xs text-gray-600">
                                <?php $__empty_1 = true; $__currentLoopData = $item->activeAssignments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $assign): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <div>
                                        <?php if($assign->department): ?>
                                            <?php echo e($assign->department->name); ?>

                                        <?php endif; ?>

                                        <?php if($assign->user): ?>
                                            - <?php echo e($assign->user->name); ?>

                                        <?php endif; ?>

                                        (<?php echo e($assign->quantity); ?>)
                                        (<?php echo e($assign->quantity); ?>)
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    -
                                <?php endif; ?>
                            </td>

                            <!-- STATUS -->
                        <td class="px-6 py-4 text-xs">

                            <?php if($item->computed_status === 'out'): ?>
                                <span class="px-2 py-1 text-red-700 bg-red-100 rounded-lg">
                                    Out of Stock
                                </span>

                            <?php elseif($item->computed_status === 'partial'): ?>
                                <span class="px-2 py-1 text-yellow-700 bg-yellow-100 rounded-lg">
                                    <?php echo e($item->totalAssigned()); ?> Assigned /
                                    <?php echo e($item->availableQuantity()); ?> Available
                                </span>

                            <?php else: ?>
                                <span class="px-2 py-1 text-green-700 bg-green-100 rounded-lg">
                                    <?php echo e($item->availableQuantity()); ?> Available
                                </span>

                            <?php endif; ?>

                        </td>

                            <!-- ACTIONS -->
                            <td class="px-6 py-4 space-y-2">

                                <?php if($item->availableQuantity() > 0): ?>

                                    <form method="POST" action="<?php echo e(route('assets.assign', $item->id)); ?>">
                                        <?php echo csrf_field(); ?>

                                        <!-- Department (REQUIRED) -->
                                        <select name="department_id" required>
                                            <option value="">Select Department</option>
                                            <?php $__currentLoopData = $departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dept): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($dept->id); ?>"><?php echo e($dept->name); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>

                                        <!-- User (OPTIONAL) -->
                                        <select name="user_id">
                                            <option value="">Optional User</option>
                                            <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($user->id); ?>"><?php echo e($user->name); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>

                                        <!-- Quantity -->
                                        <input type="number" name="quantity" min="1" max="<?php echo e($item->availableQuantity()); ?>"
                                            placeholder="Qty max <?php echo e($item->availableQuantity()); ?>" required>

                                        <button>Assign</button>
                                    </form>

                                <?php else: ?>
                                    <div class="text-xs text-gray-400">
                                        No stock
                                    </div>
                                <?php endif; ?>

                                <!-- RETURNS -->
                                <?php $__currentLoopData = $item->activeAssignments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $assign): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <form method="POST" action="<?php echo e(route('assets.return', $assign->id)); ?>">
                                        <?php echo csrf_field(); ?>
                                        <button
                                            class="px-2 py-1 text-xs text-white bg-yellow-500 rounded-lg hover:bg-yellow-600">
                                            Return <?php echo e($assign->quantity); ?>

                                        </button>
                                    </form>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

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

            <h3 class="mb-4 font-semibold text-gray-700">Activity Log</h3>

            <?php $__empty_1 = true; $__currentLoopData = $logs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="flex justify-between p-3 mb-2 text-sm rounded-lg bg-slate-50">
                    <span>
                        <?php echo e(ucfirst($log->action)); ?> -
                        <?php echo e(optional($log->item)->part_name ?? 'Asset'); ?>

                        by <?php echo e(optional($log->user)->name ?? 'System'); ?>

                    </span>
                    <span class="text-xs text-gray-400">
                        <?php echo e($log->created_at?->diffForHumans()); ?>

                    </span>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="text-gray-400">No activity</div>
            <?php endif; ?>

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