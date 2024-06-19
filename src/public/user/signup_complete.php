<?php
require_once __DIR__ . '/../../vendor/autoload.php';
use App\Infrastructure\Redirect\Redirect;
use App\Domain\ValueObject\User\UserName;
use App\Domain\ValueObject\User\Age;
use App\Domain\ValueObject\Email;
use App\Domain\ValueObject\InputPassword;
use App\UseCase\UseCaseInput\SignUpInput;
use App\UseCase\UseCaseInteractor\SignUpInteractor;
use App\Infrastructure\Dao\UserDao;
use App\Infrastructure\Dao\UserAgeDao;


require('../dbconnect.php');

$errors = $_SESSION['errors'] ?? [];
unset($_SESSION['errors']);

$name = filter_input(INPUT_POST, 'name');
$email = filter_input(INPUT_POST, 'email');
$password = filter_input(INPUT_POST, 'password');
$confirmPassword = filter_input(INPUT_POST, 'confirm_password');
$age = filter_input(INPUT_POST, 'age');

try {
    session_start();
    if (empty($password) || empty($confirmPassword)) {
        throw new Exception('パスワードを入力してください');
    }
    if ($password !== $confirmPassword) {
        throw new Exception('パスワードが一致しません');
    }

    $userName = new UserName($name);
    $userEmail = new Email($email);
    $userPassword = new InputPassword($password);
    $age = new Age($age);
    $useCaseInput = new SignUpInput(
        $userName,
        $userEmail,
        $userPassword,
        $age
    );
    $userDao = new UserDao();
    $userAgeDao = new UserAgeDao();
    $useCase = new SignUpInteractor($useCaseInput, $userDao, $userAgeDao);
    $useCaseOutput = $useCase->handler();

    if (!$useCaseOutput->isSuccess()) {
        throw new Exception('すでに登録済みのメールアドレスです');
    }
    $_SESSION['message'] = '登録が完了しました';
    Redirect::handler('./signin.php');
} catch (Exception $e) {
    $_SESSION['errors'][] = $e->getMessage();
    $_SESSION['user']['name'] = $name;
    $_SESSION['user']['email'] = $email;
    $_SESSION['user']['age'] = $age;
    Redirect::handler('./signup.php');
}


