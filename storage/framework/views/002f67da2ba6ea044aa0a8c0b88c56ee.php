
<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54 = $attributes; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\AppLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'h-screen']); ?>
    <div class= "mt-5">
        <div class="w-9/10 md:w-4/5 mx-auto sm:px-6 lg:px-8 h-full">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg h-full">
                <div class="p-6 text-black dark:text-gray-100">
                    
                    <div class="relative flex items-center justify-center h-16 my-5">
                        <h1 class=" sm:text-3xl md:text-4xl  font-bold absolute left-1/2 transform -translate-x-1/2">New Group</h1>
                    </div>
                    <div class="h-[2px] w-full bg-gradient-to-r from-yellow-400 via-orange-400 to-pink-400 my-4"></div>
                    
                    <div class="mx-auto h-full mt-8">
                        <form action="/group/store" method="post" enctype="multipart/form-data">
                            <?php echo csrf_field(); ?>
                            <div class="mb-4 flex items-center justify-center">
                                <label for="name" class="block text-sm font-semibold text-black ">Group Name</label>
                                <input type="text" name="name" id="name" class="w-3/4 mt-1 p-2 block  rounded-md focus:ring focus:border-blue-300 ml-2" required>
                            </div>
                            <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="text-red-500 text-xs"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

                            <div class="flex justify-center">
                                <div class="container mb-4 w-3/4 sm:w-2/3 md:w-1/2 lg:w-1/3  md:mx-0">
                                    <label class="block text-sm font-semibold text-gray-700 text-center">Group Member</label>
                                    <div class="space-y-2 mt-2 max-h-[500px] overflow-y-auto border p-2 rounded">
                                        <?php $__empty_1 = true; $__currentLoopData = Auth::User()->following; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                            <label class="flex w-full justify-between items-center space-x-3 cursor-pointer">
                                                <input type="checkbox" name="members[]" value="<?php echo e($user->following->id); ?>" class="hidden peer">
                                                <div class="flex items-center space-x-2 max-h-400px">
                                                    <div class="w-8 h-8 rounded-full bg-gray-300 flex items-center justify-center text-white text-sm font-bold">
                                                        <?php if($user->following->avatar): ?>
                                                            <img src="<?php echo e($user->following->avatar); ?>" alt="<?php echo e($user->following->name); ?>" class="w-8 h-8 rounded-full">
                                                        <?php else: ?>
                                                            <?php echo e(strtoupper(substr($user->following->name, 0, 1))); ?>

                                                        <?php endif; ?>
                                                    </div>
                                                    <span class="text-sm text-gray-700"><?php echo e($user->following->name); ?></span>
                                                </div>
                                                <div class=" w-4 h-4 rounded-full border-2 border-gray-400 peer-checked:bg-blue-400 peer-checked:border-blue-500 flex items-center justify-center transition">
                                                    <i class="fa-solid fa-check text-white text-xs hidden peer-checked:block"></i>
                                                </div>
                                            </label>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                            <p class="text-sm text-gray-500">No Following Users</p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="container w-1/3 mb-4 ml-4">
                                    <label for="image" class="block text-sm font-semibold text-gray-700 text-center">Group Image</label>
                                    <!--image preview-->
                                    <img id="image-preview" class="w-25 aspect-square rounded-full object-cover border border-gray-300 hidden mx-auto" alt="Preview">
                                    <input type="file" name="image" id="image" accept="image/*"
                                    class="mt-1 block w-full text-sm text-gray-500 text-center">
                                    <div class="form-text text-gray-500 mt-1" id="image-info">
                                        The acceptable formats are jpeg, jpg, png, and gif only. <br>
                                        Max file size is 1048kb.
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
                            </div>
                            <div class="flex justify-center mt-6">
                                <div class=" px-4 py-2 border border-gray-400 rounded-md hover:bg-gray-300 duration-300 mr-3">
                                    <a href="<?php echo e(route('groups.index')); ?>" class="block text-center" >Cancel</a>
                                </div>
                                <button type="submit" class="bg-yellow-300 text-black px-4 py-2 rounded hover:bg-gradient-to-r from-yellow-400 via-orange-400 to-pink-400 hover:text-white hover:shadow-lg duration-300 max-w-md text-lg">
                                    Create
                                </button>
                            </div>
                        </form>
                        <!--image preview-->
                        <script>
                            document.getElementById('image').addEventListener('change', function (event) {
                                const preview = document.getElementById('image-preview');
                                const file = event.target.files[0];

                                if (file) {
                                    const reader = new FileReader();
                                    reader.onload = function (e) {
                                        preview.src = e.target.result;
                                        preview.classList.remove('hidden');
                                    };
                                    reader.readAsDataURL(file);
                                } else {
                                    preview.src = '';
                                    preview.classList.add('hidden');
                                }
                            });
                        </script>
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

<?php /**PATH /Users/kawaimayu/Desktop/gi_12_tripe_may/resources/views/groups/create.blade.php ENDPATH**/ ?>