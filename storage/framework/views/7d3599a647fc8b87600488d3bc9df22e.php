

<?php $__env->startSection('content'); ?>
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-slate-900">Assign Assets</h1>
        <p class="text-slate-500">Assign one or many available assets in a single submission</p>
    </div>

    <form method="POST" action="<?php echo e(route('assignments.store')); ?>" class="p-6 bg-white shadow rounded-2xl">
        <?php echo csrf_field(); ?>

        <div id="assignment-rows" class="space-y-6">
            <div class="p-5 border assignment-row rounded-2xl border-slate-200">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-sm font-semibold assignment-entry-title text-slate-700">Assignment Entry 1</h2>
                </div>

                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div>
                        <label class="block mb-1 text-sm font-medium text-slate-700">Select Asset</label>
                        <select name="rows[0][item_id]"
                            class="w-full px-4 py-2 border rounded-lg border-slate-300 focus:border-blue-500 focus:outline-none"
                            required>
                            <option value="">Select Asset</option>
                            <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($item->id); ?>" <?php if(old('rows.0.item_id') == $item->id): echo 'selected'; endif; ?>>
                                    <?php echo e($item->name); ?>

                                    <?php if($item->asset_tag): ?> - <?php echo e($item->asset_tag); ?> <?php endif; ?>
                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <div>
                        <label class="block mb-1 text-sm font-medium text-slate-700">Select User</label>
                        <select name="rows[0][user_id]"
                            class="w-full px-4 py-2 border rounded-lg border-slate-300 focus:border-blue-500 focus:outline-none"
                            required>
                            <option value="">Select User</option>
                            <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($user->id); ?>" <?php if(old('rows.0.user_id') == $user->id): echo 'selected'; endif; ?>>
                                    <?php echo e($user->name); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <div>
                        <label class="block mb-1 text-sm font-medium text-slate-700">Select Department</label>
                        <select name="rows[0][department_id]"
                            class="w-full px-4 py-2 border rounded-lg border-slate-300 focus:border-blue-500 focus:outline-none"
                            required>
                            <option value="">Select Department</option>
                            <?php $__currentLoopData = $departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $department): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($department->id); ?>"
                                    <?php if(old('rows.0.assigned_department_id') == $department->id): echo 'selected'; endif; ?>>
                                    <?php echo e($department->name); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <div>
                        <label class="block mb-1 text-sm font-medium text-slate-700">Assigned At</label>
                        <input type="datetime-local" name="rows[0][assigned_at]"
                            value="<?php echo e(old('rows.0.assigned_at', now()->format('Y-m-d\TH:i'))); ?>"
                            class="w-full px-4 py-2 border rounded-lg border-slate-300 focus:border-blue-500 focus:outline-none"
                            required>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex flex-wrap gap-3 mt-6">
            <button type="button" id="add-assignment-row-btn"
                class="px-4 py-2 text-sm font-semibold text-white rounded-xl bg-slate-900 hover:bg-slate-800">
                + Add Another Assignment
            </button>

            <button type="submit"
                class="px-4 py-2 text-sm font-semibold text-white bg-blue-600 rounded-xl hover:bg-blue-700">
                Save Assignments
            </button>

            <a href="<?php echo e(route('assignments.index')); ?>"
                class="px-4 py-2 text-sm font-semibold bg-white border rounded-xl border-slate-300 text-slate-700 hover:bg-slate-50">
                Cancel
            </a>
        </div>
    </form>

    <template id="assignment-row-template">
        <div class="p-5 border assignment-row rounded-2xl border-slate-200">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-sm font-semibold assignment-entry-title text-slate-700">Assignment Entry</h2>

                <button type="button"
                    class="px-3 py-1 text-xs font-semibold text-red-600 rounded-lg remove-assignment-row-btn bg-red-50 hover:bg-red-100">
                    Remove
                </button>
            </div>

            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div>
                    <label class="block mb-1 text-sm font-medium text-slate-700">Select Asset</label>
                    <select data-name="item_id"
                        class="w-full px-4 py-2 border rounded-lg border-slate-300 focus:border-blue-500 focus:outline-none"
                        required>
                        <option value="">Select Asset</option>
                        <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($item->id); ?>">
                                <?php echo e($item->name); ?>

                                <?php if($item->asset_tag): ?> - <?php echo e($item->asset_tag); ?> <?php endif; ?>
                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <div>
                    <label class="block mb-1 text-sm font-medium text-slate-700">Select User</label>
                    <select data-name="user_id"
                        class="w-full px-4 py-2 border rounded-lg border-slate-300 focus:border-blue-500 focus:outline-none"
                        required>
                        <option value="">Select User</option>
                        <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($user->id); ?>"><?php echo e($user->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <div>
                    <label class="block mb-1 text-sm font-medium text-slate-700">Select Department</label>
                    <select data-name="department_id"
                        class="w-full px-4 py-2 border rounded-lg border-slate-300 focus:border-blue-500 focus:outline-none"
                        required>
                        <option value="">Select Department</option>
                        <?php $__currentLoopData = $departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $department): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($department->id); ?>"><?php echo e($department->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <div>
                    <label class="block mb-1 text-sm font-medium text-slate-700">Assigned At</label>
                    <input type="datetime-local" data-name="assigned_at" value="<?php echo e(now()->format('Y-m-d\TH:i')); ?>"
                        class="w-full px-4 py-2 border rounded-lg border-slate-300 focus:border-blue-500 focus:outline-none"
                        required>
                </div>
            </div>
        </div>
    </template>

    <script>
        (() => {
            const rowsContainer = document.getElementById('assignment-rows');
            const addRowBtn = document.getElementById('add-assignment-row-btn');
            const template = document.getElementById('assignment-row-template');

            let rowIndex = document.querySelectorAll('.assignment-row').length;

            function updateTitles() {
                document.querySelectorAll('.assignment-row').forEach((row, index) => {
                    const title = row.querySelector('.assignment-entry-title');
                    if (title) {
                        title.textContent = `Assignment Entry ${index + 1}`;
                    }
                });
            }

            function applyNames(row, index) {
                row.querySelectorAll('[data-name]').forEach((field) => {
                    field.name = `rows[${index}][${field.dataset.name}]`;
                });
            }

            addRowBtn.addEventListener('click', () => {
                const clone = template.content.firstElementChild.cloneNode(true);
                applyNames(clone, rowIndex);
                rowsContainer.appendChild(clone);
                rowIndex++;
                updateTitles();
            });

            rowsContainer.addEventListener('click', (e) => {
                if (e.target.classList.contains('remove-assignment-row-btn')) {
                    e.target.closest('.assignment-row').remove();

                    const rows = document.querySelectorAll('.assignment-row');
                    rowIndex = 0;

                    rows.forEach((row) => {
                        const dynamicFields = row.querySelectorAll('[data-name], select[name], input[name]');
                        dynamicFields.forEach((field) => {
                            const match = field.name?.match(/\]\[(.+)\]$/);
                            if (match) {
                                field.name = `rows[${rowIndex}][${match[1]}]`;
                            } else if (field.dataset.name) {
                                field.name = `rows[${rowIndex}][${field.dataset.name}]`;
                            }
                        });
                        rowIndex++;
                    });

                    updateTitles();
                }
            });

            updateTitles();
        })();
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\akalisik\Project\backend\resources\views/assignments/create.blade.php ENDPATH**/ ?>