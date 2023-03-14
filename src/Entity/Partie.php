<?php

namespace App\Entity;

use App\Repository\PartieRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PartieRepository::class)]
class Partie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $tour_actuel_partie = null;


    #[ORM\Column(length: 255)]
    private ?string $etat_partie = null;

    #[ORM\Column]
    private ?int $tourjoueur = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $nomDePartie = null;

    #[ORM\ManyToOne(inversedBy: 'parties')]
    private ?user $joueur1 = null;

    #[ORM\ManyToOne(inversedBy: 'parties')]
    private ?user $joueur2 = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTourActuelPartie(): ?int
    {
        return $this->tour_actuel_partie;
    }

    public function setTourActuelPartie(int $tour_actuel_partie): self
    {
        $this->tour_actuel_partie = $tour_actuel_partie;

        return $this;
    }


    public function getEtatPartie(): ?string
    {
        return $this->etat_partie;
    }

    public function setEtatPartie(string $etat_partie): self
    {
        $this->etat_partie = $etat_partie;

        return $this;
    }

    public function getTourjoueur(): ?int
    {
        return $this->tourjoueur;
    }

    public function setTourjoueur(int $tourjoueur): self
    {
        $this->tourjoueur = $tourjoueur;

        return $this;
    }

    public function getNomDePartie(): ?string
    {
        return $this->nomDePartie;
    }

    public function setNomDePartie(?string $nomDePartie): self
    {
        $this->nomDePartie = $nomDePartie;

        return $this;
    }

    public function getJoueur1(): ?user
    {
        return $this->joueur1;
    }

    public function setJoueur1(?user $joueur1): self
    {
        $this->joueur1 = $joueur1;

        return $this;
    }
    public function getJoueur2(): ?user
    {
        return $this->joueur2;
    }

    public function setJoueur2(?user $joueur2): self
    {
        $this->joueur2 = $joueur2;

        return $this;
    }
}
