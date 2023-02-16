<?php

namespace App\Utils\Manager;

use App\Entity\Cart;
use Doctrine\Persistence\ObjectRepository;

class CartManager extends AbstractBaseManager
{
    public function getRepository(): ObjectRepository
    {
        return $this->entityManager->getRepository(Cart::class);
    }

    public function save(Cart $cart): void
    {
        $this->entityManager->persist($cart);
        $this->entityManager->flush();
    }

    public function remove(Cart $cart)
    {
        $this->entityManager->remove($cart);
        $this->save($cart);
    }

}
