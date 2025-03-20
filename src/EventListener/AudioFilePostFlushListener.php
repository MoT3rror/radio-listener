<?php

namespace App\EventListener;

use App\Entity\AudioFile;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Events;

#[AsEntityListener(event: Events::postPersist, entity: AudioFile::class, method: 'postPersist')]
final class AudioFilePostFlushListener
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {}
    
    public function postPersist(AudioFile $audioFile): void
    {
        $number = str_pad($audioFile->getId(), 9, '0', STR_PAD_LEFT);

        $folder = [
            substr($number, 0, 3),
            substr($number, 3, 3),
        ];
        $baseFolder = 'public/uploads/audio-files/' . implode('/', $folder);
        if (! is_dir($baseFolder)) {
            mkdir(
                directory: $baseFolder,
                recursive: true,
            );
        }
        $path = $baseFolder . '/' . substr($number, 6, 3) . '-' . $audioFile->getName();
        @rename($audioFile->getPath(), $path);
        
        $audioFile->setPath($path);
        $this->entityManager->flush();
    }
}
