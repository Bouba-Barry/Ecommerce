<?php

namespace App\Entity;

use App\Repository\ProduitRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProduitRepository::class)]
class Produit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $designation;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $description;

    #[ORM\Column(type: 'float')]
    private $ancien_prix;

    #[ORM\Column(type: 'float', nullable: true)]
    private $nouveau_prix;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $image_produit;

    #[ORM\Column(type: 'bigint')]
    private $qte_stock;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'produits')]
    #[ORM\JoinColumn(nullable: false)]
    private $user;

    #[ORM\ManyToOne(targetEntity: SousCategorie::class, inversedBy: 'produits')]
    private $sous_categorie;

    #[ORM\ManyToMany(targetEntity: Reduction::class, inversedBy: 'produits')]
    private $reduction;

    #[ORM\ManyToMany(targetEntity: Variation::class, inversedBy: 'produits')]
    private $variation;

    #[ORM\ManyToMany(targetEntity: Commande::class, mappedBy: 'produit')]
    private $commandes;

    public function __construct()
    {
        $this->reduction = new ArrayCollection();
        $this->variation = new ArrayCollection();
        $this->commandes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDesignation(): ?string
    {
        return $this->designation;
    }

    public function setDesignation(string $designation): self
    {
        $this->designation = $designation;

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

    public function getAncienPrix(): ?float
    {
        return $this->ancien_prix;
    }

    public function setAncienPrix(float $ancien_prix): self
    {
        $this->ancien_prix = $ancien_prix;

        return $this;
    }

    public function getNouveauPrix(): ?float
    {
        return $this->nouveau_prix;
    }

    public function setNouveauPrix(?float $nouveau_prix): self
    {
        $this->nouveau_prix = $nouveau_prix;

        return $this;
    }

    public function getImageProduit(): ?string
    {
        return $this->image_produit;
    }

    public function setImageProduit(?string $image_produit): self
    {
        $this->image_produit = $image_produit;

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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getSousCategorie(): ?SousCategorie
    {
        return $this->sous_categorie;
    }

    public function setSousCategorie(?SousCategorie $sous_categorie): self
    {
        $this->sous_categorie = $sous_categorie;

        return $this;
    }

    /**
     * @return Collection<int, Reduction>
     */
    public function getReduction(): Collection
    {
        return $this->reduction;
    }

    public function addReduction(Reduction $reduction): self
    {
        if (!$this->reduction->contains($reduction)) {
            $this->reduction[] = $reduction;
        }

        return $this;
    }

    public function removeReduction(Reduction $reduction): self
    {
        $this->reduction->removeElement($reduction);

        return $this;
    }

    /**
     * @return Collection<int, Variation>
     */
    public function getVariation(): Collection
    {
        return $this->variation;
    }

    public function addVariation(Variation $variation): self
    {
        if (!$this->variation->contains($variation)) {
            $this->variation[] = $variation;
        }

        return $this;
    }

    public function removeVariation(Variation $variation): self
    {
        $this->variation->removeElement($variation);

        return $this;
    }

    /**
     * @return Collection<int, Commande>
     */
    public function getCommandes(): Collection
    {
        return $this->commandes;
    }

    public function addCommande(Commande $commande): self
    {
        if (!$this->commandes->contains($commande)) {
            $this->commandes[] = $commande;
            $commande->addProduit($this);
        }

        return $this;
    }

    public function removeCommande(Commande $commande): self
    {
        if ($this->commandes->removeElement($commande)) {
            $commande->removeProduit($this);
        }

        return $this;
    }
}
