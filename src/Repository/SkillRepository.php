<?php

namespace App\Repository;

use App\Entity\Skill;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class SkillRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Skill::class);
    }

    public function findPublishedByCategory(string $category): array
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.category = :category')
            ->andWhere('s.isPublished = :published')
            ->andWhere('s.showInBars = :showInBars')
            ->setParameter('category', $category)
            ->setParameter('published', true)
            ->setParameter('showInBars', true)
            ->orderBy('s.displayOrder', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findPublishedTags(): array
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.isPublished = :published')
            ->andWhere('s.showInTags = :showInTags')
            ->setParameter('published', true)
            ->setParameter('showInTags', true)
            ->orderBy('s.displayOrder', 'ASC')
            ->getQuery()
            ->getResult();
    }
}