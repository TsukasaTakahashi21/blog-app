<?php
session_start();
require('dbconnect.php');

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

// 全ての記事の表示
$sql = "SELECT * FROM blogs";

// キーワードが入力されている場合は条件を追加
if (!empty($keyword)) {
  $sql .= " WHERE title LIKE :keyword OR contents LIKE :keyword";
}

if (isset($_GET['sort']) && $_GET['sort'] === 'old') {
  $sql .= " ORDER BY created_at ASC";
} else {
  $sql .= " ORDER BY created_at DESC";
}

// SQL文を実行する準備
$statement = $pdo->prepare($sql);

// キーワードが入力されている場合、プレースホルダーに値をバインド
if (!empty($keyword)) {
  $keyword_like = '%' . $keyword . '%';
  $statement->bindValue(':keyword', $keyword_like, PDO::PARAM_STR);
}

$statement->execute();
$result = $statement->fetchAll(PDO::FETCH_ASSOC);
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
  <?php foreach ($result as $article): ?>
    <div class="article">
      <h3><?php echo htmlspecialchars($article['title'], ENT_QUOTES, 'UTF-8'); ?></h3>
      <p><?php echo mb_strimwidth(htmlspecialchars($article['contents'], ENT_QUOTES, 'UTF-8'), 0, 15, '...'); ?></p>
      <a href="detail.php?id=<?php echo $article['id']; ?>">記事詳細へ</a>
    </div>
  <?php endforeach; ?>
</div>

</body>
</html>
