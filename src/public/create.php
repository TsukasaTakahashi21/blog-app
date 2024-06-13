<?php
ini_set( 'display_errors', 1 );
session_start();
require('dbconnect.php');
$errors = [];

// セッションにuserのidが保存されていない場合、signin.phpに遷移。
if (!isset($_SESSION['user_id'])) {
  header('Location: signin.php');
  exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $title = $_POST['title'];
  $content = $_POST['content'];

  if (empty($title) || empty($content)) {
    $errors[] = 'タイトルか内容の入力がありません';
    $_SESSION['errors'] = $errors;
    header('Location: create.php');
    exit();
  }

  if (empty($errors)) {
    $sql = 'INSERT INTO blogs (user_id, title, contents, created_at) VALUES (:user_id, :title, :contents, NOW())';
    $statement = $pdo->prepare($sql);
    $statement->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
    $statement->bindValue(':title', $title, PDO::PARAM_STR);
    $statement->bindValue(':contents', $content, PDO::PARAM_STR);
    $statement->execute();
      // マイページにリダイレクト
    header('Location: mypage.php');
    exit();
  }
}
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