<?php
namespace Reseau\Form;

use Zend\Form\Form;

class ReservationIpForm extends Form
{
    public function __construct()
    {
        parent::__construct();
        $this->setAttribute('method', 'post');

        $this->add(array(
        		'name' => 'ip',

        		'attributes' => array(
        				'type'  => 'text',
        		),

        		'options' => array(
        				'label' => 'IP à réserver',
        				'column-size' => 'sm-9',
        				'label_attributes' => array('class' => 'col-sm-3')
        		),
        ));

        $this->add(array(
            'name' => 'libelle',

            'attributes' => array(
                'type'  => 'text',
            	'placeholder'  => 'Entrez les observations sur cette IP',
            ),

        	'options' => array(
        		'label' => 'Libellé',
        		'column-size' => 'sm-9',
        		'label_attributes' => array('class' => 'col-sm-3')
        	),
        ));

        $this->add(array(
            'name' => 'description',
            'attributes' => array(
                'type'  => 'textarea',
            	'placeholder' => 'Entrez une description optionnelle',
            ),
        	'options' => array(
        		'label' => 'Description',
        		'column-size' => 'sm-9',
        		'label_attributes' => array('class' => 'col-sm-3')
        	),
        ));

        $this->add(array(
        	'name' => 'interface',
        	'attributes' => array(
       			'type'  => 'number',
        		'placeholder' => '0',
        		'min' => 0,
        	),

        	'options' => array(
        		'label' => 'Interface',
        		'column-size' => 'sm-9',
        		'label_attributes' => array('class' => 'col-sm-3')
       		),
        ));

        $this->add(array(
        	'name' => 'nat',
       		'type'  => 'checkbox',

        	'options' => array(
        		'column-size' => 'sm-9 col-sm-offset-3',
        		'label' => 'Adresse «Natée»',
        		'use_hidden_element' => true,
        		'checked_value' => '1',
        		'unchecked_value' => '0',
       		),
        ));

        $this->add(array(
            'name' => 'csrf',
            'type' => 'Zend\Form\Element\Csrf',
            'options' => array(
                'csrf_options' => array(
                    'timeout' => 3600,
                )
            )
        ));

        $this->add(array(
            'name' => 'submit',
    		'type' => 'button',
    		'attributes' => array('type' => 'submit'),
    		'options' => array(
    			'label' => 'Réserver l\'adresse IP',
    			'column-size' => 'sm-9 col-sm-offset-3'
    		)
        ));
    }
}
