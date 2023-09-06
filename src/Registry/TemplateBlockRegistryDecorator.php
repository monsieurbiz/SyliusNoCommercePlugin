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

namespace MonsieurBiz\SyliusNoCommercePlugin\Registry;

use Laminas\Stdlib\SplPriorityQueue;
use MonsieurBiz\SyliusNoCommercePlugin\Provider\FeaturesProviderInterface;
use Sylius\Bundle\UiBundle\Registry\TemplateBlock;
use Sylius\Bundle\UiBundle\Registry\TemplateBlockRegistryInterface;

final class TemplateBlockRegistryDecorator implements TemplateBlockRegistryInterface
{
    private TemplateBlockRegistryInterface $templateBlockRegistry;

    private FeaturesProviderInterface $featuresProvider;

    /** @var array|array[] */
    private array $disableEvents = [
        'sylius.admin.dashboard.content' => [
            'before_header_legacy',
            'after_header_legacy',
            'statistics',
            'after_statistics_legacy',
            'menu',
            'after_menu_legacy',
            'latest',
            'after_content_legacy',
        ],
        'sylius.shop.layout.header.grid' => [
            'cart',
        ],
        'sylius.shop.layout.header' => [
            'menu',
        ],
        'sylius.shop.homepage' => [
            'latest_products',
            'latest_products_carousel',
            'products_grid',
        ],
        'sylius.admin.customer.show.content' => [
            'statistics',
        ],
        'sylius.admin.customer.show.address' => [
            'header',
            'content',
        ],
        'sylius.admin.layout.topbar_left' => [
            'search',
        ],
    ];

    public function __construct(
        TemplateBlockRegistryInterface $templateBlockRegistry,
        FeaturesProviderInterface $featuresProvider
    ) {
        $this->templateBlockRegistry = $templateBlockRegistry;
        $this->featuresProvider = $featuresProvider;
    }

    public function findEnabledForEvents(array $eventNames): array
    {
        // No need to sort blocks again if there's only one event because we have them sorted already
        if (1 === \count($eventNames)) {
            $eventName = reset($eventNames);

            $arrayBlocks = $this->all()[$eventName] ?? [];
            $arrayBlocks = $this->removeNoCommerceBlocks($eventName, $arrayBlocks);

            return array_values(array_filter(
                $arrayBlocks,
                static fn (TemplateBlock $templateBlock): bool => $templateBlock->isEnabled(),
            ));
        }

        $enabledFinalizedTemplateBlocks = array_filter(
            $this->findFinalizedForEvents($eventNames),
            static fn (TemplateBlock $templateBlock): bool => $templateBlock->isEnabled(),
        );

        $templateBlocksPriorityQueue = new SplPriorityQueue();
        foreach ($enabledFinalizedTemplateBlocks as $templateBlock) {
            $templateBlocksPriorityQueue->insert($templateBlock, $templateBlock->getPriority());
        }

        /** @phpstan-ignore-next-line */
        return $templateBlocksPriorityQueue->toArray();
    }

    public function all(): array
    {
        return $this->templateBlockRegistry->all();
    }

    private function findFinalizedForEvents(array $eventNames): array
    {
        /**
         * @var TemplateBlock[] $finalizedTemplateBlocks
         *
         * @psalm-var array<string, TemplateBlock> $finalizedTemplateBlocks
         */
        $finalizedTemplateBlocks = [];
        $reversedEventNames = array_reverse($eventNames);

        foreach ($reversedEventNames as $eventName) {
            $templateBlocks = $this->all()[$eventName] ?? [];
            $templateBlocks = $this->removeNoCommerceBlocks($eventName, $templateBlocks);
            foreach ($templateBlocks as $blockName => $templateBlock) {
                if (\array_key_exists($blockName, $finalizedTemplateBlocks)) {
                    $templateBlock = $finalizedTemplateBlocks[$blockName]->overwriteWith($templateBlock);
                }

                $finalizedTemplateBlocks[$blockName] = $templateBlock;
            }
        }

        return $finalizedTemplateBlocks;
    }

    /**
     * @return TemplateBlock[]
     */
    private function removeNoCommerceBlocks(string $eventName, array $arrayBlocks): array
    {
        // Remove block from no Commerce
        if ($this->featuresProvider->isNoCommerceEnabledForChannel()) {
            if (isset($this->disableEvents[$eventName])) {
                foreach ($this->disableEvents[$eventName] as $block) {
                    unset($arrayBlocks[$block]);
                }
            }
        }

        return $arrayBlocks;
    }
}
