<?php

namespace App\Entity;

use App\Repository\SessionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\ExpressionValidator;

/**
 * @ORM\Entity(repositoryClass=SessionRepository::class)
 */
class Session
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime" ,nullable=true)
     */
    private $date_deb;

    /**
     * @ORM\Column(type="datetime",nullable=true)
     */
    private $date_fin;

    /**
     * @ORM\Column(type="datetime",nullable=true)
     */
    private $date_inscri_deb;

    /**
     * @ORM\Column(type="datetime",nullable=true)
     */
    private $date_inscri_fin;

    /**
     * @ORM\Column(type="integer",nullable=true)
     */
    private $nbrePlaces;

    /**
     * @ORM\ManyToOne(targetEntity="Formation", inversedBy="sessions",cascade={"remove"})
     * @ORM\JoinColumn(name="formation_id", referencedColumnName="id")
     */
    private $formation;

    /**
     * @ORM\Column(type="string", length=255,nullable=true)
     */
    private $adresse;

    // /**
    //  * @ORM\Column(type="datetime",options={"default": "CURRENT_TIMESTAMP"})
    //  */
    // private $created_at;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, inversedBy="sessions")
     */
    private $users;


    public function __construct()
    {

        // $this->createdAt = new \DateTime();
        $this->date_deb = new \DateTime();
        //$this->formations = new ArrayCollection();
        $this->users = new ArrayCollection();
       
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateDeb(): ?\DateTimeInterface
    {
        return $this->date_deb;
    }

    public function setDateDeb(\DateTimeInterface $date_deb): self
    {
        $this->date_deb = $date_deb;

        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->date_fin;
    }

    public function setDateFin(\DateTimeInterface $date_fin): self
    {
        $this->date_fin = $date_fin;

        return $this;
    }

    public function getDateInscriDeb(): ?\DateTimeInterface
    {
        return $this->date_inscri_deb;
    }

    public function setDateInscriDeb(\DateTimeInterface $date_inscri_deb): self
    {
        $this->date_inscri_deb = $date_inscri_deb;

        return $this;
    }

    public function getDateInscriFin(): ?\DateTimeInterface
    {
        return $this->date_inscri_fin;
    }

    public function setDateInscriFin(\DateTimeInterface $date_inscri_fin): self
    {
        $this->date_inscri_fin = $date_inscri_fin;

        return $this;
    }

    public function getNbrePlaces(): ?int
    {
        return $this->nbrePlaces;
    }

    public function setNbrePlaces(int $nbrePlaces): self
    {
        $this->nbrePlaces = $nbrePlaces;

        return $this;
    }

    public function getFormation(): ?Formation
    {
        return $this->formation;
    }

    public function setFormation(?Formation $formation): self
    {
        $this->formation = $formation;

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

    // public function getCreatedAt(): ?\DateTimeInterface
    // {
    //     return $this->created_at;
    // }

    // public function setCreatedAt(\DateTimeInterface $created_at): self
    // {
    //     $this->created_at = $created_at;

    //     return $this;
    // }

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
        }

        return $this;
    }

    public function __toString() {
        return $this->id;
    }





 
}
