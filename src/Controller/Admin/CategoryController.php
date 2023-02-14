<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Form\DTO\EditCategoryModel;
use App\Form\EditCategoryFormType;
use App\Form\Handler\CategoryFormHandler;
use App\Repository\CategoryRepository;
use App\Utils\Manager\CategoryManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/admin/category', name: 'admin_category_')]
class CategoryController extends AbstractController
{
    #[Route('/')]
    #[Route('/list', name: 'list')]
    public function index(CategoryRepository $categoryRepository): Response
    {
        return $this->render('admin/category/list.html.twig', [
            'categories' => $categoryRepository->findBy(
                criteria: ['isDeleted' => false],
                orderBy: ['id' => 'DESC']
            ),
            'pagination' => [],
        ]);
    }

    #[Route('/deleted', name: 'deleted')]
    public function history(CategoryRepository $categoryRepository): Response
    {
        return $this->render('admin/category/list.html.twig', [
            'categories' => $categoryRepository->findBy(
                criteria: ['isDeleted' => true],
                orderBy: ['id' => 'DESC']
            ),
            'pagination' => [],
        ]);
    }

    #[Route('/edit/{id}', name: 'edit')]
    #[Route('/add', name: 'add')]
    public function edit(Request $request, CategoryFormHandler $categoryFormHandler, Category $category = null)
    {
        $editCategoryModal = EditCategoryModel::makeFromCategory($category);

        $form = $this->createForm(EditCategoryFormType::class, $editCategoryModal);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $category = $categoryFormHandler->processEditForm($editCategoryModal, $form);
            $this->addFlash('success', 'Success');

            return $this->redirectToRoute('admin_category_list');
        }

        return $this->render('admin/category/edit.html.twig', [
            'category' => $category,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/delete/{id}', name: 'delete')]
    public function delete(Category $category, CategoryManager $categoryManager): Response
    {
        $categoryManager->remove($category);

        $this->addFlash('success', 'The category was successfully deleted.');

        return $this->redirectToRoute('admin_category_list');
    }

    #[Route('/restore/{id}', name: 'restore')]
    public function restore(Category $category, CategoryManager $categoryManager): Response
    {
        $categoryManager->restore($category);

        $this->addFlash('success', 'The category was successfully restored.');

        return $this->redirectToRoute('admin_category_list');
    }
}
