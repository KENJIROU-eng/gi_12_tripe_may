// async とは？
// async は 「非同期処理を行う関数」を定義するキーワードです。
// この関数の中では await を使って非同期処理（例：fetch() や API 呼び出し）を「一時停止」して待つことができるようになります。
// これはイベントリスナーから受け取る イベントオブジェクト です。
// たとえば submit イベントの中では、e.preventDefault() でフォームのデフォルト送信を止めるなどに使われます。

document.getElementById('post-form').addEventListener('submit', async (e) => {
    //submitされたときにpageの更新を阻止
    e.preventDefault();
    const title = document.getElementById('title').value;

    await fetch('/post/broadcast/realtime', {
        method: 'POST',
        headers: {
        // Content-Type: application/json によって、送るデータがJSON形式であることを指定。
        // LaravelのCSRF保護のために、metaタグからトークンを取得して送信。
        // LaravelではPOST送信時に X-CSRF-TOKEN が必須です。
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ title })
    });

    const data = await response.json();

    if (data.success) {
        alert('送信成功！');
        // 例えばフォームをリセットしたい場合
        document.getElementById('post-form').reset();
    } else {
        alert('送信に失敗しました。');
    }
});

import Echo from 'laravel-echo';
import Pusher from 'pusher-js';


// Pusherライブラリをwindowにセット。Laravel EchoはPusherを使って通信します
window.Pusher = Pusher;

// Laravel Echoの初期化。ここでPusherの接続設定を指定
window.Echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
    forceTLS: true
});

// 'posts' チャンネルを購読し、
// 'NewPostCreated' イベントをリッスン（待ち受け）
// イベント発火時にコールバックを呼び出す
Echo.channel('posts')
    //.listen → 'NewPostCreated' というイベントが送られてきたら、コールバック関数（(e) => {...}）を実行する。
    // コールバック関数の引数 e は、サーバーが broadcastWith() で送ったデータ（例：id, title）を受け取る。
    .listen('NewPostCreated', (e) => {
        const list = document.getElementById('post-list');
        list.innerHTML += `<li>${e.title}</li>`;
    });

    // async とは？
    // async は 「非同期処理を行う関数」を定義するキーワードです。
    // この関数の中では await を使って非同期処理（例：fetch() や API 呼び出し）を「一時停止」して待つことができるようになります。
    // これはイベントリスナーから受け取る イベントオブジェクト です。
    // たとえば submit イベントの中では、e.preventDefault() でフォームのデフォルト送信を止めるなどに使われます。
    document.getElementById('post-form').addEventListener('submit', async (e) => {
        //submitされたときにpageの更新を阻止
        e.preventDefault();
        const title = document.getElementById('title').value;

        const response = await fetch('/post/broadcast/realtime', {
            method: 'POST',
            headers: {
            // Content-Type: application/json によって、送るデータがJSON形式であることを指定。
            // LaravelのCSRF保護のために、metaタグからトークンを取得して送信。
            // LaravelではPOST送信時に X-CSRF-TOKEN が必須です。
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ title })
        });

        const data = await response.json();

        if (data.success) {
            alert('送信成功！');
            // 例えばフォームをリセットしたい場合
            document.getElementById('post-form').reset();
        } else {
            alert('送信に失敗しました。');
        }
    });

    import Echo from 'laravel-echo';
    import Pusher from 'pusher-js';

    // Pusherライブラリをwindowにセット。Laravel EchoはPusherを使って通信します
    window.Pusher = Pusher;

    // console.log('Initializing Echo...');

    // Laravel Echoの初期化。ここでPusherの接続設定を指定
    window.Echo = new Echo({
        broadcaster: 'pusher',
        key: '32433e54b1e480384a19',
        cluster: 'ap3',
        forceTLS: false,
        authEndpoint: "/broadcasting/auth",
        auth: {
            headers: {
                Authorization: `Bearer ${yourAccessToken}`
            }
        }
    });

    console.log('Echo initialized:', window.Echo);

    window.Echo.connector.pusher.connection.bind('connected', () => {
        console.log('✅ Pusher に接続されました');
    });

    window.Echo.connector.pusher.connection.bind('connected', () => {
        console.log('✅ Echo connected to Pusher!');
    });

    // console.log('forceTLS:', false);

    // 'posts' チャンネルを購読し、
    // 'NewPostCreated' イベントをリッスン（待ち受け）
    // イベント発火時にコールバックを呼び出す
    //.listen → 'NewPostCreated' というイベントが送られてきたら、コールバック関数（(e) => {...}）を実行する。
    // コールバック関数の引数 e は、サーバーが broadcastWith() で送ったデータ（例：id, title）を受け取る。
    // Echo.channel('posts') の呼び出しで、Pusher上の 'posts' チャンネルに参加（サブスクライブ）する
    // 内部的にPusherの subscribe('posts') を呼び出している
    // console.log('👀 .listen実行中...');
    window.Echo.channel('posts')
        .listen('.NewPostCreated', (e) => {
            const list = document.getElementById('post-list');
            const li = document.createElement('li');
            li.textContent = e.title;
            list.appendChild(li);
            console.log('📡 受信データ:', e);
            alert('リアルタイム通知受信！タイトル: ' + e.title);
        });

    // window.Pusher.logToConsole = true;

    window.Echo.channel('posts')
    .listenToAll((event, data) => {
        console.log('🔥 イベント受信:', event, data);
        alert(`イベント名: ${event}\nデータ: ${JSON.stringify(data)}`);
    });
