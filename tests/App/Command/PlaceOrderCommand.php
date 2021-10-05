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
class PlaceOrderCommand implements CommandInterface
{
    #[Assert\NotBlank]
    #[Assert\Ulid]
    private Ulid $id;

    #[Assert\NotBlank]
    #[Assert\Ulid]
    private Ulid $tabId;

    #[Assert\NotBlank]
    #[Assert\Ulid]
    private Ulid $productId;

    public function __construct(Ulid $id, Ulid $tabId, Ulid $productId)
    {
        $this->tabId = $tabId;
        $this->productId = $productId;
        $this->id = $id;
    }

    public function getId(): Ulid
    {
        return $this->id;
    }

    public function getTabId(): Ulid
    {
        return $this->tabId;
    }

    public function getProductId(): Ulid
    {
        return $this->productId;
    }
}
