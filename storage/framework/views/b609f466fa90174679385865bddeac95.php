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
    <div class="mt-5  hide-scrollbar <?php echo e(Route::currentRouteName() == 'post.list' ? 'relative z-10' : ''); ?>">
        <div class="w-11/12 md:w-4/5 mx-auto sm:px-6 lg:px-8 h-screen">
            <div class=" bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg h-full">
                <div class="p-4 text-black dark:text-gray-100 h-full">
                    
                    
                    <div class="relative flex items-center justify-center h-8 my-2 lg:mb-3">
                        <h1 class="text-2xl  lg:text-5xl font-bold absolute left-1/2 transform -translate-x-1/2">Post List</h1>
                        <a href="<?php echo e(route('post.create')); ?>" class="ml-auto  sm:mr-3 right-40 no-underline text-end text-xs sm:text-xl text-teal-500 hover:text-teal-700 bg-white border border-teal-500 hover:border-teal-700 rounded-3xl shadow-md p-1 sm:p-2">
                            <i class="fa-solid fa-plus ml-auto"></i> Add Post
                        </a>
                    </div>

                    
                    <div class="mx-auto mt-4">
                        <form action="<?php echo e(route('post.search')); ?>" method="get" class="w-full">
                            <div class="flex justify-center items-center mb-3 w-full">
                                <select name="search" class="block border border-gray-300 rounded w-2/3 focus:ring-2 me-3">
                                    <option value="" selected disabled>Search Category</option>
                                    <?php $__currentLoopData = $all_categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($category->id); ?>"><?php echo e($category->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <button type="submit" class="block text-white px-4 bg-teal-500 py-2 font-semibold hover:bg-teal-700 transition rounded-md">Search</button>
                            </div>
                        </form>
                    </div>

                    
                    <div id="scroll-container" class="max-h-[780px] overflow-auto pb-4">
                        <div id="post-container" class="flex flex-wrap -mx-2" wire:ignore>
                            
                            
                            <div class="post-sizer w-full sm:w-1/2 lg:w-1/3"></div>

                            <?php $__currentLoopData = $all_posts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $post): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="post-item w-full sm:w-1/2 lg:w-1/3 px-2 mb-4 transition transform hover:scale-[1.03] hover:-translate-y-1">
                                    <div class="relative group bg-white dark:bg-gray-700 shadow-xl overflow-hidden">
                                        <a href="<?php echo e(route('post.show', $post->id)); ?>">
                                            <img src="<?php echo e($post->image); ?>" alt="<?php echo e($post->title); ?>" class="w-full h-auto object-cover max-h-[480px]">
                                        </a>

                                        
                                        <div class="absolute inset-0 bg-black bg-opacity-30 flex flex-col justify-end opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none">
                                            <p class="text-white font-extralight text-xs text-center mt-1"><?php echo e($post->user->name); ?></p>
                                            <div class="flex items-center justify-between px-3 pb-3 pointer-events-auto">
                                                <a href="<?php echo e(route('profile.show', $post->user->id)); ?>" class="ml-2">
                                                    <?php if($post->user->avatar): ?>
                                                        <img src="<?php echo e($post->user->avatar); ?>" class="object-cover rounded-full w-12 h-12 hover:scale-110 transition border border-white shadow-lg">
                                                    <?php else: ?>
                                                        <img src="<?php echo e(asset('images/ben-sweet-2LowviVHZ-E-unsplash.jpg')); ?>" class="object-cover rounded-full w-12 h-12 hover:scale-110 transition">
                                                    <?php endif; ?>
                                                </a>
                                                <h2 class="text-md sm:text-xl font-bold text-center truncate max-w-[150px] text-white"><?php echo e(Str::limit($post->title, 12)); ?></h2>

                                                
                                                <div wire:ignore>
                                                    
                                                    <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('post-like', ['post' => $post, 'viewType' => 'list']);

$__html = app('livewire')->mount($__name, $__params, $post->id, $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    <script src="https://unpkg.com/imagesloaded@4/imagesloaded.pkgd.min.js"></script>
    <script src="https://unpkg.com/masonry-layout@4/dist/masonry.pkgd.min.js"></script>
    <script>
        function initMasonry() {
            const container = document.querySelector('#post-container');
            if (!container) return;

            if (window.masonryInstance) {
                window.masonryInstance.destroy();
            }

            imagesLoaded(container, function () {
                window.masonryInstance = new Masonry(container, {
                    itemSelector: '.post-item',
                    columnWidth: '.post-sizer',
                    percentPosition: true
                });
            });
        }

        function safeMasonry() {
            initMasonry();
            setTimeout(initMasonry, 300); 
        }

        document.addEventListener('DOMContentLoaded', safeMasonry);
        document.addEventListener('livewire:load', () => {
            safeMasonry();
            Livewire.hook('message.processed', safeMasonry);
        });
    </script>
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
<?php /**PATH /Users/kawaimayu/Desktop/gi_12_tripe_may/resources/views/posts/list.blade.php ENDPATH**/ ?>