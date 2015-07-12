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
    function __construct()
    {
        parent::__construct('itemsPerPage');
        
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
        //@FIXME ce paramètre class devrait peut-être se trouver ailleurs
        $this->setAttributes([
            'class' => 'input-sm',
            'id' => 'selectItemsPerPage'
        ]);
        //Listing des options
        //@todo Il faudrait aller les chercher dans les fichiers de configuration
        $this->setOptions([
            'value_options' => [
                '10' => ' 10 items',
                '50' => ' 50 items',
                '100' => '100 items'
            ],
            //Le bouton est placé après
            'add-on-append' => $button,
            //Texte, compatible avec Twb-bundle
            'add-on-prepend' => 'Items per page',
        ]);
    }
}