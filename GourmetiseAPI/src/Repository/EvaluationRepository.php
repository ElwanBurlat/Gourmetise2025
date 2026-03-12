<?php

namespace App\Repository;

use App\Entity\Evaluation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Evaluation>
 */
class EvaluationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Evaluation::class);
    }

    public function findScore():array
    {
        $qb = $this->createQueryBuilder('e');

        $qb->select(
            'SUM(e.welcome) / COUNT(e.id) AS note_w',
            'SUM(e.shopPresentation) / COUNT(e.id) AS note_s',
            'SUM(e.productQuality) / COUNT(e.id) AS note_p',
            '(SUM(e.welcome) + SUM(e.shopPresentation) + SUM(e.productQuality)) / (COUNT(e.id) * 3) AS moyenne'
        )
            ->groupBy('e.bakery_id')
            ->orderBy('moyenne', 'DESC')
            ->addOrderBy('SUM(e.productQuality)', 'DESC')
            ->addOrderBy('SUM(e.welcome)', 'DESC')
            ->addOrderBy('SUM(e.shopPresentation)', 'DESC');

        return  $qb->getQuery()->getResult();
    }

    //    /**
    //     * @return Evaluation[] Returns an array of Evaluation objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('e')
    //            ->andWhere('e.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('e.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Evaluation
    //    {
    //        return $this->createQueryBuilder('e')
    //            ->andWhere('e.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
