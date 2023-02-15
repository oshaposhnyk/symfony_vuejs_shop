<?php

namespace App\Controller\Front;

use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/product', name: 'app_product_')]
class ProductController extends AbstractController
{
    #[Route('/{uuid}', name: 'show')]
    public function index(Request $request, Product $product): Response
    {
        if (!$product) {
            throw new NotFoundHttpException();
        }

        $images = $product->getProductImages();

        return $this->render('main/product/show.html.twig', [
            'product' => $product,
        ]);
    }
}
