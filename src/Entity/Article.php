<?php

namespace App\Entity;

use App\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use phpDocumentor\Reflection\Types\Boolean;
use Symfony\Component\HttpFoundation\File\File;
use Doctrine\Common\Collections\ArrayCollection;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ArticleRepository")
 * @UniqueEntity(fields={"id"}, message="There is already an account with this id")
 * @Vich\Uploadable
 */

class Article
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
    private $art_description;

    /**
     * @ORM\Column(type="float")
     */
    private $art_prix;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $art_qte;

    /**
     * @ORM\Column(type="float")
     */
    private $art_total = 0;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $art_remise;

    /**
     * @var string|null
     * @ORM\Column(type="string", length=254, nullable=false)
     */
    private $art_filename;
    /** 


     * @var File|null
     * 
     * @Vich\UploadableField(mapping="property_image", fileNameProperty="art_filename")
     */
    private $art_imageFile;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updated_at;

    /**
     * Undocumented variable
     *
     * @ORM\OneToMany(targetEntity="App\Entity\ArticleLike" , mappedBy="article",cascade={"persist", "remove"}))
     */
    private $artcileLikes;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $promotion;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Categorie", inversedBy="articles")
     */
    private $categories;

    public function __construct()
    {
        $this->artcileLikes = new ArrayCollection();
        $this->categories = new ArrayCollection();
    }



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getArtDescription(): ?string
    {
        return $this->art_description;
    }

    public function setArtDescription(string $art_description): self
    {
        $this->art_description = $art_description;

        return $this;
    }

    public function getArtPrix(): ?float
    {
        return $this->art_prix;
    }

    public function setArtPrix(float $art_prix): self
    {
        $this->art_prix = $art_prix;

        return $this;
    }

    public function getArtQte(): ?int
    {
        return $this->art_qte;
    }

    public function setArtQte(?int $art_qte): self
    {
        $this->art_qte = $art_qte;

        return $this;
    }

    public function getArtRemise(): ?float
    {
        return $this->art_remise;
    }

    public function setArtRemise(?float $art_remise): self
    {
        $this->art_remise = $art_remise;

        return $this;
    }
    /**
     * @return string|null
     */
    public function getArtFilename(): ?string
    {
        return $this->art_filename;
    }
    /**
     * @param string|null $art_filename
     * @return Article
     */
    public function setArtFilename(?string $art_filename): self
    {
        $this->art_filename = $art_filename;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(\DateTimeInterface $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }
    /**
     * @return File|null
     */
    public function getArtImageFile(): ?File
    {
        return $this->art_imageFile;
    }

    /**
     * @param null|File $art_imageFile
     * @return Article
     */
    public function setArtImageFile(?File $art_imageFile): Article
    {
        $this->art_imageFile = $art_imageFile;
        if ($this->art_imageFile instanceof UploadedFile) {
            $this->updated_at = new \DateTime('now');
        }
        return $this;
    }
    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getArtTotal(): ?float
    {
        return $this->art_total;
    }

    public function setArtTotal(float $art_total): self
    {
        $this->art_total = $art_total;

        return $this;
    }

    /**
     * @return Collection|ArticleLike[]
     */
    public function getArtcileLikes(): Collection
    {
        return $this->artcileLikes;
    }

    public function addArtcileLike(ArticleLike $artcileLike): self
    {
        if (!$this->artcileLikes->contains($artcileLike)) {
            $this->artcileLikes[] = $artcileLike;
        }

        return $this;
    }

    public function removeArtcileLike(ArticleLike $artcileLike): self
    {
        if ($this->artcileLikes->contains($artcileLike)) {
            $this->artcileLikes->removeElement($artcileLike);
        }

        return $this;
    }
    /**
     * Undocumented function
     *
     * @param User $user
     * @return Boolean
     */
    public function IsLikedBy(User $user): bool
    {
        foreach ($this->artcileLikes as $like) {
            if ($like->getUser() === $user)
                return true;
        }
        return false;
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

    /**
     * @return Collection|Categorie[]
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Categorie $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories[] = $category;
            $category->setArticles($this);
        }

        return $this;
    }

    public function removeCategory(Categorie $category): self
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
}
