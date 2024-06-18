<?php
use App\Adapter\Repository\BlogRepository;
use App\Infrastructure\Dao\BlogDao;
use App\Domain\ValueObject\Blog\BlogTitle;
use App\Domain\ValueObject\Blog\BlogContent;
use App\UseCase\UseCaseInteractor\UpdateBlogInteractor;
use App\UseCase\UseCaseInput\UpdateBlogInput;

session_start();
require('./dbconnect.php');
require_once __DIR__ . '/../vendor/autoload.php';

$errors = [];

if (!isset($_SESSION['user_id'])) {
  header('Location: user/signin.php');
  exit();
}

$id = $_POST['id'];
$title = $_POST['title'];
$content = $_POST['content'];

try {
  $pdo = new PDO(
    'mysql:host=mysql;dbname=blog;charset=utf8',
    'root',
    'password'
  );

  $blogDao = new BlogDao($pdo);
  $blogRepository = new BlogRepository($pdo, $blogDao);
  $interactor = new UpdateBlogInteractor($blogRepository);

  $input = new UpdateBlogInput(
    (int)$id,
    (int)$_SESSION['user_id'],
    new BlogTitle($title),
    new BlogContent($content)
  );

  $output = $interactor->handle($input);

  if ($output->success()) {
    header('Location: mypage.php');
    exit();
  } else {
    throw new Exception($output->message());
  }
} catch (Exception $e) {
  echo $e->getMessage();
  exit();
}

?>