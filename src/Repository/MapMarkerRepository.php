<?php

namespace App\Repository;

use App\Entity\MapMarker;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<MapMarker>
 *
 * @method MapMarker|null find($id, $lockMode = null, $lockVersion = null)
 * @method MapMarker|null findOneBy(array $criteria, array $orderBy = null)
 * @method MapMarker[]    findAll()
 * @method MapMarker[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MapMarkerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MapMarker::class);
    }

    public function save(MapMarker $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(MapMarker $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getMarkers($point1,$point2)
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT m
            FROM App\Entity\MapMarker m
            WHERE m.latitude BETWEEN :p2_lat and :p1_lat
            AND m.longitude BETWEEN :p1_long and :p2_long'
        )->setParameters([
            'p1_lat'    => $point1['latitude'],
            'p1_long'   => $point1['longitude'],
            'p2_lat'    => $point2['latitude'],
            'p2_long'   => $point2['longitude']
        ]);

        return $query->getResult();
    }

    public function countAddedByUser(int $userId): int {
        return $this->createQueryBuilder('u')
                ->andWhere('u.added_by = :id')
                ->setParameter('id',$userId)
                ->select('COUNT (u.added_by) as added')
                ->getQuery()
                ->getOneOrNullResult()['added'];
    }

}
