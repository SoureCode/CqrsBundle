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

use SoureCode\Component\Cqrs\CommandBusInterface;
use SoureCode\Component\Cqrs\EventBusInterface;
use SoureCode\Component\Cqrs\QueryBusInterface;

/**
 * @author Jason Schilling <jason@sourecode.dev>
 */
class DependencyInjectionTest extends AbstractCqrsTestCase
{
    public function testServicesRegistered(): void
    {
        // Arrange
        $kernel = self::bootKernel();
        $container = $kernel->getContainer();

        // Assert
        self::assertTrue($container->has(EventBusInterface::class));
        self::assertTrue($container->has(CommandBusInterface::class));
        self::assertTrue($container->has(QueryBusInterface::class));
    }
}
