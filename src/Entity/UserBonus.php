<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\UserBonusRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UserBonusRepository::class)
 * @ORM\Table(name="user_bonuses")
 */
class UserBonus
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Payment::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $payment;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Bonus")
     * @ORM\JoinColumn(nullable=false)
     */
    private $bonus;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPayment(): ?Payment
    {
        return $this->payment;
    }

    public function setPayment(?Payment $payment): self
    {
        $this->payment = $payment;

        return $this;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser($user) : self
    {
        $this->user = $user;
        return $this;
    }

    public function getBonus() : Bonus
    {
        return $this->bonus;
    }

    public function setBonus($bonus) : self
    {
        $this->bonus = $bonus;
        return $this;
    }
}
