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
    <div class="mt-5 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 h-full">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg h-full">
                <div class="p-6 text-black dark:text-gray-100">
                    
                    <div class="relative mb-6">
                        
                        <h1 class="text-xl sm:text-3xl lg:text-4xl font-bold text-center">
                            <span class="text-green-500"><?php echo e($user->name); ?></span>`s Profile
                        </h1>

                        
                        <?php if(Auth::id() === $user->id): ?>
                            <div class="mt-2 text-center sm:hidden">
                                <a href="<?php echo e(route('profile.edit')); ?>" class="text-sm text-blue-500 hover:underline">
                                    <i class="fa-solid fa-user-pen"></i> Edit Profile
                                </a>
                            </div>
                        <?php endif; ?>

                        
                        <?php if(Auth::id() === $user->id): ?>
                            <div class="hidden sm:block">
                                <a href="<?php echo e(route('profile.edit')); ?>"
                                class="absolute top-0 right-0 text-sm sm:text-base text-blue-500 hover:underline whitespace-nowrap">
                                    <i class="fa-solid fa-user-pen"></i> Edit Profile
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>

                    <hr class="border-green-500 mb-6">

                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
                        
                        <div class="flex flex-col items-center">
                            <div class="w-32 h-32 rounded-full overflow-hidden bg-gray-200">
                                <?php if($user->avatar): ?>
                                    <img src="<?php echo e($user->avatar); ?>" alt="<?php echo e($user->name); ?>" class="w-full h-full object-cover">
                                <?php else: ?>
                                    <img src="<?php echo e(asset('images/ben-sweet-2LowviVHZ-E-unsplash.jpg')); ?>" alt="default avatar" class="w-full h-full object-cover">
                                <?php endif; ?>
                            </div>
                            <div class="flex justify-center gap-6 mt-4 text-center text-sm sm:text-base">
                                <div>
                                    <strong><?php echo e($user->post->count()); ?></strong><br> <?php echo e(Str::plural('Post', $user->post->count())); ?>

                                </div>
                                <div>
                                    <a href="<?php echo e(route('follower.show', $user->id)); ?>" class="hover:text-blue-500">
                                        <strong><?php echo e($user->followers->count()); ?></strong><br><?php echo e(Str::plural('Follower', $user->followers->count())); ?>

                                    </a>
                                </div>
                                <div>
                                    <a href="<?php echo e(route('following.show', $user->id)); ?>" class="hover:text-blue-500">
                                        <strong><?php echo e($user->following->count()); ?></strong><br>Following
                                    </a>
                                </div>
                            </div>
                            <?php if(Auth::id() !== $user->id): ?>
                                <div class="mt-4">
                                    <?php if($user->isFollowed()): ?>
                                        <div class="flex  gap-2">
                                            <form action="<?php echo e(route('follow.delete', $user->id)); ?>" method="post">
                                                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                                <button type="submit" class="bg-gray-500 text-white px-4 py-1 rounded-md hover:bg-gray-600">
                                                    Following
                                                </button>
                                            </form>
                                            <?php if($group): ?>
                                                <a href="<?php echo e(route('message.show', $group->id)); ?>" class="block bg-blue-500 text-white px-4 py-1 rounded-md hover:bg-blue-600">
                                                    message
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    <?php else: ?>
                                        <form action="<?php echo e(route('follow.create', $user->id)); ?>" method="post">
                                            <?php echo csrf_field(); ?>
                                            <button type="submit" class="bg-blue-500 text-white px-4 py-1 rounded-md hover:bg-blue-600">
                                                Follow
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        
                        <div class="md:col-span-2">
                            <div class="mb-4">
                                <h3 class="text-gray-500 text-sm sm:text-base">Username</h3>
                                <p class="text-xl sm:text-2xl font-semibold"><?php echo e($user->name); ?></p>
                            </div>
                            <div>
                                <h3 class="text-gray-500 text-sm sm:text-base">Introduction</h3>
                                <div class="bg-gray-50 p-3 rounded-md text-gray-800 dark:text-gray-200">
                                    <?php echo e($user->introduction ?? 'No introduction provided.'); ?>

                                </div>
                            </div>
                        </div>
                    </div>

                    
                    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 mb-10 max-h-[600px] overflow-y-auto pr-2">
                        <?php $__currentLoopData = $all_posts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $post): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <a href="<?php echo e(route('post.show', $post->id)); ?>" class="hover:scale-105 transition-transform duration-150">
                                <div class="aspect-square bg-blue-100 rounded overflow-hidden border border-green-100">
                                    <img src="<?php echo e($post->image); ?>" alt="<?php echo e($post->title); ?>" class="w-full h-full object-cover">
                                </div>
                            </a>
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
<?php /**PATH /Users/kawaimayu/Desktop/gi_12_tripe_may/resources/views/profile/show.blade.php ENDPATH**/ ?>