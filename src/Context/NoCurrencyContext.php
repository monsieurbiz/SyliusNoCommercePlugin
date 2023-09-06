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

use MonsieurBiz\SyliusNoCommercePlugin\Provider\FeaturesProviderInterface;
use Sylius\Component\Currency\Context\CurrencyContextInterface;

final class NoCurrencyContext implements CurrencyContextInterface
{
    public const NONE_CURRENCY_CODE = 'NONE';

    private CurrencyContextInterface $decoratedCurrencyContext;

    private FeaturesProviderInterface $featuresProvider;

    public function __construct(
        CurrencyContextInterface $decoratedCurrencyContext,
        FeaturesProviderInterface $featuresProvider
    ) {
        $this->decoratedCurrencyContext = $decoratedCurrencyContext;
        $this->featuresProvider = $featuresProvider;
    }

    public function getCurrencyCode(): string
    {
        if (!$this->featuresProvider->isNoCommerceEnabledForChannel()) {
            return $this->decoratedCurrencyContext->getCurrencyCode();
        }

        return self::NONE_CURRENCY_CODE;
    }
}
