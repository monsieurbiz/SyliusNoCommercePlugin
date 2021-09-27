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

namespace MonsieurBiz\SyliusNoCommercePlugin\Form\Extension;

use MonsieurBiz\SyliusNoCommercePlugin\Form\EventSubscriber\RemoveBaseCurrencySubscriber;
use Sylius\Bundle\ChannelBundle\Form\Type\ChannelType;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormBuilderInterface;

final class ChannelTypeExtension extends AbstractTypeExtension
{
    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameters)
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->remove('shopBillingData')
            ->remove('menuTaxon')
            ->remove('skippingShippingStepAllowed')
            ->remove('skippingPaymentStepAllowed')
            ->remove('baseCurrency')
            ->remove('currencies')
            ->remove('defaultTaxZone')
            ->remove('taxCalculationStrategy')
            ->addEventSubscriber(new RemoveBaseCurrencySubscriber())
        ;
    }

    public static function getExtendedTypes(): array
    {
        return [ChannelType::class];
    }
}
