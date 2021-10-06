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
use SoureCode\Bundle\Cqrs\Tests\App\Entity\Table;

/**
 * @template-extends AbstractRepository<Table>
 */
class TableRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Table::class);
    }
}
