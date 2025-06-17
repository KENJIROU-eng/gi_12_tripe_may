// retrieve data from itinerary
const tripSchedule = window.appData.tripSchedule;
const tripName = window.appData.tripName;
const color = ['bg-green-500', 'bg-green-600', 'bg-sky-400', 'bg-blue-500', 'bg-violet-500', 'bg-pink-500', 'bg-red-600'];

function generateCalendar(year, month) {
        const calendarBody = document.getElementById('calendar-body'); // 日付を表示する要素
        calendarBody.innerHTML = ''; // 既存の日付をクリア
        // new Date->日付を取得する関数
        // ex.) new Date(year, month, 1)->日付が1日の日を取得
        // getDay()->曜日を取得（0~6までの数字で割り当てられる）
        // getDate()->日にちを取得
        const firstDayOfMonth = new Date(year, month, 1);
        const lastDayOfMonth = new Date(year, month + 1, 0);
        const daysInMonth = lastDayOfMonth.getDate();
        const firstDayOfWeek = firstDayOfMonth.getDay(); // 0 (日) から 6 (土)
        let date = 1;
        // カレンダーの先頭の空白セルを追加 (前月の末日)
        for (let i = 0; i < firstDayOfWeek; i++) {
            // createElement('')->divを作成する
            const emptyCell = document.createElement('div');
            // appendChild()->子要素の挿入
            // 親要素.appendChild(子要素);
            // emptyCell.classList.add('h-6', 'bg-green-500');
            calendarBody.appendChild(emptyCell);
        }
        // 今月の日付を追加
        // for (let i = 1; i <= daysInMonth; i++) {
        //     const dayCell = document.createElement('div');
        //     dayCell.classList.add('py-2', 'text-center', 'px-1');
        //     const dayScheduled = document.createElement('a');
        //     dayScheduled.classList.add('text-sm', 'text-center', 'my-auto', 'text-white');
        //     const currentDate = new Date();

            // getFullYear()->西暦を取得
            // getMonth()->月を取得、0->1月、11->12月
            // if (year === currentDate.getFullYear() && month === currentDate.getMonth() && i === currentDate.getDate()) {
            //     dayCell.classList.add('bg-blue-200', 'font-semibold', 'text-blue-700'); // 今日の日付のスタイル
            // } else {
            //     dayCell.classList.add('text-gray-700'); // 通常の日のスタイル
                // マウスを充てたときの外観
            //     dayCell.style.cursor = 'pointer';
            // }
            // textContent->テキストの部分を参照
            // dayCell.textContent = i;
            // for (let x = 0; x < tripSchedule.length; x++) {
                // const startDate = new Date(tripSchedule[x][0]);
                // const endDate = new Date(tripSchedule[x][1]);
            //     const thisDate = new Date(year, month , i);
            //     const [startY, startM, startD] = tripSchedule[x][0].split('-').map(Number);
            //     const [endY, endM, endD] = tripSchedule[x][1].split('-').map(Number);
            //     const startDate = new Date(startY, startM - 1, startD); // 月は0-index
            //     const endDate = new Date(endY, endM - 1, endD);
            // if (thisDate >= startDate && thisDate <= endDate) {
            //     dayScheduled.classList.add('bg-green-500', 'block');
            //     dayScheduled.href = routeUrls[x];
                // if (thisDate === startDate) {
                    // dayScheduled.textContent = tripName[x];
                // }
            // }

        // }
        // dayCell.appendChild(dayScheduled);
        // calendarBody.appendChild(dayCell);
        for (let i = 1; i <= daysInMonth; i++) {
        const dayCell = document.createElement('div');
        dayCell.classList.add('py-2', 'text-center', 'px-1', 'max-h-20', 'overflow-y-auto', 'block');

        const dayWrapper = document.createElement('div'); // スケジュール全体を囲む
        const currentDate = new Date();

        if (year === currentDate.getFullYear() && month === currentDate.getMonth() && i === currentDate.getDate()) {
            dayCell.classList.add('bg-blue-200', 'font-semibold', 'text-blue-700');
        } else {
            dayCell.classList.add('text-gray-700');
            dayCell.style.cursor = 'pointer';
        }

        // 日付の番号を表示
        const dayNumber = document.createElement('div');
        dayNumber.textContent = i;
        dayCell.appendChild(dayNumber);

        // スケジュールを探す
        const thisDate = new Date(year, month, i);
        for (let x = 0; x < tripSchedule.length; x++) {
            const [startY, startM, startD] = tripSchedule[x][0].split('-').map(Number);
            const [endY, endM, endD] = tripSchedule[x][1].split('-').map(Number);
            const startDate = new Date(startY, startM - 1, startD);
            const endDate = new Date(endY, endM - 1, endD);

            if (thisDate >= startDate && thisDate <= endDate) {
                const scheduleItem = document.createElement('a');
                const scheduleColor = x % 7;
                scheduleItem.classList.add(color[scheduleColor], 'text-white', 'text-xs', 'block', 'rounded', 'px-1', 'mt-1');
                scheduleItem.href = routeUrls[x];
                scheduleItem.textContent = tripName[x];
                dayWrapper.appendChild(scheduleItem); // 複数追加
            }
        }

        dayCell.appendChild(dayWrapper); // 予定を追加
        calendarBody.appendChild(dayCell); // 最後にDOMに追加
        date++;
        }

        // カレンダーの末尾の空白セルを追加 (翌月の月初)
        const remainingDays = 7 - (((daysInMonth - 1) % 7)+ firstDayOfWeek + 1);
        if (remainingDays > 0 && remainingDays < 7) {
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
