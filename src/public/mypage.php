<?php
ini_set( 'display_errors', 1 );
session_start();
require('dbconnect.php');
require_once __DIR__ . '/../vendor/autoload.php';

use App\Adapter\Repository\BlogRepository;
use App\Infrastructure\Dao\BlogDao;

$errors = [];
$sort = 'DESC';

// ユーザー認証機能
if (!isset($_SESSION['user_id'])) {
  header('Location: user/signin.php');
  exit();
}


try {
  $pdo = new PDO(
    'mysql:host=mysql;dbname=blog;charset=utf8',
        'root',
        'password'
  );

  $blogDao = new BlogDao($pdo);
  $blogRepository = new BlogRepository($pdo, $blogDao);
  $blogs = $blogRepository->findByUserId($_SESSION['user_id'], $sort);
  if ($blogs === null || empty($blogs)) {
    throw new Exception("ユーザーの記事が見つかりませんでした。");
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
  <title>マイページ</title>
  <link rel="stylesheet" href="css/reset.css">
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="css/header.css">
  <link rel="stylesheet" href="css/mypage.css">

</head>
<body>
  <div class="articles">
    <h2>マイページ</h2>
    <form action="create.php">
      <button type="submit">新規作成</button>
    </form>
    <?php foreach ($blogs as $blog): ?>
      <div class="article">
        <h3><?php echo htmlspecialchars($blog->title()->value(), ENT_QUOTES, 'UTF-8'); ?></h3><br>
        <p><?php echo htmlspecialchars($blog->createdAt()->format('Y-m-d H:i:s'), ENT_QUOTES, 'UTF-8'); ?></p><br>
        <p><?php echo htmlspecialchars(mb_strimwidth($blog->content()->value(), 0, 15, '...'), ENT_QUOTES, 'UTF-8'); ?></p><br>
        <a href="myarticledetail.php?id=<?php echo $blog->Id(); ?>">記事詳細へ</a>
      </div>
      <br>
    <?php endforeach; ?>
  </div>
</div>

</body>
</html>
