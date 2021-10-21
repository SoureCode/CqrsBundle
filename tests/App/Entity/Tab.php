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

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Uid\Ulid;

/**
 * @author Jason Schilling <jason@sourecode.dev>
 */
class Tab
{
    protected ?int $given = null;

    protected Ulid $id;

    protected ?bool $open = null;

    /**
     * @var Collection<int, Order>
     */
    protected Collection $orders;

    protected ?int $paid = null;

    protected ?Table $table = null;

    protected ?int $total = null;

    public function __construct(Ulid $id)
    {
        $this->id = $id;
        $this->orders = new ArrayCollection();
    }

    public function addOrder(Order $order): self
    {
        if (!$this->orders->contains($order)) {
            $this->orders[] = $order;

            $order->setTab($this);
        }

        return $this;
    }

    public function getGiven(): ?int
    {
        return $this->given;
    }

    public function setGiven(?int $given): void
    {
        $this->given = $given;
    }

    public function getId(): Ulid
    {
        return $this->id;
    }

    /**
     * @return Collection<int, Order>
     */
    public function getOrders(): Collection
    {
        return $this->orders;
    }

    public function getPaid(): ?int
    {
        return $this->paid;
    }

    public function setPaid(?int $paid): void
    {
        $this->paid = $paid;
    }

    public function getTable(): ?Table
    {
        return $this->table;
    }

    public function setTable(?Table $table): void
    {
        $this->table = $table;
    }

    public function getTotal(): ?int
    {
        return $this->total;
    }

    public function setTotal(?int $total): void
    {
        $this->total = $total;
    }

    public function isOpen(): ?bool
    {
        return $this->open;
    }

    public function removeOrder(Order $order): self
    {
        if ($this->orders->removeElement($order)) {
            // set the owning side to null (unless already changed)
            if ($order->getTab() === $this) {
                $order->setTab(null);
            }
        }

        return $this;
    }

    public function setOpen(?bool $open): void
    {
        $this->open = $open;
    }
}
