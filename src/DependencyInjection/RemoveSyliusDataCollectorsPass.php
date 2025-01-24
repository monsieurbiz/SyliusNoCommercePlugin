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

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class RemoveSyliusDataCollectorsPass implements CompilerPassInterface
{
    /**
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    public function process(ContainerBuilder $container): void
    {
        foreach ($container->getDefinitions() as $id => $definition) {
            if ($definition->hasTag('data_collector')) {
                $tagDetails = current($definition->getTag('data_collector'));
                if ('sylius_cart' === ($tagDetails['id'] ?? '')) {
                    $container->removeDefinition($id);
                }
            }
        }
        if ($container->hasAlias('sylius.collector.cart')) {
            $container->removeAlias('sylius.collector.cart');
        }
    }
}
