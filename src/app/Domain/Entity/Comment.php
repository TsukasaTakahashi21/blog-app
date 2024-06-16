<?php
namespace App\Domain\Entity;
use DateTimeImmutable;

final class comment
{
  private int $id;
  private int $blogId;
  private string $commenterName;
  private string $comment;
  private DateTimeImmutable $createdAt;

  public function __construct(int $id, int $blogId, string $commenterName, string $comment, DateTimeImmutable $createdAt)
  {
    $this->id = $id;
    $this->blogId = $blogId;
    $this->commenterName = $commenterName;
    $this->comment = $comment;
    $this->createdAt = $createdAt;
  }

  public function id(): int
  {
    return $this->id;
  }
  public function blogId(): int
  {
    return $this->blogId;
  }
  public function commenterName(): string
  {
    return $this->commenterName;
  }
  public function comment(): string
  {
    return $this->comment;
  }
  public function createdAt(): DateTimeImmutable
  {
    return $this->createdAt;
  }
}

