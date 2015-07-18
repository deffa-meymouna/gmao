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
        parent::__construct('album');
        
        $this->add(array(
            'name' => 'id',
            'type' => 'Hidden'
        ));
        $this->add(array(
            'name' => 'username',
            'type' => 'Text',
            'options' => array(
                'label' => 'Username',
                'column-size' => 'sm-10',
                'label_attributes' => array('class' => 'col-sm-2')
            )
        ));
        $this->add(array(
            'name' => 'email',
            'type' => 'email',
            'options' => array(
                'label' => 'Email',
                'column-size' => 'sm-10',
                'label_attributes' => array('class' => 'col-sm-2')
            )
        ));
        $this->add(array(
            'name' => 'displayName',
            'type' => 'Text',
            'options' => array(
                'label' => 'Display Name',
                'column-size' => 'sm-10',
                'label_attributes' => array('class' => 'col-sm-2')
            )
        ));        
        $this->add(array(
            'name' => 'submit',
            'type' => 'Submit',
            'attributes' => array(
                'value' => 'Save',
                'id' => 'submitbutton'
            ),
            'options' => array(
                'column-size' => 'sm-10 col-sm-offset-2'
            ),
        ));
    }
}

?>