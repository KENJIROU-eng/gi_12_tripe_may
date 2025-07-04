
<div x-show="open" class="fixed inset-0 bg-gray-500/75 z-10 flex items-center justify-center w-full">
    <div class="bg-white p-6 rounded shadow max-w-md w-full">
        
        <div class="px-6 py-4 text-center">
            <h1 class="text-3xl font-bold">Create Bill</h1>
        </div>
        <hr class="border-green-500 border-1">
        
        <form action="<?php echo e(route('goDutch.create', $itinerary->id)); ?>" class="mx-auto w-full mt-3" method="post">
            <?php echo csrf_field(); ?>
            <div class="grid grid-cols-6 gap-2">
                <div class="col-span-4 col-start-2 me-auto">
                    <label for="user_pay_name" class="block text-md font-medium text-gray-900">Who pay the bill?</label>
                </div>
                <div class="col-span-2 col-start-2 mb-2">
                    <select id="user_pay_id" name="user_pay_id" autocomplete="user_pay_id" class="w-full appearance-none rounded-md">
                        <option value="" disabled selected>user pay</option>
                        <?php $__currentLoopData = $groupMembers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $groupMember): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($groupMember->id); ?>"><?php echo e($groupMember->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <?php $__errorArgs = ['user_pay_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="text-danger small"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="col-span-4 col-start-2 mb-2 me-auto">
                    <label for="bill_name" class="block text-md font-medium text-gray-900 mb-2">What does she or he pay for?</label>
                    <input type="text" name="bill_name" id="bill_name" class="block rounded-md w-full mt-2" placeholder="name">
                    <?php $__errorArgs = ['bill_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="text-danger small"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="col-span-4 col-start-2 mb-2 me-auto">
                    <label for="cost" class="block text-md font-medium text-gray-900 mb-2">How much does it cost?</label>
                    <input type="number" name="cost" id="cost" class="block rounded-md w-full mt-2" placeholder="cost">
                    <?php $__errorArgs = ['cost'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="text-danger small"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="col-span-4 col-start-2 me-auto">
                    <label for="user_paid_name" class="block text-md font-medium text-gray-900">Select members who spill the bill?</label>
                </div>
                <div class="col-span-4 col-start-2 mb-2">
                    <div class="max-h-20 space-y-2 mt-2 max-h-30 overflow-y-auto p-2 rounded">
                        <?php $__empty_1 = true; $__currentLoopData = $groupMembers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $groupMember): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <label class="flex items-center space-x-3 cursor-pointer">
                            <input type="checkbox" name="user_paid_id[]" class="user-input" value="<?php echo e($groupMember->id); ?>" data-user-id="<?php echo e($groupMember->id); ?>">
                            <div class="flex items-center space-x-2">
                                <span class="text-sm text-gray-700"><?php echo e($groupMember->name); ?></span>
                            </div>
                        </label>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <p class="text-sm text-gray-500">No Users</p>
                        <?php endif; ?>
                    </div>
                    <?php $__errorArgs = ['user_paid_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="text-danger small"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="col-span-2 col-start-2 mt-2">
                    <a href="<?php echo e(route('goDutch.index', $itinerary->id)); ?>">
                        <button type="button" class="w-full bg-gray-500 font-semi-bold text-white py-2 rounded text-xl hover:bg-gray-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-gray-500">
                            Cancel
                        </button>
                    </a>
                </div>
                <div class="col-span-2 col-start-4 mt-2">
                    <button type="submit" class="w-full bg-green-500 font-semi-bold rounded text-white py-2 text-xl hover:bg-green-600 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-green-600">
                        Enter
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script src="<?php echo e(asset('js/removeOption.js')); ?>" defer></script>


<?php /**PATH C:\Users\Tamak\Desktop\gi_12_tripe_may\resources\views/goDutch/modals/create.blade.php ENDPATH**/ ?>