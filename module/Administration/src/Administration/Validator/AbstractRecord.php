<?php
namespace Administration\Validator;

use ZfcUser\Validator\AbstractRecord as ZfcUserAbstractRecord;
use Administration\Entity\User;

/**
 *
 * @author alexandre
 *        
 */
abstract class AbstractRecord extends ZfcUserAbstractRecord
{
    /**
     * 
     * @var integer
     */
    protected $exceptId = 0;
    /**
     * 
     * @param array $options
     */
    public function __construct(array $options)
    {
        if (array_key_exists('exceptId', $options)) {
            $this->setExceptId($options['exceptId']);
            unset($options['exceptId']);
        }
        parent::__construct($options);
    }
    /**
     * 
     * @param integer $id
     * @return \Administration\Validator\AbstractRecord
     */
    public function setExceptId($id){
        $this->exceptId = (int)$id;
        return $this;
    }
    
    /**
     * Grab the user from the mapper
     *
     * @param string $value
     * @return mixed
     */
    protected function query($value)
    {
        $result = parent::query($value);
        
        //Comportement normal ssi exceptId n'est pas défini
        if(empty($this->exceptId)){
            return $result;
        }
    
        switch ($this->getKey()) {
            case 'email':
                $result = $this->getMapper()->findByEmail($value);
                break;
    
            case 'username':
                $result = $this->getMapper()->findByUsername($value);
                break;
    
            default:
                throw new \Exception('Invalid key used in ZfcUser validator');
                break;
        }
        
        if ($result instanceof User && $this->exceptId == $result->getId()){
            return false;
        }
        return $result;
    }
}
