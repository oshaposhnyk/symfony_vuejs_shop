<?php

namespace App\Controller\Front;

use App\Repository\CartRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/cart', name: 'app_cart_')]
class CartController extends AbstractController
{
    #[Route('/show', name: 'show')]
    public function show(Request $request, CartRepository $cartRepository): Response
    {
        $phpSessionId = $request->cookies->get('PHPSESSID');

        $cart = $cartRepository->findOneBy(criteria: ['sessionId' => $phpSessionId]);

        return $this->render('main/cart/show.html.twig', [
            'cart' => $cart,
        ]);
    }
}
