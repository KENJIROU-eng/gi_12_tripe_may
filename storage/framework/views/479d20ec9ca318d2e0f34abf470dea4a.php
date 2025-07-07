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

        <!-- Scripts -->
        <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
        <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100 dark:bg-gray-900" style="background-image: url('/images/mesut-kaya-eOcyhe5-9sQ-unsplash.jpg'); background-size: cover;">
            <div>
                <a href="/">
                    <div class="h-20"></div>
                </a>
            </div>

            <div class="w-full h-full mt-6 px-6 py-4 bg-amber-50/40 dark:bg-gray-800 shadow-md overflow-hidden sm:rounded-lg">
                <?php echo e($slot); ?>

            </div>
        </div>

    </body>
</html>

<?php /**PATH C:\Users\Tamak\Desktop\gi_12_tripe_may\resources\views/layouts/guest.blade.php ENDPATH**/ ?>