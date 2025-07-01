<!DOCTYPE html>
<html>
<body>
    <h1>ようこそ、<?php echo e($user->name); ?> さん！</h1>
    <p>下記のリンクをクリックして、メールアドレスを認証してください。</p>
    <p><a href="<?php echo e($verifyUrl); ?>">メール認証リンク</a></p>
</body>
</html>
<?php /**PATH /Users/kawaimayu/Desktop/gi_12_tripe_may/resources/views/emails/welcome-verify.blade.php ENDPATH**/ ?>