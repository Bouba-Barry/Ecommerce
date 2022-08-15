<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use App\Repository\CategorieRepository;
use Doctrine\Common\Collections\Collection;
use Gedmo\SoftDeleteable\Filter\SoftDeleteable;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: CategorieRepository::class)]
#[Gedmo\SoftDeleteable(fieldName:"deletedAt", timeAware:false)]
class Categorie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['categorie:read'])]
    private $id;

    #[ORM\Column(name:"deletedAt", type:"datetime", nullable:true)]
    private $deletedAt;


    #[Assert\NotBlank(message: 'le champ est requis')]
    #[Assert\Length(min: 3, minMessage: 'Au Moins 3 caractères')]
    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['categorie:read'])]
    private $titre;

    #[ORM\OneToMany(mappedBy: 'categorie', targetEntity: SousCategorie::class)]
    private $sousCategories;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private $create_at;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private $update_at;

    public function __construct()
    {
        $this->create_at = new \DateTimeImmutable('now');
        $this->update_at = new \DateTimeImmutable('now');

        $this->sousCategories = new ArrayCollection();
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

    /**
     * @return Collection<int, SousCategorie>
     */
    public function getSousCategories(): Collection
    {
        return $this->sousCategories;
    }

    public function addSousCategory(SousCategorie $sousCategory): self
    {
        if (!$this->sousCategories->contains($sousCategory)) {
            $this->sousCategories[] = $sousCategory;
            $sousCategory->setCategorie($this);
        }

        return $this;
    }

    public function removeSousCategory(SousCategorie $sousCategory): self
    {
        if ($this->sousCategories->removeElement($sousCategory)) {
            // set the owning side to null (unless already changed)
            if ($sousCategory->getCategorie() === $this) {
                $sousCategory->setCategorie(null);
            }
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
}