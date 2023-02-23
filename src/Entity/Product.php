<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\DateFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Doctrine\Orm\Filter\BooleanFilter;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Uid\Uuid;


#[ApiResource(
    operations: [
        new Get(
            normalizationContext: ['groups' => ['product:item']]
        ),
        new GetCollection(
            normalizationContext: ['groups' => ['product:list']]
        ),
        new Post(
            normalizationContext: ['groups' => ['product:list:write']],
            security: "is_granted('ROLE_ADMIN')"
        ),
        new Patch(
            normalizationContext: ['groups' => ['product:item:write']],
            security: "is_granted('ROLE_ADMIN')"
        ),
     ],
    formats: ['jsonld', 'json'],
    order: [
        'createdAt' => 'DESC',
    ],
)]
#[ORM\Entity(repositoryClass: ProductRepository::class)]
#[ApiFilter(BooleanFilter::class, properties: ['isPublished'])]
#[ApiFilter(DateFilter::class, properties: ['createdAt'])]
#[ApiFilter(SearchFilter::class,  properties: ['category' => 'exact'])]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[ApiProperty(identifier: false)]
    #[Groups(groups: ['product:list', 'product:item', 'order:item'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(groups: ['product:list', 'product:list:write', 'product:item', 'product:item:write', 'order:item'])]
    private ?string $title = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 6, scale: 2)]
    #[Groups(groups: ['product:list', 'product:list:write', 'product:item', 'product:item:write', 'order:item'])]
    private ?string $price = null;

    #[ORM\Column]
    #[Groups(groups: ['product:list', 'product:list:write', 'product:item', 'product:item:write', 'order:item'])]
    private ?int $quantity = null;

    #[ORM\Column]
    #[Groups(groups: ['product:list', 'product:item'])]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(groups: ['product:list', 'product:list:write', 'product:item', 'product:item:write'])]
    private ?string $description = null;

    #[ORM\Column]
    #[Groups(groups: ['product:list', 'product:list:write', 'product:item', 'product:item:write'])]
    private ?bool $isPublished = null;

    #[ORM\Column]
    private ?bool $isDeleted = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(groups: ['product:list', 'product:list:write', 'product:item', 'product:item:write'])]
    private ?string $size = null;

    #[ORM\OneToMany(mappedBy: 'product', targetEntity: ProductImage::class, cascade: ['persist'], orphanRemoval: true)]
    private Collection $productImages;

    #[Gedmo\Slug(fields: ['title'])]
    #[ORM\Column(length: 128, nullable: true)]
    private ?string $slug = null;

    #[ORM\Column(type: 'uuid')]
    #[Groups(groups: ['product:list', 'product:item'])]
    #[ApiProperty(identifier: true)]
    private ?Uuid $uuid = null;

    #[ORM\ManyToOne(inversedBy: 'products')]
    #[Groups(groups: ['product:list', 'product:list:write', 'product:item', 'product:item:write'])]
    private ?Category $category = null;

    #[ORM\OneToMany(mappedBy: 'product', targetEntity: CartProduct::class, orphanRemoval: true)]
    private Collection $cartProducts;

    #[ORM\OneToMany(mappedBy: 'product', targetEntity: OrderProduct::class)]
    private Collection $orderProducts;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->isDeleted = false;
        $this->isPublished = false;
        $this->productImages = new ArrayCollection();
        $this->uuid = Uuid::v4();
        $this->cartProducts = new ArrayCollection();
        $this->orderProducts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(string $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getIsPublished(): ?bool
    {
        return $this->isPublished;
    }

    public function setIsPublished(bool $isPublished): self
    {
        $this->isPublished = $isPublished;

        return $this;
    }

    public function getIsDeleted(): ?bool
    {
        return $this->isDeleted;
    }

    public function setIsDeleted(bool $isDeleted): self
    {
        $this->isDeleted = $isDeleted;

        return $this;
    }

    public function getSize(): ?string
    {
        return $this->size;
    }

    public function setSize(?string $size): self
    {
        $this->size = $size;

        return $this;
    }

    /**
     * @return Collection<int, ProductImage>
     */
    public function getProductImages(): Collection
    {
        return $this->productImages;
    }

    public function addProductImage(ProductImage $productImage): self
    {
        if (!$this->productImages->contains($productImage)) {
            $this->productImages->add($productImage);
            $productImage->setProduct($this);
        }

        return $this;
    }

    public function removeProductImage(ProductImage $productImage): self
    {
        if ($this->productImages->removeElement($productImage)) {
            // set the owning side to null (unless already changed)
            if ($productImage->getProduct() === $this) {
                $productImage->setProduct(null);
            }
        }

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(?string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getUuid(): ?Uuid
    {
        return $this->uuid;
    }

    public function setUuid(Uuid $uuid): self
    {
        $this->uuid = $uuid;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return Collection<int, CartProduct>
     */
    public function getCartProducts(): Collection
    {
        return $this->cartProducts;
    }

    public function addCartProduct(CartProduct $cartProduct): self
    {
        if (!$this->cartProducts->contains($cartProduct)) {
            $this->cartProducts->add($cartProduct);
            $cartProduct->setProduct($this);
        }

        return $this;
    }

    public function removeCartProduct(CartProduct $cartProduct): self
    {
        if ($this->cartProducts->removeElement($cartProduct)) {
            // set the owning side to null (unless already changed)
            if ($cartProduct->getProduct() === $this) {
                $cartProduct->setProduct(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, OrderProduct>
     */
    public function getOrderProducts(): Collection
    {
        return $this->orderProducts;
    }

    public function addOrderProduct(OrderProduct $orderProduct): self
    {
        if (!$this->orderProducts->contains($orderProduct)) {
            $this->orderProducts->add($orderProduct);
            $orderProduct->setProduct($this);
        }

        return $this;
    }

    public function removeOrderProduct(OrderProduct $orderProduct): self
    {
        if ($this->orderProducts->removeElement($orderProduct)) {
            // set the owning side to null (unless already changed)
            if ($orderProduct->getProduct() === $this) {
                $orderProduct->setProduct(null);
            }
        }

        return $this;
    }
}
