<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ImageRepository;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ImageRepository::class)]
class Image
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['prod:read'])]
    private $id;

    #[ORM\Column(type: 'string', length: 255 , nullable: true)]
    #[Groups(['prod:read'])]
    private $url;

    #[ORM\ManyToOne(targetEntity: Produit::class, inversedBy: 'images')]
    private $produit;

    #[ORM\ManyToOne(targetEntity: Variation::class, inversedBy: 'images')]
    private $variation;

    #[ORM\Column(type: Types::BIGINT)]
    private ?string $qte_stock = null;

   

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
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

    public function getVariation(): ?Variation
    {
        return $this->variation;
    }

    public function setVariation(?Variation $variation): self
    {
        $this->variation = $variation;

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

 
}