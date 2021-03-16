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

interface ConfigInterface
{
    /**
     * Return true if customers are allowed on the website.
     *
     * @return bool
     */
    public function getAllowCustomers(): bool;
}
