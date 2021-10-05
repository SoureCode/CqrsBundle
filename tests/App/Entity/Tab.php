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
use Doctrine\ORM\Mapping as ORM;
use SoureCode\Bundle\Cqrs\Tests\App\Repository\TabRepository;
use Symfony\Component\Uid\Ulid;

/**
 * @author Jason Schilling <jason@sourecode.dev>
 */
#[ORM\Entity(repositoryClass: TabRepository::class)]
class Tab
{
    #[ORM\Column(nullable: true)]
    protected ?int $given = null;

    #[ORM\Id]
    #[ORM\Column(type: 'ulid')]
    protected Ulid $id;

    #[ORM\Column(nullable: false)]
    protected ?bool $open = null;

    /**
     * @var Collection<int, Order> $orders
     */
    #[ORM\OneToMany(mappedBy: 'tab', targetEntity: Order::class)]
    protected Collection $orders;

    #[ORM\Column(nullable: true)]
    protected ?int $paid = null;

    #[ORM\Column(nullable: true)]
    protected ?int $total = null;

    #[ORM\ManyToOne(targetEntity: Table::class, inversedBy: 'tabs')]
    protected ?Table $table = null;

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

    public function setTotal(?int $total): void
    {
        $this->total = $total;
    }
}
