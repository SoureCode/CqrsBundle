<?php
/*
 * This file is part of the SoureCode package.
 *
 * (c) Jason Schilling <jason@sourecode.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SoureCode\Bundle\Cqrs\Tests\App\Event;

use SoureCode\Component\Cqrs\EventInterface;
use Symfony\Component\Uid\Ulid;

/**
 * @author Jason Schilling <jason@sourecode.dev>
 */
class UserRegisteredEvent implements EventInterface
{

    private Ulid $tokenId;
    private Ulid $userId;

    public function __construct(Ulid $userId, Ulid $tokenId)
    {
        $this->userId = $userId;
        $this->tokenId = $tokenId;
    }

    public function getTokenId(): Ulid
    {
        return $this->tokenId;
    }

    public function getUserId(): Ulid
    {
        return $this->userId;
    }

}
