<?php

namespace App\UseCase\UseCaseInput;

use App\Domain\ValueObject\Blog\BlogTitle;
use App\Domain\ValueObject\Blog\BlogContent;

final class UpdateBlogInput
{
  private int $id;
  private int $userId;
  private BlogTitle $title;
  private BlogContent $content;

  public function __construct(int $id, int $userId, BlogTitle $title, BlogContent $content)
  {
    $this->id = $id;
    $this->userId = $userId;
    $this->title = $title;
    $this->content = $content;
  }

  public function id(): int
  {
    return $this->id;
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