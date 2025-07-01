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
    <div class= "mt-5">
        <div class="w-9/10 md:w-4/5 mx-auto sm:px-6 lg:px-8 h-screen ">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg h-full">
                <div class="p-5 text-black dark:text-gray-100">
                    
                    <div class="relative flex items-center justify-center h-12 my-4">
                        <h1 class="text-md sm:text-2xl lg:text-3xl 2xl:text-5xl font-bold absolute left-1/2 transform -translate-x-1/2">Edit Post</h1>
                        <div class="flex ml-auto items-center">
                            <div class="col-auto bg-gray-500 rounded-full w-12 h-12 ml-4">
                                <?php if($post->user->avatar): ?>
                                    <img src="<?php echo e($post->user->avatar); ?>" alt="<?php echo e($post->user->name); ?>" class="object-cover rounded-full w-12 h-12">
                                <?php else: ?>
                                    <img src="<?php echo e(asset('images/ben-sweet-2LowviVHZ-E-unsplash.jpg')); ?>" alt="default avatar" class="object-cover rounded-full w-12 h-12">
                                <?php endif; ?>
                            </div>
                            <a href="<?php echo e(route('profile.show', $post->user->id)); ?>">
                                <div class="col-auto ml-3"><?php echo e($post->user->name); ?></div>
                            </a>
                        </div>
                    </div>
                    <div class="h-[2px] w-full bg-gradient-to-r from-green-500 via-lime-500 to-emerald-500 my-2"></div>
                    
                    <div class="mx-auto h-full mt-6 mb-24">
                        <form action="<?php echo e(route('post.update', $post->id)); ?>" method="post" enctype="multipart/form-data">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('PATCH'); ?>
                            <div class="w-3/4 mx-auto">

                                
                                <div class="mb-2" x-data="{ count: 0 }">
                                    <label for="title" class="block text-sm font-semibold mb-1">Title</label>
                                    <input type="text" name="title" id="title" maxlength="30" x-model="countValue" @input="count = $event.target.value.length" value="<?php echo e($post->title); ?>"
                                    class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring focus:border-blue-100">
                                    <div class="text-right text-sm text-gray-500 mt-1">
                                        <span x-text="count"></span>/30
                                    </div>
                                </div>
                                <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="text-red-500 text-xs"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

                                <div class="flex flex-col md:flex-row gap-4 mb-4">
                                    <div class="mt-2">
                                        <label for="image" class="block text-sm font-semibold mb-2">Image</label>
                                        <img id="image-preview" class="rounded-md max-h-[200px] object-cover" src="<?php echo e($post->image); ?>" alt="Image Preview" style="min-width: 100px; max-width: 300px; width: auto;">
                                    </div>
                                    <div class="flex flex-col justify-start md:justify-end items-start md:items-end w-full sm:w-auto">
                                        <input type="file" name="image" id="image" class="form-control w-full sm:w-auto"
                                            aria-describedby="image-info" onchange="previewImage(event)">
                                        <div class="form-text text-gray-500 mt-1 text-sm break-words" id="image-info">
                                            The acceptable formats are jpeg, jpg, png, and gif only. <br>
                                            Max file size is 2096kb.
                                        </div>
                                    </div>
                                </div>

                                <?php $__errorArgs = ['image'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="text-red-500 text-xs"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

                                
                                <label  class="block text-sm font-semibold mb-1" for="category_name">Category</label>
                                <div class="max-h-20 space-y-2 mb-2 overflow-y-auto p-2 rounded">
                                    <?php $__empty_1 = true; $__currentLoopData = $all_categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <label class="flex items-center space-x-3 cursor-pointer" for="category_name">
                                        <?php if(in_array($category->id, $categoryPost_id)): ?>
                                            <input type="checkbox" name="category_name[]" value="<?php echo e($category->id); ?>" checked>
                                            <div class="flex items-center space-x-2">
                                                <span class="text-sm text-gray-700"><?php echo e($category->name); ?></span>
                                            </div>
                                        <?php else: ?>
                                            <input type="checkbox" name="category_name[]" value="<?php echo e($category->id); ?>">
                                            <div class="flex items-center space-x-2">
                                                <span class="text-sm text-gray-700"><?php echo e($category->name); ?></span>
                                            </div>
                                        <?php endif; ?>
                                    </label>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <p class="text-sm text-gray-500">No Categories</p>
                                    <?php endif; ?>
                                </div>
                                <?php $__errorArgs = ['category_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="text-red-500 text-xs"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

                                
                                <div class="mb-4" x-data="{ count: 0, max: 500 }">
                                    <label for="description" class="block text-sm font-semibold mb-1">Description</label>
                                    <textarea name="description" id="description" rows="5" cols="200"  maxlength="500" x-model="$el.value" @input="count = $event.target.value.length"
                                    class="w-full border border-gray-300 rounded focus:outline-none focus:ring focus:border-blue-100"><?php echo e($post->description); ?></textarea>
                                    <div class="text-right text-sm mt-1" :class="{ 'text-red-500': count >= max, 'text-gray-500': count < max }">
                                        <span x-text="count"></span>/<span x-text="max"></span>
                                    </div>
                                    <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="text-red-500 text-xs"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>

                                
                                <div class="grid grid-cols-4 w-full  gap-2">
                                    <div class="col-span-1 col-start-2 w-full">
                                        <a href="<?php echo e(route('post.show', $post->id)); ?>" class="block text-center w-full border border-gray-400 hover:bg-gray-300 py-2 text-black rounded-md">Cancel</a>
                                    </div>
                                    <div class="col-span-1 col-start-3 w-full">
                                        <button type="submit"
                                            class="w-full bg-green-500
                                                text-white py-2 rounded-md transition duration-300
                                                hover:bg-gradient-to-r  from-green-500 via-lime-500 to-emerald-500 hover:text-white hover:shadow-lg">
                                            Edit Post
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                        
                        <script src="<?php echo e(asset('js/previewImage.js')); ?>"></script>
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
<?php /**PATH /Users/kawaimayu/Desktop/gi_12_tripe_may/resources/views/posts/edit.blade.php ENDPATH**/ ?>