import './bootstrap';
import './chat';
import Alpine from 'alpinejs';
// import './realtime';

window.Alpine = Alpine;

Alpine.start();

// document.addEventListener('DOMContentLoaded', () => {
//     Alpine.start();

//     const check = setInterval(() => {
//         const testEl = document.getElementById('alpine-test');
//         if (testEl?.__x?.$data) {
//             console.log('🎉 Alpine x-data detected:', testEl.__x.$data);
//             clearInterval(check); // 監視終了
//         } else {
//             console.log('⏳ Waiting for Alpine...');
//         }
//     }, 100); // 100msごとにチェック
// });

// main.js
// import { login } from './auth.js';
// import { initEcho } from './realtime.js';

// フォーム送信時の例
// document.getElementById('login').addEventListener('submit', function (e) {
//     e.preventDefault();

//     const email = e.target.email.value;
//     const password = e.target.password.value;

//     console.log('login 実行前');
//     login(email, password)
//     .then(token => {
//         if (!token) {
//         console.error('トークンがありません');
//         } else {
//         console.log(localStorage.getItem('api_token'));
//         initEcho(token);
//         }
//     })
//     .catch(err => {
//         console.error('ログイン失敗', err);
//     });
// });
//laravel Echo
// import Echo from 'laravel-echo';
// import Pusher from 'pusher-js';

// window.Pusher = Pusher;

// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: import.meta.env.VITE_PUSHER_APP_KEY,
//     cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
//     forceTLS: true
// });
