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

namespace MonsieurBiz\SyliusNoCommercePlugin\Provider;

use Sylius\Component\Core\Model\ChannelInterface;

interface FeaturesProviderInterface
{
    public function isNoCommerceEnabledForChannel(?ChannelInterface $channel = null): bool;

    public function allowAdmin(): bool;
}
