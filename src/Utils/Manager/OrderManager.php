<?php

namespace App\Utils\Manager;

use App\Entity\Cart;
use App\Entity\CartProduct;
use App\Entity\Order;
use App\Entity\OrderProduct;
use App\Entity\StaticStorage\OrderStaticStorage;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;

class OrderManager extends AbstractBaseManager
{
    private CartManager $cartManager;

    public function __construct(EntityManagerInterface $entityManager, CartManager $cartManager)
    {
        parent::__construct($entityManager);
        $this->cartManager = $cartManager;
    }

    public function getRepository(): ObjectRepository
    {
        return $this->entityManager->getRepository(Order::class);
    }

    public function save(Order $order): void
    {
        $order->setUpdatedAt(new \DateTimeImmutable());
        $this->entityManager->persist($order);
        $this->entityManager->flush();
    }

    public function remove(Order $order): void
    {
        $order->setIsDeleted(true);
        $this->save($order);
    }

    public function createOrderFromCartBySessionId(string $sessionId, User $user): void
    {
        $cart = $this->cartManager->getRepository()->findOneBy(criteria: ['sessionId' => $sessionId]);
        if ($cart) {
            $this->createOrderFromCart($cart, $user);
        }
    }

    public function addOrderProductsFromCart(Order $order, int $cartId): void
    {
        /** @var Cart $cart */
        $cart = $this->cartManager->getRepository()->findOneBy(criteria: ['id' => $cartId]);

        if ($cart) {
            foreach ($cart->getCartProducts() as $cartProduct) {
                $product = $cartProduct->getProduct();

                $orderProduct = new OrderProduct();
                $orderProduct->setAppOrder($order);
                $orderProduct->setQuantity($cartProduct->getQuantity());
                $orderProduct->setPricePerOne($product->getPrice());
                $orderProduct->setProduct($cartProduct->getProduct());

                $order->addOrderProduct($orderProduct);
                $this->entityManager->persist($orderProduct);
            }
        }
    }

    public function createOrderFromCart(Cart $cart, User $user): void
    {
        $order = new Order();
        $order->setOwner($user);
        $order->setStatus(OrderStaticStorage::ORDER_STATUS_CREATED);

        $this->addOrderProductsFromCart($order, $cart->getId());
        $this->recalOrderTotalPrice($order);
        $order->setUpdatedAt(new \DateTimeImmutable());

        $this->entityManager->persist($order);
        $this->entityManager->flush();

        $this->cartManager->remove($cart);
    }

    public function recalOrderTotalPrice(Order $order): void
    {
        $totalPrice = 0;

        /** @var OrderProduct $orderProduct */
        foreach ($order->getOrderProducts()->getValues() as $orderProduct) {
            $totalPrice += $orderProduct->getQuantity() * $orderProduct->getPricePerOne();
        }

        $order->setTotalPrice($totalPrice);
    }
}
