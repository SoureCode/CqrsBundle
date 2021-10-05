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
use SoureCode\Bundle\Cqrs\Tests\App\Repository\ProductRepository;
use SoureCode\Component\Cqrs\CommandHandlerInterface;

/**
 * @author Jason Schilling <jason@sourecode.dev>
 */
class CreateProductCommandHandler implements CommandHandlerInterface
{
    private ProductRepository $repository;

    public function __construct(ProductRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(CreateProductCommand $command)
    {
        $id = $command->getId();

        $product = new Product($id);

        $product->setName($command->getName());

        $this->repository->persist($product);

        return yield new ProductCreatedEvent($id);
    }
}
