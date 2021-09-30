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

use SoureCode\Bundle\Cqrs\Tests\App\Command\RegisterUserCommand;
use SoureCode\Bundle\Cqrs\Tests\App\Entity\AppUser;
use SoureCode\Bundle\Cqrs\Tests\App\Query\GetUserQuery;
use Symfony\Bundle\FrameworkBundle\Test\MailerAssertionsTrait;
use Symfony\Component\Uid\Ulid;

/**
 * @author Jason Schilling <jason@sourecode.dev>
 */
class IntegrationTest extends AbstractCqrsIntegrationTestCase
{
    use MailerAssertionsTrait;

    public function testRegisterUser(): void
    {
        // Arrange
        $id = new Ulid();
        $command = new RegisterUserCommand($id, 'jason.schilling@sourecode.dev', 'foobar123');

        // Act
        $this->commandBus->dispatch($command);
        $user = $this->queryBus->handle(new GetUserQuery($id));

        // Assert
        $this->entityManager->clear();

        $users = $this->repository->findAll();
        self::assertCount(1, $users);

        self::assertSame('jason.schilling@sourecode.dev', $user->getEmail());
        self::assertSame($id->toRfc4122(), $user->getId()->toRfc4122());

        self::assertEmailCount(1);
    }

}
