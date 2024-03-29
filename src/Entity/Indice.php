<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\IndiceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: IndiceRepository::class)]
#[ApiResource]
class Indice
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['treasure:read', 'treasure:write'])]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['treasure:read', 'treasure:write'])]
    private ?string $mot = null;

    

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMot(): ?string
    {
        return $this->mot;
    }

    public function setMot(?string $mot): self
    {
        $this->mot = $mot;

        return $this;
    }

    


    #[ORM\ManyToOne(inversedBy: 'partie', cascade:['persist'])]
    private ?Partie $partie = null;

    

    public function getPartie(): ?Partie
    {
        return $this->partie;
    }
    public function setPartie(?Partie $partie): self
    {
        $this->partie = $partie;

        return $this;
    }



}