<?php

namespace App\Entity;

use App\Repository\SlideRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SlideRepository::class)]
class Slide
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column()]
    #[Groups(['slide'])]
    private ?int $id = null;

    #[ORM\Column(length: 1000, nullable: true)]
    #[Groups(['slide'])]
    private ?string $video = null;

    #[ORM\Column(length: 255)]
    #[Groups(['slide'])]
    private ?string $etat = null;

    #[ORM\OneToMany(mappedBy: 'slide', targetEntity: Lien::class)]
    #[Groups(['slide'])]
    private Collection $liens;

    #[ORM\Column(length: 255)]
    private ?string $choisi = null;

    public function __construct()
    {
        $this->liens = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getVideo(): ?string
    {
        return $this->video;
    }

    public function setVideo(?string $video): self
    {
        $this->video = $video;

        return $this;
    }

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(string $etat): self
    {
        $this->etat = $etat;

        return $this;
    }

    /**
     * @return Collection<int, Lien>
     */
    public function getLiens(): Collection
    {
        return $this->liens;
    }

    public function addLien(Lien $lien): self
    {
        if (!$this->liens->contains($lien)) {
            $this->liens[] = $lien;
            $lien->setSlide($this);
        }

        return $this;
    }

    public function removeLien(Lien $lien): self
    {
        if ($this->liens->removeElement($lien)) {
            // set the owning side to null (unless already changed)
            if ($lien->getSlide() === $this) {
                $lien->setSlide(null);
            }
        }

        return $this;
    }

    public function getChoisi(): ?string
    {
        return $this->choisi;
    }

    public function setChoisi(string $choisi): self
    {
        $this->choisi = $choisi;

        return $this;
    }
}
