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
                    $routeCondition = $route->getCondition();
                    if ($routeCondition && false === strpos($routeCondition, 'not context.checkNoCommerce()')) {
                        $route->setCondition($routeCondition . ' and not context.checkNoCommerce()');
                    } elseif (!$routeCondition) {
                        $route->setCondition('not context.checkNoCommerce()');
                    }
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
            (array) ($this->container->getParameter('monsieurbiz_sylius_nocommerce.config') ?? [])
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
        $routesByGroup = ConfigInterface::ROUTES_BY_GROUP;

        /** @deprecated */
        if ($config->areCustomersAllowed()) {
            unset($routesByGroup['customer_shop'], $routesByGroup['customer_api'], $routesByGroup['customer_admin']);
        }

        /** @deprecated */
        if ($config->areZonesAllowed()) {
            unset($routesByGroup['zone_admin'], $routesByGroup['zone_api']);
        }

        /** @deprecated */
        if ($config->areZonesAllowed() || $config->areCountriesAllowed()) {
            unset($routesByGroup['country_admin'], $routesByGroup['country_api']);
        }

        /** @var FeaturesProviderInterface $featuresProvider */
        $featuresProvider = $this->container->get('monsieurbiz.no_commerce.provider.features_provider');

        try {
            $allowAdmin = $featuresProvider->allowAdmin();
        } catch (Exception $e) {
            $allowAdmin = false;
        }

        foreach ($routesByGroup as $routesKey => $routes) {
            // Allow admin group routes
            if (true === $allowAdmin && false !== strpos($routesKey, ConfigInterface::ADMIN_ROUTE_GROUP_MATCHER)) {
                continue;
            }

            $routesToRemove = array_merge($routesToRemove, $routes);
        }

        return $routesToRemove;
    }
}
