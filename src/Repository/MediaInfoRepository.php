<?php

namespace App\Repository;

use App\Entity\MediaInfo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method MediaInfo|null find($id, $lockMode = null, $lockVersion = null)
 * @method MediaInfo|null findOneBy(array $criteria, array $orderBy = null)
 * @method MediaInfo[]    findAll()
 * @method MediaInfo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MediaInfoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, MediaInfo::class);
    }

    // /**
    //  * @return MediaInfo[] Returns an array of MediaInfo objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?MediaInfo
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
