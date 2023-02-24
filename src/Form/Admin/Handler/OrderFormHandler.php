<?php

namespace App\Form\Admin\Handler;

use App\Entity\Order;
use App\Utils\Manager\OrderManager;
use Symfony\Component\Form\FormInterface;

class OrderFormHandler
{

    private OrderManager $orderManager;

    public function __construct(OrderManager $orderManager)
    {
        $this->orderManager = $orderManager;
    }

    public function processEditForm(Order $order): Order
    {

        $this->orderManager->recalOrderTotalPrice($order);
        $this->orderManager->save($order);

        return $order;
    }
}
