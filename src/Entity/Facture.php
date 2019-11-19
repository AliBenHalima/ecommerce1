<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\FactureRepository")
 */
class Facture
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $fact_date;

    /**
     * @ORM\Column(type="float")
     */
    private $fact_total;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $fact_remise;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFactDate(): ?\DateTimeInterface
    {
        return $this->fact_date;
    }

    public function setFactDate(\DateTimeInterface $fact_date): self
    {
        $this->fact_date = $fact_date;

        return $this;
    }

    public function getFactTotal(): ?float
    {
        return $this->fact_total;
    }

    public function setFactTotal(float $fact_total): self
    {
        $this->fact_total = $fact_total;

        return $this;
    }

    public function getFactRemise(): ?float
    {
        return $this->fact_remise;
    }

    public function setFactRemise(?float $fact_remise): self
    {
        $this->fact_remise = $fact_remise;

        return $this;
    }
}
