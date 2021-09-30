<?php
/*
 * This file is part of the SoureCode package.
 *
 * (c) Jason Schilling <jason@sourecode.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SoureCode\Bundle\Cqrs\Tests\App\Query;

use SoureCode\Component\Cqrs\QueryInterface;
use Symfony\Component\Uid\Ulid;

/**
 * @author Jason Schilling <jason@sourecode.dev>
 */
class GetUserQuery implements QueryInterface
{

    private Ulid $id;

    public function __construct(Ulid $id)
    {
        $this->id = $id;
    }

    public function getId(): Ulid
    {
        return $this->id;
    }

}
