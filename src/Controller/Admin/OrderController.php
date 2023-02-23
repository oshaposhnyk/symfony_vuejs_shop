<?php

namespace App\Controller\Admin;

use App\Entity\Order;
use App\Entity\OrderProduct;
use App\Entity\Product;
use App\Entity\StaticStorage\OrderStaticStorage;
use App\Form\Admin\EditOrderFormType;
use App\Form\Admin\Handler\OrderFormHandler;
use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/order', name: 'admin_order_')]
class OrderController extends AbstractController
{
    #[Route('/')]
    #[Route('/list', name: 'list')]
    public function index(Request $request, OrderRepository $orderRepository): Response
    {
        return $this->render('admin/order/list.html.twig', [
            'orders' => $orderRepository->findBy(
                criteria: ['isDeleted' => false],
                orderBy: ['id' => 'DESC']
            ),
            'orderStatusChoices' => OrderStaticStorage::getOrderStatusChoices(),
            'pagination' => [],
        ]);
    }

    #[Route('/add', name: 'add')]
    #[Route('/edit/{id}', name: 'edit')]
    public function edit(Request $request, Order $order = null, OrderFormHandler $orderFormHandler): Response
    {
        if (!$order) {
            $order = new Order();
        }

        $form = $this->createForm(EditOrderFormType::class, $order);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $order = $orderFormHandler->processEditForm($order, $form);
            $this->addFlash('success', 'Success');

            return $this->redirectToRoute('admin_order_list');
        }

        $orderProducts = [];

//        /** @var OrderProduct $product */
//        foreach ($order->getOrderProducts()->getValues() as $product) {
//            $orderProducts[] = [
//                'id' => $product->getId(),
//                'product' => [
//                    'id' => $product->getProduct()->getId(),
//                    'title' => $product->getProduct()->getTitle(),
//                    'category' => [
//                        'id' => $product->getProduct()->getCategory()->getId(),
//                        'title' => $product->getProduct()->getCategory()->getTitle(),
//                    ],
//                ],
//                'quantity' => $product->getQuantity(),
//                'pricePerOne' => $product->getPricePerOne(),
//
//            ];
//        }

        return $this->render('admin/order/edit.html.twig', [
            'order' => $order,
            'orderProducts' => $orderProducts,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/delete/{id}', name: 'delete')]
    public function delete(Order $order, EntityManagerInterface $entityManager): Response
    {
        if (!$order) {
            throw new NotFoundHttpException();
        }

        $order->setIsDeleted(true);
        $entityManager->persist($order);
        $entityManager->flush();

        $this->addFlash('success', 'The category was successfully deleted.');

        return $this->redirectToRoute('admin_order_list');
    }

    #[Route('/deleted', name: 'deleted')]
    public function history(OrderRepository $orderRepository): Response
    {
        return $this->render('admin/order/list.html.twig', [
            'orders' => $orderRepository->findBy(
                criteria: ['isDeleted' => true],
                orderBy: ['id' => 'DESC']
            ),
            'orderStatusChoices' => OrderStaticStorage::getOrderStatusChoices(),
            'pagination' => [],
        ]);
    }

    #[Route('/restore/{id}', name: 'restore')]
    public function restore(Order $order, EntityManagerInterface $entityManager): Response
    {
        if (!$order) {
            throw new NotFoundHttpException();
        }

        $order->setIsDeleted(false);
        $entityManager->persist($order);
        $entityManager->flush();

        $this->addFlash('success', 'The category was successfully deleted.');

        return $this->redirectToRoute('admin_order_list');
    }
}
