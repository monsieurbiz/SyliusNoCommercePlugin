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

namespace MonsieurBiz\SyliusNoCommercePlugin\Firewall;

use IteratorAggregate;
use Symfony\Bundle\SecurityBundle\Security\FirewallContext;

/**
 * @extends IteratorAggregate<FirewallContext>
 */
interface RegistryInterface extends IteratorAggregate
{
    public function addFirewall(FirewallContext $firewall): void;
}
