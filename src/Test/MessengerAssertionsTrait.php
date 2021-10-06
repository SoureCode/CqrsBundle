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

/**
 * @author Jason Schilling <jason@sourecode.dev>
 */
trait MessengerAssertionsTrait
{
    private static function getTestBus(string $busName): TestBus
    {
        $container = static::getContainer();

        if (!$container->has($busName)) {
            static::fail('Missing bus "'.$busName.'".');
        }

        $bus = $container->get($busName);

        return new TestBus($bus);
    }
}
