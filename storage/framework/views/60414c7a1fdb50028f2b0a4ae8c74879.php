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
    
        <div class= "mt-5 h-[905px]">
            <div class="w-9/10 md:w-4/5 mx-auto sm:px-6 lg:px-8 h-full">
                <div class="bg-gray-50 dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg h-full">
                    <div class="pt-2 text-black dark:text-gray-100 h-full">
                        <div class="relative flex justify-center items-center flex-col h-full">
                            <a href="<?php echo e(route('post.list')); ?>" class="text-teal-500 hover:text-teal-700 font-semibold text-xl flex items-center mb-2 md:mb-0 md:absolute md:left-0 md:top-0 md:pl-4">
                                <i class="fa-solid fa-arrow-left mr-2"></i> Post List
                            </a>
                            <div class="hidden md:block w-[100px]"></div>
                            <div class="container shadow-lg 2xl:w-3/5 w-4/5 max-h-[90vh] overflow-y-auto border mx-auto mb-3">
                                <div class="flex items-center mt-4">
                                    <?php if($post->user): ?>
                                    <div class="rounded-full overflow-hidden w-14 h-14 ml-4">
                                        <?php if($post->user->avatar): ?>
                                            <a href="<?php echo e(route('profile.show', $post->user->id)); ?>">
                                                <img src="<?php echo e($post->user->avatar); ?>" alt="<?php echo e($post->user->name); ?>" class="object-cover w-14 h-14">
                                            </a>
                                        <?php else: ?>
                                            <a href="<?php echo e(route('profile.show', $post->user->id)); ?>">
                                                <img src="<?php echo e(asset('images/ben-sweet-2LowviVHZ-E-unsplash.jpg')); ?>" alt="default avatar" class="object-cover w-14 h-14">
                                            </a>
                                        <?php endif; ?>
                                        </div>
                                        <div class="ml-2"><?php echo e($post->user->name); ?></div>

                                        <div x-data="{ showModal: false }" class="ml-auto mr-6">
                                            <?php if (isset($component)) { $__componentOriginaldf8083d4a852c446488d8d384bbc7cbe = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaldf8083d4a852c446488d8d384bbc7cbe = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dropdown','data' => ['align' => 'right','width' => '46']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dropdown'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['align' => 'right','width' => '46']); ?>
                                                 <?php $__env->slot('trigger', null, []); ?> 
                                                    <i class="fa-solid fa-ellipsis cursor-pointer"></i>
                                                 <?php $__env->endSlot(); ?>

                                                 <?php $__env->slot('content', null, []); ?> 
                                                    <?php if($post->user->id == Auth::User()->id): ?>
                                                        <a href="<?php echo e(route('post.edit', $post->id)); ?>"
                                                        class="block px-4 py-2 text-sm text-green-700 hover:bg-green-100">
                                                        Edit
                                                        </a>
                                                        <button @click="showModal = true"
                                                            class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-100">
                                                            Delete
                                                        </button>
                                                    <?php else: ?>
                                                        <?php if($post->user->isFollowed()): ?>
                                                            <form action="<?php echo e(route('profile.follow.delete', $post->user->id)); ?>" method="post">
                                                                <?php echo csrf_field(); ?>
                                                                <?php echo method_field('DELETE'); ?>
                                                                <button
                                                                    type="submit"
                                                                    class="block w-full text-left px-4 py-2 text-sm text-gray-600 hover:bg-gray-100">
                                                                    Following
                                                                </button>
                                                            </form>
                                                        <?php else: ?>
                                                            <form action="<?php echo e(route('profile.follow.create', $post->user->id)); ?>" method="post">
                                                            <?php echo csrf_field(); ?>
                                                                <button
                                                                    type="submit"
                                                                    class="block w-full text-left px-4 py-2 text-sm text-blue-600 hover:bg-blue-100">
                                                                    Follow
                                                                </button>
                                                            </form>
                                                        <?php endif; ?>
                                                    <?php endif; ?>
                                                 <?php $__env->endSlot(); ?>
                                             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginaldf8083d4a852c446488d8d384bbc7cbe)): ?>
<?php $attributes = $__attributesOriginaldf8083d4a852c446488d8d384bbc7cbe; ?>
<?php unset($__attributesOriginaldf8083d4a852c446488d8d384bbc7cbe); ?>
<?php endif; ?>
<?php if (isset($__componentOriginaldf8083d4a852c446488d8d384bbc7cbe)): ?>
<?php $component = $__componentOriginaldf8083d4a852c446488d8d384bbc7cbe; ?>
<?php unset($__componentOriginaldf8083d4a852c446488d8d384bbc7cbe); ?>
<?php endif; ?>
                                            <?php echo $__env->make('posts.modals.delete', ['post' => $post], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                                        </div>
                                    </div>
                                    <?php endif; ?>
                                    <!-- Title -->
                                    <h1 class="font-bold text-center mb-2 text-2xl"><?php echo e($post->title); ?></h1>

                                    <!-- Image -->
                                    <div class="mb-2 bg-gradient-to-r from-gray-200 via-white to-gray-200 ">
                                        <img src="<?php echo e($post->image); ?>" alt="<?php echo e($post->title); ?>" class="object-cover mx-auto max-h-[340px] sm:max-h-[400px] md:max-h-[500px] lg:max-h-[560px] shadow">
                                    </div>

                                    <!-- Likes, Categories -->
                                    <div class="flex items-center">
                                        
                                        
                                        <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('post-like', ['post' => $post, 'viewType' => 'show']);

$__html = app('livewire')->mount($__name, $__params, $post->id, $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>


                                        

                                        <div class="col-auto">
                                            
                                            <div x-data="{ open: false }">
                                                <div class="flex items-center space-x-1">
                                                    <button @click="open = true" class="text-gray-400 hover:text-gray-600 text-2xl ml-1">
                                                        <i class="fa-regular fa-comments"></i>
                                                    </button>
                                                    <p class="text-black"><?php echo e($post->comments->count()); ?></p>
                                                </div>

                                                
                                                <?php echo $__env->make('posts.modals.comments', ['post' => $post], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                                            </div>
                                        </div>
                                        <div class="ml-auto text-blue-400 mr-3">
                                            <?php $__currentLoopData = $post->categoryPost; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $categoryPost): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            #<?php echo e($categoryPost->category->name); ?>

                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </div>
                                    </div>
                                    <div class="text-xs text-gray-500 ml-3 mt-1">
                                        <?php echo e($post->created_at->format('M d, Y')); ?>

                                    </div>
                                    
                                        <div class="px-4 mb-2">
                                            <div class="font-light whitespace-pre-line break-words text-gray-800 dark:text-gray-100">
                                                <?php echo e($post->description); ?>

                                            </div>
                                        </div>
                                    
                                </div>
                            </div>
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

<?php /**PATH C:\Users\Tamak\Desktop\gi_12_tripe_may\resources\views/posts/show.blade.php ENDPATH**/ ?>