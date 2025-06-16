// axiosのCSRFトークン設定（Laravel用）
axios.defaults.headers.common['X-CSRF-TOKEN'] =
    document.querySelector('meta[name="csrf-token"]').content;

let hideChecked = false;

// グローバル関数：モーダルリストを再描画
function renderModalList() {
    const modalList = document.getElementById('modalBelongingList');
    const list = document.getElementById('belongingList');

    modalList.innerHTML = '';

    const items = list.querySelectorAll('li');
    items.forEach((item) => {
        const clone = item.cloneNode(true);
        const checkbox = clone.querySelector('.item-checkbox');
        if (hideChecked && checkbox.checked) {
            clone.style.display = 'none';
        }
        setupItemEvents(clone);
        modalList.appendChild(clone);
    });
}

// グローバル関数：イベント付与（モーダル用）
function setupItemEvents(item) {
    const checkbox = item.querySelector('.item-checkbox');
    const editBtn = item.querySelector('.edit-btn');
    const deleteBtn = item.querySelector('.delete-btn');
    const itemName = item.querySelector('.item-name');
    const itemId = item.getAttribute('data-id');
    const list = document.getElementById('belongingList');

    // チェック操作同期
    checkbox.addEventListener('change', () => {
        const isChecked = checkbox.checked;
        itemName.classList.toggle('line-through', isChecked);
        itemName.classList.toggle('text-gray-400', isChecked);

        const original = list.querySelector(`li[data-id="${itemId}"]`);
        const originalCheckbox = original.querySelector('.item-checkbox');
        const originalName = original.querySelector('.item-name');

        originalCheckbox.checked = isChecked;
        originalName.classList.toggle('line-through', isChecked);
        originalName.classList.toggle('text-gray-400', isChecked);
    });

    // 編集
    editBtn.addEventListener('click', () => {
        const oldName = itemName.textContent.trim();
        const input = document.createElement('input');
        input.type = 'text';
        input.value = oldName;
        input.className = 'border rounded px-1 py-0.5';
        itemName.replaceWith(input);
        input.focus();

        const finishEdit = async () => {
            const newName = input.value.trim();
if (newName && newName !== oldName) {
    try {
        await axios.put(`/belongings/${itemId}`, { name: newName });

        const newSpan = document.createElement('span');
        newSpan.className = 'item-name';
        newSpan.textContent = newName;
        input.replaceWith(newSpan);

        const original = document.querySelector(`#belongingList li[data-id="${itemId}"] .item-name`);
        if (original) {
            original.textContent = newName;
        }
    } catch (err) {
        console.error('Modal edit failed:', err);
        input.replaceWith(itemName);
    }
}
 else {
                input.replaceWith(itemName);
            }
        };

        input.addEventListener('blur', finishEdit);
        input.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') {
                e.preventDefault(); // フォーム送信防止
                input.blur();       // blurを発火して編集を確定
            }
        });
    });

    // 削除
    deleteBtn.addEventListener('click', () => {
        const original = list.querySelector(`li[data-id="${itemId}"]`);
        item.remove();
        original.remove();
    });
}

// DOM構築完了後の処理
document.addEventListener('DOMContentLoaded', () => {
    const viewAllBtn = document.getElementById('viewAllBtn');
    const modal = document.getElementById('viewAllModal');
    const closeModal = document.getElementById('closeModal');
    const list = document.getElementById('belongingList');

    // モーダル開く
    viewAllBtn.addEventListener('click', () => {
        renderModalList();
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    });

    // モーダル閉じる
    closeModal.addEventListener('click', () => {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    });
});

// アイテムDOM追加関数
function addItemToDOM(belonging) {
    const li = document.createElement('li');
    li.className = 'flex items-center justify-between gap-2 p-2 border rounded';
    li.dataset.id = belonging.id;

    const checkboxDiv = document.createElement('div');
    checkboxDiv.className = 'flex-shrink-0';
    const checkbox = document.createElement('input');
    checkbox.type = 'checkbox';
    checkbox.className = 'item-checkbox';
    checkbox.setAttribute('data-id', belonging.id);
    checkbox.checked = belonging.is_checked;
    checkboxDiv.appendChild(checkbox);

    const nameDiv = document.createElement('div');
    nameDiv.className = 'flex-grow text-center';
    const span = document.createElement('span');
    span.className = 'item-name' + (belonging.is_checked ? ' text-gray-400 line-through' : '');
    span.textContent = belonging.name;
    nameDiv.appendChild(span);

    const buttonDiv = document.createElement('div');
    buttonDiv.className = 'flex-shrink-0 flex gap-2';

    const editBtn = document.createElement('button');
    editBtn.type = 'button';
    editBtn.className = 'edit-btn';
    editBtn.setAttribute('data-id', belonging.id);
    editBtn.innerHTML = '<i class="fa-solid fa-pen text-yellow-300"></i>';

    const deleteBtn = document.createElement('button');
    deleteBtn.type = 'button';
    deleteBtn.className = 'delete-btn';
    deleteBtn.setAttribute('data-id', belonging.id);
    deleteBtn.innerHTML = '<i class="fa-solid fa-trash-can text-red-500"></i>';

    buttonDiv.appendChild(editBtn);
    buttonDiv.appendChild(deleteBtn);

    li.appendChild(checkboxDiv);
    li.appendChild(nameDiv);
    li.appendChild(buttonDiv);

    document.getElementById('belongingList').appendChild(li);
}

// 追加ボタン
document.getElementById('addItemBtn').addEventListener('click', async () => {
    const input = document.getElementById('itemInput');
    const name = input.value.trim();
    if (!name) return;

    try {
        const res = await axios.post('/belongings/store', {
            name: name,
            itinerary_id: itineraryId,
        });
        addItemToDOM(res.data);
        input.value = '';
        renderModalList();
    } catch (err) {
        console.error('Add failed:', err);
    }
});

// チェック更新
document.addEventListener('change', async (e) => {
    if (e.target.classList.contains('item-checkbox')) {
        const checkbox = e.target;
        const li = checkbox.closest('li');
        const id = checkbox.dataset.id;
        const checked = checkbox.checked;

        try {
            await axios.put(`/belongings/${id}`, { is_checked: checked });
            const nameSpan = li.querySelector('.item-name');
            nameSpan.classList.toggle('text-gray-400', checked);
            nameSpan.classList.toggle('line-through', checked);
            if (hideChecked) li.style.display = checked ? 'none' : '';
        } catch (err) {
            console.error('Check update failed:', err);
        }
    }
});

// 編集/削除（通常画面）
document.addEventListener('click', async (e) => {
    const target = e.target;
    const li = target.closest('li');
    if (!li) return;
    const id = li.dataset.id;

    // 編集
    if (target.closest('.edit-btn')) {
        const span = li.querySelector('.item-name');
        const oldName = span.textContent.trim();
        const input = document.createElement('input');
        input.type = 'text';
        input.value = oldName;
        input.className = 'border rounded px-1 py-0.5';
        span.replaceWith(input);
        input.focus();

        const finishEdit = async () => {
            const newName = input.value.trim();
            if (newName && newName !== oldName) {
                try {
                    await axios.put(`/belongings/${id}`, { name: newName });
                    const newSpan = document.createElement('span');
                    newSpan.className = 'item-name';
                    newSpan.textContent = newName;
                    input.replaceWith(newSpan);
                } catch (err) {
                    console.error('Update failed:', err);
                    input.replaceWith(span);
                }
            } else {
                input.replaceWith(span);
            }
        };

        input.addEventListener('blur', finishEdit);
        input.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') {
                e.preventDefault();
                input.blur(); // blurを発火させてfinishEditを呼ぶ
            }
        });
    }


    // 削除
    if (target.closest('.delete-btn')) {
        try {
            await axios.delete(`/belongings/${id}`);
            li.remove();
        } catch (err) {
            console.error('Delete failed:', err);
        }
    }
});

// 表示切替（通常）
document.getElementById('toggleVisibility').addEventListener('click', () => {
    hideChecked = !hideChecked;
    document.querySelectorAll('.item-checkbox').forEach(checkbox => {
        const li = checkbox.closest('li');
        if (checkbox.checked) {
            li.style.display = hideChecked ? 'none' : '';
        }
    });

    const icon = document.querySelector('#toggleVisibility i');
    icon.classList.toggle('fa-eye', !hideChecked);
    icon.classList.toggle('fa-eye-slash', hideChecked);
});

// 表示切替（モーダル）
document.getElementById('modalToggleVisibility').addEventListener('click', () => {
    hideChecked = !hideChecked;
    document.querySelectorAll('#modalBelongingList .item-checkbox').forEach(checkbox => {
        const li = checkbox.closest('li');
        li.style.display = (hideChecked && checkbox.checked) ? 'none' : '';
    });

    const icon = document.querySelector('#modalToggleVisibility i');
    icon.classList.toggle('fa-eye', !hideChecked);
    icon.classList.toggle('fa-eye-slash', hideChecked);
});

// Enterで追加
document.getElementById('itemInput').addEventListener('keypress', (e) => {
    if (e.key === 'Enter') {
        document.getElementById('addItemBtn').click();
    }
});
