<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ReductionRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Filter\SoftDeleteable;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ReductionRepository::class)]
#[Gedmo\SoftDeleteable(fieldName: "deletedAt", timeAware: false)]
class Reduction
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['prod:read', 'prod:check','reduction','produit:read'])]
    private $id;

    #[ORM\Column(name: "deletedAt", type: "datetime", nullable: true)]
    private $deletedAt;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Groups(['prod:read','reduction','produit:read'])]
    private $designation;

    #[Assert\Regex('/^[0-9]{2}%$/', message: '{{ value }} ne correspond pas à l\'expression adaptée Ex: 50%')]    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Groups(['prod:read','reduction','produit:read'])]
    private $pourcentage;



    #[ORM\ManyToMany(targetEntity: Produit::class, mappedBy: 'reduction')]
    // #[Groups(['prod:read'])] on met pas ici le groups!
    private $produits;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    #[Groups(['prod:read'])]
    private $create_at;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    #[Groups(['prod:read'])]
    private $update_at;
    // #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    // private ?\DateTimeInterface $date_debut = null;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    #[Groups(['reduction','produit:read'])]
    private $date_fin;

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

    public function getDesignation(): ?string
    {
        return $this->designation;
    }

    public function setDesignation(?string $designation): self
    {
        $this->designation = $designation;

        return $this;
    }

    public function getPourcentage(): ?string
    {
        return $this->pourcentage;
    }

    public function setPourcentage(?string $pourcentage): self
    {
        $this->pourcentage = $pourcentage;

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
        // if (!$this->produits->contains($produit)) {
        //     $this->produits[] = $produit;
        //     $produit->addReduction($this);
        // }

        if ($this->produits->contains($produit)) {
            $this->produits[] = $produit;
            $produit->addReduction($this);
        }

        return $this;
    }

    public function removeProduit(Produit $produit): self
    {
        if ($this->produits->removeElement($produit)) {
            $produit->removeReduction($this);
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


    public function getDateFin(): ?\DateTimeImmutable
    {
        return $this->date_fin;
    }

    public function setDateFin(?\DateTimeImmutable $date_fin): self
    {
        $this->date_fin = $date_fin;

        return $this;
    }
}