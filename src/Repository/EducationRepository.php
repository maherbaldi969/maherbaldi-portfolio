<?php

namespace App\Repository;

use App\Entity\Education;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class EducationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Education::class);
    }

    public function findPublishedOrdered(): array
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.isPublished = :published')
            ->setParameter('published', true)
            ->orderBy('e.displayOrder', 'ASC')
            ->getQuery()
            ->getResult();
    }
}