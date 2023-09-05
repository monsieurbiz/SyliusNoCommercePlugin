<?php

/*
 * This file is part of SyliusNoCommercePlugin corporate website.
 *
 * (c) SyliusNoCommercePlugin <sylius+syliusnocommerceplugin@monsieurbiz.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace MonsieurBiz\SyliusNoCommercePlugin\Provider;

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
    )
    {
        $this->channelContext = $channelContext;
        $this->nocommerceSettings = $nocommerceSettings;
    }

    public function isNoCommerceEnabledForChannel(?ChannelInterface $channel = null): bool
    {
        if (null === $channel) {
            $channel = $this->channelContext->getChannel();
        }
        // In case we are getting a channel that does not exists yet we pass null as the channel to retrieve the setting value of the global scope
        if (null === $channel->getId()) {
            $channel = null;
        }

        return $this->nocommerceSettings->getCurrentValue($channel, null, 'enabled');
    }

}
