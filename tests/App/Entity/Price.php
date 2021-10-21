<?php
/*
 * This file is part of the SoureCode package.
 *
 * (c) Jason Schilling <jason@sourecode.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SoureCode\Bundle\Cqrs\Tests\App\Entity;

use DateTimeImmutable;
use Symfony\Component\Uid\Ulid;

/**
 * @author Jason Schilling <jason@sourecode.dev>
 */
class Price
{
    protected ?DateTimeImmutable $effectiveAt = null;

    protected Ulid $id;

    protected ?Product $product = null;

    protected ?int $value = null;

    public function __construct(Ulid $id)
    {
        $this->id = $id;
    }

    public function getEffectiveAt(): ?DateTimeImmutable
    {
        return $this->effectiveAt;
    }

    public function setEffectiveAt(?DateTimeImmutable $effectiveAt): void
    {
        $this->effectiveAt = $effectiveAt;
    }

    public function getId(): Ulid
    {
        return $this->id;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): void
    {
        $this->product = $product;
    }

    public function getValue(): ?int
    {
        return $this->value;
    }

    public function setValue(?int $value): void
    {
        $this->value = $value;
    }
}
