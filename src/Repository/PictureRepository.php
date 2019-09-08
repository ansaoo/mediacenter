<?php

namespace App\Repository;

use App\Entity\Picture;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Criteria;
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

    /**
     * @param $albumId
     * @return mixed
     */
    public function findByAlbum($albumId)
    {
        return $this->createQueryBuilder("fba")
            ->select("*")
            ->from(Picture::class, 'p')
            ->where(Criteria::expr()->startsWith("filename", $albumId))
            ->getQuery()
            ->getResult()
            ;
    }

    public function findByYearAndMonth($year, $month)
    {
        $fromTime = new \DateTime($year . '-' . sprintf("%.2d", $month) . '-01');
        $toTime = new \DateTime($fromTime->format('Y-m-d') . ' first day of next month');
        return $this->createQueryBuilder('p')
            ->where('p.created >= :fromTime')
            ->andWhere('p.created < :toTime')
            ->andWhere('p.status = :status')
            ->setParameter('status', true)
            ->setParameter('fromTime', $fromTime)
            ->setParameter('toTime', $toTime)
            ->orderBy('p.created', 'DESC')
            ->getQuery()
            ->getResult();
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
