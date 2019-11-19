<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CategorieRepository")
 */
class Categorie
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
    private $cat_label;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCatLabel(): ?string
    {
        return $this->cat_label;
    }

    public function setCatLabel(string $cat_label): self
    {
        $this->cat_label = $cat_label;

        return $this;
    }
}
