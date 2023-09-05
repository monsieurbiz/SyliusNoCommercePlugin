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

use MonsieurBiz\SyliusNoCommercePlugin\Provider\FeaturesProviderInterface;
use Sylius\Bundle\UiBundle\Menu\Event\MenuBuilderEvent;

final class AdminCustomerShowMenuListener
{
    private FeaturesProviderInterface $featuresProvider;

    public function __construct(
        FeaturesProviderInterface $featuresProvider
    )
    {
        $this->featuresProvider = $featuresProvider;
    }

    public function __invoke(MenuBuilderEvent $event): void
    {
        if (!$this->featuresProvider->isNoCommerceEnabledForChannel()) {
            return;
        }

        $menu = $event->getMenu();
        $menu->removeChild('order_index');
    }
}
