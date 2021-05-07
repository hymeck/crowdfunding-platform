<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\CampaignRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CampaignRepository::class)
 */
#[ApiResource]
class Campaign
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $name;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="integer")
     */
    private $money_amount;

    /**
     * @ORM\Column(type="datetimetz")
     */
    private $started_at;

    /**
     * @ORM\Column(type="datetimetz")
     */
    private $finished_at;

    /**
     * @ORM\ManyToOne(targetEntity=SubjectMatter::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $subject_matter;

    /**
     * @ORM\OneToMany(targetEntity=Image::class, mappedBy="campaign")
     */
    private $images;

    /**
     * @ORM\OneToMany(targetEntity=News::class, mappedBy="campaign")
     */
    private $news;

    /**
     * @ORM\OneToMany(targetEntity=Bonus::class, mappedBy="campaign")
     */
    private $bonuses;

    /**
     * @ORM\ManyToMany(targetEntity=Tag::class)
     */
    private $tags;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Rating", mappedBy="campaign")
     */
    private $rated_users;

    /**
     * @ORM\ManyToOne(targetEntity=CrowdfundingUser::class, inversedBy="campaigns")
     * @ORM\JoinColumn(nullable=false)
     */
    private $crowdfundingUser;

    public function __construct()
    {
        $this->images = new ArrayCollection();
        $this->news = new ArrayCollection();
        $this->bonuses = new ArrayCollection();
        $this->tags = new ArrayCollection();
        $this->rated_users = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
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

    public function getStartedAt(): ?\DateTimeInterface
    {
        return $this->started_at;
    }

    public function setStartedAt(\DateTimeInterface $started_at): self
    {
        $this->started_at = $started_at;

        return $this;
    }

    public function getFinishedAt(): ?\DateTimeInterface
    {
        return $this->finished_at;
    }

    public function setFinishedAt(\DateTimeInterface $finished_at): self
    {
        $this->finished_at = $finished_at;

        return $this;
    }

    public function getSubjectMatter(): ?SubjectMatter
    {
        return $this->subject_matter;
    }

    public function setSubjectMatter(?SubjectMatter $subject_matter): self
    {
        $this->subject_matter = $subject_matter;

        return $this;
    }

    /**
     * @return Collection|Image[]
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Image $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images[] = $image;
            $image->setCampaign($this);
        }

        return $this;
    }

    public function removeImage(Image $image): self
    {
        if ($this->images->removeElement($image)) {
            // set the owning side to null (unless already changed)
            if ($image->getCampaign() === $this) {
                $image->setCampaign(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|News[]
     */
    public function getNews(): Collection
    {
        return $this->news;
    }

    public function addNews(News $news): self
    {
        if (!$this->news->contains($news)) {
            $this->news[] = $news;
            $news->setCampaign($this);
        }

        return $this;
    }

    public function removeNews(News $news): self
    {
        if ($this->news->removeElement($news)) {
            // set the owning side to null (unless already changed)
            if ($news->getCampaign() === $this) {
                $news->setCampaign(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Bonus[]
     */
    public function getBonuses(): Collection
    {
        return $this->bonuses;
    }

    public function addBonus(Bonus $bonus): self
    {
        if (!$this->bonuses->contains($bonus)) {
            $this->bonuses[] = $bonus;
            $bonus->setCampaign($this);
        }

        return $this;
    }

    public function removeBonus(Bonus $bonus): self
    {
        if ($this->bonuses->removeElement($bonus)) {
            // set the owning side to null (unless already changed)
            if ($bonus->getCampaign() === $this) {
                $bonus->setCampaign(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Tag[]
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(Tag $tag): self
    {
        if (!$this->tags->contains($tag)) {
            $this->tags[] = $tag;
        }

        return $this;
    }

    public function removeTag(Tag $tag): self
    {
        $this->tags->removeElement($tag);

        return $this;
    }

    public function getCrowdfundingUser(): ?CrowdfundingUser
    {
        return $this->crowdfundingUser;
    }

    public function setCrowdfundingUser(?CrowdfundingUser $crowdfundingUser): self
    {
        $this->crowdfundingUser = $crowdfundingUser;

        return $this;
    }
}
