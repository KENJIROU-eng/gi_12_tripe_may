document.addEventListener('DOMContentLoaded', () => {
    const itineraryContainer = document.getElementById('itineraryContainer');
    const scrollContainer = document.getElementById('scrollContainer');
    const searchInput = document.getElementById('searchInput');
    const sortIcons = document.querySelectorAll('.sort-icon');
    const clearBtn = document.getElementById('clearSearchBtn');

    const rows = Array.from(document.querySelectorAll('.itinerary-row'));
    let currentSort = { key: '', direction: 'asc' };
    let currentPage = 1;
    let loading = false;
    let noMoreData = false;

    function render(filteredRows) {
        itineraryContainer.innerHTML = '';
        filteredRows.forEach(row => itineraryContainer.appendChild(row));
        updateClearButtonVisibility();
    }

    function updateSortIcons() {
        sortIcons.forEach(icon => {
            const key = icon.dataset.key;
            icon.innerHTML = '';
            if (key === currentSort.key) {
                icon.innerHTML = currentSort.direction === 'asc'
                    ? '<i class="fa-solid fa-arrow-up-a-z"></i>'
                    : '<i class="fa-solid fa-arrow-down-a-z"></i>';
            }
        });
    }

    function updateClearButtonVisibility() {
        const hasSearch = searchInput.value.trim() !== '';
        const hasFilter = ['filterUser', 'filterGroup', 'filterDateFrom', 'filterDateTo'].some(id => {
            return document.getElementById(id).value !== '';
        });
        const hasSort = currentSort.key !== '';
        if (hasSearch || hasFilter || hasSort) {
            clearBtn.classList.remove('hidden');
        } else {
            clearBtn.classList.add('hidden');
        }
    }

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
            } else {
                return currentSort.direction === 'asc'
                    ? valA.localeCompare(valB)
                    : valB.localeCompare(valA);
            }
        });

        const filtered = filterRows(searchInput.value);
        updateSortIcons();
        render(filtered);
    }

    function filterRows(query) {
        const q = query.trim().toLowerCase();
        const selectedUser = document.getElementById('filterUser').value;
        const selectedGroup = document.getElementById('filterGroup').value;
        const dateFrom = document.getElementById('filterDateFrom').value;
        const dateTo = document.getElementById('filterDateTo').value;

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

    ['filterUser', 'filterGroup', 'filterDateFrom', 'filterDateTo'].forEach(id => {
        document.getElementById(id).addEventListener('change', () => {
            const filtered = filterRows(searchInput.value);
            render(filtered);
        });
    });

    searchInput.addEventListener('input', () => {
        const filtered = filterRows(searchInput.value);
        render(filtered);
    });

    document.querySelectorAll('[data-sort]').forEach(header => {
        header.addEventListener('click', () => {
            sortRows(header.dataset.sort);
        });
    });

    clearBtn.addEventListener('click', () => {
        searchInput.value = '';
        document.getElementById('filterUser').value = '';
        document.getElementById('filterGroup').value = '';
        document.getElementById('filterDateFrom').value = '';
        document.getElementById('filterDateTo').value = '';
        clearBtn.classList.add('hidden');

        // 作成日時順に初期化
        rows.sort((a, b) => {
            const valA = a.dataset.created || '';
            const valB = b.dataset.created || '';
            return new Date(valB) - new Date(valA); // 降順
        });

        currentSort = { key: '', direction: 'asc' };
        updateSortIcons();
        render(rows);
    });


    // 無限スクロール（scrollContainer に対して）
    scrollContainer.addEventListener('scroll', async () => {
        if (loading || noMoreData) return;

        const scrollTop = scrollContainer.scrollTop;
        const scrollHeight = scrollContainer.scrollHeight;
        const clientHeight = scrollContainer.clientHeight;
        const threshold = 100;

        if (scrollTop + clientHeight >= scrollHeight - threshold) {
            loading = true;
            currentPage++;

            try {
                const response = await fetch(`/itinerary/load?page=${currentPage}`);
                const html = await response.text();

                if (html.trim() === '') {
                    noMoreData = true;
                    return;
                }

                itineraryContainer.insertAdjacentHTML('beforeend', html);

                // rows に新要素追加
                const newRows = Array.from(document.querySelectorAll('.itinerary-row'));
                rows.length = 0;
                rows.push(...newRows);

                const filtered = filterRows(searchInput.value);
                render(filtered);
            } catch (error) {
                console.error('Error loading more itineraries:', error);
            } finally {
                loading = false;
            }
        }
    });

    // 初期表示時に描画と scroll イベント発火
    updateSortIcons();
    render(rows);
    scrollContainer.dispatchEvent(new Event('scroll'));
});
