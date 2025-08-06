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
    <div class="py-6 mt-2 min-h-screen">
        <div class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-4 sm:p-6 text-black dark:text-gray-100">
                    
                    <div class="relative bg-gradient-to-r from-green-600 to-green-300 text-white rounded-2xl shadow-lg p-6 sm:p-8 flex flex-col sm:flex-row items-center justify-between overflow-hidden my-8 mx-4 sm:mx-auto max-w-6xl">
                        
                        
                        <div class="sm:w-2/3 text-center sm:text-left">
                            <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold leading-tight">
                                Welcome to Tripe@s
                            </h1>
                            <p class="mt-2 text-white text-sm sm:text-base">Check your travel schedules & travel posts</p>
                        </div>

                        
                        <div class="mt-6 sm:mt-0 sm:w-1/3 flex justify-center sm:justify-end">
                            <img src="<?php echo e(asset('images/tripeas_logo_20250617.png')); ?>" alt="Tripe@s Logo"
                                class="w-48 sm:w-48 md:w-48 rounded-full object-cover">
                        </div>
                    </div>
                    
                    <?php if(Auth::User()->notification == NULL): ?>
                        <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('notification-toggle');

$__html = app('livewire')->mount($__name, $__params, 'lw-2397315956-0', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
                    <?php endif; ?>

                    
                    
                    
                    <div class="mt-8">
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                            
                            <div class="lg:col-span-2">
                                
                                <div class="relative rounded-xl px-6 py-8 max-w-sm mx-auto mt-10">
                                    <div class="text-center ">
                                        <a href="<?php echo e(route('itinerary.share')); ?>" class="bg-gradient-to-r from-blue-500 via-green-500 to-red-500 py-2 px-4 text-lg text-shadow-lg rounded-md font-bold text-white inline-block w-full max-w-md hover:from-green-500 hover:via-blue-500 hover:to-yellow-500">
                                            Create Trip Itinerary
                                        </a>
                                    </div>
                                    <div class="absolute -top-2 -right-4 rotate-12">
                                        <div class=" p-3">
                                            <img src="<?php echo e(asset('images/de-tuno-3d-hyu.png')); ?>" alt="" class="w-20 h-20 object-cover opacity-100">
                                        </div>
                                    </div>
                                </div>
                                <h2 class="text-center text-lg font-semibold mb-4">Trip Schedule Calender</h2>
                                <div class="bg-white shadow-lg rounded-lg min-h-[500px] flex flex-col justify-between">
                                    <div class="px-6 py-4 flex justify-between items-center">
                                        <button id="prev-month" class="text-gray-500 hover:text-gray-700">&lt; Previous</button>
                                        <h2 id="month-year" class="text-xl font-semibold"></h2>
                                        <button id="next-month" class="text-gray-500 hover:text-gray-700">Next &gt;</button>
                                    </div>
                                    <div class="grid grid-cols-7 border-b border-gray-200 text-sm">
                                        <template x-for="day in ['Sun.','Mon.','Tue.','Wed.','Thu.','Fri.','Sat.']">
                                            <div><p class="text-center py-2 font-semibold text-gray-600" x-text="day"></p></div>
                                        </template>
                                    </div>
                                    <div id="calendar-body" class="grid grid-cols-7 min-h-[300px] sm:min-h-[400px] lg:min-h-[615px]"></div>
                                </div>
                            </div>
                            
                            <div class="bg-white rounded-lg shadow-lg p-4 h-auto space-y-8">
                                
                                <div x-data="{ expandedMembers: {} }" class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                    <h2 class="text-center text-lg font-semibold mb-4">Upcoming Trips (Within 1 month)</h2>
                                    <div class="space-y-3 max-h-[320px] overflow-y-auto flex flex-col gap-4">
                                        <?php $__currentLoopData = $itineraries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $itinerary): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php
                                                $diffStart = $itinerary->start_date->diffInDays($today, false);
                                                $diffEnd = $itinerary->end_date->diffInDays($today, false);
                                                $members = $itinerary->group->users;
                                                $authUser = Auth::user();

                                                // Laravelで残りメンバーを整形
                                                $remainingMembers = $members->slice(3)->map(function ($user) use ($authUser) {
                                                    $link = null;

                                                    if ($user->private_group && $user->private_group->isNotEmpty() &&
                                                        $user->private_group->contains('name', $authUser->name)) {
                                                        $link = route('message.show', $user->private_group->first()->id);
                                                    } elseif ($authUser->private_group && $authUser->private_group->isNotEmpty() &&
                                                        $authUser->private_group->contains('name', $user->name)) {
                                                        $link = route('message.show', $authUser->private_group->first()->id);
                                                    }

                                                    return [
                                                        'name' => $user->name,
                                                        'avatar' => $user->avatar ?? asset('images/ben-sweet-2LowviVHZ-E-unsplash.jpg'),
                                                        'link' => $link,
                                                    ];
                                                })->values();

                                                $itineraryId = $itinerary->id;
                                                $remainingJson = $remainingMembers->toJson();
                                            ?>

                                            <?php if(($diffStart <= 0 && $diffStart >= -30) || ($diffEnd <= 0 && $diffStart >= 0)): ?>
                                                <div class="w-full bg-yellow-200 rounded-md p-4 hover:bg-yellow-300 transition shadow-lg flex flex-col justify-between">
                                                    
                                                    <div class="text-sm text-gray-700 mb-2 text-center">
                                                        <span><?php echo e($itinerary->start_date->format('Y-m-d')); ?></span> ~
                                                        <span><?php echo e($itinerary->end_date->format('Y-m-d')); ?></span>
                                                    </div>

                                                    
                                                    <a href="<?php echo e(route('itinerary.show', $itineraryId)); ?>" class="font-semibold text-md text-blue-900 hover:text-blue-600 truncate text-center">
                                                        <?php echo e($itinerary->title); ?>

                                                    </a>

                                                    <div class="text-sm text-black text-center">
                                                        <span>Group: <?php echo e($itinerary->group->name); ?></span>
                                                    </div>

                                                    
                                                    <div class="flex flex-wrap justify-center items-center gap-2 mt-4">
                                                        
                                                        <?php $__currentLoopData = $members->take(3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <?php
                                                                $link = null;

                                                                if ($user->private_group && $user->private_group->isNotEmpty() &&
                                                                    $user->private_group->contains('name', $authUser->name)) {
                                                                    $link = route('message.show', $user->private_group->first()->id);
                                                                } elseif ($authUser->private_group && $authUser->private_group->isNotEmpty() &&
                                                                    $authUser->private_group->contains('name', $user->name)) {
                                                                    $link = route('message.show', $authUser->private_group->first()->id);
                                                                }
                                                            ?>

                                                            <div class="text-center">
                                                                <?php if($link): ?>
                                                                    <a href="<?php echo e($link); ?>" class="block w-8 h-8 rounded-full overflow-hidden bg-gray-200 mx-auto">
                                                                        <img src="<?php echo e($user->avatar ?? asset('images/ben-sweet-2LowviVHZ-E-unsplash.jpg')); ?>"
                                                                            alt="<?php echo e($user->name); ?>"
                                                                            class="w-full h-full object-cover">
                                                                    </a>
                                                                <?php else: ?>
                                                                    <div class="block w-8 h-8 rounded-full overflow-hidden bg-gray-200 mx-auto">
                                                                        <img src="<?php echo e($user->avatar ?? asset('images/ben-sweet-2LowviVHZ-E-unsplash.jpg')); ?>"
                                                                            alt="<?php echo e($user->name); ?>"
                                                                            class="w-full h-full object-cover">
                                                                    </div>
                                                                <?php endif; ?>
                                                                <p class="text-xs mt-1 truncate text-black max-w-[72px]"><?php echo e($user->name); ?></p>
                                                            </div>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                                        
                                                        <template x-for="(user, index) in <?php echo e($remainingJson); ?>" :key="index">
                                                            <div class="text-center" x-show="expandedMembers[<?php echo e($itineraryId); ?>]">
                                                                <template x-if="user.link">
                                                                    <a :href="user.link" class="block w-8 h-8 rounded-full overflow-hidden bg-gray-200 mx-auto">
                                                                        <img :src="user.avatar" :alt="user.name" class="w-full h-full object-cover">
                                                                    </a>
                                                                </template>
                                                                <template x-if="!user.link">
                                                                    <div class="block w-8 h-8 rounded-full overflow-hidden bg-gray-200 mx-auto">
                                                                        <img :src="user.avatar" :alt="user.name" class="w-full h-full object-cover">
                                                                    </div>
                                                                </template>
                                                                <p class="text-xs mt-1 truncate text-black max-w-[72px]" x-text="user.name"></p>
                                                            </div>
                                                        </template>

                                                        
                                                        <?php if($members->count() > 3): ?>
                                                            <button
                                                                @click="expandedMembers[<?php echo e($itineraryId); ?>] = !expandedMembers[<?php echo e($itineraryId); ?>]"
                                                                class="text-sm text-gray-700 hover:underline"
                                                            >
                                                                <span x-show="!expandedMembers[<?php echo e($itineraryId); ?>]">...+<?php echo e($members->count() - 3); ?>more</span>
                                                                <span x-show="expandedMembers[<?php echo e($itineraryId); ?>]">▲ close</span>
                                                            </button>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                </div>
                                
                                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                    <h2 class="text-center text-lg font-semibold mb-4">Post Like Ranking</h2>

                                    
                                    <div class="flex gap-4 overflow-x-auto pb-4">
                                        <?php $__currentLoopData = $posts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $post): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <div class="min-w-[200px] bg-white rounded-lg shadow-md flex-shrink-0 overflow-hidden border border-gray-200">
                                                
                                                <div class="text-center px-2 pt-2">
                                                    <?php if($likeCounts[$index] == $likeCounts[0]): ?>
                                                        <div class="text-yellow-300 font-semibold text-sm">
                                                            <i class="fa-solid fa-crown"></i> 1st
                                                        </div>
                                                        <a href="<?php echo e(route('post.show', $post->id)); ?>"
                                                        class="block text-black hover:text-yellow-300 text-xs truncate mt-1">
                                                            <?php echo e($post->title); ?>

                                                        </a>
                                                    <?php elseif($likeCounts[$index] == $likeCounts[1]): ?>
                                                        <div class="text-gray-400 font-semibold text-sm">
                                                            <i class="fa-solid fa-crown"></i> 2nd
                                                        </div>
                                                        <a href="<?php echo e(route('post.show', $post->id)); ?>"
                                                        class="block text-black hover:text-gray-400 text-xs truncate mt-1">
                                                            <?php echo e($post->title); ?>

                                                        </a>
                                                    <?php else: ?>
                                                        <div class="text-yellow-500 font-semibold text-sm">
                                                            <i class="fa-solid fa-crown"></i> 3rd
                                                        </div>
                                                        <a href="<?php echo e(route('post.show', $post->id)); ?>"
                                                        class="block text-black hover:text-yellow-500 text-xs truncate mt-1">
                                                            <?php echo e($post->title); ?>

                                                        </a>
                                                    <?php endif; ?>
                                                </div>

                                                
                                                <div class="relative mt-2">
                                                    <a href="<?php echo e(route('post.show', $post->id)); ?>">
                                                        <img src="<?php echo e($post->image); ?>"
                                                            alt="<?php echo e($post->id); ?>"
                                                            class="w-full h-40 object-cover hover:scale-105 transition duration-300">
                                                        <div class="absolute bottom-2 right-2 bg-white/90 rounded-full px-2 py-1 flex items-center text-sm shadow">
                                                            <i class="fa-solid fa-heart text-red-500 mr-1"></i>
                                                            <span><?php echo e($post->likes()->count()); ?></span>
                                                        </div>
                                                    </a>
                                                </div>
                                            </div>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>

                                    
                                    <div class="mt-4 text-right text-sm text-blue-500">
                                        <a href="<?php echo e(route('post.list')); ?>">View Post More</a>
                                    </div>
                                </div>
                            </div>
                        </div>
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



<script src="<?php echo e(asset('js/homepage_calender.js')); ?>"></script>

<?php /**PATH C:\Users\Tamak\Desktop\gi_12_tripe_may\resources\views/dashboard.blade.php ENDPATH**/ ?>