<?php
namespace App\Adapter\Repository;

use PDO;
use App\Domain\Entity\Blog;
use App\Infrastructure\Dao\BlogDao;
use App\Domain\ValueObject\Blog\BlogTitle;
use App\Domain\ValueObject\Blog\BlogContent;
use App\Domain\ValueObject\Blog\BlogComment;
use DateTimeImmutable;
use Exception;

class BlogRepository implements BlogRepositoryInterface
{
  private PDO $pdo;
  private BlogDao $blogDao;

  public function __construct(PDO $pdo, BlogDao $blogDao)
  {
    $this->pdo = $pdo;
    $this->blogDao = $blogDao;
  }

  public function findById(int $id): ?Blog
  {
    $sql = 'SELECT * FROM blogs WHERE id = :id';
    $statement = $this->pdo->prepare($sql);
    $statement->bindValue(':id', $id, PDO::PARAM_INT);
    $statement->execute();
    $data = $statement->fetch(PDO::FETCH_ASSOC);

    if (!$data) {
      return null;
  }

  $comment = isset($data['comment']) ? $data['comment'] : '';

    return new Blog(
      $data['id'],
      $data['user_id'],
      new BlogTitle($data['title']),
      new BlogContent($data['contents']),
      new DateTimeImmutable($data['created_at']),
      new BlogComment($comment)
    );
  }

    public function save(Blog $blog): void
    {
        $this->blogDao->create($blog);
    }

    public function findAll (string $keyword = '', string $sort = 'DESC'): array
    {
      $sql = "SELECT * FROM blogs";
      if (!empty($keyword)) {
          $sql .= " WHERE title LIKE :keyword OR contents LIKE :keyword";
      }
      $sql .= " ORDER BY created_at " . $sort;

      $statement = $this->pdo->prepare($sql);
      if (!empty($keyword)) {
          $keyword_like = '%' . $keyword . '%';
          $statement->bindValue(':keyword', $keyword_like, PDO::PARAM_STR);
      }
      $statement->execute();
      $data = $statement->fetchAll(PDO::FETCH_ASSOC);

      $blogs = [];
      foreach ($data as $row) {
          $comment = $row['comment'] ?? '';
          $blogs[] = new Blog(
              $row['id'],
              $row['user_id'],
              new BlogTitle($row['title']),
              new BlogContent($row['contents']),
              new DateTimeImmutable($row['created_at']),
              new BlogComment($comment)
          );
      }
      return $blogs;
    }

    public function findByUserId(int $userId, string $sort = 'DESC'): array
    {
      try {
        $sql = "SELECT * FROM blogs WHERE user_id = :user_id ORDER BY created_at " . $sort;
        $statement = $this->pdo->prepare($sql);
        $statement->bindValue(':user_id', $userId, PDO::PARAM_INT);
        $statement->execute();
        $data = $statement->fetchAll(PDO::FETCH_ASSOC);

        $blogs = [];
        
        foreach ($data as $row) {
            $comment = $row['comment'] ?? '';
            $blogs[] = new Blog(
                $row['id'],
                $row['user_id'],
                new BlogTitle($row['title']),
                new BlogContent($row['contents']),
                new DateTimeImmutable($row['created_at']),
                new BlogComment($comment)
            );
          }
        return $blogs;
      } catch (Exception $e) {
      throw new Exception($e->getMessage());
    }
  }
}
