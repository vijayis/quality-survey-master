<?php

namespace App\Repository;

use App\Entity\DsgQcquestions;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DsgQcquestions|null find($id, $lockMode = null, $lockVersion = null)
 * @method DsgQcquestions|null findOneBy(array $criteria, array $orderBy = null)
 * @method DsgQcquestions[]    findAll()
 * @method DsgQcquestions[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DsgQcquestionsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DsgQcquestions::class);
    }

    /**
     * @return DsgQcquestions[] Returns an array of DsgQcquestions objects
     */
    public function findByCategory($category)
    {
        return $this->createQueryBuilder('d')
            ->where("d.category IN(:cats)")
            ->setParameter('cats', array('All', $category))
            ->orderBy('d.listorder', 'ASC')
            ->getQuery()
            ->getArrayResult()
            ;
    }


}
