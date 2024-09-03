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

namespace MonsieurBiz\SyliusNoCommercePlugin\Kernel;

use Exception;
use MonsieurBiz\SyliusNoCommercePlugin\Model\Config;
use MonsieurBiz\SyliusNoCommercePlugin\Model\ConfigInterface;
use MonsieurBiz\SyliusNoCommercePlugin\Provider\FeaturesProviderInterface;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\Routing\RouteCollection;

trait SyliusNoCommerceKernelTrait
{
    use MicroKernelTrait {
        MicroKernelTrait::loadRoutes as parentLoadRoutes;
    }

    /**
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    public function loadRoutes(LoaderInterface $loader): RouteCollection
    {
        $collection = $this->parentLoadRoutes($loader);

        $routesToRemove = $this->getRoutesToRemove();
        foreach ($collection as $name => $route) {
            foreach ($routesToRemove as $routeToRemove) {
                if (false !== strpos($name, $routeToRemove)) {
                    $route->setCondition('not context.checkNoCommerce()');
                }
            }
        }

        return $collection;
    }

    /**
     * Create a NoCommerce Config object.
     */
    private function getConfig(): ConfigInterface
    {
        return new Config(
            (array) $this->container->getParameter('monsieurbiz_sylius_nocommerce.config') ?? []
        );
    }

    /**
     * Retrieve all routes to remove depending on config.
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    public function getRoutesToRemove(): array
    {
        $config = $this->getConfig();
        $routesToRemove = [];
        $this->routesToRemove = ConfigInterface::ROUTES_BY_GROUP;

        /** @deprecated */
        if ($config->areCustomersAllowed()) {
            unset($this->routesToRemove['customer']);
        }

        /** @deprecated */
        if ($config->areZonesAllowed()) {
            unset($this->routesToRemove['zone']);
        }

        /** @deprecated */
        if ($config->areZonesAllowed() || $config->areCountriesAllowed()) {
            unset($this->routesToRemove['country']);
        }

        // Loop on settings to add routes
        /** @var FeaturesProviderInterface $featuresProvider */
        $featuresProvider = $this->container->get('monsieurbiz.no_commerce.provider.features_provider');

        try {
            $routesToEnable = $featuresProvider->getRoutesToEnable();
        } catch (Exception $e) {
            $routesToEnable = [];
        }

        foreach ($routesToEnable as $route) {
            $this->enableRoute($route);
        }

        foreach ($this->routesToRemove as $routes) {
            $routesToRemove = array_merge($routesToRemove, $routes);
        }

        return $routesToRemove;
    }

    private function enableRoute(string $route): void
    {
        foreach ($this->routesToRemove as $group => $routes) {
            if (false !== ($key = array_search($route, $routes, true))) {
                unset($this->routesToRemove[$group][$key]);
            }

            if (empty($this->routesToRemove[$group])) {
                unset($this->routesToRemove[$group]);
            }
        }
    }
}
