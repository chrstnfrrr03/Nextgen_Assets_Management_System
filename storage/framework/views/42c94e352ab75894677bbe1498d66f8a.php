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
            <h1 class="text-3xl font-bold text-slate-800">User Management</h1>
            <p class="text-sm text-gray-500">Manage system users and access</p>
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

        <!-- ADD USER -->
        <div class="p-6 bg-white border shadow rounded-xl">
            <h3 class="mb-4 text-lg font-semibold text-gray-700">Add New User</h3>

            <form method="POST" action="<?php echo e(route('users.store')); ?>" class="grid gap-4 md:grid-cols-4">
                <?php echo csrf_field(); ?>

                <input name="name" placeholder="Full Name" class="px-4 py-2 border rounded-lg" required>
                <input name="email" type="email" placeholder="Email" class="px-4 py-2 border rounded-lg" required>
                <input name="password" type="password" placeholder="Password" class="px-4 py-2 border rounded-lg"
                    required>

                <button class="text-white bg-green-600 rounded-lg hover:bg-green-700">
                    + Add User
                </button>
            </form>
        </div>

        <!-- SEARCH -->
        <div class="p-4 bg-white border shadow rounded-xl">
            <form method="GET" action="<?php echo e(route('users')); ?>" class="flex gap-2">
                <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Search users..."
                    class="w-full px-4 py-2 border rounded-lg">

                <button class="px-4 py-2 text-white bg-blue-600 rounded-lg">
                    Search
                </button>
            </form>
        </div>

        <!-- TABLE -->
        <div class="overflow-hidden bg-white shadow rounded-2xl">

            <div class="flex items-center justify-between px-6 py-4 border-b bg-slate-50">
                <h3 class="font-semibold text-gray-700">All Users</h3>
                <span class="text-sm text-gray-400"><?php echo e($users->total()); ?> total</span>
            </div>

            <table class="w-full text-sm">
                <thead class="text-xs text-gray-600 uppercase bg-slate-50">
                    <tr>
                        <th class="px-6 py-4 text-left">Name</th>
                        <th class="px-6 py-4 text-left">Email</th>
                        <th class="px-6 py-4 text-left">Joined</th>
                        <th class="px-6 py-4 text-center">Actions</th>
                    </tr>
                </thead>

                <tbody class="divide-y">
                    <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="hover:bg-slate-50">

                            <td class="px-6 py-4 font-medium"><?php echo e($user->name); ?></td>
                            <td class="px-6 py-4"><?php echo e($user->email); ?></td>
                            <td class="px-6 py-4 text-gray-400">
                                <?php echo e(optional($user->created_at)->format('M d, Y')); ?>

                            </td>

                            <td class="px-6 py-4 text-center">
                                <div class="flex justify-center gap-2">

                                    <a href="<?php echo e(route('users.edit', $user->id)); ?>"
                                        class="px-3 py-1 text-white bg-blue-500 rounded-lg">
                                        Edit
                                    </a>

                                    <form method="POST" action="<?php echo e(route('users.destroy', $user->id)); ?>">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>

                                        <button onclick="return confirm('Delete this user?')"
                                            class="px-3 py-1 text-white bg-red-500 rounded-lg">
                                            Delete
                                        </button>
                                    </form>

                                </div>
                            </td>

                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="4" class="py-10 text-center text-gray-400">
                                No users found
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>

            <div class="p-4">
                <?php echo e($users->links()); ?>

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
<?php endif; ?><?php /**PATH C:\Users\akalisik\Project\backend\resources\views/users.blade.php ENDPATH**/ ?>