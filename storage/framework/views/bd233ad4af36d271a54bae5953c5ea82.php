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
        <div>
            <h1 class="text-3xl font-bold text-slate-800">Categories Management</h1>
            <p class="text-sm text-gray-500">Organize your products into categories</p>
        </div>

        <!-- SUCCESS -->
        <?php if(session('success')): ?>
            <div class="p-4 text-green-700 bg-green-100 rounded-lg">
                <?php echo e(session('success')); ?>

            </div>
        <?php endif; ?>

        <!-- ERRORS -->
        <?php if($errors->any()): ?>
            <div class="p-4 text-red-700 bg-red-100 rounded-lg">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div>• <?php echo e($error); ?></div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php endif; ?>

        <!-- SEARCH + ADD -->
        <div class="p-6 space-y-4 bg-white border shadow rounded-xl">

            <!-- SEARCH -->
            <form method="GET" action="<?php echo e(route('categories')); ?>" class="flex gap-2">
                <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Search categories..."
                    class="w-full px-4 py-2 border rounded-lg">

                <button class="px-4 py-2 text-white bg-blue-600 rounded-lg">
                    Search
                </button>
            </form>

            <!-- ADD CATEGORY -->
            <form method="POST" action="<?php echo e(route('categories.store')); ?>" class="grid grid-cols-1 gap-3 md:grid-cols-3">
                <?php echo csrf_field(); ?>

                <input name="name" placeholder="Category Name" class="px-3 py-2 border rounded-lg" required>

                <input name="description" placeholder="Description" class="px-3 py-2 border rounded-lg">

                <button class="text-white bg-green-600 rounded-lg hover:bg-green-700">
                    + Add Category
                </button>
            </form>

        </div>

        <!-- TABLE -->
        <div class="overflow-hidden bg-white border shadow rounded-2xl">

            <!-- HEADER -->
            <div class="flex items-center justify-between px-6 py-4 border-b bg-slate-50">
                <h3 class="font-semibold text-gray-700">All Categories</h3>

                <span class="text-sm text-gray-400">
                    <?php echo e($categories->total()); ?> total
                </span>
            </div>

            <table class="w-full text-sm">
                <thead class="text-xs text-gray-600 uppercase bg-slate-50">
                    <tr>
                        <th class="px-6 py-4 text-left">Name</th>
                        <th class="px-6 py-4 text-left">Description</th>
                        <th class="px-6 py-4 text-left">Products</th>
                        <th class="px-6 py-4 text-center">Actions</th>
                    </tr>
                </thead>

                <tbody class="divide-y">
                    <?php $__empty_1 = true; $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="hover:bg-slate-50">

                            <td class="px-6 py-4 font-medium"><?php echo e($cat->name); ?></td>

                            <td class="px-6 py-4 text-gray-600">
                                <?php echo e($cat->description ?? '-'); ?>

                            </td>

                            <!-- COUNT PRODUCTS -->
                            <td class="px-6 py-4 text-gray-500">
                                <?php echo e($cat->items_count ?? $cat->items->count() ?? 0); ?>

                            </td>

                            <td class="px-6 py-4 text-center">
                                <form method="POST" action="<?php echo e(route('categories.destroy', $cat->id)); ?>">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>

                                    <button onclick="return confirm('Delete this category?')"
                                        class="px-3 py-1 text-xs text-white bg-red-500 rounded-lg">
                                        Delete
                                    </button>
                                </form>
                            </td>

                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="4" class="py-10 text-center text-gray-400">
                                No categories found
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>

            <!-- PAGINATION -->
            <div class="p-4">
                <?php echo e($categories->links()); ?>

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
<?php endif; ?><?php /**PATH C:\Users\akalisik\Project\backend\resources\views/categories.blade.php ENDPATH**/ ?>