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
use SoureCode\Bundle\Cqrs\Tests\App\Repository\ProductRepository;
use Symfony\Component\Uid\Ulid;

/**
 * @author Jason Schilling <jason@sourecode.dev>
 */
#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    #[ORM\Id]
    #[ORM\Column(type: 'ulid')]
    protected Ulid $id;

    #[ORM\Column(nullable: false)]
    protected ?string $name = null;

    /**
     * @var Collection<int, Order> $orders
     */
    #[ORM\OneToMany(mappedBy: 'product', targetEntity: Order::class)]
    protected Collection $orders;

    /**
     * @var Collection<int, Price> $prices
     */
    #[ORM\OneToMany(mappedBy: 'product', targetEntity: Price::class)]
    protected Collection $prices;

    public function __construct(Ulid $id)
    {
        $this->id = $id;
        $this->orders = new ArrayCollection();
        $this->prices = new ArrayCollection();
    }

    public function addPrice(Price $price): self
    {
        if (!$this->prices->contains($price)) {
            $this->prices[] = $price;

            $price->setProduct($this);
        }

        return $this;
    }

    public function getId(): Ulid
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function addOrder(Order $order): self
    {
        if (!$this->orders->contains($order)) {
            $this->orders[] = $order;

            $order->setProduct($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Order>
     */
    public function getOrders(): Collection
    {
        return $this->orders;
    }

    public function removeOrder(Order $order): self
    {
        if ($this->orders->removeElement($order)) {
            // set the owning side to null (unless already changed)
            if ($order->getProduct() === $this) {
                $order->setProduct(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Price>
     */
    public function getPrices(): Collection
    {
        return $this->prices;
    }

    public function removePrice(Price $price): self
    {
        if ($this->prices->removeElement($price)) {
            // set the owning side to null (unless already changed)
            if ($price->getProduct() === $this) {
                $price->setProduct(null);
            }
        }

        return $this;
    }
}
