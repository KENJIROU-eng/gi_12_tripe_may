/**
 * Itinerary フィルタ・ソート・無限スクロール対応JS
 *
 * 主な機能:
 * - デスクトップ・モバイル両対応のフィルタ入力監視
 * - ソート（クリックまたはセレクトで昇順/降順切替）
 * - フィルタ条件とソート条件での描画
 * - 入力クリアボタン表示・動作
 * - スクロールによるページネーション読み込み（無限スクロール）
 */

document.addEventListener('DOMContentLoaded', () => {
    const itineraryContainer = document.getElementById('itineraryContainer');
    const scrollContainer = document.getElementById('scrollContainer');
    const clearBtn = document.getElementById('clearSearchBtn');
    const mobileClearBtn = document.getElementById('mobileClearSearchBtn');
    const rows = Array.from(document.querySelectorAll('.itinerary-row'));
    const scrollToTopBtn = document.getElementById('scrollToTopBtn');

    let currentSort = { key: '', direction: 'asc' };

    // 指定IDのfilter要素を取得（モバイル/PC共通）
    function getFilterValue(id) {
        const desktopEl = document.getElementById(id);
        const mobileEl = document.getElementById('mobile' + id.charAt(0).toUpperCase() + id.slice(1));
        return (mobileEl && mobileEl.value) || (desktopEl && desktopEl.value) || '';
    }

    // DOMに描画（検索・フィルタ後など）
    function render(filteredRows) {
        itineraryContainer.innerHTML = '';

        if (filteredRows.length === 0) {
            itineraryContainer.innerHTML = `
                <div class="text-center text-lg my-60">
                    <h2 class="mb-4 text-gray-500">No itinerary created yet.</h2>
                    <div class="text-green-500">
                        <a href="/itinerary/share">
                            <i class="fa-solid fa-plus"></i>
                            add itinerary
                        </a>
                    </div>
                </div>
            `;
        } else {
            filteredRows.forEach(row => itineraryContainer.appendChild(row));

            // ✅ スマホ用ラベルが無ければ追加
            filteredRows.forEach(row => {
                const labels = [
                    { selector: '.md\\:col-span-2', text: 'Created by' },
                    { selector: '.md\\:col-span-2:nth-of-type(3)', text: 'Group' },
                    { selector: '.md\\:col-span-3:nth-of-type(4)', text: 'Date' },
                    { selector: '.md\\:col-span-3:nth-of-type(5)', text: 'Title' },
                ];

                labels.forEach(({ selector, text }) => {
                    const el = row.querySelector(selector);
                    if (el && !el.querySelector('.mobile-label')) {
                        const label = document.createElement('span');
                        label.className = 'mobile-label block text-xs text-gray-500 dark:text-gray-400 md:hidden mb-0.5';
                        label.textContent = text;

                        el.classList.add('flex', 'flex-col', 'items-center', 'md:items-start', 'text-center', 'md:text-left');
                        el.insertBefore(label, el.firstChild);
                    }
                });
            });

        }

        updateClearButtonVisibility();
    }

    // ソートアイコン（矢印）の表示更新
    function updateSortIcons() {
        document.querySelectorAll('.sort-icon').forEach(icon => {
            const key = icon.dataset.key;
            icon.classList.remove('fa-sort', 'fa-sort-up', 'fa-sort-down');

            if (key === currentSort.key) {
                icon.classList.add(currentSort.direction === 'asc' ? 'fa-sort-up' : 'fa-sort-down');
            } else {
                icon.classList.add('fa-sort');
            }
        });
    }


    // クリアボタンの表示切替（検索・フィルタ・ソート条件があるか）
    function updateClearButtonVisibility() {
        const searchInput = document.getElementById('searchInput') || {};
        const mobileSearchInput = document.getElementById('mobileSearchInput') || {};
        const hasSearch = (searchInput.value || mobileSearchInput.value || '').trim() !== '';
        const hasFilter = ['filterUser', 'filterGroup', 'filterDateFrom', 'filterDateTo'].some(id => getFilterValue(id) !== '');
        const hasSort = currentSort.key !== '';
        clearBtn?.classList.toggle('hidden', !(hasSearch || hasFilter || hasSort));
    }

    // ソート処理（クリック or セレクト）
    function sortRows(key) {
        if (currentSort.key === key) {
            currentSort.direction = currentSort.direction === 'asc' ? 'desc' : 'asc';
        } else {
            currentSort = { key, direction: 'asc' };
        }

        rows.sort((a, b) => {
            const valA = a.dataset[key] || '';
            const valB = b.dataset[key] || '';
            if (key === 'date') {
                return currentSort.direction === 'asc'
                    ? new Date(valA) - new Date(valB)
                    : new Date(valB) - new Date(valA);
            } else if (key === 'finished') {
                return currentSort.direction === 'asc'
                    ? Number(valA) - Number(valB)
                    : Number(valB) - Number(valA);
            } else {
                return currentSort.direction === 'asc'
                    ? valA.localeCompare(valB)
                    : valB.localeCompare(valA);
            }

        });

        const filtered = filterRows(getSearchQuery());

        updateSortIcons();
        render(filtered);
    }

    // フィルタ条件に応じて行をフィルタリング
    function filterRows(query) {
        const q = query.trim().toLowerCase();
        const selectedUser = getFilterValue('filterUser');
        const selectedGroup = getFilterValue('filterGroup');
        const dateFrom = getFilterValue('filterDateFrom');
        const dateTo = getFilterValue('filterDateTo');

        return rows.filter(row => {
            const rowUser = row.dataset.user || '';
            const rowGroup = row.dataset.group || '';
            const rowDate = row.dataset.date || '';
            const matchesSearch = ['user', 'group', 'title', 'date'].some(key =>
                (row.dataset[key] || '').toLowerCase().includes(q)
            );
            const matchesUser = !selectedUser || rowUser === selectedUser;
            const matchesGroup = !selectedGroup || rowGroup === selectedGroup;
            const matchesDateFrom = !dateFrom || new Date(rowDate) >= new Date(dateFrom);
            const matchesDateTo = !dateTo || new Date(rowDate) <= new Date(dateTo);

            return matchesSearch && matchesUser && matchesGroup && matchesDateFrom && matchesDateTo;
        });
    }

    function getSearchQuery() {
        const desktop = document.getElementById('searchInput')?.value || '';
        const mobile = document.getElementById('mobileSearchInput')?.value || '';
        return `${desktop} ${mobile}`.trim();
    }

    // フィルタ条件が変更された時の監視
    ['filterUser', 'filterGroup', 'filterDateFrom', 'filterDateTo', 'mobileFilterUser', 'mobileFilterGroup', 'mobileFilterDateFrom', 'mobileFilterDateTo']
        .forEach(id => {
            const el = document.getElementById(id);
            if (el) {
                el.addEventListener('change', () => {
                    const filtered = filterRows(getSearchQuery());
                    render(filtered);
                });
            }
        });

    // 検索入力欄（PC・モバイル）
    const searchInput = document.getElementById('searchInput');
    const mobileSearchInput = document.getElementById('mobileSearchInput');
    searchInput?.addEventListener('input', () => {
        const filtered = filterRows(getSearchQuery());
        render(filtered);
    });
    mobileSearchInput?.addEventListener('input', () => {
        const filtered = filterRows(getSearchQuery());
        render(filtered);
    });

    // ヘッダのクリックでソート実行
    document.querySelectorAll('[data-sort]').forEach(header => {
        header.addEventListener('click', () => {
            sortRows(header.dataset.sort);
        });
    });

    // モバイルセレクトでソート実行
    document.getElementById('mobileSort')?.addEventListener('change', (e) => {
        if (e.target.value) sortRows(e.target.value);
    });

    // クリアボタンの動作
    clearBtn?.addEventListener('click', () => {
        ['searchInput', 'filterUser', 'filterGroup', 'filterDateFrom', 'filterDateTo',
         'mobileSearchInput', 'mobileFilterUser', 'mobileFilterGroup', 'mobileFilterDateFrom', 'mobileFilterDateTo']
        .forEach(id => {
            const el = document.getElementById(id);
            if (el) el.value = '';
        });
        currentSort = { key: '', direction: 'asc' };
        updateSortIcons();
        rows.sort((a, b) => new Date(b.dataset.created) - new Date(a.dataset.created));
        render(rows);
    });

    // モバイル版のクリアボタン
    mobileClearBtn?.addEventListener('click', () => {
        clearBtn?.click();
    });

    // 初期表示用
    updateSortIcons();
    render(rows);
    scrollContainer.dispatchEvent(new Event('scroll')); // 最初の読み込みトリガー

    // go to top
    let hideTimeout;

    window.addEventListener('scroll', () => {
        const isMobile = window.innerWidth < 768;
        const isAtTop = window.scrollY === 0;
        const btn = scrollToTopBtn;

        if (!btn) return;

        if (isMobile && !isAtTop) {
            btn.classList.remove('opacity-0', 'pointer-events-none');

            // 既存の非表示タイマーがあればリセット
            clearTimeout(hideTimeout);

            // 一定時間（2秒）後に自動で非表示
            hideTimeout = setTimeout(() => {
                btn.classList.add('opacity-0', 'pointer-events-none');
            }, 2000);
        } else {
            btn.classList.add('opacity-0', 'pointer-events-none');
            clearTimeout(hideTimeout);
        }
    });

    scrollToTopBtn?.addEventListener('click', () => {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });


});
