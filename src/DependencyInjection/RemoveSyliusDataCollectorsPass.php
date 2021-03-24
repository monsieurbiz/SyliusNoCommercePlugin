<?php

declare(strict_types=1);

namespace MonsieurBiz\SyliusNoCommercePlugin\DependencyInjection;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class RemoveSyliusDataCollectorsPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        foreach ($container->getDefinitions() as $id => $definition) {
            if ($definition->hasTag('data_collector')) {
                $tagDetails = current($definition->getTag('data_collector'));
                if (isset($tagDetails['id']) && $tagDetails['id'] === 'sylius_cart') {
                    $container->removeDefinition($id);
                }
            }
        }
    }
}
