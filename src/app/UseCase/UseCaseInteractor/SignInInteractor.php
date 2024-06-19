<?php

namespace App\UseCase\UseCaseInteractor;
require_once __DIR__ . '/../../../vendor/autoload.php';
use App\UseCase\UseCaseInput\SignInInput;
use App\Infrastructure\Dao\UserDao;
use App\Infrastructure\Dao\UserAgeDao;
use App\UseCase\UseCaseOutput\SignInOutput;
use App\Domain\Entity\User;
use App\Domain\ValueObject\User\UserId;
use App\Domain\ValueObject\User\UserName;
use App\Domain\ValueObject\Email;
use App\Domain\ValueObject\HashedPassword;
use App\Domain\ValueObject\User\Age;
use App\Domain\ValueObject\User\RegistrationDate;
use Exception;


final class SignInInteractor
{
  private $input;
  private $userDao;
  private $userAgeDao;

  public function __construct(
    SignInInput $input,
    UserDao $userDao,
    UserAgeDao $userAgeDao
  ) {
    $this->input = $input;
    $this->userDao = $userDao;
    $this->userAgeDao = $userAgeDao;
  }

  public function handler(): SignInOutput
  {
    $user = $this->findUser();

    if ($user === null) {
      return new SignInOutput(false, 'ユーザーが見つかりませんでした');
    }

    $userMapper = $this->createUserEntity($user);
    if ($userMapper === null) {
      throw new Exception('年齢の登録をしてください');
    }

    if ($this->isInvalidPassword($userMapper->password())) {
      return new SignInOutput(false, 'パスワードが間違っています');
    }

    $this->saveSession($userMapper);
    return new SignInOutput(true, 'ログインに成功しました');
  }

  private function findUser(): ?array
  {
    return $this->userDao->findByEmail($this->input->email());
  }

  private function isInvalidPassword(HashedPassword $hashedPassword): bool
  {
    return !$hashedPassword->verify($this->input->password());
  }

  private function createUserEntity(array $user): ?User
  {
    $userAge = $this->userAgeDao->fetchAll($user['id']);
    if ($userAge === null) {
      return null;
    }
      return new User(
        new UserId($user['id']),
        new UserName($user['name']),
        new Email($user['email']),
        new HashedPassword($user['password']),
        new age($userAge['age']),
        new RegistrationDate($user['created_at']),
      );
    }

  private function saveSession(User $user): void
  {
    $_SESSION['user']['id'] = $user->id()->value();
    $_SESSION['user']['name'] = $user->name()->value();

    if ($user->isPremiumMember()) {
      $_SESSION['user']['memberStatus'] = 'プレミアム会員';
    }
    if (!$user->isPremiumMember()) {
      $_SESSION['user']['memberStatus'] = 'ノーマル会員';
    }
  }
}
