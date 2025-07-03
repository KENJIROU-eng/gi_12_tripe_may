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
        <div class="pt-8 flex-1 overflow-y-auto flex flex-col lg:flex-row gap-4 max-w-screen-3xl mx-auto px-4 pb-32"">
            
            <div class="w-full lg:w-1/5"></div>

            
            <div class="bg-white dark:bg-gray-800 shadow-xl rounded-lg p-4 mx-auto w-full max-w-6xl">
                
                <div class="relative flex flex-col md:flex-row items-center justify-center text-center mb-10 gap-2 md:gap-0">
                    
                    <div class="absolute left-0 top-1/2 transform -translate-y-1/2">
                        <a href="<?php echo e(route('itinerary.index')); ?>" class="inline-flex items-center text-sm text-blue-500 hover:underline">
                            <i class="fa-solid fa-arrow-left mr-1"></i> Back
                        </a>
                    </div>

                    
                    <h1 class="text-3xl sm:text-4xl md:text-6xl font-bold text-gray-800 dark:text-gray-100 animate-fadeIn">
                        <i class="fa-solid fa-c"></i>
                        <i class="fa-solid fa-r"></i>
                        <i class="fa-solid fa-e"></i>
                        <i class="fa-solid fa-a"></i>
                        <i class="fa-solid fa-t"></i>
                        <i class="fa-solid fa-e"></i>
                    </h1>
                </div>

                <form id="itineraryForm" action="<?php echo e(route('itinerary.store')); ?>" method="POST" class="space-y-8">
                    <?php echo csrf_field(); ?>
                    
                    <div class="grid grid-cols-1 md:grid-cols-10 gap-6">
                        
                        <div class="md:col-span-4">
                            <?php if (isset($component)) { $__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-label','data' => ['for' => 'title']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['for' => 'title']); ?>
                                <span class="text-red-500 ml-1">*</span>Title
                             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581)): ?>
<?php $attributes = $__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581; ?>
<?php unset($__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581)): ?>
<?php $component = $__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581; ?>
<?php unset($__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581); ?>
<?php endif; ?>
                            <?php if (isset($component)) { $__componentOriginal18c21970322f9e5c938bc954620c12bb = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal18c21970322f9e5c938bc954620c12bb = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.text-input','data' => ['name' => 'title','id' => 'title','placeholder' => 'Please enter a title','required' => true,'maxlength' => '100','class' => 'w-full']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('text-input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'title','id' => 'title','placeholder' => 'Please enter a title','required' => true,'maxlength' => '100','class' => 'w-full']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal18c21970322f9e5c938bc954620c12bb)): ?>
<?php $attributes = $__attributesOriginal18c21970322f9e5c938bc954620c12bb; ?>
<?php unset($__attributesOriginal18c21970322f9e5c938bc954620c12bb); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal18c21970322f9e5c938bc954620c12bb)): ?>
<?php $component = $__componentOriginal18c21970322f9e5c938bc954620c12bb; ?>
<?php unset($__componentOriginal18c21970322f9e5c938bc954620c12bb); ?>
<?php endif; ?>
                            <div id="titleCharCount" class="right-2 top-2 text-sm text-gray-400">
                                0 / 100
                            </div>
                            <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        
                        <div class="md:col-span-5">
                            <div class="grid grid-cols-9 gap-1 text-sm">
                                <div class="col-span-4">
                                    <?php if (isset($component)) { $__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-label','data' => ['for' => 'start_date']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['for' => 'start_date']); ?>
                                        <span class="text-red-500 ml-1">*</span>Start Date
                                     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581)): ?>
<?php $attributes = $__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581; ?>
<?php unset($__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581)): ?>
<?php $component = $__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581; ?>
<?php unset($__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581); ?>
<?php endif; ?>
                                </div>
                                <div class="col-span-1 text-center"></div>
                                <div class="col-span-4">
                                    <?php if (isset($component)) { $__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-label','data' => ['for' => 'end_date']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['for' => 'end_date']); ?>
                                        <span class="text-red-500 ml-1">*</span>End Date
                                     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581)): ?>
<?php $attributes = $__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581; ?>
<?php unset($__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581)): ?>
<?php $component = $__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581; ?>
<?php unset($__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581); ?>
<?php endif; ?>
                                </div>
                            </div>
                            <div class="grid grid-cols-9 items-center gap-1">
                                <div class="col-span-4">
                                    <?php if (isset($component)) { $__componentOriginal18c21970322f9e5c938bc954620c12bb = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal18c21970322f9e5c938bc954620c12bb = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.text-input','data' => ['name' => 'start_date','type' => 'date','id' => 'start_date','class' => 'w-full']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('text-input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'start_date','type' => 'date','id' => 'start_date','class' => 'w-full']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal18c21970322f9e5c938bc954620c12bb)): ?>
<?php $attributes = $__attributesOriginal18c21970322f9e5c938bc954620c12bb; ?>
<?php unset($__attributesOriginal18c21970322f9e5c938bc954620c12bb); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal18c21970322f9e5c938bc954620c12bb)): ?>
<?php $component = $__componentOriginal18c21970322f9e5c938bc954620c12bb; ?>
<?php unset($__componentOriginal18c21970322f9e5c938bc954620c12bb); ?>
<?php endif; ?>
                                    <?php $__errorArgs = ['start_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                                <div class="col-span-1 text-center text-lg">〜</div>
                                <div class="col-span-4">
                                    <?php if (isset($component)) { $__componentOriginal18c21970322f9e5c938bc954620c12bb = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal18c21970322f9e5c938bc954620c12bb = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.text-input','data' => ['name' => 'end_date','type' => 'date','id' => 'end_date','class' => 'w-full']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('text-input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'end_date','type' => 'date','id' => 'end_date','class' => 'w-full']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal18c21970322f9e5c938bc954620c12bb)): ?>
<?php $attributes = $__attributesOriginal18c21970322f9e5c938bc954620c12bb; ?>
<?php unset($__attributesOriginal18c21970322f9e5c938bc954620c12bb); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal18c21970322f9e5c938bc954620c12bb)): ?>
<?php $component = $__componentOriginal18c21970322f9e5c938bc954620c12bb; ?>
<?php unset($__componentOriginal18c21970322f9e5c938bc954620c12bb); ?>
<?php endif; ?>
                                    <?php $__errorArgs = ['end_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>
                        </div>

                        
                        <div class="md:col-span-1 flex items-start justify-end">
                            <button type="submit" class="w-full md:w-auto mt-5 px-5 py-2 bg-green-500 hover:bg-green-600 text-white font-semibold rounded-lg shadow transition-all duration-200 ease-in-out">
                                Create
                            </button>
                        </div>
                    </div>
                    
                    <div class="flex flex-col md:flex-row gap-4">
                        
                        <div class="flex-1 bg-gray-50 dark:bg-gray-700 rounded-lg p-4 shadow-inner relative flex flex-col">
                            
                            <input type="hidden" name="daily_distances" id="daily_distances" />
                            <input type="hidden" name="daily_durations" id="daily_durations" />
                            <input type="hidden" name="total_distance" id="total_distance" />
                            <input type="hidden" name="total_duration" id="total_duration" />
                            <input type="hidden" name="initial_place" id="initial_place" />
                            
                            <div id="dateFieldsContainer" class="flex-1 overflow-y-auto max-h-[500px] space-y-4">
                                
                            </div>
                            
                            <div id="totalSummary" class="mt-4 text-right text-sm text-gray-600 dark:text-gray-300 hidden"></div>
                        </div>
                        
                        <div class="md:w-1/2 w-full bg-white dark:bg-gray-700 rounded-lg p-2 shadow relative">
                            <div id="map" class="h-64 md:h-[550px] w-full rounded-md border"></div>
                        </div>
                    </div>
                </form>
                
                <?php $__env->startPush('scripts'); ?>
                    <script src="<?php echo e(asset('js/itineraries/map.js')); ?>"></script>
                    <script src="<?php echo e(asset('js/itineraries/create.js')); ?>"></script>
                    <script src="https://maps.googleapis.com/maps/api/js?key=<?php echo e(config('services.google_maps.key')); ?>&libraries=places&callback=initMap" async defer></script>
                <?php $__env->stopPush(); ?>
            </div>

            
            <div class="w-full lg:w-1/5">
                <div class="bg-gray-100 dark:bg-gray-700 rounded-lg p-4 space-y-4 text-sm text-gray-800 dark:text-gray-100">
                    <h2 class="text-base font-semibold mb-2 text-gray-900 dark:text-gray-100">
                        How to Use
                    </h2>
                    <div class="flex items-start gap-2">
                        <i class="fa-solid fa-pen text-blue-500 mt-1"></i>
                        <div>
                            <p class="font-semibold">Step 1: Set the Trip Info</p>
                            <p>Enter a title and select the start and end dates of your trip.</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-2">
                        <i class="fa-solid fa-calendar-days text-green-500 mt-1"></i>
                        <div>
                            <p class="font-semibold">Step 2: Generate Daily Sections</p>
                            <p>Once the dates are selected, destination fields for each day will appear.</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-2">
                        <i class="fa-solid fa-location-dot text-red-500 mt-1"></i>
                        <div>
                            <p class="font-semibold">Step 3: Add Your Destinations</p>
                            <p>Input the names of each place you plan to visit and choose a travel mode (e.g., walking, driving).</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-2">
                        <i class="fa-solid fa-up-down-left-right text-yellow-500 mt-1"></i>
                        <div>
                            <p class="font-semibold">Step 4: Reorder Destinations</p>
                            <p>Drag and drop to reorder destinations — the system uses this order to calculate routes.</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-2">
                        <i class="fa-solid fa-route text-purple-500 mt-1"></i>
                        <div>
                            <p class="font-semibold">Step 5: Review the Route</p>
                            <p>Distances and durations are automatically calculated between stops.</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-2">
                        <i class="fa-solid fa-circle-check text-teal-500 mt-1"></i>
                        <div>
                            <p class="font-semibold">Step 6: Create Itinerary</p>
                            <p>Click the “Create” button to save your trip and proceed to the next step.</p>
                        </div>
                    </div>
                </div>
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

<?php /**PATH C:\Users\USER\Desktop\gi_12_tripe_may\resources\views/itineraries/create.blade.php ENDPATH**/ ?>