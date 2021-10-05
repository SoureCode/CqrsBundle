<?php
/*
 * This file is part of the SoureCode package.
 *
 * (c) Jason Schilling <jason@sourecode.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SoureCode\Bundle\Cqrs\Tests\App\Command;

use SoureCode\Component\Cqrs\CommandInterface;
use Symfony\Component\Uid\Ulid;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @author Jason Schilling <jason@sourecode.dev>
 */
class CloseTabCommand implements CommandInterface
{
    #[Assert\NotBlank]
    #[Assert\Ulid]
    private Ulid $id;

    #[Assert\NotBlank]
    private int $given;

    #[Assert\NotBlank]
    private int $paid;

    public function __construct(Ulid $id, int $given, int $paid)
    {
        $this->id = $id;
        $this->given = $given;
        $this->paid = $paid;
    }

    public function getGiven(): int
    {
        return $this->given;
    }

    public function getId(): Ulid
    {
        return $this->id;
    }

    public function getPaid(): int
    {
        return $this->paid;
    }
}
