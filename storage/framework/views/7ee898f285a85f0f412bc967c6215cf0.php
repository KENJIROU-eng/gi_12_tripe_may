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
        <div class="pt-8 flex-1 overflow-y-auto flex flex-col lg:flex-row gap-4 max-w-screen-3xl mx-auto px-4 pb-32">
            
            <div class="block">
                
                <a href="<?php echo e($previous ? route('itinerary.show', $previous->id) : '#'); ?>"
                    class="fixed top-1/2 left-2 sm:left-4 transform -translate-y-1/2 bg-white border rounded-full shadow p-2 text-blue-500 hover:bg-blue-100 z-50"
                    title="Previous">
                    <i class="fa-solid fa-chevron-left text-xl"></i>
                </a>

                
                <a href="<?php echo e($next ? route('itinerary.show', $next->id) : '#'); ?>"
                    class="fixed top-1/2 right-2 sm:right-4 transform -translate-y-1/2 bg-white border rounded-full shadow p-2 text-blue-500 hover:bg-blue-100 z-50"
                    title="Next">
                    <i class="fa-solid fa-chevron-right text-xl"></i>
                </a>
            </div>

            
            <div class="w-full lg:w-1/5 max-w-sm border rounded-lg shadow-md bg-white dark:bg-gray-800 p-4 h-fit order-3 lg:order-none">
                <div class="sticky top-24 bg-white dark:bg-gray-800 border rounded p-4 shadow text-sm">
                    <h3 class="font-semibold mb-2 text-gray-700 dark:text-gray-200"><i class="fa-solid fa-file-pen text-blue-500"></i> Memo</h3>

                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-2">Jot down anything you like here!</p>

                    <textarea id="itineraryMemo" data-save-url="<?php echo e(route('itinerary.memo.save', $itinerary->id)); ?>" data-csrf="<?php echo e(csrf_token()); ?>" class="w-full h-32 p-2 border rounded-lg bg-gray-50 dark:bg-gray-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-400 transition" ><?php echo e(old('content', optional($itinerary->memo)->content)); ?></textarea>

                    <div class="flex justify-end mt-2">
                        <button id="saveMemoBtn" class="bg-blue-500 hover:bg-blue-600 text-white text-sm font-semibold py-1.5 px-4 rounded flex items-center justify-center gap-2" >
                            <svg id="spinner" class="hidden animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                            </svg>
                            <span id="saveMemoText">Save</span>
                        </button>
                    </div>

                    <p id="memoSavedMsg" class="hidden text-green-500 text-xs mt-2 transition-opacity duration-300">Memo saved!</p>
                </div>
            </div>

            
            <div class="w-full lg:w-3/5 flex flex-col gap-4 order-2 lg:order-none">
                <div class="bg-white dark:bg-gray-800 shadow-xl rounded-lg p-4">
                    
                    <div class="relative flex items-center justify-between flex-wrap gap-2 mb-6">
                        
                        <div class="flex flex-col sm:flex-row items-start gap-2 z-10">
                            <a href="<?php echo e(route('itinerary.index')); ?>" class="inline-flex items-center text-sm text-blue-500 hover:underline">
                                <i class="fa-solid fa-arrow-left mr-1"></i> Back
                            </a>
                            
                            <?php if(Auth::id() === $itinerary->created_by): ?>
                                <form action="<?php echo e(route('itinerary.toggleFinish', $itinerary->id)); ?>" method="POST" class="md:ml-10">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit"
                                        class="relative inline-flex items-center h-8 w-28 rounded-full transition-colors duration-300 ease-in-out focus:outline-none
                                        <?php echo e($itinerary->finish_at ? 'bg-green-500' : 'bg-blue-500'); ?>">
                                        <span class="sr-only">Toggle Trip Status</span>

                                        
                                        <span class="absolute left-2 text-[11px] font-bold text-white z-10 transition-opacity duration-300
                                            <?php echo e($itinerary->finish_at ? 'opacity-100' : 'opacity-0'); ?>">
                                            Finish
                                        </span>

                                        
                                        <span class="absolute right-2 text-[11px] font-bold text-white z-10 transition-opacity duration-300
                                            <?php echo e($itinerary->finish_at ? 'opacity-0' : 'opacity-100'); ?>">
                                            In progress
                                        </span>

                                        
                                        <span x-data="{ isWalking: true }" x-init="setInterval(() => isWalking = !isWalking, 500)" class="absolute inline-block h-7 w-10 rounded-full bg-white shadow transform transition duration-300 ease-in-out <?php echo e($itinerary->finish_at ? 'translate-x-[68px]' : 'translate-x-1'); ?> flex items-center justify-center text-blue-500">
                                            <template x-if="!<?php echo \Illuminate\Support\Js::from($itinerary->finish_at)->toHtml() ?>">
                                                <i x-show="isWalking" class="fa-solid fa-person-walking text-sm"></i>
                                            </template>
                                            <template x-if="!<?php echo \Illuminate\Support\Js::from($itinerary->finish_at)->toHtml() ?>">
                                                <i x-show="!isWalking" class="fa-solid fa-person-running text-sm"></i>
                                            </template>

                                            
                                            <?php if($itinerary->finish_at): ?>
                                                <i class="fa-solid fa-bed text-sm text-green-500"></i>
                                            <?php endif; ?>
                                        </span>


                                    </button>
                                </form>
                            <?php endif; ?>

                        </div>

                        <?php $__currentLoopData = $itinerary->bills; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bill): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php $__currentLoopData = $bill->billUser; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <p><?php echo e($user->id); ?></p>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                        
                        <div class="order-0 w-full text-center md:absolute md:left-1/2 md:transform md:-translate-x-1/2">
                            <h1 class="text-3xl sm:text-4xl md:text-5xl font-bold text-gray-800 dark:text-gray-100 animate-fadeIn">
                                <i class="fa-solid fa-d"></i>
                                <i class="fa-solid fa-e"></i>
                                <i class="fa-solid fa-t"></i>
                                <i class="fa-solid fa-a"></i>
                                <i class="fa-solid fa-i"></i>
                                <i class="fa-solid fa-l"></i>
                                <i class="fa-solid fa-s"></i>
                            </h1>
                        </div>

                        
                        <?php if($itinerary->group_id): ?>
                            <div class="order-2 md:order-none self-end md:self-center z-10">
                                <div x-data="{ open: false }" class="flex flex-col items-end space-y-1">
                                    
                                    <div class="text-blue-500 text-sm md:text-base">
                                        <a href="<?php echo e(route('message.show', $itinerary->group_id)); ?>">
                                            <?php echo e(Str::limit($itinerary->group->name, 20)); ?>

                                            <i class="fa-regular fa-comment-dots"></i>
                                        </a>
                                    </div>
                                    <div class="relative">
                                        
                                        <button @click="open = !open" class="flex -space-x-3">
                                            <?php $__currentLoopData = $itinerary->group->users->take(3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <img src="<?php echo e($user->avatar ?? asset('images/user.png')); ?>"
                                                    class="w-8 h-8 rounded-full border-2 border-white hover:z-10"
                                                    alt="<?php echo e($user->name); ?>">
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            <?php if($itinerary->group->users->count() > 3): ?>
                                                <div class="w-8 h-8 rounded-full bg-gray-300 text-xs text-white flex items-center justify-center border-2 border-white">
                                                    +<?php echo e($itinerary->group->users->count() - 3); ?>

                                                </div>
                                            <?php endif; ?>
                                        </button>
                                        
                                        <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-64 bg-white border rounded-lg shadow-lg z-50">
                                            <div class="p-4">
                                                <h2 class="text-sm font-semibold text-gray-600 mb-2">Group Members</h2>
                                                <ul class="space-y-2 max-h-60 overflow-y-auto">
                                                    <?php $__currentLoopData = $itinerary->group->users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <li class="flex items-center space-x-3">
                                                            <a href="<?php echo e(route('profile.show', $user->id)); ?>">
                                                                <img src="<?php echo e($user->avatar ?? asset('images/user.png')); ?>" class="w-8 h-8 rounded-full object-cover" alt="<?php echo e($user->name); ?>">
                                                            </a>
                                                            <a href="<?php echo e(route('profile.show', $user->id)); ?>">
                                                                <p class="text-sm"><?php echo e($user->name); ?></p>
                                                            </a>
                                                        </li>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>

                    
                    <div class="grid grid-cols-1 lg:grid-cols-5 gap-4">
                        
                        <div class="lg:col-span-2 flex flex-col border rounded-lg shadow-sm overflow-hidden">
                            <div class="bg-white dark:bg-gray-900 p-4 h-auto lg:h-[678px] flex flex-col">
                                
                                <div class="items-center pb-2">
                                    <div class="col-span-4">
                                        <p class="text-gray-500">Title</p>
                                        <p class="font-bold break-words max-w-full overflow-hidden"><?php echo e($itinerary->title); ?></p>
                                        <p class="text-gray-500">Date</p>
                                        <p class="font-bold">
                                            <?php echo e(\Carbon\Carbon::parse($itinerary->start_date)->format('M. d, Y')); ?>

                                            ～ <?php echo e(\Carbon\Carbon::parse($itinerary->end_date)->format('M. d, Y')); ?>

                                        </p>
                                    </div>
                                    <div class="flex flex-col sm:flex-row sm:justify-end sm:items-center gap-2 mt-2 sm:mt-0">
                                        
                                        <a id="shareMapBtn" href="#" target="_blank" onclick="event.preventDefault(); openShareMapLink();" class="text-blue-500 px-1 py-1 hidden sm:inline-flex items-center  hover:text-blue-700" title="View Google Map">
                                            <i class="fa-solid fa-map-location-dot mr-1"></i> View Google Map
                                        </a>

                                        
                                        <?php if(!$itinerary->finish_at): ?>
                                            <a href="<?php echo e(route('itinerary.edit', $itinerary->id)); ?>" class="text-yellow-500 px-1 py-1 inline-flex items-center hover:text-yellow-700" title="Edit">
                                                <i class="fa-solid fa-pen mr-1"></i> Edit
                                            </a>

                                            
                                            <span class="flex items-center text-red-500">
                                                <?php echo $__env->make('itineraries.modals.delete', ['itinerary' => $itinerary, 'showText' => true], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                
                                <div class="overflow-y-auto flex-1 my-4 sm:max-h-[550px] scrollable">
                                    <?php
                                        $grandTotalDistance = 0;
                                        $grandTotalDurationSeconds = 0;
                                        $isFirstOverall = true;
                                    ?>
                                    <?php $__currentLoopData = $itinerary->dateItineraries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dateItinerary): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="flex items-center justify-between mt-2 mb-2 border-b pb-1">
                                            <h3 class="font-semibold text-blue-600 text-lg">
                                                <?php echo e(\Carbon\Carbon::parse($dateItinerary->date)->format('M. d, Y')); ?>

                                            </h3>
                                            <div id="weatherContainer-<?php echo e(\Carbon\Carbon::parse($dateItinerary->date)->format('Ymd')); ?>" class="flex items-center gap-2 text-sm text-gray-700 min-h-[24px]">
                                            </div>
                                        </div>
                                        <ul>
                                            <?php
                                                $dailyTotalDistance = 0;
                                                $dailyDurationSeconds = 0;
                                            ?>

                                        <?php $__empty_1 = true; $__currentLoopData = $dateItinerary->mapItineraries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $map): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                            <li class="flex justify-between items-center py-1 border-b border-dashed border-gray-200">
                                                <span class="ml-4 flex items-center gap-2">
                                                    <?php if($isFirstOverall): ?>
                                                        <i class="fa-solid fa-flag-checkered text-blue-600"></i>
                                                        <span class="font-semibold text-blue-600"><?php echo e($map->place_name ?? $map->destination); ?></span>
                                                        <?php $isFirstOverall = false; ?>
                                                    <?php else: ?>
                                                        <?php
                                                            $mode = strtoupper($map->travel_mode ?? 'DRIVING');
                                                            $modeIconMap = [
                                                                'DRIVING' => ['icon' => 'fa-car-side', 'color' => 'text-blue-500'],
                                                                'MOTORCYCLE' => ['icon' => 'fa-motorcycle', 'color' => 'text-green-500'],
                                                                'WALKING' => ['icon' => 'fa-person-walking', 'color' => 'text-red-500'],
                                                                'TRANSIT' => ['icon' => 'fa-van-shuttle', 'color' => 'text-yellow-500'],
                                                            ];
                                                            $modeData = $modeIconMap[$mode] ?? ['icon' => 'fa-location-dot', 'color' => 'text-gray-400'];
                                                        ?>
                                                        <i class="fa-solid <?php echo e($modeData['icon']); ?> <?php echo e($modeData['color']); ?>"></i>
                                                        <?php echo e($map->place_name ?? $map->destination); ?>

                                                    <?php endif; ?>
                                                </span>

                                                <?php if(!is_null($map->distance_km) && !is_null($map->duration_text)): ?>
                                                    <span class="text-gray-500 text-sm whitespace-nowrap">
                                                        <?php echo e(number_format($map->distance_km, 1)); ?> km / <?php echo e($map->duration_text); ?>

                                                    </span>

                                                    <?php
                                                        $dailyTotalDistance += $map->distance_km;
                                                        if (preg_match_all('/(\d+)\s*(hour|hours|minute|min|mins)/i', $map->duration_text, $matches, PREG_SET_ORDER)) {
                                                            foreach ($matches as $match) {
                                                                switch ($match[2]) {
                                                                    case 'hour':
                                                                    case 'hours':
                                                                        $dailyDurationSeconds += intval($match[1]) * 3600;
                                                                        break;
                                                                    case 'minute':
                                                                    case 'min':
                                                                    case 'mins':
                                                                        $dailyDurationSeconds += intval($match[1]) * 60;
                                                                        break;
                                                                }
                                                            }
                                                        }
                                                    ?>
                                                <?php endif; ?>
                                            </li>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                            <li class="text-sm text-gray-500 italic px-2 py-1">
                                                There is no destination on this day.
                                            </li>
                                        <?php endif; ?>
                                            <?php
                                                $grandTotalDistance += $dailyTotalDistance;
                                                $grandTotalDurationSeconds += $dailyDurationSeconds;
                                            ?>
                                        </ul>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                                <div class="">
                                    <p id="totalSummary" class="text-blue-800 text-md text-end flex justify-end items-center gap-2 hidden">
                                        <i class="fa-solid fa-route text-blue-600"></i>
                                        Total Distance: <?php echo e(number_format($grandTotalDistance, 1)); ?> km /
                                        Total Duration: <?php echo e(gmdate('G\h i\m', $grandTotalDurationSeconds)); ?>

                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="lg:col-span-3 flex flex-col gap-4 lg:h-[690px]">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                
                                <div class="border rounded-lg shadow-sm bg-white dark:bg-gray-900 p-4 h-auto md:h-[400px]" id="goDutch-container">
                                    <?php echo $__env->make('goDutch.index', ['total_Pay' => $total_Pay, 'total_getPay' => $total_getPay], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                                </div>
                                
                                <div class="border rounded-lg shadow-sm bg-white dark:bg-gray-900 p-4 h-auto md:h-[400px]" id="belongings-container">
                                    <?php echo $__env->make('belongings.index', ['all_belongings' => $all_belongings], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                                </div>
                            </div>
                            <div id="map" class="w-full h-[300px] sm:h-[400px] border rounded-lg shadow-sm bg-white dark:bg-gray-900">
                                map
                            </div>
                        </div>
                    </div>
                    
                    <?php $__env->startPush('scripts'); ?>
                        <script>
                            window.existingData = <?php echo json_encode($itineraryData['destinations'] ?? [], 15, 512) ?>;
                            console.log('existingData:', existingData);
                        </script>
                        <script>
                            const weatherApiKey = <?php echo json_encode(config('services.weatherapi.key'), 15, 512) ?>;
                        </script>
                        <script src="<?php echo e(asset('js/itineraries/show.js')); ?>"></script>
                        <script src="https://maps.googleapis.com/maps/api/js?key=<?php echo e(config('services.google_maps.key')); ?>&libraries=places&callback=initShowMap" async defer></script>
                    <?php $__env->stopPush(); ?>
                </div>
            </div>

            
            <div class="w-full lg:w-1/5 max-w-sm border rounded-lg shadow-md bg-white dark:bg-gray-800 p-4 h-fit order-3 lg:order-none">
                <h2 class="text-lg font-semibold text-blue-600 mb-2">Route Steps</h2>
                <ul id="route-steps" class="space-y-2 text-sm text-gray-700 dark:text-gray-200 overflow-y-auto max-h-[736px]">
                    
                </ul>
            </div>
        </div>
    </div>

    
    <button id="scrollToTopBtn" class="fixed bottom-12 left-1/2 transform -translate-x-1/2 z-50 bg-green-500 text-white rounded-full p-1 shadow-lg transition-opacity duration-300 opacity-0 pointer-events-none md:hidden" aria-label="Scroll to top">
        <i class="fa-solid fa-arrow-up"></i> Go to Top
    </button>

    
    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fadeIn {
            animation: fadeIn 0.6s ease-out forwards;
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
<?php /**PATH C:\Users\Tamak\Desktop\gi_12_tripe_may\resources\views/itineraries/show.blade.php ENDPATH**/ ?>