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

use DateTimeImmutable;
use Exception;
use SoureCode\Bundle\Cqrs\Tests\App\Command\PlaceOrderCommand;
use SoureCode\Bundle\Cqrs\Tests\App\Entity\Order;
use SoureCode\Bundle\Cqrs\Tests\App\Event\OrderPlacedEvent;
use SoureCode\Bundle\Cqrs\Tests\App\Repository\OrderRepository;
use SoureCode\Bundle\Cqrs\Tests\App\Repository\ProductRepository;
use SoureCode\Bundle\Cqrs\Tests\App\Repository\TabRepository;
use SoureCode\Component\Cqrs\CommandHandlerInterface;

/**
 * @author Jason Schilling <jason@sourecode.dev>
 */
class PlaceOrderCommandHandler implements CommandHandlerInterface
{
    private OrderRepository $orderRepository;

    private ProductRepository $productRepository;

    private TabRepository $tabRepository;

    public function __construct(TabRepository $tabRepository, ProductRepository $productRepository, OrderRepository $orderRepository)
    {
        $this->tabRepository = $tabRepository;
        $this->productRepository = $productRepository;
        $this->orderRepository = $orderRepository;
    }

    public function __invoke(PlaceOrderCommand $command)
    {
        $id = $command->getId();
        $tabId = $command->getTabId();
        $productId = $command->getProductId();

        $tab = $this->tabRepository->get($tabId);

        if (!$tab->isOpen()) {
            throw new Exception('Tab is not open.');
        }

        $product = $this->productRepository->get($productId);

        $order = new Order($id);
        $order->setProduct($product);
        $order->setCreatedAt(new DateTimeImmutable());
        $order->setDone(false);

        $tab->addOrder($order);

        $this->orderRepository->persist($order);

        return yield new OrderPlacedEvent($tabId);
    }
}
