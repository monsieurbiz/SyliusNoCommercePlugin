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

namespace MonsieurBiz\SyliusNoCommercePlugin\Routing;

use Symfony\Component\Config\Exception\LoaderLoadException;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\Config\Resource\ResourceInterface;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\RouteCollectionBuilder as BaseRouteCollectionBuilder;

class RouteCollectionBuilder extends BaseRouteCollectionBuilder
{
    private ?LoaderInterface $loader;
    private array $resources = [];

    private array $routesToRemove = [
        // Products
        'sylius_admin_product',
        'sylius_admin_api_product',
        'sylius_admin_ajax_product',
        'sylius_shop_partial_product',
        'sylius_shop_product',
        'sylius_admin_partial_product',
        'sylius_admin_ajax_generate_product_slug',

        // Taxons
        'sylius_admin_partial_taxon',
        'sylius_admin_ajax_taxon',
        'sylius_admin_taxon',
        'sylius_admin_api_taxon',
        'sylius_shop_partial_taxon',
        'sylius_admin_ajax_generate_taxon_slug',
        'sylius_shop_partial_channel_menu_taxon_index',

        // Checkout
        'sylius_admin_api_checkout',
        'sylius_shop_checkout',
        'sylius_shop_register_after_checkout',

        // Addresses
        'sylius_shop_account_address',
        'sylius_admin_partial_address',

        // orders
        'sylius_admin_order',
        'sylius_admin_api_order',
        'sylius_shop_account_order',
        'sylius_shop_order',
        'sylius_admin_partial_order',
        'sylius_admin_customer_order',
        'sylius_admin_api_customer_order',

        // Adjustments
        'sylius_admin_api_adjustment',
        'sylius_shop_ajax_render_province_form',

        // Promotions
        'sylius_admin_partial_promotion',
        'sylius_admin_promotion',
        'sylius_admin_api_promotion',

        // Shipping and Shipments
        'sylius_admin_partial_shipment',
        'sylius_admin_ship',
        'sylius_admin_api_ship',

        // Inventory
        'sylius_admin_inventory',

        // Attributes
        'sylius_admin_get_attribute_types',
        'sylius_admin_get_product_attributes',
        'sylius_admin_render_attribute_forms',

        // Payments
        'sylius_admin_payment',
        'sylius_admin_get_payment',
        'payum_',
        'sylius_admin_api_payment',

        // Taxes
        'sylius_admin_tax_',
        'sylius_admin_api_tax_',

        // Currencies
        'sylius_admin_currency',
        'sylius_admin_api_currency',
        'sylius_shop_switch_currency',

        // Exchange rates
        'sylius_admin_exchange',
        'sylius_admin_api_exchange',

        // Zones
        'sylius_admin_zone',
        'sylius_admin_api_zone',

        // Countries
        'sylius_admin_country',
        'sylius_admin_api_country',

        // Provinces
        'sylius_admin_api_province',

        // Carts
        'sylius_admin_api_cart',
        'sylius_shop_ajax_cart',
        'sylius_shop_partial_cart',
        'sylius_shop_cart',

        // Dashboard
        'sylius_admin_dashboard_statistics',
    ];

    /**
     * RouteCollectionBuilder constructor.
     *
     * @param LoaderInterface|null $loader
     */
    public function __construct(LoaderInterface $loader = null)
    {
        parent::__construct($loader);
        $this->loader = $loader;
    }

    /**
     * @param mixed $resource
     * @param string $prefix
     * @param string|null $type
     *
     * @throws LoaderLoadException
     *
     * @return RouteCollectionBuilder|BaseRouteCollectionBuilder
     */
    public function import($resource, $prefix = '/', $type = null)
    {
        /** @var RouteCollection[] $collections */
        $collections = $this->load($resource, $type);

        // create a builder from the RouteCollection
        $builder = $this->createBuilder();

        foreach ($collections as $collection) {
            foreach ($collection->all() as $name => $route) {
                foreach ($this->routesToRemove as $routeToRemove) {
                    if (false !== strpos($name, $routeToRemove)) {
                        $route->setCondition('1 == 0');
                    }
                }
                $builder->addRoute($route, $name);
            }

            foreach ($collection->getResources() as $routeResource) {
                $builder->addResource($routeResource);
            }
        }

        // mount into this builder
        $this->mount($prefix, $builder);

        return $builder;
    }

    /**
     * Returns a RouteCollectionBuilder that can be configured and then added with mount().
     *
     * @return RouteCollectionBuilder
     */
    public function createBuilder()
    {
        return new self($this->loader);
    }

    /**
     * Adds a resource for this collection.
     *
     * @return $this
     */
    private function addResource(ResourceInterface $resource): self
    {
        $this->resources[] = $resource;

        return $this;
    }

    /**
     * Finds a loader able to load an imported resource and loads it.
     *
     * @param mixed $resource A resource
     * @param string|null $type The resource type or null if unknown
     *
     * @throws LoaderLoadException If no loader is found
     *
     * @return RouteCollection[]
     */
    private function load($resource, string $type = null): array
    {
        if (null === $this->loader) {
            throw new \BadMethodCallException('Cannot import other routing resources: you must pass a LoaderInterface when constructing RouteCollectionBuilder.');
        }

        if ($this->loader->supports($resource, $type)) {
            $collections = $this->loader->load($resource, $type);

            return \is_array($collections) ? $collections : [$collections];
        }

        $resolver = $this->loader->getResolver();
        if (false === $loader = $resolver->resolve($resource, $type)) {
            throw new LoaderLoadException($resource, null, null, null, $type);
        }

        $collections = $loader->load($resource, $type);

        return \is_array($collections) ? $collections : [$collections];
    }
}
