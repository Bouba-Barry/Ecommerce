<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\QuantiteRepository;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: QuantiteRepository::class)]
class Quantite
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column()]
    #[Groups(['quantite:read'])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'quantites')]
    #[Groups(['quantite:read'])]
    private ?Produit $produit = null;

    #[ORM\Column(type: Types::BIGINT,nullable:true)]
    #[Groups(['quantite:read'])]
    private ?string $qte_stock = null;

    #[ORM\Column]
    #[Groups(['quantite:read'])]
    private array $variations = [];

    #[ORM\Column(type: Types::BIGINT)]
    #[Groups(['quantite:read'])]
    private ?string $prix = null;

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

    public function getPrix(): ?string
    {
        return $this->prix;
    }

    public function setPrix(string $prix): self
    {
        $this->prix = $prix;

        return $this;
    }
}
