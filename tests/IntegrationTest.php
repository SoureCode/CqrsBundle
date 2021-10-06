<?php
/*
 * This file is part of the SoureCode package.
 *
 * (c) Jason Schilling <jason@sourecode.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SoureCode\Bundle\Cqrs\Tests;

use SoureCode\Bundle\Cqrs\Test\MessengerAssertionsTrait;
use SoureCode\Bundle\Cqrs\Tests\App\Command\CloseTabCommand;
use SoureCode\Bundle\Cqrs\Tests\App\Command\CreateProductCommand;
use SoureCode\Bundle\Cqrs\Tests\App\Command\CreateTableCommand;
use SoureCode\Bundle\Cqrs\Tests\App\Command\OpenTabCommand;
use SoureCode\Bundle\Cqrs\Tests\App\Command\PlaceOrderCommand;
use SoureCode\Bundle\Cqrs\Tests\App\Command\ServeOrderCommand;
use SoureCode\Bundle\Cqrs\Tests\App\Command\SetProductPriceCommand;
use SoureCode\Bundle\Cqrs\Tests\App\Entity\Order;
use SoureCode\Bundle\Cqrs\Tests\App\Entity\Price;
use SoureCode\Bundle\Cqrs\Tests\App\Entity\Product;
use SoureCode\Bundle\Cqrs\Tests\App\Entity\Tab;
use SoureCode\Bundle\Cqrs\Tests\App\Entity\Table;
use Symfony\Component\Uid\Ulid;

/**
 * @author Jason Schilling <jason@sourecode.dev>
 */
class IntegrationTest extends AbstractCqrsIntegrationTestCase
{
    use MessengerAssertionsTrait;

    public function testBusinessCase(): void
    {
        $this->commandBus->dispatch(new CreateProductCommand($productId = new Ulid(), 'Pizza'));
        $this->commandBus->dispatch(new CreateTableCommand($tableId = new Ulid()));
        $this->commandBus->dispatch(new OpenTabCommand($tabId = new Ulid(), $tableId));
        $this->commandBus->dispatch(new SetProductPriceCommand(new Ulid(), $productId, 499));
        $this->commandBus->dispatch(new PlaceOrderCommand($order1 = new Ulid(), $tabId, $productId));

        // Need to sleep a second to ensure the effective at timestamp in price is different
        sleep(1);

        $this->commandBus->dispatch(new SetProductPriceCommand(new Ulid(), $productId, 299));
        $this->commandBus->dispatch(new PlaceOrderCommand($order2 = new Ulid(), $tabId, $productId));

        $this->commandBus->dispatch(new ServeOrderCommand($order1));
        $this->commandBus->dispatch(new ServeOrderCommand($order2));

        $this->commandBus->dispatch(new CloseTabCommand($tabId, 1000, 800));

        // Assert
        $products = $this->entityManager->getRepository(Product::class)->findAll();
        $tables = $this->entityManager->getRepository(Table::class)->findAll();
        $tabs = $this->entityManager->getRepository(Tab::class)->findAll();
        $orders = $this->entityManager->getRepository(Order::class)->findAll();
        $prices = $this->entityManager->getRepository(Price::class)->findAll();

        self::assertCount(1, $products);
        self::assertCount(1, $tables);
        self::assertCount(1, $tabs);
        self::assertCount(2, $orders);
        self::assertCount(2, $prices);

        $commandBus = self::getTestBus('command.bus');
        $eventBus = self::getTestBus('event.bus');

        $commandBus->getDispatchedEnvelopes()->assertCount(10);
        $eventBus->getDispatchedEnvelopes()->assertCount(10);
    }
}
