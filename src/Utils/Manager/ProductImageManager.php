<?php

namespace App\Utils\Manager;

use App\Entity\Product;
use App\Entity\ProductImage;
use App\Utils\File\ImageResizer;
use App\Utils\Filesystem\FilesystemWorker;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;

class ProductImageManager extends AbstractBaseManager
{
    private FilesystemWorker $filesystemWorker;
    private string $uploadsTempDir;
    private ImageResizer $imageResizer;

    public function __construct(EntityManagerInterface $entityManager, FilesystemWorker $filesystemWorker, ImageResizer $imageResizer, string $uploadsTempDir)
    {
        parent::__construct($entityManager);
        $this->filesystemWorker = $filesystemWorker;
        $this->uploadsTempDir = $uploadsTempDir;
        $this->imageResizer = $imageResizer;
    }

    public function saveImageForProduct(Product $product, string $productDir, string $tempImageFilename = null): ?ProductImage
    {
        if (!$tempImageFilename) {
            return null;
        }

        $this->filesystemWorker->createFolderIfItNotExist($productDir);

        $filenameId = uniqid();
        $imageSmallParams = [
            'width' => 60,
            'height' => null,
            'newFolder' => $productDir,
            'newFilename' => sprintf('%s-%s.jpg', $filenameId, 'small'),
        ];
        $imageSmall = $this->imageResizer->resizeImageAndSave($this->uploadsTempDir, $tempImageFilename, $imageSmallParams);

        $imageMiddleParams = [
            'width' => 430,
            'height' => null,
            'newFolder' => $productDir,
            'newFilename' => sprintf('%s-%s.jpg', $filenameId, 'middle'),
        ];
        $imageMiddle = $this->imageResizer->resizeImageAndSave($this->uploadsTempDir, $tempImageFilename, $imageMiddleParams);

        $imageBigParams = [
            'width' => 800,
            'height' => null,
            'newFolder' => $productDir,
            'newFilename' => sprintf('%s-%s.jpg', $filenameId, 'big'),
        ];
        $imageBig = $this->imageResizer->resizeImageAndSave($this->uploadsTempDir, $tempImageFilename, $imageBigParams);

        $productImage = new ProductImage();
        $productImage->setFilenameSmall($imageSmall);
        $productImage->setFilenameMiddle($imageMiddle);
        $productImage->setFilenameBig($imageBig);
        $productImage->setProduct($product);
        $this->save($productImage);

        return $productImage;
    }

    public function save(ProductImage $productImage): void
    {
        $this->entityManager->persist($productImage);
        $this->entityManager->flush();
    }

    public function removeImageFromProduct(ProductImage $productImage, string $productImageDir): void
    {
        $smallFilePath = $productImageDir.'/'.$productImage->getFilenameSmall();
        $this->filesystemWorker->remove($smallFilePath);

        $middleFilePath = $productImageDir.'/'.$productImage->getFilenameMiddle();
        $this->filesystemWorker->remove($middleFilePath);

        $bigFilePath = $productImageDir.'/'.$productImage->getFilenameBig();
        $this->filesystemWorker->remove($bigFilePath);

        $product = $productImage->getProduct();
        $product->removeProductImage($productImage);

        $this->entityManager->flush();
    }

    public function getRepository(): ObjectRepository
    {
        return $this->entityManager->getRepository(ProductImage::class);
    }
}
