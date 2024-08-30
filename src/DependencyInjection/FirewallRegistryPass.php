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

namespace MonsieurBiz\SyliusNoCommercePlugin\DependencyInjection;

use Symfony\Bundle\SecurityBundle\Security\FirewallContext;
use Symfony\Component\DependencyInjection\ChildDefinition;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;
use Symfony\Component\DependencyInjection\Reference;

final class FirewallRegistryPass implements CompilerPassInterface
{
    /**
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    public function process(ContainerBuilder $container): void
    {
        $registry = $container->findDefinition('MonsieurBiz\SyliusNoCommercePlugin\Firewall\RegistryInterface');

        foreach ($container->getDefinitions() as $code => $configuration) {
            if (!$configuration instanceof ChildDefinition) {
                continue;
            }

            try {
                $parent = $container->getDefinition($configuration->getParent());
            } catch (ServiceNotFoundException $e) {
                continue;
            }

            if (FirewallContext::class !== $parent->getClass()) {
                continue;
            }
            $registry->addMethodCall('addFirewall', [new Reference($code)]);
        }
    }
}
