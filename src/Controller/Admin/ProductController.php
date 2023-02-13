<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use App\Form\EditProductFormType;
use App\Form\Handler\ProductFormHandler;
use App\Repository\ProductRepository;
use App\Utils\Manager\ProductManager;
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
    public function edit(Request $request, ProductFormHandler $productFormHandler, Product $product = null): Response
    {
        $form = $this->createForm(EditProductFormType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $product = $productFormHandler->processEditForm($product, $form);
            $this->addFlash('success', 'Success');

            return $this->redirectToRoute('admin_product_list');
        }

        return $this->render('admin/product/edit.html.twig', [
            'images' => $product->getProductImages(),
            'form' => $form->createView(),
            'product' => $product,
        ]);
    }

    #[Route('/delete/{id}', name: 'delete')]
    public function delete(Product $product, ProductManager $productManager): Response
    {
        $productManager->remove($product);

        $this->addFlash('success', 'Success');

        return $this->redirectToRoute('admin_product_list');
    }
}
