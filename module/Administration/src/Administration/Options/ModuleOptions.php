<?php

namespace Administration\Options;

use Zend\Stdlib\AbstractOptions;
use Administration\Options\Exception\ModuleOptionsException;
use Zend\Db\Sql\Ddl\Column\Integer;

class ModuleOptions extends AbstractOptions implements ItemsPerPageOptionsInterface{
    /**
     * 
     * @var array
     */
    const DEFAULT_VALUES = [10,25,50,100];
    /**
     * 
     */
    const DEFAULT_VALUE = 10;
    /**
     * @var array of integer
     */
    protected $itemsPerPage = self::DEFAULT_VALUES;
    /**
     * @var Integer
     */
    protected $itemsPerPageDefault = self::DEFAULT_VALUE;
    /**
     * set items that can be displayed per page
     * 
     * @param string : integers separeted by comma $itemPerPage
     * @throws \Administration\Options\Exception\ModuleOptionsException
     * @return \Administration\Options\ModuleOptions
     */
    public function setItemsPerPage($itemsPerPage = DEFAULT_VALUES)
    {
        $result=array();
        foreach($itemsPerPage as $item){
            $intValue = (int)$item;
            if ($intValue > 0){
                $result[]=$intValue;
            }else{
                throw new ModuleOptionsException(sprintf('setItemsPerPage : %s is not a correct value',$item));
            }
        }        
        if (count($result) < 2){
            throw new ModuleOptionsException('setItemsPerPage : array must contain at least one value');
        }
        sort($result);
        $this->itemsPerPage = $result;
        return $this;
    }

    /**
     * get items that can be displayed per page
     *
     * @return array of integer
     */
    public function getItemsPerPage()
    {
        return $this->itemsPerPage;
    }
	/* (non-PHPdoc)
     * @see \Administration\Options\ItemsPerPageOptionsInterface::getItemsPerPageByDefault()
     */
    public function getItemsPerPageDefault()
    {
        return $this->itemsPerPageDefault;        
    }

	/* (non-PHPdoc)
     * @see \Administration\Options\ItemsPerPageOptionsInterface::setItemsPerPageByDefault()
     */
    public function setItemsPerPageDefault($quantity)
    {
        $result = (int)$quantity;
        if ($result < 1){
            throw new ModuleOptionsException(sprintf('setItemsPerPageDefault : %s is not a positive integer',$quantity));
        }
        $this->itemsPerPageDefault = $result; 
        return $this;
    }

    

}
