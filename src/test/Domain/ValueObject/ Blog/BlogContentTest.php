<?php
namespace App\test\Domain\ValueObject\Blog;

use PHPUnit\Framework\TestCase;
use App\Domain\ValueObject\Blog\BlogContent;
use InvalidArgumentException;

class BLogContentTest extends TestCase
{
  public function test_有効な内容()
  {
    $content = new BlogContent('これは有効な内容です');
    $this->assertEquals('これは有効な内容です', $content->value());
  }

  public function test_からの内容は例外をスローする()
  {
    $this->expectException(InvalidArgumentException::class);
    $this->expectExceptionMessage('内容は必須です');
    new BlogContent('');
  }
}