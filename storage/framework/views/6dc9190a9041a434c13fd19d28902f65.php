<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54 = $attributes; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\AppLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'h-screen flex flex-col overflow-hidden']); ?>
    <div class="min-h-screen bg-cover bg-center bg-no-repeat bg-fixed" style="background-image: url('https://res.cloudinary.com/dpwrycc89/image/upload/v1750757614/pexels-jplenio-1133505_ijwxpn.jpg');">
        <div class="pt-8 flex-1 overflow-y-auto flex flex-col lg:flex-row gap-4 max-w-screen-3xl mx-auto px-4 pb-24 md:pb-0">
            <div class="bg-white dark:bg-gray-800 shadow-xl rounded-lg p-4 mx-auto">
                <div class="text-black dark:text-gray-100">
                    
                    <div class="flex flex-col md:flex-row items-center justify-between text-center mb-10 gap-2 md:gap-0 relative">
                        
                        <div class="order-1 md:order-1">
                            <a href="<?php echo e(route('dashboard')); ?>" class="inline-flex items-center text-sm text-blue-500 hover:underline">
                                <i class="fa-solid fa-arrow-left mr-1"></i> Back
                            </a>
                        </div>

                        
                        <h1 class="order-2 md:order-2 text-3xl sm:text-4xl md:text-6xl font-bold text-gray-800 dark:text-gray-100 animate-fadeIn">
                            <i class="fa-solid fa-i"></i>
                            <i class="fa-solid fa-t"></i>
                            <i class="fa-solid fa-i"></i>
                            <i class="fa-solid fa-n"></i>
                            <i class="fa-solid fa-e"></i>
                            <i class="fa-solid fa-r"></i>
                            <i class="fa-solid fa-a"></i>
                            <i class="fa-solid fa-r"></i>
                            <i class="fa-solid fa-y"></i>
                        </h1>

                        
                        <div class="order-3 md:order-3">
                            <a href="<?php echo e(route('itinerary.share')); ?>"
                            class="inline-flex items-center px-3 py-1.5 bg-green-500 hover:bg-green-600 text-white text-sm font-semibold rounded-lg shadow">
                                <i class="fa-solid fa-circle-plus mr-2"></i>
                                Create
                            </a>
                        </div>
                    </div>

                    
                    <div class="max-w-6xl mx-auto mt-8">
                        
                        <div class="hidden md:grid md:grid-cols-12 items-center text-sm font-semibold border-b-2 border-gray-500 pb-2">
                            <div class="md:col-span-1 ms-4">Avatar</div>
                                <div class="md:col-span-2 cursor-pointer flex items-center gap-1 hover:bg-gray-200 dark:hover:bg-gray-700 px-2 py-1 rounded" data-sort="user">
                                    Created by <i class="fa-solid fa-sort sort-icon" data-key="user"></i>
                                </div>
                                <div class="md:col-span-2 cursor-pointer flex items-center gap-1 hover:bg-gray-200 dark:hover:bg-gray-700 px-2 py-1 rounded" data-sort="group">
                                    Group <i class="fa-solid fa-sort sort-icon" data-key="group"></i>
                                </div>
                                <div class="md:col-span-3 cursor-pointer flex items-center gap-1 hover:bg-gray-200 dark:hover:bg-gray-700 px-2 py-1 rounded" data-sort="date">
                                    Date <i class="fa-solid fa-sort sort-icon" data-key="date"></i>
                                </div>
                                <div class="md:col-span-3 cursor-pointer flex items-center gap-1 hover:bg-gray-200 dark:hover:bg-gray-700 px-2 py-1 rounded" data-sort="title">
                                    Title <i class="fa-solid fa-sort sort-icon" data-key="title"></i>
                                </div>
                                <div class="md:col-span-1 cursor-pointer flex items-center gap-1 hover:bg-gray-200 dark:hover:bg-gray-700 px-2 py-1 rounded" data-sort="finished">
                                    Status <i class="fa-solid fa-sort sort-icon" data-key="finished"></i>
                                </div>

                        </div>

                        
                        <div class="hidden md:grid md:grid-cols-12 items-center text-sm text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 py-2">
                            <div class="md:col-span-1"></div>
                            <div class="md:col-span-2 px-2">
                                <select id="filterUser" class="w-full border-gray-300 rounded max-h-40 overflow-y-auto"">
                                    <option value="">All</option>
                                    <?php $__currentLoopData = $all_itineraries->pluck('user')->unique('id'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e(strtolower($user->name)); ?>"><?php echo e($user->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            <div class="md:col-span-2 px-2">
                                <select id="filterGroup" class="w-full border-gray-300 rounded max-h-40 overflow-y-auto">
                                    <option value="">All</option>
                                    <?php $__currentLoopData = $all_itineraries->pluck('group')->filter()->unique('id'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e(strtolower($group->name)); ?>"><?php echo e($group->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <option value="no-group">No Group</option>
                                </select>
                            </div>
                            <div class="md:col-span-3 px-2 flex gap-1">
                                <input type="date" id="filterDateFrom" class="w-1/2 border-gray-300 rounded text-sm">
                                <input type="date" id="filterDateTo" class="w-1/2 border-gray-300 rounded text-sm">
                            </div>
                            <div class="md:col-span-3 px-2">
                                <input id="searchInput" type="text" placeholder="Search title..." class="w-full border-gray-300 rounded px-2 py-1 text-sm">
                            </div>
                            <div class="md:col-span-1 flex justify-start items-center px-2">
                                <button id="clearSearchBtn" class="text-gray-400 hover:text-gray-600 text-sm border rounded px-2 py-1">
                                    <i class="fa-solid fa-xmark mr-1"></i> Clear
                                </button>
                            </div>
                        </div>

                        
                        <div class="md:hidden grid grid-cols-1 gap-3 p-4 bg-gray-100 dark:bg-gray-700 text-sm rounded mb-4">
                            <div>
                                <label for="mobileSort" class="block text-gray-700 dark:text-gray-300 mb-1">Sort by</label>
                                <select id="mobileSort" class="w-full rounded border-gray-300">
                                    <option value="">Default</option>
                                    <option value="user">Created by</option>
                                    <option value="group">Group</option>
                                    <option value="date">Date</option>
                                    <option value="title">Title</option>
                                    <option value="finished">Done</option>
                                </select>
                            </div>

                            <div>
                                <label for="mobileFilterUser" class="block text-gray-700 dark:text-gray-300 mb-1">Created by</label>
                                <select id="mobileFilterUser" class="w-full rounded border-gray-300">
                                    <option value="">All</option>
                                    <?php $__currentLoopData = $all_itineraries->pluck('user')->unique('id'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e(strtolower($user->name)); ?>"><?php echo e($user->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>

                            <div>
                                <label for="mobileFilterGroup" class="block text-gray-700 dark:text-gray-300 mb-1">Group</label>
                                <select id="mobileFilterGroup" class="w-full rounded border-gray-300">
                                    <option value="">All</option>
                                    <?php $__currentLoopData = $all_itineraries->pluck('group')->filter()->unique('id'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e(strtolower($group->name)); ?>"><?php echo e($group->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <option value="no group">No Group</option>
                                </select>
                            </div>

                            <div class="flex gap-2">
                                <div class="w-1/2">
                                    <label for="mobileFilterDateFrom" class="block text-gray-700 dark:text-gray-300 mb-1">From</label>
                                    <input type="date" id="mobileFilterDateFrom" class="w-full rounded border-gray-300 text-sm">
                                </div>
                                <div class="w-1/2">
                                    <label for="mobileFilterDateTo" class="block text-gray-700 dark:text-gray-300 mb-1">To</label>
                                    <input type="date" id="mobileFilterDateTo" class="w-full rounded border-gray-300 text-sm">
                                </div>
                            </div>

                            <div>
                                <label for="mobileSearchInput" class="block text-gray-700 dark:text-gray-300 mb-1">Title</label>
                                <input type="text" id="mobileSearchInput" placeholder="Search title..." class="w-full rounded border-gray-300 px-2 py-1 text-sm">
                            </div>

                            <div class="flex justify-end">
                                <button id="mobileClearSearchBtn" class="text-gray-500 hover:text-gray-800 text-sm border rounded px-3 py-1">
                                    <i class="fa-solid fa-xmark mr-1"></i> Clear
                                </button>
                            </div>
                        </div>

                        
                        <div id="scrollContainer" class="w-full overflow-x-hidden overflow-y-auto border rounded mb-2 max-h-none md:max-h-[580px]">

                            <div id="itineraryContainer" class="max-w-6xl mx-auto">
                                <?php $__empty_1 = true; $__currentLoopData = $all_itineraries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $itinerary): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <div class="itinerary-row w-full flex flex-col md:grid md:grid-cols-12 gap-2 py-2 border-b text-sm md:text-base <?php echo e($itinerary->finish_at ? 'opacity-50' : ''); ?>"
                                        data-user="<?php echo e(strtolower($itinerary->user->name)); ?>"
                                        data-group="<?php echo e(strtolower($itinerary->group->name ?? 'no-group')); ?>"
                                        data-date="<?php echo e($itinerary->start_date); ?>"
                                        data-title="<?php echo e(strtolower($itinerary->title)); ?>"
                                        data-created="<?php echo e($itinerary->created_at); ?>"
                                        data-finished="<?php echo e($itinerary->finish_at ? '1' : '0'); ?>">

                                        
                                        <div class="md:col-span-1 flex flex-col items-center md:items-start justify-start ms-0 md:ms-6">
                                            <a href="<?php echo e(route('profile.show', $itinerary->created_by)); ?>">
                                                <?php if($itinerary->user->avatar): ?>
                                                    <img src="<?php echo e($itinerary->user->avatar); ?>" alt="<?php echo e($itinerary->user->name); ?>"
                                                        class="w-12 h-12 rounded-full object-cover" />
                                                <?php else: ?>
                                                    <i class="fa-solid fa-circle-user text-gray-400 w-12 h-12 text-[48px] leading-[48px] rounded-full"></i>
                                                <?php endif; ?>
                                            </a>
                                        </div>

                                        
                                        <div class="md:col-span-2 flex flex-col items-center md:items-center justify-center text-center md:text-center">
                                            <a href="<?php echo e(route('profile.show', $itinerary->created_by)); ?>" class="text-blue-600 font-semibold">
                                                <?php echo e(Str::limit($itinerary->user->name, 20)); ?>

                                            </a>
                                        </div>

                                        
                                        <div class="md:col-span-2 flex flex-col items-center md:items-center justify-center text-center md:text-center">
                                            <?php if($itinerary->group): ?>
                                                <a href="<?php echo e(route('message.show', $itinerary->group->id)); ?>" class="text-blue-600 font-semibold"><?php echo e(Str::limit($itinerary->group->name, 15)); ?></a>
                                            <?php else: ?>
                                                <span class="text-gray-400">No Group</span>
                                            <?php endif; ?>
                                        </div>

                                        
                                        <div class="md:col-span-3 flex flex-col items-center md:items-center justify-center text-center md:text-center">
                                            <span>
                                                <?php echo e(\Carbon\Carbon::parse($itinerary->start_date)->format('M. d, Y')); ?>

                                                ～ <?php echo e(\Carbon\Carbon::parse($itinerary->end_date)->format('M. d, Y')); ?>

                                            </span>
                                        </div>

                                        
                                        <div class="md:col-span-3 flex flex-col items-center md:items-center justify-center text-center md:text-center">
                                            <a href="<?php echo e(route('itinerary.show', $itinerary->id)); ?>" class="text-blue-600 hover:underline font-semibold">
                                                <?php echo e(Str::limit($itinerary->title, 30)); ?>

                                            </a>
                                        </div>

                                        
                                        <div class="md:col-span-1 flex justify-center items-center space-x-4">
                                            <?php if(!$itinerary->finish_at): ?>
                                                <a href="<?php echo e(route('itinerary.edit', $itinerary->id)); ?>" title="Edit">
                                                    <i class="fa-solid fa-pen text-yellow-300 text-lg hover:text-yellow-700"></i>
                                                </a>
                                                <span class="text-red-500">
                                                    <?php echo $__env->make('itineraries.modals.delete', ['itinerary' => $itinerary, 'showText' => false], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                                                </span>
                                            <?php else: ?>
                                                <span class="text-xs bg-green-500 text-white px-2 py-1 rounded">
                                                    <i class="fa-solid fa-check mr-1"></i> Finished
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <p class="text-center text-gray-500 py-4">No itineraries found.</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <button id="scrollToTopBtn" class="fixed bottom-12 left-1/2 transform -translate-x-1/2 z-50 bg-green-500 text-white rounded-full p-1 shadow-lg transition-opacity duration-300 opacity-0 pointer-events-none md:hidden" aria-label="Scroll to top">
        <i class="fa-solid fa-arrow-up"></i> Go to Top
    </button>

    <?php $__env->startPush('scripts'); ?>
        <script src="<?php echo e(asset('js/itineraries/index.js')); ?>"></script>
    <?php $__env->stopPush(); ?>

    
    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fadeIn {
            animation: fadeIn 0.6s ease-out forwards;
        }

        .sort-icon {
            transition: color 0.2s, transform 0.2s;
        }

        [data-sort]:hover .sort-icon {
            color: #3b82f6;
            transform: scale(1.1);
        }

        [data-sort] {
            transition: background-color 0.2s;
            border-radius: 0.375rem;
        }

        [data-sort]:hover {
            background-color: #e5e7eb;
        }

        @media (prefers-color-scheme: dark) {
            [data-sort]:hover {
                background-color: #374151;
            }
        }
    </style>

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
<?php /**PATH C:\Users\Tamak\Desktop\gi_12_tripe_may\resources\views/itineraries/index.blade.php ENDPATH**/ ?>