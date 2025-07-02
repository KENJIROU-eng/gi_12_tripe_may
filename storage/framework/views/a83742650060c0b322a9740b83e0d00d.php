

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
    <div style="background-image: url('/images/pexels-fotios-photos-1252983.jpg'); background-size: cover; background-position: center">
        <div class= "mt-5 h-[880px]">
            <div class="w-9/10 md:w-4/5 mx-auto sm:px-6 lg:px-8 h-full">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg h-full">
                    <div class="p-6 text-black dark:text-gray-100 h-full">
                        
                        <div class="relative flex items-center justify-center h-12 my-4">
                            <h1 class="text-3xl sm:text-3xl lg:text-3xl 2xl:text-5xl font-bold absolute left-1/2 transform -translate-x-1/2">Post List</h1>
                            <a href="<?php echo e(route('post.create')); ?>" class="ml-auto mr-3 right-40 no-underline text-end ">
                                <i class="fa-solid fa-plus ml-auto"></i> add Post
                            </a>
                        </div>
                        
                        <div class="mx-auto  mt-4 ">
                            <form action="<?php echo e(route('post.search')); ?>" method="get" class="w-full">
                                <div class="flex justify-center items-center mb-3 w-full">
                                    <select name="search" class="block border border-gray-300 rounded w-2/3 focus:ring-2 me-3">
                                        <?php if(isset($category_search)): ?>
                                            <option value="#">All categories</option>
                                            <?php $__currentLoopData = $all_categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php if($category->id != $category_search->id): ?>
                                                    <option value="<?php echo e($category->id); ?>"><?php echo e($category->name); ?></option>
                                                <?php else: ?>
                                                    <option value="<?php echo e($category->id); ?>" selected><?php echo e($category->name); ?></option>
                                                <?php endif; ?>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php else: ?>
                                            <option value="#" selected>All categories</option>
                                            <?php $__currentLoopData = $all_categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($category->id); ?>"><?php echo e($category->name); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php endif; ?>
                                    </select>

                                    <button type="submit" class="block text-white px-4 bg-green-500 py-2 font-semi-bold hover: border-green-500 hover:bg-green-600 transition duration-300 rounded-md">Search</button>
                                </div>
                            </form>

                            <div id="scroll-container" class="max-h-[660px] overflow-auto pb-12">
                                <div id="post-container" class="flex flex-wrap -mx-2" data-masonry='{"itemSelector": ".post-item", "columnWidth": ".post-sizer", "percentPosition": true }'>
                                    <div class="post-sizer w-full sm:w-1/2 lg:w-1/3"></div>

                                    <?php $__currentLoopData = $all_posts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $post): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="post-item w-full sm:w-1/2 lg:w-1/3 px-4 mb-6 transition duration-300 ease-in-out transform hover:scale-[1.03] hover:-translate-y-1 ">
                                            <div class="bg-white dark:bg-gray-700 shadow-xl  overflow-hidden">
                                                <a href="<?php echo e(route('post.show', $post->id)); ?>">
                                                    <img src="<?php echo e($post->image); ?>" alt="<?php echo e($post->title); ?>"
                                                        class="w-full h-auto object-cover transition-transform duration-300 hover:scale-100">
                                                </a>
                                                <p class="text-gray-500 font-extralight text-xs text-center mt-1"><?php echo e($post->user->name); ?></p>
                                                <div class="flex items-center px-3 pb-3">
                                                    <a href="<?php echo e(route('profile.show', $post->user->id)); ?>">
                                                        <?php if($post->user->avatar): ?>
                                                            <img src="<?php echo e($post->user->avatar); ?>" alt="<?php echo e($post->user->name); ?>" class="object-cover rounded-full w-12 h-12 hover:scale-110 duration-300 transition">
                                                        <?php else: ?>
                                                            <img src="<?php echo e(asset('images/ben-sweet-2LowviVHZ-E-unsplash.jpg')); ?>" alt="default avatar" class="object-cover rounded-full w-12 h-12 hover:scale-110 duration-300 transition">
                                                        <?php endif; ?>
                                                    </a>
                                                    <h2 class="text-xl font-bold mx-auto truncate max-w-[150px]"><?php echo e($post->title); ?></h2>
                                                    
                                                    <div class="flex items-center">
                                                        <div class="ml-4">
                                                            <?php if(in_array(Auth::User()->id, $post->likes->pluck('user_id')->toArray()) ): ?>
                                                                <form action="<?php echo e(route('post.like.delete',$post->id)); ?>" method="POST">
                                                                    <?php echo csrf_field(); ?>
                                                                    <?php echo method_field('DELETE'); ?>
                                                                    <button type="submit">
                                                                        <i class="fa-solid fa-heart text-red-500 text-xl mr-2"></i>
                                                                    </button>
                                                                </form>
                                                            <?php else: ?>
                                                                <a href="<?php echo e(route('post.like',$post->id)); ?>">
                                                                    <i class="fa-regular fa-heart text-gray-400 hover:text-red-500 text-xl mr-2"></i>
                                                                </a>
                                                            <?php endif; ?>
                                                        </div>

                                                        <div x-data="{open:false}" class="mr-4">
                                                            <button @click="open = true">
                                                                <?php echo e($post->likes()->count()); ?>

                                                            </button>
                                                            <?php echo $__env->make('posts.modals.likeUser_list', ['post' => $post], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
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
        </div>
    </div>


<script src="https://unpkg.com/masonry-layout@4/dist/masonry.pkgd.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        new Masonry('#post-container', {
            itemSelector: '.post-item',
            columnWidth: '.post-sizer',
            percentPosition: true
        });
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
<?php /**PATH /Users/kawaimayu/Desktop/gi_12_tripe_may/resources/views/posts/list_search.blade.php ENDPATH**/ ?>