// retrieve data from itinerary
const tripSchedule = window.appData.tripSchedule;
const tripName = window.appData.tripName;
const color = ['bg-amber-500', 'bg-sky-500', 'bg-purple-500', 'bg-pink-500', 'bg-green-500', 'bg-emerald-500', 'bg-violet-500'];

function generateCalendar(year, month) {
    const calendarBody = document.getElementById('calendar-body');
    calendarBody.innerHTML = '';

    const firstDayOfMonth = new Date(year, month, 1);
    const lastDayOfMonth = new Date(year, month + 1, 0);
    const daysInMonth = lastDayOfMonth.getDate();
    const firstDayOfWeek = firstDayOfMonth.getDay(); // 0:日〜6:土
    const currentDate = new Date();

    const maxVisible = 3;

    // 先頭の空白（日曜開始対応）
    for (let i = 0; i < firstDayOfWeek; i++) {
        const emptyCell = document.createElement('div');
        calendarBody.appendChild(emptyCell);
    }

    for (let i = 1; i <= daysInMonth; i++) {
        const dayCell = document.createElement('div');
        dayCell.classList.add('py-2', 'text-center', 'px-1', 'max-h-24', 'block');

        const dayWrapper = document.createElement('div');
        const thisDate = new Date(year, month, i);

        // 現在日付をハイライト
        if (
            year === currentDate.getFullYear() &&
            month === currentDate.getMonth() &&
            i === currentDate.getDate()
        ) {
            dayCell.classList.add('bg-blue-200', 'font-semibold', 'text-blue-700');
        } else {
            dayCell.classList.add('text-gray-700');
            dayCell.style.cursor = 'pointer';
        }

        // 日付番号
        const dayNumber = document.createElement('div');
        dayNumber.textContent = i;
        dayCell.appendChild(dayNumber);

        // スケジュールの抽出
        const matchedSchedules = [];
        for (let x = 0; x < tripSchedule.length; x++) {
            const [startY, startM, startD] = tripSchedule[x][0].split('-').map(Number);
            const [endY, endM, endD] = tripSchedule[x][1].split('-').map(Number);
            const startDate = new Date(startY, startM - 1, startD);
            const endDate = new Date(endY, endM - 1, endD);
            if (thisDate == startDate) {
                matchedSchedules.push({
                    name: tripName[x],
                    url: routeUrls[x],
                    colorClass: color[x % color.length]
                });
            }
        }

        // 展開・非展開を切り替え可能なラッパー
        let expanded = false;
        const wrapper = document.createElement('div');
        wrapper.classList.add('relative');

        function renderScheduleItems() {
            wrapper.innerHTML = '';
            const visibleItems = expanded ? matchedSchedules : matchedSchedules.slice(0, maxVisible);

            visibleItems.forEach(item => {
                const scheduleItem = document.createElement('a');
                scheduleItem.classList.add(
                    item.colorClass,
                    'text-white',
                    'text-xs',
                    'block',
                    'rounded',
                    'px-1',
                    'mt-1',
                    'truncate'
                );
                scheduleItem.href = item.url;
                scheduleItem.textContent = item.name;
                wrapper.appendChild(scheduleItem);
            });

            if (matchedSchedules.length > maxVisible) {
                const toggleBtn = document.createElement('button');
                toggleBtn.classList.add('text-blue-500', 'text-xs', 'underline', 'mt-1');
                toggleBtn.textContent = expanded
                    ? 'close'
                    : `+${matchedSchedules.length - maxVisible} more`;
                toggleBtn.addEventListener('click', () => {
                    expanded = !expanded;
                    renderScheduleItems();
                });
                wrapper.appendChild(toggleBtn);
            }
        }

        renderScheduleItems();
        dayWrapper.appendChild(wrapper);
        dayCell.appendChild(dayWrapper);
        calendarBody.appendChild(dayCell);
    }

    // 末尾の空白（日曜始まりで1週間が揃うように調整）
    const totalCells = firstDayOfWeek + daysInMonth;
    const remainingDays = 7 - (totalCells % 7);
    if (remainingDays < 7) {
        for (let i = 0; i < remainingDays; i++) {
            const emptyCell = document.createElement('div');
            calendarBody.appendChild(emptyCell);
        }
    }
}

function updateMonthYear(year, month) {
    const monthYearElement = document.getElementById('month-year');
    const monthNames = ["Jan.", "Feb.", "Mar.", "Apr.", "May", "Jun.", "Jul.", "Aug.", "Sep.", "Oct.", "Nov.", "Dec."];
    monthYearElement.textContent = `${monthNames[month]}  ${year}`;
}

// DOMContentLoaded->HTML要素が読み込まれると発火
document.addEventListener('DOMContentLoaded', () => {
    let currentYear = new Date().getFullYear();
    let currentMonth = new Date().getMonth();
    updateMonthYear(currentYear, currentMonth);
    generateCalendar(currentYear, currentMonth);
    const prevButton = document.getElementById('prev-month');
    const nextButton = document.getElementById('next-month');
    prevButton.addEventListener('click', () => {
        currentMonth--;
        if (currentMonth < 0) {
            currentMonth = 11;
            currentYear--;
        }
    updateMonthYear(currentYear, currentMonth);
    generateCalendar(currentYear, currentMonth);
    });
    nextButton.addEventListener('click', () => {
        currentMonth++;
        if (currentMonth > 11) {
            currentMonth = 0;
            currentYear++;
        }
        updateMonthYear(currentYear, currentMonth);
        generateCalendar(currentYear, currentMonth);
    });
});
