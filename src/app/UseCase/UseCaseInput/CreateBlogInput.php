<?php
namespace App\UseCase\UseCaseInput;

use App\Domain\ValueObject\Blog\BlogTitle;
use App\Domain\ValueObject\Blog\BlogContent;

final class CreateBlogInput
{
  private int $userId;
  private BlogTitle $title;
  private BlogContent $content;

  public function __construct(int $userId, BlogTitle $title, BlogContent $content)
  {
    $this->userId = $userId;
    $this->title = $title;
    $this->content = $content;
  }

  public function userId(): int
  {
    return $this->userId;
  }

  public function title(): BlogTitle
  {
    return $this->title;
  }

  public function content(): BlogContent
  {
    return $this->content;
  }
}