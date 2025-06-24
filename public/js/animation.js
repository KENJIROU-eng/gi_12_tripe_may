const text = ["Welcome to Tripe@s!",
            "Design your trip",
            "Enjoyably",
            "&",
            "Smoothly"
            ];
document.addEventListener('DOMContentLoaded', () => {
    const container = document.getElementById('animated-text');
    container.innerHTML = '';
    if (container) {
        for (let x = 0; x < text.length; x++){
            setTimeout(() => {
            const div = document.createElement('div');
        // [...text] は文字列を1文字ずつ配列に分解するES6構文
        // forEach で文字とインデックスを取得
        [...text[x]].forEach((char, i) => {
            const span = document.createElement('span');
            div.classList.add('mx-auto', 'mt-10', 'w-full', 'text-center', 'h-96', 'pt-10');
            // \u00A0（ノーブレークスペース）
            span.textContent = char === ' ' ? '\u00A0' : char;  // 空白はノーブレークスペースに
            if (((x == 2 || x == 3 || x == 4 ) && (i == 0)) || (x == 1 && (i == 12 || i == 13 || i == 14 || i == 15)) || (x == 0 && (i == 11 || i == 12 || i == 13 || i == 14 || i == 15 || i == 16 || i == 17 || i == 18))) {
                span.classList.add('opacity-0', 'inline-block', 'text-center', 'mx-auto', 'text-4xl', 'text-yellow-500', 'mt-10');
            }else {
                span.classList.add('opacity-0', 'inline-block', 'text-center', 'mx-auto', 'text-4xl', 'text-white', 'mt-10');    // 初期は非表示
            }
            span.style.textShadow = '5px 5px 5px rgba(0, 0, 0, 0.5)';
            div.appendChild(span);
            container.appendChild(div);

        //   少し遅延させてからアニメーション開始
        setTimeout(() => {
            span.classList.remove('opacity-0');
            span.classList.add('animate-fadeIn');
        }, i * 100);  // 100msごとに文字が表示される
        });
        }, x * 2000)
        }
    } else {
        console.warn('⚠️ #animated-text 要素が見つかりません');
    }
});

