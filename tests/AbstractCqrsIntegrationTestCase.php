<?php
/*
 * This file is part of the SoureCode package.
 *
 * (c) Jason Schilling <jason@sourecode.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SoureCode\Bundle\Cqrs\Tests;

use Doctrine\ORM\EntityManager;
use SoureCode\Bundle\User\Repository\UserRepositoryInterface;
use SoureCode\Component\Cqrs\CommandBusInterface;
use SoureCode\Component\Cqrs\EventBusInterface;
use SoureCode\Component\Cqrs\QueryBusInterface;
use SoureCode\Component\Test\ApplicationTrait;
use Symfony\Bridge\Doctrine\ManagerRegistry;

/**
 * @author Jason Schilling <jason@sourecode.dev>
 */
abstract class AbstractCqrsIntegrationTestCase extends AbstractCqrsTestCase
{
    use ApplicationTrait;

    protected ?EntityManager $entityManager = null;
    protected ?CommandBusInterface $commandBus = null;
    protected ?EventBusInterface $eventBus = null;
    protected ?QueryBusInterface $queryBus = null;
    protected ?UserRepositoryInterface $repository = null;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();
        $container = $kernel->getContainer();

        /**
         * @var ManagerRegistry $registry
         */
        $registry = $container->get('doctrine');
        $this->entityManager = $registry->getManager();

        $this->eventBus = $container->get(EventBusInterface::class);
        $this->commandBus = $container->get(CommandBusInterface::class);
        $this->queryBus = $container->get(QueryBusInterface::class);

        $this->repository = $container->get(UserRepositoryInterface::class);

        $this->executeCommand([
            'command' => 'doctrine:database:drop',
            '--if-exists' => true,
            '--force' => true,
            '--no-interaction' => true,
        ]);

        $this->executeCommand([
            'command' => 'doctrine:database:create',
            '--if-not-exists' => true,
            '--no-interaction' => true,
        ]);

        $this->executeCommand([
            'command' => 'doctrine:schema:update',
            '--force' => true,
            '--no-interaction' => true,
        ]);
    }

    protected function tearDown(): void
    {
        $this->entityManager->close();
        $this->entityManager = null;

        $this->repository = null;

        $this->eventBus = null;
        $this->commandBus = null;
        $this->queryBus = null;

        self::ensureApplicationShutdown();
        self::$application = null;

        parent::tearDown();
    }
}
