<?php
namespace Administration\Form;

use Zend\Form\Form;

/**
 *
 * @author alexandre
 *        
 */
class UserForm extends Form
{
    /**
     * Constructeur du formulaire d'utilisateur
     * 
     * @param string $name
     * @param array $options
     */
    public function __construct($name = null, $options = array())
    {
        // we want to ignore the name and options passed
        parent::__construct('userForm');
        
        $this->add(array(
            'name' => 'id',
            'type' => 'hidden'
        ));
        $this->add(array(
            'name' => 'username',
            'type' => 'text',
            'options' => array(
                'label' => _('Username'),
                'column-size' => 'sm-10',
                'label_attributes' => array('class' => 'col-sm-2')
            )
        ));
        $this->add(array(
            'name' => 'email',
            'type' => 'email',
            'options' => array(
                'label' => _('Email'),
                'column-size' => 'sm-10',
                'label_attributes' => array('class' => 'col-sm-2')
            )
        ));
        $this->add(array(
            'name' => 'displayName',
            'type' => 'Text',
            'options' => array(
                'label' => _('Display Name'),
                'column-size' => 'sm-10',
                'label_attributes' => array('class' => 'col-sm-2')
            )
        ));        
        $this->add(array(
            'name' => 'submit',
            'type' => 'submit',
            'attributes' => array(
                'value' => _('Save'),
                'id' => 'submitbutton'
            ),
            'options' => array(
                'column-size' => 'sm-10 col-sm-offset-2'
            ),
        ));
    }
}