<?php

namespace App\Repository;

use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\QueryBuilder as DoctrineQueryBuilder;

/**
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Article::class);
    }

    /**
     * @return Article[]
     */

    public function getWithSearchQueryBuilder(?string $term): DoctrineQueryBuilder
    {
        $qb = $this->createQueryBuilder('a')
            ->andWhere('a.isDelete = false')
            ->innerJoin('a.author', 'u')
            ->addSelect('u');

        if ($term) {
            $qb->andWhere('a.content LIKE :term OR a.title LIKE :term OR u.firstName LIKE :term OR u.lastName LIKE :term')
                ->setParameter('term', '%' . $term . '%');
        }


        return $qb->orderBy('a.createdAt', 'DESC');
    }

    public function findAllPublishedOrderedByArticles(int $count = null)
    {
        $query = $this->addIsPublishedQueryBuilder()
                ->leftJoin('a.tags', 't')
                ->addSelect('t')
                ->orderBy('a.publishedAt', 'DESC');

         if($count) {
            $query->setMaxResults($count);
        }

        return  $query
                    ->getQuery()
                    ->getResult();
    }

    public function createNonDeletedCriteria(): Criteria
    {
        return Criteria::create()
            ->andWhere(Criteria::expr()->eq('isDeleted', false))
            ->orderBy(['createdAt' => 'DESC']);
    }

    /*
    public function findOneBySomeField($value): ?Article
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
        return $this->getOrCreateQueryBuilder($qb)
            ->andWhere('a.publishedAt IS NOT NULL');
    }

    private function getOrCreateQueryBuilder(QueryBuilder $qb = null)
    {
        return $qb ?: $this->createQueryBuilder('a');
    }
}
