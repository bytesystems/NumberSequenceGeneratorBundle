<?php

namespace Bytesystems\NumberGeneratorBundle\Repository;

use Bytesystems\NumberGeneratorBundle\Entity\NumberSequence;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
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
     * @return NumberSequence Returns null or single Number Sequence
     */
    public function getSequence($key, $segment = null): ?NumberSequence
    {
        $qb = $this->createQueryBuilder('s');

        $qb ->andWhere($qb->expr()->eq('s.key',':key'))
            ->setParameter('key',$key);


        $qb ->andWhere($qb->expr()->isNull('s.segment'));

        return $qb->getQuery()->getOneOrNullResult();
    }

    /**
     * @param $key
     * @param $segment
     * @return NumberSequence Returns null or single Number Sequence
     */
    public function getSegmentedSequence($key, $segment): ?NumberSequence
    {
        $qb = $this->createQueryBuilder('s');

        $qb ->andWhere($qb->expr()->eq('s.key',':key'))
            ->setParameter('key',$key);


        $qb ->andWhere($qb->expr()->eq('s.segment',':segment'))
            ->setParameter('segment',$segment);

        return $qb->getQuery()->getOneOrNullResult();
    }

    public function flush()
    {
        $this->getEntityManager()->flush();
    }
}