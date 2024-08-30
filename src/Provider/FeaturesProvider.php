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

namespace MonsieurBiz\SyliusNoCommercePlugin\Provider;

use Exception;
use MonsieurBiz\SyliusSettingsPlugin\Settings\SettingsInterface;
use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Core\Model\ChannelInterface;

final class FeaturesProvider implements FeaturesProviderInterface
{
    public const COUNTRIES_KEY = 'countries';

    public const CURRENCIES_KEY = 'currencies';

    public const INVENTORY_KEY = 'inventory';

    public const PAYMENT_KEY = 'payment';

    public const CATALOG_KEY = 'catalog';

    public const SHIPPING_KEY = 'shipping';

    public const TAX_KEY = 'tax';

    public const ZONES_KEY = 'zones';

    public const ADMIN_ROUTES_THAT_CAN_BE_RE_ENABLED = [
        self::COUNTRIES_KEY => [
            'sylius_admin_country',
            'sylius_admin_ajax_render_province_form',
        ],
        self::CURRENCIES_KEY => [
            'sylius_admin_currency',
        ],
        self::INVENTORY_KEY => [
            'sylius_admin_inventory',
        ],
        self::PAYMENT_KEY => [
            'sylius_admin_payment_method',
        ],
        self::CATALOG_KEY => [
            'sylius_admin_get_attribute_types',
            'sylius_admin_get_product_attributes',
            'sylius_admin_render_attribute_forms',
            'sylius_admin_product',
            'sylius_admin_ajax_product',
            'sylius_admin_partial_product',
            'sylius_admin_ajax_generate_product_slug',
            'sylius_admin_partial_taxon',
            'sylius_admin_ajax_taxon',
            'sylius_admin_taxon',
            'sylius_admin_ajax_generate_taxon_slug',
        ],
        self::SHIPPING_KEY => [
            'sylius_admin_shipping',
        ],
        self::TAX_KEY => [
            'sylius_admin_tax_',
        ],
        self::ZONES_KEY => [
            'sylius_admin_zone',
        ],
    ];

    private ChannelContextInterface $channelContext;

    private SettingsInterface $nocommerceSettings;

    public function __construct(
        ChannelContextInterface $channelContext,
        SettingsInterface $nocommerceSettings
    ) {
        $this->channelContext = $channelContext;
        $this->nocommerceSettings = $nocommerceSettings;
    }

    public function isNoCommerceEnabledForChannel(?ChannelInterface $channel = null): bool
    {
        try {
            if (null === $channel) {
                $channel = $this->channelContext->getChannel();
            }
            // In case we are getting a channel that does not exist yet, we return null to have the channel set properly
            if (null === $channel->getId()) {
                return true;
            }
        } catch (Exception $exception) {
            return false;
        }

        return (bool) $this->nocommerceSettings->getCurrentValue($channel, null, 'enabled');
    }

    /**
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    public function isRouteForcedEnabled(array $params = []): bool
    {
        if (!isset($params['_route'])) {
            return false;
        }

        $route = $params['_route'];
        $channel = $this->channelContext->getChannel();
        /** @var ?array $reEnabledAdminRoutes */
        $reEnabledAdminRoutes = $this->nocommerceSettings->getCurrentValue($channel, null, 're_enabled_admin_routes');

        if (empty($reEnabledAdminRoutes)) {
            return false;
        }

        // We are checking if we should re-enable the route
        foreach ($reEnabledAdminRoutes as $reEnabledAdminSection) {
            if (!isset(self::ADMIN_ROUTES_THAT_CAN_BE_RE_ENABLED[$reEnabledAdminSection])) {
                continue;
            }
            foreach (self::ADMIN_ROUTES_THAT_CAN_BE_RE_ENABLED[$reEnabledAdminSection] as $reEnabledAdminRoute) {
                if (false !== strpos($route, $reEnabledAdminRoute)) {
                    return true;
                }
            }
        }

        return false;
    }
}
