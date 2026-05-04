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

    public function findScore(): array
    {
        return $this->createQueryBuilder('e')
            ->join('e.bakery', 'b')
            ->select(
                'b.siret AS bakery_siret',
                'b.companyName AS company_name',
                'SUM(e.welcome) / COUNT(e.id) AS note_w',
                'SUM(e.shopPresentation) / COUNT(e.id) AS note_s',
                'SUM(e.productQuality) / COUNT(e.id) AS note_p',
                '(SUM(e.welcome) + SUM(e.shopPresentation) + SUM(e.productQuality)) / (COUNT(e.id) * 3) AS moyenne'
            )
            ->groupBy('b.siret')
            ->addGroupBy('b.companyName')
            ->orderBy('moyenne', 'DESC')
            ->addOrderBy('note_p', 'DESC')
            ->addOrderBy('note_w', 'DESC')
            ->addOrderBy('note_s', 'DESC')
            ->getQuery()
            ->getArrayResult();
    }

    public function findScoreById(string $siret): array
    {
        return $this->createQueryBuilder('e')
            ->join('e.bakery', 'b')
            ->select(
                'b.siret AS bakery_siret',
                'b.companyName AS company_name',
                'SUM(e.welcome) / COUNT(e.id) AS note_w',
                'SUM(e.shopPresentation) / COUNT(e.id) AS note_s',
                'SUM(e.productQuality) / COUNT(e.id) AS note_p',
                '(SUM(e.welcome) + SUM(e.shopPresentation) + SUM(e.productQuality)) / (COUNT(e.id) * 3) AS moyenne'
            )
            ->where('b.siret = :siret')
            ->setParameter('siret', $siret)
            ->getQuery()
            ->getArrayResult();
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
