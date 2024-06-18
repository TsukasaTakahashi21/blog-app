<?php
namespace App\Infrastructure\Dao;

use PDO;
use App\Domain\Entity\Blog;

class BlogDao
{
  private PDO $pdo;

  public function __construct(PDO $pdo)
  {
    $this->pdo = $pdo;
  }

  public function create(Blog $blog): void
  {
    $sql = 'INSERT INTO blogs (user_id, title, contents, created_at) VALUES (:user_id, :title, :contents, NOW())';
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':user_id', $blog->userId(), PDO::PARAM_INT);
        $stmt->bindValue(':title', $blog->title()->value(), PDO::PARAM_STR);
        $stmt->bindValue(':contents', $blog->content()->value(), PDO::PARAM_STR);
        $stmt->execute();
  }

  public function insert(Blog $blog): void
  {
    $stmt = $this->pdo->prepare('INSERT INTO blogs (user_id, title, contents, created_at) VALUES (?, ?, ?, ?)');
        $stmt->execute([
            $blog->userId(),
            $blog->title()->value(),
            $blog->content()->value(),
            $blog->createdAt()->format('Y-m-d H:i:s')
        ]);
  }

  public function update(Blog $blog): void
  {
    $stmt = $this->pdo->prepare('UPDATE blogs SET title = ?, contents = ? WHERE id = ?');
        $stmt->execute([
            $blog->title()->value(),
            $blog->content()->value(),
            $blog->id()
        ]);
  }
}