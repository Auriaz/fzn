<?php

namespace App\Repository;

use App\Entity\FileManager;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method FileManager|null find($id, $lockMode = null, $lockVersion = null)
 * @method FileManager|null findOneBy(array $criteria, array $orderBy = null)
 * @method FileManager[]    findAll()
 * @method FileManager[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FileManagerRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, FileManager::class);
    }

    public function findByAll()
    {
        return $this->createQueryBuilder('f')
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }

    public function findReferenceToRemove(array $filenames, int $article_id)
    {
       $qb = $this->createQueryBuilder('m');

        $qb ->select()
            ->where(
                $qb->expr()->andX(
                    $qb->expr()->eq('m.articles', $article_id),
                    $qb->expr()->notIn('m.filename', $filenames)
                )
            )
        ;


        return $qb->getQuery()->getResult();
    }
    // /**
    //  * @return FileManager[] Returns an array of FileManager objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

   
    // public function findOneBySomeField($value): ?FileManager
    // {
    //     return $this->createQueryBuilder('f')
    //         ->andWhere('f.exampleField = :val')
    //         ->setParameter('val', $value)
    //         ->getQuery()
    //         ->getOneOrNullResult()
    //     ;
    // }

}
