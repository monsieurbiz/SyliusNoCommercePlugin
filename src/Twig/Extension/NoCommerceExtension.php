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

namespace MonsieurBiz\SyliusNoCommercePlugin\Twig\Extension;

use MonsieurBiz\SyliusNoCommercePlugin\Provider\FeaturesProviderInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

final class NoCommerceExtension extends AbstractExtension
{
    private FeaturesProviderInterface $featuresProvider;

    public function __construct(
        FeaturesProviderInterface $featuresProvider
    ) {
        $this->featuresProvider = $featuresProvider;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('is_no_commerce_enabled', [$this, 'isNoCommerceEnabled']),
        ];
    }

    public function isNoCommerceEnabled(): bool
    {
        return $this->featuresProvider->isNoCommerceEnabledForChannel();
    }
}
