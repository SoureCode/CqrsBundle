<?php
/*
 * This file is part of the SoureCode package.
 *
 * (c) Jason Schilling <jason@sourecode.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SoureCode\Bundle\Cqrs\Tests\App\Command;

use SoureCode\Component\Cqrs\CommandInterface;
use Symfony\Component\Uid\Ulid;

/**
 * @author Jason Schilling <jason@sourecode.dev>
 */
class RegisterUserCommand implements CommandInterface
{

    private string $email;
    private string $password;
    private Ulid $id;

    public function __construct(Ulid $id, string $email, string $password)
    {
        $this->id = $id;
        $this->email = $email;
        $this->password = $password;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getId(): Ulid
    {
        return $this->id;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

}
