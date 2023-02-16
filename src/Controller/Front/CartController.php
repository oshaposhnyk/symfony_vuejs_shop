<?php

namespace App\Controller\Front;

use App\Repository\CartRepository;
use App\Utils\Manager\OrderManager;
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

    #[Route('/create', name: 'create')]
    public function create(Request $request, OrderManager $orderManager): Response
    {
        $phpSessionId = $request->cookies->get('PHPSESSID');
        $user = $this->getUser();

        $orderManager->createOrderFromCartBySessionId($phpSessionId, $user);

        return $this->redirectToRoute('app_cart_show');
    }
}
