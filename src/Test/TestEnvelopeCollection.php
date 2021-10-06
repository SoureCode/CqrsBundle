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

use function array_filter;
use function array_map;
use function array_values;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\Constraint;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Messenger\Envelope;

/**
 * @author Jason Schilling <jason@sourecode.dev>
 *
 * @extends  ArrayCollection<array-key, Envelope>
 */
class TestEnvelopeCollection extends ArrayCollection
{
    /**
     * @param ?class-string $messageClass
     *
     * @return object[]
     */
    public function messages(?string $messageClass = null): array
    {
        $messages = array_map(static function (Envelope $envelope) {
            return $envelope->getMessage();
        }, $this->toArray());

        if (!$messageClass) {
            return $messages;
        }

        return array_values(
            array_filter($messages, static function (object $message) use ($messageClass) {
                return $messageClass === \get_class($message);
            })
        );
    }

    public function assertCount(int $amount, string $message = '')
    {
        TestCase::assertThat($this, new Constraint\Count($amount), $message);
    }

    public function assertEmpty(string $message = '')
    {
        TestCase::assertThat($this, new Constraint\IsEmpty(), $message);
    }

    public function assertNotEmpty(string $message = '')
    {
        TestCase::assertThat($this, new Constraint\LogicalNot(new Constraint\IsEmpty()), $message);
    }

    public function assertContains(string $messageClass, ?int $amount = null, string $message = '')
    {
        $messages = $this->messages($messageClass);

        TestCase::assertThat($messages, new Constraint\LogicalNot(new Constraint\IsEmpty()), $message);

        if (null !== $amount) {
            TestCase::assertThat($messages, new Constraint\Count($amount), $message);
        }
    }

    public function assertNotContains(string $messageClass, string $message = '')
    {
        $messages = $this->messages($messageClass);

        TestCase::assertThat($messages, new Constraint\IsEmpty(), $message);
    }
}
