<?php
require_once __DIR__ . '/../../vendor/autoload.php';
use App\Domain\ValueObject\Email;
use App\Domain\ValueObject\InputPassword;
use App\UseCase\UseCaseInput\SignInInput;
use App\Infrastructure\Dao\UserDao;
use App\Infrastructure\Dao\UserAgeDao;
use App\UseCase\UseCaseInteractor\SignInInteractor;
use App\Infrastructure\Redirect\Redirect;

session_start();
require('../dbconnect.php');

$email = filter_input(INPUT_POST, 'email');
$password = filter_input(INPUT_POST, 'password');

try {
  if (empty($email) || empty($password)) {
    throw new Exception('パスワードとメールアドレスを入力してください');
  }

  $userEmail = new Email($email);
  $inputPassword = new InputPassword($password);
  $useCaseInput = new SignInInput($userEmail, $inputPassword);
  $userDao = new UserDao();
  $userAgeDao = new UserAgeDao();
  $useCase = new SignInInteractor($useCaseInput, $userDao, $userAgeDao);
  $useCaseOutput = $useCase->handler();

  if (!$useCaseOutput->isSuccess()) {
    throw new Exception('メールアドレスまたはパスワードが間違っています');
  }
  Redirect::handler('../index.php');
} catch (Exception $e) {
  $_SESSION['errors'][] = $e->getMessage();
  Redirect::handler('./signin.php');
}


