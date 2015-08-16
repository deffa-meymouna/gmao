<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @copyright Copyright (c) 2005-2015 Alexandre Tranchant
 */
namespace Administration;

use Administration\Form\Element\ItemsPerPage;
use Administration\Options\ModuleOptions;
use Administration\Form\InputFilter\User;
use Administration\Form\InputFilter\Role;
class Module
{

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php'
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__
                )
            )
        );
    }

    public function getServiceConfig()
    {
        return array(
            'invokables' => array(
                'Administration\Form\UserForm' => 'Administration\Form\UserForm',
                'Administration\Form\RoleForm' => 'Administration\Form\RoleForm',
                'Administration\Form\InputFilter\User' => 'Administration\Form\InputFilter\User',                
            ),
            'factories' => array(
                'items_per_page' => function ($sm)
                {                    
                    $config = $sm->get('Config');                    
                    return new ModuleOptions(isset($config['administration']) ? $config['administration'] : '5,7,8');
                },
                'items_per_page_default' => function ($sm)
                {                    
                    $config = $sm->get('Config');                    
                    return new ModuleOptions(isset($config['administration']) ? $config['administration'] : 5);
                },
                'items_per_page_form' => function ($sm)
                {
                    $translator = $sm->get('translator');

                    foreach ($sm->get('items_per_page')->getItemsPerPage() as $option){
                        $integer = (int)$option;
                        if ($integer > 0){
                            $valueOptions[$option]=sprintf($translator->translate('%d items'),$option);
                        }
                    }
                    $elementOptions['value_options']=$valueOptions;
                    $element = new ItemsPerPage(null,$elementOptions);
                    return $element;
                },
                'role_input_filter' => function ($sm){
                    $roleMapper = $sm->get('RolesService');
                    $inputFilter = new Role($roleMapper);      
                    return $inputFilter;
                },
                'role_form' => function ($sm){
                    $roleForm = $sm->get('Administration\Form\RoleForm');
                    $inputFilter = $sm->get('role_input_filter');
                    $roleForm->setInputFilter($inputFilter);
                    return $roleForm;
                },
                'user_input_filter' => function ($sm){
                    $userMapper = $sm->get('UsersService');
                    $inputFilter = new User($userMapper);      
                    return $inputFilter;
                },
                'user_form' => function ($sm){
                    $userForm = $sm->get('Administration\Form\UserForm');
                    $inputFilter = $sm->get('user_input_filter');
                    $userForm->setInputFilter($inputFilter);
                    return $userForm;
                },
                'UsersService' => 'Administration\Service\Factory\UsersServiceFactory',
                'RolesService' => 'Administration\Service\Factory\RolesServiceFactory',
            )
        );
    }
}