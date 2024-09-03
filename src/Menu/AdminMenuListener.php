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

namespace MonsieurBiz\SyliusNoCommercePlugin\Menu;

use Knp\Menu\ItemInterface;
use MonsieurBiz\SyliusNoCommercePlugin\Provider\FeaturesProviderInterface;
use Sylius\Bundle\UiBundle\Menu\Event\MenuBuilderEvent;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\RouterInterface;

final class AdminMenuListener
{
    private FeaturesProviderInterface $featuresProvider;

    private RouterInterface $router;

    public function __construct(
        FeaturesProviderInterface $featuresProvider,
        RouterInterface $router
    ) {
        $this->featuresProvider = $featuresProvider;
        $this->router = $router;
    }

    /**
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    public function __invoke(MenuBuilderEvent $event): void
    {
        $menu = $event->getMenu();

        if (!$this->featuresProvider->isNoCommerceEnabledForChannel()) {
            return;
        }

        // We loop on all items and check if each route is enabled or not
        // If the route is disabled, we remove the menu item
        // If all children are removed, we remove the parent menu item

        // Retrieve all avaible routes
        $routes = $this->router->getRouteCollection();

        // Loop on level 1 menu items
        foreach ($menu->getChildren() as $childMenu) {
            // Loop on level 2 menu items
            foreach ($childMenu->getChildren() as $menuItem) {
                // Remove menu item if route is disabled
                $this->removeChildIfRoutesDisabled($childMenu, $menuItem->getName(), $routes);
            }

            // Remove parent menu item if no child left
            if (0 === \count($childMenu->getChildren())) {
                $menu->removeChild($childMenu->getName());
            }
        }
    }

    /**
     * If the route in the menu items matches a route that is disabled, remove the menu item.
     * We now that the route is disabled if the condition contains "not context.checkNoCommerce()".
     */
    private function removeChildIfRoutesDisabled(ItemInterface $menu, string $menuName, RouteCollection $routes): void
    {
        $menuItem = $menu->getChild($menuName);
        if (!$menuItem || null === $menuItem->getExtra('routes')) {
            return;
        }

        $route = $this->getRouteByUri((string) $menuItem->getUri(), $routes);
        if (false !== strpos((string) $route?->getCondition(), 'not context.checkNoCommerce()')) {
            $menu->removeChild($menuName);
        }
    }

    /**
     * @SuppressWarnings(PHPMD.UnusedLocalVariable)
     */
    private function getRouteByUri(string $uri, RouteCollection $routes): ?Route
    {
        foreach ($routes as $name => $route) {
            if ($uri === $route->getPath()) {
                return $route;
            }
        }

        return null;
    }
}
