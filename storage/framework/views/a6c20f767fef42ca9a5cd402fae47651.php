<div class="flex items-center">
    <div class="ml-4 pointer-events-auto">
        <button wire:click="toggleLike" class="focus:outline-none">
            <!--[if BLOCK]><![endif]--><?php if($isLiked): ?>
                <i class="fa-solid fa-heart text-red-500 <?php echo e($viewType === 'list' ? 'text-xl' : 'text-2xl'); ?> mr-2"></i>
            <?php else: ?>
                <i class="fa-regular fa-heart 
                <?php echo e($viewType === 'list' ? 'text-white text-xl' : 'text-gray-400 text-2xl'); ?> 
                hover:text-red-500 mr-2"></i>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
        </button>
    </div>
    
    
    <div class="mr-2">
        <!--[if BLOCK]><![endif]--><?php if($viewType === 'list'): ?>
            <span class="text-white"><?php echo e($likeCount); ?></span>
        <?php elseif($viewType === 'show'): ?>
            <div x-data="{open:false}">
                <button @click="open = true">
                    <?php echo e($likeCount); ?>

                </button>

                
                <div x-show="open" x-transition class="fixed inset-0 bg-gray-500/75 z-10 flex items-center justify-center w-full">
                    <div class="bg-white p-6 rounded shadow max-w-md w-full max-h-[500px] overflow-y-auto">
                        
                        <div class="flex px-6 py-4 text-center">
                            <h1 class="text-2xl font-bold">Users who like this post</h1>
                            <button @click="open = false" class="ml-auto">
                                <i class="fa-solid fa-xmark text-red-500 text-2xl"></i>
                            </button>
                        </div>
                        <hr class="border-green-500 border-1">
                        
                        <div class="mx-auto h-full mt-8">
                            <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $post->likes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $like): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="flex items-center justify-between bg-white rounded-lg shadow p-4 mb-4 hover:bg-gray-50 transition">
                                    <a href="" class="flex items-center space-x-4 w-full ml-2">
                                        <!--[if BLOCK]><![endif]--><?php if($like->user->avatar): ?>
                                            <img src="<?php echo e($like->user->avatar); ?>" alt="<?php echo e($like->user->name); ?>" class="w-12 h-12 rounded-full object-cover">
                                        <?php else: ?>
                                        <div class="w-12 h-12 bg-gray-300 rounded-full flex items-center justify-center text-white font-bold">
                                            <?php echo e(strtoupper(substr($like->user->name, 0, 1))); ?>

                                        </div>
                                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

                                        <div class="flex flex-row sm:flex-row sm:items-center sm:justify-center text-center sm:space-x-2 ">
                                            <p class="font-semibold text-2xl truncate "><?php echo e($like->user->name); ?></p>
                                        </div>
                                    </a>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
    </div>
</div><?php /**PATH /Users/kawaimayu/Desktop/gi_12_tripe_may/resources/views/livewire/post-like.blade.php ENDPATH**/ ?>