<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SearchRepository")
 */
class Search
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $searchtext;

    /**
     * @var null|integer
     * @ORM\Column(type="integer", nullable=true)
     */
    private $min;

    /**
     *  @var null|integer
     * @ORM\Column(type="integer", nullable=true)
     */
    private $max;

    /**
     * @var Categorie[]
     * @ORM\Column(type="array", nullable=true)
     */
    private $categories = [];

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $promotion;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSearchtext(): ?string
    {
        return $this->searchtext;
    }

    public function setSearchtext(?string $searchtext): self
    {
        $this->searchtext = $searchtext;

        return $this;
    }

    public function getMin(): ?int
    {
        return $this->min;
    }

    public function setMin(?int $min): self
    {
        $this->min = $min;

        return $this;
    }

    public function getMax(): ?int
    {
        return $this->max;
    }

    public function setMax(?int $max): self
    {
        $this->max = $max;

        return $this;
    }

    public function getCategories(): ?array
    {
        return $this->categories;
    }

    public function setCategories(?array $categories): self
    {
        $this->categories = $categories;

        return $this;
    }

    public function getPromotion(): ?bool
    {
        return $this->promotion;
    }

    public function setPromotion(?bool $promotion): self
    {
        $this->promotion = $promotion;

        return $this;
    }
}
