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
use SoureCode\Bundle\Cqrs\Tests\App\Command\ServeOrderCommand;
use SoureCode\Bundle\Cqrs\Tests\App\Event\OrderServedEvent;
use SoureCode\Bundle\Cqrs\Tests\App\Storage;
use SoureCode\Component\Cqrs\CommandHandlerInterface;

/**
 * @author Jason Schilling <jason@sourecode.dev>
 */
class ServeOrderCommandHandler implements CommandHandlerInterface
{
    private Storage $storage;

    public function __construct(Storage $storage)
    {
        $this->storage = $storage;
    }

    public function __invoke(ServeOrderCommand $command)
    {
        $id = $command->getId();
        $order = $this->storage->get((string) $id);

        if ($order->isDone()) {
            throw new Exception('Order was already served.');
        }

        $order->setDone(true);

        $this->storage->set((string) $id, $order);

        return yield new OrderServedEvent($id);
    }
}
