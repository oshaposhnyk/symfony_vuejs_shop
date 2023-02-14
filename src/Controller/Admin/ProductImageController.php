<?php

namespace App\Controller\Admin;

use App\Entity\ProductImage;
use App\Utils\Manager\ProductImageManager;
use App\Utils\Manager\ProductManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/product/image', name: 'admin_product_image_')]
class ProductImageController extends AbstractController
{
    #[Route('/delete/{id}', name: 'delete')]
    public function delete(ProductImage $productImage, ProductManager $productManager, ProductImageManager $productImageManager): Response
    {
        if (!$productImage) {
            return $this->redirectToRoute('admin_product_list');
        }

        $product = $productImage->getProduct();

        $productImageDir = $productManager->getProductImagesDir($product);
        $productImageManager->removeImageFromProduct($productImage, $productImageDir);

        $this->addFlash('success', 'The image was successfully deleted.');

        return $this->redirectToRoute('admin_product_edit', [
            'id' => $product->getId(),
        ]);
    }
}
