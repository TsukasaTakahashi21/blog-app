<?php
namespace App\UseCase\UseCaseInteractor;

use App\UseCase\UseCaseInput\CreateBlogInput;
use App\Adapter\Repository\BlogRepository;
use App\Domain\Entity\Blog;
use App\UseCase\UseCaseOutput\CreateBlogOutput;

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
        $input->userID(), 
        $input->title(), 
        $input->content()
      );
      
      $this->blogRepository->save($blog);
      return new CreateBlogOutput(true, 'ブログ記事が作成されました。');
    } catch (\Exception $e) {
      return new CreateBlogOutput(false, 'ブログ記事の作成に失敗しました。');
    }
  }
}