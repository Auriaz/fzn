<?php

namespace App\Repository;

use App\Entity\Animal;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\ORM\QueryBuilder as DoctrineQueryBuilder;

/**
 * @method Animal|null find($id, $lockMode = null, $lockVersion = null)
 * @method Animal|null findOneBy(array $criteria, array $orderBy = null)
 * @method Animal[]    findAll()
 * @method Animal[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AnimalRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Animal::class);
    }

    /**
     * @return Animal[] Returns an array of Animal objects
     */

    public function findAllPublishedOrderedByAnimals(int $count = null)
    {
        $query = $this->addIsPublishedQueryBuilder()
            ->orderBy('a.createdAt', 'DESC');

        if ($count) {
            $query->setMaxResults($count);
        }

        return  $query
            ->getQuery()
            ->getResult();
    }

    public function getWithSearchQueryBuilder(?string $term): DoctrineQueryBuilder
    {
        $qb = $this->createQueryBuilder('a')
            // ->andWhere('a.isDelete = false')
            ->innerJoin('a.user', 'u')
            ->addSelect('u')
            ;

        if ($term) {
            $qb->andWhere('a.name LIKE :term OR a.category LIKE :term OR u.firstName LIKE :term OR u.lastName LIKE :term')
                ->setParameter('term', '%' . $term . '%');
        }

        return $qb->orderBy('a.createdAt', 'DESC');
    }

    public function getSearchQueryBuilderCategory(int $category = null, ?string $term): DoctrineQueryBuilder
    {
        $qb = $this->createQueryBuilder('a');
            // ->andWhere('a.isDelete = false')
            
            // ->innerJoin('a.user', 'u')
            // ->addSelect('u');
        if($category) {
            $qb->andWhere('a.category = :category')
                ->setParameter('category',  $category );
        }

        if ($term) {
            $qb->andWhere('a.name LIKE :term')
                ->setParameter('term', '%' . $term . '%');
        }

        return $qb->orderBy('a.createdAt', 'DESC');
    }
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Animal
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    private function addIsPublishedQueryBuilder(QueryBuilder $qb = null)
    {
        return $this->getOrCreateQueryBuilder($qb);
            // ->andWhere('a.isActive = false');
    }

    private function getOrCreateQueryBuilder(QueryBuilder $qb = null)
    {
        return $qb ?: $this->createQueryBuilder('a');
    }
}
