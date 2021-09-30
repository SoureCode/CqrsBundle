<?php
/*
 * This file is part of the SoureCode package.
 *
 * (c) Jason Schilling <jason@sourecode.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SoureCode\Bundle\Cqrs\Tests\App\Entity;

use Doctrine\ORM\Mapping as ORM;
use SoureCode\Component\User\Model\User;

/**
 * @author Jason Schilling <jason@sourecode.dev>
 */
#[ORM\Entity]
#[ORM\Table(name: "`user`")]
class AppUser extends User
{
}
