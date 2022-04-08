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

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{
    /**
     * @inheritdoc
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('monsieurbiz_sylius_nocommerce');
        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->children()
            // Config
            ->arrayNode('config')
                ->addDefaultsIfNotSet()
                ->children()
                    ->booleanNode('allow_countries')->defaultFalse()->end()
                    ->booleanNode('allow_customers')->defaultFalse()->end()
                    ->booleanNode('allow_zones')->defaultFalse()->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
