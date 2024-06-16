<?php
namespace App\Adapter\Repository;

use App\Domain\Entity\comment;

interface CommentRepositoryInterface
{
  public function findByBlogId(int $blogId): array;
  public function save(Comment $comment): void;
}