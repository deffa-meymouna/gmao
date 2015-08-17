<?php
namespace Administration\Form;

use Zend\Form\Form;

/**
 *
 * @author alexandre
 *        
 */
class Search extends Form
{

    /**
     */
    function __construct($name = null, $options = array())
    {
        if (empty($name)) {
            $name = 'searchForm';
        }
        parent::__construct($name, $options);
        //Zone de saisie
        $this->add(array(
            'name' => 'search',
            'attributes' => array(
                'type' => 'text',
                'placeholder' => _('Enter search...')
            ),
            'options' => array(
                'label' => _('Search')
            )
        ));
        //J'aimerai bien ne pas mettre de name à mon submit !
        $this->add(array(
            'name' => 's',
            'type' => 'submit',
            'attributes' => array(
                'type' => 'submit'
            ),
            'options' => array(
                'label' => 'Search'
            )
        ));
        //Changement de méthode
        $this->setAttributes(['method' => 'get']);
    }
}