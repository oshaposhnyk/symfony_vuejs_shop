<?php

namespace App\Form\Handler;

use App\Entity\Product;
use App\Utils\File\FileSaver;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ProductFormHandler
{
    private EntityManagerInterface $entityManager;
    private FileSaver $fileSaver;

    public function __construct(EntityManagerInterface $entityManager, FileSaver $fileSaver)
    {
        $this->entityManager = $entityManager;
        $this->fileSaver = $fileSaver;
    }

    public function processEditForm(Product $product, Form $form): Product
    {
        $this->entityManager->persist($product);

        $newImageFile = $form->get('newImage')->getData();
        $tempIMageFilename = $newImageFile
            ? $this->saveFileUploadedFileIntoTemp($newImageFile)
            : null;
        $this->entityManager->flush();

        return $product;
    }

    private function saveFileUploadedFileIntoTemp(UploadedFile $newImageFile): string
    {
        return $this->fileSaver->saveFileUploadedFileIntoTemp($newImageFile);
    }
}
