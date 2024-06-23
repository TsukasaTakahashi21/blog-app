<?php
namespace App\UseCase\UseCaseInteractor;

require_once __DIR__ . '/../../../vendor/autoload.php';
use App\UseCase\UseCaseInput\SignUpInput;
use App\UseCase\UseCaseOutput\SignUpOutput;
use App\Domain\ValueObject\User\NewUser;
use App\Domain\Entity\UserAge;
use App\Domain\ValueObject\User\UserId;
use App\Infrastructure\Dao\UserDao;
use App\Infrastructure\Dao\UserAgeDao;


final class SignUpInteractor
{
  private $input;
  private $userDao;
  private $userAgeDao;

  public function __construct(
    SignUpInput $input,
    UserDao $userDao,
    UserAgeDao $userAgeDao
  ) {
    $this->input = $input;
    $this->userDao = $userDao;
    $this->userAgeDao = $userAgeDao;
  }

  public function handler(): SignUpOutput
  {
    $user = $this->findUser();
    if ($user !== null) {
      return new SignUpOutput(false, 'すでに登録済みのメールアドレスです');
    }

    $this->signup();
    return new SignUpOutput(true, '登録が完了しました');
  }

  private function findUser(): ?array
  {
    return $this->userDao->findByEmail(($this->input->email()));
  }

  public function signup(): void
  {
    $this->userDao->create(
      new NewUser(
        $this->input->name(),
        $this->input->email(),
        $this->input->password(),
        $this->input->age()
      )
      );

    $user = $this->findUser();
    $this->userAgeDao->create(
      new UserAge(new UserId($user['id']), $this->input->age())
    );
  }
}