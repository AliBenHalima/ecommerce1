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
    private $art_total;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $art_remise;

   /**
     * @var string|null
     * @ORM\Column(type="string", length=255, nullable=false)
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

}
