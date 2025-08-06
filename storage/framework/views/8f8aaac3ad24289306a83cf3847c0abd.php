<?php if($paginator->hasPages()): ?>
    <nav role="navigation" aria-label="Pagination Navigation" class="mt-6 flex justify-center">
        <ul class="inline-flex items-center space-x-1 text-sm">
            
            <?php if($paginator->onFirstPage()): ?>
                <li class="px-3 py-1 text-gray-400 cursor-default"><i class="fa-solid fa-chevron-left"></i> Previous</li>
            <?php else: ?>
                <li>
                    <a href="<?php echo e($paginator->previousPageUrl()); ?>" class="px-3 py-1 text-gray-700 hover:text-green-500"><i class="fa-solid fa-chevron-left"></i> Previous</a>
                </li>
            <?php endif; ?>

            
            <?php
                $current = $paginator->currentPage();
                $last    = $paginator->lastPage();
            ?>

            
            <?php if($current > 2): ?>
                <li>
                    <a href="<?php echo e($paginator->url(1)); ?>" class="px-3 py-1 text-gray-700 hover:text-green-500">1</a>
                </li>
                <?php if($current > 3): ?>
                    <li class="px-3 py-1 text-gray-400">...</li>
                <?php endif; ?>
            <?php endif; ?>

            
            <?php for($i = max(1, $current - 1); $i <= min($last, $current + 1); $i++): ?>
                <?php if($i == $current): ?>
                    <li class="px-3 py-1 text-white bg-green-500 rounded"><?php echo e($i); ?></li>
                <?php else: ?>
                    <li><a href="<?php echo e($paginator->url($i)); ?>" class="px-3 py-1 text-gray-700 hover:text-green-500"><?php echo e($i); ?></a></li>
                <?php endif; ?>
            <?php endfor; ?>

            
            <?php if($current < $last - 1): ?>
                <?php if($current < $last - 2): ?>
                    <li class="px-3 py-1 text-gray-400">...</li>
                <?php endif; ?>
                <li><a href="<?php echo e($paginator->url($last)); ?>" class="px-3 py-1 text-gray-700 hover:text-green-500"><?php echo e($last); ?></a></li>
            <?php endif; ?>

            

            
            <?php if($paginator->hasMorePages()): ?>
                <li>
                    <a href="<?php echo e($paginator->nextPageUrl()); ?>" class="px-3 py-1 text-gray-700 hover:text-green-500">Next <i class="fa-solid fa-chevron-right"></i></a>
                </li>
            <?php else: ?>
                <li class="px-3 py-1 text-gray-400 cursor-default">Next <i class="fa-solid fa-chevron-right"></i></li>
            <?php endif; ?>
        </ul>
    </nav>
<?php endif; ?>
<?php /**PATH C:\Users\Tamak\Desktop\gi_12_tripe_may\resources\views/vendor/pagination/custom.blade.php ENDPATH**/ ?>