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

namespace MonsieurBiz\SyliusNoCommercePlugin\Form\Type\Settings;

use MonsieurBiz\SyliusNoCommercePlugin\Firewall\RegistryInterface;
use MonsieurBiz\SyliusSettingsPlugin\Form\AbstractSettingsType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;

class NoCommerceType extends AbstractSettingsType
{
    private const FIREWALLS_NAMES_CANNOT_BE_DISABLED = [
        'admin',
    ];

    private RegistryInterface $firewallRegistry;

    public function __construct(RegistryInterface $firewallRegistry)
    {
        $this->firewallRegistry = $firewallRegistry;
    }

    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $choices = [];
        foreach ($this->firewallRegistry as $firewall) {
            $firewallConfig = $firewall->getConfig();
            $firewallName = $firewallConfig->getName();
            if (\in_array($firewallName, self::FIREWALLS_NAMES_CANNOT_BE_DISABLED, true)) {
                continue;
            }
            $choices[$firewallName] = $firewallConfig->getContext() ?? $firewallName;
        }

        $this->addWithDefaultCheckbox($builder, 'disabled_firewall_contexts', ChoiceType::class, [
            'label' => 'monsieurbiz.nocommerce.ui.form.field.disabled_firewall_contexts.label',
            'required' => false,
            'multiple' => true,
            'choices' => $choices,
        ]);
    }
}
