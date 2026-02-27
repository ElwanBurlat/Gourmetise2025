<?php

namespace App\Repository;

use App\Entity\EvaluationCode;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<EvaluationCode>
 */
class EvaluationCodeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EvaluationCode::class);
    }
    public function findValidCode(string $code, string $bakeryId): ?EvaluationCode
    {
        return $this->createQueryBuilder('e')
            ->join('e.bakery', 'b')
            ->andWhere('e.code = :code')
            ->andWhere('b.siret = :bakeryId')
            ->andWhere('e.used = false')
            ->setParameter('code', $code)
            ->setParameter('bakeryId', $bakeryId)
            ->getQuery()
            ->getOneOrNullResult();
    }

}
