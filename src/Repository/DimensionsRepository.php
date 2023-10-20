<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Dimensions;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Dimensions>
 *
 * @method Dimensions|null find($id, $lockMode = null, $lockVersion = null)
 * @method Dimensions|null findOneBy(array $criteria, array $orderBy = null)
 * @method Dimensions[]    findAll()
 * @method Dimensions[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DimensionsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Dimensions::class);
    }

    public function save(Dimensions $entity, bool $flush = false): void
    {
        $this->_em->persist($entity);

        if ($flush) {
            $this->_em->flush();
        }
    }

}
