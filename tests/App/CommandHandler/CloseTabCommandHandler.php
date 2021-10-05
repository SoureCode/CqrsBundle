<?php
/*
 * This file is part of the SoureCode package.
 *
 * (c) Jason Schilling <jason@sourecode.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SoureCode\Bundle\Cqrs\Tests\App\CommandHandler;

use Exception;
use SoureCode\Bundle\Cqrs\Tests\App\Command\CloseTabCommand;
use SoureCode\Bundle\Cqrs\Tests\App\Entity\Order;
use SoureCode\Bundle\Cqrs\Tests\App\Entity\Tab;
use SoureCode\Bundle\Cqrs\Tests\App\Event\TabClosedEvent;
use SoureCode\Bundle\Cqrs\Tests\App\Repository\OrderRepository;
use SoureCode\Bundle\Cqrs\Tests\App\Repository\PriceRepository;
use SoureCode\Bundle\Cqrs\Tests\App\Repository\TabRepository;
use SoureCode\Component\Cqrs\CommandHandlerInterface;

/**
 * @author Jason Schilling <jason@sourecode.dev>
 */
class CloseTabCommandHandler implements CommandHandlerInterface
{
    private OrderRepository $orderRepository;

    private PriceRepository $priceRepository;

    private TabRepository $tabRepository;

    public function __construct(
        TabRepository $tabRepository,
        OrderRepository $orderRepository,
        PriceRepository $priceRepository
    ) {
        $this->tabRepository = $tabRepository;
        $this->orderRepository = $orderRepository;
        $this->priceRepository = $priceRepository;
    }

    public function __invoke(CloseTabCommand $command)
    {
        $id = $command->getId();
        $given = $command->getGiven();
        $paid = $command->getPaid();

        if ($paid > $given) {
            throw new Exception('Given cannot be less than paid.');
        }

        $tab = $this->tabRepository->get($id);

        if (!$tab->isOpen()) {
            throw new Exception('Tab already closed.');
        }

        if ($this->orderRepository->hasOpenOrders($tab)) {
            throw new Exception('Tab has still open orders.');
        }

        $total = $this->getTotal($tab);

        if ($paid < $total) {
            throw new Exception('Paid not enough money.');
        }

        $tab->setOpen(false);
        $tab->setTotal($total);
        $tab->setGiven($given);
        $tab->setPaid($paid);

        $this->tabRepository->persist($tab);

        return yield new TabClosedEvent($id);
    }

    protected function getTotal(Tab $tab): int
    {
        $total = 0;

        /**
         * @var Order $order
         */
        foreach ($tab->getOrders() as $order) {
            $product = $order->getProduct();
            $price = $this->priceRepository->getPrice($order);

            if (!$price) {
                throw new Exception('Missing price for product "'.$product->getName().'".');
            }

            $total += $price->getValue();
        }

        return $total;
    }
}
