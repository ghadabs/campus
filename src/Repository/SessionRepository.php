<?php

namespace App\Repository;

use App\Data\SearchData;
use App\Entity\Session;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;

/**
 * @method Session|null find($id, $lockMode = null, $lockVersion = null)
 * @method Session|null findOneBy(array $criteria, array $orderBy = null)
 * @method Session[]    findAll()
 * @method Session[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SessionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, PaginatorInterface $paginator)
    {
        parent::__construct($registry, Session::class);
        $this->paginator = $paginator;
    }

    // /**
    //  * @return Session[] Returns an array of Session objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Session
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    /**
     * @return PaginationInterface
     */
    public function findSearch(SearchData $search, $valeur): PaginationInterface
    {

        $query = $this->createQueryBuilder('s')
            ->select('f', 's')
            ->orderBy('s.date_inscri_deb', 'DESC')
            ->leftJoin('s.formation', 'f')
            ->andWhere('s.date_deb > CURRENT_DATE()')
            ->andWhere('f.statut LIKE :valide')
            ->setParameter('valide', '%' . $valeur . '%');

        if (!empty($search->formations)) {
            $query = $query
                ->andwhere('f.id IN (:formations)')
                ->setParameter('formations', $search->formations);
        }

        if (!empty($search->dateDeb)) {
            $query = $query
                ->andWhere('s.date_deb >= :dateDeb')
                ->setParameter('dateDeb', $search->dateDeb->format('Y-m-d'). ' 00:00:00');
        }

        if (!empty($search->dateFin)) {
            $query = $query
                ->andWhere('s.date_fin <= :dateFin')
                ->setParameter('dateFin', $search->dateFin->format('Y-m-d'). ' 23:59:59');
        }
   
        $query = $query->getQuery();
        return $this->paginator->paginate(
            $query,
            $search->page,
            1
        );
    }
}
