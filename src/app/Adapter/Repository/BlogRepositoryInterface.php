<?php
namespace App\Adapter\Repository;

use App\Domain\Entity\Blog;

interface BlogRepositoryInterface
{
    public function save(Blog $blog): void;
}
