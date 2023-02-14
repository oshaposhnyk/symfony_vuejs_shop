<?php

namespace App\Controller;

use App\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/category', name: 'app_category_')]
class CategoryController extends AbstractController
{
    #[Route('/{slug}', name: 'show')]
    public function show(Request $request, Category $category): Response
    {
        if (!$category) {
            throw new NotFoundHttpException();
        }

        $products = $category->getProducts()->getValues();

        return $this->render('main/category/show.html.twig', [
            'category' => $category,
            'products' => $products,
        ]);
    }
}
