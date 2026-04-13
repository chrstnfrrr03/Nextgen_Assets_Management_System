<?php $__env->startSection('content'); ?>
    <div class="max-w-5xl mx-auto space-y-8">
        <div>
            <h1 class="text-3xl font-bold tracking-tight text-slate-900">Edit Profile</h1>
            <p class="mt-2 text-sm text-slate-500">
                Update your account details, profile photo, and security settings.
            </p>
        </div>

        <div class="grid grid-cols-1 gap-6 xl:grid-cols-3">
            <div class="p-6 bg-white border shadow-sm rounded-2xl border-slate-200">
                <h2 class="text-lg font-semibold text-slate-900">Account Overview</h2>

                <div class="flex flex-col items-center mt-6 text-center">
                    <?php if($user->profile_photo_url): ?>
                        <img
                            src="<?php echo e($user->profile_photo_url); ?>"
                            alt="<?php echo e($user->name); ?>"
                            class="object-cover border-4 rounded-full shadow-sm h-28 w-28 border-slate-100"
                        >
                    <?php else: ?>
                        <div class="flex items-center justify-center text-3xl font-bold text-white rounded-full shadow-sm h-28 w-28 bg-slate-900">
                            <?php echo e(strtoupper(substr($user->name, 0, 1))); ?>

                        </div>
                    <?php endif; ?>

                    <h3 class="mt-4 text-xl font-semibold text-slate-900"><?php echo e($user->name); ?></h3>
                    <p class="mt-1 text-sm text-slate-500"><?php echo e($user->email); ?></p>

                    <div class="inline-flex px-3 py-1 mt-4 text-xs font-semibold rounded-full bg-slate-100 text-slate-700">
                        <?php echo e($user->isSystemAdmin() ? 'System Administrator' : ucfirst(str_replace('_', ' ', $user->role))); ?>

                    </div>
                </div>

                <div class="mt-6 space-y-3">
                    <div class="px-4 py-3 rounded-xl bg-slate-50">
                        <p class="text-xs font-medium tracking-wide uppercase text-slate-400">Account Type</p>
                        <p class="mt-1 text-sm font-semibold text-slate-900">
                            <?php echo e($user->isSystemAdmin() ? 'System Administrator' : ucfirst(str_replace('_', ' ', $user->role))); ?>

                        </p>
                    </div>

                    <div class="px-4 py-3 rounded-xl bg-slate-50">
                        <p class="text-xs font-medium tracking-wide uppercase text-slate-400">Profile Photo</p>
                        <p class="mt-1 text-sm font-semibold text-slate-900">
                            <?php echo e($user->profile_photo ? 'Uploaded' : 'Not uploaded'); ?>

                        </p>
                    </div>
                </div>
            </div>

            <div class="xl:col-span-2">
                <form
                    method="POST"
                    action="<?php echo e(route('profile.update')); ?>"
                    enctype="multipart/form-data"
                    class="p-6 space-y-6 bg-white border shadow-sm rounded-2xl border-slate-200"
                >
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PATCH'); ?>

                    <div>
                        <h2 class="text-lg font-semibold text-slate-900">Profile Details</h2>
                        <p class="mt-1 text-sm text-slate-500">
                            Keep your account information accurate and up to date.
                        </p>
                    </div>

                    <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                        <div>
                            <label for="name" class="block mb-2 text-sm font-semibold text-slate-700">
                                Full Name
                            </label>
                            <input
                                id="name"
                                type="text"
                                name="name"
                                value="<?php echo e(old('name', $user->name)); ?>"
                                class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-800 focus:border-blue-500 focus:outline-none focus:ring-4 focus:ring-blue-100"
                                placeholder="Enter your full name"
                            >
                            <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="mt-2 text-sm text-red-600"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div>
                            <label for="email" class="block mb-2 text-sm font-semibold text-slate-700">
                                Email Address
                            </label>
                            <input
                                id="email"
                                type="email"
                                name="email"
                                value="<?php echo e(old('email', $user->email)); ?>"
                                class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-800 focus:border-blue-500 focus:outline-none focus:ring-4 focus:ring-blue-100"
                                placeholder="Enter your email"
                            >
                            <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="mt-2 text-sm text-red-600"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>

                    <div class="p-5 border rounded-2xl border-slate-200 bg-slate-50">
                        <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                            <div>
                                <h3 class="text-sm font-semibold text-slate-900">Profile Photo</h3>
                                <p class="mt-1 text-sm text-slate-500">
                                    Upload a square image for the best result. PNG or JPG up to 2MB.
                                </p>
                            </div>

                            <?php if($user->profile_photo): ?>
                                <label class="inline-flex items-center gap-2 px-3 py-2 text-sm font-semibold text-red-600 bg-white border border-red-200 rounded-xl">
                                    <input type="checkbox" name="remove_profile_photo" value="1" class="rounded border-slate-300">
                                    Remove current photo
                                </label>
                            <?php endif; ?>
                        </div>

                        <div class="mt-4">
                            <input
                                type="file"
                                name="profile_photo"
                                accept="image/*"
                                class="block w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-700 file:mr-4 file:rounded-lg file:border-0 file:bg-slate-900 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-white hover:file:bg-slate-800"
                            >
                            <?php $__errorArgs = ['profile_photo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="mt-2 text-sm text-red-600"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>

                    <div>
                        <h2 class="text-lg font-semibold text-slate-900">Security</h2>
                        <p class="mt-1 text-sm text-slate-500">
                            Leave the password fields blank if you do not want to change your password.
                        </p>
                    </div>

                    <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                        <div>
                            <label for="password" class="block mb-2 text-sm font-semibold text-slate-700">
                                New Password
                            </label>
                            <input
                                id="password"
                                type="password"
                                name="password"
                                class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-800 focus:border-blue-500 focus:outline-none focus:ring-4 focus:ring-blue-100"
                                placeholder="Enter new password"
                            >
                            <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="mt-2 text-sm text-red-600"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div>
                            <label for="password_confirmation" class="block mb-2 text-sm font-semibold text-slate-700">
                                Confirm New Password
                            </label>
                            <input
                                id="password_confirmation"
                                type="password"
                                name="password_confirmation"
                                class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-800 focus:border-blue-500 focus:outline-none focus:ring-4 focus:ring-blue-100"
                                placeholder="Confirm new password"
                            >
                        </div>
                    </div>

                    <div class="flex flex-wrap gap-3 pt-2">
                        <button
                            type="submit"
                            class="inline-flex items-center rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700"
                        >
                            Update Profile
                        </button>

                        <a
                            href="<?php echo e(route('dashboard')); ?>"
                            class="inline-flex items-center rounded-xl border border-slate-200 bg-white px-5 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50"
                        >
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\akalisik\Project\backend\resources\views/profile/edit.blade.php ENDPATH**/ ?>