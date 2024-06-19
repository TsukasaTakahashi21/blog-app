<?php
namespace App\Domain\Entity;
require_once __DIR__ . '/../../../vendor/autoload.php';

use App\Domain\ValueObject\User\UserId;
use App\Domain\ValueObject\User\Age;

final class UserAge
{
  private $id;
  private $age;

  public function __construct(UserId $id, Age $age)
  {
    $this->id = $id;
    $this->age = $age;
  }

  public function id(): UserId
  {
    return $this->id;
  }

  public function age(): Age
  {
    return $this->age;
  }
}