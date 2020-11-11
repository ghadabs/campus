<?php

namespace App\Repository;

use App\Entity\Formation;
use App\Data\SearchData;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;

/**
 * @method Formation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Formation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Formation[]    findAll()
 * @method Formation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FormationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, PaginatorInterface $paginator)
    {
        parent::__construct($registry, Formation::class);
        $this->paginator = $paginator;
    }

    /**
     * @return PaginationInterface
     */

    public function findSearchP(SearchData $search, $value,string $term):PaginationInterface
    {
        $query=$this->createQueryBuilder('f')
            ->select('f', 't', 'c', 'n', 'l')
            ->orderBy('f.created_at', 'DESC')
            ->leftJoin('f.categorie', 'c')
            ->leftJoin('f.niveau', 'n')
            ->leftJoin('f.langue', 'l')
            ->leftJoin('f.type', 't')
            ->andWhere('f.type = :id')
            ->setParameter('id', $value)
            ->andWhere('f.statut LIKE :valide')
            ->setParameter('valide', '%' . $term . '%');
            ;

        if (!empty($search->q)) {
            $query = $query
                ->andWhere('f.title LIKE :q')
                ->setParameter('q', "%{$search->q}%")
                ->orderBy('f.created_at', 'DESC')
                ->setMaxResults(10);
        }

        if (!empty($search->categories)) {
            $query = $query
                ->andwhere('c.id IN (:categories)')
                ->setParameter('categories', $search->categories);
        }

        if (!empty($search->niveaux)) {
            $query = $query
                ->andwhere('n.id IN (:niveaux)')
                ->setParameter('niveaux', $search->niveaux);
        }
        if (!empty($search->langues)) {
            $query = $query
                ->andwhere('l.id IN (:langues)')
                ->setParameter('langues', $search->langues);
        }
        if (!empty($search->gratuit)) {
            $query = $query
                ->andWhere('f.gratuit = 1');
        }
        $query = $query->getQuery();
        return $this->paginator->paginate(
            $query,
            $search->page,
          3
        );
    }


    /*
    public function findOneBySomeField($value): ?Formation
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
   
    //get the jobs offer of comapny and filter by key word or/and category or/and type job
    /**
     * @return PaginationInterface
     */
    public function findSearch(SearchData $search, string $term): PaginationInterface
    {

        $query = $this->createQueryBuilder('f')
            ->select('f', 'c', 'n', 'l')
            ->orderBy('f.created_at', 'DESC')
            ->leftJoin('f.categorie', 'c')
            ->leftJoin('f.niveau', 'n')
            ->leftJoin('f.langue', 'l')
            ->andWhere('f.statut LIKE :valide')
            ->setParameter('valide', '%' . $term . '%');

        if (!empty($search->q)) {
            $query = $query
                ->andWhere('f.title LIKE :q')
                ->setParameter('q', "%{$search->q}%")
                ->orderBy('f.created_at', 'DESC')
                ->setMaxResults(10);
        }

        if (!empty($search->categories)) {
            $query = $query
                ->andwhere('c.id IN (:categories)')
                ->setParameter('categories', $search->categories);
        }

        if (!empty($search->niveaux)) {
            $query = $query
                ->andwhere('n.id IN (:niveaux)')
                ->setParameter('niveaux', $search->niveaux);
        }
        if (!empty($search->langues)) {
            $query = $query
                ->andwhere('l.id IN (:langues)')
                ->setParameter('langues', $search->langues);
        }
        if (!empty($search->gratuit)) {
            $query = $query
                ->andWhere('f.gratuit = 1');
        }
        $query = $query->getQuery();
        return $this->paginator->paginate(
            $query,
            $search->page,
            3
        );
    }

    public function findFormation()
    {
        return $this->createQueryBuilder('f')
            ->select('f')
            ->orderBy('f.created_at', 'DESC')
            ->andWhere('f.statut LIKE :valide')
            ->setParameter('valide',  'validée')
       
        ;
    }


}
