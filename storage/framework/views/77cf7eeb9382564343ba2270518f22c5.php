

<?php $__env->startSection('content'); ?>
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Assets</h1>
            <p class="text-sm text-slate-500">Manage asset records and lifecycle</p>
        </div>

        <div class="flex gap-2">
            <?php if(auth()->user()->isAdmin() || auth()->user()->isAssetOfficer()): ?>
                <a href="<?php echo e(route('items.create')); ?>"
                    class="px-4 py-2 text-sm font-semibold text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                    + Add Asset
                </a>

                <a href="<?php echo e(route('assignments.create')); ?>"
                    class="px-4 py-2 text-sm font-semibold text-white rounded-lg bg-slate-900 hover:bg-slate-800">
                    Assign
                </a>
            <?php endif; ?>
        </div>
    </div>

    
    <form method="GET" action="<?php echo e(route('items.index')); ?>" class="p-4 mb-5 bg-white shadow-sm rounded-xl">

        <div class="grid grid-cols-1 gap-3 md:grid-cols-4">

            <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Search assets..."
                class="px-3 py-2 text-sm border rounded-lg border-slate-300 focus:ring-2 focus:ring-blue-500">

            <select name="status" onchange="this.form.submit()"
                class="px-3 py-2 text-sm border rounded-lg border-slate-300">
                <option value="">All Status</option>
                <option value="available" <?php if(request('status') == 'available'): echo 'selected'; endif; ?>>Available</option>
                <option value="assigned" <?php if(request('status') == 'assigned'): echo 'selected'; endif; ?>>Assigned</option>
                <option value="maintenance" <?php if(request('status') == 'maintenance'): echo 'selected'; endif; ?>>Maintenance</option>
                <option value="retired" <?php if(request('status') == 'retired'): echo 'selected'; endif; ?>>Retired</option>
            </select>

            <select name="category_id" onchange="this.form.submit()"
                class="px-3 py-2 text-sm border rounded-lg border-slate-300">
                <option value="">All Categories</option>
                <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($category->id); ?>" <?php if(request('category_id') == $category->id): echo 'selected'; endif; ?>>
                        <?php echo e($category->name); ?>

                    </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>

            <select name="department_id" onchange="this.form.submit()"
                class="px-3 py-2 text-sm border rounded-lg border-slate-300">
                <option value="">All Departments</option>
                <?php $__currentLoopData = $departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $department): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($department->id); ?>" <?php if(request('department_id') == $department->id): echo 'selected'; endif; ?>>
                        <?php echo e($department->name); ?>

                    </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>

        <div class="flex gap-2 mt-3">
            <button class="px-4 py-2 text-sm text-white rounded-lg bg-slate-900 hover:bg-slate-800">
                Search
            </button>

            <a href="<?php echo e(route('items.index')); ?>"
                class="px-4 py-2 text-sm rounded-lg bg-slate-100 text-slate-700 hover:bg-slate-200">
                Reset
            </a>
        </div>
    </form>

    
    <div class="overflow-hidden bg-white shadow-sm rounded-xl">
        <table class="w-full text-sm">
            <thead class="text-xs uppercase bg-slate-50 text-slate-600">
                <tr>
                    <th class="px-4 py-3 text-left">Asset</th>
                    <th class="px-4 py-3 text-left">Tag</th>
                    <th class="px-4 py-3 text-left">Category</th>
                    <th class="px-4 py-3 text-left">Department</th>
                    <th class="px-4 py-3 text-left">Status</th>
                    <th class="px-4 py-3 text-left">Assigned</th>
                    <th class="px-4 py-3 text-left">Manage</th>
                </tr>
            </thead>

            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="transition border-t hover:bg-slate-50">

                        <td class="px-4 py-3 font-medium">
                            <a href="<?php echo e(route('items.show', $item)); ?>" class="hover:underline">
                                <?php echo e($item->name); ?>

                            </a>
                        </td>

                        <td class="px-4 py-3 text-slate-500">
                            <?php echo e($item->asset_tag ?? '-'); ?>

                        </td>

                        <td class="px-4 py-3">
                            <?php echo e($item->category?->name ?? '-'); ?>

                        </td>

                        <td class="px-4 py-3">
                            <?php echo e($item->department?->name ?? '-'); ?>

                        </td>

                        
                        <td class="px-4 py-3">
                            <?php
                                $map = [
                                    'available' => 'bg-green-100 text-green-700',
                                    'assigned' => 'bg-yellow-100 text-yellow-700',
                                    'maintenance' => 'bg-red-100 text-red-700',
                                    'retired' => 'bg-gray-200 text-gray-700'
                                ];
                            ?>

                            <span class="px-2 py-1 text-xs font-semibold rounded-full <?php echo e($map[$item->status] ?? ''); ?>">
                                <?php echo e(ucfirst($item->status)); ?>

                            </span>
                        </td>

                        
                        <td class="px-4 py-3 text-slate-600">
                            <?php echo e($item->activeAssignment?->user?->name ?? 'Unassigned'); ?>

                        </td>

                        
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">

                                <a href="<?php echo e(route('items.show', $item)); ?>" class="text-xs text-blue-600 hover:underline">
                                    View
                                </a>

                                <?php if(auth()->user()->isAdmin() || auth()->user()->isAssetOfficer()): ?>
                                    <a href="<?php echo e(route('items.edit', $item)); ?>" class="text-xs text-slate-600 hover:underline">
                                        Edit
                                    </a>
                                <?php endif; ?>

                                <?php if(auth()->user()->isAdmin()): ?>
                                    <form method="POST" action="<?php echo e(route('items.destroy', $item)); ?>"
                                        onsubmit="return confirm('Delete asset?')">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>

                                        <button class="text-xs text-red-600 hover:underline">
                                            Delete
                                        </button>
                                    </form>
                                <?php endif; ?>

                            </div>
                        </td>

                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="7" class="py-10 text-center text-slate-500">
                            No assets found
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    
    <div class="mt-4">
        <?php echo e($items->withQueryString()->links()); ?>

    </div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\akalisik\Project\backend\resources\views/items/index.blade.php ENDPATH**/ ?>