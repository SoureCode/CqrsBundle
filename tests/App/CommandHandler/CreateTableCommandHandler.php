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
use SoureCode\Bundle\Cqrs\Tests\App\Storage;
use SoureCode\Component\Cqrs\CommandHandlerInterface;

/**
 * @author Jason Schilling <jason@sourecode.dev>
 */
class CreateTableCommandHandler implements CommandHandlerInterface
{
    private Storage $storage;

    public function __construct(Storage $storage)
    {
        $this->storage = $storage;
    }

    public function __invoke(CreateTableCommand $command)
    {
        $id = $command->getId();

        $table = new Table($id);

        $this->storage->set((string) $id, $table);

        return yield new TableCreatedEvent($id);
    }
}
