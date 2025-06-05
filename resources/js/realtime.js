    import Echo from 'laravel-echo';
    import Pusher from 'pusher-js';
    document.addEventListener('DOMContentLoaded', function () {
    Pusher.logToConsole = true;
    // Pusherライブラリをwindowにセット。Laravel EchoはPusherを使って通信します
    // ブラウザ上で動作する JavaScript における グローバルオブジェクト。
    // つまり、window.○○ とすると、どこからでもアクセスできる変数になります。
    window.Pusher = Pusher;
    // Laravel Echoの初期化。ここでPusherの接続設定を指定
    window.Echo = new Echo({
        broadcaster: 'pusher',
        key: 'ffddfa3bd363d3272b37',
        cluster: 'ap1',
        forceTLS: true,
        withCredentials: true,
    });
    console.log('Echo initialized:', window.Echo);
    window.Echo.connector.pusher.connection.bind('connected', () => {
        console.log('✅ Pusher に接続されました');
        window.Echo.channel('posts')
        // 直接PusherAPIから取ってくる
        const channelName = 'posts';
        const channel = window.Echo.channel(channelName);
        channel.listen('.NewPostCreated', (data) => {
        // console.log('📥 投稿受信:', data);
        // alert(`新しい投稿: ${data.title}`);
        // const privatechannelName = 'posts.155';
        // const privatechannel = window.Echo.private(privatechannelName);
    });
    });
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
            // alert('送信成功！');
            // 例えばフォームをリセットしたい場合
            document.getElementById('post-form').reset();
            const postId = data.post_id;
            const privatechannelName = `posts.${postId}`;
            const privatechannel = window.Echo.private(privatechannelName);
            // EchoのlistenToAllで確認
            privatechannel.subscribed(() => {
                console.log("✅ 購読成功");
                privatechannel.listenToAll((eventName, data) => {
                    console.log('🟢 listenToAll:', eventName, data);
                    });
                privatechannel.listen('.NewPostCreated', (e) => {
                const list = document.getElementById('post-list');
                const li = document.createElement('li');
                li.textContent = e.title;
                list.appendChild(li);
                console.log('📡 受信データ:', e);
                // alert('リアルタイム通知受信！タイトル: ' + e.title);
                // privatechannel.listenToAll((eventName, data) => {
                // console.log('🟢 listenToAll:', eventName, data);
                });

            });
            (async () => {
                const response = await fetch('/post/broadcast/event', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content // CSRF対策
                    },
                    body: JSON.stringify({ postId })
                })

                })();
                } else {
                    alert('送信に失敗しました。');
                }
            });
    });

    