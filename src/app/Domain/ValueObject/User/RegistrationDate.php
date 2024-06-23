<?php
namespace App\Domain\ValueObject\User;

use Exception;
use DateTime;
use DateTimeZone;

final class RegistrationDate
{
  const INVALID_MESSAGE = '登録日が不正です';

  const REGISTRATION_DATE_REGULAR_EXPRESSIONS = '/\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}/';

  private $value;

  public function __construct(string $value)
  {
    if ($this->isInvalid($value)) {
      throw new Exception(self::INVALID_MESSAGE);
    }

    $this->value = $value;
  }

  public function value(): string
  {
    return $this->value;
  }

  private function isInvalid(string $value): bool
  {
    return !preg_match(self::REGISTRATION_DATE_REGULAR_EXPRESSIONS, $value);
  }

  public function isLongTermCustomer(): bool
  {
    $today = new DateTime('now', new DateTimeZone('Asia/Tokyo'));
    $registrationDateAndTime = new DateTime($this->value);
    $registrationDate = new DateTime(
      $registrationDateAndTime->format('Y-m-d')
    );

    $interval = $today->diff($registrationDate);
    $periodFromRegistration = $interval->days;
    return $periodFromRegistration >= 30;
  }
}