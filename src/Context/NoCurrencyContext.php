<?php

/*
 * This file is part of Monsieur Biz' No Commerce plugin for Sylius.
 *
 * (c) Monsieur Biz <sylius@monsieurbiz.com>
 *
 * For the full copyright and license information, please view the LICENSE.txt
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace MonsieurBiz\SyliusNoCommercePlugin\Context;

use Sylius\Component\Currency\Context\CurrencyContextInterface;

final class NoCurrencyContext implements CurrencyContextInterface
{
    public const NONE_CURRENCY_CODE = 'NONE';

    public function getCurrencyCode(): string
    {
        return self::NONE_CURRENCY_CODE;
    }
}
