<?php

namespace Bytesystems\NumberGeneratorBundle\Repository;

use Bytesystems\NumberGeneratorBundle\Entity\NumberSequence;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class NumberSequenceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, string $entityClass)
    {
        parent::__construct($registry, $entityClass);
    }


    public function add(NumberSequence $sequence, bool $flush = false): void
    {
        $this->getEntityManager()->persist($sequence);
        if(!$flush) return;
        $this->getEntityManager()->flush();
    }


    /**
     * @param $key
     * @param null $segment
     * @return NumberSequence[] Returns an array of Number Sequence Objects
     */
    public function getSequence($key, $segment = null): array
    {
        $qb = $this->createQueryBuilder('s');

        $qb ->andWhere($qb->expr()->eq('s.key',':key'))
            ->setParameter('key',$key);


        $qb ->andWhere(
            $qb->expr()->orX(
                $qb->expr()->eq('s.segment',':segment'),
                $qb->expr()->isNull('s.segment')
                )
            )
            ->setParameter('segment',$segment);


        return $qb ->getQuery()
                   ->getResult();
    }

    public function flush()
    {
        $this->getEntityManager()->flush();
    }
}