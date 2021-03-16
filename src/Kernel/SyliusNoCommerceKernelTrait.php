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

namespace MonsieurBiz\SyliusNoCommercePlugin\Kernel;

use MonsieurBiz\SyliusNoCommercePlugin\Model\Config;
use MonsieurBiz\SyliusNoCommercePlugin\Model\ConfigInterface;
use MonsieurBiz\SyliusNoCommercePlugin\Routing\RouteCollectionBuilder;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\Routing\RouteCollection;

trait SyliusNoCommerceKernelTrait
{
    use MicroKernelTrait;

    /**
     * @param LoaderInterface $loader
     *
     * @return RouteCollection
     */
    public function loadRoutes(LoaderInterface $loader)
    {
        $routes = new RouteCollectionBuilder($this->getConfig(), $loader);
        $this->configureRoutes($routes);

        return $routes->build();
    }

    /**
     * Create a NoCommerce Config object.
     *
     * @return ConfigInterface
     */
    private function getConfig(): ConfigInterface
    {
        return new Config(
            (array) $this->container->getParameter('monsieurbiz_sylius_nocommerce.config') ?? []
        );
    }
}
