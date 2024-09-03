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

namespace MonsieurBiz\SyliusNoCommercePlugin\Model;

interface ConfigInterface
{
    public const ROUTES_BY_GROUP = [
        /**
         * Customers & Account & Users.
         */
        'customer_admin' => [
            'sylius_admin_partial_customer',
            'sylius_admin_customer',
        ],
        'customer_shop' => [
            'sylius_shop_log',
            'sylius_shop_register',
            'sylius_shop_request_password_reset_token',
            'sylius_shop_password_reset',
            'sylius_shop_user_request_verification_token',
            'sylius_shop_user_verification',
            'sylius_shop_account',
            'sylius_shop_ajax_user_check_action',
        ],
        'customer_api' => [
            'api_customer',
            'api_register_shop_users_post_collection',
            'sylius_api_shop_authentication_token',
        ],
        /**
         * Catalog.
         */
        'catalog_admin' => [
            'sylius_admin_product',
            'sylius_admin_ajax_product',
            'sylius_admin_partial_product',
            'sylius_admin_ajax_generate_product_slug',
            'sylius_admin_partial_taxon',
            'sylius_admin_ajax_taxon',
            'sylius_admin_taxon',
            'sylius_admin_ajax_generate_taxon_slug',
            'sylius_admin_get_attribute_types',
            'sylius_admin_get_product_attributes',
            'sylius_admin_render_attribute_forms',
            'sylius_admin_inventory',
            'sylius_admin_tax_',
            'sylius_admin_api_tax_',
        ],
        'catalog_shop' => [
            'sylius_shop_partial_product',
            'sylius_shop_product',
            'sylius_shop_partial_taxon',
            'sylius_shop_partial_channel_menu_taxon_index',
        ],
        'catalog_api' => [
            'sylius_admin_api_product',
            'api_product',
            'sylius_admin_api_taxon',
            'api_taxon',
            'api_tax',
        ],
        /**
         * Addresses.
         */
        'address_shop' => [
            'sylius_shop_account_address',
            'sylius_shop_ajax_render_province_form',
        ],
        /**
         * Orders.
         */
        'order_admin' => [
            'sylius_admin_order',
            'sylius_admin_partial_order',
            'sylius_admin_customer_order',
            'sylius_admin_partial_address',
            'sylius_admin_partial_promotion',
            'sylius_admin_promotion',
            'sylius_admin_catalog_promotion',
            'sylius_admin_partial_shipment',
            'sylius_admin_ship',
            'sylius_admin_payment',
            'sylius_admin_get_payment',
            'sylius_admin_api_cart',
        ],
        'order_shop' => [
            'sylius_shop_account_order',
            'sylius_shop_order',
            'payum_',
            'sylius_paypal',
            'sylius_shop_ajax_cart',
            'sylius_shop_partial_cart',
            'sylius_shop_cart',
            'sylius_shop_checkout',
            'sylius_shop_register_after_checkout',
        ],
        'order_api' => [
            'sylius_admin_api_adjustment',
            'sylius_admin_api_order',
            'sylius_admin_api_customer_order',
            'api_order',
            'api_adjustment',
            'sylius_admin_api_promotion',
            'api_promo',
            'sylius_admin_api_ship',
            'api_ship',
            'sylius_admin_api_payment',
            'api_pay',
            'api_cart',
            'sylius_admin_api_checkout',
        ],
        /**
         * Currencies.
         */
        'currency_admin' => [
            'sylius_admin_currency',
            'sylius_admin_api_currency',
        ],
        'currency_shop' => [
            'sylius_shop_switch_currency',
        ],
        'currency_api' => [
            'api_currencies',
        ],
        /**
         * Exchange rates.
         */
        'exchange_admin' => [
            'sylius_admin_exchange',
            'sylius_admin_api_exchange',
            'api_exchange',
        ],
        'exchange_api' => [
            'api_exchange',
        ],
        /**
         * Zones.
         */
        'zone_admin' => [
            'sylius_admin_zone',
            'sylius_admin_api_zone',
        ],
        'zone_api' => [
            'api_zone',
        ],
        /**
         * Countries.
         */
        'country_admin' => [
            'sylius_admin_country',
            'sylius_admin_api_country',
        ],
        'country_api' => [
            'api_countries',
        ],
        /**
         * Province.
         */
        'province_admin' => [
            'sylius_admin_api_province',
            'sylius_admin_ajax_render_province_form',
        ],
        'province_api' => [
            'api_province',
        ],
        /**
         * Dashboard.
         */
        'dashboard' => [
            'sylius_admin_dashboard_statistics',
        ],
        /**
         * Billing data.
         */
        'billing_data' => [
            'api_shop_billing',
            'api_channels_shop',
        ],
    ];

    public function areCountriesAllowed(): bool;

    public function areCustomersAllowed(): bool;

    public function areZonesAllowed(): bool;
}
