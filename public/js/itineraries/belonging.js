// axiosのCSRFトークン設定（Laravel用）
axios.defaults.headers.common['X-CSRF-TOKEN'] =
    document.querySelector('meta[name="csrf-token"]').content;

// itineraryId は Blade 側で `const itineraryId = @json($itinerary->id);` と定義されている前提
let hideChecked = false;

// アイテムをDOMに追加する関数
function addItemToDOM(belonging) {
    const li = document.createElement('li');
    li.className = 'flex items-center justify-between gap-2 p-2 border rounded';
    li.dataset.id = belonging.id;

    // 左：チェックボックス
    const checkboxDiv = document.createElement('div');
    checkboxDiv.className = 'flex-shrink-0';
    const checkbox = document.createElement('input');
    checkbox.type = 'checkbox';
    checkbox.className = 'item-checkbox';
    checkbox.setAttribute('data-id', belonging.id);
    checkbox.checked = belonging.is_checked;
    checkboxDiv.appendChild(checkbox);

    // 中央：名前
    const nameDiv = document.createElement('div');
    nameDiv.className = 'flex-grow text-center';
    const span = document.createElement('span');
    span.className = 'item-name' + (belonging.is_checked ? ' text-gray-400 line-through' : '');
    span.textContent = belonging.name;
    nameDiv.appendChild(span);

    // 右：編集・削除ボタン
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

    // li に3要素を追加
    li.appendChild(checkboxDiv);
    li.appendChild(nameDiv);
    li.appendChild(buttonDiv);

    document.getElementById('itemList').appendChild(li);
}


// アイテム追加
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
    } catch (err) {
        console.error('Add failed:', err);
    }
});

// 編集・削除・チェック切替のイベント委任
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

        input.addEventListener('blur', async () => {
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

// チェック切替
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
            if (hideChecked) {
                li.style.display = checked ? 'none' : '';
            }
        } catch (err) {
            console.error('Check update failed:', err);
        }
    }
});

// 表示切替
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
