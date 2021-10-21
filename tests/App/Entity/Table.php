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
class Table
{
    protected Ulid $id;

    /**
     * @var Collection<int, Tab>
     */
    protected Collection $tabs;

    public function __construct(Ulid $id)
    {
        $this->id = $id;
        $this->tabs = new ArrayCollection();
    }

    public function addTab(Tab $tab): self
    {
        if (!$this->tabs->contains($tab)) {
            $this->tabs[] = $tab;

            $tab->setTable($this);
        }

        return $this;
    }

    public function getId(): Ulid
    {
        return $this->id;
    }

    /**
     * @return Collection<int, Tab>
     */
    public function getTabs(): Collection
    {
        return $this->tabs;
    }

    public function removeTab(Tab $tab): self
    {
        if ($this->tabs->removeElement($tab)) {
            // set the owning side to null (unless already changed)
            if ($tab->getTable() === $this) {
                $tab->setTable(null);
            }
        }

        return $this;
    }
}
