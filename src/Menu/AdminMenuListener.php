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

namespace MonsieurBiz\SyliusNoCommercePlugin\Menu;

use MonsieurBiz\SyliusNoCommercePlugin\Model\ConfigInterface;
use Sylius\Bundle\UiBundle\Menu\Event\MenuBuilderEvent;

final class AdminMenuListener
{
    private ConfigInterface $config;

    public function __construct(ConfigInterface $config)
    {
        $this->config = $config;
    }

    public function __invoke(MenuBuilderEvent $event): void
    {
        $menu = $event->getMenu();

        $menu->removeChild('sales');
        $menu->removeChild('catalog');
        $menu->removeChild('marketing');

        if (!$this->config->getAllowCustomers()) {
            $menu->removeChild('customers');
        }

        if (null !== $configuration = $menu->getChild('configuration')) {
            $configuration->removeChild('currencies');
            $configuration->removeChild('countries');
            $configuration->removeChild('zones');
            $configuration->removeChild('exchange_rates');
            $configuration->removeChild('payment_methods');
            $configuration->removeChild('shipping_methods');
            $configuration->removeChild('shipping_categories');
            $configuration->removeChild('tax_categories');
            $configuration->removeChild('tax_rates');
        }
    }
}
