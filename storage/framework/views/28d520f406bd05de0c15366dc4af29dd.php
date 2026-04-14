<?php $__env->startSection('content'); ?>
    <?php if($dashboardMode === 'operations'): ?>
        <div class="space-y-8">

            <div class="flex flex-col gap-5 xl:flex-row xl:items-start xl:justify-between">
                <div>
                    <div
                        class="inline-flex items-center gap-2 px-3 py-1 text-xs font-medium bg-white border rounded-full shadow-sm border-slate-200 text-slate-500">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        System overview
                    </div>

                    <h1 class="mt-4 text-3xl font-bold tracking-tight text-slate-900 sm:text-4xl">
                        Admin Dashboard
                    </h1>

                    <p class="max-w-2xl mt-2 text-sm text-slate-500 sm:text-base">
                        Operational control center for assets, assignments, departments, and system activity.
                    </p>

                    <div class="flex flex-wrap gap-3 mt-4">
                        <a href="<?php echo e(route('items.create')); ?>"
                            class="inline-flex items-center rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700">
                            + Add Asset
                        </a>

                        <a href="<?php echo e(route('assignments.create')); ?>"
                            class="inline-flex items-center rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-slate-800">
                            + Assign Asset
                        </a>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 xl:w-[360px]">
                    <div class="p-4 bg-white border shadow-sm rounded-2xl border-slate-200">
                        <p class="text-xs font-medium tracking-wide uppercase text-slate-400">Assets</p>
                        <p class="mt-2 text-2xl font-bold text-slate-900"><?php echo e($totalAssets); ?></p>
                    </div>
                    <div class="p-4 bg-white border shadow-sm rounded-2xl border-slate-200">
                        <p class="text-xs font-medium tracking-wide uppercase text-slate-400">Active</p>
                        <p class="mt-2 text-2xl font-bold text-slate-900"><?php echo e($activeAssignments); ?></p>
                    </div>
                    <div class="p-4 bg-white border shadow-sm rounded-2xl border-slate-200">
                        <p class="text-xs font-medium tracking-wide uppercase text-slate-400">Notifications</p>
                        <p class="mt-2 text-2xl font-bold text-slate-900"><?php echo e($unreadNotifications->count()); ?></p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 2xl:grid-cols-6">
                <?php
                    $stats = [
                        ['Total Assets', $totalAssets, 'from-slate-900 to-slate-700'],
                        ['Available', $availableAssets, 'from-emerald-500 to-emerald-400'],
                        ['Assigned', $assignedAssets, 'from-amber-500 to-yellow-400'],
                        ['Maintenance', $maintenanceAssets, 'from-rose-500 to-red-400'],
                        ['Low Stock', $lowStockAssets, 'from-orange-500 to-orange-400'],
                        ['Overdue', $overdueAssignments, 'from-red-600 to-red-500'],
                    ];
                ?>

                <?php $__currentLoopData = $stats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$label, $value, $gradient]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="stat-card bg-gradient-to-br <?php echo e($gradient); ?>">
                        <p class="text-xs font-medium tracking-wide uppercase text-white/75"><?php echo e($label); ?></p>
                        <h2 class="mt-4 text-3xl font-bold text-white"><?php echo e($value); ?></h2>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

            <div class="grid grid-cols-1 gap-6 xl:grid-cols-4">
                <div class="space-y-6 xl:col-span-3">
                    <div class="overflow-hidden card card-hover">
                        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-200">
                            <div>
                                <h2 class="text-lg font-semibold text-slate-900">Recent Assignments</h2>
                                <p class="mt-1 text-sm text-slate-500">Latest asset movement across departments.</p>
                            </div>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="min-w-full text-sm">
                                <thead class="bg-slate-50 text-slate-600">
                                    <tr>
                                        <th class="px-6 py-4 font-semibold text-left">Asset</th>
                                        <th class="px-6 py-4 font-semibold text-left">User</th>
                                        <th class="px-6 py-4 font-semibold text-left">Department</th>
                                        <th class="px-6 py-4 font-semibold text-left">Date</th>
                                        <th class="px-6 py-4 font-semibold text-left">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    <?php $__empty_1 = true; $__currentLoopData = $recentAssignments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $assignment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <tr class="transition hover:bg-blue-50/60">
                                            <td class="px-6 py-4">
                                                <div class="font-medium text-slate-900"><?php echo e($assignment->item?->name ?? '-'); ?></div>
                                            </td>
                                            <td class="px-6 py-4 text-slate-700"><?php echo e($assignment->user?->name ?? '-'); ?></td>
                                            <td class="px-6 py-4 text-slate-700"><?php echo e($assignment->assignedDepartment?->name ?? '-'); ?>

                                            </td>
                                            <td class="px-6 py-4 text-slate-500"><?php echo e($assignment->assigned_at?->format('d M Y')); ?>

                                            </td>
                                            <td class="px-6 py-4">
                                                <span class="badge badge-success">Active</span>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <tr>
                                            <td colspan="5" class="px-6 py-10 text-center text-slate-500">
                                                No recent assignments.
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
                        <div class="card card-hover">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-base font-semibold text-slate-900">Category Summary</h3>
                            </div>

                            <div class="space-y-3">
                                <?php $__currentLoopData = $categorySummary; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="flex items-center justify-between px-3 py-2 text-sm rounded-xl bg-slate-50">
                                        <span class="text-slate-700"><?php echo e($category->name); ?></span>
                                        <span class="font-semibold text-slate-900"><?php echo e($category->items_count); ?></span>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>

                        <div class="card card-hover">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-base font-semibold text-slate-900">Departments</h3>
                            </div>

                            <div class="space-y-3">
                                <?php $__currentLoopData = $departmentSummary; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $department): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="flex items-center justify-between px-3 py-2 text-sm rounded-xl bg-slate-50">
                                        <span class="text-slate-700"><?php echo e($department->name); ?></span>
                                        <span class="font-semibold text-slate-900"><?php echo e($department->items_count); ?></span>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>

                        <div class="card card-hover">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-base font-semibold text-slate-900">User Roles</h3>
                            </div>

                            <div class="space-y-3 text-sm">
                                <div class="flex items-center justify-between px-3 py-2 rounded-xl bg-slate-50">
                                    <span class="text-slate-700">Admins</span>
                                    <span class="font-semibold text-slate-900"><?php echo e($usersByRole['admin'] ?? 0); ?></span>
                                </div>
                                <div class="flex items-center justify-between px-3 py-2 rounded-xl bg-slate-50">
                                    <span class="text-slate-700">Managers</span>
                                    <span class="font-semibold text-slate-900"><?php echo e($usersByRole['manager'] ?? 0); ?></span>
                                </div>
                                <div class="flex items-center justify-between px-3 py-2 rounded-xl bg-slate-50">
                                    <span class="text-slate-700">Officers</span>
                                    <span class="font-semibold text-slate-900"><?php echo e($usersByRole['asset_officer'] ?? 0); ?></span>
                                </div>
                                <div class="flex items-center justify-between px-3 py-2 rounded-xl bg-slate-50">
                                    <span class="text-slate-700">Staff</span>
                                    <span class="font-semibold text-slate-900"><?php echo e($usersByRole['staff'] ?? 0); ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="space-y-6 xl:col-span-1">
                    <div class="card card-hover">
                        <div class="pb-4 border-b border-slate-200">
                            <h2 class="text-lg font-semibold text-slate-900">System Activity</h2>
                            <p class="mt-1 text-sm text-slate-500">Live audit trail of key actions.</p>
                        </div>

                        <div class="mt-5 space-y-5">
                            <?php $__empty_1 = true; $__currentLoopData = $recentActivity; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $activity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <div class="flex items-start gap-3">
                                    <div class="mt-2 h-2.5 w-2.5 rounded-full bg-blue-500"></div>

                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-semibold text-slate-900">
                                            <?php echo e(ucfirst(str_replace('_', ' ', $activity->action))); ?>

                                        </p>
                                        <p class="mt-1 text-sm text-slate-600"><?php echo e($activity->item?->name ?? 'Unknown asset'); ?></p>
                                        <p class="mt-1 text-xs text-slate-400">
                                            <?php echo e($activity->created_at?->diffForHumans()); ?>

                                        </p>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <p class="text-sm text-slate-500">No activity found.</p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="card card-hover">
                        <div class="pb-4 border-b border-slate-200">
                            <h2 class="text-lg font-semibold text-slate-900">Notifications</h2>
                            <p class="mt-1 text-sm text-slate-500">Unread alerts for the administrator.</p>
                        </div>

                        <div class="mt-5 space-y-3">
                            <?php $__empty_1 = true; $__currentLoopData = $unreadNotifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <a href="<?php echo e(route('notifications.open', $notification)); ?>"
                                    class="block px-4 py-3 transition border rounded-2xl border-slate-200 bg-slate-50 hover:border-slate-300 hover:bg-white">
                                    <p class="text-sm font-semibold text-slate-900"><?php echo e($notification->title); ?></p>
                                    <p class="mt-1 text-xs text-slate-500"><?php echo e($notification->message); ?></p>
                                </a>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <p class="text-sm text-slate-500">No notifications.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="p-8 bg-white border shadow-sm rounded-2xl border-slate-200">
            <h1 class="text-2xl font-bold text-slate-900">My Workspace</h1>
            <p class="mt-2 text-slate-500">User dashboard upgrade comes next.</p>
        </div>
    <?php endif; ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\akalisik\Nextgen-assets-management-system\resources\views/dashboard/index.blade.php ENDPATH**/ ?>