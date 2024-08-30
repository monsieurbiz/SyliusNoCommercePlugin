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
use MonsieurBiz\SyliusNoCommercePlugin\Model\ConfigInterface;
use MonsieurBiz\SyliusNoCommercePlugin\Provider\FeaturesProviderInterface;
use Sylius\Bundle\UiBundle\Menu\Event\MenuBuilderEvent;

final class AdminMenuListener
{
    private ConfigInterface $config;

    private FeaturesProviderInterface $featuresProvider;

    public function __construct(
        ConfigInterface $config,
        FeaturesProviderInterface $featuresProvider
    ) {
        $this->config = $config;
        $this->featuresProvider = $featuresProvider;
    }

    public function __invoke(MenuBuilderEvent $event): void
    {
        $menu = $event->getMenu();

        if (!$this->featuresProvider->isNoCommerceEnabledForChannel()) {
            return;
        }

        $menu->removeChild('sales');

        $this->handleCatalogMenu($menu);
        $menu->removeChild('marketing');

        if (!$this->config->areCustomersAllowed()) {
            $menu->removeChild('customers');
        }

        if (null !== $configuration = $menu->getChild('configuration')) {
            $this->removeConfigurationChildren($configuration);
        }
    }

    private function removeConfigurationChildren(ItemInterface $configuration): void
    {
        $this->removeChildIfRoutesDisabled($configuration, 'currencies');

        if (!$this->config->areZonesAllowed() && !$this->config->areCountriesAllowed()) {
            $this->removeChildIfRoutesDisabled($configuration, 'countries');
        }
        if (!$this->config->areZonesAllowed()) {
            $this->removeChildIfRoutesDisabled($configuration, 'zones');
        }

        $configuration->removeChild('exchange_rates');
        $this->removeChildIfRoutesDisabled($configuration, 'payment_methods');
        $this->removeChildIfRoutesDisabled($configuration, 'shipping_methods');
        $this->removeChildIfRoutesDisabled($configuration, 'shipping_categories');
        $this->removeChildIfRoutesDisabled($configuration, 'tax_categories');
        $this->removeChildIfRoutesDisabled($configuration, 'tax_rates');
    }

    /**
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    private function removeChildIfRoutesDisabled(ItemInterface $menu, string $menuName): void
    {
        $menuItem = $menu->getChild($menuName);
        if (!$menuItem || null === $menuItem->getExtra('routes')) {
            return;
        }

        foreach ($menuItem->getExtra('routes') as $route) {
            if (!isset($route['route'])) {
                continue;
            }
            // If one route does not match the forced enabled routes, we remove the menu item
            if (!$this->featuresProvider->isRouteForcedEnabled(['_route' => $route['route']])) {
                $menu->removeChild($menuName);
            }
        }
    }

    private function handleCatalogMenu(ItemInterface $menu): void
    {
        $catalogMenu = $menu->getChild('catalog');

        if (null === $catalogMenu) {
            return;
        }

        $this->removeChildIfRoutesDisabled($catalogMenu, 'taxons');
        $this->removeChildIfRoutesDisabled($catalogMenu, 'products');
        $this->removeChildIfRoutesDisabled($catalogMenu, 'inventory');
        $this->removeChildIfRoutesDisabled($catalogMenu, 'attributes');
        $this->removeChildIfRoutesDisabled($catalogMenu, 'options');
        $this->removeChildIfRoutesDisabled($catalogMenu, 'association_types');

        // We remove the catalog menu if it has no children
        if ($catalogMenu->hasChildren()) {
            return;
        }

        $menu->removeChild('catalog');
    }
}
