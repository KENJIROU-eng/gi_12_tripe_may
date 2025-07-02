<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['itinerary']));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter((['itinerary']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

<div x-data="{ open: false }">
    <button @click="open = true" title="Delete" class="inline-flex items-center text-red-500 hover:text-red-700">
        <i class="fa-solid fa-trash-can text-lg"></i>
        <?php if($showText): ?>
            <span class="ml-1">Delete</span>

        <?php endif; ?>
    </button>

    <div x-show="open" x-cloak class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div @click.outside="open = false" class="bg-white rounded-lg w-full max-w-md overflow-hidden">
            
            <div class="bg-red-500 text-white px-6 py-4">
                <h1 class="text-3xl font-bold">Delete Itinerary</h1>
            </div>

            
            <div class="px-6 py-4">
                <p class="text-center">
                    <i class="fa-solid fa-triangle-exclamation text-red-500 text-4xl mb-2"></i>
                </p>
                <p class="mb-2 text-center">Are you sure you want to delete this itinerary?</p>

                <div class="text-sm text-gray-500 space-y-1">
                    <div class="flex items-start">
                        <span class="min-w-[100px] font-medium">Created by :</span>
                        <span><?php echo e($itinerary->user->name); ?></span>
                    </div>
                    <div class="flex items-start">
                        <span class="min-w-[100px] font-medium">Title :</span>
                        <span class="break-words whitespace-pre-wrap text-left max-w-[300px]"><?php echo e($itinerary->title); ?></span>

                    </div>
                    <div class="flex items-start">
                        <span class="min-w-[100px] font-medium">Date :</span>
                        <span>
                            <?php echo e(\Carbon\Carbon::parse($itinerary->start_date)->format('Y-m-d')); ?> ~
                            <?php echo e(\Carbon\Carbon::parse($itinerary->end_date)->format('Y-m-d')); ?>

                        </span>
                    </div>
                </div>
            </div>

            
            <div class="mt-4 px-6 py-3 flex justify-end gap-2">
                <button @click="open = false" class="bg-gray-300 px-4 py-2 rounded">Cancel</button>
                <form action="<?php echo e(route('itinerary.destroy', $itinerary->id)); ?>" method="post">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('DELETE'); ?>
                    <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php /**PATH /Users/kawaimayu/Desktop/gi_12_tripe_may/resources/views/itineraries/modals/delete.blade.php ENDPATH**/ ?>