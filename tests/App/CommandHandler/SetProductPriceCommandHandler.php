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
use SoureCode\Bundle\Cqrs\Tests\App\Command\SetProductPriceCommand;
use SoureCode\Bundle\Cqrs\Tests\App\Entity\Price;
use SoureCode\Bundle\Cqrs\Tests\App\Entity\Product;
use SoureCode\Bundle\Cqrs\Tests\App\Event\ProductPriceChangedEvent;
use SoureCode\Bundle\Cqrs\Tests\App\Storage;
use SoureCode\Component\Cqrs\CommandHandlerInterface;

/**
 * @author Jason Schilling <jason@sourecode.dev>
 */
class SetProductPriceCommandHandler implements CommandHandlerInterface
{
    private Storage $storage;

    public function __construct(Storage $storage)
    {
        $this->storage = $storage;
    }

    public function __invoke(SetProductPriceCommand $command)
    {
        $id = $command->getId();
        $productPrice = $command->getProductId();

        /**
         * @var Product $product
         */
        $product = $this->storage->get((string) $productPrice);

        $price = new Price($id);
        $price->setProduct($product);
        $price->setEffectiveAt(new DateTimeImmutable());
        $price->setValue($command->getPrice());

        $product->addPrice($price);

        $this->storage->set((string) $id, $price);

        return yield new ProductPriceChangedEvent($id);
    }
}
