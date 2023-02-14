<?php

namespace App\Form\DTO;

use App\Entity\Product;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

class EditProductModel
{
    public ?int $id = null;
    #[Assert\NotBlank(message: 'Please enter a title')]
    public ?string $title;
    #[Assert\NotBlank(message: 'Please enter a price')]
    #[Assert\GreaterThanOrEqual(value: 0)]
    public ?string $price;
    #[Assert\File(
        maxSize: '5M',
        mimeTypes: ['image/png', 'image/jpeg', 'image/jpg'],
        mimeTypesMessage: 'Please upload valid image',
    )]
    public ?UploadedFile $newImage;
    #[Assert\NotBlank(message: 'Please enter a quantity')]
    public ?int $quantity;
    public ?int $size;
    public ?string $description;
    public ?bool $isPublished;
    public ?bool $isDeleted;

    public static function makeFromProduct(?Product $product): self
    {
        $model = new self();

        if (!$product) {
            return $model;
        }

        $model->id = $product->getId();
        $model->title = $product->getTitle();
        $model->price = $product->getPrice();
        $model->quantity = $product->getQuantity();
        $model->size = $product->getSize();
        $model->description = $product->getDescription();
        $model->isPublished = $product->getIsPublished();
        $model->isDeleted = $product->getIsDeleted();

        return $model;
    }
}
