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

use SoureCode\Bundle\Cqrs\Tests\App\Command\RegisterUserCommand;
use SoureCode\Bundle\Cqrs\Tests\App\Entity\AppUser;
use SoureCode\Bundle\Cqrs\Tests\App\Event\UserRegisteredEvent;
use SoureCode\Component\Cqrs\CommandHandlerInterface;
use SoureCode\Component\Cqrs\EventBusInterface;
use SoureCode\Component\User\UserFactoryInterface;
use SoureCode\Component\User\UserFieldsUpdaterInterface;
use SoureCode\Component\User\UserPersisterInterface;
use SoureCode\Component\User\UserTokenFactoryInterface;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Stamp\DispatchAfterCurrentBusStamp;

/**
 * @author Jason Schilling <jason@sourecode.dev>
 */
class RegisterUserCommandHandler implements CommandHandlerInterface
{

    private EventBusInterface $eventBus;
    private UserFactoryInterface $userFactory;
    private UserPersisterInterface $userPersister;
    private UserTokenFactoryInterface $userTokenFactory;

    public function __construct(UserFactoryInterface $userFactory, UserTokenFactoryInterface $userTokenFactory, UserPersisterInterface $userPersister, EventBusInterface $eventBus)
    {
        $this->eventBus = $eventBus;
        $this->userPersister = $userPersister;
        $this->userFactory = $userFactory;
        $this->userTokenFactory = $userTokenFactory;
    }

    public function __invoke(RegisterUserCommand $command): void
    {
        $id = $command->getId();

        $user = $this->userFactory->create($id);
        $user->setEmail($command->getEmail());
        $user->setPlainPassword($command->getPassword());

        $userToken = $this->userTokenFactory->create('register');
        $token = $userToken->getToken();

        $user->addUserToken($userToken);

        $this->userPersister->persist($user);

        $event = new UserRegisteredEvent($id, $token->getId());
        $eventEnvelope = (new Envelope($event))->with(new DispatchAfterCurrentBusStamp());
        $this->eventBus->dispatch($eventEnvelope);
    }
}
