<?php

namespace App\Entity;

use App\Repository\QuantiteRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: QuantiteRepository::class)]
class Quantite
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column()]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'quantites')]
    private ?Produit $produit = null;

    #[ORM\Column(type: Types::BIGINT)]
    private ?string $qte_stock = null;

    #[ORM\Column]
    private array $variations = [];

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProduit(): ?Produit
    {
        return $this->produit;
    }

    public function setProduit(?Produit $produit): self
    {
        $this->produit = $produit;

        return $this;
    }

    public function getQteStock(): ?string
    {
        return $this->qte_stock;
    }

    public function setQteStock(string $qte_stock): self
    {
        $this->qte_stock = $qte_stock;

        return $this;
    }

    public function getVariations(): array
    {
        return $this->variations;
    }

    public function setVariations(array $variations): self
    {
        $this->variations = $variations;

        return $this;
    }
}
