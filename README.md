<p align="center">
    <a href="https://monsieurbiz.com" target="_blank">
        <img src="https://monsieurbiz.com/logo.png" width="250px" alt="Monsieur Biz logo" />
    </a>
    &nbsp;&nbsp;&nbsp;&nbsp;
    <a href="https://monsieurbiz.com/agence-web-experte-sylius" target="_blank">
        <img src="https://demo.sylius.com/assets/shop/img/logo.png" width="200px" alt="Sylius logo" />
    </a>
    <br/>
    <img src="https://monsieurbiz.com/assets/images/sylius_badge_extension-artisan.png" width="100" alt="Monsieur Biz is a Sylius Extension Artisan partner">
</p>

<h1 align="center">No Commerce for Sylius</h1>

[![No Commerce Plugin license](https://img.shields.io/github/license/monsieurbiz/SyliusNoCommercePlugin)](https://github.com/monsieurbiz/SyliusNoCommercePlugin/blob/master/LICENSE.txt)
![Tests](https://img.shields.io/github/workflow/status/monsieurbiz/SyliusNoCommercePlugin/Tests/master?label=tests&logo=github)

This plugin disables the e-commerce parts of Sylius.  
Basically it disables the routes and updates the admin and frontend templates.

## Installation

```bash
composer require monsieurbiz/sylius-no-commerce-plugin="1.0.x-dev" --no-scripts
```

Change your `config/bundles.php` file to add the line for the plugin : 

```php
<?php

return [
    //..
    MonsieurBiz\SyliusNoCommercePlugin\MonsieurBizSyliusNoCommercePlugin::class => ['all' => true],
];
```

Then create the config file in `config/packages/monsieurbiz_sylius_nocommerce_plugin.yaml` :

```yaml
imports:
    - { resource: "@MonsieurBizSyliusNoCommercePlugin/Resources/config/config.yaml" }

monsieurbiz_sylius_nocommerce:
    config:
        allow_countries: false
        allow_customers: false
        allow_zones: false
```

You can allow different sections by changing the parameters to `true`.

Add some annotations to your `src/Entity/Channel/Channel.php` entity to prevent error during Channel saving:

```diff
  /**
   * @ORM\Entity
   * @ORM\Table(name="sylius_channel")
+  * @ORM\AssociationOverrides({
+  *     @ORM\AssociationOverride(name="baseCurrency",
+  *         joinColumns=@ORM\JoinColumn(
+  *             name="base_currency_id", referencedColumnName="id", nullable=true
+  *         )
+  *     )
+  * })
   */
  class Channel extends BaseChannel
```

Use a different trait for your `src/Kernel.php`:

```diff
-     use MicroKernelTrait;
+     use SyliusNoCommerceKernelTrait;
```

(don't forget the `use MonsieurBiz\SyliusNoCommercePlugin\Kernel\SyliusNoCommerceKernelTrait;` statement or course).

Copy the templates we override:

```bash
cp -Rv vendor/monsieurbiz/sylius-no-commerce-plugin/src/Resources/templates/* templates/
```

Create the new migrations, and run them:

```
./bin/console doctrine:migrations:diff
./bin/console doctrine:migrations:migrate
```

## Contributing

You can open an issue or a Pull Request if you want! 😘  
Thank you!
