<?php
namespace Administration\Form\InputFilter;

use Zend\InputFilter\InputFilter;

/**
 *
 * @author alexandre
 *        
 */
class User extends InputFilter
{

    /**
     */
    function __construct($userRepository)
    {
        // @todo gérer les doublons
        $this->add(array(
            'name' => 'displayName',
            'required' => false,
            'filters' => array(
                array(
                    'name' => 'StripTags'
                ),
                array(
                    'name' => 'StringTrim'
                )
            ),
            'validators' => array(
                array(
                    'name' => 'StringLength',
                    'options' => array(
                        'encoding' => 'UTF-8',
                        'min' => 1,
                        'max' => 100
                    )
                ),
            )
        ));
        
        $this->add(array(
            'name' => 'email',
            'required' => true,
            'filters' => array(
                array(
                    'name' => 'StripTags'
                ),
                array(
                    'name' => 'StringTrim'
                )
            ),
            'validators' => array(
                array(
                    'name' => 'StringLength',
                    'options' => array(
                        'encoding' => 'UTF-8',
                        'min' => 1,
                        'max' => 100
                    )
                ),
                array(
                    'name' => 'EmailAddress'
                ),
                //@FIXME Parametrer correctement NoRecordExists
                /*array(
                    'name' => \ZfcUser\Validator\NoRecordExists::class,
                    'options' => array(
                        'key' => 'email',
                        'mapper' => $userRepository,
                    ),
                )*/
            )
        ));
        
        $this->add(array(
            'name' => 'username',
            'required' => true,
            'filters' => array(
                array(
                    'name' => 'StripTags'
                ),
                array(
                    'name' => 'StringTrim'
                )
            ),
            'validators' => array(
                array(
                    'name' => 'StringLength',
                    'options' => array(
                        'encoding' => 'UTF-8',
                        'min' => 1,
                        'max' => 100
                    )
                ),
                //@FIXME Parametrer correctement NoRecordExists
                /*array(
                    'name' => \ZfcUser\Validator\NoRecordExists::class,
                    'options' => array(
                        'key' => 'username',
                        'mapper' => $userRepository,
                    )
                ),*/
            ),
        ));
    }
}

?>