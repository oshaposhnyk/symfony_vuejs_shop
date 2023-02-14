<?php

namespace App\Utils\Manager;

use App\Entity\Category;
 use Doctrine\Persistence\ObjectRepository;

class CategoryManager extends AbstractBaseManager
{
    public function getRepository(): ObjectRepository
    {
        return $this->entityManager->getRepository(Category::class);
    }

    public function save(Category $category): void
    {
        $this->entityManager->persist($category);
        $this->entityManager->flush();
    }

    public function remove(Category $category): void
    {
        $this->entityManager->remove($category);
        $this->save($category);
    }
}
