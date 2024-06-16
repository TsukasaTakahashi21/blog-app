<?php
namespace App\UseCase\UseCaseInput;

use App\Domain\ValueObject\Blog\BlogTitle;
use App\Domain\ValueObject\Blog\BlogContent;
use App\Domain\ValueObject\Blog\BlogComment;

final class CreateBlogInput
{
  private int $userId;
  private BlogTitle $title;
  private BlogContent $content;
  private BlogComment $comment;
  

  public function __construct(
    int $userId, 
    BlogTitle $title, 
    BlogContent $content,
    BlogComment $comment
  ) {
    $this->userId = $userId;
    $this->title = $title;
    $this->content = $content;
    $this->comment = $comment;
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

  public function comment(): BlogComment
  {
    return $this->comment;
  }
}