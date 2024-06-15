<?php
namespace App\Domain\ValueObject\Blog;

final class BlogTitle
{
  private string $value;

  public function __construct(string $value)
  {
    if (empty($value)) {
      throw new \InvalidArgumentException('タイトルは必須です');
    }
    $this->value = $value;
  }

  public function value(): string
  {
    return $this->value;
  }
}
