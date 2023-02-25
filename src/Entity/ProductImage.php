<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use App\Repository\ProductImageRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ProductImageRepository::class)]
#[ApiResource(
    operations: [
        new Get(
            normalizationContext: ['groups' => ['product_image:item']]
        ),
        new Post(),
        new GetCollection(
            normalizationContext: ['groups' => ['product_image:list']]
        ),
    ]
)]
class ProductImage
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(groups: [
        'product:list', 'product:list:write', 'product:item', 'product:item:write',
        'order:item',
        'cart:list', 'cart:item',
        'cart_product:list', 'cart_product:item'
    ])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'productImages')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Product $product = null;

    #[ORM\Column(length: 255)]
    #[Groups(groups: [
        'product:list', 'product:list:write', 'product:item', 'product:item:write',
        'order:item',
        'cart:list', 'cart:item',
        'cart_product:list', 'cart_product:item'
    ])]
    private ?string $filenameBig = null;

    #[ORM\Column(length: 255)]
    #[Groups(groups: [
        'product:list', 'product:list:write', 'product:item', 'product:item:write',
        'order:item',
        'cart:list', 'cart:item',
        'cart_product:list', 'cart_product:item'
    ])]
    private ?string $filenameMiddle = null;

    #[ORM\Column(length: 255)]
    #[Groups(groups: [
        'product:list', 'product:list:write', 'product:item', 'product:item:write',
        'order:item',
        'cart:list', 'cart:item',
        'cart_product:list', 'cart_product:item'
    ])]
    private ?string $filenameSmall = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): self
    {
        $this->product = $product;

        return $this;
    }

    public function getFilenameBig(): ?string
    {
        return $this->filenameBig;
    }

    public function setFilenameBig(string $filenameBig): self
    {
        $this->filenameBig = $filenameBig;

        return $this;
    }

    public function getFilenameMiddle(): ?string
    {
        return $this->filenameMiddle;
    }

    public function setFilenameMiddle(string $filenameMiddle): self
    {
        $this->filenameMiddle = $filenameMiddle;

        return $this;
    }

    public function getFilenameSmall(): ?string
    {
        return $this->filenameSmall;
    }

    public function setFilenameSmall(string $filenameSmall): self
    {
        $this->filenameSmall = $filenameSmall;

        return $this;
    }
}
