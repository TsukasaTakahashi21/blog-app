<?php
session_start();
require('./dbconnect.php');
require_once __DIR__ . '/../vendor/autoload.php';

use App\Adapter\Repository\BlogRepository;
use App\Adapter\Repository\CommentRepository;
use App\UseCase\UseCaseInteractor\BlogDetailInteractor;
use App\Infrastructure\Dao\BlogDao;

$dbUserName = 'root';
$dbPassword = 'password';

try {
  $pdo = new PDO(
    'mysql:host=mysql;dbname=blog;charset=utf8',
    $dbUserName,
    $dbPassword
  );

$blogDao = new BlogDao($pdo);
$blogRepository = new BlogRepository($pdo, $blogDao);
$commentRepository = new CommentRepository($pdo);

$blogDetailInteractor = new BlogDetailInteractor($blogRepository, $commentRepository);

if (!isset($_GET['id'])) {
  throw new Exception('記事IDが指定されていません');
}

$blogId = (int)$_GET['id'];
if ($blogId <= 0) {
    throw new Exception('有効な記事IDが指定されていません');
}

$data = $blogDetailInteractor->blogDetail($blogId);
    if (!$data || !$data['blog']) {
        throw new Exception('記事が見つかりませんでした');
    }

$blog = $data['blog'];
$comments = $data['comments'];
} catch (Exception $e) {
  echo $e->getMessage();
  exit;
}
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
    <h2><?php echo htmlspecialchars($blog->title()->value(), ENT_QUOTES, 'UTF-8'); ?></h2>
    <p><?php echo htmlspecialchars($blog->createdAt()->format('Y-m-d H:i:s'), ENT_QUOTES, 'UTF-8'); ?></p>
    <p><?php echo htmlspecialchars($blog->content()->value(), ENT_QUOTES, 'UTF-8'); ?></p>
    <button onclick="location.href='index.php'">一覧ページへ</button>
  </div>

  <div class="comment-form">
    <h2>この投稿にコメントしますか？</h2>

    <form action="comment/store.php" method="post">
    <input type="hidden" name="blog_id" value="<?php echo htmlspecialchars($blog->id(), ENT_QUOTES, 'UTF-8'); ?>">

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
          <p><?php echo htmlspecialchars($comment->commenterName(), ENT_QUOTES, 'UTF-8'); ?></p>
          <p><?php echo nl2br(htmlspecialchars($comment['comment'], ENT_QUOTES, 'UTF-8')); ?></p>
          <p><?php echo htmlspecialchars($comment->comment(), ENT_QUOTES, 'UTF-8'); ?></p>
          <p?><?php echo htmlspecialchars($comment->createdAt()->format('Y-m-d H:i:s'), ENT_QUOTES, 'UTF-8');?></p>
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <p>コメントはまだありません。</p>
    <?php endif; ?>
  </div>
</body>
</html>


