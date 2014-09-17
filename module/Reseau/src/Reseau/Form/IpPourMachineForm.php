<?php
namespace Reseau\Form;

class IpPourMachineForm extends ReservationIpForm
{
    public function __construct()
    {
        parent::__construct();
    	$this->add(array(
    			'name' => 'machine',
    			'type' => '\TwbBundle\Form\Element\StaticElement',
    			'options' => array('label' => 'Machine','column-size' => 'sm-9','label_attributes' => array('class' => 'col-sm-3')
    	)));
        $priority = 1000;
        foreach($this->getElements() as $element){
        	$this->setPriority($element->getName(), $priority--);
        }
    	$this->setPriority('machine', 1100);

        $this->get('submit')->setOption('label', 'Créer cette Ip et l\'associer');
    }
}