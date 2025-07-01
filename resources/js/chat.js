
//textareaの設定
document.addEventListener("DOMContentLoaded", () => {
    const textarea = document.getElementById('message-input');

    if (textarea) {
        textarea.addEventListener('input', () => {
            textarea.style.height = 'auto';
            const maxHeight = parseInt(getComputedStyle(textarea).lineHeight) * 5;
            textarea.style.height = Math.min(textarea.scrollHeight, maxHeight) + 'px';
            textarea.style.overflowY = textarea.scrollHeight > maxHeight ? 'auto' : 'hidden';
        });
    }
});
//送信者のための非同期処理（fetch）
//フォームの送信処理（チャットメッセージ送信）を担当
//ユーザーがフォームからメッセージを送信したときに、ページをリロードせずにjsで非同期非同期（Ajax）でサーバーへ送信するための処理


//1.DOMの読み込みを待つ
document.addEventListener('DOMContentLoaded', () => { //ページ内のHTML要素が全て読み込まれた後に実行されるようにする

    //2.フォームと入力要素の取得
    const form = document.getElementById('chat-form');
    //06−27追加
    const imageInput = document.getElementById('image-upload');
    const textarea = document.getElementById('message-input');
    const sendBtn = document.getElementById('send-btn');
    // const messageInput = form.querySelector('input[name="message"]');
    // const imageInput = form.querySelector('input[name="image"]');

    //send-btn 無効化
    function updateSendButton() {
        const hasText = textarea.value.trim().length > 0;
        const hasImage = imageInput.files.length > 0;
        sendBtn.disabled = !(hasText || hasImage);
    }

    textarea.addEventListener('input', updateSendButton);
    imageInput.addEventListener('change', updateSendButton);




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

            if (data.mode === 'edit') {
                location.reload();
            }else {
                // alert('送信成功！');
                // 例えばフォームをリセットしたい場合
                document.getElementById('chat-form').reset();
                //今日追加したやつ27/06

            // alert('送信成功！');
            // 例えばフォームをリセットしたい場合
            document.getElementById('chat-form').reset();

            textarea.value = '';
            textarea.style.height = 'auto';
            updateSendButton();

                const messagesDiv = document.getElementById('messages');
                if (messagesDiv) {
                    messagesDiv.scrollTop = messagesDiv.scrollHeight;
                }
            }
        } else {
            alert('送信に失敗しました。');
        }

    }
        catch (err) {
            console.error('通信エラー:', err);
            alert('通信error');
        }
    });

    textarea.value = '';
    textarea.style.height = 'auto';
    sendBtn.disabled = true;

    if (imageInput) {
        imageInput.addEventListener('change', () => {
            if (imageInput.files.length > 0) {
                form.dispatchEvent(new Event('submit', { cancelable: true }));
            }
        });
    }
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

// HTMLエスケープ用関数
function escapeHtml(unsafe) {
    return unsafe
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#039;");
}

// nl2br関数
function nl2br(str) {
    if (typeof str !== "string") return "";
    return str.replace(/\r?\n/g, '<br>');
}


//2.チャットが属するグループのIDをHTMLから取得
const groupId = document.getElementById('messages')?.dataset.groupId;
const myUserId = document.body.dataset.userId;
const groupIds = window.appData.groupIds;
const length = groupIds.length;

const messagesDiv = document.getElementById('messages');
if (messagesDiv) {
    messagesDiv.scrollTop = messagesDiv.scrollHeight;
}

//4.Echo（WebSocket）の設定、LaravelEchoでWebSocket接続を確立
window.Echo = new Echo({
    broadcaster: 'pusher',  //Pusher を使って Echo を初期化
    key: import.meta.env.VITE_PUSHER_APP_KEY, //.env ファイルに定義されたキー・クラスタ情報を使う
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
    useTLS:true,
    withCredentials: true,
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

// for notification
//notificationの設定

function unlockAudio() {
    const dummy = new Audio('/sounds/maou_se_onepoint23.mp3');
    dummy.volume = 0;
    dummy.play().then(() => {
        localStorage.setItem(`audioUnlocked_user_${myUserId}`, '1');
        console.log('✅ ユーザー操作で再生許可を取得 & 保存');
        hideModal();
    }).catch(err => {
        console.warn('❌ 音声再生許可取得失敗:', err);
    });
}

function hideModal() {
    document.getElementById('audio-permission-modal').classList.add('hidden');
}

function showModal() {
    document.getElementById('audio-permission-modal').classList.remove('hidden');
}

window.addEventListener('DOMContentLoaded', () => {
    const unlocked = localStorage.getItem(`audioUnlocked_user_${myUserId}`);
    if (Number(localStorage.getItem(`notificationsEnabled_user_${myUserId}`)) === 1) {
        if (unlocked === '1' || unlocked === '0') {
            hideModal();

        } else {
            showModal();

            document.getElementById('enable-sound-btn').addEventListener('click', () => {
            unlockAudio();
            }, { once: true });

            document.getElementById('cancel-sound-btn').addEventListener('click', () => {
            console.log('ユーザーが通知音の許可をキャンセルしました');
            localStorage.setItem(`audioUnlocked_user_${myUserId}`, '0');
            hideModal();
            }, { once: true });
        }
    }
});
// グローバル関数として明示

window.enableNotification = function () {
    document.getElementById('notify-box').style.display = 'none';
    Livewire.dispatch('notification-enabled');
    localStorage.setItem(`notificationsEnabled_user_${myUserId}`, '1');
    location.reload();
}

window.dismissNotification = function () {
    document.getElementById('notify-box').style.display = 'none';
    Livewire.dispatch('notification-dismiss');
    localStorage.setItem(`notificationsEnabled_user_${myUserId}`, '0');
}

// DOM読み込み後に実行
window.addEventListener('DOMContentLoaded', () => {
    const saved = localStorage.getItem(`notificationsEnabled_user_${myUserId}`);
    if (saved === '1' || saved === '0') {
        document.getElementById('notify-box').style.display = 'none';
    }
});

// notification
// document.addEventListener('livewire:init', () => {
for (let i = 0; i < length; i++) {
    window.Echo.private(`group.${groupIds[i]}`)
    .listen('.message.sent', (e) => {
        // new Notification(`you receive a message by ${e.user_name} group ${e.group_name}`, {
        //     body: `${e.message.text}`,
        // });
        if (Number(localStorage.getItem(`notificationsEnabled_user_${myUserId}`)) === 1) {
            if (!groupId) {
                if (myUserId != e.user_id) {
                    // Livewire.dispatch('message-notice');
                    const container = document.getElementById('notification-area');
                    if (!container) return;

                    // 通知用のdivを生成
                    const notification = document.createElement('a');
                    notification.className = "bg-blue-500 text-white px-4 py-2 rounded shadow-lg animate-fadeIn";
                    notification.href = `/chat/${groupIds[i]}`
                    notification.innerText = `You receive a message by ${e.user_name} Group ${e.group_name}`;

                    // DOMに追加
                    container.appendChild(notification);
                    if (Number(localStorage.getItem(`audioUnlocked_user_${myUserId}`)) === 1) {
                    //sound
                    const audio = new Audio('/sounds/maou_se_onepoint23.mp3');
                    audio.volume = 0.5;
                    audio.play().catch(e => console.error("Audio play error:", e));
                    };

                    // if (window.Livewire && typeof window.Livewire.trigger === 'function') {
                    //     console.log('送信！', groupIds[i], myUserId);
                    //     window.Livewire.trigger('refreshMessages', groupIds[i], myUserId);
                    // }
                    // Livewire.dispatch('refresh', {
                    //     groupId: groupIds[i],
                    //     userId: myUserId,
                    // });
                    // window.dispatchEvent(new CustomEvent('refresh-messages', {
                    //     detail: {
                    //         groupId: groupId,
                    //         userId: myUserId
                    //     }
                    // }));

                    // 10秒後に自動削除
                    setTimeout(() => {
                        notification.remove();
                        location.reload(); //notification count 用
                    }, 4000);
                };
            };
        } else {
            if (!groupId) {
                if (myUserId != e.user_id) {
                    location.reload(); //notification count 用
                };
            };
        };
    });
}
// });





//3.IDが取れたらWebSocketを開始
if (groupId && myUserId) {

    //5.グループチャネルを購読して受信を待つ
    window.Echo.private(`group.${groupId}`) //group.{id} という名前のプライベートチャンネルを購読
        .listen('.message.sent', (e) => { //.message.sent というイベント名がブロードキャストされるのをリッスン、メッセージをチャットに追加する処理

            //6.受信データでメッセージ要素を作成
            console.log('受信:', e); //コンソールに受信内容を表示（デバッグ用）
            console.log("Echo object:", window.Echo);

            const messagesDiv = document.getElementById('messages'); //チャットメッセージの親要素を取得
            const isMine = parseInt(myUserId) === e.user_id; //メッセージの送信者が自分かどうかを判定

            const wrapper = document.createElement('div');
            wrapper.id = `message-${e.message_id}`;
            wrapper.className = isMine ? 'flex justify-end items-end' : '';
            //7.メッセージ内容をHTMLに組み立て
            //const messageElement = document.createElement('div');//新しいメッセージを追加するための div を用意
            let messageContent = '';

            //テキスト組み込み
            if (e.message && e.message.text) {
                //messageContent += `<div>${e.message.text}</div>`;
                // messageContent += escapeHtml(e.message.text);
                messageContent += nl2br(escapeHtml(e.message.text));
            }

            //画像組み込み
            if (e.image_url) {
                messageContent += `<img src="${e.image_url}" class="mt-2 max-w-xs rounded-lg">`;
            }

            if (isMine) {
                // テキストがある場合は枠付き
                if (e.message && e.message.text) {
                    wrapper.innerHTML = `
                        <div class="flex items-end justify-end">
                            <div>
                                <div class="text-xs text-right mt-1 text-gray-400 mr-2">${e.time_hm}</div>
                                <div class="text-xs text-right mt-1 text-gray-400 mr-2">${e.time_ymd}</div>
                            </div>
                        
                            <div class="bg-teal-200 text-base lg:text-xl mr-2 rounded-2xl p-3 max-w-[70%] shadow" oncontextmenu="openCustomMenu(event, ${e.message_id}, this)"
                                x-data="{ editing: false, content: ${JSON.stringify(e.message.text ?? '')} }">
                                    <div style="word-break: break-word; overflow-wrap: break-word; ">
                                        ${messageContent}
                                    </div>
                            </div>
                        </div>
                    `;
                } else {
                    // 画像だけの場合
                    wrapper.innerHTML = `
                        <div class="flex items-end justify-end">
                            <div class="max-w-[70%]">
                                <div class="mt-2">${messageContent}</div>
                            </div>
                            <div>
                                <div class="text-xs text-right mt-1 text-gray-400 mr-2">${e.time_hm}</div>
                                <div class="text-xs text-right mt-1 text-gray-400 mr-2">${e.time_ymd}</div>
                            </div>
                        </div>
                    `;
                }
                // wrapper.setAttribute('oncontextmenu', `openCustomMenu(event, ${e.message_id}, this)`);
                // wrapper.setAttribute('x-data', `{ editing: false, content: ${JSON.stringify(e.message.text ?? '')} }`);

            } else {
                if (e.message && e.message.text) {
                    wrapper.innerHTML = `
                        <div class="flex items-start">
                            <img src="${e.avatar_url ?? '/images/user.png'}" class="w-8 h-8 rounded-full mt-1" alt="${e.user_name}">
                            <div class="flex space-x-2 items-end">
                                <div class="max-w-[70%]">
                                    <div class="text-sm md:text-base text-gray-600 font-medium">${e.user_name}</div>
                                    <div class="text-base lg:text-xl bg-white border border-gray-200 rounded-2xl p-3 shadow">
                                        <div style="word-break: break-word; overflow-wrap: break-word; ">
                                            ${messageContent}
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <div class="text-xs  mt-1 text-gray-400 ml-1">${e.time_hm}</div>
                                    <div class="text-xs  mt-1 text-gray-400 ml-1">${e.time_ymd}</div>
                                </div>
                            </div>
                        </div>
                    `;
                } else {
                    // 画像だけの場合
                    wrapper.innerHTML = `
                        <div class="flex items-start">
                            <img src="${e.avatar_url ?? '/images/user.png'}" class="w-8 h-8 rounded-full mt-1" alt="${e.user_name}">
                            <div class="max-w-[70%]">
                                <div class="text-sm md:text-base text-gray-600 font-medium">${e.user_name}</div>
                                <div class="flex items-end space-y-1">
                                    <div>${messageContent}</div>
                                    <div>
                                        <div class="text-xs  mt-1 text-gray-400 ml-2">${e.time_hm}</div>
                                        <div class="text-xs  mt-1 text-gray-400 ml-2">${e.time_ymd}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                }
            }

            //自分のメッセージは右寄せ（緑背景）、他人のは左寄せ（白背景）にする

            //DOMに追加してスクロール
            // messagesDiv.appendChild(messageElement); //メッセージをチャットに追加
            Alpine.initTree(wrapper);
            messagesDiv.appendChild(wrapper);
            messagesDiv.scrollTop = messagesDiv.scrollHeight; //チャットのスクロールを一番下に自動で移動
            requestAnimationFrame(() => {
                const img = wrapper.querySelector('img');
                if (img) {
                    if (img.complete) {
                        messagesDiv.scrollTop = messagesDiv.scrollHeight;
                    } else {
                        img.addEventListener('load', () => {
                            messagesDiv.scrollTop = messagesDiv.scrollHeight;
                        });
                    }
                } else {
                    messagesDiv.scrollTop = messagesDiv.scrollHeight;
                }
            });
        });
}



