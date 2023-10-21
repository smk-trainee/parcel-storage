<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\FullName;
use App\Entity\Parcel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Parcel>
 *
 * @method Parcel|null find($id, $lockMode = null, $lockVersion = null)
 * @method Parcel|null findOneBy(array $criteria, array $orderBy = null)
 * @method Parcel[]    findAll()
 * @method Parcel[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ParcelRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Parcel::class);
    }

    /**
     * @return Parcel[] Returns an array of Parcel objects
     */
    public function findBySenderPhone(string $phone): array
    {
        return $this->createQueryBuilder('p')
            ->where('p.sender.phone = :phone')
            ->setParameter('phone', $phone)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findByRecipientFullName(string $fullName): array
    {
        return $this->createQueryBuilder('p')
            ->where("CONCAT(p.recipient.fullName.firstName, ' ', p.recipient.fullName.lastName, ' ', p.recipient.fullName.middleName) = :fullName")
            ->setParameter('fullName', $fullName)
            ->getQuery()
            ->getResult()
        ;
    }

    public function create(Parcel $parcel): void
    {
        $this->_em->persist($parcel);
        $this->_em->flush();
    }

    public function destroy(Parcel $parcel): void
    {
        $this->_em->remove($parcel);
        $this->_em->flush();
    }
}
