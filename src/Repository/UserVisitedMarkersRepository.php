<?php

namespace App\Repository;

use App\Entity\UserVisitedMarkers;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UserVisitedMarkers>
 *
 * @method UserVisitedMarkers|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserVisitedMarkers|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserVisitedMarkers[]    findAll()
 * @method UserVisitedMarkers[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserVisitedMarkersRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserVisitedMarkers::class);
    }

    public function save(UserVisitedMarkers $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(UserVisitedMarkers $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function countVisited(int $userId): int {
        return $this->createQueryBuilder('u')
                ->andWhere('u.user_id = :id')
                ->setParameter('id',$userId)
                ->select('COUNT (u.user_id) as visited')
                ->getQuery()
                ->getOneOrNullResult()['visited'];
    }

//    /**
//     * @return UserVisitedMarkers[] Returns an array of UserVisitedMarkers objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('u.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?UserVisitedMarkers
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
