<?php

namespace App\UseCase\UseCaseInteractor;

use App\UseCase\UseCaseInput\UpdateBlogInput;
use App\UseCase\UseCaseOutput\UpdateBlogOutput;
use App\Adapter\Repository\BlogRepository;
use App\Domain\Entity\Blog;
use App\Domain\ValueObject\Blog\BlogTitle;
use App\Domain\ValueObject\Blog\BlogContent;
use DateTimeImmutable;
use Exception;

final class UpdateBlogInteractor
{
  private BlogRepository $blogRepository;

  public function __construct(BlogRepository $blogRepository)
  {
    $this->blogRepository = $blogRepository;
  }

  public function handle(UpdateBlogInput $input): UpdateBlogOutput
  {
    try {
      $blog = $this->blogRepository->findById($input->id());

      if (!$blog || $blog->userId() !== $input->userId()) {
        throw new Exception('権限がありません');
      }

      $blog->UpdateTitle(new BlogTitle($input->title()->value()));
      $blog->updateContent(new BlogContent($input->content()->value()));

      $this->blogRepository->save($blog);

      return new UpdateBlogOutput(true, 'ブログ記事が更新されました');
    } catch (\Exception $e) {
      return new UpdateBlogOutput(false, 'ブログ記事の更新に失敗しました');
    }
  }
}