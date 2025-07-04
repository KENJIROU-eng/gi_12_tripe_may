
<?php if($totalCount > 0): ?>
    <div class="w-full px-4 mb-4">
        <div class="flex justify-between text-sm mb-1 text-gray-600 dark:text-gray-300">
            <span><?php echo e($checkedCount); ?> / <?php echo e($totalCount); ?> items</span>
            <span><?php echo e($progressPercent); ?>%</span>
        </div>
        <div class="w-full bg-gray-200 dark:bg-gray-600 rounded-full h-2.5 overflow-hidden">
            <div class="bg-blue-500 h-full transition-all duration-300" style="width: <?php echo e($progressPercent); ?>%;"></div>
        </div>
    </div>
<?php endif; ?>


<div class="relative flex justify-center items-center mb-4">
    <h2 class="text-xl font-bold text-gray-800 dark:text-gray-100">Belonging List</h2>
    <button id="toggleCheckedBtn" type="button" class="absolute right-2 text-gray-600 hover:text-gray-800 dark:text-gray-300 dark:hover:text-white text-xl" title="Toggle Checked Items">
        <i class="fa-solid fa-eye text-blue-500"></i>
    </button>
</div>

<div class="text-center mt-2">
    <a href="<?php echo e(route('belonging.index', $itinerary->id)); ?>" class="inline-block text-blue-600 hover:underline text-sm font-medium mb-2">
        View All
    </a>
</div>


<ul id="belongingList" class="space-y-1 mb-4 max-h-[250px] overflow-y-auto overflow-x-hidden pr-2">
    <?php $__empty_1 = true; $__currentLoopData = $all_belongings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $belonging): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <li class="belonging-item flex justify-between items-center px-1 border rounded bg-white dark:bg-gray-700 shadow-sm <?php echo e($belonging->checked ? 'is-checked opacity-50 pointer-events-none' : ''); ?>" data-id="<?php echo e($belonging->id); ?>" data-checked="<?php echo e($belonging->checked ? '1' : '0'); ?>">
            <div class="flex-grow text-start truncate" title="<?php echo e($belonging->name); ?>">
                <span class="text-sm text-gray-800 dark:text-gray-100">
                    <?php echo e(Str::limit($belonging->name, 25)); ?>

                </span>
            </div>
        </li>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <li class="text-center text-gray-500 dark:text-gray-400 text-sm">No belongings yet.</li>
    <?php endif; ?>
</ul>

<<<<<<<< HEAD:storage/framework/views/ce6f212253566370cb7b615f3f3a7f10.php
<?php /**PATH C:\Users\Tamak\Desktop\gi_12_tripe_may\resources\views/belongings/index.blade.php ENDPATH**/ ?>
========
<?php /**PATH C:\Users\USER\Desktop\gi_12_tripe_may\resources\views/belongings/index.blade.php ENDPATH**/ ?>
>>>>>>>> 6df93b8a75dd54c8cbc6cfa7579d8c9215562e33:storage/framework/views/81abf0654aa8b8e5215f879bee7b4eae.php
