<?php

namespace App\Entity;

use App\Repository\AudioFileRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use JsonSerializable;

#[ORM\Entity(repositoryClass: AudioFileRepository::class)]
#[Gedmo\Uploadable(
    path: 'public/uploads/audio-files',
    allowOverwrite: true,
    appendNumber: true
)]
class AudioFile implements JsonSerializable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    #[ORM\Column(name: 'path', type: Types::STRING)]
    #[Gedmo\UploadableFilePath]
    private string $path;

    #[ORM\Column(name: 'name', type: Types::STRING)]
    #[Gedmo\UploadableFileName]
    private string $name;

    #[ORM\Column(name: 'mime_type', type: Types::STRING)]
    #[Gedmo\UploadableFileMimeType]
    private string $mimeType;

    #[ORM\Column(name: 'size', type: Types::DECIMAL, precision: 10, scale: 0)]
    #[Gedmo\UploadableFileSize]
    private float $size;

    public function getPath(): string
    {
        return $this->path;
    }

    public function setPath(string $path): static
    {
        $this->path = $path;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getMimeType(): string
    {
        return $this->mimeType;
    }

    public function setMimeType(string $mimeType): static
    {
        $this->mimeType = $mimeType;

        return $this;
    }

    public function getSize(): float
    {
        return $this->size;
    }

    public function setSize(float $size): static
    {
        $this->size = $size;

        return $this;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'path' => str_replace('public', '', $this->path),
            'name' => $this->name,
            'mimeType' => $this->mimeType,
            'size' => $this->size,
        ];
    }
}
