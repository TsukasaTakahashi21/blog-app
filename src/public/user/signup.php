<?php
session_start();
require('../dbconnect.php');

$errors = $_SESSION['errors'] ?? [];
unset($_SESSION['errors']);

?>


<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>会員登録</title>
  
</head>
<body>
  <section class="login">
    <h2>会員登録</h2>
    <?php foreach ($errors as $error): ?>
      <p><?php echo $error; ?></p>
    <?php endforeach; ?>
    <form action="signup_complete.php" method="post">
      <input type="text" id="name" name="name" placeholder="Username" required><br>
      <input type="email" id="email" name="email" placeholder="Email" required ><br>
      <input type="password" id="password" name="password"><br>
      <input type="password" id="confirm_password" name="confirm_password" placeholder="パスワード確認"><br>
      <button type="submit" onclick="location.href='./signup_complete.php'">アカウント作成</button>
    </form>
    <a href="signin.php">ログイン画面へ</a>
  </section>
</body>
</html>
