<?php

namespace App\Adapter\QueryService;
require_once __DIR__ . '/../../../vendor/autoload.php';
use App\Infrastructure\Dao\UserDao;
use App\Domain\Entity\User;
use App\Domain\ValueObject\User\UserId;
use App\Domain\ValueObject\User\UserName;
use App\Domain\ValueObject\Email;
use App\Domain\ValueObject\HashedPassword;
use App\Domain\ValueObject\User\Age;
use App\Domain\ValueObject\User\RegistrationDate;


final class UserQueryService
{
  private $userDao;

  public function __construct()
  {
    $this->userDao = new UserDao();
  }

  public function findByEmail(Email $email): ?User
  {
    $userMapper = $this->userDao->findByEmail($email);

    return $this->notExistsUser($userMapper)
    ? null
    : new User(
      new UserId($userMapper['id']),
      new UserName($userMapper['name']),
      new Email($userMapper['email']),
      new HashedPassword($userMapper['password']),
      new Age($userMapper['age']),
      new RegistrationDate($userMapper['created_at'])
    );
  }

  private function notExistsUser(?array $user): bool
  {
    return is_null($user);
  }
}