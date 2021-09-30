<?php
/*
 * This file is part of the SoureCode package.
 *
 * (c) Jason Schilling <jason@sourecode.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SoureCode\Bundle\Cqrs;

use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * @author Jason Schilling <jason@sourecode.dev>
 */
class SoureCodeCqrsBundle extends Bundle
{
    public function getPath(): string
    {
        return \dirname(__DIR__);
    }
}
