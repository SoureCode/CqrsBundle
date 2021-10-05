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
class SetProductPriceCommand implements CommandInterface
{
    #[Assert\NotBlank]
    #[Assert\Ulid]
    private Ulid $id;

    #[Assert\NotBlank]
    private int $price;

    #[Assert\NotBlank]
    #[Assert\Ulid]
    private Ulid $productId;

    public function __construct(Ulid $id, Ulid $productId, int $price)
    {
        $this->id = $id;
        $this->productId = $productId;
        $this->price = $price;
    }

    public function getId(): Ulid
    {
        return $this->id;
    }

    public function getPrice(): int
    {
        return $this->price;
    }

    public function getProductId(): Ulid
    {
        return $this->productId;
    }
}
