<?php

/*
 * This file is part of Monsieur Biz' No Commerce plugin for Sylius.
 *
 * (c) Monsieur Biz <sylius@monsieurbiz.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace MonsieurBiz\SyliusNoCommercePlugin\Model;

final class Config implements ConfigInterface
{
    private array $config = [];

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function areCustomersAllowed(): bool
    {
        return (bool) $this->config['allow_customers'] ?: false;
    }
}
