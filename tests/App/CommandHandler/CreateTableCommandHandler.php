<?php
/*
 * This file is part of the SoureCode package.
 *
 * (c) Jason Schilling <jason@sourecode.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SoureCode\Bundle\Cqrs\Tests\App\CommandHandler;

use SoureCode\Bundle\Cqrs\Tests\App\Command\CreateTableCommand;
use SoureCode\Bundle\Cqrs\Tests\App\Entity\Table;
use SoureCode\Bundle\Cqrs\Tests\App\Event\TableCreatedEvent;
use SoureCode\Bundle\Cqrs\Tests\App\Repository\TableRepository;
use SoureCode\Component\Cqrs\CommandHandlerInterface;

/**
 * @author Jason Schilling <jason@sourecode.dev>
 */
class CreateTableCommandHandler implements CommandHandlerInterface
{
    private TableRepository $repository;

    public function __construct(TableRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(CreateTableCommand $command)
    {
        $id = $command->getId();

        $table = new Table($id);

        $this->repository->persist($table);

        return yield new TableCreatedEvent($id);
    }
}
