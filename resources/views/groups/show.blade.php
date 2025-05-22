@extends('layouts.app')

@section('title','Chat Show')

@section('content')

<script src="/js/chat.js"></script>
<script src="https://js.pusher.com/8.4.0/pusher.min.js"></script>

<div id="messages" class="max-h-500x overflow-auto p-4 bg-gray-100 rounded-lg shadow-inner space-y-2"></div>

<form id="chat-form" action="chat.send" method="POST" enctype="multipart/form-data" class="flex items-center gap-2 p-2 border-t mt-4 bg-white">
    @csrf
    <input type="text" name="message" placeholder="message..." class="flex-1 p-2 border rounded-lg focus:outline-none focus:ring focus:border-blue-300">
    <input type="file" name="image" accept="image/*" class="hidden" id="image-upload">
    <label for="image-upload" class="cursor-pointer">
        <i class="fa-solid fa-image text-xl text-gray-500 hover:text-blue-500"></i>
    </label>
    <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">Send
    </button>
</form>

<script>
    const YOUR_USER_ID = //@json(auth()->id());

    window.Echo.private('chat')
    .listen('.message.sent',(e) => {
        console.log('New message:', e.message);
        //メッセージ表示用のdiv
        const messagesDiv = document.getElementById('messages');

        //新しいメッセージを作成して追加
        const messageElement = document.createElement('div');
        const isMine = e.user_id ===YOUR_USER_ID;//@json(auth->id())みたいな

        let messageContent = '';



        const avatarHtml = e.user_avatar
            ? `<img src="${e.user_avatar}" class="w-8 h-8 rounded-full mr-2">`
            : `<i class="fa-solid fa-user text-gray-400 text-xl mr-2"></i>`;

        const headerHtml = `
            <div class="flex items-center mb-1">
                ${!isMine ? avatarHtml : ''}
                <span class="text-sm font-semibold text-gray-500">${e.user_name}</span>
            </div>
        `;


        //メッセージがあれば追加
        if(e.message) {
            messageContent += `<div>${e.message}</div>`;
        }

        //画像があれば表示
        if(e.image_url){
            messageContent += `<img src="${e.image_url}" class="mt-2 max-w-xs rounded-lg">`;
        }

        //時刻を追加
        messageContent += `<div class="text-xs text-right mt-1 text-gray-400">${e.time}</div>`;

        messageElement.innerHTML = `
        <div class="flex ${isMine ? 'justify-end' : 'justify-start'}">
            <div class="${isMine ? 'bg-green-300 text-black' : 'bg-white text-black'} rounded-2xl p-3 max-w-[70%] shadow">
                ${messageContent}
            </div>
        </div>
        `;


        messagesDiv.appendChild(messageElement);
        messagesDiv.scrollTop = messagesDiv.scrollHeight;
    });

</script>




@endsection