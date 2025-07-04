<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['disabled' => false]));

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

foreach (array_filter((['disabled' => false]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

<input
    <?php if($disabled): echo 'disabled'; endif; ?>
    <?php echo e($attributes->merge([
        'class' => 'border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm'
    ])); ?>

    value="<?php echo e($attributes['value'] ?? ''); ?>"
>
<<<<<<<< HEAD:storage/framework/views/11a767d3124c82e2fcc06a12e9a88131.php
<?php /**PATH C:\Users\Tamak\Desktop\gi_12_tripe_may\resources\views/components/text-input.blade.php ENDPATH**/ ?>
========
<?php /**PATH C:\Users\USER\Desktop\gi_12_tripe_may\resources\views/components/text-input.blade.php ENDPATH**/ ?>
>>>>>>>> 6df93b8a75dd54c8cbc6cfa7579d8c9215562e33:storage/framework/views/d7cd3e59cfcd4568ad7ebf533b20739e.php
