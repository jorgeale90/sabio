<?php

namespace App\Repository;

use App\Entity\Especialidad;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Especialidad|null find($id, $lockMode = null, $lockVersion = null)
 * @method Especialidad|null findOneBy(array $criteria, array $orderBy = null)
 * @method Especialidad[]    findAll()
 * @method Especialidad[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EspecialidadRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Especialidad::class);
    }

    public function findByCargo($cargo_id)
    {
        $em = $this->getEntityManager();
        $consulta = $em->createQuery('SELECT m FROM App:Especialidad m WHERE m.cargo = :cargo_id');
        $consulta->setParameter('cargo_id', $cargo_id);
        return $consulta->getArrayResult();
    }

    // /**
    //  * @return Especialidad[] Returns an array of Especialidad objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Especialidad
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
