<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\PaymentRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PaymentRepository::class)
 */
#[ApiResource]
class Payment
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
    private $money_amount;

    /**
     * @ORM\ManyToOne(targetEntity=CrowdfundingUser::class, inversedBy="payments")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=Campaign::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $campaign;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMoneyAmount(): ?int
    {
        return $this->money_amount;
    }

    public function setMoneyAmount(int $money_amount): self
    {
        $this->money_amount = $money_amount;

        return $this;
    }

    public function getUser(): ?CrowdfundingUser
    {
        return $this->user;
    }

    public function setUser(?CrowdfundingUser $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getCampaign(): ?Campaign
    {
        return $this->campaign;
    }

    public function setCampaign(?Campaign $campaign): self
    {
        $this->campaign = $campaign;

        return $this;
    }
}
