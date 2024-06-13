<?php
ini_set( 'display_errors', 1 );

session_start();
require('dbconnect.php');
$errors = [];
$result = '';

// ユーザー認証機能
if (!isset($_SESSION['user_id'])) {
  header('Location: user/signin.php');
  exit();
}

// 自分の作成した記事のみ表示
$sql = "SELECT * FROM blogs where user_id = :user_id";
if (isset($_GET['sort']) && $_GET['sort'] === 'old') {
  $sql .= " ORDER BY created_at ASC";
} else {
  $sql .= " ORDER BY created_at DESC";
}
$statement = $pdo->prepare($sql);
$statement->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
$statement->execute();
$result = $statement->fetchAll(PDO::FETCH_ASSOC);
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
    <?php foreach ($result as $article): ?>
      <div class="article">
        <h3><?php echo htmlspecialchars($article['title'], ENT_QUOTES, 'UTF-8'); ?></h3><br>
        <p><?php echo htmlspecialchars($article['created_at'], ENT_QUOTES, 'UTF-8'); ?></p><br>
        <p><?php echo htmlspecialchars(mb_strimwidth($article['contents'], 0, 15, '...'), ENT_QUOTES, 'UTF-8'); ?></p><br>
        <a href="myarticledetail.php?id=<?php echo $article['id']; ?>">記事詳細へ</a>
      </div>
      <br>
    <?php endforeach; ?>
  </div>
</div>

</body>
</html>
