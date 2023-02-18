<?php

namespace App\Form\Admin\Handler;

use App\Entity\User;
use App\Utils\Manager\UserManager;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFormHandler
{
    private UserManager $userManager;
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserManager $userManager, UserPasswordHasherInterface $passwordHasher)
    {
        $this->userManager = $userManager;
        $this->passwordHasher = $passwordHasher;
    }

    public function processEditForm(FormInterface $form): User
    {
        $plainPassword = $form->get('plainPassword')->getData();
        $newEmail = $form->get('newEmail')->getData();

        /** @var User $user */
        $user = $form->getData();

        if (!$user->getId()) {
            $user->setEmail($newEmail);
        }

        if ($plainPassword) {
            $encocdedPassword = $this->passwordHasher->hashPassword($user, $plainPassword);
            $user->setPassword($encocdedPassword);
        }

        $this->userManager->save($user);

        return $user;
    }
}
