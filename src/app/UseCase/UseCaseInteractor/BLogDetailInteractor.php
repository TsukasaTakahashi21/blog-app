<?php
namespace App\UseCase\UseCaseInteractor;

use App\Adapter\Repository\BlogRepositoryInterface;
use App\Adapter\Repository\CommentRepositoryInterface;
use App\Domain\Entity\Blog;
use App\Domain\Entity\Comment;
use DateTimeImmutable;

final class BlogDetailInteractor
{
  private BlogRepositoryInterface $blogRepository;
  private CommentRepositoryInterface $commentRepository;

  public function __construct(BlogRepositoryInterface $blogRepository, CommentRepositoryInterface $commentRepository)
  {
    $this->blogRepository = $blogRepository;
    $this->commentRepository = $commentRepository;
  }

  public function blogDetail(int $blogId): array
  {
    $blog = $this->blogRepository->findById($blogId);

    if(!$blog) {
      throw new \Exception('記事が見つかりませんでした。');
    }


    $commentsData = $this->commentRepository->findByBlogId($blogId);
    $comments = [];

    foreach ($commentsData as $commentData) {
      $comments[] = new comment(
        $commentData['id'],
        $commentData['blog_id'],
        $commentData['commenter_name'],
        $commentData['comment'],
        new DateTimeImmutable($commentData['created_at'])
      );
    }

    return [
      'blog' => $blog,
      'comments' => $comments,
    ];
  }
}