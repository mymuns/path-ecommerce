<?php

namespace App\Repository;

use App\Entity\CustomerOrder;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityRepository;

/**
 * @extends ServiceEntityRepository<CustomerOrder>
 *
 * @method CustomerOrder|null find($id, $lockMode = null, $lockVersion = null)
 * @method CustomerOrder|null findOneBy(array $criteria, array $orderBy = null)
 * @method CustomerOrder[]    findAll()
 * @method CustomerOrder[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CustomerOrderRepository extends EntityRepository
{

    /**
     * @param CustomerOrder $entity
     * @param bool $flush
     * @return CustomerOrder
     */
    public function add(CustomerOrder $entity, bool $flush = false): CustomerOrder
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
        return $entity;
    }

    /**
     * @param CustomerOrder $entity
     * @param bool $flush
     */
    public function remove(CustomerOrder $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @param User $user
     * @return int|mixed|string
     */
    public function getMyOrders(User $user)
    {
        return $this->createQueryBuilder('o')
            ->where('o.user = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param User $user
     * @param string $orderCode
     * @return int|mixed|string|null
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function showOrderByOrderCode(User $user, string $orderCode)
    {
        return $this->createQueryBuilder('o')
            ->where('o.user = :user and o.orderCode = : orderCode')
            ->setParameter('user', $user)
            ->setParameter('orderCode', $orderCode)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
