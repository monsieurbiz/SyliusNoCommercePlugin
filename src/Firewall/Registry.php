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

use ArrayIterator;
use Symfony\Bundle\SecurityBundle\Security\FirewallContext;

class Registry implements RegistryInterface
{
    /**
     * @var FirewallContext[]
     */
    private array $firewalls = [];

    public function addFirewall(FirewallContext $firewall): void
    {
        $this->firewalls[] = $firewall;
    }

    public function getIterator(): \Traversable
    {
        return new ArrayIterator($this->firewalls);
    }
}
