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
use SoureCode\Bundle\Common\Repository\AbstractRepository;
use SoureCode\Bundle\Cqrs\Tests\App\Entity\Tab;
use SoureCode\Bundle\Cqrs\Tests\App\Entity\Table;

/**
 * @template-extends AbstractRepository<Tab>
 */
class TabRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tab::class);
    }

    public function hasOpenTab(Table $table): bool
    {
        $queryBuilder = $this->createQueryBuilder('tab');
        $queryBuilder
            ->select('count(tab.id)')
            ->join('tab.table', 'table')
            ->where($queryBuilder->expr()->eq('table.id', ':id'))
            ->andWhere($queryBuilder->expr()->eq('tab.open', ':open'))
            ->setParameter('open', true, 'boolean')
            ->setParameter('id', $table->getId(), 'ulid');

        $query = $queryBuilder->getQuery();

        return ((int) $query->getSingleScalarResult()) > 0;
    }
}
