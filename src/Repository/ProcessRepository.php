<?php

namespace App\Repository;

use App\Entity\Process;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Process|null find($id, $lockMode = null, $lockVersion = null)
 * @method Process|null findOneBy(array $criteria, array $orderBy = null)
 * @method Process[]    findAll()
 * @method Process[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProcessRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Process::class);
    }

    /**
     * @return mixed
     * @throws NonUniqueResultException
     */
    public function getProgressList()
    {
        $qb = $this->createQueryBuilder('p');
        $q = $qb
            ->select('(p.' . Process::PROPERTY_ARTICLE . ')' . ' as ' . Process::PROPERTY_ARTICLE)
            ->addSelect('(p.' . Process::PROPERTY_CONFIG . ')' . ' as ' . Process::PROPERTY_CONFIG)
            ->addSelect('p.' . Process::PROPERTY_PROCESS)
            ->distinct(true)
            ->getQuery();
        $processList = $q->getResult();

        foreach ($processList as $index => $process) {
            $processList[$index]['min'] = $this->getMinTimestamp($process[Process::PROPERTY_PROCESS]);
            $processList[$index]['max'] = $this->getMaxTimestamp($process[Process::PROPERTY_PROCESS]);
        }

        return $processList;
    }

    /**
     * @param $process
     * @return mixed
     * @throws NonUniqueResultException
     */
    public function getMinTimestamp(string $process): string
    {
        $qb = $this->createQueryBuilder('p');
        $q = $qb
            ->select($qb->expr()->min('p.' . Process::PROPERTY_TIMESTAMP))
            ->where($qb->expr()->eq('p.' . Process::PROPERTY_PROCESS, '?1'))
            ->setParameter('1', $process)
            ->distinct(true)
            ->getQuery();
        return $q->getSingleScalarResult();
    }

    /**
     * @param $process
     * @return mixed
     * @throws NonUniqueResultException
     */
    public function getMaxTimestamp(string $process): string
    {
        $qb = $this->createQueryBuilder('p');
        $q = $qb
            ->select($qb->expr()->max('p.' . Process::PROPERTY_TIMESTAMP))
            ->where($qb->expr()->eq('p.' . Process::PROPERTY_PROCESS, '?1'))
            ->setParameter('1', $process)
            ->distinct(true)
            ->getQuery();
        return $q->getSingleScalarResult();
    }

//    /**
//     * @return Process[] Returns an array of Process objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Process
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
