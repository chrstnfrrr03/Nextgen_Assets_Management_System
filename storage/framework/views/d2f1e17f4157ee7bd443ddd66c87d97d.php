

<?php $__env->startSection('content'); ?>
    <div>
        <label>Status</label>

        <div class="px-4 py-2 border rounded-lg bg-slate-50">
            <?php
                $statusColors = [
                    'available' => 'bg-emerald-100 text-emerald-700',
                    'assigned' => 'bg-amber-100 text-amber-700',
                    'maintenance' => 'bg-red-100 text-red-700',
                    'retired' => 'bg-slate-100 text-slate-500',
                ];
            ?>

            <span class="px-3 py-1 text-xs font-semibold rounded-full <?php echo e($statusColors[$item->status]); ?>">
                <?php echo e(ucfirst($item->status)); ?>

            </span>

            <p class="mt-1 text-xs text-slate-400">
                Status is managed automatically by assignments and inventory actions.
            </p>

            <input type="hidden" name="status" value="<?php echo e($item->status); ?>">
        </div>

        <div class="px-4 py-2 mt-2 text-sm border rounded-lg bg-slate-50 text-slate-500">
            Quantity: <?php echo e($item->quantity); ?> (fixed — each record = 1 unit)
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\akalisik\Project\backend\resources\views/items/edit.blade.php ENDPATH**/ ?>