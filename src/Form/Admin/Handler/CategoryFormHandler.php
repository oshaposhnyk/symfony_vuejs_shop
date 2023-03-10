<?php

namespace App\Form\Admin\Handler;

use App\Entity\Category;
use App\Form\Admin\DTO\EditCategoryModel;
use App\Utils\Manager\CategoryManager;
use Symfony\Component\Form\FormInterface;

class CategoryFormHandler
{
    private CategoryManager $categoryManager;

    public function __construct(CategoryManager $categoryManager)
    {
        $this->categoryManager = $categoryManager;
    }

    public function processEditForm(EditCategoryModel $editCategoryModel, FormInterface $form): Category
    {
        $category = new Category();

        if ($editCategoryModel->id) {
            $category = $this->categoryManager->find($editCategoryModel->id);
        }
        $category->setTitle($editCategoryModel->title);
        $this->categoryManager->save($category);

        return $category;
    }
}
