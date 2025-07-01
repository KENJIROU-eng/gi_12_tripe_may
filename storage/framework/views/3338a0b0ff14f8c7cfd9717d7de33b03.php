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
        <div class="w-11/12 md:w-4/5 mx-auto sm:px-4 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg">
                <div class="p-4 sm:p-6 text-black dark:text-gray-100">
                    
                    <div class="text-center my-5">
                        <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold">Admin Page</h1>
                    </div>

                    
                    <div class="flex flex-col lg:flex-row gap-6">
                        
                        <div class="w-full lg:w-1/4">
                            <div class="space-y-3">
                                <?php $__currentLoopData = [
                                    'admin.users.show' => 'Users',
                                    'admin.posts.show' => 'Posts',
                                    'admin.categories.show' => 'Categories',
                                    'admin.itineraries.show' => 'Itineraries',
                                ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $route => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <a href="<?php echo e(route($route)); ?>"
                                    class="block text-center py-2 rounded-md font-semibold transition
                                            <?php echo e(request()->routeIs($route) ? 'bg-green-600 text-white' : 'bg-green-100 hover:bg-green-300'); ?>">
                                        <?php echo e($label); ?>

                                    </a>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>

                        
                        <div class="w-full lg:w-3/4 overflow-x-auto">
                            <table class="min-w-full table-auto border border-gray-300 text-center">
                                <thead class="bg-green-500 text-white text-sm sm:text-base">
                                    <tr>
                                        <th class="py-3 px-2">#</th>
                                        <th class="py-3 px-2">User</th>
                                        <th class="py-3 px-2">Username</th>
                                        <th class="py-3 px-2">Image</th>
                                        <th class="py-3 px-2">Title</th>
                                        <th class="py-3 px-2">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $all_posts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $post): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr class="<?php echo e($loop->odd ? 'bg-green-100' : 'bg-green-50'); ?> text-sm sm:text-base">
                                            <td class="py-2"><?php echo e($post->id); ?></td>
                                            <td class="py-2">
                                                <?php if($post->user->avatar): ?>
                                                    <img src="<?php echo e($post->user->avatar); ?>" alt="<?php echo e($post->user->name); ?>" class="w-8 h-8 sm:w-10 sm:h-10 rounded-full mx-auto object-cover">
                                                <?php else: ?>
                                                    <i class="fa-solid fa-circle-user text-blue-600 text-lg sm:text-xl"></i>
                                                <?php endif; ?>
                                            </td>
                                            <td class="py-2"><?php echo e($post->user->name); ?></td>
                                            <td class="py-2">
                                                <img src="<?php echo e($post->image); ?>" alt="<?php echo e($post->title); ?>" class="w-12 h-12 rounded-md mx-auto object-cover">
                                            </td>
                                            <td class="py-2"><?php echo e($post->title); ?></td>
                                            <td class="py-2">
                                                <form action="<?php echo e(route('admin.posts.delete', $post->id)); ?>" method="POST" onsubmit="return confirm('Are you sure to delete this post?')">
                                                    <?php echo csrf_field(); ?>
                                                    <?php echo method_field('DELETE'); ?>
                                                    <button type="submit" class="text-red-500 hover:text-red-700">
                                                        <i class="fa-solid fa-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    
                    <div class="flex justify-center mt-6">
                        <?php echo e($all_posts->links('vendor.pagination.custom')); ?>

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
<?php /**PATH /Users/kawaimayu/Desktop/gi_12_tripe_may/resources/views/admin/posts/show.blade.php ENDPATH**/ ?>