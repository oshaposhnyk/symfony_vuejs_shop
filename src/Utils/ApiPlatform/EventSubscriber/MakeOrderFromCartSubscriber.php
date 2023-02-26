<?php

namespace App\Utils\ApiPlatform\EventSubscriber;

use ApiPlatform\Symfony\EventListener\EventPriorities;
use App\Entity\Order;
use App\Entity\StaticStorage\OrderStaticStorage;
use App\Entity\User;
use App\Event\OrderCreatedFromCartEvent;
use App\Utils\Manager\OrderManager;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class MakeOrderFromCartSubscriber implements EventSubscriberInterface
{
    private Security $security;
    private OrderManager $orderManager;
    private EventDispatcherInterface $eventDispatcher;

    public function __construct(Security $security, OrderManager $orderManager, EventDispatcherInterface $eventDispatcher)
    {
        $this->security = $security;
        $this->orderManager = $orderManager;
        $this->eventDispatcher = $eventDispatcher;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::VIEW => [
                [
                    'makeOrder', EventPriorities::PRE_WRITE
                ],
                [
                    'sendNotificationAboutNewOrder', EventPriorities::POST_WRITE
                ]
            ]
        ];
    }

    public function sendNotificationAboutNewOrder(ViewEvent $event)
    {
        $order = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if (!$order instanceof Order || Request::METHOD_POST !== $method) {
            return;
        }
        $event = new OrderCreatedFromCartEvent($order);
        $this->eventDispatcher->dispatch($event);
    }

    public function makeOrder(ViewEvent $event)
    {
        $order = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if (!$order instanceof Order || Request::METHOD_POST !== $method) {
            return;
        }
        

        /** @var User $user */
        $user = $this->security->getUser();

        if (!$user) {
            return;
        }

        $order->setOwner($user);

        $contentJson = $event->getRequest()->getContent();
        if (!$contentJson) {
            return;
        }

        $content = json_decode($contentJson, true);
        if (!array_key_exists('cartId', $content)) {
            return;
        }

        $cartId = $content['cartId'];

        $this->orderManager->addOrderProductsFromCart($order, $cartId);
        $this->orderManager->recalOrderTotalPrice($order);

        $order->setStatus(OrderStaticStorage::ORDER_STATUS_CREATED);

    }
}