<?php
namespace App\UseCase\UseCaseInteractor;

use App\UseCase\UseCaseInput\CreateBlogInput;
use App\Adapter\Repository\BlogRepository;
use App\Domain\Entity\Blog;
use App\Domain\ValueObject\Blog\BlogTitle;
use App\Domain\ValueObject\Blog\BlogContent;
use App\Domain\ValueObject\Blog\BlogComment;
use App\UseCase\UseCaseOutput\CreateBlogOutput;
use DateTimeImmutable; 

final class CreateBlogInteractor
{
  private BlogRepository $blogRepository;

  public function __construct(BlogRepository $blogRepository)
  {
    $this->blogRepository = $blogRepository;
  }

  public function handle(CreateBlogInput $input): CreateBlogOutput
  {
    try {
      $blog = new Blog(
        0,
        $input->userId(), 
        $input->title(), 
        $input->content(),
        new DateTimeImmutable(),
        $input->comment()
      );

      $this->blogRepository->save($blog);
      return new CreateBlogOutput(true, 'ブログ記事が作成されました。');
    } catch (\Exception $e) {
      return new CreateBlogOutput(false, 'ブログ記事の作成に失敗しました。');
    }
  }
}