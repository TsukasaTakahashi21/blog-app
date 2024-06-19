<?php
namespace App\Domain\ValueObject\Blog;


final class BlogComment
{
    private string $value;

    public function __construct(?string $value)
    {
      $this->value = $value ?? ''; 
    }

    public function value(): string
    {
        return $this->value;
    }
}