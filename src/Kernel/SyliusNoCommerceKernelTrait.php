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

use MonsieurBiz\SyliusNoCommercePlugin\Model\Config;
use MonsieurBiz\SyliusNoCommercePlugin\Model\ConfigInterface;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\Routing\RouteCollection;

trait SyliusNoCommerceKernelTrait
{
    use MicroKernelTrait {
        MicroKernelTrait::loadRoutes as parentLoadRoutes;
    }

    private array $routesToRemove = [
        // Customers & Account & Users
        'customer' => [
            'sylius_admin_partial_customer',
            'sylius_admin_customer',
            'api_customer',
            'sylius_shop_log',
            'sylius_shop_register',
            'sylius_shop_request_password_reset_token',
            'sylius_shop_password_reset',
            'sylius_shop_user_request_verification_token',
            'sylius_shop_user_verification',
            'sylius_shop_account',
            'api_register_shop_users_post_collection',
            'sylius_api_shop_authentication_token',
            'sylius_shop_ajax_user_check_action',
        ],

        // Products
        'product' => [
            'sylius_admin_product',
            'sylius_admin_api_product',
            'sylius_admin_ajax_product',
            'sylius_shop_partial_product',
            'sylius_shop_product',
            'sylius_admin_partial_product',
            'sylius_admin_ajax_generate_product_slug',
            'api_product',
        ],

        // Taxons
        'taxon' => [
            'sylius_admin_partial_taxon',
            'sylius_admin_ajax_taxon',
            'sylius_admin_taxon',
            'sylius_admin_api_taxon',
            'sylius_shop_partial_taxon',
            'sylius_admin_ajax_generate_taxon_slug',
            'sylius_shop_partial_channel_menu_taxon_index',
            'api_taxon',
        ],

        // Checkout
        'checkout' => [
            'sylius_admin_api_checkout',
            'sylius_shop_checkout',
            'sylius_shop_register_after_checkout',
        ],

        // Addresses
        'address' => [
            'sylius_shop_account_address',
            'sylius_admin_partial_address',
        ],

        // Orders
        'order' => [
            'sylius_admin_order',
            'sylius_admin_api_order',
            'sylius_shop_account_order',
            'sylius_shop_order',
            'sylius_admin_partial_order',
            'sylius_admin_customer_order',
            'sylius_admin_api_customer_order',
            'api_order',
        ],

        // Adjustments
        'adjustment' => [
            'sylius_admin_api_adjustment',
            'sylius_shop_ajax_render_province_form',
            'api_adjustment',
        ],

        // Promotions
        'promotion' => [
            'sylius_admin_partial_promotion',
            'sylius_admin_promotion',
            'sylius_admin_api_promotion',
            'api_promo',
        ],

        // Shipping and Shipments
        'shipment' => [
            'sylius_admin_partial_shipment',
            'sylius_admin_ship',
            'sylius_admin_api_ship',
            'api_ship',
        ],

        // Inventory
        'inventory' => [
            'sylius_admin_inventory',
        ],

        // Attributes
        'attribute' => [
            'sylius_admin_get_attribute_types',
            'sylius_admin_get_product_attributes',
            'sylius_admin_render_attribute_forms',
        ],

        // Payments
        'payment' => [
            'sylius_admin_payment',
            'sylius_admin_get_payment',
            'payum_',
            'sylius_admin_api_payment',
            'api_pay',
        ],

        // PayPal
        'paypal' => [
            'sylius_paypal',
        ],

        // Taxes
        'tax' => [
            'sylius_admin_tax_',
            'sylius_admin_api_tax_',
            'api_tax',
        ],

        // Currencies
        'currency' => [
            'sylius_admin_currency',
            'sylius_admin_api_currency',
            'sylius_shop_switch_currency',
            'api_currencies',
        ],

        // Exchange rates
        'exchange' => [
            'sylius_admin_exchange',
            'sylius_admin_api_exchange',
            'api_exchange',
        ],

        // Zones
        'zone' => [
            'sylius_admin_zone',
            'sylius_admin_api_zone',
            'api_zone',
        ],

        // Countries
        'country' => [
            'sylius_admin_country',
            'sylius_admin_api_country',
            'api_countries',
        ],

        // Provinces
        'province' => [
            'sylius_admin_api_province',
            'sylius_admin_ajax_render_province_form',
            'api_province',
        ],

        // Carts
        'cart' => [
            'sylius_admin_api_cart',
            'sylius_shop_ajax_cart',
            'sylius_shop_partial_cart',
            'sylius_shop_cart',
            'api_cart',
        ],

        // Dashboard
        'dashboard' => [
            'sylius_admin_dashboard_statistics',
        ],

        // Others
        'other' => [
            'api_shop_billing',
            'api_channels_shop',
        ],
    ];

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
                    $route->setCondition('not context.checkNoCommerce(params)');
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
    private function getRoutesToRemove(): array
    {
        $config = $this->getConfig();
        $routesToRemove = [];

        if ($config->areCustomersAllowed()) {
            unset($this->routesToRemove['customer']);
        }

        if ($config->areZonesAllowed()) {
            unset($this->routesToRemove['zone']);
        }

        if ($config->areZonesAllowed() || $config->areCountriesAllowed()) {
            unset($this->routesToRemove['country']);
        }

        foreach ($this->routesToRemove as $routes) {
            $routesToRemove = array_merge($routesToRemove, $routes);
        }

        return $routesToRemove;
    }
}
