<?php
namespace App\Adapter\Repository;

use App\Domain\Entity\Blog;

interface BlogRepositoryInterface
{
    public function save(Blog $blog): void;
    public function findAll(string $keyword = '', string $sort = 'DESC'): array;
    public function findById(int $id): ?Blog;
}
