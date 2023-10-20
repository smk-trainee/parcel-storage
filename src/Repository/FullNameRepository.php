<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\FullName;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<FullName>
 *
 * @method FullName|null find($id, $lockMode = null, $lockVersion = null)
 * @method FullName|null findOneBy(array $criteria, array $orderBy = null)
 * @method FullName[]    findAll()
 * @method FullName[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FullNameRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FullName::class);
    }

    public function save(FullName $entity, bool $flush = false): void
    {
        $this->_em->persist($entity);

        if ($flush) {
            $this->_em->flush();
        }
    }
}
