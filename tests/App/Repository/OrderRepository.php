<?php
/*
 * This file is part of the SoureCode package.
 *
 * (c) Jason Schilling <jason@sourecode.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SoureCode\Bundle\Cqrs\Tests\App\Repository;

use Doctrine\Persistence\ManagerRegistry;
use SoureCode\Bundle\Cqrs\Tests\App\Entity\Order;
use SoureCode\Bundle\Cqrs\Tests\App\Entity\Tab;

/**
 * @template-extends AbstractRepository<Order>
 */
class OrderRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Order::class);
    }

    public function hasOpenOrders(Tab $tab): bool
    {
        $queryBuilder = $this->createQueryBuilder('o');
        $queryBuilder
            ->select('count(o.id)')
            ->join('o.tab', 'tab')
            ->where($queryBuilder->expr()->eq('tab.id', ':id'))
            ->andWhere($queryBuilder->expr()->eq('o.done', ':open'))
            ->setParameter('open', false, 'boolean')
            ->setParameter('id', $tab->getId(), 'ulid');

        $query = $queryBuilder->getQuery();

        return ((int) $query->getSingleScalarResult()) > 0;
    }
}
