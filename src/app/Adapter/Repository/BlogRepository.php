<?php
namespace App\Adapter\Repository;

use App\Domain\Entity\Blog;
use App\Infrastructure\DAO\BlogDAO;

class BlogRepository implements BlogRepositoryInterface
{
    private BlogDAO $blogDAO;

    public function __construct(BlogDAO $blogDAO)
    {
        $this->blogDAO = $blogDAO;
    }

    public function save(Blog $blog): void
    {
        $this->blogDAO->create($blog);
    }
}
