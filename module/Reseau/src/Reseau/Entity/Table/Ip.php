<?php
/**
 * Reseau - Reseau Entity to use with Doctrine
 *
 * @link https://github.com/Alexandre-T/gmao for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Alexandre Tranchant
 * @license https://github.com/Alexandre-T/gmao/blob/master/LICENSE MIT
 * @author Alexandre Tranchant <Alexandre.Tranchant@gmail.com>
 */

namespace Reseau\Entity\Table;

use Doctrine\ORM\Mapping as ORM;
use Reseau\Entity\Abs\Ip as IpAbstract;

/**
 * Doctrine ORM implementation of Reseau entity
 *
 * @ORM\Entity
 * @ORM\Table(name="`te_ip_ip`")
 */
class Ip extends IpAbstract
{
}
