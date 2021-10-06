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

use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\TraceableMessageBus;

/**
 * @author Jason Schilling <jason@sourecode.dev>
 */
class TestBus
{
    private TraceableMessageBus $bus;

    public function __construct(TraceableMessageBus $bus)
    {
        $this->bus = $bus;
    }

    public function getDispatchedEnvelopes(): TestEnvelopeCollection
    {
        $messages = $this->bus->getDispatchedMessages();
        $envelopes = [];

        foreach ($messages as $message) {
            $envelopes[] = Envelope::wrap($message['message'], $message['stamps']);
        }

        return new TestEnvelopeCollection($envelopes);
    }
}
