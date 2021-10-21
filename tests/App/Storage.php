<?php
/*
 * This file is part of the SoureCode package.
 *
 * (c) Jason Schilling <jason@sourecode.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SoureCode\Bundle\Cqrs\Tests\App;

use Doctrine\Common\Collections\ArrayCollection;
use SoureCode\Bundle\Cqrs\Tests\App\Entity\Order;
use SoureCode\Bundle\Cqrs\Tests\App\Entity\Price;
use SoureCode\Bundle\Cqrs\Tests\App\Entity\Tab;
use SoureCode\Bundle\Cqrs\Tests\App\Entity\Table;

/**
 * @author Jason Schilling <jason@sourecode.dev>
 */
class Storage extends ArrayCollection
{
    public function getPrice(Order $order): ?Price
    {
        $product = $order->getProduct();
        $prices = $product->getPrices()->toArray();
        $date = $order->getCreatedAt();

        usort($prices, static function (Price $priceA, Price $priceB) {
            return $priceB->getEffectiveAt()->getTimestamp() - $priceA->getEffectiveAt()->getTimestamp();
        });

        /**
         * @var Price $price
         */
        foreach ($prices as $price) {
            if ($price->getEffectiveAt() < $date) {
                return $price;
            }
        }

        return null;
    }

    public function hasOpenOrders(Tab $tab): bool
    {
        $orders = $tab->getOrders();

        /**
         * @var Order $order
         */
        foreach ($orders as $order) {
            if (!$order->isDone()) {
                return true;
            }
        }

        return false;
    }

    public function hasOpenTab(Table $table): bool
    {
        $tabs = $table->getTabs();

        /**
         * @var Tab $tab
         */
        foreach ($tabs as $tab) {
            if ($tab->isOpen()) {
                return true;
            }
        }

        return false;
    }
}
