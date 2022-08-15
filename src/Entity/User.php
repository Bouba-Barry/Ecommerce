<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[Assert\Regex(
        '/([a-zA-Z0-9]+)([\.{1}])?([a-zA-Z0-9]+)\@gmail([\.])com/',
        message: 'Ceci n\'est pas un compte gmail !'
    )]
    #[ORM\Column(type: 'string', length: 180, unique: true)]
    private $email;

    #[ORM\Column(type: 'json')]
    private $roles = [];

    #[Assert\Length(
        min: 3,
        max: 255,
        minMessage: 'Votre Nom doit avoir {{ limit }} caractères de long',
        maxMessage: 'Votre Nom doit ne dois pas depasser {{ limit }} caractères',
    )]
    #[ORM\Column(type: 'string', length: 180)]
    private $nom;

    #[Assert\Length(
        min: 3,
        max: 255,
        minMessage: 'Votre prenom doit avoir {{ limit }} caractères de long',
        maxMessage: 'Votre prenom doit ne dois pas depasser {{ limit }} caractères',
    )]
    #[ORM\Column(type: 'string', length: 180)]
    private $prenom;


    #[Assert\NotBlank(message: 'le champ est requis')]
    #[ORM\Column(type: 'string', length: 180)]
    private $adresse;

    // #[Assert\Length(
    //     min: 10,
    //     minMessage: 'votre Numero doit contenir au moins {{ limit }} ',
    // )]
    #[Assert\Regex('/^[0-9+]{10}$/', message: 'Veillez Entrez Un Numéro Valide')]
    #[ORM\Column(type: 'string', length: 255)]
    private $telephone;

    #[Assert\Length(
        min: 8,
        minMessage: 'Au Moins {{ limit }} caractères',
    )]
    // #[Assert\Regex('/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/', message: 'password: 8 caractères Au moins 1')]
    // #[Assert\Regex('/^[a-zA-Z0-9]\S{8}$/', message: 'Au Moins 8 caractères avec des lettres et chiffres')]
    #[ORM\Column(type: 'string')]
    private $password;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Produit::class)]
    private $produits;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Commande::class)]
    private $commandes;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Panier::class)]
    private $paniers;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $profile;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private $createAt;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private $update_at;

    #[ORM\ManyToOne(inversedBy: 'users')]
    private ?Ville $ville = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: FeadBack::class)]
    private Collection $feadBacks;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Wishlist::class)]
    #[Groups(['prod:read'])]
    private Collection $wishlists;

    public function __construct()
    {
        $this->createAt = new \DateTimeImmutable('now');
        $this->update_at = new \DateTimeImmutable('now');
        $this->produits = new ArrayCollection();
        $this->commandes = new ArrayCollection();
        $this->paniers = new ArrayCollection();
        $this->feadBacks = new ArrayCollection();
        $this->wishlists = new ArrayCollection();
    }



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }


    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
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









    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = '';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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
            $produit->setUser($this);
        }

        return $this;
    }

    public function removeProduit(Produit $produit): self
    {
        if ($this->produits->removeElement($produit)) {
            // set the owning side to null (unless already changed)
            if ($produit->getUser() === $this) {
                $produit->setUser(null);
            }
        }

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
            $commande->setUser($this);
        }

        return $this;
    }

    public function removeCommande(Commande $commande): self
    {
        if ($this->commandes->removeElement($commande)) {
            // set the owning side to null (unless already changed)
            if ($commande->getUser() === $this) {
                $commande->setUser(null);
            }
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
            $panier->setUser($this);
        }

        return $this;
    }

    public function removePanier(Panier $panier): self
    {
        if ($this->paniers->removeElement($panier)) {
            // set the owning side to null (unless already changed)
            if ($panier->getUser() === $this) {
                $panier->setUser(null);
            }
        }

        return $this;
    }

    public function getProfile(): ?string
    {
        return $this->profile;
    }

    public function setProfile(?string $profile): self
    {
        $this->profile = $profile;

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
        return $this->update_at;
    }

    public function setUpdateAt(?\DateTimeImmutable $update_at): self
    {
        $this->update_at = $update_at;

        return $this;
    }

    public function getVille(): ?Ville
    {
        return $this->ville;
    }

    public function setVille(?Ville $ville): self
    {
        $this->ville = $ville;

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
            $feadBack->setUser($this);
        }

        return $this;
    }

    public function removeFeadBack(FeadBack $feadBack): self
    {
        if ($this->feadBacks->removeElement($feadBack)) {
            // set the owning side to null (unless already changed)
            if ($feadBack->getUser() === $this) {
                $feadBack->setUser(null);
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
            $wishlist->setUser($this);
        }

        return $this;
    }

    public function removeWishlist(Wishlist $wishlist): self
    {
        if ($this->wishlists->removeElement($wishlist)) {
            // set the owning side to null (unless already changed)
            if ($wishlist->getUser() === $this) {
                $wishlist->setUser(null);
            }
        }

        return $this;
    }
}