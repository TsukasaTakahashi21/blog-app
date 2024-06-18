<?php
use App\Adapter\Repository\BlogRepository;
use App\Infrastructure\Dao\BlogDao;

session_start();
require('./dbconnect.php');
require_once __DIR__ . '/../vendor/autoload.php';

$errors = [];

if (!isset($_SESSION['user_id'])) {
  header('Location: user/signin.php');
  exit();
}

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
  <title>記事編集</title>
</head>
<body>
  <h1 class="title">記事編集</h1>

  <div class="article-edit">
    <form action="update.php" method="post">
      <input type="hidden" name='id', value="<?php echo htmlspecialchars($article->id(), ENT_QUOTES, 'UTF-8'); ?>">
      <div class="form-group">
        <label for="title">タイトル</label>
        <input type="text" name="title" id="title" value="<?php echo htmlspecialchars($article->title()->value(), ENT_QUOTES, 'UTF-8'); ?>">
      </div>
      <div class="form-group">
        <label for="content">内容</label>
        <input type="content" name="content" id="content" value="<?php echo htmlspecialchars($article->content()->value(), ENT_QUOTES, 'UTF-8'); ?>">
      </div>
      <div class="form-group">
        <button type="submit">更新</button>
      </div>
    </form>
    <a href="mypage.php">マイページへ</a>
  </div>
</body>
</html>

