<?php

namespace App\EventSubscriber;

use App\Event\OrderCreatedFromCartEvent;
use App\Utils\Mailer\Sender\OrderCreatedFromCartEmailSender;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class OrderCreatedFromCartSendNotificationSubscriber implements EventSubscriberInterface
{
    private OrderCreatedFromCartEmailSender $emailSender;

    public function __construct(OrderCreatedFromCartEmailSender $emailSender)
    {
        $this->emailSender = $emailSender;
    }

    public function onOrderCreatedFromCartEvent(OrderCreatedFromCartEvent $event): void
    {
        $order = $event->getOrder();

        $this->emailSender->sendEmailToClient($order);
        $this->emailSender->sendEmailToManager($order);
    }


    public static function getSubscribedEvents(): array
    {
        return [
            OrderCreatedFromCartEvent::NAME => 'onOrderCreatedFromCartEvent',
        ];
    }
}
