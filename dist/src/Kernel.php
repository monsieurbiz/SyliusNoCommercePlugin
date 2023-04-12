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

namespace App;

use MonsieurBiz\SyliusNoCommercePlugin\Kernel\SyliusNoCommerceKernelTrait;
use PSS\SymfonyMockerContainer\DependencyInjection\MockerContainer;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;

final class Kernel extends BaseKernel
{
    use SyliusNoCommerceKernelTrait;

    protected function getContainerBaseClass(): string
    {
        if (class_exists(MockerContainer::class) && $this->isTestEnvironment()) {
            return MockerContainer::class;
        }

        return parent::getContainerBaseClass();
    }

    private function isTestEnvironment(): bool
    {
        return str_starts_with($this->getEnvironment(), 'test');
    }
}
