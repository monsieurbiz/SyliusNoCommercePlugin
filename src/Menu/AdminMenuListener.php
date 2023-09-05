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
        $menu->removeChild('catalog');
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
        $configuration->removeChild('currencies');

        if (!$this->config->areZonesAllowed() && !$this->config->areCountriesAllowed()) {
            $configuration->removeChild('countries');
        }
        if (!$this->config->areZonesAllowed()) {
            $configuration->removeChild('zones');
        }
        $configuration->removeChild('exchange_rates');
        $configuration->removeChild('payment_methods');
        $configuration->removeChild('shipping_methods');
        $configuration->removeChild('shipping_categories');
        $configuration->removeChild('tax_categories');
        $configuration->removeChild('tax_rates');
    }
}
