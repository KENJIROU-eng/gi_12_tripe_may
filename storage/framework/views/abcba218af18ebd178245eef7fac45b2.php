<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

        <title><?php echo e(config('app.name', 'Laravel')); ?></title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Bree+Serif&family=Slabo+27px&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Oswald:wght@200..700&family=Playfair+Display:ital,wght@1,400..900&family=Playwrite+IN&family=Raleway:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
        <link rel="icon" href="<?php echo e(asset('images/tripeas_logo_20250617.png')); ?>" type="image/x-icon">

        <!-- Scripts -->
        <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
        <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
        <script src="https://unpkg.com/alpinejs" defer></script>
        <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
        <?php echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::styles(); ?>


        
        <link rel="stylesheet" href="<?php echo e(asset('css/style.css')); ?>">

        <style>
            [x-cloak] {
                display: none !important;
            }
        </style>

    </head>
    <body data-user-id="<?php echo e(Auth::user()->id); ?>" class="page-transition">
        <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
            <?php echo $__env->make('layouts.navigation', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <!-- Page Content -->
            <main class="pt-12" style="background-image: url('/images/mesut-kaya-eOcyhe5-9sQ-unsplash.jpg'); background-size: cover;">
            
                <?php echo e($slot); ?>

            </main>
            
            <?php if(!in_array(Route::currentRouteName(), ['message.show'])): ?>
                <?php echo $__env->make('layouts.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php endif; ?>
        </div>
        <?php echo $__env->yieldPushContent('scripts'); ?>
        <script>
            window.appData = {
                groupIds: <?php echo json_encode($groupIds ?? [], 15, 512) ?>,
                tripSchedule: <?php echo json_encode($tripSchedule ?? [], 15, 512) ?>,
                tripName: <?php echo json_encode($tripName ?? [], 15, 512) ?>,
                tripId: <?php echo json_encode($tripId ?? [], 15, 512) ?>,
            };
            const routeUrls = <?php echo json_encode($routeUrls ?? [], 15, 512) ?>;
        </script>


        <?php echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::scripts(); ?>

    </body>
</html>
<?php /**PATH C:\Users\Tamak\Desktop\gi_12_tripe_may\resources\views/layouts/app.blade.php ENDPATH**/ ?>