
<div x-show="showEditModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
    <div class="bg-white p-6 rounded-lg w-full max-w-3xl">
        <div class="relative flex items-center justify-center h-16 my-5">
            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold absolute left-1/2 transform -translate-x-1/2">Edit Group</h1>
        </div>

        <form method="POST" action="<?php echo e(route('groups.update', $group->id)); ?>" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PATCH'); ?>

            <div class="mb-4 flex items-center justify-center">
                <label for="name-<?php echo e($group->id); ?>" class="block text-sm font-semibold text-black">Group Name</label>
                <input type="text" name="name" id="name-<?php echo e($group->id); ?>" value="<?php echo e($group->name); ?>"
                        class="w-3/4 mt-1 p-2 block rounded-md focus:ring focus:border-blue-300 ml-2" required>
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
                
                <div class="container mb-4 w-3/4 sm:w-2/3 md:w-1/2 lg:w-1/3  mr-2">
                    <label class="block text-sm font-semibold text-gray-700 text-center">Group Members</label>
                    <div class="space-y-2 mt-2 max-h-64 overflow-y-auto border p-2 rounded">
                        <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <label class="flex w-full justify-between items-center space-x-3 cursor-pointer">
                                <input type="checkbox" name="members[]" value="<?php echo e($user->id); ?>" class="hidden peer"
                                    <?php echo e($group->users->contains($user->id) ? 'checked' : ''); ?>>

                                <div class="flex items-center space-x-2">
                                    <div class="w-8 h-8 rounded-full bg-gray-300 flex items-center justify-center text-white text-sm font-bold">
                                        <?php echo e(strtoupper(substr($user->name, 0, 1))); ?>

                                    </div>
                                    <span class="text-sm text-gray-700"><?php echo e($user->name); ?></span>
                                </div>

                                <div class="w-4 h-4 rounded-full border-2 border-gray-400 peer-checked:bg-blue-400 peer-checked:border-blue-500 flex items-center justify-center transition">
                                    <i class="fa-solid fa-check text-white text-xs hidden peer-checked:block"></i>
                                </div>
                            </label>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>

                
                <div class="container w-1/3 mb-4 ml-4">
                    <label for="image-<?php echo e($group->id); ?>" class="block text-sm font-semibold text-gray-700 text-center">Group Image</label>

                    <img id="image-preview-<?php echo e($group->id); ?>" src="<?php echo e($group->image ? asset('storage/' . $group->image) : ''); ?>"
                        class="w-25 aspect-square rounded-full object-cover border border-gray-300 mx-auto <?php echo e($group->image ? '' : 'hidden'); ?>" alt="Preview">

                    <input type="file" name="image" id="image-<?php echo e($group->id); ?>" accept="image/*" class="mt-1 block w-full text-sm text-gray-500 text-center">
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

            <div class="flex justify-end mt-6 gap-2">
                <button type="button" @click="showEditModal = false" class="bg-white border border-gray-400 text-black px-4 py-2 rounded hover:bg-gray-300">Cancel</button>
                <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded hover:bg-green-700">Update Group</button>
            </div>
        </form>

        <script>
            document.getElementById('image-<?php echo e($group->id); ?>')?.addEventListener('change', function (event) {
                const preview = document.getElementById('image-preview-<?php echo e($group->id); ?>');
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


<div x-show="showDeleteModal" x-cloak class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
    <div class="bg-white p-6 rounded-lg w-full max-w-md">
        <h2 class="text-xl font-bold mb-4 text-red-600">Delete Group: <?php echo e($group->name); ?></h2>
        <p class="mb-4 text-gray-700">Are you sure you want to delete this group? This action cannot be undone.</p>
        <form method="POST" action="<?php echo e(route('groups.delete', $group->id)); ?>">
            <?php echo csrf_field(); ?>
            <?php echo method_field('DELETE'); ?>
            <div class="flex justify-end gap-2">
                <button type="button" @click="showDeleteModal = false" class="bg-gray-300 px-4 py-2 rounded">Cancel</button>
                <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded">Delete</button>
            </div>
        </form>
    </div>
</div>
<?php /**PATH /Users/kawaimayu/Desktop/gi_12_tripe_may/resources/views/groups/modals.blade.php ENDPATH**/ ?>