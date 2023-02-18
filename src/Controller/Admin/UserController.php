<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\Admin\EditUserFormType;
use App\Form\Admin\Handler\UserFormHandler;
use App\Repository\UserRepository;
use App\Utils\Manager\UserManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/admin/user', name: 'admin_user_')]
#[IsGranted('ROLE_SUPER_ADMIN')]
class UserController extends AbstractController
{
    #[Route('/')]
    #[Route('/list', name: 'list')]
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('admin/user/list.html.twig', [
            'users' => $userRepository->findBy(
                criteria: ['isDeleted' => false],
                orderBy: ['id' => 'DESC']
            ),
            'pagination' => [],
        ]);
    }

    #[Route('/edit/{id}', name: 'edit')]
    #[Route('/add', name: 'add')]
    public function edit(Request $request, UserFormHandler $userFormHandler, User $user = null): Response
    {
        if (!$user) {
            $user = new User();
        }
        $form = $this->createForm(EditUserFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $userFormHandler->processEditForm($form);
            $this->addFlash('success', 'Success');

            return $this->redirectToRoute('admin_user_list');
        }

        return $this->render('admin/user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/delete/{id}', name: 'delete')]
    public function delete(User $user, UserManager $userManager): Response
    {
        $userManager->remove($user);

        $this->addFlash('success', 'The user was successfully deleted.');

        return $this->redirectToRoute('admin_user_list');
    }

    #[Route('/deleted', name: 'deleted')]
    public function history(UserRepository $userRepository): Response
    {
        return $this->render('admin/user/list.html.twig', [
            'users' => $userRepository->findBy(
                criteria: ['isDeleted' => true],
                orderBy: ['id' => 'DESC']
            ),
            'pagination' => [],
        ]);
    }

    #[Route('/restore/{id}', name: 'restore')]
    public function restore(User $user, UserManager $userManager): Response
    {
        $userManager->restore($user);

        $this->addFlash('success', 'The user was successfully restored.');
        return $this->redirectToRoute('admin_user_list');
    }
}
