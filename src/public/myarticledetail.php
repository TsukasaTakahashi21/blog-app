<?php
ini_set('display_errors', 1);
session_start();

require('dbconnect.php');
$errors = [];


// ユーザー認証設定
if (!isset($_SESSION['user_id'])) {
  header('Location: user/signin.php');
  exit();
}

// 記事の取得
if (!isset($_GET['id'])) {
  header('Location: mypage.php');
  exit();
}

$article_id = (int)$_GET['id'];

// 記事の取得
$sql = "SELECT * FROM blogs WHERE id = :id";
$statement = $pdo->prepare($sql);
$statement ->bindValue(':id', $article_id, PDO::PARAM_INT);
$statement->execute();
$article = $statement->fetch(PDO::FETCH_ASSOC);


if (!$article) {
  header('Location: index.php');
  exit();
}


// 記事のuser_idとログインユーザーのidが一致しているか確認
if ($article['user_id'] != $_SESSION['user_id']) {
  header('Location: mypage.php');
  exit();
}

?>


<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>記事詳細</title>
    <link rel="stylesheet" href="reset.css">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="detail.css">

</head>
<body>
     <h1 class="title"><?php echo htmlspecialchars($article['title'], ENT_QUOTES, 'UTF-8'); ?></h1>

    <div class="article-detail">
        <p>投稿日時: <?php echo htmlspecialchars($article['created_at'], ENT_QUOTES, 'UTF-8'); ?></p>
        <div class="content">
            <p><?php echo nl2br(htmlspecialchars($article['contents'], ENT_QUOTES, 'UTF-8')); ?></p>
        </div>
        <div class="actions">
            <a href="edit.php?id=<?php echo $article['id']; ?>" class="btn-edit">編集</a>
            <a href="post/delete.php?id=<?php echo $article['id']; ?>" class="btn-delete">削除</a>
            <a href="mypage.php" class="btn-mypage">マイページへ</a>
        </div>
    </div>
</body>
</html>

