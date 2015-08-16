<?php
namespace Administration\Form\InputFilter;

use Zend\InputFilter\InputFilter;
use Administration\Service\Roles;
use Administration\Validator\NoRecordExists;

/**
 *
 * @author alexandre
 *        
 */
class Role extends InputFilter
{
    /**
     * 
     * @var Roles
     */
    protected $roleRepository;
    /**
     */
    function __construct(Roles $userRepository,$exceptId = 0)
    {        
        $this->roleRepository = $userRepository;
        $this->add(array(
            'name' => 'roleId',
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
            )
        ));
        
        if (!empty($exceptId)){
            $this->exceptId($exceptId);            
        }
    }
    /**
     * 
     * @param integer $exceptId
     * @return \Administration\Form\InputFilter\Role
     */
    public function setExceptId($exceptId){
        $this->get('roleId')->getValidatorChain()->attach(new NoRecordExists(
            array(
                'key' => 'roleId',
                'mapper' => $this->roleRepository,
                'exceptId' => (int)$exceptId,
            )
        ));
        return $this;
    }
}

?>