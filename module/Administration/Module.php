<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @copyright Copyright (c) 2005-2015 Alexandre Tranchant
 */
namespace Administration;

use Administration\Form\Element\ItemsPerPage;
use Administration\Options\ModuleOptions;
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
            ),
            'factories' => array(
                'items_per_page' => function ($sm)
                {                    
                    $config = $sm->get('Config');                    
                    return new ModuleOptions(isset($config['administration']) ? $config['administration'] : '5,7,8');
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
            )
        );
    }
}