<?php
session_start();
require('../dbconnect.php');
$errors = $_SESSION['errors'] ?? [];
unset($_SESSION['errors']);

$successRegistedMessage = $_SESSION['message'] ?? '';
unset($_SESSION['message']);
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=, initial-scale=1.0">
  <title>ログイン</title>
</head>
<body>
  <section class="login">
    <h2>ログイン</h2>

    <?php if (!empty($errors)): ?>
      <?php foreach ($errors as $error): ?>
        <p><?php echo $error; ?></p>
      <?php endforeach; ?>
    <?php endif; ?>

    <form action="./signin_complete.php" method="post">
      <input type="email" id="email" name="email" required placeholder="Email" value="<?php if (
        isset($_SESSION['email'])
      ) {
        echo$_SESSION['email'];
      } ?>"><br>
      <input type="password" id="password" name="password" placeholder="パスワード"><br>
      <button type="submit">ログイン</button>
    </form>
    <a href="./signup.php">アカウントを作る</a></p>
  </section>
</body>
</html>