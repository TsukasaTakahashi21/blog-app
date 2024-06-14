<?php

namespace App\UseCase\UseCaseInteractor;
require_once __DIR__ . '/../../../vendor/autoload.php';
use App\Adapter\QueryService\UserQueryService;
use App\UseCase\UseCaseInput\SignInInput;
use App\UseCase\UseCaseOutput\SignInOutput;
use App\Domain\Entity\User;
use App\Domain\ValueObject\HashedPassword;

final class SignInInteractor
{
  const FAILED_MESSAGE = 'メールアドレスまたは<br />パスワードが間違っています';

  const SUCCESS_MESSAGE = 'ログインしました';

  private $userQueryService;

  private $input;

  public function __construct(SignInInput $input)
  {
    $this->userQueryService = new UserQueryService();
    $this->input = $input;
  }

  public function handler(): SignInOutput
  {
    $user = $this->findUser();

    if ($this->notExistsUser($user)) {
      return new SignInOutput(false, self::FAILED_MESSAGE);
    }

    if ($this->isInvalidPassword($user->password())) {
      return new SignInOutput(false, self::FAILED_MESSAGE);
    }

    $this->saveSession($user);

    return new SignInOutput(true, self::SUCCESS_MESSAGE);
  }

  private function findUser(): ?User
  {
    return $this->userQueryService->findByEmail($this->input->email());
  }

  private function notExistsUser(?User $user): bool
  {
    return is_null($user);
  }

  private function isInvalidPassword(HashedPassword $hashedPassword): bool
  {
    return !$hashedPassword->verify($this->input->password());
  }

  private function saveSession(User $user): void
  {
    $_SESSION['user']['id'] = $user->id()->value();
    $_SESSION['user']['name'] = $user->name()->value();
  }
}
