<?php
require_once __DIR__ . '/../vendor/autoload.php';
use App\Infrastructure\Redirect\Redirect;
use App\Adapter\Repository\BlogRepository;
use App\Infrastructure\Dao\BlogDao;

session_start();
if (!isset($_SESSION['user']['id'])) {
    Redirect::handler('./user/signin.php');
}
$memberStatus = $_SESSION['user']['memberStatus'];
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
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=, initial-scale=1.0">
  <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
  <title>blog一覧</title>
</head>

<?php require_once __DIR__ . '/header.php'; ?>

<body>
  <div class="blogs__wraper bg-green-300 py-20 px-20">
    <div class="ml-8 mb-12">
      <h2 class="mb-2 px-2 text-6xl font-bold text-green-800">blog一覧</h2>
      <p><?php echo $memberStatus; ?></p>

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
  </div>
</body>

</html>