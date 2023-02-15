<?php

namespace App\Controller\Front;

use App\Entity\Cart;
use App\Entity\CartProduct;
use App\Repository\CartProductRepository;
use App\Repository\CartRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/cart/api', name: 'app_cart_api_')]
class CartApiController extends AbstractController
{
    #[Route('/add', name: 'save', methods: ['POST'])]
    public function saveCart(Request $request, CartRepository $cartRepository, CartProductRepository $cartProductRepository, ProductRepository $productRepository, EntityManagerInterface $entityManager): Response
    {
        $productId = $request->request->get('productId');
        $phpSessionId = $request->cookies->get('PHPSESSID');

        $product = $productRepository->findOneBy(criteria: ['uuid' => $productId]);

        if (!$product) {
            return new JsonResponse([
                'success' => false,
                'data' => [
                    'message' => 'Product not found',
                ],
            ], 404);
        }

        $cart = $cartRepository->findOneBy(criteria: ['sessionId' => $phpSessionId]);

        if (!$cart) {
            $cart = new Cart();
            $cart->setSessionId($phpSessionId);
        }

        $cartProduct = $cartProductRepository->findOneBy(criteria: ['cart' => $cart, 'product' => $product]);

        if (!$cartProduct) {
            $cartProduct = new CartProduct();
            $cartProduct->setCart($cart);
            $cartProduct->setProduct($product);
            $cartProduct->setQuantity(1);
        } else {
            $quantity = $cartProduct->getQuantity();
            $cartProduct->setQuantity($quantity + 1);
        }


        $cart->addCartProduct($cartProduct);

        $entityManager->persist($cart);
        $entityManager->persist($cartProduct);
        $entityManager->flush();

        return new JsonResponse([
            'success' => false,
            'data' => [
                'quantity' => $cartProduct->getQuantity(),
            ],
        ]);
    }
}
