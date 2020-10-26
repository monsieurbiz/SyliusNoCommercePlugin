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

use Sylius\Bundle\UiBundle\Menu\Event\MenuBuilderEvent;

final class AdminMenuListener
{
    public function __invoke(MenuBuilderEvent $event): void
    {
        $menu = $event->getMenu();

        $menu->removeChild('sales');
        $menu->removeChild('catalog');
        $menu->removeChild('marketing');

        $configuration = $menu->getChild('configuration');
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
