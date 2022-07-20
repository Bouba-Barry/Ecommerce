<?php

namespace App\Entity;

use App\Repository\CommandeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommandeRepository::class)]
class Commande
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'datetime')]
    private $date_cmd;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $adresse_livraison;

    #[ORM\Column(type: 'string', length: 255)]
    private $method_payement;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'commandes')]
    #[ORM\JoinColumn(nullable: false)]
    private $user;

    #[ORM\ManyToMany(targetEntity: Produit::class, inversedBy: 'commandes')]
    private $produit;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private $create_at;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private $update_at;

    public function __construct()
    {
        $this->create_at = new \DateTimeImmutable('now');
        $this->update_at = new \DateTimeImmutable('now');

        $this->produit = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateCmd(): ?\DateTimeInterface
    {
        return $this->date_cmd;
    }

    public function setDateCmd(\DateTimeInterface $date_cmd): self
    {
        $this->date_cmd = $date_cmd;

        return $this;
    }

    public function getAdresseLivraison(): ?string
    {
        return $this->adresse_livraison;
    }

    public function setAdresseLivraison(?string $adresse_livraison): self
    {
        $this->adresse_livraison = $adresse_livraison;

        return $this;
    }

    public function getMethodPayement(): ?string
    {
        return $this->method_payement;
    }

    public function setMethodPayement(string $method_payement): self
    {
        $this->method_payement = $method_payement;

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

    /**
     * @return Collection<int, Produit>
     */
    public function getProduit(): Collection
    {
        return $this->produit;
    }

    public function addProduit(Produit $produit): self
    {
        if (!$this->produit->contains($produit)) {
            $this->produit[] = $produit;
        }

        return $this;
    }

    // public function getCommandeProduit()
    // {
    //     return $this->getProduit()   

    // }

    public function removeProduit(Produit $produit): self
    {
        $this->produit->removeElement($produit);

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