<?php

namespace App\Form\Handler;

use App\Entity\Category;
use App\Utils\Manager\CategoryManager;
use Symfony\Component\Form\Form;

class CategoryFormHandler
{
    private CategoryManager $categoryManager;

    public function __construct(CategoryManager $categoryManager)
    {
        $this->categoryManager = $categoryManager;
    }

    public function processEditForm(Category $category, Form $form): Category
    {
        $data = $form->getData();
        $category->setTitle($data->getTitle());

        $this->categoryManager->save($category);

        return $category;
    }
}
