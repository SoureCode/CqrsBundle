<?php
/*
 * This file is part of the SoureCode package.
 *
 * (c) Jason Schilling <jason@sourecode.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SoureCode\Bundle\Cqrs\Test;

use SoureCode\Component\Cqrs\CommandBusInterface;
use SoureCode\Component\Cqrs\EventBusInterface;
use SoureCode\Component\Cqrs\QueryBusInterface;

/**
 * @author Jason Schilling <jason@sourecode.dev>
 */
trait CqrsTestTrait
{
    use MessengerAssertionsTrait;

    protected static ?CommandBusInterface $commandBus = null;

    protected static ?EventBusInterface $eventBus = null;

    protected static ?QueryBusInterface $queryBus = null;

    public static function getTestEnvelopeCollection(string $busName): TestEnvelopeCollection
    {
        $testBus = self::getTestBus($busName);

        return $testBus->getDispatchedEnvelopes();
    }

    public static function setUpCqrs(): void
    {
        $container = self::getContainer();

        static::$eventBus = $container->get(EventBusInterface::class);
        static::$commandBus = $container->get(CommandBusInterface::class);
        static::$queryBus = $container->get(QueryBusInterface::class);
    }

    public static function tearDownCqrs(): void
    {
        static::$eventBus = null;
        static::$commandBus = null;
        static::$queryBus = null;
    }
}
