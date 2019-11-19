<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MarkRepository")
 */
class Mark
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
    private $mark_label;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMarkLabel(): ?string
    {
        return $this->mark_label;
    }

    public function setMarkLabel(string $mark_label): self
    {
        $this->mark_label = $mark_label;

        return $this;
    }
}
