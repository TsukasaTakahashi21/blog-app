<?php
namespace App\UseCase\UseCaseInteractor;
require_once __DIR__ . '/../../../vendor/autoload.php';
use App\Adapter\QueryService\UserQueryService;
use App\Adapter\Repository\UserRepository;
use App\UseCase\UseCaseInput\SignUpInput;
use App\UseCase\UseCaseOutput\SignUpOutput;
use App\Domain\ValueObject\User\NewUser;
use App\Domain\Entity\User;


final class SignUpInteractor
{
  const ALLREADY_EXISTS_MESSAGE = 'すでに登録済みのメールアドレスです';

  const COMPLETED_MESSAGED = '登録が完了しました';

  private $userRepository;
  private $userQueryService;

  private $input;

  public function __construct(SignUpInput $input)
  {
    $this->userRepository = new UserRepository();
    $this->userQueryService = new UserQueryService();
    $this->input = $input;
  }

  public function handler(): SignUpOutput
  {
    $user = $this->findUser();
    if ($this->existsUser($user)) {
      return new SignUpOutput(false, self::ALLREADY_EXISTS_MESSAGE);
    }

    $this->signup();
    return new SignUpOutput(true, self::COMPLETED_MESSAGED);
  }

  private function findUser(): ?User
  {
    return $this->userQueryService->findByEmail(($this->input->email()));
  }

  public function existsUser(?User $user): bool
  {
    return !is_null($user);
  }

  private function signup(): void
  {
    $this->userRepository->insert(
      new NewUser(
        $this->input->name(),
        $this->input->email(),
        $this->input->password()
      )
    );
  }
}