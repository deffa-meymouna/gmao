<?php
/**
 * Machine - Machine ModuleOpionsFactory to use with Zend Framework 2 MVC 2
 *
 * @link https://github.com/Alexandre-T/gmao for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Alexandre Tranchant
 * @license https://github.com/Alexandre-T/gmao/blob/master/LICENSE MIT
 * @author Alexandre Tranchant <Alexandre.Tranchant@gmail.com>
 */

namespace Reseau\Service\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Reseau\Entity\Machines;

class MachineService implements FactoryInterface
{
	/**
	 * (non-PHPdoc)
	 * @see \Zend\ServiceManager\FactoryInterface::createService()
	 * @return Reseau\Entity\Machines
	 */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $entityManager = $serviceLocator->get('doctrine.entitymanager.orm_default');
        return  new Machines($entityManager);
    }
}
