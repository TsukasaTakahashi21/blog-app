<?php
session_start();
require('./dbconnect.php');
require_once __DIR__ . '/../vendor/autoload.php';

use App\Adapter\Repository\BlogRepository;
use App\Infrastructure\Dao\BlogDao;
use App\Domain\ValueObject\Blog\BlogComment;

$errors = [];
$result = '';

$username = isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username'], ENT_QUOTES, 'UTF-8') : 'ゲスト';
$username .= 'さん';

// ユーザー認証機能
if (!isset($_SESSION['user_id'])) {
  header('Location: user/signin.php');
  exit();
}

// 検索ワードと並び順の処理
$keyword = isset($_GET['keyword']) ? htmlspecialchars($_GET['keyword'], ENT_QUOTES, 'UTF-8') : '';
$sort = isset($_GET['sort']) && $_GET['sort'] === 'old' ? 'ASC' : 'DESC';

try {
  $pdo = new PDO(
    'mysql:host=mysql;dbname=blog;charset=utf8',
        'root',
        'password'
  );

  $blogDao = new BlogDao($pdo);
  $blogRepository = new BlogRepository($pdo, $blogDao);
  $blogs = $blogRepository->findAll($keyword, $sort);
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
  <title>記事一覧</title>
</head>
<body>
<?php echo "こんにちは！" . $username ?>

<header>
  <div class="navi">
    <ul>
      <li><a href="index.php">ホーム</a></li>
      <li><a href="mypage.php">マイページ</a></li>
      <li><a href="user/logout.php">ログアウト</a></li>
    </ul>
  </div>
</header>

<div class="articles">
  <h2>ブログ一覧</h2>
  <div class="filter">
    <form action="" method="get">
      <div class="search">
        <input type="text" name="keyword" placeholder="キーワードを入力" value="<?php echo htmlspecialchars($keyword, ENT_QUOTES, 'UTF-8'); ?>">
        <select name="sort" id="">
          <option value="new" <?php if ($sort !== 'ASC') echo 'selected'; ?>>新着順</option>
          <option value="old" <?php if ($sort === 'ASC') echo 'selected'; ?>>古い順</option>
        </select>
      </div>
      <button type="submit">検索</button>
    </form>
  </div>
  <?php foreach ($blogs as $blog): ?>
    <div class="article">
      <h3><?php echo htmlspecialchars($blog->title()->value(), ENT_QUOTES, 'UTF-8'); ?></h3>
      <p><?php echo mb_strimwidth(htmlspecialchars($blog->comment()->value(), ENT_QUOTES, 'UTF-8'), 0, 15, '...'); ?></p>
      <a href="detail.php?id=<?php echo $blog->Id(); ?>">記事詳細へ</a>
    </div>
  <?php endforeach; ?>
</div>

</body>
</html>
