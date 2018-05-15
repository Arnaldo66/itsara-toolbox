<?php

namespace App\Repository;

use App\Entity\Command;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Command|null find($id, $lockMode = null, $lockVersion = null)
 * @method Command|null findOneBy(array $criteria, array $orderBy = null)
 * @method Command[]    findAll()
 * @method Command[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommandRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Command::class);
    }


    public function getUniqueCommandNumber()
    {
        return $this->createQueryBuilder('c')
            ->select('distinct c.number, c.name, c.firstName, c.street, c.streetComplement, c.postalCode, c.province, c.city, c.country, c.phone, c.email, c.typePayment, c.dateCommand, c.shippingCoastHT, c.shippingCoastTTC')
            ->andWhere('c.print = :print')
            ->setParameter('print', true)
            ->andWhere('c.isDiscount = :discount')
            ->setParameter('discount', false)
            ->orderBy('c.number', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }
}
