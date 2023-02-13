<?php

namespace App\Form\Handler;

use App\Entity\Product;
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

    public function processEditForm(Product $product, Form $form): Product
    {
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
