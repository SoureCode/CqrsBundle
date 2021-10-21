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
use SoureCode\Bundle\Cqrs\Tests\App\Entity\Tab;
use SoureCode\Bundle\Cqrs\Tests\App\Storage;
use Symfony\Component\Uid\Ulid;

/**
 * @author Jason Schilling <jason@sourecode.dev>
 */
class IntegrationTest extends AbstractCqrsTestCase
{
    use MessengerAssertionsTrait;

    public function testBusinessCase(): void
    {
        self::$commandBus->dispatch(new CreateProductCommand($productId = new Ulid(), 'Pizza'));
        self::$commandBus->dispatch(new CreateTableCommand($tableId = new Ulid()));
        self::$commandBus->dispatch(new OpenTabCommand($tabId = new Ulid(), $tableId));
        self::$commandBus->dispatch(new SetProductPriceCommand(new Ulid(), $productId, $firstPrice = 499));
        self::$commandBus->dispatch(new PlaceOrderCommand($order1 = new Ulid(), $tabId, $productId));

        // Need to sleep a second to ensure the effective at timestamp in price is different
        sleep(1);

        self::$commandBus->dispatch(new SetProductPriceCommand(new Ulid(), $productId, $secondPrice = 299));
        self::$commandBus->dispatch(new PlaceOrderCommand($order2 = new Ulid(), $tabId, $productId));

        self::$commandBus->dispatch(new ServeOrderCommand($order1));
        self::$commandBus->dispatch(new ServeOrderCommand($order2));

        self::$commandBus->dispatch(new CloseTabCommand($tabId, 1000, 800));

        // Assert
        $storage = self::getContainer()->get(Storage::class);

        self::assertCount(7, $storage);

        /**
         * @var Tab $tab
         */
        $tab = $storage->get((string) $tabId);

        self::assertSame($firstPrice + $secondPrice, $tab->getTotal());

        self::getTestEnvelopeCollection('command.bus')->assertCount(10);
        self::getTestEnvelopeCollection('event.bus')->assertCount(10);
    }
}
