<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CommandeRepository")
 */
class Commande 
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     */
    private $cmd_date;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $cmd_total;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $cmd_description;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCmdDate(): ?\DateTimeInterface
    {
        return $this->cmd_date;
    }

    public function setCmdDate(\DateTimeInterface $cmd_date): self
    {
        $this->cmd_date = $cmd_date;

        return $this;
    }

    public function getCmdTotal(): ?float
    {
        return $this->cmd_total;
    }

    public function setCmdTotal(?float $cmd_total): self
    {
        $this->cmd_total = $cmd_total;

        return $this;
    }

    public function getCmdDescription(): ?string
    {
        return $this->cmd_description;
    }

    public function setCmdDescription(string $cmd_description): self
    {
        $this->cmd_description = $cmd_description;

        return $this;
    }
}
