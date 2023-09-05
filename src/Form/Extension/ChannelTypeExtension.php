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

namespace MonsieurBiz\SyliusNoCommercePlugin\Form\Extension;

use MonsieurBiz\SyliusNoCommercePlugin\Form\EventSubscriber\RemoveBaseCurrencySubscriber;
use MonsieurBiz\SyliusNoCommercePlugin\Provider\FeaturesProviderInterface;
use Sylius\Bundle\ChannelBundle\Form\Type\ChannelType;
use Sylius\Component\Core\Model\ChannelInterface;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

final class ChannelTypeExtension extends AbstractTypeExtension
{
    private FeaturesProviderInterface $featuresProvider;

    public function __construct(
        FeaturesProviderInterface $featuresProvider
    )
    {
        $this->featuresProvider = $featuresProvider;
    }

    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameters)
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /*
         * We are testing with the channel that is currently edited to know which field to remove
         * In case of a creation, the setting value is not set yet so the provider will check if the no commerce plugin is enabled in the global scope
         */
        /** @var ChannelInterface $channel */
        $channel = $options['data'];
        if (!$this->featuresProvider->isNoCommerceEnabledForChannel($channel)) {
            return;
        }

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

    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        parent::buildView($view, $form, $options);

        /** @var ChannelInterface $channel */
        $channel = $options['data'];

        $view->vars['noCommerceEnabled'] = $this->featuresProvider->isNoCommerceEnabledForChannel($channel);
    }
}
