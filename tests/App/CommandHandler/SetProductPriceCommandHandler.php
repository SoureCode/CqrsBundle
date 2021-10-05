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
use SoureCode\Bundle\Cqrs\Tests\App\Event\ProductPriceChangedEvent;
use SoureCode\Bundle\Cqrs\Tests\App\Repository\PriceRepository;
use SoureCode\Bundle\Cqrs\Tests\App\Repository\ProductRepository;
use SoureCode\Component\Cqrs\CommandHandlerInterface;

/**
 * @author Jason Schilling <jason@sourecode.dev>
 */
class SetProductPriceCommandHandler implements CommandHandlerInterface
{
    private PriceRepository $priceRepository;

    private ProductRepository $productRepository;

    public function __construct(ProductRepository $productRepository, PriceRepository $priceRepository)
    {
        $this->productRepository = $productRepository;
        $this->priceRepository = $priceRepository;
    }

    public function __invoke(SetProductPriceCommand $command)
    {
        $id = $command->getId();
        $productPrice = $command->getProductId();

        $product = $this->productRepository->get($productPrice);

        $price = new Price($id);
        $price->setProduct($product);
        $price->setEffectiveAt(new DateTimeImmutable());
        $price->setValue($command->getPrice());

        $this->priceRepository->persist($price);

        return yield new ProductPriceChangedEvent($id);
    }
}
