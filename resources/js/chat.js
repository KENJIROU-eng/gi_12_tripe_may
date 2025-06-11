//送信者のための非同期処理（fetch）
//フォームの送信処理（チャットメッセージ送信）を担当
//ユーザーがフォームからメッセージを送信したときに、ページをリロードせずにjsで非同期非同期（Ajax）でサーバーへ送信するための処理

//1.DOMの読み込みを待つ
document.addEventListener('DOMContentLoaded', () => { //ページ内のHTML要素が全て読み込まれた後に実行されるようにする

    //2.フォームと入力要素の取得
    const form = document.getElementById('chat-form');
    const messageInput = form.querySelector('input[name="message"]');
    const imageInput = form.querySelector('input[name="image"]');

    //3.フォーム送信イベントをキャッチ
    form.addEventListener('submit', async (e) => {
        e.preventDefault(); //フォームのsubmitイベントを止めて、ページリロードを防ぐ
                            //これによりJavaScriptで非同期に処理を行う準備が整う

        //4.FormDataオブジェクトを生成
        const formData = new FormData(form); //フォーム内のすべてのデータをFormDataとしてまとめる

        try {
            const response = await fetch('/chat/send', { //サーバー（/chat/send）へフォームデータをPOST
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                }, //X-CSRF-TOKEN をヘッダーに含めることで、Laravel側のCSRF保護に対応
                body: formData,
            });

            if (!response.ok) {
                const text = await response.text(); // HTML or error JSON
                console.error('エラーレスポンス:', text);
                throw new Error(`サーバーエラー: ${response.status}\n${text}`);
            }

        const data = await response.json();
        if (data.success) {
            alert('送信成功！');
            // 例えばフォームをリセットしたい場合
            document.getElementById('chat-form').reset();
        } else {
            alert('送信に失敗しました。');
        }


    }
        catch (err) {
            console.error('通信エラー:', err);
            alert('通信error');
        }
    });
});

//window: jsのすべてのオブジェクトの親を指すオブジェクト。（Echoやpusherをグローバルで使えるようにするため）
//document: jsのwindowの一部で、「HTMLドキュメント全体」を表す。（ページの要素（フォーム、div、inputなど）にアクセス・操作するため）
//response: fetch()関数が返すオブジェクト、サーバーから帰ってきた応答全体を含む。（サーバーが返したJSONやHTTPステータスなどを受け取る）
//messageElement: jsで動的に作成された <div> 要素、新しいチャットメッセージを表示する箱。（受信したメッセージをHTMLとして表示するための要素を作る）
//messagesDiv: <div id="messages" ...> のように、チャット全体の表示領域を表すDOM要素。(メッセージが届いたら、この中に messageElement を追加して表示)
//messageContent: 受信したメッセージのHTML部分を格納する変数。(テキストや画像など、受信した内容を組み立てるためのHTML文字列を一時的に格納)
//e: イベントに関する情報を持つオブジェクト（例：submit や WebSocket での message.sent）(イベントの中身を取得（例：どのキーが押された、どんなデータが送られてきたかなど）
//bind: Pusherライブラリが提供するメソッドで、特定の「接続イベント」に対して処理を結びつける。(Pusherへの接続状態を確認するイベントハンドラを設定する。)
//listen: Laravel Echo のメソッド。サーバー側でブロードキャストされたイベントを「受信」する。(特定のイベント（例：.message.sent）が来たときに処理を実行)
//try:「エラーが出るかもしれない処理を安全に試す」ためのブロック。（非同期通信（fetch）で エラーが出てもアプリが止まらないようにする、成功すれば次の処理に進み、失敗したら catch に処理を渡す）
//catch: try ブロックで発生したエラーを キャッチして処理 する部分。（通信エラーやJSON変換の失敗などをハンドリングして、画面にエラー表示やログを出せるようにする）
//let: 再代入が可能な変数を定義するためのキーワード（varより安全、constより柔軟）。(メッセージ内容のHTML組み立てに使われる変数や、一時的な値の格納)
//DOM: Document Object Model の略で、HTMLページをツリー状のJavaScriptオブジェクトとして扱える仕組み。（HTMLの各要素（フォーム、div、inputなど）を JavaScriptで取得・変更・追加 できるようにする）









//受信者のためのリアルタイム表示（Echo）
//リアルタイム受信処理（メッセージ受信時の表示）を担当
//サーバーからpusher経由でリアルタイムに送信されたメッセージをブラウザで即座に受け取って、チャット画面に動的に表示する処理

//1. ライブラリのインポート
import Echo from 'laravel-echo'; //Laravelのリアルタイム通信（WebSocket）を扱えるJSライブラリ
import Pusher from 'pusher-js'; //Pusherのクライアントライブラリ（WebSocket通信を担当）

// const channel = pusher.subscribe('chat');
// channel.bind('message-sent', function(data) {
//     console.log('Received:', data.message); // Make sure this logs
// });

window.Pusher = Pusher; //Laravel Echoが内部的に Pusher を使うため

//2.チャットが属するグループのIDをHTMLから取得
const groupId = document.getElementById('messages')?.dataset.groupId;
const myUserId = document.body.dataset.userId;
console.log(myUserId);
//3.IDが取れたらWebSocketを開始
if (groupId && myUserId) {

    //4.Echo（WebSocket）の設定、LaravelEchoでWebSocket接続を確立
    window.Echo = new Echo({
        broadcaster: 'pusher',  //Pusher を使って Echo を初期化
        key: import.meta.env.VITE_PUSHER_APP_KEY, //.env ファイルに定義されたキー・クラスタ情報を使う
        cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
        useTLS:true,
        // forceTLS: false, //HTTPS経由でWebSocket通信を強制する
        // encrypted: true, //暗号化通信（Pusherでは必須に近い）
    });

    //pusherへの接続状態を確認（デバック用）
    window.Echo.connector.pusher.connection.bind('connected', () => {
            console.log(':チェックマーク_緑: Pusher に接続されました');
        });
        window.Echo.connector.pusher.connection.bind('connected', () => {
            console.log(':チェックマーク_緑: Echo connected to Pusher!');
        });

    //5.グループチャネルを購読して受信を待つ
    window.Echo.private(`group.${groupId}`) //group.{id} という名前のプライベートチャンネルを購読
        .listen('.message.sent', (e) => { //.message.sent というイベント名がブロードキャストされるのをリッスン、メッセージをチャットに追加する処理

            //6.受信データでメッセージ要素を作成
            console.log('受信:', e); //コンソールに受信内容を表示（デバッグ用）
            console.log("chat.js is loaded");
            console.log("Echo object:", window.Echo);

            const messagesDiv = document.getElementById('messages'); //チャットメッセージの親要素を取得
            const isMine = parseInt(myUserId) === e.user_id; //メッセージの送信者が自分かどうかを判定

            //7.メッセージ内容をHTMLに組み立て
            const messageElement = document.createElement('div');//新しいメッセージを追加するための div を用意
            let messageContent = '';

            //テキスト組み込み
            if (e.message && e.message.text) {
                messageContent += `<div>${e.message.text}</div>`;
            }

            //画像組み込み
            if (e.image_url) {
                messageContent += `<img src="${e.image_url}" class="mt-2 max-w-xs rounded-lg">`;
            }

            //投稿時間の表示
            messageContent += `<div class="text-xs text-right mt-1 text-gray-400">${e.time}</div>`;

            //8. 自分 or 他人の表示スタイルに合わせて描画
            // messageElement.innerHTML = `
            //     <div class="flex ${isMine ? 'justify-end' : 'justify-start'}">
            //         <div class="${isMine ? 'bg-green-300' : 'bg-white'} rounded-2xl p-3 max-w-[70%] shadow">
            //             ${messageContent}
            //         </div>
            //     </div>
            // `;
            if (isMine) {
                messageElement.innerHTML = `
                    <div class="flex justify-end">
                        <div class="bg-green-300 rounded-2xl p-3 max-w-[70%] shadow">
                            ${messageContent}
                        </div>
                    </div>
                `;
            } else {
                messageElement.innerHTML = `
                    <div class="flex items-start space-x-2">
                        <img src="${e.user.avatar_url ?? '/images/user.png'}" class="w-8 h-8 rounded-full mt-1" />
                        <div>
                            <div class="text-sm text-gray-600 font-medium">${e.user.name}</div>
                            <div class="bg-white rounded-2xl p-3 max-w-[70%] shadow">
                                ${messageContent}
                            </div>
                        </div>
                    </div>
                `;
            }
            //自分のメッセージは右寄せ（緑背景）、他人のは左寄せ（白背景）にする

            //DOMに追加してスクロール
            messagesDiv.appendChild(messageElement); //メッセージをチャットに追加
            messagesDiv.scrollTop = messagesDiv.scrollHeight; //チャットのスクロールを一番下に自動で移動
        });

        window.Echo.private(`group.${groupId}`)
            .listenToAll((event, data) => {
                console.log(':火: イベント受信:', event, data);
                alert(`イベント名: ${event}\nデータ: ${JSON.stringify(data)}`);
            });
}

