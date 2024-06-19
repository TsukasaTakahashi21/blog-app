<?php
session_start();
require('dbconnect.php');
$errors = [];

?>


<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>新規記事</title>
  <link rel="stylesheet" href="reset.css">
  <link rel="stylesheet" href="style.css">
</head>
<body>

  <div class="mypage">
    <h2>新規記事</h2>
    <?php if (!empty($_SESSION['errors'])): ?>
      <div class="error-message">
        <ul>
          <?php foreach ($_SESSION['errors'] as $error): ?>
            <li><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
      <?php unset($_SESSION['errors']); ?>
    <?php endif; ?>
    <form action="store.php" method="post">
      <div>
        <label for="title">タイトル</label>
        <input type="text" id="title", name="title">
      </div>
      <div>
        <label for="title">内容</label>
        <textarea name="content" id="content" cols="85" rows="10"></textarea>
      </div>
      <br>
      <button type="submit">新規作成</button>
    </form>
  </div>
</body>
</html>