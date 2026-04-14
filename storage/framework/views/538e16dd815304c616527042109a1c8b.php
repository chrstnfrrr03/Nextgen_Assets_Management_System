<aside :class="sidebarOpen ? 'w-72' : 'w-24'"
    class="flex flex-col h-screen text-white transition-all duration-300 border-r shadow-2xl border-slate-800 bg-gradient-to-b from-slate-950 via-slate-900 to-slate-950">
    <?php $user = auth()->user(); ?>

    <div class="px-5 py-5 border-b border-slate-800">
        <div x-show="sidebarOpen">
            <h1 class="text-lg font-bold tracking-tight">
                <?php echo e($appSettings['system_name'] ?? 'NextGen Assets'); ?>

            </h1>
            <p class="text-xs text-slate-400">
                <?php echo e($appSettings['system_tagline'] ?? 'Management System'); ?>

            </p>
        </div>

        <div x-show="!sidebarOpen" class="text-center">
            <div class="inline-flex items-center justify-center w-10 h-10 text-sm font-bold bg-blue-600 rounded-xl">
                <?php echo e(strtoupper(substr($appSettings['system_name'] ?? 'N', 0, 1))); ?>

            </div>
        </div>
    </div>

    <div class="px-4 py-4">
        <div class="flex items-center gap-3 px-3 py-3 rounded-2xl bg-slate-800">
            <?php if($user && $user->profile_photo_url): ?>
                <img src="<?php echo e($user->profile_photo_url); ?>" alt="<?php echo e($user->name); ?>"
                    class="object-cover w-10 h-10 border rounded-xl border-slate-700">
            <?php else: ?>
                <div class="flex items-center justify-center w-10 h-10 font-bold bg-blue-600 rounded-xl">
                    <?php echo e(strtoupper(substr($user->name ?? 'U', 0, 1))); ?>

                </div>
            <?php endif; ?>

            <div x-show="sidebarOpen" class="min-w-0">
                <div class="text-sm font-semibold truncate"><?php echo e($user->name ?? 'User'); ?></div>
                <div class="text-xs truncate text-slate-400">
                    <?php echo e($user && $user->isSystemAdmin() ? 'System Administrator' : ucfirst(str_replace('_', ' ', $user->role ?? 'staff'))); ?>

                </div>
            </div>
        </div>
    </div>

    <nav class="flex-1 px-3 space-y-2">
        <a href="<?php echo e(route('dashboard')); ?>"
            class="<?php echo e(request()->routeIs('dashboard') ? 'bg-blue-600 text-white shadow-md' : 'hover:bg-slate-800'); ?> block rounded-xl px-3 py-2.5 transition">
            <span x-show="sidebarOpen">Dashboard</span>
            <span x-show="!sidebarOpen">D</span>
        </a>

        <a href="<?php echo e(route('items.index')); ?>"
            class="<?php echo e(request()->routeIs('items.*') ? 'bg-blue-600 text-white shadow-md' : 'hover:bg-slate-800'); ?> block rounded-xl px-3 py-2.5 transition">
            <span x-show="sidebarOpen">Assets</span>
            <span x-show="!sidebarOpen">A</span>
        </a>

        <a href="<?php echo e(route('assignments.index')); ?>"
            class="<?php echo e(request()->routeIs('assignments.*') ? 'bg-blue-600 text-white shadow-md' : 'hover:bg-slate-800'); ?> block rounded-xl px-3 py-2.5 transition">
            <span x-show="sidebarOpen">Assignments</span>
            <span x-show="!sidebarOpen">A</span>
        </a>

        <a href="<?php echo e(route('inventory.index')); ?>"
            class="<?php echo e(request()->routeIs('inventory.*') ? 'bg-blue-600 text-white shadow-md' : 'hover:bg-slate-800'); ?> block rounded-xl px-3 py-2.5 transition">
            <span x-show="sidebarOpen">Inventory</span>
            <span x-show="!sidebarOpen">I</span>
        </a>

        <a href="<?php echo e(route('suppliers.index')); ?>"
            class="<?php echo e(request()->routeIs('suppliers.*') ? 'bg-blue-600 text-white shadow-md' : 'hover:bg-slate-800'); ?> block rounded-xl px-3 py-2.5 transition">
            <span x-show="sidebarOpen">Suppliers</span>
            <span x-show="!sidebarOpen">S</span>
        </a>

        <a href="<?php echo e(route('categories.index')); ?>"
            class="<?php echo e(request()->routeIs('categories.*') ? 'bg-blue-600 text-white shadow-md' : 'hover:bg-slate-800'); ?> block rounded-xl px-3 py-2.5 transition">
            <span x-show="sidebarOpen">Categories</span>
            <span x-show="!sidebarOpen">C</span>
        </a>

        <a href="<?php echo e(route('departments.index')); ?>"
            class="<?php echo e(request()->routeIs('departments.*') ? 'bg-blue-600 text-white shadow-md' : 'hover:bg-slate-800'); ?> block rounded-xl px-3 py-2.5 transition">
            <span x-show="sidebarOpen">Departments</span>
            <span x-show="!sidebarOpen">D</span>
        </a>

        <?php if($user && $user->isAdmin()): ?>
            <a href="<?php echo e(route('users.index')); ?>"
                class="<?php echo e(request()->routeIs('users.*') ? 'bg-blue-600 text-white shadow-md' : 'hover:bg-slate-800'); ?> block rounded-xl px-3 py-2.5 transition">
                <span x-show="sidebarOpen">Users</span>
                <span x-show="!sidebarOpen">U</span>
            </a>
        <?php endif; ?>
    </nav>

    <div class="p-4 border-t border-slate-800">
        <form method="POST" action="<?php echo e(route('logout')); ?>">
            <?php echo csrf_field(); ?>
            <button type="submit"
                class="w-full py-2 text-sm font-semibold text-white transition bg-red-500 rounded-xl hover:bg-red-600">
                <span x-show="sidebarOpen">Logout</span>
                <span x-show="!sidebarOpen">⎋</span>
            </button>
        </form>
    </div>
</aside><?php /**PATH C:\Users\akalisik\Nextgen-assets-management-system\resources\views/layouts/navigation.blade.php ENDPATH**/ ?>