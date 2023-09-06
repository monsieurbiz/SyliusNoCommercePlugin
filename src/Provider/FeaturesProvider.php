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
    private ChannelContextInterface $channelContext;

    private SettingsInterface $nocommerceSettings;

    public function __construct(
        ChannelContextInterface $channelContext,
        SettingsInterface $nocommerceSettings
    ) {
        $this->channelContext = $channelContext;
        $this->nocommerceSettings = $nocommerceSettings;
    }

    public function isNoCommerceEnabledForChannel(ChannelInterface $channel = null): bool
    {
        try {
            if (null === $channel) {
                $channel = $this->channelContext->getChannel();
            }
            // In case we are getting a channel that does not exists yet we return null to have the channel set properly
            if (null === $channel->getId()) {
                return true;
            }
        } catch (Exception $exception) {
            return false;
        }

        return (bool) $this->nocommerceSettings->getCurrentValue($channel, null, 'enabled');
    }
}
