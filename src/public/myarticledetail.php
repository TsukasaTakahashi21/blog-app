<?php

use App\Adapter\Repository\BlogRepository;
use App\Infrastructure\Dao\BlogDao;

ini_set('display_errors', 1);
session_start();
require('./dbconnect.php');
require_once __DIR__ . '/../vendor/autoload.php';

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

try {
  $pdo = new PDO(
    'mysql:host=mysql;dbname=blog;charset=utf8',
        'root',
        'password'
  );

  $blogDao = new BlogDao($pdo);
  $blogRepository = new BlogRepository($pdo, $blogDao);
  $article = $blogRepository->findById($article_id);

  if (!$article) {
    throw new Exception('記事が見つかりませんでした');
  }

  if ($article->userId() != $_SESSION['user_id']) {
    throw new Exception('権限がありません');
  } 
} catch (Exception $e) {
  echo $e->getMessage();
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
    <h1 class="title"><?php echo htmlspecialchars($article->title()->value(), ENT_QUOTES, 'UTF-8'); ?></h1>

    <div class="article-detail">
        <p>投稿日時: <?php echo htmlspecialchars($article->createdAt()->format('Y-m-d H:i:s'), ENT_QUOTES, 'UTF-8'); ?></p>
        <div class="content">
            <p><?php echo nl2br(htmlspecialchars($article->content()->value(), ENT_QUOTES, 'UTF-8')); ?></p>
        </div>
        <div class="actions">
            <a href="edit.php?id=<?php echo $article->Id(); ?>" class="btn-edit">編集</a>
            <a href="post/delete.php?id=<?php echo $article->Id(); ?>" class="btn-delete">削除</a>
            <a href="mypage.php" class="btn-mypage">マイページへ</a>
        </div>
    </div>
</body>
</html>

