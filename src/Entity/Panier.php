<?php

namespace App\Entity;

use App\Repository\PanierRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PanierRepository::class)]
class Panier
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $firstname = null;

    #[ORM\Column(length: 255)]
    private ?string $lastname = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $transportName = null;

    #[ORM\Column]
    private ?float $transportPrice = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $adresseLivraison = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $moreInformations = null;

    #[ORM\Column]
    private ?bool $isPaid = null;

    #[ORM\Column]
    private ?int $quantityPanier = null;

    #[ORM\Column]
    private ?float $subTotalHT = null;

    #[ORM\Column]
    private ?float $taxePanier = null;

    #[ORM\Column]
    private ?float $subTotalTTC = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(length: 255)]
    private ?string $reference = null;

    #[ORM\OneToMany(mappedBy: 'panier', targetEntity: PanierDetails::class)]
    private Collection $panierDetails;

    #[ORM\ManyToOne(inversedBy: 'paniers')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    public function __construct()
    {
        $this->panierDetails = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): static
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): static
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getTransportName(): ?string
    {
        return $this->transportName;
    }

    public function setTransportName(string $transportName): static
    {
        $this->transportName = $transportName;

        return $this;
    }

    public function getTransportPrice(): ?float
    {
        return $this->transportPrice;
    }

    public function setTransportPrice(float $transportPrice): static
    {
        $this->transportPrice = $transportPrice;

        return $this;
    }

    public function getAdresseLivraison(): ?string
    {
        return $this->adresseLivraison;
    }

    public function setAdresseLivraison(string $adresseLivraison): static
    {
        $this->adresseLivraison = $adresseLivraison;

        return $this;
    }

    public function getMoreInformations(): ?string
    {
        return $this->moreInformations;
    }

    public function setMoreInformations(string $moreInformations): static
    {
        $this->moreInformations = $moreInformations;

        return $this;
    }

    public function isIsPaid(): ?bool
    {
        return $this->isPaid;
    }

    public function setIsPaid(bool $isPaid): static
    {
        $this->isPaid = $isPaid;

        return $this;
    }

    public function getQuantityPanier(): ?int
    {
        return $this->quantityPanier;
    }

    public function setQuantityPanier(int $quantityPanier): static
    {
        $this->quantityPanier = $quantityPanier;

        return $this;
    }

    public function getSubTotalHT(): ?float
    {
        return $this->subTotalHT;
    }

    public function setSubTotalHT(float $subTotalHT): static
    {
        $this->subTotalHT = $subTotalHT;

        return $this;
    }

    public function getTaxePanier(): ?float
    {
        return $this->taxePanier;
    }

    public function setTaxePanier(float $taxePanier): static
    {
        $this->taxePanier = $taxePanier;

        return $this;
    }

    public function getSubTotalTTC(): ?float
    {
        return $this->subTotalTTC;
    }

    public function setSubTotalTTC(float $subTotalTTC): static
    {
        $this->subTotalTTC = $subTotalTTC;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(string $reference): static
    {
        $this->reference = $reference;

        return $this;
    }

    /**
     * @return Collection<int, PanierDetails>
     */
    public function getPanierDetails(): Collection
    {
        return $this->panierDetails;
    }

    public function addPanierDetail(PanierDetails $panierDetail): static
    {
        if (!$this->panierDetails->contains($panierDetail)) {
            $this->panierDetails->add($panierDetail);
            $panierDetail->setPanier($this);
        }

        return $this;
    }

    public function removePanierDetail(PanierDetails $panierDetail): static
    {
        if ($this->panierDetails->removeElement($panierDetail)) {
            // set the owning side to null (unless already changed)
            if ($panierDetail->getPanier() === $this) {
                $panierDetail->setPanier(null);
            }
        }

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }
}
