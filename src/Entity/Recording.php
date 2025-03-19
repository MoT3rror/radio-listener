<?php

namespace App\Entity;

use App\JsonRepresentative\Date;
use App\Repository\RecordingRepository;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use JsonSerializable;

#[ORM\Entity(repositoryClass: RecordingRepository::class)]
class Recording implements JsonSerializable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIMETZ_IMMUTABLE)]
    private ?DateTimeImmutable $startTime = null;

    #[ORM\Column(type: Types::DATETIMETZ_IMMUTABLE)]
    private ?DateTimeImmutable $endTime = null;

    #[ORM\Column]
    #[Gedmo\Timestampable(on: 'create')]
    private ?DateTimeImmutable $createdAt;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    #[Gedmo\Timestampable]
    private ?DateTimeImmutable $updatedAt;

    #[ORM\ManyToOne(inversedBy: 'recordings')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Radio $radio = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?AudioFile $audioFile = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStartTime(): ?\DateTimeInterface
    {
        return $this->startTime;
    }

    public function setStartTime(\DateTimeInterface $startTime): static
    {
        $this->startTime = $startTime;

        return $this;
    }

    public function getEndTime(): ?\DateTimeInterface
    {
        return $this->endTime;
    }

    public function setEndTime(\DateTimeInterface $endTime): static
    {
        $this->endTime = $endTime;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): static
    {
        $this->createdAt = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updated_at): static
    {
        $this->updatedAt = $updated_at;

        return $this;
    }

    public function getRadio(): ?Radio
    {
        return $this->radio;
    }

    public function setRadio(?Radio $radio): static
    {
        $this->radio = $radio;

        return $this;
    }

    public function getAudioFile(): ?AudioFile
    {
        return $this->audioFile;
    }

    public function setAudioFile(AudioFile $audioFile): static
    {
        $this->audioFile = $audioFile;

        return $this;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'startTime' => Date::createFromImmutable($this->startTime),
            'endTime' => Date::createFromImmutable($this->endTime),
            'audioFile' => $this->audioFile,
        ];
    }
}
