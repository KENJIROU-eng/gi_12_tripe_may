<nav class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700 fixed top-0 left-0 right-0 z-40 shadow h-16">
    <div x-data="{ open: false, planOpen: false }" class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 h-full">
        <div class="h-full relative flex items-center justify-between">

            
            <div class="flex items-center gap-6 h-full flex-shrink-0">
                
                <div class="flex items-center space-x-2">
                    <a href="<?php echo e(route('dashboard')); ?>" class="h-10 w-10 flex-shrink-0">
                        <img src="<?php echo e(asset('images/tripeas_logo_20250617.png')); ?>" alt="logo" class="h-full w-full object-cover rounded-md">
                    </a>
                    <span class="text-lg font-semibold text-gray-900 dark:text-gray-100 tracking-wide">Tripe@s</span>
                </div>

                
                <div class="hidden lg:flex items-center gap-2 text-sm text-indigo-600 dark:text-indigo-400 cursor-pointer" @click="planOpen = true">
                    <i class="fa-solid fa-calendar-day"></i>
                    <span>Today's Plan</span>
                </div>

                
                <button type="button" class="lg:hidden flex items-center justify-center text-indigo-600 dark:text-indigo-400 cursor-pointer focus:outline-none" @click="planOpen = true" aria-label="Open today's plan">
                    <i class="fa-solid fa-calendar-day text-xl"></i>
                </button>
            </div>

            
            <div x-show="planOpen" x-cloak class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
                <div @click.away="planOpen = false" class="bg-white dark:bg-gray-800 rounded-lg shadow-lg w-full max-w-md flex flex-col max-h-[50vh] overflow-hidden">

                    
                    <div class="p-4 border-b border-gray-300 dark:border-gray-600">
                        <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Today's Plan</h2>
                    </div>

                    
                    <div class="p-4 overflow-y-auto flex-1">
                        <?php if($todayItineraries && $todayItineraries->count()): ?>
                            <ul class="text-sm text-gray-700 dark:text-gray-200 space-y-1">
                                <?php $__currentLoopData = $todayItineraries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $itinerary): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li class="grid grid-cols-[60px_1fr]">
                                        <span class="font-medium text-gray-500 dark:text-gray-200">Date:</span>
                                        <span><?php echo e(\Carbon\Carbon::parse($itinerary->start_date)->format('M. d, Y')); ?> ～ <?php echo e(\Carbon\Carbon::parse($itinerary->end_date)->format('M. d, Y')); ?></span>
                                    </li>
                                    <li class="grid grid-cols-[60px_1fr]">
                                        <span class="font-medium text-gray-500 dark:text-gray-200">Title:</span>
                                        <a href="<?php echo e(route('itinerary.show', $itinerary->id)); ?>" class="text-blue-500 hover:underline"><?php echo e(Str::limit($itinerary->title, 20)); ?></a>
                                    </li>
                                    <li class="grid grid-cols-[60px_1fr] mb-2">
                                        <span class="font-medium text-gray-500 dark:text-gray-200">Group:</span>
                                        <?php if($itinerary->group): ?>
                                            <a href="<?php echo e(route('message.show', $itinerary->group->id)); ?>" class="text-blue-500 hover:underline">
                                                <?php echo e(Str::limit($itinerary->group->name, 20)); ?>

                                            </a>
                                        <?php else: ?>
                                            <span class="text-gray-400 italic">No group</span>
                                        <?php endif; ?>
                                    </li>
                                    <li><hr class="my-1 border-gray-300 dark:border-gray-600"></li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                        <?php else: ?>
                            <p class="text-sm text-gray-500 dark:text-gray-400">No plans for today.</p>
                        <?php endif; ?>
                    </div>

                    
                    <div class="px-4 py-2 border-t border-gray-300 dark:border-gray-600 text-right">
                        <button @click="planOpen = false" class="px-4 py-2 text-sm bg-indigo-600 text-white rounded hover:bg-indigo-700">Close</button>
                    </div>
                </div>
            </div>

            
            <div class="absolute left-1/2 transform -translate-x-1/2 hidden sm:flex text-xl items-center space-x-6">
                <?php if(auth()->guard()->check()): ?>
                    
                    <a href="<?php echo e(route('post.list')); ?>"
                    class="relative inline-block transition-all duration-200
                        <?php echo e(request()->routeIs('post*') ? 'text-red-500 font-bold scale-150' : 'text-gray-500 scale-90 hover:text-red-500 hover:scale-100'); ?>">
                        <span class="relative inline-block">
                            <span class="relative z-10">Post</span>
                            <i class="fa-solid fa-camera absolute inset-0 flex items-center justify-center
                                <?php echo e(request()->routeIs('post*') ? 'text-red-300 opacity-40' : 'text-red-300 opacity-30'); ?>

                                text-3xl pointer-events-none"></i>
                        </span>
                    </a>

                    
                    <span class="text-gray-300 dark:text-gray-500 select-none">｜</span>

                    
                    <a href="<?php echo e(route('itinerary.index')); ?>"
                    class="relative inline-block transition-all duration-200
                        <?php echo e(request()->routeIs('itinerary*') ? 'text-blue-500 font-bold scale-150' : 'text-gray-500 scale-90 hover:text-blue-500 hover:scale-100'); ?>">
                        <span class="relative inline-block">
                            <span class="relative z-10">Itinerary</span>
                            <i class="fa-solid fa-road absolute inset-0 flex items-center justify-center
                                <?php echo e(request()->routeIs('itinerary*') ? 'text-blue-300 opacity-40' : 'text-blue-300 opacity-30'); ?>

                                text-3xl pointer-events-none"></i>
                        </span>
                    </a>

                    
                    <span class="text-gray-300 dark:text-gray-500 select-none">｜</span>

                    
                    <a href="<?php echo e(route('groups.index')); ?>"
                    class="relative inline-block transition-all duration-200
                        <?php echo e(request()->routeIs('group*') ? 'text-yellow-500 font-bold scale-150' : 'text-gray-500 scale-90 hover:text-yellow-500 hover:scale-100'); ?>">
                        <span class="relative inline-block">
                            <span class="relative z-10">Group</span>
                            <i class="fa-solid fa-comments absolute inset-0 flex items-center justify-center
                                <?php echo e(request()->routeIs('group*') ? 'text-yellow-300 opacity-40' : 'text-yellow-300 opacity-30'); ?>

                                text-3xl pointer-events-none"></i>
                        </span>
                    </a>
                <?php endif; ?>
            </div>

            
            <div class="flex items-center h-full gap-2 sm:gap-4 pr-2 flex-shrink-0">
                
                <?php if($groupIds): ?>
                    <div x-data="{ notificationOpen: false }" class="relative">
                        <button data-notification-button @click.stop="notificationOpen = !notificationOpen" class="relative px-2 sm:ms-4 text-gray-600 dark:text-gray-200 hover:text-green-500 focus:outline-none focus:ring-0 focus:border-transparent" aria-label="Notification" title="Notification">

                            <i class="fa-solid fa-comment-dots text-xl"></i>
                            <?php if($nonReadCount_total > 0): ?>
                                <span class="absolute -top-1 -right-1 inline-block w-4 h-4 bg-red-500 text-white text-xs rounded-full flex items-center justify-center">
                                    <?php echo e($nonReadCount_total); ?>

                                </span>
                            <?php endif; ?>
                        </button>
                        <div x-show="notificationOpen" @click.outside="notificationOpen = false" x-cloak class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded shadow-lg z-50">
                            <?php $hasUnread = false; ?>
                            <?php $__currentLoopData = $groups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php if($nonReadCount[$group->id] > 0): ?>
                                    <?php $hasUnread = true; ?>
                                    <a href="<?php echo e(route('message.show', $group->id)); ?>" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-100 hover:bg-gray-100 dark:hover:bg-gray-800">
                                        <?php echo e($group->name); ?>

                                        <span class="ml-2 inline-block text-xs bg-red-500 text-white px-2 py-0.5 rounded-full">
                                            <?php echo e($nonReadCount[$group->id]); ?>

                                        </span>
                                    </a>
                                <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php if(! $hasUnread): ?>
                                <div class="px-4 py-2 text-sm text-gray-500 dark:text-gray-300">
                                    No notification
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>

                
                <button id="sound-toggle" class=" text-gray-600 dark:text-gray-200 hover:text-yellow-500 focus:outline-none" aria-label="Toggle notification sound" title="Toggle notification sound">
                    <i id="sound-icon" class="fa-solid fa-bell text-xl"></i>
                    <span id="sound-status" class="ml-1 text-sm text-gray-500 dark:text-gray-300 hidden sm:inline-block">
                        ON
                    </span>
                </button>

                
                
                <div class="hidden sm:flex items-center space-x-4">
                    <?php if(auth()->guard()->check()): ?>
                        <?php if (isset($component)) { $__componentOriginaldf8083d4a852c446488d8d384bbc7cbe = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaldf8083d4a852c446488d8d384bbc7cbe = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dropdown','data' => ['align' => 'right','width' => '48']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dropdown'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['align' => 'right','width' => '48']); ?>
                             <?php $__env->slot('trigger', null, []); ?> 
                                <button class="w-8 h-8 rounded-full overflow-hidden border-2 <?php echo e($nonReadCount_total > 0 ? 'border-red-500' : 'border-transparent'); ?>">
                                    <img src="<?php echo e(Auth::user()->avatar ?? asset('images/ben-sweet-2LowviVHZ-E-unsplash.jpg')); ?>" alt="Avatar" class="w-full h-full object-cover">
                                </button>
                             <?php $__env->endSlot(); ?>
                             <?php $__env->slot('content', null, []); ?> 
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin')): ?>
                                    <?php if (isset($component)) { $__componentOriginal68cb1971a2b92c9735f83359058f7108 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal68cb1971a2b92c9735f83359058f7108 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dropdown-link','data' => ['href' => route('admin.users.show')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dropdown-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('admin.users.show'))]); ?><i class="fa-solid fa-user-secret"></i> Admin <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal68cb1971a2b92c9735f83359058f7108)): ?>
<?php $attributes = $__attributesOriginal68cb1971a2b92c9735f83359058f7108; ?>
<?php unset($__attributesOriginal68cb1971a2b92c9735f83359058f7108); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal68cb1971a2b92c9735f83359058f7108)): ?>
<?php $component = $__componentOriginal68cb1971a2b92c9735f83359058f7108; ?>
<?php unset($__componentOriginal68cb1971a2b92c9735f83359058f7108); ?>
<?php endif; ?>
                                <?php endif; ?>
                                <?php if (isset($component)) { $__componentOriginal68cb1971a2b92c9735f83359058f7108 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal68cb1971a2b92c9735f83359058f7108 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dropdown-link','data' => ['href' => route('profile.show', Auth::id())]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dropdown-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('profile.show', Auth::id()))]); ?><i class="fa-solid fa-address-card"></i> Profile <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal68cb1971a2b92c9735f83359058f7108)): ?>
<?php $attributes = $__attributesOriginal68cb1971a2b92c9735f83359058f7108; ?>
<?php unset($__attributesOriginal68cb1971a2b92c9735f83359058f7108); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal68cb1971a2b92c9735f83359058f7108)): ?>
<?php $component = $__componentOriginal68cb1971a2b92c9735f83359058f7108; ?>
<?php unset($__componentOriginal68cb1971a2b92c9735f83359058f7108); ?>
<?php endif; ?>
                                <?php if (isset($component)) { $__componentOriginal68cb1971a2b92c9735f83359058f7108 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal68cb1971a2b92c9735f83359058f7108 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dropdown-link','data' => ['href' => route('profile.users.list')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dropdown-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('profile.users.list'))]); ?><i class="fa-solid fa-magnifying-glass"></i> Search Users <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal68cb1971a2b92c9735f83359058f7108)): ?>
<?php $attributes = $__attributesOriginal68cb1971a2b92c9735f83359058f7108; ?>
<?php unset($__attributesOriginal68cb1971a2b92c9735f83359058f7108); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal68cb1971a2b92c9735f83359058f7108)): ?>
<?php $component = $__componentOriginal68cb1971a2b92c9735f83359058f7108; ?>
<?php unset($__componentOriginal68cb1971a2b92c9735f83359058f7108); ?>
<?php endif; ?>
                                <?php if (isset($component)) { $__componentOriginal68cb1971a2b92c9735f83359058f7108 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal68cb1971a2b92c9735f83359058f7108 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dropdown-link','data' => ['href' => route('settings')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dropdown-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('settings'))]); ?><i class="fa-solid fa-gear"></i> Settings <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal68cb1971a2b92c9735f83359058f7108)): ?>
<?php $attributes = $__attributesOriginal68cb1971a2b92c9735f83359058f7108; ?>
<?php unset($__attributesOriginal68cb1971a2b92c9735f83359058f7108); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal68cb1971a2b92c9735f83359058f7108)): ?>
<?php $component = $__componentOriginal68cb1971a2b92c9735f83359058f7108; ?>
<?php unset($__componentOriginal68cb1971a2b92c9735f83359058f7108); ?>
<?php endif; ?>
                                <form method="POST" action="<?php echo e(route('logout')); ?>">
                                    <?php echo csrf_field(); ?>
                                    <?php if (isset($component)) { $__componentOriginal68cb1971a2b92c9735f83359058f7108 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal68cb1971a2b92c9735f83359058f7108 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dropdown-link','data' => ['href' => '#','onclick' => 'event.preventDefault(); clearAudioSettings(); this.closest(\'form\').submit();']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dropdown-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => '#','onclick' => 'event.preventDefault(); clearAudioSettings(); this.closest(\'form\').submit();']); ?>
                                        <i class="fa-solid fa-arrow-right-from-bracket"></i> Log Out
                                     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal68cb1971a2b92c9735f83359058f7108)): ?>
<?php $attributes = $__attributesOriginal68cb1971a2b92c9735f83359058f7108; ?>
<?php unset($__attributesOriginal68cb1971a2b92c9735f83359058f7108); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal68cb1971a2b92c9735f83359058f7108)): ?>
<?php $component = $__componentOriginal68cb1971a2b92c9735f83359058f7108; ?>
<?php unset($__componentOriginal68cb1971a2b92c9735f83359058f7108); ?>
<?php endif; ?>
                                </form>
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
                    <?php endif; ?>
                </div>

                
                <div class="flex sm:hidden items-center">
                    <button @click="open = !open" class="p-2 focus:outline-none">
                        <svg class="h-6 w-6 text-gray-700 dark:text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path x-show="!open" d="M4 6h16M4 12h16M4 18h16"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path x-show="open" d="M6 18L18 6M6 6l12 12"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </button>
                </div>

            </div>
        </div>

        
        <div x-show="open" x-cloak class="fixed inset-0 z-30">
            <div class="absolute inset-0 bg-black bg-opacity-50" @click="open = false"></div>
            <div class="relative w-64 bg-white dark:bg-gray-900 p-4 h-full overflow-y-auto z-40">
                <?php if(auth()->guard()->check()): ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin')): ?>
                        <?php if (isset($component)) { $__componentOriginald69b52d99510f1e7cd3d80070b28ca18 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald69b52d99510f1e7cd3d80070b28ca18 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.responsive-nav-link','data' => ['href' => route('admin.users.show')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('responsive-nav-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('admin.users.show'))]); ?>Admin <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald69b52d99510f1e7cd3d80070b28ca18)): ?>
<?php $attributes = $__attributesOriginald69b52d99510f1e7cd3d80070b28ca18; ?>
<?php unset($__attributesOriginald69b52d99510f1e7cd3d80070b28ca18); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald69b52d99510f1e7cd3d80070b28ca18)): ?>
<?php $component = $__componentOriginald69b52d99510f1e7cd3d80070b28ca18; ?>
<?php unset($__componentOriginald69b52d99510f1e7cd3d80070b28ca18); ?>
<?php endif; ?>
                    <?php endif; ?>
                    <?php if (isset($component)) { $__componentOriginald69b52d99510f1e7cd3d80070b28ca18 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald69b52d99510f1e7cd3d80070b28ca18 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.responsive-nav-link','data' => ['href' => route('post.list'),'active' => request()->routeIs('post*')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('responsive-nav-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('post.list')),'active' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(request()->routeIs('post*'))]); ?>Post <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald69b52d99510f1e7cd3d80070b28ca18)): ?>
<?php $attributes = $__attributesOriginald69b52d99510f1e7cd3d80070b28ca18; ?>
<?php unset($__attributesOriginald69b52d99510f1e7cd3d80070b28ca18); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald69b52d99510f1e7cd3d80070b28ca18)): ?>
<?php $component = $__componentOriginald69b52d99510f1e7cd3d80070b28ca18; ?>
<?php unset($__componentOriginald69b52d99510f1e7cd3d80070b28ca18); ?>
<?php endif; ?>
                    <?php if (isset($component)) { $__componentOriginald69b52d99510f1e7cd3d80070b28ca18 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald69b52d99510f1e7cd3d80070b28ca18 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.responsive-nav-link','data' => ['href' => route('itinerary.index'),'active' => request()->routeIs('itinerary*')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('responsive-nav-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('itinerary.index')),'active' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(request()->routeIs('itinerary*'))]); ?>Itinerary <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald69b52d99510f1e7cd3d80070b28ca18)): ?>
<?php $attributes = $__attributesOriginald69b52d99510f1e7cd3d80070b28ca18; ?>
<?php unset($__attributesOriginald69b52d99510f1e7cd3d80070b28ca18); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald69b52d99510f1e7cd3d80070b28ca18)): ?>
<?php $component = $__componentOriginald69b52d99510f1e7cd3d80070b28ca18; ?>
<?php unset($__componentOriginald69b52d99510f1e7cd3d80070b28ca18); ?>
<?php endif; ?>
                    <?php if (isset($component)) { $__componentOriginald69b52d99510f1e7cd3d80070b28ca18 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald69b52d99510f1e7cd3d80070b28ca18 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.responsive-nav-link','data' => ['href' => route('groups.index'),'active' => request()->routeIs('group*')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('responsive-nav-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('groups.index')),'active' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(request()->routeIs('group*'))]); ?>Group <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald69b52d99510f1e7cd3d80070b28ca18)): ?>
<?php $attributes = $__attributesOriginald69b52d99510f1e7cd3d80070b28ca18; ?>
<?php unset($__attributesOriginald69b52d99510f1e7cd3d80070b28ca18); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald69b52d99510f1e7cd3d80070b28ca18)): ?>
<?php $component = $__componentOriginald69b52d99510f1e7cd3d80070b28ca18; ?>
<?php unset($__componentOriginald69b52d99510f1e7cd3d80070b28ca18); ?>
<?php endif; ?>
                    <?php if (isset($component)) { $__componentOriginald69b52d99510f1e7cd3d80070b28ca18 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald69b52d99510f1e7cd3d80070b28ca18 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.responsive-nav-link','data' => ['href' => route('profile.show', Auth::id())]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('responsive-nav-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('profile.show', Auth::id()))]); ?>Profile <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald69b52d99510f1e7cd3d80070b28ca18)): ?>
<?php $attributes = $__attributesOriginald69b52d99510f1e7cd3d80070b28ca18; ?>
<?php unset($__attributesOriginald69b52d99510f1e7cd3d80070b28ca18); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald69b52d99510f1e7cd3d80070b28ca18)): ?>
<?php $component = $__componentOriginald69b52d99510f1e7cd3d80070b28ca18; ?>
<?php unset($__componentOriginald69b52d99510f1e7cd3d80070b28ca18); ?>
<?php endif; ?>
                    <?php if (isset($component)) { $__componentOriginald69b52d99510f1e7cd3d80070b28ca18 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald69b52d99510f1e7cd3d80070b28ca18 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.responsive-nav-link','data' => ['href' => route('profile.users.list')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('responsive-nav-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('profile.users.list'))]); ?>Search Users <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald69b52d99510f1e7cd3d80070b28ca18)): ?>
<?php $attributes = $__attributesOriginald69b52d99510f1e7cd3d80070b28ca18; ?>
<?php unset($__attributesOriginald69b52d99510f1e7cd3d80070b28ca18); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald69b52d99510f1e7cd3d80070b28ca18)): ?>
<?php $component = $__componentOriginald69b52d99510f1e7cd3d80070b28ca18; ?>
<?php unset($__componentOriginald69b52d99510f1e7cd3d80070b28ca18); ?>
<?php endif; ?>
                    <?php if (isset($component)) { $__componentOriginald69b52d99510f1e7cd3d80070b28ca18 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald69b52d99510f1e7cd3d80070b28ca18 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.responsive-nav-link','data' => ['href' => route('settings')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('responsive-nav-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('settings'))]); ?>Settings <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald69b52d99510f1e7cd3d80070b28ca18)): ?>
<?php $attributes = $__attributesOriginald69b52d99510f1e7cd3d80070b28ca18; ?>
<?php unset($__attributesOriginald69b52d99510f1e7cd3d80070b28ca18); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald69b52d99510f1e7cd3d80070b28ca18)): ?>
<?php $component = $__componentOriginald69b52d99510f1e7cd3d80070b28ca18; ?>
<?php unset($__componentOriginald69b52d99510f1e7cd3d80070b28ca18); ?>
<?php endif; ?>
                    <form method="POST" action="<?php echo e(route('logout')); ?>">
                        <?php echo csrf_field(); ?>
                        <?php if (isset($component)) { $__componentOriginald69b52d99510f1e7cd3d80070b28ca18 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald69b52d99510f1e7cd3d80070b28ca18 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.responsive-nav-link','data' => ['href' => '#','onclick' => 'event.preventDefault(); this.closest(\'form\').submit();']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('responsive-nav-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => '#','onclick' => 'event.preventDefault(); this.closest(\'form\').submit();']); ?>Log Out <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald69b52d99510f1e7cd3d80070b28ca18)): ?>
<?php $attributes = $__attributesOriginald69b52d99510f1e7cd3d80070b28ca18; ?>
<?php unset($__attributesOriginald69b52d99510f1e7cd3d80070b28ca18); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald69b52d99510f1e7cd3d80070b28ca18)): ?>
<?php $component = $__componentOriginald69b52d99510f1e7cd3d80070b28ca18; ?>
<?php unset($__componentOriginald69b52d99510f1e7cd3d80070b28ca18); ?>
<?php endif; ?>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script>
        function clearAudioSettings() {
            const userId = document.body.dataset.userId;
            if (userId) {
                localStorage.removeItem(`audioUnlocked_user_${userId}`);
                localStorage.removeItem(`notificationsEnabled_user_${userId}`);
            }
        }

        // 通知音をグローバルに保持
        let notificationSound;

        window.addEventListener('DOMContentLoaded', () => {
            const userId = document.body.dataset.userId || 'default';
            const toggleBtn = document.getElementById('sound-toggle');
            const icon = document.getElementById('sound-icon');
            const statusText = document.getElementById('sound-status');

            // 通知音を事前に読み込み
            notificationSound = new Audio('/sounds/maou_se_onepoint23.mp3');
            notificationSound.preload = 'auto';
            notificationSound.volume = 1;

            // 状態の初期化
            const audioUnlocked = localStorage.getItem(`audioUnlocked_user_${userId}`) === '1';
            updateUI(audioUnlocked);

            toggleBtn.addEventListener('click', () => {
                const current = localStorage.getItem(`audioUnlocked_user_${userId}`) === '1';
                const next = !current;

                if (next) {
                    const dummy = new Audio('/sounds/maou_se_onepoint23.mp3');
                    dummy.volume = 0;
                    dummy.play().then(() => {
                        localStorage.setItem(`audioUnlocked_user_${userId}`, '1');
                        console.log('🔊 Sound ON');
                        updateUI(true);
                    }).catch(err => {
                        console.warn('❌ Sound permission denied:', err);
                    });
                } else {
                    localStorage.setItem(`audioUnlocked_user_${userId}`, '0');
                    console.log('🔇 Sound OFF');
                    updateUI(false);
                }
            });

            function updateUI(enabled) {
                if (enabled) {
                    icon.classList.replace('fa-bell-slash', 'fa-bell');
                    icon.classList.add('text-yellow-500');
                    icon.classList.remove('text-gray-600');
                    if (statusText) statusText.textContent = 'ON';
                } else {
                    icon.classList.replace('fa-bell', 'fa-bell-slash');
                    icon.classList.remove('text-yellow-500');
                    icon.classList.add('text-gray-600');
                    if (statusText) statusText.textContent = 'OFF';
                }
            }

            // グローバル再生関数
            window.playNotificationSound = function () {
                const enabled = localStorage.getItem(`audioUnlocked_user_${userId}`) === '1';
                if (enabled && notificationSound) {
                    notificationSound.currentTime = 0;
                    notificationSound.play().catch(e => console.warn('🔕 Sound play failed:', e));
                }
            };

            // 🔔 通知音をページロード時に鳴らすかのロジックを変更
            const currentCount = <?php echo e($nonReadCount_total); ?>;
            const countKey = `prevNonReadCount_user_${userId}`;
            const prevCount = parseInt(localStorage.getItem(countKey) || '0');

            if (currentCount > prevCount) {
                playNotificationSound();
            }

            // 通知を開いたときに前回カウントを更新（これを通知表示時にも追加）
            const notificationButton = document.querySelector('[data-notification-button]');
            if (notificationButton) {
                notificationButton.addEventListener('click', () => {
                    if (currentCount > 0) {
                        playNotificationSound();
                    }
                    localStorage.setItem(countKey, currentCount.toString());
                });
            }

            // fallback: 自動で更新（通知表示がない場合用）
            localStorage.setItem(countKey, currentCount.toString());
        });
    </script>
</nav>
<?php /**PATH C:\Users\Tamak\Desktop\gi_12_tripe_may\resources\views/layouts/navigation.blade.php ENDPATH**/ ?>