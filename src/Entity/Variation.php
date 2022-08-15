<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\VariationRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Filter\SoftDeleteable;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: VariationRepository::class)]
#[Gedmo\SoftDeleteable(fieldName: "deletedAt", timeAware: false)]
class Variation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['prod:read', 'variation', 'attribut'])]
    private $id;

    #[ORM\Column(name: "deletedAt", type: "datetime", nullable: true)]
    private $deletedAt;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank(message: 'le champ est requis')]
    #[Groups(['prod:read', 'variation', 'attribut'])]
    private $nom;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Groups(['prod:read'])]
    private $code;

    #[ORM\ManyToOne(targetEntity: Attribut::class, inversedBy: 'variations')]
    #[Groups(['variation'])]
    private $attribut;

    #[ORM\ManyToMany(targetEntity: Produit::class, mappedBy: 'variation')]
    private $produits;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private $create_at;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private $update_at;

    #[ORM\OneToMany(mappedBy: 'variation', targetEntity: Image::class)]
    #[Groups(['prod:read'])]
    private $images;

    #[ORM\ManyToMany(targetEntity: Quantite::class, mappedBy: 'variations')]
    private Collection $quantites;

    public function __construct()
    {
        $this->produits = new ArrayCollection();
        $this->images = new ArrayCollection();
        $this->quantites = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }


    public function getDeletedAt()
    {
        return $this->deletedAt;
    }

    public function setDeletedAt($deletedAt)
    {
        $this->deletedAt = $deletedAt;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(?string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getAttribut(): ?Attribut
    {
        return $this->attribut;
    }

    public function setAttribut(?Attribut $attribut): self
    {
        $this->attribut = $attribut;

        return $this;
    }

    /**
     * @return Collection<int, Produit>
     */
    public function getProduits(): Collection
    {
        return $this->produits;
    }

    public function addProduit(Produit $produit): self
    {
        // origine du code à verifier après
        // if (!$this->produits->contains($produit)) {
        //     $this->produits[] = $produit;
        //     $produit->addVariation($this);
        // }

        // && !$produit->getVariation()->contains($this->id)
        if ($this->produits->contains($produit)) {
            $this->produits[] = $produit;
            $produit->addVariation($this);
        }

        return $this;
    }

    public function removeProduit(Produit $produit): self
    {
        if ($this->produits->removeElement($produit)) {
            $produit->removeVariation($this);
        }

        return $this;
    }

    public function getCreateAt(): ?\DateTimeImmutable
    {
        return $this->create_at;
    }

    public function setCreateAt(?\DateTimeImmutable $create_at): self
    {
        $this->create_at = $create_at;

        return $this;
    }

    public function getUpdateAt(): ?\DateTimeImmutable
    {
        return $this->update_at;
    }

    public function setUpdateAt(?\DateTimeImmutable $update_at): self
    {
        $this->update_at = $update_at;

        return $this;
    }

    /**
     * @return Collection<int, Image>
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Image $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images[] = $image;
            $image->setVariation($this);
        }

        return $this;
    }

    public function removeImage(Image $image): self
    {
        if ($this->images->removeElement($image)) {
            // set the owning side to null (unless already changed)
            if ($image->getVariation() === $this) {
                $image->setVariation(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Quantite>
     */
    public function getQuantites(): Collection
    {
        return $this->quantites;
    }

    public function addQuantite(Quantite $quantite): self
    {
        if (!$this->quantites->contains($quantite)) {
            $this->quantites[] = $quantite;
            $quantite->addVariation($this);
        }

        return $this;
    }

    public function removeQuantite(Quantite $quantite): self
    {
        if ($this->quantites->removeElement($quantite)) {
            $quantite->removeVariation($this);
        }

        return $this;
    }
}