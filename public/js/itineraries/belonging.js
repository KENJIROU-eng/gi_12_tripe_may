/**
 * belongings UI handler
 *
 * このスクリプトは、旅行持ち物機能におけるUIの挙動を担当します。
 * - チェック状態の同期と背景グレーアウト制御
 * - 編集・削除機能の実装
 * - 新規アイテム登録時の重複チェック処理
 * - 全選択/解除、文字数カウント表示
 */

document.addEventListener('DOMContentLoaded', () => {
    // --- CSRFトークン取得とaxiosの存在チェック ---
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    if (!csrfToken) return console.error('CSRF token not found.');
    if (typeof axios === 'undefined') return console.error('Axios is not loaded.');

    /**
     * グレーアウト表示を更新（全員チェック済みなら灰色、自分のみチェックなら青）
     */
    const updateGrayoutState = (belongingElement) => {
        const userId = document.body.dataset.userId?.trim();
        const allCheckboxes = belongingElement.querySelectorAll('.member-checkbox');
        const checkedCount = [...allCheckboxes].filter(cb => cb.checked).length;
        const ownCheckbox = belongingElement.querySelector(`.member-checkbox[data-user-id="${userId}"]`);
        const selfChecked = ownCheckbox?.checked;
        const allChecked = allCheckboxes.length > 0 && checkedCount === allCheckboxes.length;

        belongingElement.classList.remove(
            'opacity-50', 'bg-white', 'dark:bg-gray-700', 'bg-blue-100', 'dark:bg-blue-200', 'bg-gray-200'
        );

        if (allChecked) belongingElement.classList.add('opacity-50', 'bg-gray-200');
        else if (selfChecked) belongingElement.classList.add('bg-blue-100', 'dark:bg-blue-200');
        else belongingElement.classList.add('bg-white', 'dark:bg-gray-700');

        belongingElement.dataset.checked = allChecked ? '1' : '0';
    };

    /**
     * チェックボックスの変更監視と同期処理
     */
    const initBelongingCheckboxes = () => {
        document.querySelectorAll('.belonging-item').forEach(item => {
            updateGrayoutState(item);
            item.querySelectorAll('.member-checkbox').forEach(cb => {
                cb.addEventListener('change', async (e) => {
                    const checkbox = e.target;
                    const { belongingId, userId } = checkbox.dataset;
                    try {
                        await axios.patch(`/belonging/${belongingId}/user/${userId}`, {
                            is_checked: checkbox.checked
                        }, {
                            headers: { 'X-CSRF-TOKEN': csrfToken }
                        });
                        updateGrayoutState(item);
                    } catch (err) {
                        checkbox.checked = !checkbox.checked;
                        alert('Check update failed');
                    }
                });
            });
        });
    };

    /**
     * 編集・削除ボタンの初期化
     */
    const initEditAndDeleteButtons = () => {
        const modal = document.getElementById('editModal');
        const form = document.getElementById('editForm');
        const nameInput = document.getElementById('editName');
        const descInput = document.getElementById('editDescription');
        const idInput = document.getElementById('editBelongingId');
        const memberCheckboxes = document.querySelectorAll('.edit-member-checkbox');

        // 編集ボタンクリック時、モーダルに情報をセット
        document.querySelectorAll('.edit-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                const item = btn.closest('.belonging-item');
                idInput.value = item.dataset.belongingId;
                nameInput.value = item.dataset.belongingName;
                descInput.value = item.dataset.belongingDescription;
                const userIds = JSON.parse(item.dataset.belongingUsers || '[]');
                memberCheckboxes.forEach(cb => cb.checked = userIds.includes(Number(cb.value)));
                modal.classList.remove('hidden');
            });
        });

        // キャンセルでモーダルを閉じる
        document.getElementById('cancelEdit').addEventListener('click', () => {
            modal.classList.add('hidden');
        });

        // 更新送信処理
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            const members = [...memberCheckboxes].filter(cb => cb.checked).map(cb => Number(cb.value));
            try {
                await axios.patch(`/belonging/${idInput.value}/update`, {
                    name: nameInput.value.trim(),
                    description: descInput.value.trim(),
                    members
                }, {
                    headers: { 'X-CSRF-TOKEN': csrfToken }
                });
                location.reload();
            } catch (err) {
                alert('Update failed.');
            }
        });

        // 削除処理
        document.querySelectorAll('.delete-btn').forEach(btn => {
            btn.addEventListener('click', async () => {
                try {
                    await axios.delete(`/belonging/${btn.dataset.belongingId}/destroy`, {
                        headers: { 'X-CSRF-TOKEN': csrfToken }
                    });
                    btn.closest('.belonging-item').remove();
                } catch (err) {
                    alert('Deletion failed.');
                }
            });
        });
    };

    /**
     * 要素が表示されるまで待ってから初期化関数を実行
     */
    const waitForElements = (selector, callback, timeout = 3000) => {
        const check = () => {
            if (document.querySelector(selector)) callback();
            else if ((timeout -= 100) > 0) setTimeout(check, 100);
        };
        check();
    };

    waitForElements('.edit-btn', initEditAndDeleteButtons);
    waitForElements('.member-checkbox', initBelongingCheckboxes);

    /**
     * チェック済みアイテムの表示・非表示切り替え
     */
    const toggleBtn = document.getElementById('toggleCheckedBtn');
    if (toggleBtn) {
        let showChecked = true;
        toggleBtn.addEventListener('click', () => {
            showChecked = !showChecked;
            document.querySelectorAll('.belonging-item').forEach(item => {
                const isChecked = item.dataset.checked === '1' || item.classList.contains('is-checked');
                item.classList.toggle('hidden', !showChecked && isChecked);
            });
            toggleBtn.innerHTML = showChecked
                ? '<i class="fa-solid fa-eye"></i>'
                : '<i class="fa-solid fa-eye-slash"></i>';
        });
    }

    /**
     * 新規フォーム送信時の重複チェックと統合処理
     */
    const form = document.getElementById('belongingForm');
    if (form) {
        const duplicateModal = document.getElementById('duplicateModal');
        const addToExistingBtn = document.getElementById('addToExistingBtn');
        const createNewBtn = document.getElementById('createNewBtn');
        const cancelDuplicate = document.getElementById('cancelDuplicate');

        let lastItemName = '', lastDescription = '', lastMembers = [], lastItineraryId = '', duplicateId = null;

        const handleFormSubmit = async function (e) {
            e.preventDefault();

            const itemName = form.querySelector('input[name="item"]').value.trim();
            const description = form.querySelector('textarea[name="description"]').value;
            const members = [...form.querySelectorAll('input[name="members[]"]:checked')].map(cb => cb.value);
            const itineraryId = form.action.split('/').slice(-2, -1)[0];

            if (!itemName || members.length === 0) {
                alert('Please enter the item name and member');
                return;
            }

            try {
                const res = await axios.get(`/belonging/check-duplicate`, {
                    params: { name: itemName, itinerary_id: itineraryId }
                });

                if (res.data.exists) {
                    lastItemName = itemName;
                    lastDescription = description;
                    lastMembers = members;
                    lastItineraryId = itineraryId;
                    duplicateId = res.data.id;
                    duplicateModal.classList.remove('hidden');
                } else {
                    form.removeEventListener('submit', handleFormSubmit);
                    form.submit();
                }
            } catch (error) {
                console.error('Duplicate check failed:', error);
                alert('Duplicate check failed.');
            }
        };

        form.addEventListener('submit', handleFormSubmit);

        addToExistingBtn.addEventListener('click', async () => {
            try {
                await axios.patch(`/belonging/${duplicateId}/add-members`, {
                    members: lastMembers
                }, {
                    headers: { 'X-CSRF-TOKEN': csrfToken }
                });
                location.reload();
            } catch (error) {
                alert('Failed to append to existing item.');
            }
        });

        createNewBtn.addEventListener('click', () => {
            duplicateModal.classList.add('hidden');
            form.removeEventListener('submit', handleFormSubmit);
            form.submit();
        });

        cancelDuplicate.addEventListener('click', () => {
            duplicateModal.classList.add('hidden');
        });

        // 全選択トグル（新規フォーム）
        const toggleSelectBtn = document.getElementById('toggleSelectAllMembers');
        if (toggleSelectBtn) {
            const checkboxes = form.querySelectorAll('input[name="members[]"]');
            const updateLabel = () => {
                const allChecked = [...checkboxes].every(cb => cb.checked);
                toggleSelectBtn.textContent = allChecked ? 'Unselect All' : 'Select All';
            };

            toggleSelectBtn.addEventListener('click', () => {
                const allChecked = [...checkboxes].every(cb => cb.checked);
                checkboxes.forEach(cb => cb.checked = !allChecked);
                updateLabel();
            });

            updateLabel();
        }
    }

    // 編集モーダル内の全選択トグル
    const editSelectAllBtn = document.getElementById('editToggleSelectAll');
    if (editSelectAllBtn) {
        editSelectAllBtn.addEventListener('click', () => {
            const checkboxes = document.querySelectorAll('.edit-member-checkbox');
            const allChecked = [...checkboxes].every(cb => cb.checked);
            checkboxes.forEach(cb => cb.checked = !allChecked);
            editSelectAllBtn.textContent = allChecked ? 'Select All' : 'Unselect All';
        });
    }

    /**
     * 入力欄の文字数カウント（リアルタイム）
     */
    function setupCharCount(inputId, counterId, defaultMaxLength = 100) {
        const input = document.getElementById(inputId);
        const counter = document.getElementById(counterId);
        if (!input || !counter) return;

        const maxLength = input.getAttribute('maxlength') || defaultMaxLength;

        const updateCounter = () => {
            const length = input.value.length;
            counter.textContent = `${length} / ${maxLength}`;
        };

        input.addEventListener('input', updateCounter);
        updateCounter();
    }

    // 文字数カウント適用箇所
    setupCharCount('item', 'itemCharCount', 50);
    setupCharCount('description', 'descriptionCharCount', 500);
    setupCharCount('editName', 'editNameCharCount', 50);
    setupCharCount('editDescription', 'editDescriptionCharCount', 500);
});
