<?php
/*
 * This file is part of the SoureCode package.
 *
 * (c) Jason Schilling <jason@sourecode.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SoureCode\Bundle\Cqrs\Tests\App\QueryHandler;

use SoureCode\Bundle\Cqrs\Tests\App\Query\GetUserQuery;
use SoureCode\Bundle\User\Repository\UserRepositoryInterface;
use SoureCode\Component\Cqrs\QueryHandlerInterface;

/**
 * @author Jason Schilling <jason@sourecode.dev>
 */
class GetUserQueryHandler implements QueryHandlerInterface
{
    private UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function __invoke(GetUserQuery $query)
    {
        $id = $query->getId();

        return $this->userRepository->find($id);
    }
}
