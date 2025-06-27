// retrieve data from itinerary
const tripSchedule = window.appData.tripSchedule;
const tripName = window.appData.tripName;
const tripId = window.appData.tripId;
const color = ['bg-amber-500', 'bg-sky-500', 'bg-purple-500', 'bg-pink-500', 'bg-green-500', 'bg-emerald-500', 'bg-violet-500'];
// グローバルにslotMapを定義（予定ごとのスロット位置を固定）
const slotMap = {}; // 旅行単位でslotを記憶（例: '2025-07-01-TripA': 0）

function generateCalendar(year, month) {
    const calendarBody = document.getElementById('calendar-body');
    calendarBody.innerHTML = '';
    const firstDayOfMonth = new Date(year, month, 1);
    const lastDayOfMonth = new Date(year, month + 1, 0);
    const daysInMonth = lastDayOfMonth.getDate();
    const firstDayOfWeek = firstDayOfMonth.getDay();
    const maxVisible = 3;

    for (let i = 0; i < firstDayOfWeek; i++) {
        calendarBody.appendChild(document.createElement('div'));
    }

    for (let i = 1; i <= daysInMonth; i++) {
        const dayCell = document.createElement('div');
        dayCell.classList.add('py-2', 'text-center', 'block', 'text-sm');

        const dayWrapper = document.createElement('div');
        const dayNumber = document.createElement('div');
        dayNumber.textContent = i;
        dayCell.appendChild(dayNumber);

        const thisDate = new Date(year, month, i);
        const matchedSchedules = [];

        for (let x = 0; x < tripSchedule.length; x++) {
            const [sy, sm, sd] = tripSchedule[x][0].split('-').map(Number);
            const [ey, em, ed] = tripSchedule[x][1].split('-').map(Number);
            const start = new Date(sy, sm - 1, sd);
            const end = new Date(ey, em - 1, ed);

            if (thisDate >= start && thisDate <= end) {
                const key = `${tripSchedule[x][0]}-${tripName[x]}-${tripId[x]}`;
                matchedSchedules.push({
                    name: tripName[x],
                    url: routeUrls[x],
                    colorClass: color[x % color.length],
                    start: start.toDateString(),
                    key: key,
                    slot: null,
                    localSlot: null
                });
            }
        }

        // スロットを割り当て
        //重複なしのarray ->set
        const usedSlots = new Set();
        let slotCounter = 0;

        matchedSchedules.forEach(m => {
            if (slotMap[m.key] !== undefined) {
                m.localSlot = slotMap[m.key];
            } else {
                // 日単位で詰めて空いてる番号から割り当てる
                while (usedSlots.has(slotCounter)) {
                    slotCounter++;
                }
                m.localSlot = slotCounter;
                slotMap[m.key] = slotCounter;
            }
            usedSlots.add(m.localSlot);
        });

        matchedSchedules.sort((a, b) => a.localSlot - b.localSlot);

        for (let s = 0; s < maxVisible; s++) {
            const item = matchedSchedules.find(m => m.localSlot === s);
            const slotDiv = document.createElement('a');
            slotDiv.classList.add('block', 'px-1', 'mt-1', 'text-xs');
            if (item) {
                slotDiv.classList.add(item.colorClass, 'text-white', 'truncate');
                slotDiv.href = item.url;
                slotDiv.textContent = (thisDate.toDateString() === item.start) ? item.name : '\u00a0';
            } else {
                slotDiv.classList.add('invisible');
                slotDiv.textContent = '\u00a0';
            }
            dayWrapper.appendChild(slotDiv);
        }

        const hiddenItems = matchedSchedules.filter(m => m.localSlot >= maxVisible);

        if (hiddenItems.length > 0) {
            const moreBtn = document.createElement('button');
            moreBtn.textContent = `+${hiddenItems.length} more`;
            moreBtn.classList.add('text-xs', 'text-blue-500', 'mt-1', 'hover:underline');

            const hiddenContainer = document.createElement('div');
            hiddenContainer.style.display = 'none';

            hiddenItems.forEach(item => {
                const div = document.createElement('a');
                div.href = item.url;
                div.textContent = item.name;
                div.classList.add(item.colorClass, 'text-white', 'px-1', 'block', 'mt-1', 'text-xs');
                hiddenContainer.appendChild(div);
            });

            moreBtn.addEventListener('click', () => {
                const isVisible = hiddenContainer.style.display === 'block';
                hiddenContainer.style.display = isVisible ? 'none' : 'block';
                moreBtn.textContent = isVisible ? `+${hiddenItems.length} more` : '▲ Close';
            });

            dayWrapper.appendChild(moreBtn);
            dayWrapper.appendChild(hiddenContainer);
        }

        dayCell.appendChild(dayWrapper);
        calendarBody.appendChild(dayCell);
    }

    const remainingDays = 7 - ((daysInMonth + firstDayOfWeek) % 7);
    if (remainingDays < 7) {
        for (let i = 0; i < remainingDays; i++) {
            calendarBody.appendChild(document.createElement('div'));
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

// const tripSchedule = window.appData.tripSchedule;
// const tripName = window.appData.tripName;
// const color = ['bg-amber-500', 'bg-sky-500', 'bg-purple-500', 'bg-pink-500', 'bg-green-500', 'bg-emerald-500', 'bg-violet-500'];

// function generateCalendar(year, month) {
//     const calendarBody = document.getElementById('calendar-body');
//     calendarBody.innerHTML = '';

//     const firstDayOfMonth = new Date(year, month, 1);
//     const lastDayOfMonth = new Date(year, month + 1, 0);
//     const daysInMonth = lastDayOfMonth.getDate();
//     const firstDayOfWeek = firstDayOfMonth.getDay();
//     const currentDate = new Date();

//     const maxVisible = 3;

//     for (let i = 0; i < firstDayOfWeek; i++) {
//         const emptyCell = document.createElement('div');
//         calendarBody.appendChild(emptyCell);
//     }

//     for (let i = 1; i <= daysInMonth; i++) {
//         const dayCell = document.createElement('div');
//         dayCell.classList.add('py-2', 'text-center', 'px-1', 'max-h-24', 'block');

//         const dayWrapper = document.createElement('div');
//         const thisDate = new Date(year, month, i);

//         if (
//             year === currentDate.getFullYear() &&
//             month === currentDate.getMonth() &&
//             i === currentDate.getDate()
//         ) {
//             dayCell.classList.add('bg-blue-200', 'font-semibold', 'text-blue-700');
//         } else {
//             dayCell.classList.add('text-gray-700');
//             dayCell.style.cursor = 'pointer';
//         }

//         const dayNumber = document.createElement('div');
//         dayNumber.textContent = i;
//         dayCell.appendChild(dayNumber);

//         const matchedSchedules = [];
//         for (let x = 0; x < tripSchedule.length; x++) {
//             const [startY, startM, startD] = tripSchedule[x][0].split('-').map(Number);
//             const [endY, endM, endD] = tripSchedule[x][1].split('-').map(Number);
//             const startDate = new Date(startY, startM - 1, startD);
//             const endDate = new Date(endY, endM - 1, endD);
//             if (thisDate == startDate) {
//                 matchedSchedules.push({
//                     name: tripName[x],
//                     url: routeUrls[x],
//                     colorClass: color[x % color.length]
//                 });
//             }
//         }

//         let expanded = false;
//         const wrapper = document.createElement('div');
//         wrapper.classList.add('relative');

//         function renderScheduleItems() {
//             wrapper.innerHTML = '';
//             const visibleItems = expanded ? matchedSchedules : matchedSchedules.slice(0, maxVisible);

//             visibleItems.forEach(item => {
//                 const scheduleItem = document.createElement('a');
//                 scheduleItem.classList.add(
//                     item.colorClass,
//                     'text-white',
//                     'text-xs',
//                     'block',
//                     'rounded',
//                     'px-1',
//                     'mt-1',
//                     'truncate'
//                 );
//                 scheduleItem.href = item.url;
//                 scheduleItem.textContent = item.name;
//                 wrapper.appendChild(scheduleItem);
//             });

//             if (matchedSchedules.length > maxVisible) {
//                 const toggleBtn = document.createElement('button');
//                 toggleBtn.classList.add('text-blue-500', 'text-xs', 'underline', 'mt-1');
//                 toggleBtn.textContent = expanded
//                     ? 'close'
//                     : `+${matchedSchedules.length - maxVisible} more`;
//                 toggleBtn.addEventListener('click', () => {
//                     expanded = !expanded;
//                     renderScheduleItems();
//                 });
//                 wrapper.appendChild(toggleBtn);
//             }
//         }

//         renderScheduleItems();
//         dayWrapper.appendChild(wrapper);
//         dayCell.appendChild(dayWrapper);
//         calendarBody.appendChild(dayCell);
//     }

//     const totalCells = firstDayOfWeek + daysInMonth;
//     const remainingDays = 7 - (totalCells % 7);
//     if (remainingDays < 7) {
//         for (let i = 0; i < remainingDays; i++) {
//             const emptyCell = document.createElement('div');
//             calendarBody.appendChild(emptyCell);
//         }
//     }
// }

// function updateMonthYear(year, month) {
//     const monthYearElement = document.getElementById('month-year');
//     const monthNames = ["Jan.", "Feb.", "Mar.", "Apr.", "May", "Jun.", "Jul.", "Aug.", "Sep.", "Oct.", "Nov.", "Dec."];
//     monthYearElement.textContent = `${monthNames[month]}  ${year}`;
// }

// document.addEventListener('DOMContentLoaded', () => {
//     let currentYear = new Date().getFullYear();
//     let currentMonth = new Date().getMonth();
//     updateMonthYear(currentYear, currentMonth);
//     generateCalendar(currentYear, currentMonth);
//     const prevButton = document.getElementById('prev-month');
//     const nextButton = document.getElementById('next-month');
//     prevButton.addEventListener('click', () => {
//         currentMonth--;
//         if (currentMonth < 0) {
//             currentMonth = 11;
//             currentYear--;
//         }
//     updateMonthYear(currentYear, currentMonth);
//     generateCalendar(currentYear, currentMonth);
//     });
//     nextButton.addEventListener('click', () => {
//         currentMonth++;
//         if (currentMonth > 11) {
//             currentMonth = 0;
//             currentYear++;
//         }
//         updateMonthYear(currentYear, currentMonth);
//         generateCalendar(currentYear, currentMonth);
//     });
// });
