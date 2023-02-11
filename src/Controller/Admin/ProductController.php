<?php

namespace App\Controller\Admin;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
    public function edit(): Response
    {
        return $this->render('admin/product/index.html.twig');
    }

    #[Route('/delete/{id}', name: 'delete')]
    public function delete(): Response
    {
        return $this->render('admin/product/index.html.twig', [
            'controller_name' => 'ProductController',
        ]);
    }
}
