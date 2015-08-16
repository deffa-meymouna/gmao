<?php
namespace Administration\Form;

use Zend\Form\Form;

/**
 *
 * @author alexandre
 *        
 */
class RoleForm extends Form
{
    /**
     * Constructeur du formulaire de rôle
     * 
     * @param string $name
     * @param array $options
     */
    public function __construct($name = null, $options = array())
    {
        // we want to ignore the name and options passed
        parent::__construct('role');
        
        $this->add(array(
            'name' => 'id',
            'type' => 'Hidden'
        ));
        $this->add(array(
            'name' => 'roleId',
            'type' => 'Text',
            'options' => array(
                'label' => 'Username',
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