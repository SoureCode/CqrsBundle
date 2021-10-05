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

use Symfony\Bundle\FrameworkBundle\Test\MailerAssertionsTrait;
use PHPUnit\Framework\Constraint;

/**
 * @author Jason Schilling <jason@sourecode.dev>
 */
trait MessengerAssertionsTrait
{
    use MailerAssertionsTrait;

    public static function assertMessageCount(int $count, string $bus, string $message = ''): void
    {
        self::assertThat(self::getMessengerDispatchedMessages($bus), new Constraint\Count($count), $message);
    }

    public static function assertMessageType(string $type, string $bus, string $message = ''): void
    {
        $dispatchedMessages = self::getMessengerDispatchedMessagesOfType($bus, $type);

        self::assertThat($dispatchedMessages, new Constraint\LogicalNot(new Constraint\IsEmpty()), $message);
    }

    private static function getMessengerDispatchedMessagesOfType(string $bus, string $type)
    {
        $dispatchedMessages = self::getMessengerDispatchedMessages($bus);
        $items = [];

        foreach ($dispatchedMessages as $dispatchedMessage) {
            if(is_a($dispatchedMessage['message'], $type)) {
                $items[] = $dispatchedMessage;
            }
        }

        return $items;
    }

    private static function getMessengerDispatchedMessages(string $bus)
    {
        $container = static::getContainer();
        $busName = $bus.'.bus';

        if (!$container->has($busName)) {
            static::fail('Missing bus "'.$busName.'".');
        }

        return $container->get($busName)->getDispatchedMessages();
    }
}
