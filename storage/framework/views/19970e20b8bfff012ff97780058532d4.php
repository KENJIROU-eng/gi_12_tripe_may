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
    <div class="bg-white/85 pt-4">
        <script src="https://js.pusher.com/8.4.0/pusher.min.js"></script>

        <div class="relative flex w-full h-16 items-center shadow-[0_4px_10px_rgba(0,0,0,0.4)] justify-between top-0 left-0 right-0 z-50">
            <a href="<?php echo e(route('groups.index')); ?>"><i class="fa-regular fa-less-than text-gray-800 hover:text-gray-400 ml-4 text-xl"></i></a>
            <?php if($group->users->count() > 2): ?>
                <p class="text-lg sm:text-xl md:text-2xl lg:text-3xl absolute left-1/2 transform -translate-x-1/2 font-semibold text-center text-gray-800"><?php echo e($group->name); ?>  (<?php echo e($group->users->count()); ?>)</p>
            <?php elseif($group->users->count() == 2): ?>
                <?php if(($group->name == Auth::User()->name) || ($group->user_id == Auth::User()->id)): ?>
                    <?php $__currentLoopData = $group->users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if(($group->name == $user->name) || ($group->user_id == $user->id)): ?>
                            <?php if($user->id != Auth::User()->id): ?>
                                <p class="text-2xl absolute left-1/2 transform -translate-x-1/2 font-semibold text-center"><?php echo e($user->name); ?></p>
                            <?php endif; ?>
                        <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php else: ?>
                    <p class="text-2xl absolute left-1/2 transform -translate-x-1/2 font-semibold text-center text-emerald-600"><?php echo e($group->name); ?></p>
                <?php endif; ?>
            <?php else: ?>
                <p class="text-2xl absolute left-1/2 transform -translate-x-1/2 font-semibold text-center text-emerald-600"><?php echo e($group->name); ?></p>
            <?php endif; ?>
            <div x-data="{ open: false }" class="flex ">
                <button @click="open = !open" class="flex -space-x-4">
                    <?php $__currentLoopData = $group->users->take(3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <img src="<?php echo e($user->avatar ?? asset('images/user.png')); ?>" class="w-9 h-9 md:w-11 md:h-11 rounded-full border border-gray-300 hover:z-10" alt="<?php echo e($user->name); ?>">
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php if($group->users->count() > 3): ?>
                    <div class="w-8 h-8 md:w-10 md:h-10 rounded-full bg-gray-300 text-sm md:text-base text-gray-800 flex items-center justify-center border border-white">
                        +<?php echo e($group->users->count() - 3); ?>

                    </div>
                    <?php endif; ?>
                </button>
                <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-64 bg-white border rounded-lg shadow-lg z-50">
                    <div class="p-4">
                        <h2 class="text-sm font-semibold text-gray-600 mb-2">Group Member</h2>
                        <ul class="space-y-6 max-h-60 overflow-y-auto">
                            <?php $__currentLoopData = $group->users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li class="flex items-center space-x-3">
                                <?php if(isset($groupKey[$user->id])): ?>
                                <a href="<?php echo e(route('message.show', $groupKey[$user->id])); ?>">
                                    <div class="flex">
                                        <img src="<?php echo e($user->avatar ?? asset('images/user.png')); ?>" class="w-8 h-8 rounded-full" alt="<?php echo e($user->name); ?>">
                                        <span class="text-sm ml-2"><?php echo e($user->name); ?></span>
                                    </div>
                                </a>
                                <?php else: ?>
                                <div class="flex">
                                    <img src="<?php echo e($user->avatar ?? asset('images/user.png')); ?>" class="w-8 h-8 rounded-full" alt="<?php echo e($user->name); ?>">
                                    <span class="text-sm ml-2"><?php echo e($user->name); ?></span>
                                </div>
                                <?php endif; ?>
                            </li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        
        <div id="messages" data-group-id="<?php echo e($group->id); ?>" class="overflow-y-scroll  px-4 pb-20 pt-4 space-y-2" style="height: calc(100vh - 4rem - 4rem);">
            <?php $__currentLoopData = $messages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $message): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php $isMine = $message->user_id === auth()->id();?>
                <?php if($isMine): ?>
                    <?php if($message->message): ?>
                        <div id="message-<?php echo e($message->id); ?>" class="flex justify-end items-end">
                            <div class="text-xs text-right mt-1 text-gray-400 mr-2">
                                <div class="text-start"><?php echo e($message->created_at->format('H:i')); ?></div>
                                <div><?php echo e($message->created_at->format('Y-m-d')); ?></div>
                            </div>
                            <div class="text-base lg:text-xl bg-teal-200 mr-2 rounded-2xl p-3 max-w-[70%] shadow" oncontextmenu="openCustomMenu(event, <?php echo e($message->id); ?>, this)">
                                <div style="word-break: break-word; overflow-wrap: break-word; ">
                                    <?php echo nl2br(e($message->message)); ?>

                                </div>
                                
                            </div>
                        </div>
                    <?php elseif($message->image_url): ?>
                        <div id="message-<?php echo e($message->id); ?>" class="flex items-end justify-end">
                            <div class="text-xs text-gray-400 mr-2">
                                <div class="ml-auto"><?php echo e($message->created_at->format('H:i')); ?></div>
                                <div><?php echo e($message->created_at->format('Y-m-d')); ?></div>
                            </div>
                            <div class="max-w-[70%]">
                                <img src="<?php echo e($message->image_url); ?>" class="mt-2 max-w-xs rounded-lg mr-3" download>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php else: ?>
                    <?php if($message->message): ?>
                        <div>
                            <div class="flex items-start">
                                <img src="<?php echo e($message->user->avatar_url ?? asset('images/user.png')); ?>" class="w-8 h-8 rounded-full mt-1" alt="<?php echo e($message->user->name); ?>">
                                <div class="flex space-x-2 items-end">
                                    <div class="max-w-[70%]">
                                        <div class="text-sm text-gray-600 font-medium ml-1"><?php echo e($message->user->name); ?></div>
                                        <div class="text-base lg:text-xl bg-white border border-gray-200 rounded-2xl p-3 shadow">
                                            <div style="word-break: break-word; overflow-wrap: break-word; ">
                                                <?php echo nl2br(e($message->message)); ?>

                                            </div>
                                            
                                        </div>
                                    </div>
                                    <div class="text-xs text-gray-400 items-end">
                                        <div><?php echo e($message->created_at->format('H:i')); ?></div>
                                        <div><?php echo e($message->created_at->format('Y-m-d')); ?></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php elseif($message->image_url): ?>
                        <div>
                            <div class="flex items-start">
                                <img src="<?php echo e($message->user->avatar_url ?? asset('images/user.png')); ?>" class="w-8 h-8 rounded-full mt-1" alt="<?php echo e($message->user->name); ?>">
                                <div>
                                    <div class="text-sm md:text-base text-gray-600 font-medium ml-1"><?php echo e($message->user->name); ?></div>
                                    <div class="flex space-x-2 items-end">
                                        <img src="<?php echo e($message->image_url); ?>" class="mt-1 max-w-xs rounded-lg" download>
                                        <div class="text-xs text-gray-400 items-end">
                                            <div><?php echo e($message->created_at->format('H:i')); ?></div>
                                            <div><?php echo e($message->created_at->format('Y-m-d')); ?></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <ul id="custom-menu" class="absolute hidden bg-gray-100  rounded shadow z-50">
            <li id="edit-item" class="p-1 m-2 hover:bg-gray-200 cursor-pointer">
                Edit
            </li>
            <li id="delete-item" class="p-1 hover:bg-gray-200 cursor-pointer">
                Delete
            </li>
        </ul>

    </div>

<!-- 編集用フォーム -->
<div id="edit-form" class="hidden mt-4">
    <textarea id="edit-textarea" class="w-full p-2 border rounded"></textarea>
    <button id="submit-edit" class="mt-2 px-4 py-1 bg-blue-500 text-white rounded">送信</button>
</div>

<script>
    let currentMessageId = null;
    let targetMessageElement = null;

// カスタムメニューを開く関数（右クリック時に呼び出す）
window.openCustomMenu = function(event, messageId, element) {
    event.preventDefault();

    currentMessageId = messageId;
    targetMessageElement = element;
    console.log(targetMessageElement);

    const menu = document.getElementById('custom-menu');
    menu.style.top = `${event.clientY}px`;
    menu.style.left = `${event.clientX}px`;
    menu.classList.remove('hidden');
};

// DOM読み込み後にイベントを設定
document.addEventListener("DOMContentLoaded", () => {
    const customMenu = document.getElementById("custom-menu");
    const editItem = document.getElementById("edit-item");
    const editForm = document.getElementById("edit-form");
    const editTextarea = document.getElementById("edit-textarea");
    const submitEdit = document.getElementById("submit-edit");

    // 編集クリック時
    editItem.addEventListener("click", () => {
        console.log('Editクリック時のelement:', targetMessageElement);
        if (!targetMessageElement) return;

        const currentText = targetMessageElement.innerText.trim();
        editTextarea.value = currentText;
        editForm.classList.remove("hidden");
        customMenu.classList.add("hidden");
    });

    // 編集送信時
    submitEdit.addEventListener("click", () => {
        if (!targetMessageElement || !currentMessageId) return;

        const newText = editTextarea.value.trim();

        // 表示を即時反映（必要ならサーバーにもPOST/PUT送信可能）
        targetMessageElement.innerText = newText;

        // オプション：fetchでサーバーに送信したい場合は以下を使う

        editForm.classList.add("hidden");
        targetMessageElement = null;
        currentMessageId = null;
    });

    // メニュー外クリックで閉じる
    document.addEventListener("click", (e) => {
        const menu = document.getElementById('custom-menu');
        if (!menu.contains(e.target)) {
            menu.classList.add('hidden');
        }
    });

    // 削除処理
    document.getElementById('delete-item').addEventListener('click', () => {
        if (currentMessageId && confirm('本当に削除しますか？')) {
            fetch(`/chat/${currentMessageId}/delete`, {
                method:'DELETE',
                headers:{'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'}
            }).then(response => {
                if (response.ok) {
                    location.reload();
                }
            });
        }
    });
});
</script>


    <form id="chat-form" action="<?php echo e(route('message.send')); ?>" method="POST" enctype="multipart/form-data" class="flex items-center gap-2 p-2 border-t mt-4 bg-white fixed bottom-0 left-0 right-0 z-50">
    <?php echo csrf_field(); ?>
        <input type="hidden" name="group_id" value="<?php echo e($group->id); ?>">
        <textarea id="message-input" name="message" rows="1" placeholder="message..." class="flex-1 p-2 rounded-lg focus:outline-none focus:ring focus:border-teal-500 resize-none max-h-[6rem] overflow-y-auto leading-relaxed text-sm sm:text-base"></textarea>
        <input type="file" name="image" accept="image/*" class="hidden" id="image-upload">
        <label for="image-upload" class="cursor-pointer">
            <i class="fa-solid fa-image text-xl text-gray-500 hover:text-teal-700"></i>
        </label>
        <button type="submit" id="send-btn" class="px-4 py-2 bg-teal-500 text-white rounded-lg hover:bg-teal-700 disabled:opacity-50" disabled>Send
        </button>
    </form>

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
<?php /**PATH C:\Users\USER\Desktop\gi_12_tripe_may\resources\views/groups/show.blade.php ENDPATH**/ ?>