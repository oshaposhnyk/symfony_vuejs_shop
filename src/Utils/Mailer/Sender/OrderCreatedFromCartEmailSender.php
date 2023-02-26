<?php

namespace App\Utils\Mailer\Sender;

use App\Entity\Order;
use App\Utils\Mailer\DTO\MailerOptions;
use App\Utils\Mailer\MailerSender;

class OrderCreatedFromCartEmailSender
{
    private MailerSender $mailerSender;

    public function __construct(MailerSender $mailerSender)
    {
        $this->mailerSender = $mailerSender;
    }

    public function sendEmailToClient(Order $order)
    {
        $mailerOptions = (new MailerOptions())
            ->setRecipient($order->getOwner()->getEmail())
            ->setCc('test@mail.com')
            ->setSubject('New order')
            ->setHtmlTemplate('main/email/client/created_order_from_cart.html.twig')
            ->setContext([
                'order' => $order
            ]);

        $this->mailerSender->sendTemplatedEmail($mailerOptions);
    }

    public function sendEmailToManager(Order $order)
    {
        $mailerOptions = (new MailerOptions())
            ->setRecipient('test@mail.com')
            ->setCc('test@mail.com')
            ->setSubject('New order')
            ->setHtmlTemplate('main/email/client/created_order_from_cart.html.twig')
            ->setContext([
                'order' => $order
            ]);

        $this->mailerSender->sendTemplatedEmail($mailerOptions);
    }
}