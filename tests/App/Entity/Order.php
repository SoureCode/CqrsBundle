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
use Doctrine\ORM\Mapping as ORM;
use SoureCode\Bundle\Cqrs\Tests\App\Repository\OrderRepository;
use Symfony\Component\Uid\Ulid;

/**
 * @author Jason Schilling <jason@sourecode.dev>
 */
#[ORM\Table(name: '`order`')]
#[ORM\Entity(repositoryClass: OrderRepository::class)]
class Order
{
    #[ORM\Column(nullable: false)]
    protected ?DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: false)]
    protected ?bool $done = null;

    #[ORM\Id]
    #[ORM\Column(type: 'ulid')]
    protected Ulid $id;

    #[ORM\ManyToOne(targetEntity: Product::class, inversedBy: 'orders')]
    protected ?Product $product = null;

    #[ORM\ManyToOne(targetEntity: Tab::class, inversedBy: 'orders')]
    protected ?Tab $tab = null;

    public function __construct(Ulid $id)
    {
        $this->id = $id;
    }

    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?DateTimeImmutable $createdAt): void
    {
        $this->createdAt = $createdAt;
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

    public function getTab(): ?Tab
    {
        return $this->tab;
    }

    public function setTab(?Tab $tab): void
    {
        $this->tab = $tab;
    }

    public function isDone(): ?bool
    {
        return $this->done;
    }

    public function setDone(?bool $done): void
    {
        $this->done = $done;
    }
}
