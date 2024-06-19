<?php
namespace App\Domain\Entity;

use App\Domain\ValueObject\Blog\BlogTitle;
use App\Domain\ValueObject\Blog\BlogContent;
use App\Domain\ValueObject\Blog\BlogComment;
use DateTimeImmutable;

final class Blog
{
  private int $id;
  private int $userId;
  private BlogTitle $title;
  private BlogContent $contents;
  private DateTimeImmutable $createdAt;
  private BlogComment $comment;

  public function __construct(
    int $id, 
    int $userId, 
    BlogTitle $title, 
    BlogContent $contents, 
    DateTimeImmutable $createdAt,
    BlogComment $comment
  ) {
    $this->id = $id;
    $this->userId = $userId;
    $this->title = $title;
    $this->contents = $contents;
    $this->createdAt = $createdAt;
    $this->comment = $comment;
  }

  public function Id(): int
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
    return $this->contents;
  }

  public function createdAt(): DateTimeImmutable
  {
    return $this->createdAt;
  }

  public function comment(): BlogComment
  {
    return $this->comment;
  }

  public function updateTitle(BlogTitle $title): void
  {
    $this->title = $title;
  }

    public function updateContent(BlogContent $content): void
  {
    $this->contents = $content;
  }

}
