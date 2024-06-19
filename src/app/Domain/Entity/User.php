<?php

namespace App\Domain\Entity;
require_once __DIR__ . '/../../../vendor/autoload.php';
use App\Domain\ValueObject\User\UserId;
use App\Domain\ValueObject\User\UserName;
use App\Domain\ValueObject\Email;
use App\Domain\ValueObject\HashedPassword;
use App\Domain\ValueObject\User\Age;
use App\Domain\ValueObject\User\RegistrationDate;

final class User
{
  private $id;
  private $name;
  private $email;
  private $password;
  private $age;
  private $registrationDate;

  public function __construct(
    UserId $id,
    UserName $name,
    Email $email,
    HashedPassword $password,
    Age $age,
    RegistrationDate $registrationDate
  ) {
      $this->id = $id;
      $this->name = $name;
      $this->email = $email;
      $this->password = $password;
      $this->age = $age;
      $this->registrationDate = $registrationDate;
  }

  public function id(): UserId
  {
    return $this->id;
  }

  public function name(): UserName
  {
    return $this->name;
  }

  public function email(): Email
  {
    return $this->email;
  }

  public function password(): HashedPassword
  {
      return $this->password;
  }

  public function age(): Age
  {
    return $this->age;
  }

  public function registrationDate(): RegistrationDate
  {
    return $this->registrationDate;
  }

  public function isPremiumMember(): bool
  {
    return $this->registrationDate()->isLongTermCustomer() && $this->age->isAdult();
  }
}