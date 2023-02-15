<?php

namespace App\Controller\Front;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class EmbedController extends AbstractController
{
    public function showLastProducts(Request $request, ProductRepository $productRepository, int $productCount = 4, int $categoryId = null): Response
    {
        $params = [];
        if ($categoryId) {
            $params['category'] = $categoryId;
        }
        $products = $productRepository->findBy(criteria: $params, limit: $productCount);

        return $this->render('main/_embed/_last_products.html.twig', [
            'products' => $products,
        ]);
    }
}
