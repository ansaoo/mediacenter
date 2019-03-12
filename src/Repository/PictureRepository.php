<?php

namespace App\Repository;

use App\Entity\Picture;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Picture|null find($id, $lockMode = null, $lockVersion = null)
 * @method Picture|null findOneBy(array $criteria, array $orderBy = null)
 * @method Picture[]    findAll()
 * @method Picture[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PictureRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Picture::class);
    }

//    /**
//     * @return mixed
//     * @throws \Doctrine\ORM\NoResultException
//     * @throws \Doctrine\ORM\NonUniqueResultException
//     */
//    public function count()
//    {
//        return $this->createQueryBuilder("p")
//            ->select("count(p.id)")
//            ->from('Picture', 'p')
//            ->getQuery()
//            ->getSingleScalarResult();
//    }


    /**
     * @return mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function sumFileSize()
    {
        return $this->createQueryBuilder("sum")
            ->select("sum(p.fileSize)")
            ->from(Picture::class, 'p')
            ->getQuery()
            ->getSingleScalarResult();
    }

    // /**
    //  * @return Picture[] Returns an array of Picture objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Picture
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
