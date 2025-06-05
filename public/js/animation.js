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
            div.classList.add('mx-auto', 'mt-10', 'w-full', 'text-center', 'h-96', 'my-auto');
            // \u00A0（ノーブレークスペース）
            span.textContent = char === ' ' ? '\u00A0' : char;  // 空白はノーブレークスペースに
            span.classList.add('opacity-0', 'inline-block', 'text-center', 'mx-auto', 'text-4xl');    // 初期は非表示
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

