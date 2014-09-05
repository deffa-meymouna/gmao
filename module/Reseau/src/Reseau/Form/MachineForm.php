<?php
namespace Reseau\Form;

use Zend\Form\Form;

class MachineForm extends Form
{
    public function __construct()
    {
        parent::__construct();
        $this->setAttribute('method', 'post');

        $this->add(array(
            'name' => 'libelle',

            'attributes' => array(
                'type'  => 'text',
            	'placeholder'  => 'Entrez le nom de la machine',
            ),

        	'options' => array(
        		'label' => 'Nom',
        		'column-size' => 'sm-10',
        		'label_attributes' => array('class' => 'col-sm-2')
        	),
        ));

        $this->add(array(
            'name' => 'description',
            'attributes' => array(
                'type'  => 'textarea',
            	'placeholder' => 'Entrez une description de cette réseau',
            ),
        	'options' => array(
        		'label' => 'Description',
        		'column-size' => 'sm-10',
        		'label_attributes' => array('class' => 'col-sm-2')
        	),
        ));

        $this->add(array(
        	'name' => 'interface',
        	'attributes' => array(
       			'type'  => 'number',
        		'placeholder' => '24',
        		'min' => 1,
        		'max' => 24,
        	),

        	'options' => array(

        		'label' => 'Interfaces',
        		'column-size' => 'sm-10',
        		'label_attributes' => array('class' => 'col-sm-2')
       		),
        ));

        $this->add(array(
        	'name' => 'type',
        	'attributes' => array(
       			'type'  => 'text',
        		'placeholder' => 'Firewall, Serveur Web, poste bureautique...',
        	),

        	'options' => array(
        		'label' => 'Type de machine',
        		'column-size' => 'sm-10',
        		'label_attributes' => array('class' => 'col-sm-2')
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
    			'label' => 'Créer le réseau',
    			'column-size' => 'sm-10 col-sm-offset-2'
    		)
        ));
    }
}
