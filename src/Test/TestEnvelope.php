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

use PHPUnit\Framework\Constraint;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Messenger\Envelope;

/**
 * @author Jason Schilling <jason@sourecode.dev>
 */
class TestEnvelope
{
    private Envelope $envelope;

    public function __construct(Envelope $envelope)
    {
        $this->envelope = $envelope;
    }

    public function assertHasStamp(string $stampClass, string $message = ''): self
    {
        $stamps = $this->envelope->all($stampClass);

        TestCase::assertThat($stamps, new Constraint\LogicalNot(new Constraint\IsEmpty()), $message);
    }

    public function assertNotHasStamp(string $stampClass, string $message = ''): self
    {
        $stamps = $this->envelope->all($stampClass);

        TestCase::assertThat($stamps, new Constraint\IsEmpty(), $message);
    }

    public function getEnvelope(): Envelope
    {
        return $this->envelope;
    }
}
