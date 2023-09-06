<?php

namespace App\Entity;

use App\Repository\OrderDetailsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderDetailsRepository::class)]
class OrderDetails
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $productName = null;

    #[ORM\Column]
    private ?float $ProductPrice = null;

    #[ORM\Column]
    private ?int $productQuantity = null;

    #[ORM\Column]
    private ?float $subtotalProductHT = null;

    #[ORM\Column]
    private ?float $taxeProduct = null;

    #[ORM\Column]
    private ?float $subtotalProductTTC = null;

    #[ORM\ManyToOne(inversedBy: 'orderDetails')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Orders $Orders = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProductName(): ?string
    {
        return $this->productName;
    }

    public function setProductName(string $productName): static
    {
        $this->productName = $productName;

        return $this;
    }

    public function getProductPrice(): ?float
    {
        return $this->ProductPrice;
    }

    public function setProductPrice(float $ProductPrice): static
    {
        $this->ProductPrice = $ProductPrice;

        return $this;
    }

    public function getProductQuantity(): ?int
    {
        return $this->productQuantity;
    }

    public function setProductQuantity(int $productQuantity): static
    {
        $this->productQuantity = $productQuantity;

        return $this;
    }

    public function getSubtotalProductHT(): ?float
    {
        return $this->subtotalProductHT;
    }

    public function setSubtotalProductHT(float $subtotalProductHT): static
    {
        $this->subtotalProductHT = $subtotalProductHT;

        return $this;
    }

    public function getTaxeProduct(): ?float
    {
        return $this->taxeProduct;
    }

    public function setTaxeProduct(float $taxeProduct): static
    {
        $this->taxeProduct = $taxeProduct;

        return $this;
    }

    public function getSubtotalProductTTC(): ?float
    {
        return $this->subtotalProductTTC;
    }

    public function setSubtotalProductTTC(float $subtotalProductTTC): static
    {
        $this->subtotalProductTTC = $subtotalProductTTC;

        return $this;
    }

    public function getOrders(): ?Orders
    {
        return $this->Orders;
    }

    public function setOrders(?Orders $Orders): static
    {
        $this->Orders = $Orders;

        return $this;
    }
}
