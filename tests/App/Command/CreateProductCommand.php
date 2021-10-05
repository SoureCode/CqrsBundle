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
class CreateProductCommand implements CommandInterface
{
    #[Assert\NotBlank]
    #[Assert\Ulid]
    private Ulid $id;

    #[Assert\NotBlank]
    private string $name;

    public function __construct(Ulid $id, string $name)
    {
        $this->id = $id;
        $this->name = $name;
    }

    public function getId(): Ulid
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
