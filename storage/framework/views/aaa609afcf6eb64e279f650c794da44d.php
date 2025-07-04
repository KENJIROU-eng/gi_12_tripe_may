<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['status']));

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

foreach (array_filter((['status']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

<?php if($status): ?>
    <div <?php echo e($attributes->merge(['class' => 'font-medium text-sm text-green-600 dark:text-green-400'])); ?>>
        <?php echo e($status); ?>

    </div>
<?php endif; ?>
<<<<<<<< HEAD:storage/framework/views/52595ad903b560c5fa20da72ccd29c6f.php
<?php /**PATH C:\Users\Tamak\Desktop\gi_12_tripe_may\resources\views/components/auth-session-status.blade.php ENDPATH**/ ?>
========
<?php /**PATH C:\Users\USER\Desktop\gi_12_tripe_may\resources\views/components/auth-session-status.blade.php ENDPATH**/ ?>
>>>>>>>> 6df93b8a75dd54c8cbc6cfa7579d8c9215562e33:storage/framework/views/aaa609afcf6eb64e279f650c794da44d.php
