<?php
namespace Reseau\Form;

use Zend\Form\Form;

class ReseauForm extends Form
{
    public function __construct()
    {
        parent::__construct();
        $this->setAttribute('method', 'post');

        $this->add(array(
            'name' => 'libelle',

            'attributes' => array(
                'type'  => 'text',
            	'placeholder'  => 'Entrez le libellé du réseau',
            ),

        	'options' => array(
        		'label' => 'Libellé',
        		'column-size' => 'sm-10',
        		'label_attributes' => array('class' => 'col-sm-2')
        	),
        ));

        $this->add(array(
            'name' => 'description',
            'attributes' => array(
                'type'  => 'textarea',
            	'placeholder' => 'Entrez une description précise de ce réseau',
            ),
        	'options' => array(
        		'label' => 'Description',
        		'column-size' => 'sm-10',
        		'label_attributes' => array('class' => 'col-sm-2')
        	),
        ));

        $this->add(array(
       		'name' => 'ip',

        	'attributes' => array(
       			'type'  => 'text',
        		'placeholder' => '192.168.1.0',
        	),

        	'options' => array(
        			'label' => 'IP du réseau',
        			'column-size' => 'sm-10',
        			'label_attributes' => array('class' => 'col-sm-2')
       		),
        ));

        $this->add(array(
        	'name' => 'masque',
        	'attributes' => array(
       			'type'  => 'number',
        		'placeholder' => '24',
        		'min' => 2,
        		'max' => 24,
        	),

        	'options' => array(

        		'label' => 'Masque du réseau',
        		'column-size' => 'sm-10',
        		'label_attributes' => array('class' => 'col-sm-2')
       		),
        ));

        $this->add(array(
        	'name' => 'passerelle',
        	'attributes' => array(
       			'type'  => 'text',
        		'placeholder' => '192.168.1.1',
        	),

        	'options' => array(
        		'label' => 'Passerelle du réseau',
        		'column-size' => 'sm-10',
        		'label_attributes' => array('class' => 'col-sm-2')
       		),
        ));

        $this->add(array(
        		'name' => 'couleur',
        		'attributes' => array(
        				'type'  => 'color',
        				//'placeholder' => '192.168.1.1',
        		),

        		'options' => array(
        				'label' => 'Couleur',
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
