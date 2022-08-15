<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use App\Repository\SousCategorieRepository;
use Doctrine\Common\Collections\Collection;
use Gedmo\SoftDeleteable\Filter\SoftDeleteable;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: SousCategorieRepository::class)]
#[Gedmo\SoftDeleteable(fieldName:"deletedAt", timeAware:false)]
class SousCategorie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['souscategorie:read'])]
    private $id;

    #[ORM\Column(name:"deletedAt", type:"datetime", nullable:true)]
    private $deletedAt;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['souscategorie:read'])]
    private $titre;

    #[ORM\ManyToOne(targetEntity: Categorie::class, inversedBy: 'sousCategories')]
    private $categorie;

    #[ORM\OneToMany(mappedBy: 'sous_categorie', targetEntity: Produit::class)]
    private $produits;


    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private $create_at;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private $update_at;


    public function __construct()
    {
        $this->create_at = new \DateTimeImmutable('now');
        $this->update_at = new \DateTimeImmutable('now');
        $this->produits = new ArrayCollection();
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

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getCategorie(): ?Categorie
    {
        return $this->categorie;
    }

    public function setCategorie(?Categorie $categorie): self
    {
        $this->categorie = $categorie;

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
        if (!$this->produits->contains($produit)) {
            $this->produits[] = $produit;
            $produit->setSousCategorie($this);
        }

        return $this;
    }

    public function removeProduit(Produit $produit): self
    {
        if ($this->produits->removeElement($produit)) {
            // set the owning side to null (unless already changed)
            if ($produit->getSousCategorie() === $this) {
                $produit->setSousCategorie(null);
            }
        }

        return $this;
    }

    public function getCreateAt(): ?\DateTimeImmutable
    {
        return $this->create_at;
    }

    public function setCreateAt(?\DateTimeImmutable $createAt): self
    {
        $this->create_at = $createAt;

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
}