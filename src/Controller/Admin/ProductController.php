<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use App\Form\EditProductFormType;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/admin/product', name: 'admin_product_')]
class ProductController extends AbstractController
{
    #[Route('/')]
    #[Route('/list', name: 'list')]
    public function index(ProductRepository $productRepository): Response
    {
        return $this->render('admin/product/list.html.twig', [
            'products' => $productRepository->findBy(criteria: ['isDeleted' => false], orderBy: ['id' => 'DESC'], limit: 50),
            'pagination' => [],
        ]);
    }

    #[Route('/edit/{id}', name: 'edit')]
    #[Route('/add', name: 'add')]
    public function edit(Request $request, EntityManagerInterface $entityManager, Product $product = null): Response
    {
        $form = $this->createForm(EditProductFormType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($product);
            $entityManager->flush();

            return $this->redirectToRoute('admin_product_list');
        }
        return $this->render('admin/product/edit.html.twig', [
            'form' => $form->createView(),
            'product' => $product,
        ]);
    }

    #[Route('/delete/{id}', name: 'delete')]
    public function delete(): Response
    {
        return $this->render('admin/product/index.html.twig', [
            'controller_name' => 'ProductController',
        ]);
    }
}
