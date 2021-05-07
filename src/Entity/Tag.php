<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\TagRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TagRepository::class)
 */
#[ApiResource]
class Tag
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $name;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?int
    {
        return $this->name;
    }

    public function setName(int $name): self
    {
        $this->name = $name;

        return $this;
    }
}
