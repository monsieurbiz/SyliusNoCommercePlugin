<?php

/*
 * This file is part of SyliusNoCommercePlugin corporate website.
 *
 * (c) SyliusNoCommercePlugin <sylius+syliusnocommerceplugin@monsieurbiz.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace MonsieurBiz\SyliusNoCommercePlugin\Provider;

use Sylius\Component\Core\Model\ChannelInterface;

interface FeaturesProviderInterface
{
    public  function isNoCommerceEnabledForChannel(?ChannelInterface $channel = null): bool;
}
