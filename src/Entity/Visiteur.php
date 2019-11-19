<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\VisiteurRepository")
 */
class Visiteur
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $visit_firstname;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $visit_lastname;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $visit_email;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getVisitFirstname(): ?string
    {
        return $this->visit_firstname;
    }

    public function setVisitFirstname(string $visit_firstname): self
    {
        $this->visit_firstname = $visit_firstname;

        return $this;
    }

    public function getVisitLastname(): ?string
    {
        return $this->visit_lastname;
    }

    public function setVisitLastname(string $visit_lastname): self
    {
        $this->visit_lastname = $visit_lastname;

        return $this;
    }

    public function getVisitEmail(): ?string
    {
        return $this->visit_email;
    }

    public function setVisitEmail(string $visit_email): self
    {
        $this->visit_email = $visit_email;

        return $this;
    }
}
