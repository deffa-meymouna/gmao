<?php

namespace Administration\Options;

use Zend\Stdlib\AbstractOptions;
use Administration\Options\Exception\ModuleOptionsException;

class ModuleOptions extends AbstractOptions implements ItemsPerPageOptionsInterface{
    /**
     * 
     * @var array
     */
    const DEFAULT_VALUES = [10,25,50,100];
    /**
     * @var array of integer
     */
    protected $itemsPerPage = self::DEFAULT_VALUES;
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

}
