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

use DateTimeImmutable;
use Exception;
use SoureCode\Bundle\Cqrs\Tests\App\Command\PlaceOrderCommand;
use SoureCode\Bundle\Cqrs\Tests\App\Entity\Order;
use SoureCode\Bundle\Cqrs\Tests\App\Event\OrderPlacedEvent;
use SoureCode\Bundle\Cqrs\Tests\App\Storage;
use SoureCode\Component\Cqrs\CommandHandlerInterface;

/**
 * @author Jason Schilling <jason@sourecode.dev>
 */
class PlaceOrderCommandHandler implements CommandHandlerInterface
{
    private Storage $storage;

    public function __construct(Storage $storage)
    {
        $this->storage = $storage;
    }

    public function __invoke(PlaceOrderCommand $command)
    {
        $id = $command->getId();
        $tabId = $command->getTabId();
        $productId = $command->getProductId();

        $tab = $this->storage->get((string) $tabId);

        if (!$tab->isOpen()) {
            throw new Exception('Tab is not open.');
        }

        $product = $this->storage->get((string) $productId);

        $order = new Order($id);
        $order->setProduct($product);
        $order->setCreatedAt(new DateTimeImmutable());
        $order->setDone(false);

        $tab->addOrder($order);

        $this->storage->set((string) $id, $order);

        return yield new OrderPlacedEvent($tabId);
    }
}
