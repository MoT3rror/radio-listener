<?php

namespace App\Entity;

use App\Repository\RadioRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use JsonSerializable;

#[ORM\Entity(repositoryClass: RadioRepository::class)]
class Radio implements JsonSerializable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $folderName = null;

    #[ORM\Column]
    #[Gedmo\Timestampable(on: 'create')]
    private ?\DateTimeImmutable $createdAt;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    #[Gedmo\Timestampable]
    private ?\DateTimeImmutable $updatedAt;

    /**
     * @var Collection<int, Recording>
     */
    #[ORM\OneToMany(targetEntity: Recording::class, mappedBy: 'radio', orphanRemoval: true)]
    private Collection $recordings;

    #[ORM\Column(length: 255)]
    private ?string $broadcastListenUrl = null;

    public function __construct()
    {
        $this->recordings = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

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

    public function getFolderName(): string
    {
        return $this->folderName;
    }

    public function setFolderName(string $folderName): static
    {
        $this->folderName = $folderName;

        return $this;
    }

    /**
     * @return Collection<int, Recording>
     */
    public function getRecordings(): Collection
    {
        return $this->recordings;
    }

    public function addRecording(Recording $recording): static
    {
        if (!$this->recordings->contains($recording)) {
            $this->recordings->add($recording);
            $recording->setRadio($this);
        }

        return $this;
    }

    public function removeRecording(Recording $recording): static
    {
        if ($this->recordings->removeElement($recording)) {
            // set the owning side to null (unless already changed)
            if ($recording->getRadio() === $this) {
                $recording->setRadio(null);
            }
        }

        return $this;
    }

    public function getBroadcastListenUrl(): ?string
    {
        return $this->broadcastListenUrl;
    }

    public function setBroadcastListenUrl(string $broadcastListenUrl): static
    {
        $this->broadcastListenUrl = $broadcastListenUrl;

        return $this;
    }

    public function jsonSerialize(): mixed
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'broadcast_listen_url' => $this->broadcastListenUrl,
            'created_at' => $this->createdAt,
            'updated_at' => $this->updatedAt,
        ];
    }
}
