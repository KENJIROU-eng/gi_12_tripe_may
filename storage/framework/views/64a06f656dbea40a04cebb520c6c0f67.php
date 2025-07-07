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
                    
                    <div class="relative flex items-center justify-center h-16 my-5">
                        <h1 class="text-md sm:text-2xl lg:text-3xl 2xl:text-5xl font-bold absolute left-1/2 transform -translate-x-1/2">User List</h1>
                    </div>
                    
                    <div class="mx-auto h-full mt-8">
                        <form action="<?php echo e(route('profile.users.search')); ?>" method="get" class="w-full">
                            <div class="flex">
                                <div class="flex justify-center items-center mb-3 w-full">
                                    <input type="text" name="user_name" class="w-1/2 rounded-md mr-4" value="<?php echo e($search); ?>" >
                                    <button type="submit" class="block text-white px-4 bg-green-500 py-2 font-semi-bold hover: border-green-500 hover:bg-green-600 transition duration-300 rounded-md">Search</button>
                                    <a href="<?php echo e(route('profile.users.list')); ?>" class="ml-3 block text-white px-4 bg-blue-500 py-2 font-semi-bold hover: border-blue-600 hover:bg-blue-600 transition duration-300 rounded-md">View all</a>
                                </div>
                            </div>
                        </form>
                        <?php $__currentLoopData = $all_users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php if(Auth::User()->id != $user->id): ?>
                                <div class="flex items-center justify-between bg-white rounded-lg shadow p-4 mb-4 hover:bg-gray-50 transition">
                                    <a href="<?php echo e(route('profile.show', ['user_id' => $user->id])); ?>" class="flex items-center space-x-4 w-full ml-2">
                                        <?php if($user->avatar): ?>
                                            <img src="<?php echo e($user->avatar); ?>" alt="<?php echo e($user->name); ?>" class="w-12 h-12 rounded-full object-cover">
                                        <?php else: ?>
                                            <img src="<?php echo e(asset('images/ben-sweet-2LowviVHZ-E-unsplash.jpg')); ?>" alt="default avatar" class="w-12 h-12 rounded-full object-cover">
                                        <?php endif; ?>

                                        <div class="text-center">
                                            <p class="font-semibold text-2xl truncate"><?php echo e($user->name); ?></p>
                                        </div>
                                    </a>
                                    <?php if(Auth::id() !== $user->id): ?>
                                        <div class="mt-4">
                                            <?php if($user->isFollowed()): ?>
                                                <div class="flex  gap-2">
                                                    <form action="<?php echo e(route('profile.follow.delete', $user->id)); ?>" method="post">
                                                        <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                                        <button type="submit" class="bg-gray-500 text-white px-4 py-1 rounded-md hover:bg-gray-600">
                                                            Following
                                                        </button>
                                                    </form>
                                                    <?php if(($user->private_group->isNotEmpty() && $user->private_group->contains('name', Auth::User()->name))): ?>
                                                        <a href="<?php echo e(route('message.show', $user->private_group->first()->id)); ?>" class="block bg-blue-500 text-white px-4 py-1 rounded-md hover:bg-blue-600">
                                                            message
                                                        </a>
                                                    <?php elseif((Auth::User()->private_group->isNotEmpty() && Auth::User()->private_group->contains('name', $user->name))): ?>
                                                        <a href="<?php echo e(route('message.show', Auth::User()->private_group->first()->id)); ?>" class="block bg-blue-500 text-white px-4 py-1 rounded-md hover:bg-blue-600">
                                                            message
                                                        </a>
                                                    <?php endif; ?>
                                                </div>
                                            <?php else: ?>
                                                <form action="<?php echo e(route('profile.follow.create', $user->id)); ?>" method="post">
                                                    <?php echo csrf_field(); ?>
                                                    <button type="submit" class="bg-blue-500 text-white px-4 py-1 rounded-md hover:bg-blue-600">
                                                        Follow
                                                    </button>
                                                </form>
                                            <?php endif; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                    
                    <div class="flex justify-center mt-6">
                        <?php echo e($all_users->links('vendor.pagination.custom')); ?>

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
<?php /**PATH C:\Users\Tamak\Desktop\gi_12_tripe_may\resources\views/profile/users_list_search.blade.php ENDPATH**/ ?>