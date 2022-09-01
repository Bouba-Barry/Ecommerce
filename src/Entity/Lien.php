<?php

namespace App\Entity;

use App\Repository\LienRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: LienRepository::class)]
class Lien
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column()]
    #[Groups(['lien'])]
    private ?int $id = null;

    #[ORM\Column(length: 1000)]
    #[Groups(['lien'])]
    private ?string $image = null;

    #[ORM\Column(length: 1000, nullable: true)]
    #[Groups(['lien'])]
    private ?string $url = null;

    #[ORM\ManyToOne(inversedBy: 'liens')]
    private ?Slide $slide = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getSlide(): ?Slide
    {
        return $this->slide;
    }

    public function setSlide(?Slide $slide): self
    {
        $this->slide = $slide;

        return $this;
    }
}
