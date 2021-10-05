<?php
/*
 * This file is part of the SoureCode package.
 *
 * (c) Jason Schilling <jason@sourecode.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SoureCode\Bundle\Cqrs\Tests\App\Model;

use NumberFormatter;

/**
 * @author Jason Schilling <jason@sourecode.dev>
 */
class Money
{
    private int $amount;

    private string $currency;

    public function __construct(int $amount, string $currency = 'EUR')
    {
        $this->amount = $amount;
        $this->currency = $currency;
    }

    public function format(string $locale = 'de_DE'): string
    {
        $formatter = new NumberFormatter($locale, NumberFormatter::CURRENCY);

        return $formatter->formatCurrency($this->amount / 100, $this->currency);
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function setAmount(int $amount): void
    {
        $this->amount = $amount;
    }
}
