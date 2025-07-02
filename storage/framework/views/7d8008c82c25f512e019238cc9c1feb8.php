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
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg h-full">
                <div class="p-6 text-black dark:text-gray-100">
                    
                    <!-- Backボタン（左上固定） -->
                    <div class="absolute left-4 sm:left-6">
                        <a href="<?php echo e(route('profile.show', $user->id)); ?>" class="inline-flex items-center text-sm sm:text-base text-blue-500 hover:underline">
                            <i class="fa-solid fa-arrow-left mr-1"></i> Back to profile page
                        </a>
                    </div>

                    <!-- 見出し（中央） -->
                    <h1 class="text-md sm:text-2xl lg:text-3xl 2xl:text-5xl font-bold text-center">
                        Following List
                    </h1>
                </div>
                    
                    <div class="mx-auto h-full mt-8">
                        <?php $__currentLoopData = $followings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $following): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="flex items-center justify-between bg-white rounded-lg shadow p-4 mb-4 hover:bg-gray-50 transition">
                                <a href="<?php echo e(route('profile.show', $following->following->id)); ?>" class="flex items-center space-x-4 w-full ml-2">
                                    <?php if($following->avatar): ?>
                                        <img src="<?php echo e($following->following->avatar); ?>" alt="<?php echo e($following->following->name); ?>" class="w-12 h-12 rounded-full object-cover">
                                    <?php else: ?>
                                        <div class="w-12 h-12 bg-gray-300 rounded-full flex items-center justify-center text-white font-bold">
                                            <?php echo e(strtoupper(substr($following->following->name, 0, 1))); ?>

                                        </div>
                                    <?php endif; ?>
                                    <div class="text-center">
                                        <p class="font-semibold text-2xl truncate"><?php echo e($following->following->name); ?></p>
                                    </div>
                                </a>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
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
<?php /**PATH /Users/kawaimayu/Desktop/gi_12_tripe_may/resources/views/follows/following.blade.php ENDPATH**/ ?>