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

namespace App\Entity\Channel;

use Doctrine\ORM\Mapping as ORM;
use Sylius\Component\Core\Model\Channel as BaseChannel;

#[ORM\Entity()]
#[ORM\Table(name: 'sylius_channel')]
#[ORM\AssociationOverrides([
    new ORM\AssociationOverride(
        name: 'baseCurrency',
        joinColumns: new ORM\JoinColumn(name: 'base_currency_id', referencedColumnName: 'id', nullable: true)
    )]
)]
/**
 * @ORM\Entity
 * @ORM\Table(name="sylius_channel")
 * @ORM\AssociationOverrides({
 *     @ORM\AssociationOverride(name="baseCurrency",
 *         joinColumns=@ORM\JoinColumn(
 *             name="base_currency_id", referencedColumnName="id", nullable=true
 *         )
 *     )
 * })
 */
class Channel extends BaseChannel
{
}
