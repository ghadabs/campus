<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\Formation;
use App\Entity\Session;
use App\Data\SearchData;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Knp\Component\Pager\PaginatorInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry,PaginatorInterface $paginator)
    {
        parent::__construct($registry, User::class);
        $this->paginator=$paginator;
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(UserInterface $user, string $newEncodedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newEncodedPassword);
        $this->_em->persist($user);
        $this->_em->flush();
    }

    /**
     * @return User[] 
     */
    
    public function findCollab()
    {
        return $this->createQueryBuilder('u')
        ->select('u') 
        ->where('u.roles NOT LIKE :role') 
        ->andWhere('u.statut NOT LIKE :statut') 
        ->setParameter('role', '%"'.'ROLE_ADMIN'.'"%') 
        ->setParameter('statut', 'rien') 
        ->getQuery()
        ->getResult();
        ;
    }
    /**
    * @return User[] 
    */
   
   public function FindEq(Formation $formation)
    {
        $qb = $this->createQueryBuilder("u")
            ->where(':formation MEMBER OF u.formations')
            ->setParameters(array('formation' => $formation))
        ;
        return $qb->getQuery()->getResult();
    }

    /**
    * @return User[] 
    */
   
   public function FindUS(Session $session)
   {
       $qb = $this->createQueryBuilder("u")
           ->select('u')
           ->where(':session MEMBER OF u.sessions')
           ->setParameters(array('session' => $session))
       ;
       return $qb->getQuery()->getResult();
   }

  
    public function findByEmail($value):? User
    {
        return $this->getEntityManager()
        ->createQuery(
           'SELECT u
            FROM  App\Entity\User u
            where (u.email LIKE :value) and (u.roles NOT LIKE :role)and(u.statut LIKE :valide)'
        )
     
        ->setParameter('valide', '%' . 'validé' . '%')
        ->setParameter('value', '%'.$value.'%')
        ->setParameter('role', '%"'.'ROLE_ADMIN'.'"%') 
        ->getSingleResult()
           
        ;
    }
    
    
    /**
     * @return PaginationInterface
     */
    public function findSearch(SearchData $search): PaginationInterface
    {

        $query = $this
            ->createQueryBuilder('u')
            ->orderBy('u.created_at','DESC');

        if (!empty($search->q)) {
            $query = $query
                ->andWhere('u.name LIKE :q')
                ->setParameter('q', "%{$search->q}%");
        }

        return $this->paginator->paginate(
            $query,
            $search->page,
            9
        );
    }



    public function findOneByEmail( $valeur)
    {

  
        return $this->createQueryBuilder('u')
        ->select('u') 
        ->where('u.email LIKE :val') 
        
        ->setParameter('val', '%' . $valeur . '%') 
        ->getQuery()
        ->getResult();

    }

  
}
