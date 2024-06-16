<?php
require_once __DIR__ . '/../vendor/autoload.php';

use App\Infrastructure\Redirect\Redirect;
use App\Domain\ValueObject\Blog\BlogTitle;
use App\Domain\ValueObject\Blog\BlogContent;
use App\Domain\ValueObject\Blog\BlogComment;
use App\UseCase\UseCaseInput\CreateBlogInput;
use App\UseCase\UseCaseInteractor\CreateBlogInteractor;
use App\Infrastructure\Dao\BlogDao;
use App\Adapter\Repository\BlogRepository;

session_start();
$dbUserName = 'root';
$dbPassword = 'password';
$pdo = new PDO('mysql:host=mysql; dbname=blog; charset=utf8', $dbUserName, $dbPassword);
$blogDao = new BlogDao($pdo);
$blogRepository = new BlogRepository($pdo, $blogDao);


$title = filter_input(INPUT_POST, 'title');
$content = filter_input(INPUT_POST, 'content');
$comment = filter_input(INPUT_POST, 'comment');

try {
    if (empty($title) || empty($content)) {
        throw new Exception('タイトルか内容の入力がありません');
    }

    $blogTitle = new BlogTitle($title);
    $blogContent = new BlogContent($content);
    $blogComment = new BlogComment($comment); 

    $useCaseInput = new CreateBlogInput($_SESSION['user_id'], $blogTitle, $blogContent, $blogComment);

    $useCase = new CreateBlogInteractor($blogRepository);
    $useCaseOutput = $useCase->handle($useCaseInput);

    if ($useCaseOutput->isSuccess()) {
        $_SESSION['message'] = $useCaseOutput->message();
        Redirect::handler('./mypage.php');
    } else {
        throw new Exception($useCaseOutput->message());
    }

} catch (Exception $e) {
    $_SESSION['errors'] = [$e->getMessage()];
    Redirect::handler('./create.php');
}

