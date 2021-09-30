<?php
/*
 * This file is part of the SoureCode package.
 *
 * (c) Jason Schilling <jason@sourecode.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SoureCode\Bundle\Cqrs\Tests\App\EventHandler;

use SoureCode\Bundle\Cqrs\Tests\App\Event\UserRegisteredEvent;
use SoureCode\Bundle\User\Repository\UserRepositoryInterface;
use SoureCode\Component\Cqrs\EventHandlerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;

/**
 * @author Jason Schilling <jason@sourecode.dev>
 */
class UserRegisteredEventHandler implements EventHandlerInterface
{
    private MailerInterface $mailer;
    private UserRepositoryInterface $userRepository;

    public function __construct(MailerInterface $mailer, UserRepositoryInterface $userRepository)
    {
        $this->mailer = $mailer;
        $this->userRepository = $userRepository;
    }

    public function __invoke(UserRegisteredEvent $event)
    {
        $id = $event->getUserId();
        $user = $this->userRepository->get($id);

        $email = (new TemplatedEmail())
            ->to(new Address($user->getCanonicalEmail(), $user->getDisplayName()))
            ->from('test@sourecode.dev')
            ->subject('Welcome!')
            ->html(
                sprintf(
                    'Welcome! <strong>%s</strong>. Your registration token id: %s',
                    $user->getDisplayName(),
                    $event->getTokenId()->toRfc4122()
                )
            );

        $this->mailer->send($email);
    }
}
