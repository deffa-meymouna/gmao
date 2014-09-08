<?php
namespace Reseau\Form;

use Zend\InputFilter\InputFilter;

class MachineFilter extends InputFilter
{
    public function __construct()
    {
        $this->add(array(
            'name' => 'libelle',
            'required' => true,
            'filters' => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim'),
            ),
            'validators' => array(
                array(
                    'name' => 'StringLength',
                    'options' => array(
                        'encoding' => 'UTF-8',
                        'min' => 3,
                        'max' => 32,
                    ),
                ),
                /*array(
                    'name' => 'Regex',
                    'options' => array(
                        'pattern' => '/^[ña-zÑA-Z][ña-zÑA-Z0-9\ \_\-]+$/',
                    ),
                ),*/
               /* array(
                    'name' => 'DoctrineModule\Validator\NoObjectExists',
                    'options' => array(
                        'object_repository' => $entityManager->getRepository('CsnUser\Entity\Role'),
                        'fields' => 'name'
                    ),
                ),*/
            ),
        ));

        $this->add(array(
            'name' => 'description',
            'filters' => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim'),
            ),
            'validators' => array(
                array(
                    'name' => 'StringLength',
                    'options' => array(
                        'encoding' => 'UTF-8',
                    ),
                ),
            ),
        ));
        $this->add(array(
        		'name' => 'interface',
        		'required' => true,
        		'filters' => array(
        				array('name' => 'StripTags'),
        				array('name' => 'StringTrim'),
        		),
        		'validators' => array(
        				array(
        						'name' => 'Int',
        						'options' => array(
        								'min' => 1,
        								'max' => 24
        						),
        				),
        		),
        ));

        $this->add(array(
        		'name' => 'type',
        		'required' => true,
        		'filters' => array(
        				array('name' => 'StripTags'),
        				array('name' => 'StringTrim'),
        		),
        		'validators' => array(
        				array(
        						'name' => 'StringLength',
        						'options' => array(
        								'encoding' => 'UTF-8',
        								'min' => 3,
        								'max' => 32,
        						),
        				),
        		),
        ));
    }
}
