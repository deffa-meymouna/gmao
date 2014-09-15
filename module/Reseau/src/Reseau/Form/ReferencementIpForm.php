<?php
namespace Reseau\Form;

use Zend\Form\Form;

class ReferencementIpForm extends Form
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
        				'label' => 'IP à référencer',
        				'column-size' => 'sm-9',
        				'label_attributes' => array('class' => 'col-sm-3')
        		),
        ));

        $this->add(array(
        		'name' => 'mac_libelle',

        		'attributes' => array(
        				'type'  => 'text',
        				'placeholder'  => 'Entrez le nom de la machine',
        		),

        		'options' => array(
        				'label' => 'Machine',
        				'column-size' => 'sm-9',
        				'label_attributes' => array('class' => 'col-sm-3')
        		),
        ));


        $this->add(array(
            'name' => 'ip_libelle',

            'attributes' => array(
                'type'  => 'text',
            	'placeholder'  => 'Entrez les observations sur cette IP',
            ),

        	'options' => array(
        		'label' => 'Observations',
        		'column-size' => 'sm-9',
        		'label_attributes' => array('class' => 'col-sm-3')
        	),
        ));

        $this->add(array(
            'name' => 'ip_description',
            'attributes' => array(
                'type'  => 'textarea',
            	'placeholder' => 'Entrez une description optionnelle de l\'IP',
            ),
        	'options' => array(
        		'label' => 'Description',
        		'column-size' => 'sm-9',
        		'label_attributes' => array('class' => 'col-sm-3')
        	),
        ));

        $this->add(array(
        		'name' => 'mac_description',
        		'attributes' => array(
        				'type'  => 'textarea',
        				'placeholder' => 'Entrez une description de cette machine',
        		),
        		'options' => array(
        				'label' => 'Description',
        				'column-size' => 'sm-9',
        				'label_attributes' => array('class' => 'col-sm-3')
        		),
        ));

        $this->add(array(
        		'name' => 'mac_interface',
        		'attributes' => array(
        				'type'  => 'number',
        				'placeholder' => '24',
        				'min' => 1,
        				'max' => 24,
        		),

        		'options' => array(

        				'label' => 'Interfaces',
        				'column-size' => 'sm-9',
        				'label_attributes' => array('class' => 'col-sm-3')
        		),
        ));

        $this->add(array(
        		'name' => 'mac_type',
        		'attributes' => array(
        				'type'  => 'text',
        				'placeholder' => 'Firewall, Serveur Web, poste bureautique...',
        		),

        		'options' => array(
        				'label' => 'Type de machine',
        				'column-size' => 'sm-9',
        				'label_attributes' => array('class' => 'col-sm-3')
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
    			'label' => 'Référencer cette machine et cette IP',
    			'column-size' => 'sm-9 col-sm-offset-3'
    		)
        ));
    }
}
