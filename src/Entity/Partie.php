<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\PartieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;

#[ORM\Entity(repositoryClass: PartieRepository::class)]
#[ApiResource(
    normalizationContext: [
        'groups' => ['treasure:read'],
    ],
    denormalizationContext: [
        'groups' => ['treasure:write'],
    ]
)]

class Partie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['treasure:read', 'treasure:write'])]
    private ?int $id = null;

    #[ORM\ManyToOne()]
    #[MaxDepth(1)]
    #[Groups(['treasure:read', 'treasure:write'])]
    private ?User $joueur1 = null;

    #[ORM\ManyToOne]
    #[MaxDepth(1)]
    #[Groups(['treasure:read', 'treasure:write'])]
    private ?User $joueur2 = null;

    #[ORM\Column(length: 255)]
    #[Groups(['treasure:read', 'treasure:write'])]
    private ?string $etatPartie = null;

    #[ORM\ManyToOne]
    #[MaxDepth(1)]
    #[Groups(['treasure:read', 'treasure:write'])]
    private ?User $tourJoueur = null;

    #[ORM\Column(length: 255)]
    #[Groups(['treasure:read', 'treasure:write'])]
    private ?string $victoire = null;

    #[ORM\OneToMany(mappedBy: 'partie', targetEntity: MotPartie::class)]
    #[Groups(['treasure:read', 'treasure:write'])]
    private Collection $motParties;

    #[ORM\Column(length: 255)]
    #[Groups(['treasure:read', 'treasure:write'])]
    private ?string $tourduj = '1';

    #[ORM\Column]
    #[Groups(['treasure:read', 'treasure:write'])]
    private ?int $tourpartie = 1;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['treasure:read', 'treasure:write'])]
    private ?string $messages = null;

    #[ORM\OneToMany(mappedBy: 'partie', targetEntity: Indice::class)]
    #[Groups(['treasure:read', 'treasure:write'])]
    private Collection $indices;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;
    
    public function __construct()
    {
        $this->motParties = new ArrayCollection();
        $this->indices = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getJoueur1(): ?User
    {
        return $this->joueur1;
    }

    public function setJoueur1(?User $joueur1): self
    {
        $this->joueur1 = $joueur1;

        return $this;
    }

    public function getJoueur2(): ?User
    {
        return $this->joueur2;
    }

    public function setJoueur2(?User $joueur2): self
    {
        $this->joueur2 = $joueur2;

        return $this;
    }

    public function getEtatPartie(): ?string
    {
        return $this->etatPartie;
    }

    public function setEtatPartie(string $etatPartie): self
    {
        $this->etatPartie = $etatPartie;

        return $this;
    }

    public function getTourJoueur(): ?User
    {
        return $this->tourJoueur;
    }

    public function setTourJoueur(?User $tourJoueur): self
    {
        $this->tourJoueur = $tourJoueur;

        return $this;
    }

    public function getVictoire(): ?string
    {
        return $this->victoire;
    }

    public function setVictoire(string $victoire): self
    {
        $this->victoire = $victoire;

        return $this;
    }

    /**
     * @return Collection<int, MotPartie>
     */
    public function getMotParties(): Collection
    {
        return $this->motParties;
    }

    public function addMotParty(MotPartie $motParty): self
    {
        if (!$this->motParties->contains($motParty)) {
            $this->motParties->add($motParty);
            $motParty->setPartie($this);
        }

        return $this;
    }

    public function removeMotParty(MotPartie $motParty): self
    {
        if ($this->motParties->removeElement($motParty)) {
            // set the owning side to null (unless already changed)
            if ($motParty->getPartie() === $this) {
                $motParty->setPartie(null);
            }
        }

        return $this;
    }

    public function getTourduj(): ?string
    {
        return $this->tourduj;
    }

    public function setTourduj(string $tourduj): self
    {
        $this->tourduj = $tourduj;

        return $this;
    }

    public function getTourpartie(): ?int
    {
        return $this->tourpartie;
    }

    public function setTourpartie(int $tourpartie): self
    {
        $this->tourpartie = $tourpartie;

        return $this;
    }

    public function getMessages(): ?string
    {
        return $this->messages;
    }

    public function setMessages(?string $messages): self
    {
        $this->messages = $messages;

        return $this;
    }

    /**
     * @return Collection<int, Indice>
     */
    public function getIndices(): Collection
    {
        return $this->indices;
    }

    public function addIndex(Indice $index): self
    {
        if (!$this->indices->contains($index)) {
            $this->indices->add($index);
            $index->setPartieId($this);
        }

        return $this;
    }

    public function removeIndex(Indice $index): self
    {
        if ($this->indices->removeElement($index)) {
            // set the owning side to null (unless already changed)
            if ($index->getPartieId() === $this) {
                $index->setPartieId(null);
            }
        }

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
}