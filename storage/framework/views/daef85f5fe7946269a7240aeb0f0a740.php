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
    <div class= "mt-5 h-[880px]">
        <div class="w-9/10 md:w-4/5 mx-auto sm:px-6 lg:px-8 h-full">
            <div class="min-h-screen bg-gray-100 dark:bg-gray-800 py-6 px-4 sm:px-6 lg:px-8">
                <div class="max-w-7xl mx-auto bg-white dark:bg-gray-900 rounded-lg shadow-lg p-6">
                    
                    <div class="relative text-center mb-8 border-b border-gray-300 pb-4">
                        <h1 class="text-2xl sm:text-3xl font-bold text-gray-800 dark:text-gray-100 flex justify-center items-center gap-2">
                            <i class="fa-solid fa-file-invoice-dollar text-green-500"></i>
                            Trip Payment Summary
                        </h1>

                        <?php
                            $hasCreateError = $errors->has('user_pay_id') || $errors->has('bill_name') || $errors->has('cost') || $errors->has('user_paid_id');
                        ?>

                        <div x-data="{ open: <?php echo e($hasCreateError ? 'true' : 'false'); ?> }" class="mt-4 sm:mt-0">
                            
                            <div class="flex justify-center sm:justify-end mt-4 sm:mt-0">
                                <button @click="open = true"
                                    class="inline-flex items-center px-4 py-2 bg-green-500 hover:bg-green-600 text-white text-sm font-semibold rounded-lg shadow transition-all duration-200">
                                    <i class="fa-solid fa-circle-plus mr-2"></i>
                                    Add Bills
                                </button>
                            </div>

                            
                            <?php echo $__env->make('goDutch.modals.create', ['all_bills' => $all_bills, 'groupMembers' => $groupMembers, 'itinerary' => $itinerary], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                        </div>

                        <p class="text-sm text-gray-500 mt-2">Invoice Overview</p>
                    </div>

                    
                    <?php if(count($all_bills) > 0): ?>
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm text-left text-gray-700 dark:text-gray-200 border border-gray-300">
                            <thead class="bg-gray-200 dark:bg-gray-700 text-xs uppercase tracking-wider">
                                <tr>
                                    <th class="px-4 py-2">Payer</th>
                                    <th class="px-4 py-2">Description</th>
                                    <th class="px-4 py-2">Amount</th>
                                    <th class="px-4 py-2">Split With</th>
                                    <th class="px-4 py-2"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $all_bills; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bill): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr class="border-t border-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800">
                                        <td class="px-4 py-3">
                                            <div class="flex items-center space-x-2">
                                                <img src="<?php echo e($bill->userPay->avatar ?? asset('images/ben-sweet-2LowviVHZ-E-unsplash.jpg')); ?>" alt="avatar" class="w-8 h-8 rounded-full object-cover">
                                                <span><?php echo e($bill->userPay->name); ?></span>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3"><?php echo e($bill->name); ?></td>
                                        <td class="px-4 py-3 text-red-500 font-semibold">$<?php echo e(number_format($bill->cost, 0)); ?></td>
                                        <td class="px-4 py-3">
                                            <div class="flex gap-1 overflow-x-auto max-w-full">
                                                <?php $__currentLoopData = $bill->billUser; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <img src="<?php echo e($user->userPaid->avatar ?? asset('images/ben-sweet-2LowviVHZ-E-unsplash.jpg')); ?>"
                                                        alt="avatar"
                                                        title="<?php echo e($user->userPaid->name); ?>"
                                                        class="w-7 h-7 rounded-full object-cover border border-gray-300 shadow-sm flex-shrink-0">
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3">
                                            
                                            <div class="flex justify-center items-center space-x-2">
                                                <?php
                                                    $hasEditError = $errors->has('user_pay_id_edit') || $errors->has('bill_name_edit') || $errors->has('cost_edit') || $errors->has('user_paid_id_edit');
                                                ?>
                                                
                                                <div x-data="{ open: <?php echo e($hasEditError ? 'true' : 'false'); ?> }">
                                                    <button
                                                        data-modal-target="modal-<?php echo e($bill->id); ?>"
                                                        data-modal-toggle="modal-<?php echo e($bill->id); ?>"
                                                        @click="open = true"
                                                        title="Edit"
                                                        class="w-10 h-10 flex items-center justify-center rounded-full hover:bg-gray-200 transition"
                                                    >
                                                        <i class="fa-solid fa-pen text-blue-600 text-lg"></i>
                                                    </button>

                                                    
                                                    <?php echo $__env->make('goDutch.modals.edit', [
                                                        'bill' => $bill,
                                                        'groupMembers' => $groupMembers,
                                                        'itinerary' => $itinerary
                                                    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                                                </div>

                                                
                                                <form action="<?php echo e(route('goDutch.delete', ['bill_id' => $bill->id, 'itinerary_id' => $itinerary->id])); ?>" method="post">
                                                    <?php echo csrf_field(); ?>
                                                    <?php echo method_field('DELETE'); ?>
                                                    <button
                                                        type="submit"
                                                        title="Delete"
                                                        class="w-10 h-10 flex items-center justify-center rounded-full hover:bg-red-100 transition"
                                                    >
                                                        <i class="fa-solid fa-trash text-red-500 text-lg"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                    <?php else: ?>
                        <p class="text-center text-gray-500 mt-10">No bills recorded yet.</p>
                    <?php endif; ?>

                    <?php if(count($all_bills) > 0 && !empty($details)): ?>
                        <div class="mt-10">
                            <h2 class="text-2xl font-bold text-center text-gray-800 dark:text-gray-100 mb-6 border-b pb-2">
                                <i class="fa-solid fa-calculator text-green-500 mr-2"></i>
                                Calculation Result for Payment
                            </h2>

                            
                            <div class="hidden sm:grid grid-cols-4 items-center text-center text-sm font-semibold bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-100 py-3 rounded-t">
                                <div>Pay → Receive</div>
                                <div>Amount</div>
                                <div>Status</div>
                                <div>Pay Now</div>
                            </div>

                            
                            <div class="space-y-4 mt-2 max-h-[300px] overflow-y-auto">
                                <?php $__currentLoopData = $details; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="grid grid-cols-1 sm:grid-cols-4 gap-4 items-center bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-md p-4 shadow-sm">
                                        
                                        <div class="flex justify-center sm:justify-center gap-4 items-center">
                                            
                                            <div class="text-center">
                                                <a href="<?php echo e(route('profile.show', $detail[0]->id)); ?>" class="block w-10 h-10 rounded-full overflow-hidden mx-auto">
                                                    <img src="<?php echo e($detail[0]->avatar ?? asset('images/ben-sweet-2LowviVHZ-E-unsplash.jpg')); ?>" class="w-full h-full object-cover">
                                                </a>
                                                <p class="text-xs mt-1"><?php echo e($detail[0]->name); ?></p>
                                            </div>

                                            
                                            <div class="text-red-500 text-xl">
                                                <i class="fa-solid fa-arrow-right"></i>
                                            </div>

                                            
                                            <div class="text-center">
                                                <a href="<?php echo e(route('profile.show', $detail[1]->id)); ?>" class="block w-10 h-10 rounded-full overflow-hidden mx-auto">
                                                    <img src="<?php echo e($detail[1]->avatar ?? asset('images/ben-sweet-2LowviVHZ-E-unsplash.jpg')); ?>" class="w-full h-full object-cover">
                                                </a>
                                                <p class="text-xs mt-1"><?php echo e($detail[1]->name); ?></p>
                                            </div>
                                        </div>

                                        
                                        <div class="text-center text-red-500 font-semibold text-md">
                                            $<?php echo e(number_format($detail[2], 0)); ?>

                                        </div>

                                        
                                        <div class="text-center text-red-600">
                                            <i class="fa-solid fa-circle-xmark"></i> Unpaid
                                        </div>

                                        
                                        <div class="text-center text-sm text-blue-600">
                                            <span class="inline-block bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-xs">
                                                Finalize to enable PayPal
                                            </span>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>

                            
                            <div class="flex justify-center mt-6">
                                <a href="<?php echo e(route('goDutch.finalize', $itinerary->id)); ?>"
                                class="bg-blue-500 text-white text-lg font-bold px-6 py-2 rounded-md hover:bg-blue-600 transition">
                                    <i class="fa-brands fa-paypal mr-2"></i>
                                    Finalize the Bills
                                </a>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
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
<?php endif; ?>
<?php /**PATH /Users/kawaimayu/Desktop/gi_12_tripe_may/resources/views/goDutch/show.blade.php ENDPATH**/ ?>