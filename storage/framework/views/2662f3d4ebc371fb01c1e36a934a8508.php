
<div x-show="open" class="fixed inset-0 bg-gray-500/75 z-10 flex items-center justify-center w-full">
    <div class="bg-white p-6 rounded shadow max-w-md w-full max-h-[500px] overflow-y-auto">
        
        <div class="flex px-6 py-4 text-center">
            <h1 class="text-2xl font-bold">Users who like this post</h1>
            <a href="<?php echo e(route('post.list')); ?>" class="ml-auto">
                <i class="fa-solid fa-xmark text-red-500 text-2xl"></i>
            </a>
        </div>
        <hr class="border-green-500 border-1">
        
        <div class="mx-auto h-full mt-8">
            <?php $__currentLoopData = $post->likes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $like): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="flex items-center justify-between bg-white rounded-lg shadow p-4 mb-4 hover:bg-gray-50 transition">
                    <a href="" class="flex items-center space-x-4 w-full ml-2">
                        <?php if($like->user->avatar): ?>
                            <img src="<?php echo e($like->user->avatar); ?>" alt="<?php echo e($like->user->name); ?>" class="w-12 h-12 rounded-full object-cover">
                        <?php else: ?>
                        <div class="w-12 h-12 bg-gray-300 rounded-full flex items-center justify-center text-white font-bold">
                            <?php echo e(strtoupper(substr($like->user->name, 0, 1))); ?>

                        </div>
                        <?php endif; ?>

                        <div class="flex flex-row sm:flex-row sm:items-center sm:justify-center text-center sm:space-x-2 ">
                            <p class="font-semibold text-2xl truncate "><?php echo e($like->user->name); ?></p>
                        </div>
                    </a>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</div>
<?php /**PATH /Users/kawaimayu/Desktop/gi_12_tripe_may/resources/views/posts/modals/likeUser_list.blade.php ENDPATH**/ ?>