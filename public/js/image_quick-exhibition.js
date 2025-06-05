function previewImage(event) {
    const reader = new FileReader();
    // reader.onload に関数を代入：ファイルの読み込みが完了したらこの関数が実行される。
    reader.onload = function(){
        const preview = document.getElementById('imagePreview');
        preview.src = reader.result;
        preview.classList.remove('hidden');
    };
    // 選択された画像ファイルをBase64形式のURL（Data URL）として読み込む。
    reader.readAsDataURL(event.target.files[0]);
}
