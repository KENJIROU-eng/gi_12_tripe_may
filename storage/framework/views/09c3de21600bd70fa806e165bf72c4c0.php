<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['value']));

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

foreach (array_filter((['value']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

<label <?php echo e($attributes->merge(['class' => 'block font-medium text-sm text-gray-500 dark:text-gray-300'])); ?>>
    <?php echo e($value ?? $slot); ?>

</label>
<<<<<<<< HEAD:storage/framework/views/a44a9e0254b2f3190eaced8865daf9f2.php
<?php /**PATH C:\Users\Tamak\Desktop\gi_12_tripe_may\resources\views/components/input-label.blade.php ENDPATH**/ ?>
========
<?php /**PATH C:\Users\USER\Desktop\gi_12_tripe_may\resources\views/components/input-label.blade.php ENDPATH**/ ?>
>>>>>>>> 6df93b8a75dd54c8cbc6cfa7579d8c9215562e33:storage/framework/views/09c3de21600bd70fa806e165bf72c4c0.php
