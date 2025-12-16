<?php

namespace App\Entity;

use App\Repository\EventsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: EventsRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Events
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: "Typ zdarzenia jest wymagany.")]
    #[Assert\GreaterThan(
        value: 0,
        message: "Nieprawidłowy typ zdarzenia."
    )]
    private ?int $typeID = null;

    #[ORM\Column(type: Types::TEXT, length: 500)]
    #[Assert\NotBlank(message: "Opis zdarzenia jest wymagany.")]
    #[Assert\Length(
        min: 5,
        max: 500,
        minMessage: "Opis musi mieć minimum {{limit}} znaków.",
        maxMessage: "Opis może mieć maksymalnie {{limit}} znaków."
    )]
    private ?string $description = null;

    #[ORM\Column(type: "string", length: 100)]
    #[Assert\NotBlank(message: "Miejsce jest wymagane.")]
    #[Assert\Length(
        min: 5,
        max: 100,
        minMessage: "Miejsce musi mieć minimum {{limit}} znaków.",
        maxMessage: "Miejsce może mieć maksymalnie {{limit}} znaków."
    )]
    private ?string $place = null;

    #[ORM\Column(type: 'datetime')]
    #[Assert\NotBlank(message: "Data i czas są wymagane.")]
    #[Assert\GreaterThanOrEqual(
        value: '-12 hours',
        message: "Data nie może być wcześniejsza niż 12 godzin wstecz."
    )]
    #[Assert\LessThanOrEqual(
        value: "now",
        message: "Data nie może być z przyszłości."
    )]
    private ?\DateTime $date = null;

    #[ORM\Column]
    private ?int $author_ID = null;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    public function __construct()
    {
        $this->date = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTypeID(): ?int
    {
        return $this->typeID;
    }

    public function setTypeID(int $typeID): self
    {
        $this->typeID = $typeID;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getPlace(): ?string
    {
        return $this->place;
    }

    public function setPlace(string $place): static
    {
        $this->place = $place;

        return $this;
    }

    public function getDate(): ?\DateTime
    {
        return $this->date;
    }

    public function setDate(\DateTime $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getAuthorID(): ?int
    {
        return $this->author_ID;
    }

    public function setAuthorID(int $author_ID): self
    {
        $this->author_ID = $author_ID;
        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    #[ORM\PrePersist]
    public function onPrePersist(): void
    {
        $this->updatedAt = new \DateTimeImmutable();
    }

    #[ORM\PreUpdate]
    public function onPreUpdate(): void
    {
        $this->updatedAt = new \DateTimeImmutable();
    }
}
