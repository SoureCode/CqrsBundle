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

/**
 * @author Jason Schilling <jason@sourecode.dev>
 *
 * @extends ArrayCollection<array-key, TestEnvelope>
 */
class TestEnvelopeCollection extends ArrayCollection
{
    /**
     * @param class-string $messageClass
     *
     * @return $this
     */
    public function assertContains(string $messageClass, ?int $amount = null, string $message = ''): static
    {
        $messages = $this->messages($messageClass);

        TestCase::assertThat($messages, new Constraint\LogicalNot(new Constraint\IsEmpty()), $message);

        if (null !== $amount) {
            TestCase::assertThat($messages, new Constraint\Count($amount), $message);
        }

        return $this;
    }

    /**
     * @param ?class-string $messageClass
     *
     * @return object[]
     */
    public function messages(?string $messageClass = null): array
    {
        $messages = array_map(static function (TestEnvelope $envelope) {
            return $envelope->getEnvelope()->getMessage();
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

    public function assertCount(int $amount, string $message = ''): static
    {
        TestCase::assertThat($this, new Constraint\Count($amount), $message);

        return $this;
    }

    public function assertEmpty(string $message = ''): static
    {
        TestCase::assertThat($this, new Constraint\IsEmpty(), $message);

        return $this;
    }

    /**
     * @param class-string $messageClass
     *
     * @return $this
     */
    public function assertNotContains(string $messageClass, string $message = ''): static
    {
        $messages = $this->messages($messageClass);

        TestCase::assertThat($messages, new Constraint\IsEmpty(), $message);

        return $this;
    }

    public function assertNotEmpty(string $message = ''): static
    {
        TestCase::assertThat($this, new Constraint\LogicalNot(new Constraint\IsEmpty()), $message);

        return $this;
    }
}
