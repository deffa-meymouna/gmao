<?php
/**
 * User - User ModuleOpionsFactory to use with Zend Framework 2 MVC 2
 *
 * @link https://github.com/Alexandre-T/gmao for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Alexandre Tranchant
 * @license https://github.com/Alexandre-T/gmao/blob/master/LICENSE MIT
 * @author Alexandre Tranchant <Alexandre.Tranchant@gmail.com>
 */
namespace Administration\Service\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Administration\Service\Roles;

class RolesServiceFactory implements FactoryInterface
{
	/**
	 * (non-PHPdoc)
	 * @see \Zend\ServiceManager\FactoryInterface::createService()
	 * @return Administration\Service\Roles
	 */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $entityManager = $serviceLocator->get('doctrine.entitymanager.orm_default');
        $authorize     = $serviceLocator->get('BjyAuthorize\Service\Authorize');
        $moduleOptions = $serviceLocator->get('zfcuser_module_options');
        return new Roles($entityManager, $authorize, $moduleOptions);
    }
}