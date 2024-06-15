<?php
namespace App\Domain\ValueObject\Blog;

final class BlogContent
{
    private string $value;

    public function __construct(string $value)
    {
        if (empty($value)) {
            throw new \InvalidArgumentException('内容は必須です');
        }
        $this->value = $value;
    }

    public function value(): string
    {
        return $this->value;
    }
}
