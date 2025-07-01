<?php $__empty_1 = true; $__currentLoopData = $all_itineraries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $itinerary): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <div class="itinerary-row w-full flex flex-col md:grid md:grid-cols-12 gap-2 py-4 border-b text-sm md:text-base"
                                        data-user="<?php echo e(strtolower($itinerary->user->name)); ?>"
                                        data-group="<?php echo e(strtolower($itinerary->group->name ?? 'no-group')); ?>"
                                        data-title="<?php echo e(strtolower($itinerary->title)); ?>"
                                        data-date="<?php echo e($itinerary->start_date); ?>"
                                        data-created="<?php echo e($itinerary->created_at); ?>">

                                        
                                        <div class="md:col-span-1 flex flex-col items-center md:items-start justify-start ms-0 md:ms-6">
                                            <a href="<?php echo e(route('profile.show', $itinerary->created_by)); ?>">
                                                <?php if($itinerary->user->avatar): ?>
                                                    <img src="<?php echo e($itinerary->user->avatar); ?>" alt="<?php echo e($itinerary->user->name); ?>" class="w-12 h-12 rounded-full object-cover">
                                                <?php else: ?>
                                                    <i class="fa-solid fa-circle-user text-3xl text-gray-400"></i>
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
                                            <a href="<?php echo e(route('itinerary.edit', $itinerary->id)); ?>" title="Edit">
                                                <i class="fa-solid fa-pen text-yellow-300 text-lg"></i>
                                            </a>
                                            <span class="text-red-500">
                                                <?php echo $__env->make('itineraries.modals.delete', ['itinerary' => $itinerary], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                                            </span>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <p class="text-center text-gray-500 py-4">No itineraries found.</p>
                                <?php endif; ?>
<?php /**PATH /Users/kawaimayu/Desktop/gi_12_tripe_may/resources/views/itineraries/partials/scroll.blade.php ENDPATH**/ ?>