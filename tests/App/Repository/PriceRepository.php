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

use Doctrine\DBAL\Types\Types;
use Doctrine\Persistence\ManagerRegistry;
use SoureCode\Bundle\Cqrs\Tests\App\Entity\Order;
use SoureCode\Bundle\Cqrs\Tests\App\Entity\Price;
use SoureCode\Bundle\Cqrs\Tests\App\Entity\Product;

/**
 * @template-extends AbstractRepository<Price>
 */
class PriceRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Price::class);
    }

    public function getPrice(Order $order): ?Price
    {
        $queryBuilder = $this->createQueryBuilder('price');
        $queryBuilder
            ->join('price.product', 'product')
            ->where($queryBuilder->expr()->eq('product.id', ':id'))
            ->andWhere($queryBuilder->expr()->gte('price.effectiveAt', ':date'))
            ->orderBy('price.effectiveAt', 'ASC')
            ->setMaxResults(1)
            ->setParameter('date', $order->getCreatedAt(), Types::DATETIME_IMMUTABLE)
            ->setParameter('id', $order->getProduct()->getId(), 'ulid');

        $query = $queryBuilder->getQuery();

        return $query->getOneOrNullResult();
    }
}
