<?php

namespace App\Repository;

use App\Entity\Experience;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ExperienceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Experience::class);
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