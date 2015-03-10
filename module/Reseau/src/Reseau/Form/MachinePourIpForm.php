<?php
namespace Reseau\Form;

class MachinePourIpForm extends MachineForm
{
    public function __construct()
    {
        parent::__construct();
    	$this->add(array(
    			'name' => 'ipString',
    			'type' => '\TwbBundle\Form\Element\StaticElement',
    			'options' => array('label' => 'IP','column-size' => 'sm-10','label_attributes' => array('class' => 'col-sm-2')
    	)));
        $priority = 1000;
        foreach($this->getElements() as $element){
        	$priority = $priority - 20;
        	$this->setPriority($element->getName(),$priority);
        }
    	$this->setPriority('ipString', 950);

        $this->get('submit')->setOption('label', 'Créer cette machine et l\'associer');
    }
}