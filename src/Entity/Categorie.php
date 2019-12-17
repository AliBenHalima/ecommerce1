<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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
     * @ORM\Column(type="string", length=254)
     */
    private $cat_label;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Article", mappedBy="categories")
     */
    private $articles;


    public function __construct()
    {
        $this->categories = new ArrayCollection();
    }

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

    public function getArticles(): ?self
    {
        return $this->articles;
    }

    public function setArticles(?self $articles): self
    {
        $this->articles = $articles;

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(self $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories[] = $category;
            $category->setArticles($this);
        }

        return $this;
    }

    public function removeCategory(self $category): self
    {
        if ($this->categories->contains($category)) {
            $this->categories->removeElement($category);
            // set the owning side to null (unless already changed)
            if ($category->getArticles() === $this) {
                $category->setArticles(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->cat_label;
    }
}
