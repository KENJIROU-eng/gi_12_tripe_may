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
            <div class="bg-white dark:bg-gray-800 shadow-xl rounded-lg p-4 mx-auto w-full max-w-6xl">
            
            <div class="flex items-center justify-between mb-4">
                
                <a href="<?php echo e(route('itinerary.show', $itineraryId)); ?>" class="inline-flex items-center text-sm text-blue-500 hover:underline">
                    <i class="fa-solid fa-arrow-left mr-1"></i> Back
                </a>

                
                <h1 class="text-4xl md:text-6xl font-bold text-gray-800 dark:text-gray-100 animate-fadeIn text-center flex-1">
                    <i class="fa-solid fa-b"></i>
                    <i class="fa-solid fa-e"></i>
                    <i class="fa-solid fa-l"></i>
                    <i class="fa-solid fa-o"></i>
                    <i class="fa-solid fa-n"></i>
                    <i class="fa-solid fa-g"></i>
                    <i class="fa-solid fa-i"></i>
                    <i class="fa-solid fa-n"></i>
                    <i class="fa-solid fa-g"></i>
                </h1>

                
                <button id="toggleCheckedBtn" class="me-2 text-gray-700 dark:text-gray-300 whitespace-nowrap text-xl">
                    <i class="fas fa-eye text-blue-500"></i>
                </button>
            </div>


                
                <form id="belongingForm" action="<?php echo e(route('belonging.store', $itineraryId)); ?>" method="POST" class="space-y-1" name="belongingForm">
                    <?php echo csrf_field(); ?>

                    <div class="flex flex-col md:flex-row gap-2">
                        <div class="md:w-1/2">
                            <?php if (isset($component)) { $__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-label','data' => ['for' => 'item']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['for' => 'item']); ?>
                                <span class="text-red-500 ml-1">*</span>Item Name
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.text-input','data' => ['id' => 'item','name' => 'item','required' => true,'maxlength' => '50','class' => 'w-full','placeholder' => 'e.g. Passport, Wallet, Charger']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('text-input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'item','name' => 'item','required' => true,'maxlength' => '50','class' => 'w-full','placeholder' => 'e.g. Passport, Wallet, Charger']); ?>
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
                            <div id="itemCharCount" class="right-2 top-2 text-sm text-gray-400">
                                0 / 50
                            </div>
                            <?php $__errorArgs = ['item'];
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

                        <div class="md:w-1/2">
                            <?php if (isset($component)) { $__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-label','data' => ['for' => 'description','class' => 'text-gray-700','value' => 'Description']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['for' => 'description','class' => 'text-gray-700','value' => 'Description']); ?>
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
                            <textarea id="description" name="description" rows="1" maxlength="500" class="w-full rounded-md border-gray-300" placeholder="Details about the item..."></textarea>
                            <div id="descriptionCharCount" class="right-2 top-2 text-sm text-gray-400">
                                0 / 500
                            </div>
                            <?php $__errorArgs = ['description'];
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

                    <div>
                        
                        <div class="flex justify-between items-center mb-1">
                            <label class="block font-medium text-sm text-gray-700 dark:text-gray-300">
                                <i class="fa-solid fa-users"></i> <span class="text-red-500 ml-1">*</span>Assign to Members
                            </label>
                            <button type="button" id="toggleSelectAllMembers" class="text-sm text-blue-600 hover:underline">
                                Select All
                            </button>
                        </div>

                        <div class="max-h-40 overflow-y-auto border rounded-md p-2 bg-white dark:bg-gray-700">
                            <div class="grid grid-cols-2 gap-1">
                                <?php $__currentLoopData = $members; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $member): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <label class="flex items-center space-x-2">
                                        <input type="checkbox" name="members[]" value="<?php echo e($member->id); ?>" class="member-checkbox rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                        <span class="text-sm text-gray-800 dark:text-gray-100"><?php echo e($member->name); ?></span>
                                    </label>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                        <?php $__errorArgs = ['members'];
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

                    
                    <?php if($totalCount > 0): ?>
                        <div class="pt-2 md:flex md:items-center md:justify-between">
                            
                            <div class="w-full px-4 mb-4">
                                <div class="flex justify-between text-sm mb-1 text-gray-600 dark:text-gray-300">
                                    <span class="progress-count-text"><?php echo e($checkedCount); ?> / <?php echo e($totalCount); ?> items</span>
                                    <span class="progress-percent-text"><?php echo e($progressPercent); ?>%</span>
                                </div>
                                <div class="w-full bg-gray-200 dark:bg-gray-600 rounded-full h-2.5 overflow-hidden">
                                    <div class="progress-bar-fill bg-blue-500 h-full transition-all duration-300" style="width: <?php echo e($progressPercent); ?>%;"></div>
                                </div>
                            </div>

                            
                            <div class="text-right md:w-auto">
                                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md shadow">
                                    Add
                                </button>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="pt-2 text-right">
                            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md shadow">
                                Add
                            </button>
                        </div>
                    <?php endif; ?>

                </form>

                
                <div class="mt-8 max-h-[470px] overflow-y-auto pr-1 space-y-6" id="belongingListScrollArea">
                    <?php $__empty_1 = true; $__currentLoopData = $all_belongings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $belonging): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="relative border p-2 rounded-md bg-white dark:bg-gray-700 shadow-sm belonging-item" data-belonging-id="<?php echo e($belonging->id); ?>" data-belonging-name="<?php echo e($belonging->name); ?>" data-belonging-description="<?php echo e($belonging->description); ?>" data-belonging-users='<?php echo json_encode($belonging->users->pluck("id"), 15, 512) ?>' data-checked="<?php echo e($belonging->users->every(fn($u) => $u->pivot->is_checked) ? '1' : '0'); ?>">
                            <div class="absolute top-2 right-2 flex space-x-2">
                                <button class="edit-btn text-yellow-500 hover:text-yellow-700" title="Edit" data-belonging-id="<?php echo e($belonging->id); ?>">
                                    <i class="fas fa-pen"></i>
                                </button>
                                <button class="delete-btn text-red-500 hover:text-red-700" title="Delete" data-belonging-id="<?php echo e($belonging->id); ?>">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>

                            <div class="font-bold text-lg text-gray-800 dark:text-gray-100 belonging-name">
                                <?php echo e($belonging->name); ?>

                            </div>
                            <div class="text-sm text-gray-500 mb-2 break-words whitespace-pre-line">
                                <?php echo e($belonging->description); ?>

                            </div>

                            
                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-1 mt-2 max-h-20 overflow-y-auto pr-1">
                                <?php $__currentLoopData = $belonging->users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php $isOwn = $user->id === Auth::id(); ?>
                                    <label class="flex items-center gap-2 px-3 py-1 rounded-lg border
                                        <?php echo e($isOwn ? 'bg-indigo-50 border-indigo-400' : 'bg-gray-50 dark:bg-gray-700 border-gray-300'); ?>">
                                        <input type="checkbox" class="member-checkbox h-4 w-4 <?php echo e(!$isOwn ? 'opacity-50 cursor-not-allowed' : 'cursor-pointer'); ?>" data-belonging-id="<?php echo e($belonging->id); ?>" data-user-id="<?php echo e($user->id); ?>" <?php echo e($user->pivot->is_checked ? 'checked' : ''); ?> <?php echo e(!$isOwn ? 'disabled' : ''); ?>>
                                        <span class="text-sm <?php echo e($isOwn ? 'text-indigo-600 font-bold' : 'text-gray-800 dark:text-gray-100'); ?>">
                                            <?php echo e($user->name); ?>

                                            <?php if($isOwn): ?>
                                                <span class="text-xs bg-indigo-100 text-indigo-800 px-1 rounded ml-1"><i class="fa-regular fa-user"></i></span>
                                            <?php endif; ?>
                                        </span>
                                    </label>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>

                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <p class="text-gray-500 text-center">No belongings yet.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    
    <button id="scrollToTopBtn"
        class="fixed bottom-12 left-1/2 transform -translate-x-1/2 z-50 bg-green-400 text-white rounded-full p-1 shadow-lg transition-opacity duration-300 opacity-0 pointer-events-none md:hidden"
        aria-label="Scroll to top">
        <i class="fa-solid fa-arrow-up"></i> Go to Top
    </button>

    
    <div id="editModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden">
        <div class="bg-white dark:bg-gray-800 p-6 rounded-md w-full max-w-lg">
            <h2 class="text-2xl font-semibold text-gray-800 dark:text-white mb-4">
                <i class="fa-solid fa-e"></i>
                <i class="fa-solid fa-d"></i>
                <i class="fa-solid fa-i"></i>
                <i class="fa-solid fa-t"></i>
                <span class="mx-2"></span>
                <i class="fa-solid fa-b"></i>
                <i class="fa-solid fa-e"></i>
                <i class="fa-solid fa-l"></i>
                <i class="fa-solid fa-o"></i>
                <i class="fa-solid fa-n"></i>
                <i class="fa-solid fa-g"></i>
                <i class="fa-solid fa-i"></i>
                <i class="fa-solid fa-n"></i>
                <i class="fa-solid fa-g"></i>
            </h2>
            <form id="editForm" method="POST" class="space-y-4">
                <?php echo csrf_field(); ?>
                <input type="hidden" id="editBelongingId">
                <div>
                    <label class="block mb-1 font-medium text-sm"><span class="text-red-500 ml-1">*</span>Item Name</label>
                    <input type="text" id="editName" class="w-full rounded-md border-gray-300" required maxlength="50">
                    <div id="editNameCharCount" class="right-2 top-2 text-sm text-gray-400">
                        0 / 50
                    </div>
                </div>
                <div>
                    <label class="block mb-1 font-medium text-sm">Description</label>
                    <textarea id="editDescription" class="w-full rounded-md border-gray-300" rows="2" maxlength="500"></textarea>
                    <div id="editDescriptionCharCount" class="right-2 top-2 text-sm text-gray-400">
                        0 / 500
                    </div>
                </div>
                <div>
                    <div class="flex justify-between items-center mb-1">
                        <label class="block mb-1 font-medium text-sm"><i class="fa-solid fa-users"></i> <span class="text-red-500 ml-1">*</span>Assign to Members</label>
                        <button type="button" id="editToggleSelectAll" class="text-sm text-blue-600 hover:underline">
                            Select All
                        </button>
                    </div>
                    <div class="max-h-40 overflow-y-auto border rounded-md p-3 bg-white dark:bg-gray-700">
                        <div class="grid grid-cols-2 gap-2">
                            <?php $__currentLoopData = $members; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $member): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <label class="flex items-center space-x-2">
                                    <input type="checkbox" class="edit-member-checkbox" value="<?php echo e($member->id); ?>">
                                    <span><?php echo e($member->name); ?></span>
                                </label>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                </div>
                <div class="flex justify-end space-x-2 pt-2">
                    <button type="button" id="cancelEdit" class="px-4 py-2 bg-gray-300 rounded-md">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md">Update</button>
                </div>
            </form>
        </div>
    </div>

    
    <div id="duplicateModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden">
        <div class="bg-white dark:bg-gray-800 p-6 rounded-md w-full max-w-md">
            <h2 class="text-lg font-bold text-gray-800 dark:text-gray-100 mb-4">
                This item name already exists.
            </h2>
            <p class="mb-4 text-gray-600 dark:text-gray-300">
                Do you want to add members to the existing item or create a new item with the same name?
            </p>
            <div class="flex justify-end space-x-2">
                <button id="addToExistingBtn" class="bg-green-600 text-white px-4 py-2 rounded-md">Add to Existing</button>
                <button id="createNewBtn" class="bg-indigo-600 text-white px-4 py-2 rounded-md">Create New</button>
                <button id="cancelDuplicate" class="bg-gray-300 px-4 py-2 rounded-md">Cancel</button>
            </div>
        </div>
    </div>

    <?php $__env->startPush('scripts'); ?>
        <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
        <script src="<?php echo e(asset('js/itineraries/belonging.js')); ?>?v=<?php echo e(now()->timestamp); ?>"></script>
    <?php $__env->stopPush(); ?>

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
<?php /**PATH C:\Users\Tamak\Desktop\gi_12_tripe_may\resources\views/belongings/list.blade.php ENDPATH**/ ?>