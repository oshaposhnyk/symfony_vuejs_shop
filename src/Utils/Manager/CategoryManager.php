<?php

namespace App\Utils\Manager;

use App\Entity\Category;
use App\Entity\Product;
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
        $category->setIsDeleted(true);
        /** @var Product $product */
        foreach ($category->getProducts()->getValues() as $product) {
            $product->setIsDeleted(true);
        }
        $this->save($category);
    }

    public function restore(Category $category): void
    {
        $category->setIsDeleted(false);
        /** @var Product $product */
        foreach ($category->getProducts()->getValues() as $product) {
            $product->setIsDeleted(false);
        }
        $this->save($category);
    }
}
