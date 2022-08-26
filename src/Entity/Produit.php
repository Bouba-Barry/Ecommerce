<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ProduitRepository;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\Collection;
use Gedmo\SoftDeleteable\Filter\SoftDeleteable;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ProduitRepository::class)]
#[Gedmo\SoftDeleteable(fieldName: "deletedAt", timeAware: false)]
class Produit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['prod:read','produit:read','prod:check','quantite:read','image:read'])]
    private $id;

    #[ORM\Column(name: "deletedAt", type: "datetime", nullable: true)]
    private $deletedAt;


    #[Assert\Length(
        min: 2,
        minMessage: 'la Designation doit avoir {{ limit }} caractères minimum',
    )]
    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['prod:read','produit:read','quantite:read','image:read'])]
    private $designation;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['prod:read'])]
    private $description;

    #[Assert\GreaterThan(5)]
    #[ORM\Column(type: 'float')]
    #[Groups(['prod:read'])]
    private $ancien_prix;


    #[ORM\Column(type: 'float', nullable: true)]
    #[Groups(['prod:read'])]
    private $nouveau_prix;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Groups(['prod:read'])]
    private $image_produit;


    #[Assert\GreaterThanOrEqual(
        value: 1,
        message: 'la quantite doit être plus grand que 0'
    )]
    #[ORM\Column(type: 'bigint')]
    #[Groups(['prod:read'])]
    private $qte_stock;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'produits')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['prod:read'])]
    private $user;

    #[ORM\ManyToOne(targetEntity: SousCategorie::class, inversedBy: 'produits')]
    #[Groups(['prod:read'])]
    private $sous_categorie;

    #[ORM\ManyToMany(targetEntity: Reduction::class, inversedBy: 'produits')]
    #[Groups(['prod:read', 'prod:check','produit:read'])]
    private $reduction;

    #[ORM\ManyToMany(targetEntity: Variation::class, inversedBy: 'produits')]
    #[Groups(['prod:read'])]
    private $variation;

    #[ORM\ManyToMany(targetEntity: Commande::class, mappedBy: 'produit')]
    #[Groups(['prod:read'])]
    private $commandes;

    #[ORM\ManyToMany(targetEntity: Panier::class, mappedBy: 'produit')]
    #[Groups(['prod:read'])]
    private $paniers;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    #[Groups(['prod:read'])]
    private $createAt;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    #[Groups(['prod:read'])]
    private $updateAt;

    #[ORM\OneToMany(mappedBy: 'produit', targetEntity: Image::class)]
    #[Groups(['prod:read'])]
    private $images;

    #[ORM\ManyToMany(targetEntity: Attribut::class, inversedBy: 'produits')]
    #[Groups(['prod:read'])]
    private $attributs;

    #[ORM\OneToMany(mappedBy: 'produit', targetEntity: FeadBack::class)]
    private Collection $feadBacks;

    #[ORM\ManyToMany(targetEntity: Wishlist::class, mappedBy: 'produit')]
    private Collection $wishlists;

    #[ORM\OneToMany(mappedBy: 'produit', targetEntity: Quantite::class)]
    private Collection $quantites;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['prod:read'])]
    private ?string $description_detaille = null;

    #[ORM\Column(length: 255)]
    private ?string $type = null;

    public function __construct()
    {
        $this->createAt = new \DateTimeImmutable('now');
        $this->updateAt = new \DateTimeImmutable('now');

        $this->reduction = new ArrayCollection();
        $this->variation = new ArrayCollection();
        $this->commandes = new ArrayCollection();
        $this->paniers = new ArrayCollection();
        $this->images = new ArrayCollection();
        $this->attributs = new ArrayCollection();
        $this->feadBacks = new ArrayCollection();
        $this->wishlists = new ArrayCollection();
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

    /**
     * @return Collection<int, Panier>
     */
    public function getPaniers(): Collection
    {
        return $this->paniers;
    }

    public function addPanier(Panier $panier): self
    {
        if (!$this->paniers->contains($panier)) {
            $this->paniers[] = $panier;
            $panier->addProduit($this);
        }

        return $this;
    }

    public function removePanier(Panier $panier): self
    {
        if ($this->paniers->removeElement($panier)) {
            $panier->removeProduit($this);
        }

        return $this;
    }

    public function getCreateAt(): ?\DateTimeImmutable
    {
        return $this->createAt;
    }

    public function setCreateAt(?\DateTimeImmutable $createAt): self
    {
        $this->createAt = $createAt;

        return $this;
    }
    public function getUpdateAt(): ?\DateTimeImmutable
    {
        return $this->updateAt;
    }

    public function setUpdateAt(?\DateTimeImmutable $updateAt): self
    {
        $this->updateAt = $updateAt;

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
            $image->setProduit($this);
        }

        return $this;
    }

    public function removeImage(Image $image): self
    {
        if ($this->images->removeElement($image)) {
            // set the owning side to null (unless already changed)
            if ($image->getProduit() === $this) {
                $image->setProduit(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Attribut>
     */
    public function getAttributs(): Collection
    {
        return $this->attributs;
    }

    public function addAttribut(Attribut $attribut): self
    {
        if (!$this->attributs->contains($attribut)) {
            $this->attributs[] = $attribut;
        }

        return $this;
    }

    public function removeAttribut(Attribut $attribut): self
    {
        $this->attributs->removeElement($attribut);

        return $this;
    }

    /**
     * @return Collection<int, FeadBack>
     */
    public function getFeadBacks(): Collection
    {
        return $this->feadBacks;
    }

    public function addFeadBack(FeadBack $feadBack): self
    {
        if (!$this->feadBacks->contains($feadBack)) {
            $this->feadBacks[] = $feadBack;
            $feadBack->setProduit($this);
        }

        return $this;
    }

    public function removeFeadBack(FeadBack $feadBack): self
    {
        if ($this->feadBacks->removeElement($feadBack)) {
            // set the owning side to null (unless already changed)
            if ($feadBack->getProduit() === $this) {
                $feadBack->setProduit(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Wishlist>
     */
    public function getWishlists(): Collection
    {
        return $this->wishlists;
    }

    public function addWishlist(Wishlist $wishlist): self
    {
        if (!$this->wishlists->contains($wishlist)) {
            $this->wishlists[] = $wishlist;
            $wishlist->addProduit($this);
        }

        return $this;
    }

    public function removeWishlist(Wishlist $wishlist): self
    {
        if ($this->wishlists->removeElement($wishlist)) {
            $wishlist->removeProduit($this);
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
            $quantite->setProduit($this);
        }

        return $this;
    }

    public function removeQuantite(Quantite $quantite): self
    {
        if ($this->quantites->removeElement($quantite)) {
            // set the owning side to null (unless already changed)
            if ($quantite->getProduit() === $this) {
                $quantite->setProduit(null);
            }
        }

        return $this;
    }

    public function getDescriptionDetaille(): ?string
    {
        return $this->description_detaille;
    }

    public function setDescriptionDetaille(?string $description_detaille): self
    {
        $this->description_detaille = $description_detaille;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }
}