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

namespace MonsieurBiz\SyliusNoCommercePlugin\Firewall;

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

    public function getIterator(): \ArrayIterator
    {
        return new \ArrayIterator($this->firewalls);
    }
}
