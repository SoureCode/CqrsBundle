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
use SoureCode\Bundle\Cqrs\Tests\App\Command\ServeOrderCommand;
use SoureCode\Bundle\Cqrs\Tests\App\Event\OrderServedEvent;
use SoureCode\Bundle\Cqrs\Tests\App\Repository\OrderRepository;
use SoureCode\Component\Cqrs\CommandHandlerInterface;

/**
 * @author Jason Schilling <jason@sourecode.dev>
 */
class ServeOrderCommandHandler implements CommandHandlerInterface
{
    private OrderRepository $orderRepository;

    public function __construct(OrderRepository $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    public function __invoke(ServeOrderCommand $command)
    {
        $id = $command->getId();
        $order = $this->orderRepository->get($id);

        if ($order->isDone()) {
            throw new Exception('Order was already served.');
        }

        $order->setDone(true);

        $this->orderRepository->persist($order);

        return yield new OrderServedEvent($id);
    }
}
