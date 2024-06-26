<?php
session_start();
require('dbconnect.php');

// ユーザー認証設定
if (!isset($_SESSION['user_id']))  {
  header('Location: user/signin.php');
  exit();
}

// 記事の取得
$id = $_GET['id'];
$sql = 'SELECT * FROM blogs WHERE id = :id';
$statement = $pdo->prepare($sql);
$statement->bindValue(':id', $id, PDO::PARAM_INT);
$statement->execute();
$article = $statement->fetch(PDO::FETCH_ASSOC);

if(!$article) {
  echo '記事が見つかりませんでした';
  exit();
}


// コメントの取得
$sql = 'SELECT * FROM comments WHERE blog_id = :blog_id ORDER BY created_at DESC';
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':blog_id', $id, PDO::PARAM_INT);
$stmt->execute();
$comments = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>詳細ページ</title>
</head>
<body>

  <div class="article-detail">
    <h2><?php echo htmlspecialchars($article['title'], ENT_QUOTES, 'UTF-8'); ?></h2>
    <p><?php echo htmlspecialchars($article['created_at'], ENT_QUOTES, 'UTF-8'); ?></p>
    <p><?php echo htmlspecialchars($article['contents'], ENT_QUOTES, 'UTF-8'); ?></p>
    <button onclick="location.href='index.php'">一覧ページへ</button>
  </div>

  <div class="comment-form">
    <h2>この投稿にコメントしますか？</h2>

    <form action="comment/store.php" method="post">
    <input type="hidden" name="blog_id" value="<?php echo htmlspecialchars($id, ENT_QUOTES, 'UTF-8'); ?>">

      <div class="commenter_name">
        <label for="commenter_name">コメント名</label>
        <input type="text" id="commenter_name" name="commenter_name">
      </div>
      <div class="comment-content">
        <label for="comment">内容</label>
        <textarea name="comment" id="comment" cols="30" rows="10" required></textarea>
      </div>
      <button type="submit">コメント</button>
    </form>
  </div>

  <?php if (!empty($_SESSION['errors'])): ?>
      <div class="errors">
        <?php foreach ($_SESSION['errors'] as $error): ?>
          <p><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></p>
        <?php endforeach; ?>
        <?php unset($_SESSION['errors']); ?>
      </div>
    <?php endif; ?>

  <div class="comments">
    <h3>コメント一覧</h3>
    <?php if ($comments): ?>
      <?php foreach ($comments as $comment): ?>
        <div class="comment">
          <p><?php echo htmlspecialchars($comment['commenter_name'], ENT_QUOTES, 'UTF-8'); ?></p>
          <p><?php echo nl2br(htmlspecialchars($comment['comment'], ENT_QUOTES, 'UTF-8')); ?></p>
          <p><?php echo htmlspecialchars($comment['created_at'], ENT_QUOTES, 'UTF-8'); ?></p>
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <p>コメントはまだありません。</p>
    <?php endif; ?>
  </div>
</body>
</html>


