<?php
session_start();
require('../dbconnect.php');
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = $_POST['email'];
  $password = $_POST['password'];

  if (empty($email) || empty($password)) {
    $errors[] = 'EmailかPasswordの入力がありません';
    $_SESSION['errors'] = $errors;
    header('Location: signin.php');
    exit();
  }

  // ログインチェック
  $sql = 'SELECT * FROM users WHERE email = :email';
  $statement = $pdo->prepare($sql);
  $statement->bindValue(':email', $email, PDO::PARAM_STR);
  $statement->execute();
  $user = $statement->fetch(PDO::FETCH_ASSOC);

  if (!$user || !password_verify($password, $user['password'])) {
    $errors[] = 'メールアドレスまたはパスワードが違います';
    $_SESSION['errors'] = $errors;
    header('Location: signin.php');
    exit();
  }

  // ログイン成功時の処理
  $_SESSION['user_id'] = $user['id'];
  $_SESSION['username'] = $user['name'];
  header('Location: ../index.php');
  exit();
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>会員登録</title>
  <link rel="stylesheet" href="../reset.css">
  <link rel="stylesheet" href="../style.css">
</head>
<body>
  <div class="header">
  <h1>こんにちは！<?php echo isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username'], ENT_QUOTES, 'UTF-8') : 'ゲスト'; ?></h1>
      <nav class="navi">
        <ul>
          <li><a href="../index.php">ホーム</a></li>
          <li><a href="../mypage.php">マイページ</a></li>
          <li><a href="signin.php">ログアウト</a></li>
        </ul>
      </nav>
  </div>
    
  <div class="login">
    <div class="login-screen">
      <form action="signin_complete.php" method="post">
        <div class="app-title">
          <h2>ログイン</h2>
        </div>

        <?php if (!empty($_SESSION['errors'])): ?>
          <div>
            <ul>
              <?php foreach ($_SESSION['errors'] as $error): ?>
                <li><?php echo  htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></li>
              <?php endforeach; ?>
            </ul>
          </div>
          <?php unset($_SESSION['errors']); ?>
        <?php endif; ?>
          <div>
            <label for="email">メールアドレス:</label>
            <input type="email" id="email" name="email" placeholder="Email">
          </div>
          <div>
            <label for="password">パスワード:</label>
            <input type="password" id="password" name="password">
          </div>
          <button type="submit">ログイン</button>
          <p class="login-button"><a href="user/signup.php">アカウントを作る</a></p>
          </form>
    </div>
  </div>
</body>
</html>