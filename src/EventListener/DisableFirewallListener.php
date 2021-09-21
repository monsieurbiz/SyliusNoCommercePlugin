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

namespace MonsieurBiz\SyliusNoCommercePlugin\EventListener;

use MonsieurBiz\SyliusSettingsPlugin\Settings\SettingsInterface;
use Sylius\Component\Channel\Context\ChannelContextInterface;
use Symfony\Bundle\SecurityBundle\Security\FirewallMap;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class DisableFirewallListener
{
    private FirewallMap $firewallContext;
    private SettingsInterface $nocommerceSettings;
    private ChannelContextInterface $channelContext;

    public function __construct(
        FirewallMap $firewallContext,
        SettingsInterface $nocommerceSettings,
        ChannelContextInterface $channelContext
    ) {
        $this->firewallContext = $firewallContext;
        $this->nocommerceSettings = $nocommerceSettings;
        $this->channelContext = $channelContext;
    }

    public function __invoke(RequestEvent $event): void
    {
        $currentChannel = $this->channelContext->getChannel();
        $disabledFirewall = $this->nocommerceSettings->getCurrentValue($currentChannel, null, 'disabled_firewall_contexts') ?? [];
        $firewallContextName = $this->getFirewallContextName($event->getRequest());
        if (\in_array($firewallContextName, $disabledFirewall, true)) {
            throw new NotFoundHttpException('Route not found');
        }
    }

    private function getFirewallContextName(Request $request): string
    {
        $firewallConfig = $this->firewallContext->getFirewallConfig($request);
        if (null === $firewallConfig) {
            return '';
        }

        return $firewallConfig->getContext() ?? $firewallConfig->getName();
    }
}
