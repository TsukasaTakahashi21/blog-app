<?php
namespace App\Adapter\Repository;

use App\Adapter\Repository\CommentRepositoryInterface as RepositoryCommentRepositoryInterface;
use PDO;
use App\Domain\Entity\Comment;
use App\Adapter\Repository\CommentRepositoryInterface;

class CommentRepository implements RepositoryCommentRepositoryInterface
{
  private PDO $pdo;

  public function __construct(PDO $pdo)
  {
    $this->pdo = $pdo;
  }

  public function findByBlogId(int $blogId): array
  {
    $sql = 'SELECT * FROM comments WHERE blog_id = :blog_id ORDER BY created_at DESC';
        $statement = $this->pdo->prepare($sql);
        $statement->bindValue(':blog_id', $blogId, PDO::PARAM_INT);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
  }

  public function save(comment $comment): void
  {
    $sql = 'INSERT INTO comments (blog_id, commenter_name, comment, created_at) VALUES (:blog_id, :commenter_name, :comment, :created_at)';
        $statement = $this->pdo->prepare($sql);
        $statement->bindValue(':blog_id', $comment->BlogId(), PDO::PARAM_INT);
        $statement->bindValue(':commenter_name', $comment->CommenterName(), PDO::PARAM_STR);
        $statement->bindValue(':comment', $comment->Comment(), PDO::PARAM_STR);
        $statement->bindValue(':created_at', $comment->CreatedAt()->format('Y-m-d H:i:s'), PDO::PARAM_STR);
        $statement->execute();
  }
}