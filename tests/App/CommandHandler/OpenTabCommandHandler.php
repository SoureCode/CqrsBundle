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

use Exception;
use SoureCode\Bundle\Cqrs\Tests\App\Command\OpenTabCommand;
use SoureCode\Bundle\Cqrs\Tests\App\Entity\Tab;
use SoureCode\Bundle\Cqrs\Tests\App\Event\TabOpenedEvent;
use SoureCode\Bundle\Cqrs\Tests\App\Storage;
use SoureCode\Component\Cqrs\CommandHandlerInterface;

/**
 * @author Jason Schilling <jason@sourecode.dev>
 */
class OpenTabCommandHandler implements CommandHandlerInterface
{
    private Storage $storage;

    public function __construct(Storage $storage)
    {
        $this->storage = $storage;
    }

    public function __invoke(OpenTabCommand $command)
    {
        $id = $command->getTabId();
        $tableId = $command->getTableId();

        $table = $this->storage->get((string) $tableId);

        if ($this->storage->hasOpenTab($table)) {
            throw new Exception('Table is already in use.');
        }

        $tab = new Tab($id);
        $tab->setOpen(true);
        $tab->setTable($table);

        $this->storage->set((string) $id, $tab);

        return yield new TabOpenedEvent($id);
    }
}
