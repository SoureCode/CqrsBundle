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
use SoureCode\Bundle\Cqrs\Tests\App\Repository\TableRepository;
use SoureCode\Bundle\Cqrs\Tests\App\Repository\TabRepository;
use SoureCode\Component\Cqrs\CommandHandlerInterface;

/**
 * @author Jason Schilling <jason@sourecode.dev>
 */
class OpenTabCommandHandler implements CommandHandlerInterface
{
    private TabRepository $tabRepository;

    private TableRepository $tableRepository;

    public function __construct(TabRepository $tabRepository, TableRepository $tableRepository)
    {
        $this->tabRepository = $tabRepository;
        $this->tableRepository = $tableRepository;
    }

    public function __invoke(OpenTabCommand $command)
    {
        $id = $command->getTabId();
        $tableId = $command->getTableId();

        $table = $this->tableRepository->get($tableId);

        if ($this->tabRepository->hasOpenTab($table)) {
            throw new Exception('Table is already in use.');
        }

        $tab = new Tab($id);
        $tab->setOpen(true);
        $tab->setTable($table);

        $this->tabRepository->persist($tab);

        return yield new TabOpenedEvent($id);
    }
}
