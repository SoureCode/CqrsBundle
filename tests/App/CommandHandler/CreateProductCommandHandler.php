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

use SoureCode\Bundle\Cqrs\Tests\App\Command\CreateProductCommand;
use SoureCode\Bundle\Cqrs\Tests\App\Entity\Product;
use SoureCode\Bundle\Cqrs\Tests\App\Event\ProductCreatedEvent;
use SoureCode\Bundle\Cqrs\Tests\App\Storage;
use SoureCode\Component\Cqrs\CommandHandlerInterface;

/**
 * @author Jason Schilling <jason@sourecode.dev>
 */
class CreateProductCommandHandler implements CommandHandlerInterface
{
    private Storage $storage;

    public function __construct(Storage $storage)
    {
        $this->storage = $storage;
    }

    public function __invoke(CreateProductCommand $command)
    {
        $id = $command->getId();

        $product = new Product($id);

        $product->setName($command->getName());

        $this->storage->set((string) $id, $product);

        return yield new ProductCreatedEvent($id);
    }
}
