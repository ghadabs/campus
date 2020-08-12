<?php

namespace App\Entity;

use App\Repository\FormationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FormationRepository::class)
 */
class Formation
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $title;

    /**
     * @ORM\Column(type="string", length=255)
     * 
     */
    protected $image;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $description;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $tarif;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="formation")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $user;

    /**
     * @ORM\ManyToOne(targetEntity=Niveau::class, inversedBy="formation")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $niveau;

    /**
     * @ORM\Column(type="datetime",options={"default": "CURRENT_TIMESTAMP"})
     */
    protected $created_at;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $statut;

    /**
     * @ORM\ManyToOne(targetEntity=Langue::class, inversedBy="formations")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $langue;

    /**
     * @ORM\ManyToOne(targetEntity=Type::class, inversedBy="formations")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $type;

    /**
     * @ORM\ManyToOne(targetEntity=Categorie::class, inversedBy="formations")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $categorie;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $gratuit;

    /**
     * @ORM\OneToMany(targetEntity="Session", mappedBy="formation", cascade={"persist"}, orphanRemoval=true)
     */
    protected $sessions;



    /**
     * @ORM\OneToMany(targetEntity=Equipe::class, mappedBy="formation",cascade={"persist","remove"},orphanRemoval=true )
     */
    private $equipes;

   



    public function __construct()
    {
        $this->createdAt = new \DateTime();

        $this->sessions = new ArrayCollection();
        $this->equipes = new ArrayCollection();
   
   
       
        
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getTarif(): ?String
    {
        return $this->tarif;
    }

    public function setTarif(string $tarif): self
    {
        $this->tarif = $tarif;

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

    public function getNiveau(): ?Niveau
    {
        return $this->niveau;
    }

    public function setNiveau(?Niveau $niveau): self
    {
        $this->niveau = $niveau;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): self
    {
        $this->statut = $statut;

        return $this;
    }


    public function getLangue(): ?Langue
    {
        return $this->langue;
    }

    public function setLangue(?Langue $langue): self
    {
        $this->langue = $langue;

        return $this;
    }

    public function getType(): ?Type
    {
        return $this->type;
    }

    public function setType(?Type $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getCategorie(): ?Categorie
    {
        return $this->categorie;
    }

    public function setCategorie(?Categorie $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }

  

    public function getGratuit(): ?bool
    {
        return $this->gratuit;
    }

    public function setGratuit(bool $gratuit): self
    {
        $this->gratuit = $gratuit;

        return $this;
    }

    /**
     * @return Collection|Session[]
     */
    public function getSessions(): Collection
    {
        return $this->sessions;
    }
    public function addSession($session)
    {
        if (!$this->sessions->contains($session)) {
            $this->sessions->add($session);   
            $session->setFormation($this);

       }
       return $this;
     
    }



    public function removeSession(Session $session): self
    {
        $this->sessions->removeElement($session);
        $session->setFormation(null);

        return $this;
    }

    /**
     * @return Collection|Equipe[]
     */
    public function getEquipes(): Collection
    {
        return $this->equipes;
    }

    public function addEquipe(Equipe $equipe)
    {
      
            $this->equipes->add($equipe);
            $equipe->setFormation($this);
         

      
    }

    public function removeEquipe(Equipe $equipe): self
    {
        if ($this->equipes->contains($equipe)) {
            $this->equipes->removeElement($equipe);
            $equipe->setFormation(null);
        }

        return $this;
    }



   
   


}
