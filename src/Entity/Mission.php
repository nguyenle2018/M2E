<?php

namespace App\Entity;

use App\Repository\MissionRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MissionRepository::class)]
class Mission
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nomMission = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $siteInternet = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $lieu = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(length: 255)]
    private ?string $typeMission = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $competences = null;

    #[ORM\ManyToOne(targetEntity: Association::class, inversedBy: "missions")]
    #[ORM\JoinColumn(nullable: false)]
    private Association $association;

    #[ORM\OneToMany(targetEntity: Candidature::class, mappedBy: "missions")]
    private Collection $candidatures;

    public function __construct()
    {
        $this->candidatures = new ArrayCollection();
    }

    public function getCandidatures(): Collection
    {
        return $this->candidatures;
    }

    public function addCandidature(Candidature $candidature): self
    {
            if (!$this->candidatures->contains($candidature)) {
                $this->candidatures[] = $candidature;
                $candidature->setMission($this);
            }
            return $this;
    }

    public function removeCandidature(Candidature $candidature): self
    {
            if ($this->candidatures->removeElement($candidature)) {
                // Set the owning side to null (unless already changed)
                if ($candidature->getMission() === $this) {
                    $candidature->setMission(null);
                }
            }
            return $this;
    }

    public function getAssociation(): Association
    {
        return $this->association;
    }

     public function setAssociation(Association $association): self
    {
        $this->association = $association;
        return $this;
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomMission(): ?string
    {
        return $this->nomMission;
    }

    public function setNomMission(string $nomMission): static
    {
        $this->nomMission = $nomMission;
         return $this;
    }
     
    public function getSiteInternet(): ?string
    {
        return $this->siteInternet;
    }

    public function setSiteInternet(?string $siteInternet): static
    {
        $this->siteInternet = $siteInternet;
         return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;
        return $this;
    }

    public function getLieu(): ?string
    {
        return $this->lieu;
    }

    public function setLieu(?string $lieu): static
    {
        $this->lieu = $lieu;
         return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;
         return $this;
    }

    public function getTypeMission(): ?string
    {
        return $this->typeMission;
    }

    public function setTypeMission(string $typeMission): static
    {
        $this->typeMission = $typeMission;
        return $this;
    }

    public function getCompetences(): ?string
    {
        return $this->competences;
    }

    public function setCompetences(?string $competences): static
    {
        $this->competences = $competences;
          return $this;
    }
    public function __toString(): string
    {
        return $this->nomMission;
    }
}
