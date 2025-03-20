<?php

namespace App\Repository;

use App\Entity\Recording;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Recording>
 */
class RecordingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Recording::class);
    }

    public function findRecordingsByRadioId(int $radioId, DateTime $searchDate): array
    {
        return $this->createQueryBuilder('recording')
            ->andWhere('recording.radio = :radioId')
            ->andWhere('recording.startTime > :startDate')
            ->andWhere('recording.startTime < :endDate')
            ->setParameter('radioId', $radioId)
            ->setParameter('startDate', $searchDate->format('Y-m-d') . ' 00:00:00')
            ->setParameter('endDate', $searchDate->format('Y-m-d') . ' 23:59:59')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findRecordingsWithNullVoiceToText(): array
    {
        return $this->createQueryBuilder('recording')
            ->andWhere('recording.voiceToText IS NULL')
            ->getQuery()
            ->getResult()
        ;
    }
}
