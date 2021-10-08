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
use SoureCode\Bundle\Cqrs\Test\CqrsTestTrait;
use SoureCode\Component\Test\ApplicationTrait;
use Symfony\Bridge\Doctrine\ManagerRegistry;

/**
 * @author Jason Schilling <jason@sourecode.dev>
 */
abstract class AbstractCqrsIntegrationTestCase extends AbstractCqrsTestCase
{
    use ApplicationTrait;
    use CqrsTestTrait;

    protected ?EntityManager $entityManager = null;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();
        $container = $kernel->getContainer();

        /**
         * @var ManagerRegistry $registry
         */
        $registry = $container->get('doctrine');
        $this->entityManager = $registry->getManager();

        self::setUpCqrs();

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
        $this->entityManager->getConnection()->close();
        $this->entityManager->close();
        $this->entityManager = null;

        self::tearDownCqrs();
        self::tearDownApplication();

        parent::tearDown();
    }
}
