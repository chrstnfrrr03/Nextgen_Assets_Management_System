<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>NextGen Assets Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="flex items-center justify-center min-h-screen bg-slate-100">

    <!-- =========================
         LOGIN CARD
    ========================== -->
    <div class="w-full max-w-md p-8 bg-white shadow-xl rounded-2xl">

        <!-- TITLE -->
        <h2 class="mb-6 text-2xl font-bold text-center text-slate-800">
            NextGen Assets Login
        </h2>

        <!-- =========================
             SUCCESS / SESSION MESSAGE
        ========================== -->
        <?php if(session('status')): ?>
            <div class="p-3 mb-4 text-sm text-green-700 bg-green-100 rounded">
                <?php echo e(session('status')); ?>

            </div>
        <?php endif; ?>

        <!-- =========================
             LOGIN FORM
        ========================== -->
        <form method="POST" action="<?php echo e(route('login')); ?>">
            <?php echo csrf_field(); ?>

            <!-- EMAIL -->
            <div class="mb-4">
                <label class="block mb-1 text-sm text-gray-600">
                    Email
                </label>

                <input type="email" name="email" required value="<?php echo e(old('email')); ?>"
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">

                <!-- ERROR -->
                <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="mt-1 text-sm text-red-500"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <!-- PASSWORD -->
            <div class="mb-4">
                <label class="block mb-1 text-sm text-gray-600">
                    Password
                </label>

                <input type="password" name="password" required
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">

                <!-- ERROR -->
                <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="mt-1 text-sm text-red-500"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <!-- REMEMBER -->
            <div class="flex items-center mb-4">
                <input type="checkbox" name="remember" class="mr-2">
                <span class="text-sm text-gray-600">Remember me</span>
            </div>

            <!-- BUTTON -->
            <button class="w-full py-2 font-semibold text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                Login
            </button>

        </form>

        <!-- =========================
             LINKS SECTION
        ========================== -->
        <div class="mt-4 text-center space-y-2">

            <!-- REGISTER -->
            <a href="<?php echo e(route('register')); ?>" class="block text-sm text-blue-600 hover:underline">
                Create an account
            </a>

            <!-- FORGOT PASSWORD -->
            <?php if(Route::has('password.request')): ?>
                <a href="<?php echo e(route('password.request')); ?>" class="block text-sm text-gray-500 hover:underline">
                    Forgot your password?
                </a>
            <?php endif; ?>

        </div>

    </div>

</body>

</html><?php /**PATH C:\Users\akalisik\Project\backend\resources\views/auth/login.blade.php ENDPATH**/ ?>