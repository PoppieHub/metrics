<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function findVisitors()
    {
        $db = $this->createQueryBuilder('u')
            ->select('COUNT(u.id) as count','u.ip')
            ->leftJoin('u.dates','ud')
            ->where('ud.connectionToUrl > CURRENT_DATE()')
            ->groupBy('u.ip')
        ;
        $query = $db->getQuery();
        return $query->execute();
    }

    public function findDevices()
    {
        $db = $this->createQueryBuilder('u')
            ->select('COUNT(u.type) as count','u.type')
            ->orderBy('count', 'DESC')
            ->groupBy('u.type')
        ;
        $query = $db->getQuery();
        return $query->execute();
    }

    public function findOs()
    {
        $db = $this->createQueryBuilder('u')
            ->select('COUNT(u.os) as count','u.os')
            ->orderBy('count', 'DESC')
            ->groupBy('u.os')
        ;
        $query = $db->getQuery();
        return $query->execute();
    }

    public function findBrowsers()
    {
        $db = $this->createQueryBuilder('u')
            ->select('COUNT(u.browser) as count','u.browser')
            ->orderBy('count', 'DESC')
            ->groupBy('u.browser')
        ;
        $query = $db->getQuery();
        return $query->execute();
    }

    public function findCountForTheDate($oneDate)
    {
        $db = $this->createQueryBuilder('u')
            ->select('COUNT(u.id) as count','DATE_FORMAT(ud.connectionToUrl, \'%d-%m-%Y\') as date')
            ->where('ud.connectionToUrl > :start')
            ->setParameter('start',$oneDate)
            ->leftJoin('u.dates','ud')
            ->groupBy('date')


        ;
        $query = $db->getQuery();
        return $query->execute();
    }


}
