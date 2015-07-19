<?php
namespace Administration\Form\InputFilter;

use Zend\InputFilter\InputFilter;
use Administration\Service\Users;
use Administration\Validator\NoRecordExists;

/**
 *
 * @author alexandre
 *        
 */
class User extends InputFilter
{
    /**
     * 
     * @var Users
     */
    protected $userRepository;
    /**
     */
    function __construct(Users $userRepository,$exceptId = 0)
    {        
        $this->userRepository = $userRepository;
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
            ),
        ));
        if (!empty($exceptId)){
            $this->exceptId($exceptId);            
        }
    }
    /**
     * 
     * @param integer $exceptId
     * @return \Administration\Form\InputFilter\User
     */
    public function setExceptId($exceptId){
        $this->get('username')->getValidatorChain()->attach(new NoRecordExists(
            array(
                'key' => 'username',
                'mapper' => $this->userRepository,
                'exceptId' => (int)$exceptId,
            )
        ));
        $this->get('email')->getValidatorChain()->attach(new NoRecordExists(
            array(
                'key' => 'email',
                'mapper' => $this->userRepository,
                'exceptId' => (int)$exceptId,
            )
        ));
        return $this;
    }
}

?>