<?php
namespace App\Domain\ValueObject\User;
use Exception;

final class Age
{
  const INVALID_MESSAGE = '正しい年齢をご記入してください';

  private $value;

  public function __construct(int $value)
  {
    if ($this->isInvalid($value)) {
      throw new Exception(self::INVALID_MESSAGE);
    }
    $this->value = $value;
  }

  public function value(): int{
    return $this->value;
  }

  public function isInValid(int $value): bool
  {
    return $value < 0 || 150 < $value;
  }

  public function isAdult(): bool
  {
    return $this->value >=18;
  }
}
