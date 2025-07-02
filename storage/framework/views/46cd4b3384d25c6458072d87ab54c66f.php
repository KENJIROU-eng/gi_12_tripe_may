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
            <div class="w-9/10 md:w-4/5 mx-auto sm:px-6 lg:px-8">
                <div class="bg-white/95 dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class=" text-black dark:text-gray-100">
                        
                        <div class="relative flex items-center justify-center h-16 my-3 sm:my-5">
                            <h1 class=" text-3xl lg:text-5xl xl:text-6xl font-semibold font-bree absolute left-1/2 transform -translate-x-1/2">Group List</h1>
                            <a href="<?php echo e(route('groups.create')); ?>" class="absolute right-3 text-xs sm:text-base lg:text-lg font-medium text-teal-500 hover:text-teal-700 bg-white border border-teal-500 hover:border-teal-700 rounded-3xl shadow-md p-1 sm:p-2"><i class="fa-solid fa-plus"></i> New Group</a>
                        </div>
                        <div class="p-[2px] bg-gradient-to-r from-stone-200 via-stone-400 to-stone-200"></div>
                        
                        <div class="mx-auto overflow-y-auto max-h-[670px] mt-8 flex-1">
                            <?php if(!($groups->isNotEmpty())): ?>
                                <div class="text-center text-lg my-60">
                                    <h2 class="mb-4 text-gray-500">No group created yet.</h2>
                                    <div class="text-blue-500">
                                        <a href="<?php echo e(route('groups.create')); ?>">
                                            <i class="fa-solid fa-plus"></i>
                                            add group
                                        </a>
                                    </div>
                                </div>
                            <?php else: ?>
                                <?php if(($latestMessages->isNotEmpty())): ?>
                                    <?php $__currentLoopData = $latestMessages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $message): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="flex items-center justify-between fonu-serif bg-white rounded-lg shadow p-5 mb-3 mx-3 hover:bg-amber-100 transition">
                                            <a href="<?php echo e(route('message.show', $message->group->id)); ?>" class="flex items-center space-x-4 w-full ml-2">
                                                <?php if($message->group->image): ?>
                                                    <img src="<?php echo e(asset('storage/' . $message->group->image)); ?>" alt="Group Image" class="w-14 h-14 rounded-full object-cover">
                                                <?php else: ?>
                                                <div class="w-12 h-12 bg-gray-300 rounded-full flex items-center justify-center text-white font-bold">
                                                    <?php echo e(strtoupper(substr($message->group->name, 0, 1))); ?>

                                                </div>
                                                <?php endif; ?>
                                                <div class="flex flex-row sm:flex-row sm:items-center sm:justify-center text-center sm:space-x-2">
                                                    <?php if($message->group->users->count() == 2): ?>
                                                        <?php if(($message->group->name == Auth::User()->name) || ($message->group->user_id == Auth::User()->id)): ?>
                                                            <?php $__currentLoopData = $message->group->users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                <?php if(($message->group->name == $user->name) || ($message->group->user_id == $user->id)): ?>
                                                                    <?php if(Auth::User()->id != $user->id): ?>
                                                                        <p class="font-semibold text-2xl truncate"><?php echo e($user->name); ?></p>
                                                                    <?php endif; ?>
                                                                <?php endif; ?>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                        <?php else: ?>
                                                            <p class="font-semibold text-2xl truncate "><?php echo e($message->group->name); ?></p>
                                                        <?php endif; ?>
                                                    <?php else: ?>
                                                        <p class="font-semibold text-2xl truncate "><?php echo e($message->group->name); ?></p>
                                                    <?php endif; ?>
                                                    <p class="text-lg ml-3">(<?php echo e($message->group->members->count()); ?>)</p>
                                                </div>
                                                <?php if($nonReadCount): ?>
                                                    <?php if($nonReadCount[$message->group->id] > 0): ?>
                                                        <div class="flex items-center justify-center w-6 h-6 bg-red-500 text-white rounded-full">
                                                            <p class="text-sm font-semibold"><?php echo e($nonReadCount[$message->group->id]); ?></p>
                                                        </div>
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                            </a>
                                            <div x-data="{ showEditModal: false, showDeleteModal: false }" class="mb-6 relative">
                                                <div class="flex items-center justify-between  rounded">
                                                    <div class="relative" x-data="{ open: false }">
                                                        <button @click="open = !open" class="text-gray-600 hover:text-black focus:outline-none">
                                                            <i class="fa-solid fa-ellipsis text-xl"></i>
                                                        </button>
                                                        <div x-show="open" @click.away="open = false" x-cloak class="absolute right-0 mt-2 w-32 bg-white border rounded shadow z-50">
                                                            <button @click="showEditModal = true" class="block w-full text-left px-4 py-2 text-sm text-green-700 hover:bg-green-100">
                                                                Edit
                                                            </button>
                                                            <button @click="showDeleteModal = true" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-100">
                                                                Delete
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <?php echo $__env->make('groups.modals', ['group' => $message->group, 'users' => $users], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                                            </div>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php $__currentLoopData = $groups_filtered; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="flex items-center justify-between fonu-serif bg-white rounded-lg shadow-lg p-5 mb-3 mx-3 hover:bg-orange-100 transition">
                                            <a href="<?php echo e(route('message.show', $group->id)); ?>" class="flex items-center space-x-4 w-full ml-2">
                                                <?php if($group->image): ?>
                                                    <img src="<?php echo e(asset('storage/' . $group->image)); ?>" alt="Group Image" class="w-14 h-14 rounded-full object-cover">
                                                <?php else: ?>
                                                <div class="w-14 h-14 bg-gray-300 rounded-full flex items-center justify-center text-white font-bold">
                                                    <?php echo e(strtoupper(substr($group->name, 0, 1))); ?>

                                                </div>
                                                <?php endif; ?>
                                                <div class="flex flex-row sm:flex-row sm:items-center sm:justify-center text-center sm:space-x-2 ">
                                                    <p class="font-semibold text-2xl truncate "><?php echo e($group->name); ?></p>
                                                    <p class="text-lg ml-3">(<?php echo e($group->members->count()); ?>)</p>
                                                </div>
                                                <?php if($nonReadCount): ?>
                                                    <?php if($nonReadCount[$group->id] > 0): ?>
                                                        <div class="flex items-center justify-center w-6 h-6 bg-red-500 text-white rounded-full">
                                                            <p class="text-sm font-semibold"><?php echo e($nonReadCount[$group->id]); ?></p>
                                                        </div>
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                            </a>
                                            <div x-data="{ showEditModal: false, showDeleteModal: false }" class="mb-6 relative">
                                                <div class="flex items-center justify-between  rounded">
                                                    <div class="relative" x-data="{ open: false }">
                                                        <button @click="open = !open" class="text-gray-600 hover:text-black focus:outline-none">
                                                            <i class="fa-solid fa-ellipsis text-xl"></i>
                                                        </button>
                                                        <div x-show="open" @click.away="open = false" x-cloak class="absolute right-0 mt-2 w-32 bg-white border rounded shadow z-50">
                                                            <button @click="showEditModal = true" class="block w-full text-left px-4 py-2 text-sm text-green-700 hover:bg-green-100">
                                                                Edit
                                                            </button>
                                                            <button @click="showDeleteModal = true" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-100">
                                                                Delete
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <?php echo $__env->make('groups.modals', ['group' => $group, 'users' => $users], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                                            </div>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php else: ?>
                                    <?php $__currentLoopData = $groups_filtered; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="flex items-center justify-between fonu-serif bg-white rounded-lg shadow p-5 mb-3 mx-3 hover:bg-orange-100 transition">
                                            <a href="<?php echo e(route('message.show', $group->id)); ?>" class="flex items-center space-x-4 w-full ml-2">
                                                <?php if($group->image): ?>
                                                    <img src="<?php echo e(asset('storage/' . $group->image)); ?>" alt="Group Image" class="w-14 h-14 rounded-full object-cover">
                                                <?php else: ?>
                                                <div class="w-12 h-12 bg-gray-300 rounded-full flex items-center justify-center text-white font-bold">
                                                    <?php echo e(strtoupper(substr($group->name, 0, 1))); ?>

                                                </div>
                                                <?php endif; ?>
                                                <div class="flex flex-row sm:flex-row sm:items-center sm:justify-center text-center sm:space-x-2 ">
                                                    <p class="font-semibold text-2xl truncate "><?php echo e($group->name); ?></p>
                                                    <p class="text-lg ml-3">(<?php echo e($group->members->count()); ?>)</p>
                                                </div>
                                                <?php if($nonReadCount): ?>
                                                    <?php if($nonReadCount[$group->id] > 0): ?>
                                                        <div class="flex items-center justify-center w-6 h-6 bg-red-500 text-white rounded-full">
                                                            <p class="text-sm font-semibold"><?php echo e($nonReadCount[$group->id]); ?></p>
                                                        </div>
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                            </a>
                                            <div x-data="{ showEditModal: false, showDeleteModal: false }" class="mb-6 relative">
                                                <div class="flex items-center justify-between  rounded">
                                                    <div class="relative" x-data="{ open: false }">
                                                        <button @click="open = !open" class="text-gray-600 hover:text-black focus:outline-none">
                                                            <i class="fa-solid fa-ellipsis text-xl"></i>
                                                        </button>
                                                        <div x-show="open" @click.away="open = false" x-cloak class="absolute right-0 mt-2 w-32 bg-white border rounded shadow z-50">
                                                            <button @click="showEditModal = true" class="block w-full text-left px-4 py-2 text-sm text-green-700 hover:bg-green-100">
                                                                Edit
                                                            </button>
                                                            <button @click="showDeleteModal = true" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-100">
                                                                Delete
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <?php echo $__env->make('groups.modals', ['group' => $group, 'users' => $users], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                                            </div>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endif; ?>
                            <?php endif; ?>
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
<?php /**PATH /Users/kawaimayu/Desktop/gi_12_tripe_may/resources/views/groups/list.blade.php ENDPATH**/ ?>