<?php

namespace App\Entity;

use App\Repository\ExperienceRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ExperienceRepository::class)]
#[ORM\Table(name: 'experiences')]
class Experience
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 150)]
    private ?string $jobTitle = null;

    #[ORM\Column(length: 180)]
    private ?string $companyName = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $employmentType = null;

    #[ORM\Column(length: 100)]
    private ?string $startLabel = null;

    #[ORM\Column(length: 100)]
    private ?string $endLabel = null;

    #[ORM\Column(length: 180, nullable: true)]
    private ?string $locationLabel = null;

    #[ORM\Column(type: 'text')]
    private ?string $summary = null;

    #[ORM\Column(type: 'json')]
    private array $achievements = [];

    #[ORM\Column(type: 'json')]
    private array $technologies = [];

    #[ORM\Column]
    private int $displayOrder = 0;

    #[ORM\Column]
    private bool $isFeatured = false;

    #[ORM\Column]
    private bool $isPublished = true;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updatedAt = null;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function getId(): ?int { return $this->id; }

    public function getJobTitle(): ?string { return $this->jobTitle; }
    public function setJobTitle(string $jobTitle): static { $this->jobTitle = $jobTitle; return $this; }

    public function getCompanyName(): ?string { return $this->companyName; }
    public function setCompanyName(string $companyName): static { $this->companyName = $companyName; return $this; }

    public function getEmploymentType(): ?string { return $this->employmentType; }
    public function setEmploymentType(?string $employmentType): static { $this->employmentType = $employmentType; return $this; }

    public function getStartLabel(): ?string { return $this->startLabel; }
    public function setStartLabel(string $startLabel): static { $this->startLabel = $startLabel; return $this; }

    public function getEndLabel(): ?string { return $this->endLabel; }
    public function setEndLabel(string $endLabel): static { $this->endLabel = $endLabel; return $this; }

    public function getLocationLabel(): ?string { return $this->locationLabel; }
    public function setLocationLabel(?string $locationLabel): static { $this->locationLabel = $locationLabel; return $this; }

    public function getSummary(): ?string { return $this->summary; }
    public function setSummary(string $summary): static { $this->summary = $summary; return $this; }

    public function getAchievements(): array { return $this->achievements; }
    public function setAchievements(array $achievements): static { $this->achievements = $achievements; return $this; }

    public function getTechnologies(): array { return $this->technologies; }
    public function setTechnologies(array $technologies): static { $this->technologies = $technologies; return $this; }

    public function getDisplayOrder(): int { return $this->displayOrder; }
    public function setDisplayOrder(int $displayOrder): static { $this->displayOrder = $displayOrder; return $this; }

    public function isFeatured(): bool { return $this->isFeatured; }
    public function setIsFeatured(bool $isFeatured): static { $this->isFeatured = $isFeatured; return $this; }

    public function isPublished(): bool { return $this->isPublished; }
    public function setIsPublished(bool $isPublished): static { $this->isPublished = $isPublished; return $this; }

    public function getCreatedAt(): ?\DateTimeImmutable { return $this->createdAt; }
    public function setCreatedAt(\DateTimeImmutable $createdAt): static { $this->createdAt = $createdAt; return $this; }

    public function getUpdatedAt(): ?\DateTimeImmutable { return $this->updatedAt; }
    public function setUpdatedAt(\DateTimeImmutable $updatedAt): static { $this->updatedAt = $updatedAt; return $this; }
}