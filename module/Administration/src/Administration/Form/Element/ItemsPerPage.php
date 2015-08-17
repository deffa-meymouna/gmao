<?php
namespace Administration\Form\Element;

use Zend\Form\Element\Button;
use Zend\Form\Element\Select;

/**
 *
 * @author alexandre
 *        
 */
class ItemsPerPage extends Select
{
    /**
     * Constructeur
     */
    function __construct($name = null, $options = array())
    {
        if (empty($name)){
            $name = 'selectItemsPerPage';
        }
        parent::__construct($name,$options);
        
        //Création du bouton qui suivera
        $button = new Button('submit');
        $button->setAttributes([
            'class' => 'input-sm',
            'onclick' => "changement(this);"
        ]);
        //Une icône en guise de texte
        $button->setOptions([
            'fontAwesome' => 'refresh'
        ]);
        $button->setLabel('');
        //Attribut lié à la vue
        $this->setAttributes([
            'class' => 'input-sm',
            'id' => 'selectItemsPerPage'
        ]);
        //Listing des options
        $this->setOptions([
            //Le bouton est placé après
            'add-on-append' => $button,
            //Texte, compatible avec Twb-bundle
            'add-on-prepend' => _('Items per page'),
        ]);
    }
    /**
     * 
     * @param string integers comma separeted
     * @return \Administration\Form\Element\ItemsPerPage
     */
    public function setItemsPerPage($options)
    {
        foreach (explode(',', $options) as $option){
            $integer = (int)$option;
            if ($integer > 0){
                $valueOptions[$option]=sprintf('%i items',$option);
            }
        }
        $this->setValueOptions($valueOptions);
        return $this;
    }


    
}