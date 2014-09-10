<?php
namespace Reseau\Form;

use Zend\InputFilter\InputFilter;

class ReservationIpFilter extends InputFilter
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
            'required' => false,
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
        		'name' => 'ip',
        		'required' => true,
        		'filters' => array(
        				array('name' => 'StripTags'),
        				array('name' => 'StringTrim'),
        		),
        		'validators' => array(
        				array(
        						'name' => 'Regex',
        						'options' => array(
        								'pattern' => '/^\\b(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\\b$/',
        						),
        				),
        		),
        ));
        $this->add(array(
        		'name' => 'interface',
        		'filters' => array(
        				array('name' => 'StripTags'),
        				array('name' => 'StringTrim'),
        				//array('name' => 'Null'),
        				array('name' => 'Int'),
        		),
        		'validators' => array(
        				array(
        						'name' => 'Int',
        						'options' => array(
        							'min' => 0,
        							'max' => 127
        						)
        				),
        		),
        ));
        $this->add(array(
        		'name' => 'nat',
        		'filters' => array(
        				array('name' => 'StripTags'),
        				array('name' => 'StringTrim'),
        				array('name' => 'Int'),
        		),
        		'validators' => array(
        				array(
        						'name' => 'InArray',
        						'options' => array(
        								'haystack' => array(0,1),
        						)
        				),
        		),
        ));
    }
}
