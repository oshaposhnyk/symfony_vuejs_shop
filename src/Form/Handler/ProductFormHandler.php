<?php

namespace App\Form\Handler;

use App\Entity\Product;
use App\Form\DTO\EditProductModel;
use App\Utils\File\FileSaver;
use App\Utils\Manager\ProductManager;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ProductFormHandler
{
    private FileSaver $fileSaver;
    private ProductManager $productManager;

    public function __construct(ProductManager $productManager, FileSaver $fileSaver)
    {
        $this->fileSaver = $fileSaver;
        $this->productManager = $productManager;
    }

    public function processEditForm(EditProductModel $editProductModel, Form $form): Product
    {
        $product = new Product();

        if ($editProductModel->id) {
            $product = $this->productManager->find($editProductModel->id);
        }

        $product->setTitle($editProductModel->title);
        $product->setPrice($editProductModel->price);
        $product->setQuantity($editProductModel->quantity);
        $product->setDescription($editProductModel->description);
        $product->setIsPublished($editProductModel->isPublished);
        $product->getIsDeleted($editProductModel->isDeleted);
        $product->setCategory($editProductModel->category);

        $this->productManager->save($product);

        $newImageFile = $form->get('newImage')->getData();
        $tempIMageFilename = $newImageFile
            ? $this->saveFileUploadedFileIntoTemp($newImageFile)
            : null;

        $this->productManager->updateProductImages($product, $tempIMageFilename);

        return $product;
    }

    private function saveFileUploadedFileIntoTemp(UploadedFile $newImageFile): string
    {
        return $this->fileSaver->saveFileUploadedFileIntoTemp($newImageFile);
    }
}
