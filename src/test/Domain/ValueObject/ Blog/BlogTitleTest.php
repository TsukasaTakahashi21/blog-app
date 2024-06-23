<?php
namespace App\test\Domain\ValueObject\Blog;

use PHPUnit\Framework\TestCase;
use App\Domain\ValueObject\Blog\BlogTitle;
use InvalidArgumentException;

class BlogTitleTest extends TestCase
{
  public function test_有効なタイトル()
  {
    $title = new BlogTitle('これは有効なタイトルです');
    $this->assertEquals('これは有効なタイトルです', $title->value());
  }

  public function test_空のタイトルは例外をスローする()
  {
    $this->expectException(InvalidArgumentException::class);
    $this->expectExceptionMessage('タイトルは必須です');
    new BlogTitle('');
  }
}