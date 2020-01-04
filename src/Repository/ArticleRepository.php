<?php

namespace App\Repository;

use App\Entity\Search;
use App\Entity\Article;
use Doctrine\ORM\Query;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Article::class);
    }

    /**
     * @return Query
     */

    public function FindArticles(): Query
    {
        return $this->createQueryBuilder('a')
            ->getQuery();
    }
    public function findSearch(Search $search): array
    {
        $query = $this
            ->createQueryBuilder('p');
        if ($search->getSearchtext()) {
            $query = $query->andWhere('p.art_description LIKE :val')
                ->setParameter('val', "%{$search->getSearchtext()}%");
        }
        if ($search->getMin()) {
            $query = $query->andWhere('p.art_prix >= :min')
                ->setParameter('min', $search->getMin());
        }
        if ($search->getMax()) {
            $query = $query->andWhere('p.art_prix <= :max')
                ->setParameter('max', $search->getMax());
        }
        if ($search->getPromotion()) {
            $query = $query->andWhere('p.promotion = 1');
        }
        if ($search->getCategories()) {
            $query = $query->andWhere(":cat MEMBER OF p.categories")
                ->setParameter("cat", $search->getCategories());
            // ->setParameter('val', "%{$search->getCategories()}%");
        }


        return $query->getQuery()->getResult();
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
}
