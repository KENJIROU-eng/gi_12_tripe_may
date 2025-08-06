<!DOCTYPE html>
<html>
<body>
    <h1>ようこそ、{{ $user->name }} さん！</h1>
    <p>下記のリンクをクリックして、メールアドレスを認証してください。</p>
    <p><a href="{{ $verifyUrl }}">メール認証リンク</a></p>
</body>
</html>
