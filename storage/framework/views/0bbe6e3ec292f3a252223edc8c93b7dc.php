<div class="relative flex justify-center items-center mb-4">
    <h2 class="text-xl font-bold">Bill</h2>
    <a href="<?php echo e(($itinerary->finalize_bill_at != NULL) ? route('goDutch.finalize', $itinerary->id) : route('goDutch.index', $itinerary->id)); ?>" class="absolute right-6 text-2xl" title="Bill">
        <i class="fa-solid fa-money-bill text-blue-500"></i>
    </a>
</div>

<ul id="itemList" class="space-y-1 mb-4 max-h-[300px] overflow-y-auto overflow-x-hidden">
    <?php if(isset($itinerary->group)): ?>
        <?php $__empty_1 = true; $__currentLoopData = $itinerary->group->users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <?php if($total_getPay[$user->id] - $total_Pay[$user->id] != 0): ?>
            <li class="flex items-between gap-2 p-1 border rounded">
                <div class="flex">
                    <div class="w-10 h-10 rounded-full overflow-hidden border border-gray-300 bg-gray-100 flex items-center justify-center text-gray-400">
                        <?php if($user->avatar): ?>
                            <a href="<?php echo e(route('profile.show', $user->id)); ?>">
                                <img src="<?php echo e($user->avatar); ?>" alt="<?php echo e($user->name); ?>" class="w-full h-full object-cover">
                            </a>
                        <?php else: ?>
                            <a href="<?php echo e(route('profile.show', $user->id)); ?>"><i class="fa-regular fa-circle-user fa-lg"></i></a>
                        <?php endif; ?>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-md text-gray-700"><?php echo e($user->name); ?></span>
                        <?php if($total_getPay[$user->id] - $total_Pay[$user->id] > 0): ?>
                            <span class="text-md text-green-500 ml-auto text-end">
                                Get $<?php echo e(number_format($total_getPay[$user->id] - $total_Pay[$user->id], 0)); ?>

                            </span>
                        <?php elseif($total_getPay[$user->id] - $total_Pay[$user->id] < 0): ?>
                            <span class="text-md text-red-500 ml-auto text-end">
                                Pay $<?php echo e(number_format(abs($total_getPay[$user->id] - $total_Pay[$user->id]), 0)); ?>

                            </span>
                        <?php endif; ?>
                    </div>
                </div>
            </li>
        <?php endif; ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <p class="text-sm text-gray-500">No Bills</p>
        <?php endif; ?>
    <?php endif; ?>
</ul>
<div class="flex space-x-2">
    <a href="<?php echo e(route('goDutch.index', $itinerary->id)); ?>" class="mx-auto text-xl text-blue-500">
        add Bill
    </a>
</div>
<?php $__env->startPush('scripts'); ?>
    <script>
        const itineraryId = <?php echo json_encode($itinerary->id, 15, 512) ?>;
    </script>
    <script src="<?php echo e(asset('js/itineraries/belonging.js')); ?>"></script>
<?php $__env->stopPush(); ?>
<<<<<<<< HEAD:storage/framework/views/2bd4123654a2bce740a73f0d5c7521a6.php
<?php /**PATH C:\Users\Tamak\Desktop\gi_12_tripe_may\resources\views/goDutch/index.blade.php ENDPATH**/ ?>
========
<?php /**PATH C:\Users\USER\Desktop\gi_12_tripe_may\resources\views/goDutch/index.blade.php ENDPATH**/ ?>
>>>>>>>> 6df93b8a75dd54c8cbc6cfa7579d8c9215562e33:storage/framework/views/0bbe6e3ec292f3a252223edc8c93b7dc.php
