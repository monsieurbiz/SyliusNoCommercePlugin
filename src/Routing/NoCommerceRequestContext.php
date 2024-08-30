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

namespace MonsieurBiz\SyliusNoCommercePlugin\Routing;

use Exception;
use MonsieurBiz\SyliusNoCommercePlugin\Provider\FeaturesProviderInterface;
use Symfony\Component\Routing\RequestContext as BaseRequestContext;

final class NoCommerceRequestContext extends BaseRequestContext
{
    private BaseRequestContext $decorated;

    private FeaturesProviderInterface $featuresProvider;

    public function __construct(
        BaseRequestContext $decorated,
        FeaturesProviderInterface $featuresProvider
    ) {
        parent::__construct(
            $decorated->getBaseUrl(),
            $decorated->getMethod(),
            $decorated->getHost(),
            $decorated->getScheme(),
            $decorated->getHttpPort(),
            $decorated->getHttpsPort(),
            $decorated->getPathInfo(),
            $decorated->getQueryString()
        );
        $this->decorated = $decorated;
        $this->featuresProvider = $featuresProvider;
    }

    public function checkNoCommerce(): bool
    {
        return $this->featuresProvider->isNoCommerceEnabledForChannel();
    }

    /**
     * @throws Exception
     *
     * @return mixed
     */
    public function __call(string $name, array $arguments)
    {
        $callback = [$this->decorated, $name];
        if (\is_callable($callback)) {
            return \call_user_func($callback, ...$arguments);
        }

        throw new Exception(\sprintf('Method %s not found for class "%s"', $name, \get_class($this->decorated)));
    }
}
